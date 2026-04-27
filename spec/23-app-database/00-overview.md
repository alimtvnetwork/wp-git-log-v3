---
kind: module
description: App-specific database tables (App, AppLink, AppStatus, AppLinkType) — schema, queries, migration patterns. The "App" entity binds inbound CI/CD pushes to a Profile via polymorphic AppLink rows.
---

# App Database

**Version:** 4.0.0
**Updated:** 2026-04-27
**AI Confidence:** High
**Ambiguity:** None

---

## Keywords

`app-database` · `app-entity` · `app-link` · `polymorphic-fk` · `sqlite-ddl` · `forward-only-migrations` · `pascal-case`

---

## Scoring

| Criterion | Status |
|-----------|--------|
| `00-overview.md` present | ✅ |
| AI Confidence assigned | ✅ |
| Ambiguity assigned | ✅ |
| Keywords present | ✅ |
| Scoring table present | ✅ |
| Inline DDL contracts | ✅ |
| Inline query patterns | ✅ |
| Migration template | ✅ |

---

## Purpose

This module owns the **App** subsystem of the application database. An **App** is a logical CI/CD endpoint (e.g., a deployment target, build pipeline, or webhook receiver) that:

1. Belongs to exactly one `Profile` (the credential owner).
2. Is reachable from one or more `Repo` rows or `GitProfile` rows via polymorphic **AppLink** rows.
3. Has a lifecycle (`Active` / `Disabled` / `Archived`) controlled via `AppStatus`.
4. Has **no own credentials** — CI/CD pushes authenticate as the parent Profile, then resolve to an App via `AppLink`.

This file is the **single source of truth** for the App table family. Cross-references to `04-database-conventions/`, `05-split-db-architecture/`, and `22-git-logs-v2/07-app-entity.md` are informational only — every contract needed to implement these tables is **inlined below**.

---

## Document Inventory

| # | File | Purpose |
|---|------|---------|
| 00 | `00-overview.md` | Module router + inline DDL + query patterns (this file) |
| 97 | `97-acceptance-criteria.md` | Given/When/Then verification rules |
| 98 | `98-changelog.md` | Module version history |
| 99 | `99-consistency-report.md` | Health/inventory + open items |

> **Slot policy:** Slots 01–96 are reserved for future per-table or per-feature deep-dives (e.g., `01-app-table.md`, `02-app-link-resolution.md`). They remain empty by design until a specific deep-dive is required. The full schema is currently small enough to fit in `00-overview.md`.

---

## Inlined Contracts

### Convention recap (binding)

- **Naming:** PascalCase for tables and columns. PK = `{TableName}Id INTEGER PRIMARY KEY AUTOINCREMENT`.
- **Booleans:** `INTEGER` 0/1 with `Is` prefix.
- **Timestamps:** `INTEGER` Unix seconds UTC, named `CreatedAt`, `UpdatedAt`, `DisconnectedAt`.
- **Foreign keys:** `ON UPDATE CASCADE ON DELETE RESTRICT` unless stated.
- **Migrations (Rule 12):** Forward-only. New columns must be `NULLABLE` and **must not** declare a `DEFAULT`. No destructive `DROP TABLE` / `DROP COLUMN` in production migrations — superseded data moves via a "shadow + backfill + cutover" sequence.

### DDL — Lookup tables

```sql
-- AppStatus: Active / Disabled / Archived
CREATE TABLE IF NOT EXISTS AppStatus (
    AppStatusId INTEGER PRIMARY KEY AUTOINCREMENT,
    Name        TEXT    NOT NULL UNIQUE
);

-- AppLinkType: GitProfile / Repo (polymorphic discriminator)
CREATE TABLE IF NOT EXISTS AppLinkType (
    AppLinkTypeId INTEGER PRIMARY KEY AUTOINCREMENT,
    Name          TEXT    NOT NULL UNIQUE
);
```

### DDL — App

