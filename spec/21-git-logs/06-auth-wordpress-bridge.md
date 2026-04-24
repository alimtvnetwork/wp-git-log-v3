# WordPress Authentication Bridge

**Version:** 1.0.0  
**Updated:** 2026-04-25  
**Status:** Active  
**AI Confidence:** Production-Ready  
**Ambiguity:** Low

---

## Purpose

Operationalises [Locked Decision #8](./00-overview.md): the plugin accepts both **WordPress Application Passwords** and **WordPress cookie auth** for routes that need to bridge a WP user into a plugin user. This document defines detection precedence, capability mapping, nonce handling, plugin-user provisioning, and failure modes.

This bridge is consumed by `POST /auth/token` and any admin-only route that prefers cookie auth (e.g., `GET /audit` from the WP admin UI).

---

## 1. Detection Precedence

For each request the auth resolver tries methods in this fixed order; the **first** that returns a `WpUser` wins:

1. `Authorization: Basic <base64 user:appPassword>` — WP App Password.
2. WP cookie + valid REST nonce (`X-WP-Nonce` header).
3. None matched → `Anonymous` (other auth classes may still apply).

If method 1 is present but invalid, the resolver MUST NOT fall through to method 2 (avoid token-shadowing). Return `401 GL-AUTH-001`.

---

## 2. Capability Mapping

Plugin roles are mapped from WP capabilities at provisioning time and re-evaluated on every login.

| WP capability | Plugin `Role.Code` |
|---|---|
| `manage_options` | `Admin` |
| `gitlogs_manage_repos` (custom) | `CanAddRepo` |
| `gitlogs_manage_users` (custom) | `CanAddUser` |
| `gitlogs_view_logs` (custom) | `CanViewLogs` |
| (none of the above) | _Bridge denied — see §6_ |

The three `gitlogs_*` capabilities are registered at plugin activation and granted by default to the `administrator` and `editor` roles. They are editable from `03-admin-ui.md` (when authored).

---

## 3. Plugin-User Provisioning

### 3.1 Mapping

A WP user maps to **at most one** plugin `User` row. The link is `User.WordPressUserId = wp_users.ID`.

### 3.2 First-time bridge

When a WP user authenticates successfully via §1 and no plugin user exists for them:

1. Generate `Username = "wp-{$wpUser->ID}"` (collision-safe; never reused).
2. INSERT `User` row with:
   - `Username` per step 1
   - `DisplayName = $wpUser->display_name`
   - `Email = $wpUser->user_email`
   - `TokenHash = NULL` (no plugin token issued; auth is via WP)
   - `UserStatusId = UserStatus::Active`
   - `HasWordPressBridge = 1`
   - `WordPressUserId = $wpUser->ID`
3. Insert `UserRole` rows per the capability map in §2.
4. Write `AuditTrail (UserCreate, Success)` with `ActorWordPressUserId` set.

### 3.3 Subsequent calls

The existing plugin user is loaded; `UserRole` rows are reconciled against the current capability map (added or removed in a single transaction). Reconciliation writes `AuditTrail (UserUpdate, Success)` only when role rows actually changed.

### 3.4 WP user disabled / deleted

If the WP user becomes invalid (deleted, role stripped to none of §2), the next bridge attempt:
- Sets `User.UserStatusId = UserStatus::Suspended`.
- Revokes all live refresh tokens for that plugin user.
- Returns `403 GL-AUTH-005`.

---

## 4. Nonce Handling (cookie path only)

The cookie path requires a valid REST nonce per WP convention:

- Header: `X-WP-Nonce: <nonce>`
- Generation: standard `wp_create_nonce('wp_rest')` on the admin page that calls the API.
- Validation: `wp_verify_nonce($nonce, 'wp_rest')` returns `1` or `2`.

A missing or invalid nonce on the cookie path → `403 GL-AUTH-NONCE`.

---

## 5. Token Issuance via Bridge

`POST /auth/token` with bridge auth issues a normal RS256 access JWT and a refresh token per [`05-auth-jwt-flow.md`](./05-auth-jwt-flow.md). The JWT's `sub` is the plugin `User.UserId`; `roles` is the freshly reconciled set from §2.

The bridge does **not** issue WP cookies or App Passwords — those are owned by WP itself.

---

## 6. Failure Modes

| Failure | HTTP | Code | Notes |
|---|---|---|---|
| `Authorization: Basic` present but credentials wrong | 401 | `GL-AUTH-001` | |
| App Password disabled for the user | 401 | `GL-AUTH-001` | |
| Cookie path with missing/invalid nonce | 403 | `GL-AUTH-NONCE` | |
| Cookie path with logged-out user | 401 | `GL-AUTH-001` | |
| WP user has none of the §2 capabilities | 403 | `GL-AUTH-005` | "Bridge denied — no eligible role" |
| Plugin user is `Suspended` / `Revoked` | 423 | `GL-AUTH-LOCKED` | |
| Plugin user has `IsLocked = 1` | 423 | `GL-AUTH-LOCKED` | |

Every failure writes one `AuditTrail (AuthFail, Rejected)` row.

---

## 7. Security Notes

- App Passwords MUST be transmitted over HTTPS only. The plugin does not enforce TLS termination but the install guide MUST require it.
- Cookie auth is vulnerable to CSRF without the nonce — the nonce check is non-optional.
- Bridge authentication never exposes the WP user's primary password to the plugin.
- The bridge does not federate WP roles back into WP — role demotion in WP propagates to the plugin on next login (§3.3), not the other way around.

---

## 8. Acceptance Criteria

| ID | Given | When | Then |
|---|---|---|---|
| AC-WPB-01 | Valid App Password | `POST /auth/token` | Returns `200` with access + refresh JWTs |
| AC-WPB-02 | Valid WP cookie + nonce | `POST /auth/token` | Same as AC-WPB-01 |
| AC-WPB-03 | Both methods present (Basic + cookie) | `POST /auth/token` | Basic wins (precedence rule §1) |
| AC-WPB-04 | Basic present but wrong | Same | Returns `401`; cookie path is **not** evaluated |
| AC-WPB-05 | Cookie path with missing `X-WP-Nonce` | Same | Returns `403 GL-AUTH-NONCE` |
| AC-WPB-06 | First-time WP user with `manage_options` | Same | A plugin `User` row is created with `Admin` role |
| AC-WPB-07 | Subsequent call after admin removed `gitlogs_view_logs` from WP role | Same | `UserRole` row for `CanViewLogs` is removed and `UserUpdate` row is written |
| AC-WPB-08 | WP user has none of the §2 capabilities | Same | Returns `403 GL-AUTH-005`; no plugin user is created |
| AC-WPB-09 | Plugin user with `IsLocked = 1` | Same | Returns `423 GL-AUTH-LOCKED` |
| AC-WPB-10 | Bridge produces a JWT | The JWT is verified | `sub` equals `User.UserId`, `roles` matches the reconciled set |

---

## 9. Cross-References

| Reference | Location |
|---|---|
| JWT issuance | [05-auth-jwt-flow.md](./05-auth-jwt-flow.md) |
| Lifecycle | [16-jwt-onboarding-and-token-usage.md](./16-jwt-onboarding-and-token-usage.md) |
| Endpoint catalog | [04-rest-api-endpoints.md](./04-rest-api-endpoints.md) |
| Audit trail | [10-audit-trail.md](./10-audit-trail.md) |
| Error envelope | [11-error-management.md](./11-error-management.md) |
| Logging | [12-logging-strategy.md](./12-logging-strategy.md) |
| Glossary | [01-glossary-and-enums.md](./01-glossary-and-enums.md) |
