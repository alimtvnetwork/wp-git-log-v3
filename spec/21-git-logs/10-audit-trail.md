# Audit Trail — Schema, Write Rules, Query API

**Version:** 1.0.0  
**Updated:** 2026-04-25  
**Status:** Active  
**AI Confidence:** Production-Ready  
**Ambiguity:** Low

---

## Purpose

`AuditTrail` is the append-only, immutable record of every endpoint call and every business transaction performed by the plugin. It is the single source of truth for "did the request happen, what did it do, and what was the outcome".

This document defines:

1. The schema (canonical, mirroring [`02-database-schema-and-erd.md`](./02-database-schema-and-erd.md) §3.6).
2. The write contract (when to write, what to write, no-swallow rules).
3. The denormalized columns and their purpose.
4. The query API (`VwAuditTrailDetail`, retention, pagination).
5. Cross-references to logging, errors, JWT, and push flows.

---

## 1. Schema (Authoritative)

### Columns

The `AuditTrail` schema is defined in [`02-database-schema-and-erd.md` §3.6](./02-database-schema-and-erd.md). This document does not duplicate the table DDL — it describes the **semantics** of each column. Schema bumps belong in `02`.

| Column | Semantic role |
|---|---|
| `AuditTrailId` | Stable PK; never recycled. |
| `ActorUserId` | Plugin `User.UserId`. NULL only for anonymous log push (per allowlist) and unauthenticated probes. |
| `ActorWordPressUserId` | `wp_users.ID` for WP-bridge actions; coexists with `ActorUserId` once the bridge has provisioned a plugin user. |
| `RepositoryId` | Set whenever the action is repo-scoped (`LogPush`, `LogQuery`, `RepoCreate`, etc.). |
| `AuditActionTypeId` | One of the 12 codes in [`01-glossary-and-enums.md` §AuditActionType](./01-glossary-and-enums.md). |
| `AuditOutcomeId` | `Success` / `Rejected` / `Error`. Final state only — never intermediate. |
| `EndpointPath` | Always the **route path** (`/git-logs/v1/...`), never a full URL. |
| `HttpMethod`, `HttpStatus` | Final response status, after error-envelope mapping. |
| `RequestIp` | Resolved via the trusted-proxy chain in [`12-logging-strategy.md` §6](./12-logging-strategy.md). |
| `UserAgent` | Raw header, redacted only if it contains tokens (see redaction rules below). |
| `IsSuccessful` | Mirrors `AuditOutcomeId = Success`. Denormalized for fast filters. |
| `DetailsJson` | Structured event payload. **Authoritative format** is in [`12-logging-strategy.md` §3](./12-logging-strategy.md). |
| `Notes` | Reviewer remarks (audit-only Rule 11 variant). Never written by the plugin itself. |
| `CreatedAt` | Server time. Indexed. |

### `RevokedJti` companion table

The JWT revocation denylist referenced by [`16-jwt-onboarding-and-token-usage.md`](./16-jwt-onboarding-and-token-usage.md) is **not** part of `AuditTrail`. It belongs in its own table; see [`02-database-schema-and-erd.md` §3.7 (to be added)](./02-database-schema-and-erd.md) and finding [`F-03`](../22-app-issues/02-consolidated-audit-findings/00-overview.md). This document does not duplicate that schema.

---

## 2. Write Contract

### 2.1 Cardinality

| Rule | Statement |
|---|---|
| W1 | **Exactly one** terminal `AuditTrail` row per endpoint call, including 4xx and 5xx. |
| W2 | **Exactly one** terminal `AuditTrail` row per business transaction (`TokenIssue`, `RepoCreate`, etc.). |
| W3 | Bulk operations (`LogPush` carrying many `LogEntry` rows) write **one** row regardless of the entry count. |
| W4 | Intermediate progress / debug events go to the structured log stream only — never to `AuditTrail`. |
| W5 | The `AuditTrail` insert MUST NOT block the response on failure. On insert failure, mirror the JSON to PHP `error_log` and continue (per `12` §7.2). |

### 2.2 No-swallow

