# Error Management — Plugin-Wide Contract

**Version:** 1.0.0  
**Updated:** 2026-04-24  
**Status:** Draft  
**AI Confidence:** Medium  
**Ambiguity:** Low

---

## Overview

This document is the **single source of truth for error responses, error codes, and correlation IDs** across every route exposed by the `git-logs` WordPress plugin. It applies the foundational error-management contract from `spec/03-error-manage/` to the plugin's PHP/REST context.

Three guarantees:

1. **Every** plugin response — success or failure — uses the **Universal Response Envelope** (PascalCase keys, `Status` / `Attributes` / `Results` / optional `Errors` / optional `MethodsStack`).
2. **Every** response carries a **correlation ID** (`TraceId`) that propagates from the inbound request through every log line, every `AuditTrail` row, and back to the client in the `X-Request-Id` response header.
3. **No error is ever swallowed** — every `catch` block either logs-and-rethrows or logs-and-returns-a-registered-error. Empty catches and the `@` suppression operator are forbidden.

---

## Cross-References

| Reference | Location |
|-----------|----------|
| Universal Response Envelope (canonical) | [../03-error-manage/02-error-architecture/05-response-envelope/04-response-envelope-reference.md](../03-error-manage/02-error-architecture/05-response-envelope/04-response-envelope-reference.md) |
| Error Code Registry (project-wide) | [../03-error-manage/03-error-code-registry/01-registry.md](../03-error-manage/03-error-code-registry/01-registry.md) |
| Error Architecture (3-tier) | [../03-error-manage/02-error-architecture/01-error-handling-reference.md](../03-error-manage/02-error-architecture/01-error-handling-reference.md) |
| Logging strategy (TraceId, redaction, no-swallow) | [./12-logging-strategy.md](./12-logging-strategy.md) |
| JWT onboarding (auth error codes appear here) | [./16-jwt-onboarding-and-token-usage.md](./16-jwt-onboarding-and-token-usage.md) |
| Allowlist (push-error codes appear here) | [./08-allowlist-and-wildcard-matching.md](./08-allowlist-and-wildcard-matching.md) |
| Audit trail schema | [./02-database-schema-and-erd.md](./02-database-schema-and-erd.md) §3.6 |

---

## 1. Hard Rules (Non-Negotiable)

| # | Rule |
|---|------|
| R1 | Every response body MUST be the Universal Response Envelope. No raw JSON, no `WP_Error` returned bare to the client, no plain strings. |
| R2 | Every response MUST include `Attributes.SessionId` (correlation ID, see §3) and `Attributes.RequestedAt`. |
| R3 | Every response MUST set the `X-Request-Id` HTTP header to the same correlation ID. |
| R4 | Every error response MUST carry a registered code in `Errors.BackendMessage` formatted as `[<CODE>] <human message>`. |
| R5 | `Code` registration: a code that doesn't exist in §6 of this document MUST NOT be returned. New codes are added here first. |
| R6 | Empty `catch (\Throwable $e) {}`, silent-fallback `catch (\Throwable $e) { return null; }`, and the `@` suppression operator are **forbidden** (lint-enforced). |
| R7 | The HTTP status code in the response header MUST equal `Status.Code` in the body. |
| R8 | Stack traces are emitted **only when** `gitlogs_debug_responses = 1` (WP option) AND the requester is an Admin. Production responses to non-Admins MUST omit `MethodsStack` and `Errors.Backend`. |
| R9 | Secrets (raw tokens, JWTs, refresh tokens, `LogSenderToken`, `Authorization` header values, passwords) MUST never appear in any envelope field. Redaction per `12-logging-strategy.md` §6. |
| R10 | Every terminal response MUST be paired with exactly one `AuditTrail` row whose `DetailsJson.traceId` equals `Attributes.SessionId`. |

---

## 2. Universal Response Envelope (applied to git-logs)

PascalCase keys, exactly as defined in the canonical reference.

### 2.1 Success — single resource

