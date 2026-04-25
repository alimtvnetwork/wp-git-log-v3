# Allowlist & Wildcard Matching — Endpoint-Level Approval for `POST /logs/push`

**Version:** 1.1.0  
**Updated:** 2026-04-25  
**Status:** Active  
**AI Confidence:** Production-Ready  
**Ambiguity:** Low

---

## Overview

`POST /wp-json/git-logs/v1/logs/push` is **unauthenticated** in the JWT/cookie sense — CI runners do not log in as plugin users — but it is **strictly controlled** by a server-side **allowlist** of approved GitHub repositories combined with a per-repo HMAC envelope JWT.

This document defines the **endpoint-level approval check** that gates that endpoint:

1. The repository must exist in the `Repository` table with `RepositoryStatusId = Active`.
2. The inbound `repoUrl` must match an entry under one of the supported acceptance modes (`RepoUrl`, `OwnerWildcard`) and version modes (`Exact`, `Wildcard`).
3. The envelope JWT must be HS256-signed with the per-repo `LogSenderToken` and not expired.
4. The push must be within the per-repo rate limit.

If any check fails, the request is rejected, audited, and counted toward security telemetry. **No error is swallowed.**

---

## Cross-References

| Reference | Location |
|-----------|----------|
| Database schema (Repository, lookup tables) | [./02-database-schema-and-erd.md](./02-database-schema-and-erd.md) §3.3, §5 |
| Glossary (`logSenderToken`, envelope JWT, wildcard) | [./01-glossary-and-enums.md](./01-glossary-and-enums.md) |
| Logging strategy (allowlist & ingestion events) | [./12-logging-strategy.md](./12-logging-strategy.md) §4.3, §4.4 |
| Log push flow (planned) | [./07-log-push-flow.md](./07-log-push-flow.md) |
| Locked decisions (rate limit, payload cap, HS256) | [./00-overview.md](./00-overview.md) §Locked Decisions |

---

## 1. Endpoint Contract