```sql
CREATE TABLE IF NOT EXISTS App (
    AppId        INTEGER PRIMARY KEY AUTOINCREMENT,
    AppName      TEXT    NOT NULL,
    AppSlug      TEXT    NOT NULL UNIQUE,
    Description  TEXT    NULL,
    ProfileId    INTEGER NOT NULL REFERENCES Profile(ProfileId)
                 ON UPDATE CASCADE ON DELETE RESTRICT,
    AppStatusId  INTEGER NOT NULL REFERENCES AppStatus(AppStatusId)
                 ON UPDATE CASCADE ON DELETE RESTRICT,
    CreatedAt    INTEGER NOT NULL,
    UpdatedAt    INTEGER NOT NULL
);

CREATE INDEX IF NOT EXISTS IX_App_ProfileId   ON App(ProfileId);
CREATE INDEX IF NOT EXISTS IX_App_AppStatusId ON App(AppStatusId);
```

### DDL — AppLink (polymorphic)

```sql
CREATE TABLE IF NOT EXISTS AppLink (
    AppLinkId            INTEGER PRIMARY KEY AUTOINCREMENT,
    AppId                INTEGER NOT NULL REFERENCES App(AppId)
                         ON UPDATE CASCADE ON DELETE RESTRICT,
    AppLinkTypeId        INTEGER NOT NULL REFERENCES AppLinkType(AppLinkTypeId)
                         ON UPDATE CASCADE ON DELETE RESTRICT,
    TargetGitProfileId   INTEGER NULL REFERENCES GitProfile(GitProfileId)
                         ON UPDATE CASCADE ON DELETE RESTRICT,
    TargetRepoId         INTEGER NULL REFERENCES Repo(RepoId)
                         ON UPDATE CASCADE ON DELETE RESTRICT,
    IsActive             INTEGER NOT NULL,           -- 0/1
    CreatedAt            INTEGER NOT NULL,
    DisconnectedAt       INTEGER NULL,

    -- Polymorphic XOR invariant: exactly one target populated, matching the type discriminator.
    CHECK (
      (AppLinkTypeId = (SELECT AppLinkTypeId FROM AppLinkType WHERE Name = 'GitProfile')
         AND TargetGitProfileId IS NOT NULL AND TargetRepoId IS NULL)
      OR
      (AppLinkTypeId = (SELECT AppLinkTypeId FROM AppLinkType WHERE Name = 'Repo')
         AND TargetRepoId IS NOT NULL AND TargetGitProfileId IS NULL)
    ),

    -- Disconnect invariant: IsActive=0 ⇒ DisconnectedAt populated; IsActive=1 ⇒ DisconnectedAt NULL.
    CHECK (
      (IsActive = 1 AND DisconnectedAt IS NULL)
      OR
      (IsActive = 0 AND DisconnectedAt IS NOT NULL)
    )
);

CREATE INDEX IF NOT EXISTS IX_AppLink_AppId              ON AppLink(AppId);
CREATE INDEX IF NOT EXISTS IX_AppLink_TargetRepoId       ON AppLink(TargetRepoId)       WHERE TargetRepoId IS NOT NULL;
CREATE INDEX IF NOT EXISTS IX_AppLink_TargetGitProfileId ON AppLink(TargetGitProfileId) WHERE TargetGitProfileId IS NOT NULL;
CREATE INDEX IF NOT EXISTS IX_AppLink_Active             ON AppLink(AppId, IsActive);
```

### Seed data (lookup tables)

```sql
INSERT OR IGNORE INTO AppStatus  (Name) VALUES ('Active'), ('Disabled'), ('Archived');
INSERT OR IGNORE INTO AppLinkType(Name) VALUES ('GitProfile'), ('Repo');
```

---

## Query Patterns

### Q1 — Resolve App from inbound RepoUrl (push attribution)