```json
{
  "Status": {
    "IsSuccess": true,
    "IsFailed": false,
    "Code": 200,
    "Message": "OK",
    "Timestamp": "2026-04-24T08:11:42.117Z"
  },
  "Attributes": {
    "RequestedAt": "https://example.com/wp-json/git-logs/v1/repositories/42",
    "SessionId": "5b3e0fd2-9b4a-4a18-b2b6-cb2c2c8cd571",
    "HasAnyErrors": false,
    "IsSingle": true,
    "IsMultiple": false,
    "IsEmpty": false,
    "TotalRecords": 1,
    "PerPage": 0,
    "TotalPages": 0,
    "CurrentPage": 0
  },
  "Results": [
    {
      "RepositoryId": 42,
      "OwnerName": "acme",
      "RepoName": "widget",
      "RepositoryStatusName": "Active",
      "AcceptanceModeName": "RepoUrl",
      "VersionModeName": "Wildcard",
      "CreatedAt": "2026-04-22T10:14:01.000Z"
    }
  ]
}
```

### 2.2 Error — registered code, production (no stack)

```json
{
  "Status": {
    "IsSuccess": false,
    "IsFailed": true,
    "Code": 401,
    "Message": "[GL-AUTH-002] Access JWT is invalid or expired.",
    "Timestamp": "2026-04-24T08:12:03.881Z"
  },
  "Attributes": {
    "RequestedAt": "https://example.com/wp-json/git-logs/v1/logs",
    "SessionId": "f2c11b5e-4406-49c9-bff1-3a5a6d9b7044",
    "HasAnyErrors": true,
    "IsSingle": false,
    "IsMultiple": false,
    "IsEmpty": true,
    "TotalRecords": 0,
    "PerPage": 0,
    "TotalPages": 0,
    "CurrentPage": 0
  },
  "Results": [],
  "Errors": {
    "BackendMessage": "[GL-AUTH-002] Access JWT is invalid or expired.",
    "DelegatedServiceErrorStack": [],
    "Backend": [],
    "Frontend": [],
    "DelegatedRequestServer": null,
    "ValidationErrors": []
  }
}
```

### 2.3 Error — validation failure, multiple field errors

```json
{
  "Status": {
    "IsSuccess": false,
    "IsFailed": true,
    "Code": 422,
    "Message": "[GL-VAL-001] Request validation failed.",
    "Timestamp": "2026-04-24T08:13:11.000Z"
  },
  "Attributes": {
    "RequestedAt": "https://example.com/wp-json/git-logs/v1/repositories",
    "SessionId": "9d11c0b1-71ab-43d3-9c0f-ee9a7f5e2210",
    "HasAnyErrors": true,
    "IsSingle": false, "IsMultiple": false, "IsEmpty": true,
    "TotalRecords": 0, "PerPage": 0, "TotalPages": 0, "CurrentPage": 0
  },
  "Results": [],
  "Errors": {
    "BackendMessage": "[GL-VAL-001] Request validation failed.",
    "ValidationErrors": [
      { "Field": "OwnerName", "Code": "GL-VAL-002", "Message": "OwnerName is required." },
      { "Field": "AcceptanceModeName", "Code": "GL-VAL-003", "Message": "AcceptanceModeName must be one of: RepoUrl, OwnerWildcard." }
    ]
  }
}
```

### 2.4 Error — admin-debug variant (stack and `MethodsStack` included)

Only when `gitlogs_debug_responses = 1` AND requester has the `Admin` role.

```json
{
  "Status": { "IsSuccess": false, "IsFailed": true, "Code": 500, "Message": "[GL-INT-001] Internal error during log persistence.", "Timestamp": "..." },
  "Attributes": { "SessionId": "...", "RequestedAt": "...", "HasAnyErrors": true, "IsEmpty": true, "TotalRecords": 0 },
  "Results": [],
  "Errors": {
    "BackendMessage": "[GL-INT-001] Internal error during log persistence.",
    "Backend": [
      "wp-content/plugins/git-logs/src/Http/Controllers/LogPushController.php:142 LogPushController->handle",
      "wp-content/plugins/git-logs/src/Domain/Logs/IngestionService.php:78 IngestionService->persist"
    ]
  },
  "MethodsStack": {
    "Backend": [
      { "Method": "LogPushController->handle", "File": ".../LogPushController.php", "LineNumber": 142 },
      { "Method": "IngestionService->persist", "File": ".../IngestionService.php", "LineNumber": 78 }
    ]
  }
}
```

