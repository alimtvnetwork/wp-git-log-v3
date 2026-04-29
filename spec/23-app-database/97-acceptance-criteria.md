# Acceptance Criteria — 23 App Database

**Version:** 3.2.0
**Updated:** 2026-04-29
**Scope:** `spec/23-app-database/`
**Generated:** Hand-authored alongside the v4.0.0 overview (Phase 39a). Supersedes the auto-extracted v2.0.0 set.

---

## Module Summary

Defines the App, AppLink, AppStatus, and AppLinkType tables — the polymorphic-link subsystem that binds inbound CI/CD pushes to a Profile. Enforces SQLite + PascalCase + Rule 12 (forward-only, NULLABLE, no DEFAULT) migration discipline, plus two AppLink CHECK invariants (XOR target + disconnect timestamp).

---

## Inlined Contracts

> Required artifacts inlined here so each AC is self-contained — a mediocre AI does not need to chase cross-links.

### Tables under test

- `App(AppId PK, AppName, AppSlug UNIQUE, Description NULL, ProfileId FK, AppStatusId FK, CreatedAt, UpdatedAt)`
- `AppLink(AppLinkId PK, AppId FK, AppLinkTypeId FK, TargetGitProfileId NULL FK, TargetRepoId NULL FK, IsActive 0/1, CreatedAt, DisconnectedAt NULL)`
- `AppStatus(AppStatusId PK, Name UNIQUE)` — seeds: `Active`, `Disabled`, `Archived`
- `AppLinkType(AppLinkTypeId PK, Name UNIQUE)` — seeds: `GitProfile`, `Repo`

### Rule 12 — Migration constraints (forward-only)

- New columns: `NULLABLE`, **no** `DEFAULT`.
- No `DROP TABLE` / `DROP COLUMN` in migrations.
- No `ROLLBACK` / `DOWN` blocks; reversibility is achieved by writing a new forward migration.

### AppLink CHECK invariants

- **XOR target:** if `AppLinkType.Name = 'Repo'` then `TargetRepoId IS NOT NULL AND TargetGitProfileId IS NULL`; symmetric for `'GitProfile'`.
- **Disconnect timestamp:** `IsActive = 1 ⇔ DisconnectedAt IS NULL`.

### File paths

- Verification script: `linter-scripts/check-forbidden-strings.py`
- DDL source of truth: `spec/23-app-database/00-overview.md` § "Inlined Contracts"
- Sibling table DDL: `spec/22-git-logs-v2/02-database-schema.md`, `spec/22-git-logs-v2/18-schema.sql`

---

## Acceptance Criteria

### AC-ADB-01: Forward-only migrations  `[critical]`
- **Given** A migration file under `migrations/` that touches `App`, `AppLink`, `AppStatus`, or `AppLinkType`.
- **When** The file is parsed for SQL keywords.
- **Then** It MUST NOT contain any of: `DROP TABLE`, `DROP COLUMN`, `ROLLBACK`, `BEGIN ROLLBACK`, or a `-- DOWN` marker section.
- **Verifies:** `00-overview.md` § "Migration Template (Rule 12 — forward-only)"

### AC-ADB-02: PascalCase enforcement  `[high]`
- **Given** Any `CREATE TABLE` or `ALTER TABLE … ADD COLUMN` statement targeting the App subsystem.
- **When** Identifiers are extracted via regex.
- **Then** Every table name and column name MUST match `^[A-Z][A-Za-z0-9]*$` (PascalCase, no underscores, no leading lowercase).
- **Verifies:** `00-overview.md` § "Convention recap (binding)"

### AC-ADB-03: New columns NULLABLE without DEFAULT  `[critical]`
- **Given** An `ALTER TABLE … ADD COLUMN` statement in any new migration.
- **When** The column definition is parsed.
- **Then** It MUST contain `NULL` (or omit `NOT NULL`) AND MUST NOT contain a `DEFAULT` clause.
- **Verifies:** Rule 12 in `00-overview.md`

### AC-ADB-04: Forbidden-strings linter passes  `[critical]`
- **Given** A clean working tree on the spec branch.
- **When** Running `python3 linter-scripts/check-forbidden-strings.py`.
- **Then** Exit code MUST be `0`. Any non-zero exit blocks merge.
- **Verifies:** `00-overview.md` § Verification

### AC-ADB-05: AppLink XOR target invariant  `[critical]`
- **Given** An `INSERT` into `AppLink` with `AppLinkType.Name = 'Repo'`.
- **When** The CHECK constraint is evaluated by SQLite.
- **Then** The insert MUST succeed only if `TargetRepoId IS NOT NULL AND TargetGitProfileId IS NULL`. The symmetric rule applies when `Name = 'GitProfile'`.
- **Verifies:** DDL `CHECK` clause for `AppLink` in `00-overview.md`

