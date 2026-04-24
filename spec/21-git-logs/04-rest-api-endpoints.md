# REST API Endpoints — `git-logs/v1`

**Version:** 1.0.0  
**Updated:** 2026-04-25  
**Status:** Active  
**AI Confidence:** Production-Ready  
**Ambiguity:** Low  
**Namespace:** `/wp-json/git-logs/v1`

---

## Purpose

Single source of truth for every REST route the plugin exposes. Each route specifies: HTTP method, auth class, request schema, success envelope, error envelope, rate-limit class, and the `AuditActionType` written for it. All response bodies use the **PascalCase JSON envelope** defined in [`11-error-management.md` §1](./11-error-management.md).

---

## 1. Auth Classes

| Class | Header / Mechanism | Verified by |
|---|---|---|
| `Anonymous` | None | Allowlist (envelope JWT) — only applicable to log push |
| `JwtBearer` | `Authorization: Bearer <RS256-jwt>` | [`05-auth-jwt-flow.md`](./05-auth-jwt-flow.md) |
| `WpAppPassword` | `Authorization: Basic <user:appPassword>` | [`06-auth-wordpress-bridge.md`](./06-auth-wordpress-bridge.md) |
| `WpCookie` | WP nonce + cookie | Same |
| `JwtBearer + AdminRole` | Same as JwtBearer, requires `Admin` role | Role check after JWT verify |

Routes that accept multiple classes try them in declared order; the first that succeeds wins.

---

## 2. Common Headers

| Header | Direction | Purpose |
|---|---|---|
| `X-Request-Id` | Inbound (optional) / Outbound (always) | Trace correlation; precedence per [`12-logging-strategy.md` §3](./12-logging-strategy.md) |
| `Traceparent` | Inbound (optional) | W3C trace context; takes precedence over `X-Request-Id` |
| `Authorization` | Inbound | Per auth class |
| `X-GitLogs-Envelope` | Inbound (push only) | Per-repo HS256 envelope JWT |
| `Content-Encoding: gzip` | Inbound (push only) | Permitted; size cap is post-decompression |
| `RateLimit-*` | Outbound | IETF draft rate-limit headers (`Limit`, `Remaining`, `Reset`) |

---

## 3. Pagination

All list endpoints use **cursor pagination**:

| Param | Type | Default | Max |
|---|---|---|---|
| `pageSize` | int | 50 | 200 |
| `cursor` | opaque string | — | — |
| `sort` | string | endpoint-specific | endpoint-specific |

Response envelope `Attributes` carries `NextCursor` (null on last page) and `HasMore` (boolean).

---

## 4. Endpoint Catalog

### 4.1 Authentication

| # | Route | Method | Auth | Rate Limit | Action |
|---|---|---|---|---|---|
| E01 | `/auth/token` | POST | `WpAppPassword` ∪ `WpCookie` | `auth-strict` (5/min/IP) | `TokenIssue` |
| E02 | `/auth/refresh` | POST | `Anonymous` (refresh token in body) | `auth-strict` | `TokenIssue` |
| E03 | `/auth/logout` | POST | `JwtBearer` | `auth-normal` (60/min/user) | `TokenRevoke` |
| E04 | `/.well-known/jwks.json` | GET | `Anonymous` | `public` (600/min/IP) | none |

#### E01 `POST /auth/token`

Request body:

```json
{ "Username": "alice", "Password": "<wp-app-password>" }
```

Or `WpCookie` auth with empty body.

Success `200`:

```json
{
  "Status": "ok",
  "Attributes": { "SessionId": "01HXYZ...", "ExpiresIn": 86400 },
  "Results": {
    "AccessToken": "<rs256-jwt>",
    "RefreshToken": "<opaque>",
    "TokenType": "Bearer"
  },
  "Errors": null,
  "MethodsStack": null
}
```

Error codes used: `GL-AUTH-001`, `GL-AUTH-003`, `GL-AUTH-LOCKED`. See [`11-error-management.md`](./11-error-management.md).

#### E02 `POST /auth/refresh`

Request: `{ "RefreshToken": "<opaque>" }`. Implements rotating single-use refresh with 5-second idempotency window per [`16-jwt-onboarding-and-token-usage.md` §6.4](./16-jwt-onboarding-and-token-usage.md).

#### E03 `POST /auth/logout`