---

## 3. Correlation ID (`TraceId` / `SessionId`)

| Aspect | Rule |
|--------|------|
| Source | `X-Request-Id` inbound header → `Traceparent` (W3C) → server-generated `wp_generate_uuid4()`. (Same chain as `12-logging-strategy.md` §2.) |
| Format | Server-generated values are UUIDv4. Inbound values accepted only if they match `^[A-Za-z0-9._-]{8,128}$`; otherwise overwritten. |
| Propagation | Set as `Attributes.SessionId` in every envelope; echoed as `X-Request-Id` response header; stored as `AuditTrail.DetailsJson.traceId`; included in every `error_log` mirror line. |
| Logging | Every internal log line for the request carries the same `traceId`. |
| Naming | The envelope field is `SessionId` (matching the canonical reference). The internal/log-side name is `traceId`. They are the same value with different field names per layer. |

> **Rationale for two names:** `SessionId` is the public envelope label (kept identical to the foundational spec). `traceId` is the internal/log/AuditTrail label. The plugin MUST NOT diverge these values.

---

## 4. PHP Implementation Contract

### 4.1 Centralized error-to-envelope mapping

Every controller delegates response construction to a single `ErrorEnvelopeMapper` (one class, one place to edit). Controllers MUST NOT hand-craft envelopes.

```php
final class ErrorEnvelopeMapper {
    public function fromAppError(AppError $e, RequestContext $ctx): WP_REST_Response {
        $payload = [
            'Status' => [
                'IsSuccess' => false,
                'IsFailed'  => true,
                'Code'      => $e->httpStatus(),
                'Message'   => sprintf('[%s] %s', $e->code(), $e->publicMessage()),
                'Timestamp' => gmdate('Y-m-d\TH:i:s\Z'),
            ],
            'Attributes' => [
                'RequestedAt'  => $ctx->fullUrl(),
                'SessionId'    => $ctx->traceId(),
                'HasAnyErrors' => true,
                'IsSingle'     => false,
                'IsMultiple'   => false,
                'IsEmpty'      => true,
                'TotalRecords' => 0,
                'PerPage'      => 0,
                'TotalPages'   => 0,
                'CurrentPage'  => 0,
            ],
            'Results' => [],
            'Errors'  => [
                'BackendMessage'   => sprintf('[%s] %s', $e->code(), $e->publicMessage()),
                'ValidationErrors' => $e->validationErrors(),
            ],
        ];

        if ($ctx->isDebugEnabledForActor()) {
            $payload['Errors']['Backend']     = $e->stackFramesShort();
            $payload['MethodsStack']          = ['Backend' => $e->stackFramesStructured()];
        }

        $resp = new WP_REST_Response($payload, $e->httpStatus());
        $resp->header('X-Request-Id', $ctx->traceId());
        $resp->header('Content-Type', 'application/json');
        return $resp;
    }
}
```

### 4.2 `AppError` — registered-code wrapper

Every domain failure throws an `AppError` (or a subclass). `AppError`:

- Carries one of the registered codes from §6 (constructor validates against the registry).
- Carries an HTTP status (default per code in §6).
- Carries a `publicMessage` (safe to show users; never includes secrets).
- Carries an internal `cause` chain (full message + previous exception) for logging.
- Carries optional `validationErrors[]`.

```php
throw new AppError(
    code: 'GL-AUTH-002',
    publicMessage: 'Access JWT is invalid or expired.',
    httpStatus: 401,
    cause: $previous
);
```

### 4.3 No-swallow `try`/`catch` template (mandatory shape)

