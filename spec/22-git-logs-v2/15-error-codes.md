# Error Code Catalog (v2)

**Version:** 2.7.0  
**Updated:** 2026-04-25

All `GL-*` codes returned by the plugin. Codes are stable strings (constants in `inc/Support/ErrorCodes.php`). Adding a new code requires a row here.

---

## Authentication (Lane A — admin/read)

| Code | HTTP | Cause | Caller action |
|------|------|-------|---------------|
| GL-AUTH-WP-MISSING | 401 | No WP App Password / cookie present. | Authenticate via Application Password. |
| GL-AUTH-WP-INVALID | 401 | App Password rejected by WP. | Regenerate App Password in WP admin. |
| GL-AUTH-NOT-LOGGED-IN | 401 | `wp_get_current_user()` returned 0 (no resolved user). | Authenticate first. |
| GL-AUTH-PROFILE-NOT-LINKED | 403 | WP user has no matching `Profile.UserName`. | Admin must create the Profile in plugin UI. |
| GL-AUTH-NO-PROFILE-LINK | 403 | No `Profile.Email` matches `wp_user.user_email`. | Provision a Git Logs Profile with the same email. |
| GL-AUTH-PROFILE-SUSPENDED | 403 | Lane A: matched Profile has `UserStatusId != Active`. | Re-activate Profile in admin. |
| GL-AUTH-WRONG-LANE | 400 | Lane B credential (TempToken in body) sent to a Lane A read endpoint, or vice versa. | Use the correct lane per `25-headless-auth-notes.md`. |
| GL-AUTHZ-PERMISSION-DENIED | 403 | Profile's `RolePermission` union lacks the required Permission. | Grant via AccessToRoles screen. |

## Authentication (Lane B — CI/CD writes)

| Code | HTTP | Cause | Caller action |
|------|------|-------|---------------|
| GL-AUTH-TEMPTOKEN-INVALID | 401 | `TempToken` matches no Profile. | Rotate TempToken in Profile UI; re-issue to CI. |
| GL-AUTH-TOKEN-MISMATCH | 401 | `Token` doesn't match the same Profile that owns the matched `TempToken`. | Update CI secrets. |
| GL-AUTH-PROFILE-INACTIVE | 403 | Profile `UserStatus` ∈ {Suspended, Revoked}. | Re-activate Profile in admin. |
| GL-APP-NOT-ACTIVE | 403 | Linked `App.AppStatus` ∈ {Disabled, Archived}. | Set App back to Active or unlink. |

## Authentication (Lane B — SSH sub-mode, preferred from v2.7.0)

Validation order fixed by §31 (steps 1–10). See [`31-ssh-key-auth.md`](./31-ssh-key-auth.md) for canonical signing string and payload shape.

| Code | HTTP | Step | Cause | Caller action |
|------|------|------|-------|---------------|
| GL-SSH-HEADER-MISSING | 400 | 2 | One of `X-GL-Fingerprint`, `X-GL-Timestamp`, `X-GL-Nonce`, `X-GL-Signature` absent. | Re-run signer; ensure all four headers set. |
| GL-SSH-TIMESTAMP-SKEW | 401 | 3 | `\|now − X-GL-Timestamp\| > ReplayWindowSeconds`. | Sync runner clock (NTP). |
| GL-SSH-KEY-UNKNOWN | 401 | 4 | No `SshKey` row matches `Fingerprint`. | Register the public key on the target Repo. |
| GL-SSH-KEY-INACTIVE | 403 | 4 | `SshKey.IsActive=0`. | Re-activate or rotate to a new key. |
| GL-SSH-REPO-MISMATCH | 403 | 5 | Parsed `RepoId` ≠ `SshKey.RepoId` (deploy-key binding). | Use a key registered on this Repo. |
| GL-SSH-NONCE-REUSED | 401 | 7 | `(SshKeyId, Nonce)` already seen within `ReplayWindowSeconds`. | Generate a fresh ≥16-byte nonce per request. |
| GL-SSH-SIGNATURE-INVALID | 401 | 8 | `ssh-keygen -Y verify` failed against canonical `GL-SSHSIG-V1` string. | Confirm namespace `git-logs@v2`, body hash, header values match signed payload. |
| GL-SSH-LANE-CONFLICT | 400 | mode parse | `X-GL-Auth-Mode: ssh` AND body `TempToken` both present. | Pick one lane. |
| GL-AUTH-LANE-DISABLED | 403 | mode parse | TempToken submitted while `ConfigKv.SshAuthMode = required`. | Switch CI to SSH lane. |