Empty body. Revokes the refresh chain and adds the access JWT's `jti` to the `RevokedJti` denylist for the remaining `exp`.

#### E04 `GET /.well-known/jwks.json`

Public JWKS, returning the active and (during overlap) prior public keys. **Exempt** from the standard envelope — returns the raw JWKS object as required by RFC 7517.

---

### 4.2 Repositories

| # | Route | Method | Auth | Rate Limit | Action |
|---|---|---|---|---|---|
| E05 | `/repositories` | GET | `JwtBearer` | `read-normal` (300/min/user) | `LogQuery` |
| E06 | `/repositories` | POST | `JwtBearer` (`Admin` ∪ `CanAddRepo`) | `write-normal` (60/min/user) | `RepoCreate` |
| E07 | `/repositories/{id}` | GET | `JwtBearer` | `read-normal` | none |
| E08 | `/repositories/{id}` | PATCH | `JwtBearer` (`Admin` ∪ `CanAddRepo`) | `write-normal` | `RepoUpdate` |
| E09 | `/repositories/{id}` | DELETE | `JwtBearer` (`Admin`) | `write-normal` | `RepoDelete` |
| E10 | `/repositories/{id}/rotate-token` | POST | `JwtBearer` (`Admin`) | `write-strict` (10/min/user) | `RepoUpdate` |

POST body for E06:

```json
{
  "Provider": "GitHub",
  "OwnerType": "User",
  "OwnerName": "octocat",
  "RepoName": "hello-world",
  "VersionMode": "Wildcard",
  "AcceptanceMode": "RepoUrl",
  "Description": "Public demo"
}
```

Response includes the freshly generated `LogSenderToken` **once**:

```json
{
  "Status": "ok",
  "Attributes": { "SessionId": "..." },
  "Results": {
    "RepositoryId": 42,
    "LogSenderToken": "<shown-once>",
    "LogSenderTokenShownAt": "2026-04-25T12:00:00Z"
  },
  "Errors": null
}
```

E10 returns the new token plus a `GraceWindowEndsAt` so CI can roll over.

---

### 4.3 Logs (Ingestion)

| # | Route | Method | Auth | Rate Limit | Action |
|---|---|---|---|---|---|
| E11 | `/logs/push` | POST | `Anonymous` + `X-GitLogs-Envelope` | `push` (60/min/repo) | `LogPush` |

Headers: `X-GitLogs-Envelope: <hs256-or-ed25519-envelope-jwt>`.  
Body (max 1 MB **decompressed**):

```json
{
  "RepoUrl": "https://github.com/octocat/hello-world",
  "Branch": "main",
  "PipelineName": "build",
  "CommitSha": "a1b2c3...",
  "Entries": [
    { "Severity": "Info", "Message": "...", "OccurredAt": "...", "MetadataJson": {} }
  ]
}
```

Allowlist resolution per [`08-allowlist-and-wildcard-matching.md`](./08-allowlist-and-wildcard-matching.md). End-to-end push flow per [`07-log-push-flow.md`](./07-log-push-flow.md).

Error codes used: `GL-PUSH-001..009`, `GL-RATE-*`. Always returns exactly one `LogPush` `AuditTrail` row regardless of `Entries` length.

---

### 4.4 Logs (Retrieval)

| # | Route | Method | Auth | Rate Limit | Action |
|---|---|---|---|---|---|
| E12 | `/repositories/{id}/branches` | GET | `JwtBearer` (`CanViewLogs`) | `read-normal` | `LogQuery` |
| E13 | `/repositories/{id}/branches/{branch}/pipelines` | GET | `JwtBearer` (`CanViewLogs`) | `read-normal` | `LogQuery` |
| E14 | `/pipelines/{id}/entries` | GET | `JwtBearer` (`CanViewLogs`) | `read-normal` | `LogQuery` |
| E15 | `/logs/search` | GET | `JwtBearer` (`CanViewLogs`) | `read-normal` | `LogQuery` |

Full retrieval semantics in [`09-log-retrieval-flow.md`](./09-log-retrieval-flow.md).

---

### 4.5 Users

