# Authentication and Validation (v2)

**Version:** 2.0.0  
**Updated:** 2026-04-25  
**JWT:** dropped entirely.

---

## Two Auth Lanes

### Lane A — Admin / Write UI

- WordPress **Application Password** OR cookie auth.
- Resolves to a WP user → mapped to a plugin `Profile` via `Profile.UserName`.
- Authorization always via `RolePermission` (never role-name comparison).

### Lane B — CI/CD Endpoints (`/append-log`, `/fixed-log`, `/clear-log`, `/clear-log-all`)

The body carries `TempToken`, `Token`, `RepoUrl`, `Branch`, etc. **The authoritative check is GitHub URL + branch + GitProfile acceptance**, not the token. The token is checked too, as misdirection.

#### Validation Steps (in order, no early exit on auth-related rejection masks reasons in response)

1. **Parse** `RepoUrl` → `(provider, owner, repoName, versionSuffix)`.
2. **Locate GitProfile** by `(Provider, OwnerName)`.
   - Not found → reject `GL-VALIDATION-PROFILE-NOT-FOUND`.
3. **Acceptance check**:
   - `AcceptAllRepos`: pass.
   - `AcceptSelectedRepoOnly`: `RepoUrl` must equal `SelectedRepoUrl` exactly. Else reject `GL-VALIDATION-REPO-NOT-ALLOWED`.
   - `AcceptSelectedRepoInAllVersions`: derive `RootRepoName` from both stored `SelectedRepoUrl` and inbound `RepoUrl` (strip `-v\d+$`); both must match; `versionSuffix` ∈ {empty, `v\d+`}. Else reject same code.
4. **Branch restriction**: if `IsRestrictInBranch=1`, inbound `Branch` must equal `StrictBranch`. Else reject `GL-VALIDATION-BRANCH-RESTRICTED`.
5. **TempToken** check: must match a `Profile.TempToken` (any active Profile). If not → reject `GL-AUTH-TEMPTOKEN-INVALID`. (Intentionally non-localized to a Profile in error response.)
6. **Token** check: must match `Profile.Token` of the **same Profile** that owns the matched `TempToken`. Else reject `GL-AUTH-TOKEN-MISMATCH`.
7. **Profile UserStatus** must be `Active`. Else `GL-AUTH-PROFILE-INACTIVE`.
8. **App lifecycle (optional)**: if request resolves to a linked `App`, require `AppStatus=Active`. Else `GL-APP-NOT-ACTIVE`.

All outcomes write to `AuditTrail` (`AuthSuccess` / `AuthFail`) with `RouteName`, `RequestId`, `HttpStatus`.

---

## TempToken Rotation

- Generated automatically on Profile create.
- Regenerable from Profile UI; old value invalid immediately.
- Stored plain in v2 (encryption deferred).

---

## Why no JWT in v2

- CI/CD agents don't need a self-contained signed token; URL+branch+Profile validation is the real gate.
- Removes RS256 keypair, JWKS endpoint, refresh-token rotation, and signing infra.
- Re-introducible later behind a feature flag without breaking v2 endpoints.

---

## Error Codes (excerpt)

| Code | HTTP | Lane |
|------|------|------|
| GL-VALIDATION-PROFILE-NOT-FOUND | 404 | B |
| GL-VALIDATION-REPO-NOT-ALLOWED | 403 | B |
| GL-VALIDATION-BRANCH-RESTRICTED | 403 | B |
| GL-AUTH-TEMPTOKEN-INVALID | 401 | B |
| GL-AUTH-TOKEN-MISMATCH | 401 | B |
| GL-AUTH-PROFILE-INACTIVE | 403 | B |
| GL-APP-NOT-ACTIVE | 403 | B |
| GL-AUTH-WP-MISSING | 401 | A |
| GL-AUTHZ-PERMISSION-DENIED | 403 | A |