```php
try {
    return $this->doWork($input);
} catch (AppError $e) {
    $this->logger->logEvent('OperationRejected', LogSeverity::Warn, [
        'errorCode'    => $e->code(),
        'errorMessage' => $e->publicMessage(),
    ]);
    throw $e; // controller maps via ErrorEnvelopeMapper
} catch (\Throwable $e) {
    $wrapped = new AppError(
        code: 'GL-INT-001',
        publicMessage: 'Internal server error. See SessionId for diagnostics.',
        httpStatus: 500,
        cause: $e
    );
    $this->logger->logEvent('OperationFailed', LogSeverity::Error, [
        'errorCode'    => $wrapped->code(),
        'errorMessage' => $e->getMessage(),
        'errorStack'   => $e->getTraceAsString(),
    ]);
    throw $wrapped;
}
```

### 4.4 Forbidden patterns (lint rules)

- `catch (\Throwable $e) {}` — empty catch.
- `catch (\Throwable $e) { return null; }` / `return false;` / `return [];` — silent fallback.
- `@function_call(...)` — error suppression operator.
- `set_error_handler(function() { return true; })` — global swallow.
- Returning `WP_Error` directly to the client (must be mapped to envelope).
- Throwing string codes (`throw new AppError(code: 'random-string', ...)`) — code must exist in §6.
- Mutating `Attributes.SessionId` after the request entry middleware sets it.

### 4.5 Middleware order (REST request lifecycle)

```
1. TraceMiddleware      — resolves/generates SessionId, sets X-Request-Id on response.
2. LoggingMiddleware    — emits EndpointReceived (12-logging-strategy.md §4.1).
3. AuthMiddleware       — JWT or WP-bridge or envelope-JWT (per route).
4. RbacMiddleware       — role check.
5. RateLimitMiddleware  — per-route bucket.
6. ValidationMiddleware — body/query schema.
7. Controller           — business action.
8. ErrorEnvelopeMiddleware — catches every AppError + Throwable, returns envelope.
9. AuditMiddleware      — writes the one terminal AuditTrail row.
10. LoggingMiddleware (exit) — emits EndpointCompleted.
```

`ErrorEnvelopeMiddleware` MUST be the outermost catch (after TraceMiddleware). Even if step 9 itself fails, step 1 has already set `X-Request-Id` and step 8 returns a degraded envelope referencing the same `SessionId`.

---

## 5. Coverage — Every Route Returns an Envelope

| Route | Method | Success body | Error envelope on |
|-------|--------|--------------|-------------------|
| `/users` | `POST` | created user | validation, RBAC, conflict |
| `/users` | `GET` | list | RBAC |
| `/users/{id}` | `GET` / `PATCH` / `DELETE` | resource / 204 | not-found, RBAC, validation |
| `/users/{id}/token:rotate` | `POST` | new rawToken | not-found, RBAC |
| `/auth/token` | `POST` | accessToken + refreshToken | invalid creds, locked, rate-limited |
| `/auth/refresh` | `POST` | rotated tokens | reuse, expired, revoked, rate-limited |
| `/auth/logout` | `POST` | empty | invalid JWT |
| `/.well-known/jwks.json` | `GET` | JWKS doc *(raw, see §5.1)* | n/a |
| `/repositories` | `POST` / `GET` | created / list | validation, RBAC, conflict |
| `/repositories/{id}` | `GET` / `PATCH` / `DELETE` | resource / 204 | not-found, RBAC |
| `/repositories/{id}/sender-token:rotate` | `POST` | new rawToken | not-found, RBAC |
| `/logs/push` | `POST` | `202 Accepted` envelope | every reason in §6 GL-PUSH-* |
| `/logs` | `GET` | paginated list | RBAC, validation |
| `/logs/{id}` | `GET` | single entry | not-found, RBAC |
| `/audit-trail` | `GET` | paginated list | RBAC |
| `/health` | `GET` | health snapshot | n/a |

### 5.1 Single documented exception — JWKS

`/.well-known/jwks.json` MUST return the **raw JWKS document** (not wrapped) because external JWT libraries expect the standard shape. The `X-Request-Id` header is still set. This is the **only** route exempt from envelope wrapping. If a JWKS request errors, an envelope IS returned with an appropriate code.

---

## 6. Plugin Error Code Registry

Codes are namespaced `GL-<DOMAIN>-<NNN>`. Every code listed below MUST be added to the central registry at `../03-error-manage/03-error-code-registry/error-codes-master.json` before being returned.