## Validation (Lane B body inputs)

| Code | HTTP | Cause | Caller action |
|------|------|-------|---------------|
| GL-VALIDATION-PROFILE-NOT-FOUND | 404 | No `GitProfile` row for `(Provider, OwnerName)` parsed from `RepoUrl`. | Add the GitProfile in admin. |
| GL-VALIDATION-REPO-NOT-ALLOWED | 403 | `Acceptance` rule rejected the repo (`AcceptSelectedRepoOnly` mismatch or version mismatch). | Adjust GitProfile Acceptance or push from the allowed repo. |
| GL-VALIDATION-BRANCH-RESTRICTED | 403 | `IsRestrictInBranch=1` and inbound `Branch` ≠ `StrictBranch`. | Push from allowed branch or relax restriction. |
| GL-VALIDATION-REPOURL-MALFORMED | 400 | `RepoUrl` failed parser (no provider/owner/repo). | Fix CI variable. |
| GL-VALIDATION-MISSING-FIELD | 400 | Required body field absent (e.g., `RepoUrl`, `Branch`). | Add field. |
| GL-VALIDATION-FIELD-TYPE | 400 | Field present but wrong type (e.g., `HasError` not bool). | Fix payload. |

## Rate limiting + payload (Lane B)

| Code | HTTP | Cause | Caller action |
|------|------|-------|---------------|
| GL-PAYLOAD-TOO-LARGE | 413 | `Content-Length` > `MaxPushPayloadBytes` (default 1 MiB). | Split push or increase config. |
| GL-LINES-TOO-MANY | 413 | `len(Logs) + len(ErrorLogs)` > `MaxLinesPerPush` (default 10000). | Split push. |
| GL-RATE-LIMIT-EXCEEDED | 429 | Per-Profile token bucket empty. Response includes `Retry-After`. | Wait per `Retry-After` then retry. |

> Per-line oversize is **not** an error: the server truncates and tags the line with `LogSeverity=Warn`. No GL- code returned.

## Read endpoints (Lane A)

| Code | HTTP | Cause | Caller action |
|------|------|-------|---------------|
| GL-READ-CURSOR-INVALID | 400 | `Cursor` opaque string failed decode. | Drop cursor and start fresh. |
| GL-READ-PIPELINE-NOT-FOUND | 404 | `PipelineId` not in DB. | Verify ID. |
| GL-READ-LIMIT-OUT-OF-RANGE | 400 | `Limit` < 1 or > 1000. | Use 1–1000. |

## Operational

| Code | HTTP | Cause | Caller action |
|------|------|-------|---------------|
| GL-DB-UNAVAILABLE | 503 | SQLite open failed (file lock, FS error). | Retry; check WP error log. |
| GL-MIGRATION-PENDING | 503 | Plugin booting; migration not yet applied. | Retry after a few seconds. |
| GL-CONFIG-MISSING | 500 | Required `ConfigKv` key absent (corrupted DB). | Re-run activator. |
| GL-INTERNAL | 500 | Unhandled exception. `RequestId` correlates with WP error log. | Report to maintainer with `RequestId`. |

---

## Envelope (recap)

Every reject returns:

```json
{
  "Status": "Error",
  "Code": "GL-…",
  "Message": "human-readable",
  "RequestId": "req_…",
  "HttpStatus": <int>
}
```

`RequestId` is also written to `AuditTrail.RequestId` for any reject — operators can grep both sides.

---

## Adding a new code

1. Append a row to the relevant section above.
2. Add a constant in `inc/Support/ErrorCodes.php`.
3. Wire `inc/Rest/ErrorResponder.php` to map it to the listed HTTP status.
4. Bump `98-changelog.md`.