| Property | Value |
|----------|-------|
| Method | `POST` |
| Path | `/wp-json/git-logs/v1/logs/push` |
| Auth | **None** (no `Authorization: Bearer <userJwt>`) |
| Required header | `X-GitLogs-Envelope: <hs256JwtSignedWithLogSenderToken>` |
| Body content type | `application/json` |
| Max body size | **1 MB** (Locked Decision #5) |
| Rate limit | **60 requests/min per repository** (Locked Decision #6) |
| Provider scope | GitHub only (Locked Decision #9) |

### 1.1 Envelope JWT (HS256)

| Claim | Required | Description |
|-------|----------|-------------|
| `repoUrl` | yes | Canonical GitHub URL of the pushing repo, e.g. `https://github.com/acme/widget-v3` |
| `branch` | yes | Branch name (≤ 255 chars) |
| `pipelineName` | yes | Pipeline identifier (≤ 255 chars) |
| `iat` | yes | Issued-at, unix seconds |
| `exp` | yes | Expiry, unix seconds, ≤ 5 min after `iat` |
| `nonce` | yes | 16+ char random; replay-protection (see §6) |

The envelope is signed with the per-repo `LogSenderToken` (HMAC-SHA256). Only the **hash** of `LogSenderToken` is stored server-side (`Repository.LogSenderTokenHash`, Argon2id).

> Because Argon2id is one-way, the server cannot directly verify HS256 against the stored hash. To bridge this, on every push the server: (a) extracts `repoUrl` from the JWT **header claim** `kid` (or unverified payload — see §3 step 3), (b) loads the candidate `Repository` row, (c) recomputes the verifier as described in §3 step 6.

---

## 2. Approval Model

### 2.1 Acceptance modes (`AcceptanceMode` lookup)

| Code | Meaning |
|------|---------|
| `RepoUrl` | Approval is for an exact `(OwnerName, RepoName)` pair. |
| `OwnerWildcard` | Approval covers **every** repository owned by `OwnerName` (user or org). |

### 2.2 Version modes (`VersionMode` lookup)

| Code | Meaning |
|------|---------|
| `Exact` | The inbound `RepoName` must equal `Repository.RepoName`. |
| `Wildcard` | The inbound `RepoName` must match `^<base>(-v[1-9][0-9]*)?$` where `<base> = Repository.RepoName`. |

### 2.3 Combined matrix

| `AcceptanceMode` | `VersionMode` | Inbound `(owner, repo)` accepted iff |
|------------------|---------------|--------------------------------------|
| `RepoUrl` | `Exact` | `owner = OwnerName` AND `repo = RepoName` |
| `RepoUrl` | `Wildcard` | `owner = OwnerName` AND `repo` matches `^RepoName(-v[1-9][0-9]*)?$` |
| `OwnerWildcard` | `Exact` | `owner = OwnerName` (repo ignored) |
| `OwnerWildcard` | `Wildcard` | `owner = OwnerName` (repo ignored; wildcard implied) |

> `RepoName-v0`, `RepoName-v01`, `RepoName-V2` (uppercase V) are **not** matches. Regex is case-sensitive; comparisons on `OwnerName` and `RepoName` are case-**insensitive** because GitHub identifiers are case-preserving but case-insensitive.

### 2.4 Resolution precedence

When more than one `Repository` row could match (e.g., an `Exact RepoUrl` and an `OwnerWildcard` for the same owner), the resolver MUST select in this order:

1. `RepoUrl` + `Exact` (most specific)
2. `RepoUrl` + `Wildcard`
3. `OwnerWildcard` + `Exact`
4. `OwnerWildcard` + `Wildcard` (least specific)

Within each tier, the row with the **most recent `UpdatedAt`** wins. The chosen row's `RepositoryId` is the one used for HMAC verification, audit attribution, rate limiting, and pipeline auto-creation.

---

## 3. Server Algorithm (per request)

```
1.  Generate / propagate traceId.
2.  Reject if Content-Length > 1 MB                 → 413 PAYLOAD_TOO_LARGE
3.  Parse X-GitLogs-Envelope WITHOUT verifying signature yet.
    Extract: repoUrl, branch, pipelineName, iat, exp, nonce.
    Reject if any required claim missing or malformed → 400 ENVELOPE_MALFORMED
4.  Normalize repoUrl → (provider, owner, repo)     (see §4)
    Reject if provider != GitHub                    → 400 PROVIDER_UNSUPPORTED
5.  Resolve candidate Repository rows per §2 precedence.
    Reject if none                                   → 403 ALLOWLIST_REJECTED_NOT_REGISTERED
    Reject if chosen.RepositoryStatusId != Active    → 403 ALLOWLIST_REJECTED_DISABLED
6.  HMAC verify:
    For the chosen row, the server holds Argon2id(LogSenderToken).
    The server CANNOT recompute LogSenderToken from the hash, so:
    - The server stores BOTH:
        (a) Repository.LogSenderTokenHash       (Argon2id, for offline audits / future migrations)
        (b) Repository.LogSenderTokenVerifier   (HMAC-SHA256 key wrapped with WP AUTH_KEY)
    - Use (b) to verify the HS256 envelope signature.
    Reject on bad signature                          → 401 ENVELOPE_BAD_SIGNATURE
    Reject if exp < now - 60s skew                   → 401 ENVELOPE_EXPIRED
    Reject if exp - iat > 300s                       → 401 ENVELOPE_TTL_TOO_LONG
    Reject if nonce already seen for this repo in last 10 min → 401 ENVELOPE_REPLAYED
7.  Rate limit check (§5).
    Reject if exceeded                                → 429 RATE_LIMITED
8.  Auto-create / fetch Pipeline (RepositoryId, BranchName, PipelineName).
9.  Validate body schema; persist LogEntry rows in a single transaction.
    On any DB error                                   → 500 INTERNAL_ERROR (re-throw, do not swallow)
10. Write ONE terminal AuditTrail row (LogPush, Success|Rejected|Error).
11. Respond 202 Accepted with { traceId, persistedCount, rejectedCount }.
```

> **Note on step 6:** The original spec said only `LogSenderTokenHash` (Argon2id) is stored. That alone makes HS256 verification mathematically impossible. This document amends the schema requirement to also persist a wrapped HMAC key. See §9 Open Item OI-ALLOW-01 for the alternative (asymmetric envelope, e.g., Ed25519 with public key stored in DB) which avoids storing any verifier key at all.

---

## 4. `repoUrl` Normalization

The same GitHub repo can be expressed many ways. The resolver MUST normalize before matching.

| Input | Normalized `(provider, owner, repo)` |
|-------|--------------------------------------|
| `https://github.com/Acme/Widget` | `(GitHub, acme, widget)` |
| `https://github.com/Acme/Widget.git` | `(GitHub, acme, widget)` |
| `https://github.com/Acme/Widget/` | `(GitHub, acme, widget)` |
| `git@github.com:Acme/Widget.git` | `(GitHub, acme, widget)` |
| `ssh://git@github.com/Acme/Widget` | `(GitHub, acme, widget)` |
| `https://github.com/Acme/Widget/tree/main` | **REJECT** (path components beyond `<owner>/<repo>` not allowed) |
| `https://gitlab.com/Acme/Widget` | **REJECT** (provider not supported in v1) |

Normalization rules:

1. Lowercase scheme/host comparison; strip trailing `/`, strip trailing `.git`.
2. Recognized hosts: `github.com`, `www.github.com`, `api.github.com` (path adjusted to `/repos/<owner>/<repo>`).
3. Lowercase `owner` and `repo` for matching only — original casing is logged in `AuditTrail.DetailsJson.originalRepoUrl`.
4. Reject any path with > 2 segments after the host.

---

## 5. Rate Limit (per repository)

| Property | Value |
|----------|-------|
| Window | 60 seconds, sliding |
| Cap | 60 requests |
| Bucket key | `gitlogs:rl:repo:<RepositoryId>` |
| Substrate | If `wp_using_ext_object_cache()` → atomic `INCR` with TTL; else dedicated `RateLimit` table with row-level lock (per `12-logging-strategy.md` §16). |
| Response on overage | `429 RATE_LIMITED` with `Retry-After` seconds until window reset. |

The bucket is keyed by **`RepositoryId` after resolution**, not by `OwnerName/RepoName`, so an `OwnerWildcard` org-wide approval shares one bucket across all its repos. This is intentional: it caps the blast radius of a leaked org-level `LogSenderToken`.

---

## 6. Replay & Nonce Protection

- Every envelope MUST carry a `nonce` of ≥ 16 chars (recommend UUIDv4).
- The server stores `(RepositoryId, nonce, exp)` in a short-lived store (object cache or `EnvelopeNonce` table) for the **larger** of (envelope `exp` window) and 10 minutes.
- A second push with the same `(RepositoryId, nonce)` within that window → `401 ENVELOPE_REPLAYED`, severity `Error`, emits `SuspectedReplayAttack` security event.

---

## 7. Admin Approval Flow (how a repo gets onto the allowlist)

1. WP admin opens `/wp-admin/admin.php?page=git-logs-repositories`.
2. Clicks **Add Repository**, fills:
   - `OwnerName` (GitHub login, case preserved)
   - `RepoName` (or leave blank if `AcceptanceMode = OwnerWildcard`)
   - `OwnerType` (`User` | `Organization`)
   - `AcceptanceMode` (`RepoUrl` | `OwnerWildcard`)
   - `VersionMode` (`Exact` | `Wildcard`)
   - Optional `EndpointUrl`, `Description`
3. Server validates uniqueness against `IdxRepository_OwnerRepo`.
4. Server generates a fresh 32-byte `LogSenderToken`, computes:
   - `LogSenderTokenHash = Argon2id(token)` (long-term audit anchor)
   - `LogSenderTokenVerifier = AEAD-Encrypt(token, key = derive(AUTH_KEY, RepositoryId))` (used for HS256 verification per §3 step 6)
5. Inserts `Repository` row with `RepositoryStatusId = Active`, `IsActive = 1`.
6. Writes `AuditTrail (RepoCreate, Success)`.
7. Returns the raw `LogSenderToken` to the admin **once**, with explicit "shown only now" UX warning.
8. Admin pastes the token into the CI runner's secret store (e.g., GitHub Actions secret `GITLOGS_SENDER_TOKEN`).

> Disabling a repo: admin sets `RepositoryStatusId = Disabled`. Subsequent pushes are rejected (`ALLOWLIST_REJECTED_DISABLED`) but historical logs remain queryable.

> Rotating a `LogSenderToken`: admin clicks **Rotate Token**; both the new and old verifier are accepted for a configurable grace window (default 24 h) to allow CI to roll over.

---

## 8. Audit Events Emitted

| Event | When | `AuditOutcome` | Severity |
|-------|------|----------------|----------|
| `AllowlistMatched_Exact` | §3 step 5, tier 1 hit | continues | `Info` |
| `AllowlistMatched_VersionWildcard` | tier 2 hit | continues | `Info` |
| `AllowlistMatched_OwnerWildcard` | tier 3 or 4 hit | continues | `Info` |
| `AllowlistRejected_NotRegistered` | no row matched | `Rejected` | `Warn` |
| `AllowlistRejected_Disabled` | row matched but Disabled | `Rejected` | `Warn` |
| `EnvelopeJwtVerified` | step 6 success | continues | `Info` |
| `EnvelopeJwtRejected_BadHmac` | bad signature | `Rejected` | `Warn` |
| `EnvelopeJwtRejected_Expired` | expired | `Rejected` | `Info` |
| `EnvelopeJwtRejected_Replayed` | nonce reuse | `Rejected` | `Error` + `SuspectedReplayAttack` |
| `PayloadCapExceeded` | step 2 | `Rejected` | `Warn` |
| `RateLimitExceeded` | step 7 | `Rejected` | `Warn` |
| `LogEntriesPersisted` | step 9 success | `Success` | `Info` |
| `IngestionFailed_DbError` | step 9 DB error | `Error` | `Error` |

Each request produces exactly one **terminal** `AuditTrail` row at step 10 in addition to the informational events above (per `12-logging-strategy.md` §4.3 rule).

---

## 9. Acceptance Criteria

| # | Criterion |
|---|-----------|
| AC-ALW-01 | A push for an `(owner, repo)` with no matching `Repository` row returns `403 ALLOWLIST_REJECTED_NOT_REGISTERED` and writes one `LogPush`-`Rejected` audit row. |
| AC-ALW-02 | A push for a matching but `Disabled` repo returns `403 ALLOWLIST_REJECTED_DISABLED` and is auditable. |
| AC-ALW-03 | A push for `acme/widget-v7` is accepted iff a `Repository` row with `OwnerName=acme, RepoName=widget, VersionMode=Wildcard, AcceptanceMode=RepoUrl` exists and is Active. |
| AC-ALW-04 | A push for `acme/anything` is accepted iff a `Repository` row with `OwnerName=acme, AcceptanceMode=OwnerWildcard` exists and is Active. |
| AC-ALW-05 | When an `Exact RepoUrl` row and an `OwnerWildcard` row both could match, the `Exact RepoUrl` row is chosen (precedence §2.4). |
| AC-ALW-06 | An envelope JWT signed with the wrong `LogSenderToken` returns `401 ENVELOPE_BAD_SIGNATURE`. |
| AC-ALW-07 | An envelope JWT replay (same nonce within 10 min) returns `401 ENVELOPE_REPLAYED`, revokes nothing, but emits a `SuspectedReplayAttack` event. |
| AC-ALW-08 | The 61st push within 60 s for the same `RepositoryId` returns `429 RATE_LIMITED` with a `Retry-After` header. |
| AC-ALW-09 | A request body > 1 MB returns `413 PAYLOAD_TOO_LARGE` **before** any DB write. |
| AC-ALW-10 | All normalization variants in §4 row 1–5 resolve to the same `RepositoryId`. |
| AC-ALW-11 | Provider != `GitHub` returns `400 PROVIDER_UNSUPPORTED`. |
| AC-ALW-12 | Successful push returns `202 Accepted` with `{ traceId, persistedCount, rejectedCount }` and writes exactly one `LogPush`-`Success` audit row regardless of how many `LogEntry` rows were inserted. |

---

## 10. Open Items

| # | Item | Notes |
|---|------|-------|
| OI-ALLOW-01 | HS256 verifier storage | Section 3 step 6 introduces `Repository.LogSenderTokenVerifier` (AEAD-encrypted). Alternative: switch envelope to **Ed25519 / RS256** so only a public key is stored — eliminates symmetric secret at rest. Decide before implementation; affects schema. |
| OI-ALLOW-02 | Token rotation grace window | Default 24 h; should it be admin-configurable per-repo? |
| OI-ALLOW-03 | `OwnerWildcard` + per-repo rate limit | Currently buckets by resolved `RepositoryId`; should an `OwnerWildcard` row instead bucket by `(RepositoryId, owner, repo-from-payload)` to avoid one noisy repo starving siblings? |
| OI-ALLOW-04 | Whether to expose a `/repos/check` dry-run endpoint for CI to validate config | UX nicety; needs rate limit of its own. |
| OI-ALLOW-05 | Schema amendment | If OI-ALLOW-01 chooses AEAD verifier, add column `LogSenderTokenVerifier VARBINARY(255) NULL` to `Repository` and bump schema to v3.0.0. |

---

*Endpoint-level approval for unauthenticated, controlled CI/CD log ingestion. No error swallowed; every decision audited.*
