# WordPress Admin UI

**Version:** 1.0.0  
**Updated:** 2026-04-25  
**Status:** Active  
**AI Confidence:** Production-Ready  
**Ambiguity:** Low

---

## Purpose

Specifies the WordPress admin surface for `git-logs`: menu placement, screens, fields, validation, secret-rotation UX, and the configuration surfaces required by other specs (trusted proxies per [`F-07`](../22-app-issues/02-consolidated-audit-findings/00-overview.md), CORS allow-list per [`F-14`](../22-app-issues/02-consolidated-audit-findings/00-overview.md), JWT rotation cadence per [`05-auth-jwt-flow.md`](./05-auth-jwt-flow.md)).

---

## 1. Menu Placement

Top-level menu item under WP admin:

| Property | Value |
|---|---|
| Menu title | `Git Logs` |
| Capability | `manage_options` (top-level visibility); per-screen capabilities below |
| Icon | `dashicons-editor-code` |
| Position | 65 (after `Tools`) |

Submenus:

| Submenu | Capability | Default callback |
|---|---|---|
| Dashboard | `manage_options` | `GitLogsDashboardScreen` |
| Repositories | `gitlogs_manage_repos` | `GitLogsRepositoriesScreen` |
| Users | `gitlogs_manage_users` | `GitLogsUsersScreen` |
| Audit Trail | `manage_options` | `GitLogsAuditScreen` |
| Settings | `manage_options` | `GitLogsSettingsScreen` |

Custom capabilities (`gitlogs_manage_repos`, `gitlogs_manage_users`, `gitlogs_view_logs`) are registered at activation per [`06-auth-wordpress-bridge.md` §2](./06-auth-wordpress-bridge.md).

---

## 2. Screens

### 2.1 Dashboard

Read-only summary cards:

| Card | Source |
|---|---|
| Active repositories | `SELECT COUNT(*) FROM Repository WHERE IsActive = 1` |
| Push attempts (24 h) | `SELECT COUNT(*) FROM AuditTrail WHERE AuditActionTypeId = LogPush AND CreatedAt > NOW() - INTERVAL 1 DAY` |
| Failed auths (24 h) | Same with `AuthFail` |
| JWT rotation in | `OverlapEndsAt` of `Previous` JWK or "next scheduled" |

### 2.2 Repositories

List + detail screens. List columns: `OwnerName/RepoName`, `AcceptanceMode`, `VersionMode`, `Status`, `LastPushAt`, `Actions`.

Detail form fields:

| Field | Validation | Notes |
|---|---|---|
| `Provider` | enum `Provider`, only `GitHub` selectable | |
| `OwnerType` | enum `OwnerType` | |
| `OwnerName` | 1–128 chars; matches `^[A-Za-z0-9](?:[A-Za-z0-9-]{0,38})$` | |
| `RepoName` | 1–128 chars; matches `^[A-Za-z0-9._-]+$` | |
| `VersionMode` | enum | |
| `AcceptanceMode` | enum | |
| `Description` | ≤ 4096 chars | Optional |

Action buttons:

- **Save** — POST to `E06` / PATCH to `E08`.
- **Rotate Token** — POST to `E10`. On success, shows the new token in a modal **once** with a "Copy" button and a 24-hour grace-window timer.
- **Disable / Enable** — toggles `RepositoryStatus`.
- **Delete** — admin-only, confirmation modal, types `DELETE` to confirm.

### 2.3 Users

List + detail. List columns: `Username`, `DisplayName`, `Email`, `UserStatus`, `HasWordPressBridge`, `LastLoginAt`, `Actions`.

Detail form fields:

| Field | Validation |
|---|---|
| `Username` | 1–64 chars; `^[a-z0-9._-]+$`; unique |
| `DisplayName` | ≤ 128 chars |
| `Email` | RFC 5322 |
| `Roles` | multi-select from `Role` enum |
| `UserStatus` | enum |

Actions: **Issue Token**, **Revoke Token**, **Lock**, **Unlock**, **Delete**. Issue-token modal shows the raw token once.

### 2.4 Audit Trail

Filter form (maps to [`10-audit-trail.md` §4.2](./10-audit-trail.md)):

| Field | Type |
|---|---|
| `Date range` | from / to date pickers |
| `Action` | multi-select `AuditActionType` |
| `Outcome` | multi-select `AuditOutcome` |
| `Actor user` | autocomplete `User.Username` |
| `Repository` | autocomplete `Repository.OwnerName/RepoName` |
| `HTTP status` | text |
| `Trace id` | text |

Results table is server-paginated (cursor); `Details` cell expands to pretty-printed `DetailsJson`.

