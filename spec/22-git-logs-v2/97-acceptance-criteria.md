# Acceptance Criteria (v2)

**Version:** 3.8.0  
**Updated:** 2026-04-26

| # | Criterion | Source |
|---|-----------|--------|
| AC-01 | Top-level menu renders exactly: Profile, Roles, AccessToRoles, GitProfile, Repo, RepoVersion, History, Action. | brief §1.a–h |
| AC-02 | Profile stores `UserName`, `Email`, `GeneratedKeyApi`, `Token`, `TempToken` in SQLite root DB; no password. | brief §2 |
| AC-03 | Migration runs once per plugin version; subsequent boots of the same version skip it. | brief §3.b–e |
| AC-04 | Logger supports Trace/Debug/Info/Warn/Error/Fatal; `LogLevelMin` in `ConfigKv` disables Info/Debug at runtime. | brief §3.f |
| AC-05 | Duplicate diagnostic log lines deduplicate within a 60s window. | brief §3.g |
| AC-06 | Roles/Permissions live in plugin SQLite, not WP. Authorization checks `RolePermission`, never the role name. | brief §4–5 |
| AC-07 | GitProfile supports User and Organization URLs via `IsOrganization` boolean (v3.8.0 — replaces `OwnerType` lookup); trailing slash optional; canonicalized on save as `github.com/$org/$repo` when `IsOrganization=1` else `github.com/$username/$repo`. | brief §Domain.2 + v3.8.0 |
| AC-08 | GitProfile.Acceptance ∈ { AcceptAllRepos, AcceptSelectedRepoOnly, AcceptSelectedRepoInAllVersions }. | brief §Domain.3.b |
| AC-09 | `IsRestrictInBranch` toggles visibility and enforcement of `StrictBranch`. | brief §Domain.3.f |
| AC-10 | Repo stores root URL stripped of `-vN`; RepoVersion stores each variant linked back to Repo. | brief §Domain.5 |
| AC-11 | All 10 logical endpoints exist with the exact request/response field names from §Endpoints. Endpoints fold to 8 HTTP paths in §17 via the `?q=` query-param collapse rule documented in §04 (rows #5/#6 share `/get-logs`; rows #7/#8 share `/get-pipeline-logs`). | brief §Endpoints, §04, §17 |
| AC-12 | `/append-log` supports streaming ingestion (`Transfer-Encoding: chunked`). | brief §Endpoints.2.b |
| AC-13 | `HasError=true` on `/append-log` sets `Pipeline.HasError=1` until `/fixed-log` clears it. | brief §Endpoints.2.c |
| AC-14 | All write endpoints respond with structured ack including `Retrieval` hints. | brief §Endpoints.1.a–b |
| AC-15 | CI/CD endpoints validate GitHub URL + branch authoritatively; `TempToken` is checked but is non-authoritative. | brief §Auth.4 |
| AC-16 | JWT is **not** implemented in v2. | brief §Auth + locked decision 5 |
| AC-17 | App entity exists with `AppName`, `AppSlug` (unique), `Description`, `ProfileId`, `AppStatusId`. | locked decision 10–12 |
| AC-18 | App↔Repo / App↔GitProfile linkage uses polymorphic `AppLink` with exactly-one-target CHECK. | locked decision 10 |
| AC-19 | App inherits credentials from parent Profile; no own tokens. | locked decision 11 |
| AC-20 | App lifecycle status enum (Active/Disabled/Archived) gates push acceptance. | locked decision 12 |
| AC-21 | Four audit tables coexist: `AuditTrail` (HTTP forensics), `History` (per-RepoVersion git timeline), `PipelineAction` (renamed from `Action` in v3.8.0 — pipeline-bound), `SystemEvent` (NEW v3.8.0 — non-Git business events). Responsibility split documented in §08. | locked decision 13 + v3.8.0 |
| AC-49 | Per-SHA log storage (v3.8.0): every accepted `/append-log` for a new `(RepoVersionId, GitSha256)` creates `wp-content/uploads/git-logs/logs/<RepoVersionId>/<GitSha256>.sqlite` and a `ShaRegistry` row in the root DB. `LogEntry` and `ErrorLogEntry` no longer exist in the root DB — all log lines live in the per-SHA file. | §39 |
| AC-50 | `ShaRegistry` mirrors `EntryCount`, `ErrorCount`, `LastStatus`, `LastSeverityId`, `LastFailureAt`, `LastSuccessAt` from the per-SHA `StatusSnapshot` so dashboards never have to open the per-SHA file to render summary tiles. | §39 |
| AC-51 | Per-SHA file is self-contained: includes denormalized `LogSeverity` lookup + `ShaMeta` single-row identity, so it can be exported / zipped / handed to support without root-DB context. | §39 |
| AC-52 | Open per-SHA handles capped per process at `ConfigKv.MaxOpenShaDbHandles` (default 64) with LRU eviction; idle handles closed after `ConfigKv.ShaDbIdleCloseSec` seconds (default 300). | §39 |
| AC-53 | `wp git-logs prune` walks `ShaRegistry`, deletes the per-SHA file, then deletes the row. `wp git-logs backup` zips the entire `git-logs/` directory including all `logs/` per-SHA files; manifest records each per-SHA file's row counts + sha256. Uninstall Wipe mode deletes the entire `uploads/git-logs/` folder. | §22, §23, §29, §39 |
| AC-22 | Folder 26 contains: ER, domain, endpoint, auth, permission Mermaid diagrams. | brief §Diagrams |
| AC-23 | All tables/columns/JSON keys/values use PascalCase; PKs are `INTEGER AUTOINCREMENT` named `{Table}Id`. | brief §DB.2–4 |
| AC-24 | All typed values modeled as Enum in code AND lookup table in DB; no string-literal status comparisons. | brief §DB.5 |
| AC-25 | Items marked `format:hide` in the mind-map are not rendered in UI. | brief §1.j |
| AC-26 | Per-Profile token bucket enforces `RatePerMinPerProfile`; bucket state survives request boundaries; `Retry-After` header set on 429. | §10 |
| AC-27 | `MaxPushPayloadBytes`, `MaxLinesPerPush`, `MaxLineBytes` enforced before parse; oversize line truncated + tagged Warn (no GL- code). | §10 |
| AC-28 | First-run bootstrap form appears iff `Profile` table empty AND current user has `manage_options`; one-time credential reveal; re-shown if last Profile deleted. | §03 |
| AC-29 | `inc/Migrations/V{Major}_{Minor}_{Patch}.php` classes implement `MigrationInterface`; `MigrationState` keyed by `PluginVersion`; idempotent re-runs. | §06, §12 |
| AC-30 | Every endpoint reject returns the §15 envelope `{Status, Code, Message, RequestId, HttpStatus}`; `RequestId` mirrored in `AuditTrail.RequestId`. | §15 |
| AC-31 | `wp git-logs prune --older-than=Nd` enforces 7d floor; refused while migration pending; two-phase delete; final `wal_checkpoint(TRUNCATE)`; emits `AuditActionType.Prune` row. | §22 |
| AC-32 | `wp git-logs backup` uses SQLite Online Backup API + manifest JSON; `restore` refuses without maintenance mode unless `--force`; downgrade across major versions always refused. | §23 |
| AC-33 | `wp git-logs verify` runs `integrity_check` + `foreign_key_check` + `Profile≥1` + `MigrationState.PluginVersion=ConfigKv.PluginVersion`; surfaced via Site Health card. | §20, §23 |
| AC-34 | Multisite: per-site DB file always (even when network-activated); no shared DB; reads stay site-scoped. | §24 |
| AC-35 | Lane B credential to a Lane A endpoint (or vice versa) returns `GL-AUTH-WRONG-LANE`; Lane A requires Profile match by email else `GL-AUTH-NO-PROFILE-LINK`. | §25, §15 |
| AC-36 | Translatable scope honors §21: GL-* codes, enum names, Permission names, REST routes, hook names, `ConfigKv` keys, audit `Detail` keys remain English. CI POT diff passes. | §21 |
| AC-37 | `GET /wp-json/git-logs/v2/metrics` returns Prometheus exposition; auth gated by `HistoryView`; counters flushed every 5 min via wp_cron. | §20 |
| AC-38 | `AuditActionType` lookup contains rows: Prune (19), Restore (20) per `18-schema.sql` seed. | §22, §23, §18 |
| AC-39 | Permission gate uses `RolePermission` join only — never role name string compare. Buttons hidden (not disabled) when permission missing. | §19 |
| AC-40 | OpenAPI 3.1 spec at `17-openapi.yaml` parses; covers all 10 endpoints; references `15-error-codes.md` envelope schema. | §17 |
| AC-41 | WP.org release ZIP contains `readme.txt` + `screenshot-1..8.png`; CI gate runs `wp plugin check` before tagging. | §26 |
