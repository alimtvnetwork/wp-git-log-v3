# v1 ↔ v2 Mapping

**Version:** 2.1.0
**Updated:** 2026-04-25

Side-by-side reference for anyone arriving from `spec/21-git-logs/` (legacy v1). v2 wins in every overlap; v1 is retained for historical context only.

---

## Architecture deltas

| Concern | v1 (folder 21) | v2 (folder 22) |
|---------|----------------|----------------|
| Storage | Per-WP-site MySQL via `$wpdb` | Single SQLite file at plugin root |
| Auth | JWT bearer + Profile credentials | WP App Password / cookie (admin) + `TempToken` (CI/CD) |
| Naming | snake_case columns + camelCase JSON | PascalCase across DB, JSON, code |
| Audit | Single `audit_log` table | Three tables: `AuditTrail`, `History`, `Action` |
| App entity | None (Profile pushed directly) | First-class `App` + polymorphic `AppLink` |
| Lifecycle | Profile.is_active boolean | `AppStatus` enum (Active/Disabled/Archived) |
| Rate limit | Per-Repo, hard-coded | Per-Profile, configurable via `ConfigKv` |
| Encryption | Planned (never shipped) | Deferred to v3 with documented blueprint (§11) |

---

## Endpoint mapping

| v1 route | v2 route | Notes |
|----------|----------|-------|
| `POST /git-logs/v1/push` | `POST /git-logs/v2/append-log` | Streaming added; ack envelope expanded |
| `POST /git-logs/v1/push?fixed=1` | `POST /git-logs/v2/fixed-log` | Split into dedicated route |
| `POST /git-logs/v1/clear` | `POST /git-logs/v2/clear-log` | Same semantics |
| (none) | `POST /git-logs/v2/clear-log-all` | New |
| `GET /git-logs/v1/logs` | `GET /git-logs/v2/get-logs` | PascalCase keys |
| (none) | `GET /git-logs/v2/get-pipeline-logs` | New: per-pipeline view |
| `GET /git-logs/v1/errors` | `GET /git-logs/v2/get-error-logs` | Split error retrieval |
| (none) | `GET /git-logs/v2/get-pipeline-error-logs` | New |

---

## Table mapping

| v1 table | v2 table(s) | Notes |
|----------|-------------|-------|
| `wp_gitlogs_profile` | `Profile` | No `Password`; adds `TempToken` |
| `wp_gitlogs_repo` | `Repo` + `RepoVersion` | Variant suffix lifted into its own table |
| `wp_gitlogs_log` | `LogEntry` + `ErrorLogEntry` + `Pipeline` | Streaming + HasError flag promoted to `Pipeline` |
| `wp_gitlogs_audit` | `AuditTrail` + `History` + `Action` | Responsibility split per §08 |
| (none) | `App`, `AppLink`, `AppStatus` | New entity |
| (none) | `RolePermission`, `Role`, `Permission` | Plugin-owned, not WP capabilities |
| (none) | `ConfigKv`, `MigrationState` | Operational |

---

## Term renames

| v1 | v2 |
|----|----|
| `api_key` | `GeneratedKeyApi` |
| `bearer_token` | `Token` |
| (n/a) | `TempToken` |
| `is_active` | `AppStatusId` (via App) / `UserStatusId` (via Profile) |
| `created_at` (unix) | `CreatedAt` (unix, same units) |
| `repo_url` | `RepoUrl` |

---

## Migration path (v1 → v2)

There is no in-place upgrade. v2 is a clean install with a fresh SQLite DB. Operators wanting historical v1 data should export from MySQL and import via a one-off script that maps the column names per the table above. This is intentional — v2 changes naming, audit shape, and credentials simultaneously.
