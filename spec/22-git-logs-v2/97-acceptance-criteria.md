# Acceptance Criteria (v2)

**Version:** 2.1.0  
**Updated:** 2026-04-25

| # | Criterion | Source |
|---|-----------|--------|
| AC-01 | Top-level menu renders exactly: Profile, Roles, AccessToRoles, GitProfile, Repo, RepoVersion, History, Action. | brief §1.a–h |
| AC-02 | Profile stores `UserName`, `Email`, `GeneratedKeyApi`, `Token`, `TempToken` in SQLite root DB; no password. | brief §2 |
| AC-03 | Migration runs once per plugin version; subsequent boots of the same version skip it. | brief §3.b–e |
| AC-04 | Logger supports Trace/Debug/Info/Warn/Error/Fatal; `LogLevelMin` in `ConfigKv` disables Info/Debug at runtime. | brief §3.f |
| AC-05 | Duplicate diagnostic log lines deduplicate within a 60s window. | brief §3.g |
| AC-06 | Roles/Permissions live in plugin SQLite, not WP. Authorization checks `RolePermission`, never the role name. | brief §4–5 |
| AC-07 | GitProfile supports User and Organization URLs; trailing slash optional; canonicalized on save. | brief §Domain.2 |
| AC-08 | GitProfile.Acceptance ∈ { AcceptAllRepos, AcceptSelectedRepoOnly, AcceptSelectedRepoInAllVersions }. | brief §Domain.3.b |
| AC-09 | `IsRestrictInBranch` toggles visibility and enforcement of `StrictBranch`. | brief §Domain.3.f |
| AC-10 | Repo stores root URL stripped of `-vN`; RepoVersion stores each variant linked back to Repo. | brief §Domain.5 |
| AC-11 | All 10 endpoints exist with the exact request/response field names from §Endpoints. | brief §Endpoints |
| AC-12 | `/append-log` supports streaming ingestion (`Transfer-Encoding: chunked`). | brief §Endpoints.2.b |
| AC-13 | `HasError=true` on `/append-log` sets `Pipeline.HasError=1` until `/fixed-log` clears it. | brief §Endpoints.2.c |
| AC-14 | All write endpoints respond with structured ack including `Retrieval` hints. | brief §Endpoints.1.a–b |
| AC-15 | CI/CD endpoints validate GitHub URL + branch authoritatively; `TempToken` is checked but is non-authoritative. | brief §Auth.4 |
| AC-16 | JWT is **not** implemented in v2. | brief §Auth + locked decision 5 |
| AC-17 | App entity exists with `AppName`, `AppSlug` (unique), `Description`, `ProfileId`, `AppStatusId`. | locked decision 10–12 |
| AC-18 | App↔Repo / App↔GitProfile linkage uses polymorphic `AppLink` with exactly-one-target CHECK. | locked decision 10 |
| AC-19 | App inherits credentials from parent Profile; no own tokens. | locked decision 11 |
| AC-20 | App lifecycle status enum (Active/Disabled/Archived) gates push acceptance. | locked decision 12 |
| AC-21 | Three audit tables coexist: `AuditTrail`, `History`, `Action`, with the responsibility split documented in 08. | locked decision 13 |
| AC-22 | Folder 26 contains: ER, domain, endpoint, auth, permission Mermaid diagrams. | brief §Diagrams |
| AC-23 | All tables/columns/JSON keys/values use PascalCase; PKs are `INTEGER AUTOINCREMENT` named `{Table}Id`. | brief §DB.2–4 |
| AC-24 | All typed values modeled as Enum in code AND lookup table in DB; no string-literal status comparisons. | brief §DB.5 |
| AC-25 | Items marked `format:hide` in the mind-map are not rendered in UI. | brief §1.j |
| AC-26 | All lookup tables seeded per §09 with the exact rows listed; re-running migration is a no-op (`INSERT OR IGNORE`). | §09 |
| AC-27 | Admin role has every Permission row; Editor role has the 8 view/modify permissions listed in §09. | §09 |
| AC-28 | First-run bootstrap creates exactly one Profile for the WP admin visitor; logged in `AuditTrail` as `ProfileCreate`. | §09 + §03 bootstrap |
| AC-29 | Push payload >`MaxPushPayloadBytes` rejected with `GL-PAYLOAD-TOO-LARGE` (HTTP 413). | §10 |
| AC-30 | Per-Profile token bucket enforces `RatePerMinPerProfile`; over-limit returns HTTP 429 with `Retry-After`. | §10 |
| AC-31 | Lines per push >`MaxLinesPerPush` rejected with `GL-LINES-TOO-MANY`; lines >`MaxLineBytes` truncated and tagged Warn. | §10 |
| AC-32 | v2 stores secrets as plain TEXT; no AES/HMAC code paths exist (deferred to v3). | §11 |
| AC-33 | §11 documents v3 encryption fields, key strategy, and migration trigger; reserved `inc/Db/Crypto/` placeholder exists in v2 scaffold. | §11 + §12 |
| AC-34 | PHP file tree matches §12: PSR-4 autoload, one class per file, bootstrap contains no business logic, hook/capability/error-code constants in `inc/Support/*`. | §12 |
| AC-35 | Every spec entity in §02/§07/§08 has a corresponding repository file under `inc/Db/Repositories/`. | §12 |
| AC-36 | Migration classes follow `V{Major}_{Minor}_{Patch}` naming under `inc/Migrations/` and implement `MigrationInterface`. | §06 + §12 |
