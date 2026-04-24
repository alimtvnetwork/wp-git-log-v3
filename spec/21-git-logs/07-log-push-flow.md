# Log Push Flow

**Version:** 1.0.0  
**Updated:** 2026-04-25  
**Status:** Active  
**AI Confidence:** Production-Ready  
**Ambiguity:** Low  
**Endpoint:** `POST /wp-json/git-logs/v1/logs/push`

---

## Purpose

End-to-end flow for the unauthenticated CI/CD log push endpoint. The endpoint is **anonymous** (no JWT, no WP cookie) but **gated** by a per-repository envelope JWT, the allowlist resolver, a strict body cap, and a per-repository rate limit.

This document is the orchestration layer. Allowlist matching rules live in [`08-allowlist-and-wildcard-matching.md`](./08-allowlist-and-wildcard-matching.md); REST contract in [`04-rest-api-endpoints.md`](./04-rest-api-endpoints.md) §E11; envelope cryptography in [`05-auth-jwt-flow.md`](./05-auth-jwt-flow.md) and finding [`F-02`](../22-app-issues/02-consolidated-audit-findings/00-overview.md).

---

## 1. Inputs

### Headers

| Header | Required | Notes |
|---|---|---|
| `Content-Type` | Yes | `application/json` (or `application/json; charset=utf-8`) |
| `Content-Encoding` | No | `gzip` permitted; cap is post-decompression |
| `Content-Length` | Yes when body is not chunked | Pre-checked against 1 MB |
| `X-GitLogs-Envelope` | Yes | Envelope JWT — see §3 |
| `X-Request-Id` | No | Trace correlation; precedence per [`12` §3](./12-logging-strategy.md) |
| `Traceparent` | No | Same |
| `User-Agent` | No | Logged for forensics; redacted if it contains tokens |

### Body (JSON)

```json
{
  "RepoUrl": "https://github.com/octocat/hello-world",
  "Branch": "main",
  "PipelineName": "build",
  "CommitSha": "a1b2c3d4...",
  "Entries": [
    {
      "Severity": "Info",
      "Message": "Started build #42",
      "OccurredAt": "2026-04-25T12:00:00Z",
      "MetadataJson": { "Job": "build", "Step": "compile" }
    }
  ]
}
```

| Field | Type | Constraints |
|---|---|---|
| `RepoUrl` | string | HTTPS GitHub URL; normalized per `08` §2 |
| `Branch` | string | 1–255 chars |
| `PipelineName` | string | 1–255 chars |
| `CommitSha` | string | NULL or 7–40 hex chars |
| `Entries` | array | 1–10,000 items |
| `Entries[].Severity` | enum | One of `LogSeverity` codes |
| `Entries[].Message` | string | 1–65,535 chars |
| `Entries[].OccurredAt` | RFC3339 | Required |
| `Entries[].MetadataJson` | object | Optional, ≤ 16 KB serialized |

---

## 2. Flow

```
┌─────────┐  1  ┌────────┐  2  ┌────────┐  3  ┌──────┐  4  ┌──────┐  5  ┌─────┐
│ CI / CD │────►│ Edge   │────►│ Body   │────►│ JWT  │────►│ Allow│────►│ DB  │
│ runner  │     │ checks │     │ parse  │     │ verify│     │ list │     │     │
└─────────┘     └────────┘     └────────┘     └──────┘     └──────┘     └─────┘
                                                                         │
                                                                  6      ▼
                                                                 ┌────────────────┐
                                                                 │ AuditTrail row │
                                                                 └────────────────┘
```

### Step 1 — Edge checks (before parsing)

| Check | Failure → HTTP / Code |
|---|---|
| `Content-Type` is JSON | `415 GL-PUSH-001` |
| `Content-Length` ≤ 1 MB | `413 GL-PUSH-009` |
| `X-GitLogs-Envelope` present | `401 GL-PUSH-002` |
| Body present | `400 GL-PUSH-003` |

If `Content-Length` is absent (chunked), proceed to step 2 with a streaming reader that aborts at 1 MB decompressed.

### Step 2 — Decompress + parse

- If `Content-Encoding: gzip`, stream-decompress with a hard 1 MB cap. Cap exceeded → `413 GL-PUSH-009` (one `LogPush, Rejected` audit row).
- JSON-decode the result. Parse failure → `400 GL-PUSH-003`.

### Step 3 — Envelope JWT verify

Per [`08` §3 step 6](./08-allowlist-and-wildcard-matching.md):
1. Decode JWT header → extract `kid` (which carries the `repoUrl`).
2. Decode payload (still untrusted at this point) → extract `repoUrl`, `branch`, `pipelineName`, `exp`.
3. Run allowlist resolution (step 4) using the `repoUrl` from the payload.
4. Load the resolved `Repository` row and recompute the verifier (per F-02 decision).
5. Verify the JWT signature against the verifier; reject with `401 GL-PUSH-004` on mismatch.
6. Reject if `exp` < now − 60 s skew → `401 GL-PUSH-005`.
7. Reject if the `nonce` claim has been seen in the last 10 min for this repo → `401 GL-PUSH-006`.

### Step 4 — Allowlist resolution

Apply the 4-tier precedence in [`08` §2](./08-allowlist-and-wildcard-matching.md):
1. `RepoUrl` + `Exact`
2. `RepoUrl` + `Wildcard`
3. `OwnerWildcard` + `Exact`
4. `OwnerWildcard` + `Wildcard`

If no match → `403 GL-PUSH-007` (one `LogPush, Rejected`).

