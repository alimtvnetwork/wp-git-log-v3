# JWT Issuance, Verification, JWKS — Auth Flow

**Version:** 1.0.0  
**Updated:** 2026-04-25  
**Status:** Active  
**AI Confidence:** Production-Ready  
**Ambiguity:** Low

---

## Purpose

Operationalises [Locked Decision #1](./00-overview.md): RS256-signed JWTs issued by the plugin and published via JWKS. Defines keypair generation, encrypted-at-rest storage of the private key, JWKS payload shape, `kid` strategy, rotation cadence, and dual-key overlap. Lifecycle behaviour (onboarding, refresh, logout) lives in [`16-jwt-onboarding-and-token-usage.md`](./16-jwt-onboarding-and-token-usage.md); this document is the cryptographic backbone.

---

## 1. Token Anatomy

### 1.1 Access JWT

| Field | Source | Notes |
|---|---|---|
| `alg` | constant `"RS256"` | Header |
| `kid` | active key id | Header — required for verification |
| `typ` | constant `"JWT"` | Header |
| `iss` | `https://{site}/wp-json/git-logs/v1` | Payload |
| `aud` | constant `"git-logs"` | Payload |
| `sub` | `User.UserId` (string) | Payload |
| `roles` | array of `Role.Code` strings | Payload |
| `iat` | UNIX seconds | Payload |
| `exp` | `iat + 86400` (24 h) | Payload |
| `nbf` | `iat - 5` | Payload |
| `jti` | UUIDv4 | Payload — used for revocation |

### 1.2 Refresh token

Refresh tokens are **opaque** (not JWTs) — high-entropy 32-byte base64url strings. Argon2id hashes are persisted in `RefreshToken.TokenHash`. Lifecycle in [`16`](./16-jwt-onboarding-and-token-usage.md).

---

## 2. Keypair Lifecycle

### 2.1 Generation

A keypair is **2048-bit RSA** generated via `openssl_pkey_new(['private_key_bits' => 2048, 'private_key_type' => OPENSSL_KEYTYPE_RSA])`. The private key is exported as PEM; the public key as JWK.

### 2.2 Storage

| Item | Location | Encryption |
|---|---|---|
| Private key (PEM) | WP option `gitlogs_jwt_keys` (autoload **off**) | AES-256-GCM, key derived from `AUTH_KEY` via HKDF-SHA256 with salt `"gitlogs/jwt/v1"` |
| Public key (JWK) | Same option, separate field | None (public) |
| `kid` | Same option | None |
| `CreatedAt`, `RotatedAt`, `ExpiresAt` | Same option | None |

The option payload shape:

```json
{
  "Active": {
    "Kid": "2026-04-25-a",
    "PrivateKeyEncrypted": "<base64 AES-256-GCM>",
    "Iv": "<base64 12-byte IV>",
    "Tag": "<base64 16-byte tag>",
    "PublicKeyJwk": { "kty": "RSA", "n": "...", "e": "AQAB" },
    "CreatedAt": "2026-04-25T00:00:00Z",
    "ExpiresAt": "2026-07-24T00:00:00Z"
  },
  "Previous": { "Kid": "2026-01-25-a", "PublicKeyJwk": {…}, "RotatedOutAt": "2026-04-25T00:00:00Z", "OverlapEndsAt": "2026-04-26T00:00:00Z" }
}
```

The `Previous` slot holds **only the public key** so the JWKS can still verify in-flight tokens during the overlap window. The private key is destroyed at rotation.

### 2.3 Rotation cadence

| Trigger | Action |
|---|---|
| Scheduled (default 90 days) | New keypair generated; `Active` becomes `Previous`; new keypair becomes `Active`. |
| Compromise (admin action) | Same flow with overlap shortened to 5 minutes; all access JWTs forcibly added to `RevokedJti` (best-effort scan). |
| `AUTH_KEY` rotation in WP | Re-encrypt the private key with the new derived key — no `kid` change. |

The 90-day cadence is configurable via WP option `gitlogs_jwt_rotation_days` (range 30–365). Overlap window default: **24 hours**, configurable via `gitlogs_jwt_rotation_overlap_hours` (range 1–168).

### 2.4 `kid` format

`{ISO-date}-{shortSuffix}` — e.g., `2026-04-25-a`. Suffix increments on same-day re-rotation. Stable, sortable, human-readable.

---

## 3. JWKS Endpoint

`GET /wp-json/git-logs/v1/.well-known/jwks.json`

```json
{
  "keys": [
    { "kid": "2026-04-25-a", "kty": "RSA", "alg": "RS256", "use": "sig", "n": "...", "e": "AQAB" },
    { "kid": "2026-01-25-a", "kty": "RSA", "alg": "RS256", "use": "sig", "n": "...", "e": "AQAB" }
  ]
}
```

| Aspect | Value |
|---|---|
| Cache-Control | `public, max-age=300` (5 min) |
| Content-Type | `application/jwk-set+json` |
| Auth | Anonymous (RFC 7517) |
| Envelope | **Exempt** — returns raw JWKS, not the standard PascalCase envelope |
| Rate limit | `public` (600/min/IP) |

The endpoint always lists `Active` first; `Previous` is included only while the overlap window is open.

---

## 4. Issuance

```
function issueAccessJwt(User $user): string {
    $keys = JwtKeyStore::loadActive();          // throws on missing/expired
    $now  = time();
    $payload = [
        'iss'   => SiteUrl::issuer(),
        'aud'   => 'git-logs',
        'sub'   => (string) $user->getUserId(),
        'roles' => $user->getRoleCodes(),
        'iat'   => $now,
        'nbf'   => $now - 5,
        'exp'   => $now + 86400,
        'jti'   => UlidFactory::create()->toUuid(),
    ];
    $header = ['alg' => 'RS256', 'typ' => 'JWT', 'kid' => $keys->getActiveKid()];
    return JwtCodec::encode($header, $payload, $keys->getActivePrivateKey());
}
```

Every issuance writes one `AuditTrail` row with `AuditActionType=TokenIssue, AuditOutcome=Success` (or `Error` on signing failure).

---

## 5. Verification

```
function verifyAccessJwt(string $jwt): VerifiedJwt {
    $parsed = JwtCodec::decodeHeader($jwt);     // throws GL-AUTH-002 on malformed
    $kid    = $parsed->getKid();                // GL-AUTH-002 if missing
    $publicJwk = JwksProvider::publicKeyFor($kid); // GL-AUTH-002 if unknown kid
    $payload = JwtCodec::verify($jwt, $publicJwk); // GL-AUTH-002 on bad signature
    JwtClaims::assertStandard($payload, [
        'iss' => SiteUrl::issuer(),
        'aud' => 'git-logs',
        'skew' => 60,                            // ±60 s clock-skew
    ]);
    if (RevokedJtiStore::isRevoked($payload->getJti())) {
        throw new AppError(ErrorCode::GL_AUTH_002, 'Revoked.');
    }
    return new VerifiedJwt($payload);
}
```

Verification failures produce **exactly one** `AuditTrail` row with `AuditActionType=AuthFail, AuditOutcome=Rejected` and the specific `eventName` from [`12-logging-strategy.md` §4.1](./12-logging-strategy.md). The HTTP response always uses generic `GL-AUTH-002` (no oracle).

---

## 6. Failure Modes

| Failure | HTTP | Code | Notes |
|---|---|---|---|
| Missing `Authorization` header | 401 | `GL-AUTH-001` | |
| Malformed JWT | 401 | `GL-AUTH-002` | |
| Unknown `kid` | 401 | `GL-AUTH-002` | Suggests rotation gap |
| Bad signature | 401 | `GL-AUTH-002` | |
| Expired `exp` (with skew) | 401 | `GL-AUTH-002` | |
| `nbf` in future (beyond skew) | 401 | `GL-AUTH-002` | |
| Wrong `iss` / `aud` | 401 | `GL-AUTH-002` | |
| `jti` in `RevokedJti` denylist | 401 | `GL-AUTH-002` | |
| User locked (`User.IsLocked = 1`) | 423 | `GL-AUTH-LOCKED` | |
| Insufficient role | 403 | `GL-AUTH-005` | After successful verify |

---

## 7. Acceptance Criteria

| ID | Given | When | Then |
|---|---|---|---|
| AC-JWT-A1 | Active keypair exists | `GET /.well-known/jwks.json` is called | Response contains the `Active.Kid` JWK |
| AC-JWT-A2 | Rotation overlap is open | Same call | Response also contains the `Previous` JWK |
| AC-JWT-A3 | Rotation overlap has ended | Same call | Response contains only the `Active` JWK |
| AC-JWT-A4 | A JWT signed with `Previous.Kid` arrives during overlap | `verifyAccessJwt` runs | Verification succeeds |
| AC-JWT-A5 | A JWT signed with a `kid` not in JWKS arrives | `verifyAccessJwt` runs | Verification fails with `GL-AUTH-002` |
| AC-JWT-A6 | Clock skew ≤ 60 s | `exp` is in the past by 50 s | Verification still succeeds |
| AC-JWT-A7 | `WP AUTH_KEY` is rotated | A re-encrypt job runs | Private key remains decryptable; `kid` unchanged |
| AC-JWT-A8 | Rotation cron fires | Scheduled time | A new keypair is generated and `Previous` is shifted |
| AC-JWT-A9 | An admin triggers compromise rotation | Admin action | Overlap window collapses to 5 min and `RevokedJti` receives the in-flight `jti`s |

---

## 8. Open Considerations

| ID | Topic | Note |
|---|---|---|
| OI-JWT-A1 | Asymmetric envelope alignment | If [`F-02`](../22-app-issues/02-consolidated-audit-findings/00-overview.md) chooses Ed25519, this file gains a parallel signing path for log-push envelopes. |
| OI-JWT-A2 | HSM offload | Reserve config keys for delegating private-key storage to a KMS / HSM (e.g., AWS KMS). |

---

## 9. Cross-References

| Reference | Location |
|---|---|
| Lifecycle (onboarding, refresh, logout) | [16-jwt-onboarding-and-token-usage.md](./16-jwt-onboarding-and-token-usage.md) |
| WP auth bridge | [06-auth-wordpress-bridge.md](./06-auth-wordpress-bridge.md) |
| Endpoint catalog | [04-rest-api-endpoints.md](./04-rest-api-endpoints.md) |
| Audit trail (revocation rows) | [10-audit-trail.md](./10-audit-trail.md) |
| Error envelope & codes | [11-error-management.md](./11-error-management.md) |
| Logging | [12-logging-strategy.md](./12-logging-strategy.md) |
| Locked decisions | [00-overview.md](./00-overview.md) |