```sql
-- :repoUrl is the canonicalized inbound URL. Returns the FIRST active match
-- ordered by specificity (Repo-link wins over GitProfile-link).
SELECT a.*
FROM App a
JOIN AppLink l       ON l.AppId = a.AppId AND l.IsActive = 1
JOIN AppLinkType lt  ON lt.AppLinkTypeId = l.AppLinkTypeId
JOIN AppStatus s     ON s.AppStatusId = a.AppStatusId AND s.Name = 'Active'
LEFT JOIN Repo r     ON r.RepoId = l.TargetRepoId
LEFT JOIN GitProfile gp_repo ON gp_repo.GitProfileId = r.GitProfileId
LEFT JOIN GitProfile gp_link ON gp_link.GitProfileId = l.TargetGitProfileId
WHERE
   (lt.Name = 'Repo'       AND r.RootRepoUrl = :repoUrl)
OR (lt.Name = 'GitProfile' AND gp_link.ProfileUrl = (SELECT ProfileUrl FROM GitProfile WHERE GitProfileId = (SELECT GitProfileId FROM Repo WHERE RootRepoUrl = :repoUrl)))
ORDER BY (lt.Name = 'Repo') DESC, l.CreatedAt DESC
LIMIT 1;
```

### Q2 — Disconnect (soft) an AppLink

```sql
UPDATE AppLink
   SET IsActive = 0,
       DisconnectedAt = strftime('%s','now')
 WHERE AppLinkId = :appLinkId
   AND IsActive = 1;
```

### Q3 — Reconnect (always insert a new row; never reuse the disconnected row)

```sql
INSERT INTO AppLink (AppId, AppLinkTypeId, TargetRepoId, TargetGitProfileId, IsActive, CreatedAt)
VALUES (:appId, :appLinkTypeId, :targetRepoId, :targetGitProfileId, 1, strftime('%s','now'));
```

### Q4 — List active links for an App (admin UI)

```sql
SELECT l.AppLinkId, lt.Name AS LinkType,
       COALESCE(r.RootRepoUrl, gp.ProfileUrl) AS Target,
       l.CreatedAt
FROM AppLink l
JOIN AppLinkType lt ON lt.AppLinkTypeId = l.AppLinkTypeId
LEFT JOIN Repo r       ON r.RepoId       = l.TargetRepoId
LEFT JOIN GitProfile gp ON gp.GitProfileId = l.TargetGitProfileId
WHERE l.AppId = :appId AND l.IsActive = 1
ORDER BY l.CreatedAt DESC;
```

---

## Migration Template (Rule 12 — forward-only)

```sql
-- migrations/2026XXXXNN-add-{column}-to-App.sql
BEGIN TRANSACTION;

-- ✅ Allowed: nullable, no DEFAULT.
ALTER TABLE App ADD COLUMN OwnerEmail TEXT NULL;

-- ❌ Forbidden in a migration:
--   ALTER TABLE App ADD COLUMN Foo TEXT NOT NULL DEFAULT 'x';
--   ALTER TABLE App DROP COLUMN Description;

COMMIT;
```

To populate a value for the new column, use a separate **backfill** migration that runs `UPDATE` statements; do **not** rely on `DEFAULT`.

---

## Cross-References

- [Database Conventions (Core)](../04-database-conventions/00-overview.md) — General naming, PK/FK, ORM conventions
- [Split DB Architecture](../05-split-db-architecture/00-overview.md) — SQLite root vs per-SHA partitioning
- [Git Logs v2 — App Entity (resolution flow)](../22-git-logs-v2/07-app-entity.md) — Higher-level lifecycle/audit description
- [Git Logs v2 — Schema (sibling tables: Profile/GitProfile/Repo)](../22-git-logs-v2/02-database-schema.md)
- [Consolidated Database Conventions](../17-consolidated-guidelines/18-database-conventions.md)

---

*App database — created 2026-04-16, populated with concrete schema in v4.0.0 (2026-04-27, Phase 39a).*

---

## Verification

_See `spec/23-app-database/97-acceptance-criteria.md` for the full Given/When/Then suite._

### AC-ADB-000: App-database conformance: Overview

**Given** A migration set targeting the App / AppLink / AppStatus / AppLinkType tables.
**When** Run the verification command shown below.
**Then** Migrations are forward-only; PascalCase preserved; new columns are NULLABLE with no DEFAULT (Rule 12); both AppLink CHECK invariants hold.

**Verification command:**

```bash
python3 linter-scripts/check-forbidden-strings.py
```

**Expected:** exit 0. Any non-zero exit is a hard fail and blocks merge.

_Verification section last updated: 2026-04-27_
