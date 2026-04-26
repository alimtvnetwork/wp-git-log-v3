# Acceptance Criteria (v2)

**Version:** 3.8.12  
**Updated:** 2026-04-26 (Phase 12: Phase 9 Follow-ups — AC-73..AC-75 added for `Pipeline.PreviousHasError` state-transition matrix, back-fill correctness, single-statement write atomicity; AC-67 extended to mention optional `Header.StateTransition` field)

---

## Format

Every criterion below is stated as **Given / When / Then**. Each AC also carries a `Verifies:` pointer to the source section(s) of this folder so a downstream auditor can trace the assertion back to the authoritative spec without external context.

> **Status legend:** `[active]` = enforced for v2.9.3 schema. `[draft]` = not yet enforced (no rows in this file currently carry this state — kept here for future phases). `[deprecated]` = retained for cross-version diff only.

---

## Section A — UI / Menu / First-Run

### AC-01 — Top-level menu inventory  `[active]`
- **Given** a freshly migrated v2 install with at least one Profile present
- **When** an authenticated WP admin opens the plugin's top-level menu
- **Then** exactly these 8 items render in this order: `Profile`, `Roles`, `AccessToRoles`, `GitProfile`, `Repo`, `RepoVersion`, `History`, `Action` — no more, no fewer.
- **Verifies:** brief §1.a–h, §03.

### AC-25 — `format:hide` items not rendered  `[active]`
- **Given** mind-map nodes annotated `format:hide`
- **When** the corresponding admin screen renders
- **Then** those nodes MUST NOT appear in the DOM (not merely hidden via CSS).
- **Verifies:** brief §1.j, §03.

### AC-28 — First-run bootstrap form gating  `[active]`
- **Given** the `Profile` table is empty AND the current WP user holds `manage_options`
- **When** any plugin admin route is loaded
- **Then** the bootstrap form renders, the generated credentials are revealed exactly once on submit, and the form re-appears if the last Profile row is later deleted.
- **Verifies:** §03.

### AC-54 — IsOrganization checkbox in GitProfile form  `[active]`
- **Given** the GitProfile create/edit form
- **When** an admin opens it
- **Then** an **Is organization** checkbox (default off) renders bound to `GitProfile.IsOrganization` (0/1); the legacy "OwnerType (derived)" field MUST NOT render; toggling the checkbox flips the canonical URL form between `github.com/$org/$repo` and `github.com/$username/$repo` on save.
- **Verifies:** §03 + v3.8.1.

### AC-59 — History Activity tab  `[active]`
- **Given** the History admin screen post-v3.8.2
- **When** an authorized user opens it
- **Then** an **Activity** tab renders backed by `SystemEvent` with filter chip *Git events / System events / All*; the legacy "Action" top-level menu label is retained but is now backed by the renamed `PipelineAction` table.
- **Verifies:** §03 + v3.8.2.

---

## Section B — Domain Model & Profiles

### AC-02 — Profile fields  `[active]`
- **Given** a `Profile` row exists in the SQLite root DB (path per §39 `ConfigKv.RootDbPath`, conventionally `<plugin-data>/git-logs.db`)
- **When** the row is read via `SELECT * FROM Profile WHERE ProfileId = :id`
- **Then** the result MUST contain exactly these columns and no others (per §02 v3.8.6 + §18 v2.9.3 DDL): `ProfileId INTEGER PRIMARY KEY AUTOINCREMENT`, `UserName TEXT NOT NULL UNIQUE`, `Email TEXT NOT NULL`, `GeneratedKeyApi TEXT NOT NULL` (server-issued opaque token used as the WP App Password username — see §05 §3.a), `Token TEXT NOT NULL` (the long-lived bearer token paired with `GeneratedKeyApi` per §05), `TempToken TEXT NULL` (short-lived rotation slot, NULL when no rotation in flight), `CreatedAt INTEGER NOT NULL`, `UpdatedAt INTEGER NOT NULL`; AND there MUST NOT be any column named `Password`, `PasswordHash`, `Salt`, `Pepper`, or any other plaintext-or-derived secret material — the schema is intentionally password-free because authentication uses WP App Passwords (§05 Lane A) or SSH key signatures (§31 Lane B), never a Profile-stored password; AND the `UNIQUE` constraint on `UserName` MUST be enforced at the SQLite level (not application level) so concurrent inserts cannot race past a `SELECT … WHERE UserName = ?` check.
- **Verifies:** brief §2 (Profile entity), §02 v3.8.6 (Profile table doc), §18 v2.9.3 (DDL), §05 (Lane A auth flow), §31 (Lane B auth flow), §39 (RootDbPath ConfigKv key).

### AC-07 — GitProfile URL canonicalization  `[active]`
- **Given** a GitProfile is saved with `IsOrganization` set to 0 or 1
- **When** the URL is normalized on save
- **Then** the trailing slash is optional on input, the stored URL is `github.com/$org/$repo` when `IsOrganization=1` else `github.com/$username/$repo`.
- **Verifies:** brief §Domain.2 + v3.8.0, §02.

### AC-08 — GitProfile.Acceptance enum  `[active]`
- **Given** a GitProfile row
- **When** the `Acceptance` column is read
- **Then** the value MUST be one of `AcceptAllRepos`, `AcceptSelectedRepoOnly`, `AcceptSelectedRepoInAllVersions` — any other value is a schema violation.
- **Verifies:** brief §Domain.3.b, §02.

### AC-09 — Branch restriction toggle  `[active]`
- **Given** a GitProfile row with `IsRestrictInBranch` flipped
- **When** the form re-renders AND a `/append-log` request is dispatched
- **Then** the `StrictBranch` field's UI visibility AND server-side enforcement BOTH track the toggle (no orphan enforcement when hidden, no orphan visibility when disabled).
- **Verifies:** brief §Domain.3.f, §05.