Every controller MUST wrap its body in `try`/`catch`/`finally`:

```
try {
    // business logic
    $outcome = AuditOutcome::Success;
} catch (Throwable $e) {
    $outcome = AuditOutcome::Error;
    AppErrorLogger::record($e, $traceId);  // see 11-error-management.md §4
    throw $e;                              // never swallow
} finally {
    AuditTrailWriter::write([
        'AuditActionTypeId' => $actionTypeId,
        'AuditOutcomeId'    => $outcome->value,
        'HttpStatus'        => $httpStatus,
        'DetailsJson'       => RedactionFilter::apply($details),
        // ...denormalized columns
    ]);
}
```

The `finally` block guarantees that even uncaught exceptions produce one terminal row.

### 2.3 Redaction

Before any value lands in `DetailsJson` it MUST pass through `RedactionFilter`:

| Field pattern | Action |
|---|---|
| `Authorization`, `X-GitLogs-Envelope` headers | Replace with `"<redacted>"` |
| `password`, `token`, `secret`, `jwt` (case-insensitive substring of key) | Replace value with `"<redacted>"` |
| Email addresses | Mask local-part beyond first 2 chars (`al***@example.com`) |
| Plain JWTs detected by regex `^[A-Za-z0-9_-]+\.[A-Za-z0-9_-]+\.[A-Za-z0-9_-]+$` | Replace with `"<jwt:redacted>"` |
| Argon2id-hashed values already in DB | Pass-through (already irreversible) |

Same rules apply to the `error_log` mirror.

### 2.4 Trace correlation

Every row's `DetailsJson.traceId` MUST equal:
1. The W3C `Traceparent.trace-id` if present and well-formed; else
2. The validated `X-Request-Id` header; else
3. A freshly generated ULID.

The chosen value is echoed in the `X-Request-Id` response header and forwarded to outbound HTTP calls (e.g., GitHub API).

---

## 3. Action × Outcome Matrix

| `AuditActionType` | Typical `AuditOutcome` | Endpoint(s) | Notes |
|---|---|---|---|
| `UserCreate` | Success / Rejected / Error | `POST /users`, WP-bridge auto-provision | One row per call |
| `UserUpdate` | Success / Rejected / Error | `PATCH /users/{id}` | |
| `UserDelete` | Success / Rejected / Error | `DELETE /users/{id}` | Soft-delete preferred |
| `TokenIssue` | Success / Rejected / Error | `POST /auth/token`, `POST /auth/refresh` | |
| `TokenRevoke` | Success / Error | `POST /auth/logout`, admin revoke | Adds JTI to denylist |
| `RepoCreate` | Success / Rejected / Error | `POST /repositories` | |
| `RepoUpdate` | Success / Rejected / Error | `PATCH /repositories/{id}` | |
| `RepoDelete` | Success / Rejected / Error | `DELETE /repositories/{id}` | |
| `LogPush` | Success / Rejected / Error | `POST /logs/push` | One row even when many `LogEntry` rows persisted |
| `LogQuery` | Success / Rejected / Error | `GET /logs`, `GET /pipelines/{id}/entries` | |
| `AuthSuccess` | Success | Any auth-bearing route | Written in addition to the action row |
| `AuthFail` | Rejected / Error | Any failed auth | Written instead of the action row |

`AuthSuccess` is recorded once per endpoint hit only when the auth check is non-trivial (JWT, App Password, cookie nonce). For anonymous log push the `LogPush` row carries `ActorUserId = NULL` and the result of allowlist matching is in `DetailsJson`.

---

## 4. Query API

### 4.1 View

The view `VwAuditTrailDetail` (defined in [`02-database-schema-and-erd.md` §6.2](./02-database-schema-and-erd.md)) joins `AuditTrail` with lookup tables to produce human-readable rows.

### 4.2 Read endpoints

| Endpoint | Auth | Description |
|---|---|---|
| `GET /wp-json/git-logs/v1/audit` | JWT (`Admin`) or WP capability `manage_options` | Paginated audit list |
| `GET /wp-json/git-logs/v1/audit/{id}` | Same | Single row, full `DetailsJson` |