### 2.5 Settings

Tabs:

#### a) General

| Field | Option key | Default | Validation |
|---|---|---|---|
| Debug responses | `gitlogs_debug_responses` | `0` | 0/1 |
| Log retention notice | `gitlogs_retention_notice` | "Indefinite" | display-only |

#### b) Security

| Field | Option key | Default | Validation |
|---|---|---|---|
| Trusted proxy CIDRs | `gitlogs_trusted_proxies` | `""` | CSV of CIDRv4/v6 |
| Allowed origins (CORS) | `gitlogs_allowed_origins` | `""` | CSV of `https://` origins |
| JWT rotation days | `gitlogs_jwt_rotation_days` | `90` | int 30–365 |
| JWT rotation overlap (hours) | `gitlogs_jwt_rotation_overlap_hours` | `24` | int 1–168 |
| Rate-limit fallback table | `gitlogs_rate_limit_use_db_fallback` | `0` | 0/1 (per [`F-13`](../22-app-issues/02-consolidated-audit-findings/00-overview.md)) |

#### c) Maintenance

| Action | Effect |
|---|---|
| Re-encrypt JWT private key | Decrypts with old `AUTH_KEY`, re-encrypts with current |
| Force JWT rotation now | Generates new keypair; collapses overlap to 5 min |
| Purge expired `RevokedJti` | Manual trigger of the WP-Cron job |
| Export audit (CSV) | Streams `VwAuditTrailDetail` rows for current filter |

---

## 3. Form Submission Contract

All write actions go through the REST API, never direct DB writes from the screen. Submission steps:

1. Capture form values.
2. Validate client-side (HTML5 + lightweight JS) — best-effort, never authoritative.
3. POST/PATCH to the relevant endpoint with `X-WP-Nonce` (cookie auth path).
4. On success, refresh the list and show a `notice notice-success`.
5. On error, render `Errors[]` from the response envelope inline next to fields by `Errors[i].Field` (PascalCase JSON path, e.g., `OwnerName`).

---

## 4. UX Rules

| # | Rule |
|---|---|
| U1 | Every secret value is shown **once** and only via a copy-to-clipboard button; never logged in the browser console. |
| U2 | Destructive actions (Delete, Revoke, Force Rotate) require a confirmation modal with the user typing the entity name or `DELETE`. |
| U3 | Tables are server-paginated; never load > 200 rows in a single request. |
| U4 | All datetime values are rendered in the WP site timezone with a tooltip showing UTC. |
| U5 | Long `DetailsJson` cells are collapsed with "Show more"; never auto-expand. |
| U6 | All form labels are translatable via WP i18n (`__()`); placeholders are NOT used as labels. |
| U7 | Errors carry `Code` (e.g., `GL-VAL-001`) and a translated `Message`. The code is shown small-caps under the message for support handoff. |

---

## 5. Acceptance Criteria

| ID | Given | When | Then |
|---|---|---|---|
| AC-UI-01 | A `manage_options` user | Visits the menu | Sees all 5 submenus |
| AC-UI-02 | A user with only `gitlogs_view_logs` | Visits the menu | Sees only the screens they can view |
| AC-UI-03 | Repositories detail "Rotate Token" | Clicked | New token shown once with grace timer |
| AC-UI-04 | Settings → Trusted proxies with bad CIDR | Submit | Field-level error rendered with `GL-VAL-001` |
| AC-UI-05 | Audit Trail filter | Submitted with date range | Server query returns matching rows; cursor pagination works |
| AC-UI-06 | Maintenance → Force JWT rotation | Confirmed | New `kid` appears in JWKS; previous `kid` retains 5-min overlap |
| AC-UI-07 | Any destructive action without confirmation | Attempted | Submit button stays disabled until the confirmation phrase matches |
| AC-UI-08 | Form posts | All | Use cookie auth + `X-WP-Nonce` |

---

## 6. Cross-References

| Reference | Location |
|---|---|
| Endpoint catalog | [04-rest-api-endpoints.md](./04-rest-api-endpoints.md) |
| WP auth bridge | [06-auth-wordpress-bridge.md](./06-auth-wordpress-bridge.md) |
| JWT rotation | [05-auth-jwt-flow.md](./05-auth-jwt-flow.md) |
| Audit trail | [10-audit-trail.md](./10-audit-trail.md) |
| Error envelope | [11-error-management.md](./11-error-management.md) |
| Glossary | [01-glossary-and-enums.md](./01-glossary-and-enums.md) |
| Open findings | [22-app-issues/02-consolidated-audit-findings](../22-app-issues/02-consolidated-audit-findings/00-overview.md) |