### AC-ADB-06: AppLink disconnect-timestamp invariant  `[high]`
- **Given** Any row in `AppLink`.
- **When** The row is inserted or updated.
- **Then** `IsActive = 1` MUST imply `DisconnectedAt IS NULL`; `IsActive = 0` MUST imply `DisconnectedAt IS NOT NULL`.
- **Verifies:** DDL `CHECK` clause for `AppLink` in `00-overview.md`

### AC-ADB-07: Reconnect inserts a new row  `[medium]`
- **Given** An AppLink row that was previously soft-disconnected (`IsActive = 0`).
- **When** The link is re-established for the same `(AppId, target)`.
- **Then** A NEW `AppLink` row MUST be inserted (per Q3); the existing disconnected row MUST NOT be flipped back to `IsActive = 1`.
- **Verifies:** `00-overview.md` § "Q3 — Reconnect"

### AC-ADB-08: AppSlug uniqueness  `[medium]`
- **Given** Two App rows being inserted with the same `AppSlug`.
- **When** The second `INSERT` executes.
- **Then** SQLite MUST raise a `UNIQUE constraint failed: App.AppSlug` error.
- **Verifies:** `App.AppSlug TEXT NOT NULL UNIQUE` in DDL

### AC-ADB-09: Lookup seeds present after migration  `[medium]`
- **Given** A freshly migrated database.
- **When** Selecting from the lookup tables.
- **Then** `AppStatus.Name` MUST contain exactly `{Active, Disabled, Archived}` and `AppLinkType.Name` MUST contain exactly `{GitProfile, Repo}`.
- **Verifies:** `00-overview.md` § "Seed data (lookup tables)"

### AC-ADB-10: Push attribution prefers Repo over GitProfile link  `[high]`
- **Given** An App that has both an active Repo-typed `AppLink` and an active GitProfile-typed `AppLink` resolving to the same inbound `RepoUrl`.
- **When** Query Q1 from `00-overview.md` is executed.
- **Then** The returned App row MUST be the one matched via the **Repo** link (more specific wins), per the `ORDER BY (lt.Name = 'Repo') DESC` clause.
- **Verifies:** `00-overview.md` § "Q1 — Resolve App from inbound RepoUrl"