### AC-10 — Repo / RepoVersion split  `[active]`
- **Given** a GitHub URL with a `-vN` suffix
- **When** it is registered
- **Then** the `Repo` row stores the URL **stripped** of `-vN`, and a `RepoVersion` row stores the variant linked back to the parent Repo row by FK.
- **Verifies:** brief §Domain.5, §02, §18.

### AC-17 — App entity columns  `[active]`
- **Given** an `App` row exists in the SQLite root DB (per §02 v3.8.6 + §18 v2.9.3 DDL; this is the consumer-app identity table introduced by §07 locked decisions 10–12)
- **When** the row is read via `SELECT * FROM App WHERE AppId = :id`
- **Then** the result MUST contain exactly these columns and no others: `AppId INTEGER PRIMARY KEY AUTOINCREMENT`, `AppName TEXT NOT NULL`, `AppSlug TEXT NOT NULL UNIQUE` (kebab-case identifier used in `/append-log` payloads — `[a-z0-9][a-z0-9-]*` per §07 locked decision 11), `Description TEXT NULL`, `ProfileId INTEGER NOT NULL REFERENCES Profile(ProfileId) ON DELETE RESTRICT` (the App MUST be owned by exactly one Profile — RESTRICT prevents orphaning), `AppStatusId INTEGER NOT NULL REFERENCES AppStatus(AppStatusId) ON DELETE RESTRICT` (lookup table per §02; values `Active` / `Suspended` / `Archived` per §01 glossary), `CreatedAt INTEGER NOT NULL`, `UpdatedAt INTEGER NOT NULL`; AND `AppSlug` UNIQUE MUST be enforced at the SQLite level so two Profiles cannot register the same slug; AND the `App` table MUST NOT carry any of the (forbidden, blocked-on-user) Phase B1 fields `Environment`, `Platform`, `OwnerEmail` until the §07 App identity decision is unblocked — adding them speculatively is a schema violation per §07 locked decision 12.
- **Verifies:** locked decisions 10–12 (App entity scope), §02 v3.8.6 (App + AppStatus table doc), §18 v2.9.3 (DDL + FK constraints), §01 (AppStatus enum), §07 (Phase B1 blocked fields).