| # | Route | Method | Auth | Rate Limit | Action |
|---|---|---|---|---|---|
| E16 | `/users` | GET | `JwtBearer` (`Admin`) | `read-normal` | none |
| E17 | `/users` | POST | `JwtBearer` (`Admin` ∪ `CanAddUser`) | `write-normal` | `UserCreate` |
| E18 | `/users/{id}` | PATCH | `JwtBearer` (`Admin` ∪ `CanAddUser`) | `write-normal` | `UserUpdate` |
| E19 | `/users/{id}` | DELETE | `JwtBearer` (`Admin`) | `write-normal` | `UserDelete` |
| E20 | `/users/{id}/rotate-token` | POST | `JwtBearer` (`Admin`) | `write-strict` | `TokenIssue` |

---

### 4.6 Audit

| # | Route | Method | Auth | Rate Limit | Action |
|---|---|---|---|---|---|
| E21 | `/audit` | GET | `JwtBearer` (`Admin`) ∪ `WpCookie` (`manage_options`) | `read-normal` | none |
| E22 | `/audit/{id}` | GET | Same as E21 | `read-normal` | none |

Filter params and view contract in [`10-audit-trail.md` §4.2](./10-audit-trail.md).

---

## 5. Rate-Limit Classes

| Class | Limit | Window | Bucket key |
|---|---|---|---|
| `auth-strict` | 5 | 60 s | `Ip` |
| `auth-normal` | 60 | 60 s | `UserId` |
| `read-normal` | 300 | 60 s | `UserId` |
| `write-normal` | 60 | 60 s | `UserId` |
| `write-strict` | 10 | 60 s | `UserId` |
| `push` | 60 | 60 s | `RepositoryId` (post-resolution) |
| `public` | 600 | 60 s | `Ip` |

Storage backend per [Locked Decision #6](./00-overview.md) and finding [`F-13`](../22-app-issues/02-consolidated-audit-findings/00-overview.md).

---

## 6. Error Envelope

Every error response (except E04) returns:

```json
{
  "Status": "error",
  "Attributes": { "SessionId": "01HXYZ..." },
  "Results": null,
  "Errors": [
    { "Code": "GL-AUTH-001", "Message": "Invalid credentials.", "Field": null }
  ],
  "MethodsStack": null
}
```

`MethodsStack` is populated only when `gitlogs_debug_responses = 1` AND the caller has `manage_options`. See [`11-error-management.md`](./11-error-management.md).

---

## 7. Acceptance Criteria

| ID | Given | When | Then |
|---|---|---|---|
| AC-API-01 | Every route in §4 | Called with valid auth | Returns the documented success envelope |
| AC-API-02 | E04 (`jwks.json`) | Called | Returns the raw JWKS object (no envelope) |
| AC-API-03 | Any route receives no auth | Called | Returns `401` with `GL-AUTH-001` |
| AC-API-04 | Any route exceeds its rate-limit | Called | Returns `429` with `GL-RATE-001` and `RateLimit-*` headers |
| AC-API-05 | E10/E20 (rotate token) | Called by non-admin | Returns `403` with `GL-AUTH-005` |
| AC-API-06 | E11 with `Content-Length > 1 MB` (decompressed) | Called | Returns `413` with `GL-PUSH-009` **before** any DB write |
| AC-API-07 | Any route | Returns | Response includes `X-Request-Id` matching `AuditTrail.DetailsJson.traceId` |
| AC-API-08 | E14 with cursor pagination | Two consecutive calls | The second call's first row equals the row immediately after the first call's last |

---

## 8. Cross-References

| Reference | Location |
|---|---|
| Auth (JWT) | [05-auth-jwt-flow.md](./05-auth-jwt-flow.md) |
| Auth (WP bridge) | [06-auth-wordpress-bridge.md](./06-auth-wordpress-bridge.md) |
| Push flow | [07-log-push-flow.md](./07-log-push-flow.md) |
| Allowlist | [08-allowlist-and-wildcard-matching.md](./08-allowlist-and-wildcard-matching.md) |
| Retrieval | [09-log-retrieval-flow.md](./09-log-retrieval-flow.md) |
| Audit trail | [10-audit-trail.md](./10-audit-trail.md) |
| Errors & envelopes | [11-error-management.md](./11-error-management.md) |
| Logging | [12-logging-strategy.md](./12-logging-strategy.md) |
| Glossary & enums | [01-glossary-and-enums.md](./01-glossary-and-enums.md) |
| REST API format (PascalCase JSON) | [../04-database-conventions/06-rest-api-format.md](../04-database-conventions/06-rest-api-format.md) |