### AC-ADB-11: SQLite is the Primary Implementation Target; PostgreSQL DDL is Reference (Phase 153 Task A11b)  `[critical]`
- **Given** `00-overview.md` ships TWO DDL blocks — the main "Schema" section (SQLite, PascalCase, INTEGER PKs, `INTEGER PRIMARY KEY AUTOINCREMENT`) AND the "Inlined Contracts (Phase 53 — SQL DDL lever)" appendix (PostgreSQL 15+, snake_case, `uuid` PKs, `gen_random_uuid()`),
- **When** any implementer reads §00 to build the App database,
- **Then** the **SQLite block (PascalCase, INTEGER PKs)** is the **Primary Implementation Target** — it is the dialect every consuming binary (CI/CD push handler, app-link resolver, `app-database` CLI) MUST materialise; AND the **PostgreSQL block** is **Reference / Secondary**, preserved for cross-reference with `spec/05-split-db-architecture/` (which uses PostgreSQL for the root DB) and to document the canonical column-naming intent — implementers MUST NOT materialise the PostgreSQL block as the App database. This codifies the **Phase 153 Task A11b audit finding** "Conflicting DDL Dialects — SQLite (PascalCase, INTEGER) vs PostgreSQL (snake_case, UUIDs) without designated primary target". The choice of SQLite is locked because (a) the App database is **per-binary local state** (single-writer, embedded, no network), (b) `spec/05` AC-SD-21/22/23 already govern SQLite identifier double-quoting + busy-timeout + TTL, and (c) PascalCase + INTEGER PKs match the Profile/GitProfile/Repo parent tables specified in `spec/22-git-logs-v2/`. Future contributors who want to add a PostgreSQL implementation MUST add a NEW AC explicitly opening that lane (and reconcile with spec/05's per-SHA partitioning model) — silent dialect-flip is FORBIDDEN.
- **Verifies:** `00-overview.md` § "Schema" (SQLite, primary); `00-overview.md` § "Inlined Contracts (Phase 53)" (PostgreSQL, reference); spec/05 AC-SD-21/22/23 (SQLite identifier + busy-timeout + TTL contracts); spec/22 §07 App identity locked decision 12.

### AC-ADB-12: External-table prerequisites inlined as minimal DDL summary (Phase 153 Task A11b)  `[high]`
- **Given** the App and AppLink tables reference `Profile(ProfileId)`, `GitProfile(GitProfileId)`, and `Repo(RepoId)` foreign keys whose authoritative DDL lives in `spec/22-git-logs-v2/` (NOT in this module),
- **When** any context-window-bounded auditor or fresh implementer reads `spec/23-app-database/00-overview.md` alone,
- **Then** the **minimal DDL summary block** below MUST be present in §00's "Convention recap" or a "Prerequisites" section (the implementer cannot author App/AppLink without knowing the parent PK types) — this is summary, not authoritative; the authoritative DDL stays in spec/22:

  ```sql
  -- PREREQUISITE TABLES (authoritative DDL: spec/22-git-logs-v2/)
  -- Materialised by the git-logs-v2 module BEFORE app-database migrations run.
  -- This summary exists to make spec/23 auditable in isolation; do NOT
  -- duplicate or fork these definitions — track spec/22 for changes.
  CREATE TABLE IF NOT EXISTS Profile (
      ProfileId    INTEGER PRIMARY KEY AUTOINCREMENT,
      Email        TEXT    NOT NULL UNIQUE,                       -- canonical user email
      DisplayName  TEXT    NOT NULL CHECK (length(DisplayName) BETWEEN 1 AND 120),
      CreatedAt    TEXT    NOT NULL DEFAULT (strftime('%Y-%m-%dT%H:%M:%fZ', 'now'))
  );
  CREATE TABLE IF NOT EXISTS GitProfile (
      GitProfileId INTEGER PRIMARY KEY AUTOINCREMENT,
      ProfileId    INTEGER NOT NULL REFERENCES Profile(ProfileId) ON DELETE CASCADE,
      Provider     TEXT    NOT NULL CHECK (Provider IN ('github','gitlab','bitbucket','gitea')),
      Username     TEXT    NOT NULL,                              -- provider-side handle
      UNIQUE (Provider, Username)
  );
  CREATE TABLE IF NOT EXISTS Repo (
      RepoId       INTEGER PRIMARY KEY AUTOINCREMENT,
      GitProfileId INTEGER NOT NULL REFERENCES GitProfile(GitProfileId) ON DELETE CASCADE,
      RepoUrl      TEXT    NOT NULL UNIQUE,                       -- canonicalised SSH/HTTPS URL
      RepoName     TEXT    NOT NULL
  );
  ```

  This codifies the **Phase 153 Task A11b audit finding** "Broken External References — App/AppLink reference Profile/GitProfile/Repo whose schemas are not provided in this context". Mirrors spec/02 AC-CG-21 Subfolder Delegation Map and spec/27 AC-T-29 per-artifact AC delegation contracts (Lesson #19/#21): when audit-boundary < verification-boundary, the consuming module MUST inline a summary surface so the contract is auditable in isolation while keeping the authoritative source in one place.
- **Verifies:** spec/22-git-logs-v2 §02/§07 (authoritative Profile/GitProfile/Repo DDL); `00-overview.md` § "Schema" (App + AppLink FKs reference these parent tables); codifies **Lesson #26** "external-FK contract surfaces MUST inline a minimal DDL summary so consuming-module audits don't fail on unresolved references".

### AC-ADB-13: AppLink CHECK constraint uses hardcoded ID constants, not subqueries (Phase 153 Task A11b)  `[medium]`
- **Given** the AppLink table's CHECK constraint in `00-overview.md` § "Schema" currently uses subqueries `(SELECT AppLinkTypeId FROM AppLinkType WHERE Name = 'GitProfile')` to enforce the polymorphic XOR invariant (exactly one of `TargetGitProfileId` / `TargetRepoId` is non-null per row, matching the `AppLinkTypeId` discriminator),
- **When** any SQLite engine attempts to evaluate the CHECK constraint at INSERT/UPDATE time,
- **Then** SQLite MUST reject the table definition because **subqueries are NOT allowed inside CHECK constraints in SQLite** (per the SQLite docs: "The CHECK constraint may not contain ... subqueries"). The fix is two-pronged: (1) **hardcode the AppLinkType IDs as constants** in the schema (`AppLinkTypeId = 1` for GitProfile, `AppLinkTypeId = 2` for Repo) AND seed `INSERT OR IGNORE INTO AppLinkType(AppLinkTypeId, Name) VALUES (1, 'GitProfile'), (2, 'Repo')` to lock the IDs to those values; (2) the CHECK constraint becomes the literal `(AppLinkTypeId = 1 AND TargetGitProfileId IS NOT NULL AND TargetRepoId IS NULL) OR (AppLinkTypeId = 2 AND TargetRepoId IS NOT NULL AND TargetGitProfileId IS NULL)`. As a defence-in-depth, application-layer logic (the `app-database` CLI's `app link` command) MUST also enforce the XOR invariant before INSERT — DB-level CHECK is the last line of defence, NOT the only one. This codifies the **Phase 153 Task A11b audit finding** "Unresolved CHECK Constraint Logic — AppLink CHECK uses subqueries which SQLite forbids". The hardcoded-ID approach is preferred over a TRIGGER because (a) CHECK is declarative and inspectable in `.schema` output, (b) TRIGGER bodies are opaque to schema-diff tools, (c) the AppLinkType lookup table only ever has 2 rows (this is a closed enumeration, not an extension point).
- **Verifies:** `00-overview.md` § "Schema" (AppLink CHECK constraint must be subquery-free per SQLite); `00-overview.md` § "Seed data (lookup tables)" (AppLinkType seed must lock IDs to 1/2); codifies **Lesson #27** "SQLite CHECK constraints CANNOT contain subqueries — closed-enumeration FKs MUST hardcode IDs in seed + CHECK pair, not look them up dynamically".

### AC-ADB-14: Polymorphic AppLink resolution algorithm is normative and deterministic (Phase 153 P48-3)  `[critical]`
- **Given** the AppLink table is polymorphic via `AppLinkTypeId` (1=GitProfile, 2=Repo per AC-ADB-13) and an inbound CI/CD push arrives with a canonicalised `:repoUrl`,
- **When** any implementer (CI/CD push handler, `app-database` CLI, app-link resolver binary) resolves `:repoUrl` to an App,
- **Then** the implementer MUST follow the **4-step resolution algorithm** in `00-overview.md` § "Polymorphic AppLink Resolution (Normative)" — (1) canonicalise the URL using the same pipeline as `Repo.RepoUrl` insertion, (2) collect Direct (Repo) candidates with `AppLinkTypeId = 2`, (3) collect Transitive (GitProfile) candidates with `AppLinkTypeId = 1` via `Repo.GitProfileId`, (4) tie-break with **Direct > Transitive > newer `CreatedAt`** AND require the resolved App's `AppStatusId` = `Active`. Resolution MUST terminate in exactly one of the four closed states `{RESOLVED_DIRECT, RESOLVED_TRANSITIVE, REJECTED_INACTIVE_APP, REJECTED_NO_MATCH}` — implementations MUST NOT invent additional outcomes, MUST NOT silently fall through `REJECTED_INACTIVE_APP` to the next candidate, and MUST NOT join `AppLink` directly to `GitProfile` bypassing the `Repo` table in step 3 (the inbound URL carries no GitProfile hint). Q1 in `00-overview.md` § "Query Patterns" is the SQL realisation; the prose algorithm is authoritative — if Q1 and the prose ever diverge, the prose wins and Q1 MUST be patched. This codifies the **Phase 153 P47-fu1 critical finding** "Polymorphic AppLink resolution logic ambiguous — DDL describes structure, not the resolution algorithm". Mirrors spec/02 AC-CG-21 Subfolder Delegation Map and spec/27 AC-T-29 per-artifact AC delegation contracts (Lesson #19/#21/#26): when a contract surface (the resolution algorithm) lives implicitly inside example SQL, it is invisible to context-window-bounded auditors and to fresh implementers — the algorithm MUST be lifted to a normative prose section with a closed-enumeration outcome table.
- **Verifies:** `00-overview.md` § "Polymorphic AppLink Resolution (Normative)" (discriminator binding table + 4-step algorithm + 4-state outcome enumeration + forbidden patterns); `00-overview.md` § "Q1 — Resolve App from inbound RepoUrl" (SQL realisation); AC-ADB-05 (XOR target invariant — load-bearing for step 2/3 disjointness); AC-ADB-06 (disconnect-timestamp invariant — load-bearing for `IsActive = 1` filter); AC-ADB-10 (Repo > GitProfile precedence — now codified prose-side, not just `ORDER BY`); AC-ADB-13 (locked IDs 1/2 referenced by the algorithm). Codifies **Lesson #33** "Polymorphic-FK resolution algorithms MUST be lifted to normative prose with a closed-enumeration outcome table — example SQL is illustrative, not authoritative; relying on `ORDER BY` clauses to encode precedence rules is invisible to auditors and fresh implementers."

---

## Cross-References

- [Module overview](./00-overview.md)
- [Module changelog](./98-changelog.md)
- [Module consistency report](./99-consistency-report.md)