### AC-18 — Polymorphic AppLink  `[active]`
- **Given** an `AppLink` row insert is attempted (per §02 v3.8.6 + §18 v2.9.3 DDL — this table associates an `App` with EITHER a specific `Repo` OR a specific `GitProfile` for credential-inheritance scoping per §07 locked decision 10)
- **When** the row is committed
- **Then** the SQLite-level `CHECK` constraint MUST enforce XOR semantics: `CHECK ((RepoId IS NOT NULL) <> (GitProfileId IS NOT NULL))` — i.e. exactly one of the two FK columns is non-NULL, never both, never neither; AND inserting a row with both columns NULL MUST fail with SQLite error code 19 (`SQLITE_CONSTRAINT_CHECK`) — application-layer guards alone are NOT sufficient; AND inserting a row with both columns set MUST also fail with the same constraint error; AND the table MUST carry composite UNIQUE constraints `UNIQUE (AppId, RepoId)` and `UNIQUE (AppId, GitProfileId)` so a single App cannot link to the same Repo or GitProfile twice (the NULL side is exempt by SQLite's standard UNIQUE-with-NULL semantics, which is the desired behavior); AND both FKs MUST use `ON DELETE CASCADE` so deleting the parent `App`, `Repo`, or `GitProfile` automatically prunes the dangling link rows; AND the polymorphic discriminator is implicit (which column is non-NULL) — the table MUST NOT carry an explicit `LinkType TEXT` column because the XOR CHECK already encodes it and a separate column risks drift between `LinkType` and the actual non-NULL FK.
- **Verifies:** locked decision 10 (polymorphic linking design), §02 v3.8.6 (AppLink table doc), §18 v2.9.3 (DDL + CHECK + UNIQUE + FK CASCADE).

### AC-19 — App credential inheritance  `[active]`
- **Given** an App linked to a parent Profile
- **When** the App attempts an authenticated call
- **Then** the App MUST NOT carry its own `Token`/`TempToken`/`GeneratedKeyApi`; credentials resolve through `App.ProfileId → Profile`.
- **Verifies:** locked decision 11, §05.

### AC-20 — App lifecycle gates push  `[active]`
- **Given** an App with `AppStatus ∈ {Active, Disabled, Archived}`
- **When** a `/append-log` arrives bound to that App (via `Repo` or `GitProfile` linkage)
- **Then** only `Active` accepts; `Disabled` and `Archived` reject with the §15 envelope.
- **Verifies:** locked decision 12, §05, §15.

---

## Section C — Auth, Tokens & Lane Routing

### AC-15 — CI/CD URL+branch authoritative, TempToken non-authoritative  `[active]`
- **Given** a `/append-log` from CI/CD
- **When** the auth lane resolves
- **Then** the GitHub URL + branch combination is the authoritative identity; the `TempToken` is checked but is **non-authoritative** (it cannot grant access if URL/branch fails).
- **Verifies:** brief §Auth.4, §05, §25.

### AC-16 — JWT not implemented  `[active]`
- **Given** the v2 codebase and shipped DDL
- **When** any token-handling code path is inspected
- **Then** there MUST be no JWT issuance, validation, signing key, or `jwt.io`-style header parsing — only opaque Tokens (Lane A) and SSH signatures (Lane B).
- **Verifies:** brief §Auth + locked decision 5, §05, §25.

### AC-26 — Per-Profile rate limit  `[active]`
- **Given** a Profile is making sustained requests
- **When** the per-minute count exceeds `ConfigKv.RatePerMinPerProfile`
- **Then** subsequent requests in that minute return HTTP 429 with a `Retry-After` header; the bucket state survives the request boundary (in-memory + persisted floor).
- **Verifies:** §10, §05.

### AC-27 — Payload size enforcement  `[active]`
- **Given** an inbound `/append-log` body
- **When** the server reads the request
- **Then** `MaxPushPayloadBytes`, `MaxLinesPerPush`, `MaxLineBytes` are enforced **before parse**; an oversize line is truncated and tagged `Warn` (no `GL-*` error code raised) so a single bad line does not reject the whole push.
- **Verifies:** §10, §05.

### AC-35 — Wrong-lane rejection  `[active]`
- **Given** a Lane A credential is presented to a Lane B endpoint (or vice versa)
- **When** the auth resolver runs
- **Then** the response is `GL-AUTH-WRONG-LANE`; for Lane A, an unmatched `Email→Profile` resolves to `GL-AUTH-NO-PROFILE-LINK`.
- **Verifies:** §25, §15.

---

## Section D — Endpoints & Streaming

### AC-11 — Endpoint inventory  `[active]`
- **Given** the running plugin
- **When** the WP REST index is queried
- **Then** all 10 logical endpoints from §Endpoints exist with the documented field names; they fold to 8 HTTP paths in §17 via the `?q=` query-param collapse rule (rows #5/#6 share `/get-logs`; rows #7/#8 share `/get-pipeline-logs`).
- **Verifies:** brief §Endpoints, §04, §17.

### AC-12 — Streaming ingestion  `[active]`
- **Given** a client sends a request to `/append-log` (endpoint #1 per §04) carrying `Transfer-Encoding: chunked` (no `Content-Length` header) — typically used by CI runners forwarding live `tail -f` output
- **When** the server begins parsing the request body
- **Then** the server MUST consume the body as a stream — reading and parsing one JSON value (or one NDJSON line, if §04 §11 ingestion-side streaming is later adopted) at a time — and MUST NOT buffer the entire body into memory before validation begins; AND the §10 size caps (`ConfigKv.MaxPushPayloadBytes` default 1 MiB, `MaxLinesPerPush` default 10 000, `MaxLineBytes` default 64 KiB) MUST be enforced INCREMENTALLY against running counters: as soon as any threshold is crossed mid-stream, the server MUST abort by returning `GL-PAYLOAD-TOO-LARGE` (HTTP 413) per §15 with a partial-ingest indicator in the error envelope's `Message` (e.g. `"aborted at line 8217 of …"`); AND chunks already accepted before the abort MUST be rolled back (the entire `/append-log` request is atomic per AC-13's sticky-`HasError` contract — a partial commit would corrupt the per-SHA cursor); AND the server MUST set a hard wall-clock cap of `ConfigKv.AppendLogMaxStreamSec` (recommended default 30 s) to prevent slow-loris ingest exhausting the PHP-FPM worker pool — exceeding the cap MUST return `GL-INGEST-TIMEOUT` per §15; AND when the request omits `Transfer-Encoding: chunked` and instead sends `Content-Length: N`, the server MAY (but is not required to) enable streaming — buffered ingest is acceptable for known-bounded payloads under the size caps.
- **Verifies:** brief §Endpoints.2.b (chunked ingest), §04 (endpoint #1 contract), §10 (size cap matrix), §15 (`GL-PAYLOAD-TOO-LARGE`, `GL-INGEST-TIMEOUT`), AC-13 (atomicity coupling).

### AC-13 — HasError sticky until fixed-log  `[active]`
- **Given** an `/append-log` with `HasError=true` is accepted for a Pipeline
- **When** subsequent requests for the same Pipeline are read
- **Then** `Pipeline.HasError=1` remains until a `/fixed-log` for that Pipeline clears it back to 0.
- **Verifies:** brief §Endpoints.2.c, §04.

### AC-14 — Structured ack with Retrieval hints  `[active]`
- **Given** any write endpoint returns 200/202
- **When** the response body is parsed
- **Then** it contains a `Retrieval` block with the canonical read-back URL(s) for the just-written entity.
- **Verifies:** brief §Endpoints.1.a–b, §04.

### AC-30 — Error envelope shape + RequestId mirroring  `[active]`
- **Given** any endpoint rejects a request
- **When** the response body is read
- **Then** it matches `{Status, Code, Message, RequestId, HttpStatus}` AND `RequestId` appears in the corresponding `AuditTrail.RequestId` row.
- **Verifies:** §15, §10.

### AC-37 — Prometheus metrics endpoint  `[active]`
- **Given** the metrics endpoint `GET /wp-json/git-logs/v2/metrics`
- **When** an authenticated client with `HistoryView` permission requests it
- **Then** the response is Prometheus exposition format; counter values are flushed every 5 minutes via `wp_cron`; missing permission ⇒ 403.
- **Verifies:** §20.

### AC-40 — OpenAPI parity  `[active]`
- **Given** `17-openapi.yaml`
- **When** it is loaded by an OpenAPI 3.1 parser
- **Then** the parse succeeds, all 10 endpoints are covered, and the error schema references `15-error-codes.md`'s envelope.
- **Verifies:** §17.

---

## Section E — Logging, Migrations & Roles

### AC-03 — Migration runs once per version  `[active]`
- **Given** the plugin boots at version `X.Y.Z`
- **When** the migration runner inspects `MigrationState`
- **Then** the V`X_Y_Z` migration runs exactly once; subsequent boots of the same version short-circuit.
- **Verifies:** brief §3.b–e, §06, §12.

### AC-04 — Logger level gating  `[active]`
- **Given** `ConfigKv.LogLevelMin` is set
- **When** a log line is emitted
- **Then** the logger supports `Trace/Debug/Info/Warn/Error/Fatal` and lines below `LogLevelMin` are dropped at runtime (not just at sink).
- **Verifies:** brief §3.f, §06.

### AC-05 — Diagnostic dedup window  `[active]`
- **Given** two identical diagnostic log lines
- **When** they arrive within 60 seconds of each other for the same source
- **Then** the second is deduplicated (not written) — error logs are NOT subject to this rule.
- **Verifies:** brief §3.g, §06.

### AC-06 — Roles in plugin DB, permission-name authz  `[active]`
- **Given** a permission check at any gate
- **When** the resolver runs
- **Then** it queries the plugin's `RolePermission` join (in plugin SQLite, NOT WP roles) and matches by **permission name**, never by role name string.
- **Verifies:** brief §4–5, §19.

### AC-29 — MigrationInterface contract  `[active]`
- **Given** the `inc/Migrations/V{Major}_{Minor}_{Patch}.php` files
- **When** each is loaded
- **Then** each implements `MigrationInterface`; `MigrationState` is keyed by `PluginVersion`; re-runs at the same version are idempotent (no duplicate rows, no schema drift).
- **Verifies:** §06, §12.

### AC-39 — Permission gate hides buttons  `[active]`
- **Given** a UI element bound to a permission
- **When** the current user lacks that permission
- **Then** the button is **hidden** (removed from DOM), not merely `disabled`; the underlying REST route also rejects with the §15 envelope.
- **Verifies:** §19.

---

## Section F — Audit & Activity

### AC-21 — Four audit tables coexist  `[active]`
- **Given** the v3.8.0+ schema
- **When** the audit surface is inventoried
- **Then** four tables coexist with the §08 split: `AuditTrail` (HTTP forensics), `History` (per-RepoVersion git timeline), `PipelineAction` (renamed from `Action` in v3.8.0 — pipeline-bound), `SystemEvent` (NEW v3.8.0 — non-Git business events).
- **Verifies:** locked decision 13 + v3.8.0, §08, §18.

### AC-38 — AuditActionType seed rows  `[active]`
- **Given** a fresh migration to v2.9.1
- **When** `AuditActionType` is queried
- **Then** rows include `Prune` (19), `Restore` (20), and SSH lane rows `SshKeyRegister` (22), `SshKeyRevoke` (23), `SshKeyRotate` (24) per `18-schema.sql` seed.
- **Verifies:** §22, §23, §18.

### AC-57 — SystemEvent table shape  `[active]`
- **Given** the v3.8.2+ schema
- **When** `SystemEvent` is inspected
- **Then** it has columns `(SystemEventId PK, SystemEventTypeId FK, ActorProfileId FK NULL, TargetType TEXT NULL, TargetId INTEGER NULL, Summary TEXT, DetailJson TEXT, OccurredAt INTEGER)` with indexes `(SystemEventTypeId, OccurredAt)`, `(ActorProfileId, OccurredAt)`, `(TargetType, TargetId, OccurredAt)`; `TargetType`/`TargetId` carry **no** FK CHECK so audit history outlives target rows.
- **Verifies:** §02, §08, §18 + v3.8.2.

### AC-58 — SystemEventType seed inventory  `[active]`
- **Given** a fresh migration to v3.8.2
- **When** `SystemEventType` is queried
- **Then** exactly 16 rows exist in this order: `ProfileCreated`, `ProfileDeleted`, `ProfileStatusChanged`, `RoleAssigned`, `RoleRevoked`, `GitProfileCreated`, `GitProfileAcceptanceChanged`, `GitProfileBranchRestrictionChanged`, `AppCreated`, `AppStatusChanged`, `AppLinkAdded`, `AppLinkRemoved`, `SshKeyRegistered`, `SshKeyRevoked`, `SshKeyRotated`, `TempTokenRotated`.
- **Verifies:** §01, §18 + v3.8.2.

---

## Section G — Schema Conventions & Diagrams

### AC-22 — Diagram inventory  `[active]`
- **Given** folder `26-gitlogs-diagrams/`
- **When** it is listed
- **Then** it contains Mermaid sources for ER, domain, endpoint, auth, and permission diagrams.
- **Verifies:** brief §Diagrams, §26.

### AC-23 — PascalCase + AUTOINCREMENT PK  `[active]`
- **Given** any table or JSON payload in the codebase
- **When** identifiers are inspected
- **Then** all table names, column names, JSON keys, and JSON enum values use PascalCase; primary keys are `INTEGER AUTOINCREMENT` named `{Table}Id` (no `id`, `pk`, or snake_case).
- **Verifies:** brief §DB.2–4, §02, §18.

### AC-24 — Enums modeled twice  `[active]`
- **Given** any typed value (severity, status, lane mode, etc.)
- **When** it is referenced
- **Then** it is modeled as an Enum in code AND as a lookup table in the DB; comparisons MUST use the lookup ID, never a string literal.
- **Verifies:** brief §DB.5, §01, §18.

### AC-55 — No legacy OwnerType  `[active]`
- **Given** `18-schema.sql`
- **When** it is parsed
- **Then** it MUST NOT create the `OwnerType` table, MUST NOT seed `OwnerType` rows, AND `GitProfile` MUST declare `IsOrganization INTEGER NOT NULL DEFAULT 0 CHECK (IsOrganization IN (0,1))` in place of `OwnerTypeId`.
- **Verifies:** §18 + v3.8.1.

### AC-56 — PipelineAction rename completeness  `[active]`
- **Given** `18-schema.sql`
- **When** the action surface is inspected
- **Then** there MUST be no `ActionType` or `Action` tables; the lookup is `PipelineActionType`; the audit-row table is `PipelineAction`; `History.ActionTypeId` is renamed to `History.PipelineActionTypeId` and references `PipelineActionType(PipelineActionTypeId)`.
- **Verifies:** §18 + v3.8.2.

---

## Section H — Per-SHA Split-DB (v2.9.0)

### AC-49 — Per-SHA file creation on first append  `[active]`
- **Given** an accepted `/append-log` for a `(PipelineId, Sha)` pair never seen before
- **When** the write commits
- **Then** the server creates `<dataDir>/<ShaLogsRoot>/<Sha[0:2]>/<Sha>.db` and a `ShaRegistry` row in the root DB keyed `UNIQUE(PipelineId, Sha)`; `LogEntry`/`ErrorLogEntry` tables MUST NOT exist in the root DDL — all log lines live exclusively in the per-SHA file.
- **Verifies:** §18, §39, §22.

### AC-50 — ShaRegistry mirrors per-SHA stats  `[active]`
- **Given** any per-SHA file is updated
- **When** the write completes
- **Then** `ShaRegistry` mirrors `RowCount`, `LastSeenAt`, `FileSizeBytes`, `Sha256` so dashboards and the prune planner can render summaries / compute eligibility WITHOUT opening the per-SHA file.
- **Verifies:** §18, §39.

### AC-51 — Per-SHA file is self-contained  `[active]`
- **Given** any `<Sha>.db` is exported, zipped, or handed to support
- **When** it is opened standalone (no root DB)
- **Then** it carries denormalized `LogSeverity` lookup + a single-row `ShaMeta` identity, so it is interpretable without root-DB context.
- **Verifies:** §39.

### AC-52 — Open per-SHA handle pool  `[active]`
- **Given** the running plugin holds open `<Sha>.db` connections
- **When** open count is measured per process
- **Then** it is capped at `ConfigKv.MaxOpenShaDbHandles` (default `32`) with LRU eviction; idle handles are closed after `ConfigKv.ShaDbIdleCloseSec` seconds (default `120`); pool refusal raises `GL-SHA-DB-QUOTA-EXCEEDED` (HTTP 507).
- **Verifies:** §15, §39.

### AC-53 — Prune / backup / restore / wipe lifecycle  `[active]`
- **Given** the per-SHA tree exists with N files
- **When** `wp git-logs prune` / `backup` / `restore` / uninstall `Wipe` runs
- **Then** **prune** walks `ShaRegistry` (eligibility on `LastSeenAt` + `Pipeline.HasError` + history-window guard), per row does `rename → delete-row → unlink` for crash-safety, and removes empty `<Sha[0:2]>/` shard folders while preserving the `<ShaLogsRoot>` root; **backup** writes a directory tree (`git-logs.sqlite` + `manifest.json` + `logs/<aa>/<sha>.db…`) with `manifest.ShaFiles[]` recording `{PipelineId, Sha, DbFilePath, RowCount, FileSizeBytes, Sha256}`; **restore** is all-or-nothing with `.bak`/`logs.bak.<ts>/` rollback and verifies sha256 against the manifest (drift ⇒ `GL-SHA-DB-CHECKSUM-MISMATCH`); **uninstall Wipe** deletes the per-SHA tree first, root DB last, then `rmdir`'s the parent.
- **Verifies:** §15, §22, §23, §29, §39.

### AC-31 — Prune CLI guards  `[active]`
- **Given** `wp git-logs prune --older-than=Nd`
- **When** N is provided
- **Then** the CLI enforces a 7d floor; refuses while a migration is pending; uses two-phase delete (logical first, physical after); ends with `wal_checkpoint(TRUNCATE)`; emits an `AuditActionType.Prune` row.
- **Verifies:** §22.

### AC-32 — Backup / restore cross-version safety  `[active]`
- **Given** `wp git-logs backup` / `restore`
- **When** they run
- **Then** backup uses SQLite Online Backup API + manifest JSON; restore refuses without maintenance mode unless `--force`; downgrade across **major** versions is always refused.
- **Verifies:** §23.

### AC-33 — Verify CLI surfaces  `[active]`
- **Given** `wp git-logs verify`
- **When** it runs
- **Then** it executes `integrity_check` + `foreign_key_check` + asserts `Profile≥1` + asserts `MigrationState.PluginVersion = ConfigKv.PluginVersion`; the result surfaces in the WP Site Health card.
- **Verifies:** §20, §23.

### AC-34 — Multisite per-site DB  `[active]`
- **Given** a multisite install (network-activated or per-site)
- **When** any site is opened
- **Then** each site has its own DB file (no shared file even when network-activated); reads stay site-scoped (no cross-site fan-out).
- **Verifies:** §24.

### AC-36 — Translatable scope  `[active]`
- **Given** the POT extraction CI step
- **When** strings are categorized per §21
- **Then** GL-* codes, enum names, Permission names, REST routes, hook names, `ConfigKv` keys, and audit `Detail` keys remain English; the POT diff CI gate passes.
- **Verifies:** §21.

### AC-41 — WP.org release ZIP  `[active]`
- **Given** the release CI job
- **When** the ZIP is assembled
- **Then** it contains `readme.txt` + `screenshot-1..8.png`; the CI gate runs `wp plugin check` before tagging.
- **Verifies:** §26.

---

## Section I — SSH-Key Lane B (v2.9.1) — NEW in Phase 7

> All ACs in this section are **active** for schema v2.9.1 / docs v3.8.7+. They formalize the contracts laid down in §05 (auth flow), §15 (error codes), §18 (DDL), §28 (GH-Actions example), §30 (threat model), §31 (Lane B reference).

### AC-60 — SshKey registration shape  `[active]`
- **Given** an admin registers a deploy key via the SshKey UI or REST
- **When** the row is committed to `SshKey`
- **Then** the row has `Fingerprint TEXT UNIQUE NOT NULL`, `RepoId FK → Repo ON DELETE CASCADE`, `KeyType` (recommended `ssh-ed25519`), `PublicKey`, `Label`, `OwnedByProfileId FK → Profile`, `IsActive INTEGER CHECK 0/1 DEFAULT 1`, `LastUsedAt`, `CreatedAt`, `RevokedAt`; AND a `SystemEvent` of type `SshKeyRegistered` is appended; AND an `AuditTrail` row of `AuditActionType.SshKeyRegister` (id 22) is written.
- **Verifies:** §18, §31, §08, §22 (AuditActionType seed).

### AC-61 — SshNonce replay defense  `[active]`
- **Given** a valid SSH-signed `/append-log` arrives with headers `X-GL-Fingerprint`, `X-GL-Timestamp`, `X-GL-Nonce`, `X-GL-Signature`
- **When** the server validates
- **Then** (a) `|now − X-GL-Timestamp| ≤ ConfigKv.SshReplayWindowSec` else `GL-SSH-TIMESTAMP-SKEW`; (b) `INSERT OR IGNORE INTO SshNonce(SshKeyId, Nonce, SeenAt)` with `affected_rows=0` ⇒ `GL-SSH-NONCE-REUSED`; (c) the nonce table is per-key (`UNIQUE(SshKeyId, Nonce)`), NOT global, so one tenant cannot DoS another's nonce space; (d) `SshNonceJanitorBatch` rows are pruned per request keeping the table bounded.
- **Verifies:** §05, §15, §18, §30 (S5).

### AC-62 — Lane gating via SshAuthMode  `[active]`
- **Given** `ConfigKv.SshAuthMode ∈ {optional, preferred, required}`
- **When** an inbound request is dispatched
- **Then** **`optional`**: both Lane A (TempToken) and Lane B (SSH) are accepted; **`preferred`**: both accepted but Lane A logs a deprecation warning header; **`required`**: any TempToken submission rejects with `GL-AUTH-LANE-DISABLED` regardless of header presence; AND a request carrying BOTH `X-GL-Auth-Mode: ssh` AND a body `TempToken` always rejects with `GL-SSH-LANE-CONFLICT` (no quiet fallback).
- **Verifies:** §05, §15, §18 (ConfigKv defaults), §30 (S7).

### AC-63 — Signature stripping defense  `[active]`
- **Given** an inbound request claims Lane B (`X-GL-Auth-Mode: ssh`)
- **When** any of `X-GL-Fingerprint`, `X-GL-Timestamp`, `X-GL-Nonce`, `X-GL-Signature` is missing
- **Then** the request rejects with `GL-SSH-HEADER-MISSING` **before** signature verification runs (header-completeness check ordered first); AND HTTPS is mandatory at the deployment surface so on-path header rewrite is blocked at TLS.
- **Verifies:** §05, §15, §30 (S7).

### AC-64 — SshKey rotation flow  `[active]`
- **Given** an active `SshKey` row with `IsActive=1`
- **When** an admin registers a replacement and flips the old row to `IsActive=0`
- **Then** the very next request signed by the old key rejects with `GL-SSH-KEY-INACTIVE` (no propagation delay, no cache); AND `SystemEvent.SshKeyRevoked` is appended for the old; AND `SystemEvent.SshKeyRotated` is appended capturing the new fingerprint; AND `AuditTrail` rows of types `SshKeyRevoke` (23) and `SshKeyRotate` (24) are written.
- **Verifies:** §31, §18, §08, §22 (AuditActionType seed), §30 (S6).

### AC-65 — Deploy-key one-Repo blast radius  `[active]`
- **Given** a stolen `SshKey` private key
- **When** the attacker attempts to forge `/append-log` for a different `RepoId`
- **Then** the request rejects with `GL-SSH-REPO-MISMATCH` because `SshKey.RepoId` is bound to exactly one Repo (FK with `ON DELETE CASCADE`); AND `SshKey.LastUsedAt` is updated on every accepted request so anomaly detection on the admin UI surfaces theft fast; AND the per-Profile rate limit (`RatePerMinPerProfile`) caps the blast radius even within the bound Repo.
- **Verifies:** §31, §18, §05, §10, §30 (S6).

### AC-66 — Canonical signing string + namespace  `[active]`
- **Given** a CI runner signs a request per the §28 / §31 contract
- **When** it constructs the signing input
- **Then** it builds exactly `GL-SSHSIG-V1\nMETHOD\nPATH\nTIMESTAMP\nNONCE\nsha256(body)` (LF-separated, no trailing newline) and signs via `ssh-keygen -Y sign -n git-logs@v2 -H sha512`; AND the server uses the same string + namespace to verify; AND any deviation (different namespace, missing field, CRLF separators) rejects with `GL-SSH-SIGNATURE-INVALID`.
- **Verifies:** §28, §31, §15, §05.

---

## Section J — NDJSON Streaming Retrieval (v2.9.3) — NEW in Phase 11

### AC-67 — NDJSON opt-in via Accept header  `[active]`
- **Given** a client issues a GET to one of `/get-logs`, `/get-pipeline-logs`, `/get-error-logs`, `/get-pipeline-error-logs` (endpoints #5–#10 per §04 §11.7)
- **When** the request carries `Accept: application/x-ndjson` (alone, or with a lower q-value alternative such as `application/x-ndjson, application/json;q=0.5`)
- **Then** the server MUST respond with `Content-Type: application/x-ndjson; charset=utf-8`, `Transfer-Encoding: chunked`, `X-Content-Type-Options: nosniff`, and MUST NOT set `Content-Length`; AND if the same request is sent without the header (or with `Accept: application/json`), the server MUST return the legacy `LogPage` / `ErrorLogPage` JSON envelope per §17 OpenAPI without setting any of the streaming headers; AND for write endpoints #1–#4 (`/append-log`, `/fixed-log`, `/clear-log`, `/clear-log-all`) the `Accept: application/x-ndjson` header MUST be silently ignored — these endpoints always return the standard JSON `AckResponse`.
- **Verifies:** §04 §11.2, §04 §11.7, §17 (paths `/get-*` content variants).

### AC-68 — Frame ordering and discriminator  `[active]`
- **Given** an NDJSON stream is opened against any of the 6 read endpoints
- **When** the server flushes frames over the socket
- **Then** exactly one `Header` frame MUST be the first line, with `Schema:"git-logs-v2/ndjson@1"` and a non-empty UUID `StreamId`; AND zero or more `Log` / `ErrorLog` / `Progress` frames MUST follow in cursor order with monotonically increasing `Seq` (no gaps, no duplicates within a single uninterrupted stream); AND at most one `Error` frame MAY appear; AND exactly one `End` frame MUST be the last line with `Status ∈ {Complete, Truncated, Error}`; AND every line MUST carry a `Type` discriminator matching one of the six values declared in `components.schemas.NdjsonFrame`; AND lines MUST be separated by exactly one LF (`\n`, U+000A) — never CRLF; AND the server MUST NOT split a single JSON object across `\n`.
- **Verifies:** §04 §11.3, §04 §11.4, §17 `components.schemas.NdjsonFrame` discriminator mapping.

### AC-69 — Resume via after-seq + stream-id  `[active]`
- **Given** an earlier stream returned `End{Status:"Truncated", NextAfterSeq:N}` — typically because `ConfigKv.NdjsonMaxRowsPerStream` (default `1000000`) was hit — or the client recorded the last successfully received `Seq` before disconnect
- **When** the client re-issues the same request with `?after-seq=N` (and optionally `?stream-id=<original Header.StreamId UUID>` for audit correlation)
- **Then** the new stream MUST emit a fresh `Header` frame with a NEW `StreamId` and resume row emission strictly after `Seq=N` (exclusive); AND if the per-SHA `.db` file referenced by the original cursor has since been pruned per AC-53, the response MUST be `Header` → `Error{Code:"GL-NDJSON-CURSOR-LOST"}` → `End{Status:"Error"}` per §15 v2.9.3; AND `?after-seq` and `?stream-id` MUST be ignored on legacy `application/json` responses (no error, no behavior change) so accidental presence does not break non-streaming clients.
- **Verifies:** §04 §11.6, §15 (`GL-NDJSON-CURSOR-LOST`), AC-53 (prune lifecycle).

### AC-70 — Client disconnect handling (GL-NDJSON-CLIENT-DISCONNECT)  `[active]`
- **Given** a streaming response is in progress with the per-SHA SQLite handle open via the AC-52 LRU pool
- **When** the client closes the TCP connection mid-stream (EPIPE / ECONNRESET / browser tab close)
- **Then** the server MUST detect the broken pipe within 1 flush cycle (≤ `NdjsonProgressEveryMs` worst case, default 2000 ms); AND MUST return the per-SHA handle to the AC-52 pool (no leak); AND MUST abandon the cursor without retry; AND MUST write exactly one `AuditTrail` row with `Code="GL-NDJSON-CLIENT-DISCONNECT"` (HTTP 499, informational, server-side audit only); AND MUST NOT attempt to send any further frames (the socket is gone — no `Error` frame, no `End` frame).
- **Verifies:** §04 §11.4 step 4, §15 (`GL-NDJSON-CLIENT-DISCONNECT`), AC-52 (handle pool).

### AC-71 — Per-frame size cap and truncation  `[active]`
- **Given** the server is about to emit a `Log` or `ErrorLog` frame whose serialized JSON exceeds `ConfigKv.NdjsonMaxFrameBytes` (default `262144` = 256 KiB)
- **When** the frame is composed
- **Then** the server MUST truncate `LogText` to fit within the cap (preserving valid UTF-8 — never emit a partial multi-byte sequence), MUST add `"Truncated":true` to the same frame (mirroring AC-27 ingest-side `Warn` truncation semantics), and MUST emit the line atomically with a single trailing `\n`; AND the server MUST NOT split the object across multiple lines; AND `Seq` numbering MUST treat the truncated frame as one row (no gap, no duplicate).
- **Verifies:** §04 §11.4 step 3, §04 §11.5 (`NdjsonMaxFrameBytes`), AC-27 (ingest truncation parity).

### AC-72 — Progress frame cadence  `[active]`
- **Given** a long-running stream is walking the per-SHA cursor
- **When** either `ConfigKv.NdjsonProgressEveryRows` rows have been emitted since the last `Progress` (default `10000`) OR `ConfigKv.NdjsonProgressEveryMs` milliseconds have elapsed since the last `Progress` (default `2000`), whichever fires first
- **Then** the server MUST emit one `Progress` frame carrying `Seq` (continuing the monotonic sequence), `RowsEmitted` (cumulative since `Header`), `ElapsedMs` (cumulative since `Header`), and OPTIONALLY `CurrentSha` (the SHA the cursor is currently inside); AND if either ConfigKv key is set to `0`, that trigger MUST be disabled (rows-only or time-only progress); AND if both are `0`, NO `Progress` frames MUST be emitted at all.
- **Verifies:** §04 §11.5 (`NdjsonProgressEveryRows`, `NdjsonProgressEveryMs`), §04 §11.3 `Progress` frame schema.

---

## Section K — Pipeline.PreviousHasError State Transitions (v2.9.2) — NEW in Phase 12

### AC-73 — State-transition label matrix  `[active]`
- **Given** a `Pipeline` row exists with `HasError ∈ {0,1}` and `PreviousHasError ∈ {0,1}` (both columns `NOT NULL` per §18 v2.9.2 DDL)
- **When** any consumer (admin UI per §03, NDJSON `Header` frame per §04 §11.3 + AC-74, audit/analytics per §22) needs to label the pipeline's current run state
- **Then** the label MUST be derived purely from the `(PreviousHasError, HasError)` tuple per the §01 glossary v3.8.10 mapping: `(0,0) → "still-green"`, `(0,1) → "first-failure"`, `(1,1) → "still-failing"`, `(1,0) → "just-recovered"`; AND no other label values are permitted (the four are an exhaustive enum); AND consumers MUST NOT invent a fifth label such as `"unknown"` or `"initial"` — newly-inserted rows whose first observation is a failure MUST be labeled `"first-failure"` (because the back-fill rule of AC-75 ensures `PreviousHasError=0` for fresh rows by virtue of the column `DEFAULT 0`); AND label derivation MUST be a pure function (no DB lookup beyond the two columns already on the row) so the same `(PreviousHasError, HasError)` tuple ALWAYS produces the same label across all consumers.
- **Verifies:** §01 glossary v3.8.10 (`PreviousHasError` row), §02 v3.8.10 (`Pipeline.PreviousHasError`), §18 v2.9.2 (DDL + `CHECK (PreviousHasError IN (0,1))`), §03 (admin UI consumer), §04 §11.3 + AC-74 (NDJSON Header consumer).

### AC-74 — NDJSON Header.StateTransition exposure  `[active]`
- **Given** an NDJSON streaming response is being opened for any read endpoint scoped to a single `PipelineId` (i.e. `/get-pipeline-logs` or `/get-pipeline-error-logs` per §04 §11.7 endpoints #7–#10) AND the request matches exactly one `Pipeline` row
- **When** the server composes the `Header` frame per AC-68
- **Then** the `Header` frame MAY (but is not required to) carry an OPTIONAL `StateTransition` field whose value is the AC-73 label derived from the row's current `(PreviousHasError, HasError)` tuple; AND when present, the value MUST be one of the four AC-73 enum strings exactly (no casing variants, no whitespace); AND when the request resolves to zero pipelines (404 case) or to multiple pipelines (e.g. `/get-logs` repo+branch scope), the `StateTransition` field MUST be absent from the `Header` frame entirely (NEVER emit `null`, NEVER emit `"unknown"`); AND the field's absence MUST NOT be treated as an error by clients — older spec versions and broader-scope endpoints simply omit it; AND if the field is present, the OpenAPI `NdjsonHeaderFrame` schema (§17 v2.9.3+) MUST declare it as an optional string with the four-value enum constraint matching AC-73.
- **Verifies:** §04 §11.3 (`Header` frame), §17 (`components.schemas.NdjsonHeaderFrame`), AC-68 (frame ordering + Header semantics), AC-73 (label enum).

### AC-75 — Back-fill correctness + single-statement write atomicity  `[active]`
- **Given** a v2.9.1 → v2.9.2 schema upgrade is being applied OR a server-side mutation is updating `Pipeline.HasError`
- **When** the migration runs OR a row is updated
- **Then** the migration MUST execute exactly `UPDATE Pipeline SET PreviousHasError = HasError;` as a single SQL statement (so every existing row's first post-upgrade transition is labeled `still-green` or `still-failing` per AC-73, NEVER spuriously labeled `first-failure` or `just-recovered`) — the migration MUST NOT use a row-by-row loop and MUST NOT default `PreviousHasError` to `0` for rows whose `HasError=1` (which would produce a fake `just-recovered` label on the next clean run); AND every server-side mutation of `HasError` MUST also update `PreviousHasError` in the SAME SQL statement using a `CASE` or self-referencing `UPDATE` so the read-modify-write window is zero (e.g. `UPDATE Pipeline SET PreviousHasError = HasError, HasError = :new_value, UpdatedAt = strftime('%s','now') WHERE PipelineId = :id`) — clients MUST NOT issue a separate `SELECT HasError` followed by `UPDATE … SET HasError, PreviousHasError` because two concurrent writers could observe the same `OLD.HasError` and produce inconsistent transition labels; AND if the application layer cannot express this atomically (e.g. an ORM that always splits read+write), the spec REQUIRES wrapping the mutation in a `BEGIN IMMEDIATE; … COMMIT;` SQLite transaction to serialize concurrent writers AND verifying via `SELECT changes() = 1` that exactly one row was touched (zero-row updates indicate a stale `PipelineId`, multi-row updates indicate a missing `WHERE PipelineId = :id` clause); AND `MigrationState` MUST record marker `2.9.2` exactly once per database after the back-fill `UPDATE` succeeds — re-running the migration MUST be a no-op (idempotent) because the `UPDATE` re-executes harmlessly on already-back-filled rows (`PreviousHasError = HasError` is the steady-state invariant immediately after each write and immediately after back-fill, but diverges briefly between writes — that's the whole point).
- **Verifies:** §18 v2.9.2 (DDL + back-fill comment block + write-rule comment block), §02 v3.8.10 (`Pipeline.PreviousHasError` doc), §01 v3.8.10 (`PreviousHasError` glossary entry — back-fill rule + write rule).

---

## Cross-References

- [Module overview](./00-overview.md)
- [Module changelog](./98-changelog.md)
- [Module consistency report](./99-consistency-report.md)
- [Auth flow §05](./05-auth-and-validation.md)
- [Error codes §15](./15-error-codes.md)
- [Schema DDL §18](./18-schema.sql)
- [GH-Actions SSH example §28](./28-example-github-actions.md)
- [Threat model §30](./30-threat-model.md)
- [SSH-Key Lane B reference §31](./31-ssh-key-auth.md)
- [Per-SHA storage §39](./39-per-sha-storage.md)