### 6.1 Auth — `GL-AUTH-*`

| Code | HTTP | Public message | When |
|------|------|----------------|------|
| `GL-AUTH-001` | 401 | Authentication failed. | Wrong username/rawToken on `POST /auth/token`. |
| `GL-AUTH-002` | 401 | Access JWT is invalid or expired. | JWT signature, exp, iss, aud, kid failures. |
| `GL-AUTH-003` | 401 | Refresh token has been used or revoked. | Refresh-token reuse-detection (chain revoked). |
| `GL-AUTH-004` | 401 | Refresh token has expired. | `ExpiresAt < NOW`. |
| `GL-AUTH-005` | 423 | Account is temporarily locked. | `User.IsLocked = 1`. |
| `GL-AUTH-006` | 401 | WordPress credentials are invalid. | WP App Password / cookie+nonce failure. |
| `GL-AUTH-007` | 401 | Authorization header missing or malformed. | Bearer token absent or wrong scheme. |

### 6.2 RBAC — `GL-RBAC-*`

| Code | HTTP | Public message | When |
|------|------|----------------|------|
| `GL-RBAC-001` | 403 | The required role is not granted to this user. | RBAC check failure. |

### 6.3 Validation — `GL-VAL-*`

| Code | HTTP | Public message | When |
|------|------|----------------|------|
| `GL-VAL-001` | 422 | Request validation failed. | Generic; populate `ValidationErrors[]`. |
| `GL-VAL-002` | 422 | A required field is missing. | Per-field. |
| `GL-VAL-003` | 422 | A field value is not in the allowed set. | Per-field enum. |
| `GL-VAL-004` | 422 | A field value exceeds the allowed length. | Per-field length. |
| `GL-VAL-005` | 422 | A field value has an invalid format. | Per-field regex/format. |

### 6.4 Push / Allowlist — `GL-PUSH-*`

| Code | HTTP | Public message | When |
|------|------|----------------|------|
| `GL-PUSH-001` | 403 | Repository is not in the allowlist. | `ALLOWLIST_REJECTED_NOT_REGISTERED`. |
| `GL-PUSH-002` | 403 | Repository is disabled. | `ALLOWLIST_REJECTED_DISABLED`. |
| `GL-PUSH-003` | 401 | Envelope JWT signature is invalid. | `ENVELOPE_BAD_SIGNATURE`. |
| `GL-PUSH-004` | 401 | Envelope JWT has expired. | `ENVELOPE_EXPIRED`. |
| `GL-PUSH-005` | 401 | Envelope JWT TTL is too long. | `ENVELOPE_TTL_TOO_LONG`. |
| `GL-PUSH-006` | 401 | Envelope JWT replay detected. | `ENVELOPE_REPLAYED`. |
| `GL-PUSH-007` | 400 | Envelope JWT is malformed. | Missing/invalid claims. |
| `GL-PUSH-008` | 400 | Provider is not supported. | Non-GitHub URL. |
| `GL-PUSH-009` | 413 | Request body exceeds 1 MB. | `PAYLOAD_TOO_LARGE`. |

### 6.5 Resource — `GL-RES-*`

| Code | HTTP | Public message | When |
|------|------|----------------|------|
| `GL-RES-001` | 404 | Resource not found. | Generic. |
| `GL-RES-002` | 409 | Resource already exists. | Unique-key collision (e.g., duplicate repo). |

### 6.6 Rate Limit — `GL-RATE-*`

| Code | HTTP | Public message | When |
|------|------|----------------|------|
| `GL-RATE-001` | 429 | Too many requests. | Per-route or per-repo bucket exceeded. Includes `Retry-After` header. |

### 6.7 Internal — `GL-INT-*`

| Code | HTTP | Public message | When |
|------|------|----------------|------|
| `GL-INT-001` | 500 | Internal server error. See SessionId for diagnostics. | Uncaught `Throwable`. |
| `GL-INT-002` | 500 | Audit log write failed; operation completed. | `AuditTrail` insert failed (mirrored to `error_log`). |
| `GL-INT-003` | 503 | Plugin database is temporarily unavailable. | `wpdb` connection lost. |