### Step 5 — Rate limit (post-resolution)

Bucket key = resolved `RepositoryId`. Class `push` (60/min). Exceed → `429 GL-RATE-001` with `RateLimit-*` headers.

### Step 6 — Persist

Inside one DB transaction:
1. UPSERT `Pipeline` row by `(RepositoryId, BranchName, PipelineName)`.
2. INSERT all `Entries` as `LogEntry` rows referencing the pipeline.
3. COMMIT.

Persistence failure (transient DB error) → `503 GL-SYS-001` after one retry. Single `LogPush, Error` audit row.

### Step 7 — Respond

Success `202`:

```json
{
  "Status": "ok",
  "Attributes": { "SessionId": "01HXYZ..." },
  "Results": {
    "RepositoryId": 42,
    "PipelineId": 17,
    "EntriesAccepted": 35,
    "ReceivedAt": "2026-04-25T12:00:00Z"
  },
  "Errors": null,
  "MethodsStack": null
}
```

### Step 8 — Audit (in `finally`)

Exactly one `AuditTrail` row with `AuditActionType=LogPush` and the matching outcome. `DetailsJson` carries:

```json
{
  "traceId": "...",
  "originalRepoUrl": "...",
  "matchedRepositoryId": 42,
  "matchKind": "RepoUrlExact",
  "entryCount": 35,
  "bytesAfterDecompression": 8217,
  "rejectReason": null
}
```

---

## 3. Envelope JWT Claims

Header (HS256 or Ed25519 per F-02):
```json
{ "alg": "HS256", "typ": "JWT", "kid": "https://github.com/octocat/hello-world" }
```

Payload:
```json
{
  "repoUrl": "https://github.com/octocat/hello-world",
  "branch": "main",
  "pipelineName": "build",
  "iat": 1714050000,
  "exp": 1714050120,
  "nonce": "01HXYZABCDEF..."
}
```

`exp - iat` MUST be ≤ 120 s. `nonce` MUST be ≥ 16 chars; replays within a 10-min window are rejected.

---

## 4. Idempotency

The endpoint is **not** idempotent at the message level — re-pushing the same payload produces duplicate `LogEntry` rows. Clients that need idempotency MUST include a unique `nonce` per push and rely on the 10-min replay window. The response header `X-GitLogs-Push-Token-Reused: true` is set when the nonce is rejected.

---

## 5. Failure Code Cheat Sheet

| Code | HTTP | Reason |
|---|---|---|
| `GL-PUSH-001` | 415 | `Content-Type` not JSON |
| `GL-PUSH-002` | 401 | `X-GitLogs-Envelope` missing |
| `GL-PUSH-003` | 400 | Body missing or invalid JSON |
| `GL-PUSH-004` | 401 | Envelope signature invalid |
| `GL-PUSH-005` | 401 | Envelope expired |
| `GL-PUSH-006` | 401 | Envelope nonce replay detected |
| `GL-PUSH-007` | 403 | Repo not in allowlist |
| `GL-PUSH-008` | 422 | Schema validation failure (unknown field, bad enum, etc.) |
| `GL-PUSH-009` | 413 | Body > 1 MB (decompressed) |
| `GL-RATE-001` | 429 | Push rate limit exceeded |
| `GL-SYS-001` | 503 | Transient DB error after one retry |

---

## 6. Acceptance Criteria

| ID | Given | When | Then |
|---|---|---|---|
| AC-PUSH-01 | Valid envelope + matched repo | `POST /logs/push` with 50 entries | Returns `202` and persists 50 `LogEntry` rows |
| AC-PUSH-02 | Body > 1 MB after gzip decompression | Same | Returns `413 GL-PUSH-009` **before** any DB write |
| AC-PUSH-03 | Envelope `exp` 5 min in the past | Same | Returns `401 GL-PUSH-005` |
| AC-PUSH-04 | Envelope `nonce` reused within 10 min | Same | Returns `401 GL-PUSH-006` |
| AC-PUSH-05 | Repo URL has no allowlist match | Same | Returns `403 GL-PUSH-007` |
| AC-PUSH-06 | 61st request in 60 s for same repo | Same | Returns `429 GL-RATE-001` with `RateLimit-Reset` |
| AC-PUSH-07 | Any outcome | Response sent | Exactly one `AuditTrail (LogPush, …)` row written |
| AC-PUSH-08 | Anonymous request | Successful push | `AuditTrail.ActorUserId IS NULL` |
| AC-PUSH-09 | Chunked transfer encoding without `Content-Length` | Body decompresses to 700 KB | Returns `202` |
| AC-PUSH-10 | Invalid `Severity` enum value | Same | Returns `422 GL-PUSH-008` with `Field = "Entries[3].Severity"` |

---

## 7. Cross-References

| Reference | Location |
|---|---|
| Allowlist matching | [08-allowlist-and-wildcard-matching.md](./08-allowlist-and-wildcard-matching.md) |
| Endpoint catalog | [04-rest-api-endpoints.md](./04-rest-api-endpoints.md) §E11 |
| Envelope cryptography decision | [F-02](../22-app-issues/02-consolidated-audit-findings/00-overview.md) |
| JWT verification primitives | [05-auth-jwt-flow.md](./05-auth-jwt-flow.md) |
| Audit trail | [10-audit-trail.md](./10-audit-trail.md) |
| Error envelope | [11-error-management.md](./11-error-management.md) |
| Logging | [12-logging-strategy.md](./12-logging-strategy.md) |