Filter parameters (all optional, AND-combined):

| Param | Type | Maps to |
|---|---|---|
| `actorUserId` | int | `AuditTrail.ActorUserId` |
| `repositoryId` | int | `AuditTrail.RepositoryId` |
| `action` | string (enum code) | `AuditActionType.Code` |
| `outcome` | string (enum code) | `AuditOutcome.Code` |
| `httpStatus` | int | `AuditTrail.HttpStatus` |
| `from`, `to` | RFC3339 | `AuditTrail.CreatedAt` range |
| `traceId` | string | `JSON_EXTRACT(DetailsJson, '$.traceId')` (indexed via virtual column) |

Pagination contract is the standard cursor pagination defined in [`04-rest-api-endpoints.md`](./04-rest-api-endpoints.md) §Pagination.

### 4.3 Retention

Per [Locked Decision #4](./00-overview.md), retention is **indefinite** in v1. Future archival to cold storage is reserved; partition the table monthly by `CreatedAt` to enable it (finding [`F-12`](../22-app-issues/02-consolidated-audit-findings/00-overview.md)).

---

## 5. Immutability

| Operation | Allowed? |
|---|---|
| `INSERT` | Yes (only by `AuditTrailWriter`) |
| `UPDATE` | **No.** The DB user used by the plugin MUST NOT have UPDATE on `AuditTrail`. |
| `DELETE` | **No.** Same. Archival uses `INSERT … SELECT` into a sibling cold table. |

Reviewer remarks belong in the audit-only `Notes` column and are written by a separate maintenance UI with elevated permissions; they are **not** edits to the original event payload.

---

## 6. Acceptance Criteria

| ID | Given | When | Then |
|---|---|---|---|
| AC-AUD-01 | Any REST endpoint completes (any HTTP status) | The response is sent | Exactly one `AuditTrail` row exists with matching `traceId` |
| AC-AUD-02 | A `POST /logs/push` carrying 500 `LogEntry` rows succeeds | The request finishes | One `AuditTrail` row with `AuditActionType=LogPush, AuditOutcome=Success` |
| AC-AUD-03 | The `AuditTrail` insert fails (DB down) | The request finishes | The same JSON appears in `error_log`, the response is still 2xx/4xx/5xx as applicable |
| AC-AUD-04 | A controller throws an uncaught exception | The `finally` block runs | One row with `AuditOutcome=Error` is written and the exception is re-thrown |
| AC-AUD-05 | A row's `DetailsJson` is inspected | A field name matches a redaction rule | The value is `<redacted>` |
| AC-AUD-06 | A WP-bridge call provisions a plugin user | Provisioning succeeds | Both `ActorWordPressUserId` and `ActorUserId` are populated |
| AC-AUD-07 | The plugin DB role attempts `UPDATE AuditTrail …` | The query runs | The DB rejects with permission error |
| AC-AUD-08 | A reader queries `GET /audit?traceId=...` | A row matches | The row is returned with all columns |

---

## 7. Cross-References

| Reference | Location |
|---|---|
| Authoritative schema (DDL) | [02-database-schema-and-erd.md §3.6](./02-database-schema-and-erd.md) |
| Logging strategy + redaction | [12-logging-strategy.md](./12-logging-strategy.md) |
| Error envelope & no-swallow | [11-error-management.md](./11-error-management.md) |
| JWT lifecycle (drives action codes) | [16-jwt-onboarding-and-token-usage.md](./16-jwt-onboarding-and-token-usage.md) |
| Push flow (`LogPush` action) | [07-log-push-flow.md](./07-log-push-flow.md) |
| Retrieval flow (`LogQuery` action) | [09-log-retrieval-flow.md](./09-log-retrieval-flow.md) |
| REST endpoint contracts | [04-rest-api-endpoints.md](./04-rest-api-endpoints.md) |
| Glossary & enums | [01-glossary-and-enums.md](./01-glossary-and-enums.md) |
| Open finding (immutable + partitioning) | [F-12](../22-app-issues/02-consolidated-audit-findings/00-overview.md) |