### 6.8 Convention for adding new codes

1. Add the row here (this document is authoritative for plugin-specific codes).
2. Add the entry to `error-codes-master.json` with `Domain = git-logs`.
3. Run the overlap validator script (`../03-error-manage/03-error-code-registry/05-overlap-validator.md`).
4. Bump this file's version (semver patch for new codes within an existing class; minor for a new class).

---

## 7. HTTP Status ↔ Envelope `Status.Code` Mapping

| HTTP | Used for |
|------|----------|
| 200 | Success (read, update). |
| 201 | Resource created. |
| 202 | `POST /logs/push` accepted. |
| 204 | Success with no body — body MUST still be the envelope (`Results: []`, `IsEmpty: true`). |
| 400 | Malformed request (envelope claim missing, provider unsupported). |
| 401 | Auth/JWT/envelope failure. |
| 403 | RBAC or allowlist rejection. |
| 404 | Resource not found. |
| 409 | Resource conflict. |
| 413 | Payload too large. |
| 422 | Validation failure. |
| 423 | Account locked. |
| 429 | Rate limit. |
| 500 | Uncaught / internal. |
| 503 | DB unavailable. |

---

## 8. Acceptance Criteria

| # | Criterion |
|---|-----------|
| AC-ERR-01 | Every plugin route except `/.well-known/jwks.json` returns the Universal Response Envelope on every HTTP status. |
| AC-ERR-02 | Every response carries `Attributes.SessionId` and an `X-Request-Id` header equal to that value. |
| AC-ERR-03 | The `X-Request-Id` value matches `AuditTrail.DetailsJson.traceId` for the same request. |
| AC-ERR-04 | Every error response's `Status.Message` and `Errors.BackendMessage` start with `[<CODE>]` where `<CODE>` is registered in §6. |
| AC-ERR-05 | A 500 response in production never contains `Errors.Backend` or `MethodsStack`. |
| AC-ERR-06 | A 500 response with `gitlogs_debug_responses = 1` AND Admin requester contains both `Errors.Backend` and `MethodsStack`. |
| AC-ERR-07 | No raw token, JWT, refresh token, `LogSenderToken`, password, or full `Authorization` header value appears anywhere in the envelope. Verified by a redaction lint over fixture responses. |
| AC-ERR-08 | Empty `catch` blocks and `@` suppression are absent from the codebase. Verified by lint. |
| AC-ERR-09 | A validation failure on multiple fields returns `422 GL-VAL-001` with one entry per failing field in `Errors.ValidationErrors`. |
| AC-ERR-10 | A request with `X-Request-Id: existing-id` reuses that id end-to-end (envelope, header, audit, logs) when the value matches the format whitelist; otherwise the server-generated id is used. |
| AC-ERR-11 | When `AuditTrail` insert fails, the response is still the success/failure envelope of the originating operation (not a 500), and `GL-INT-002` is mirrored to `error_log` with the same `SessionId`. |
| AC-ERR-12 | The `HTTP Status` header always equals `Status.Code` in the body. |

---

## 9. Open Items

| # | Item | Notes |
|---|------|-------|
| OI-ERR-01 | Should `ValidationErrors[]` use `Field` (PascalCase per envelope) or `field` (JSON Schema style)? Currently PascalCase. Confirm against the canonical envelope schema. |
| OI-ERR-02 | Whether `GL-INT-002` (audit-write failure) should also surface a header (e.g., `X-GitLogs-Audit-Degraded: 1`) so monitoring can react. |
| OI-ERR-03 | Whether `Attributes.RequestDelegatedAt` is ever set by this plugin. The plugin does not delegate to a Go backend — likely always omitted/empty. Document explicitly. |
| OI-ERR-04 | Where to store revoked-`jti` denylist for `GL-AUTH-002` evaluation under load (shared with `16-jwt-…` OI-JWT-02). |

---

*Plugin-wide error envelope, registered codes, and end-to-end correlation IDs. No error swallowed. Single mapper class. Lint-enforced.*
