# Acceptance Criteria — 23 App Database

**Version:** 3.0.0
**Updated:** 2026-04-27
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

---

## Cross-References

- [Module overview](./00-overview.md)
- [Module changelog](./98-changelog.md)
- [Module consistency report](./99-consistency-report.md)
