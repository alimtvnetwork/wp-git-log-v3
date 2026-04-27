---
description: App Database Conventions — content child module of `02-coding-guidelines/23-app-database/`. Carries an inlined contract, Mermaid lifecycle diagram, and full GWT acceptance criteria.
---

# App Database Conventions

**Version:** 2.0.0
**Updated:** 2026-04-27
**Parent:** [`../00-overview.md`](../00-overview.md)

---

## Overview

App-layer SQL and migration conventions extending the shared database conventions. Enforces forward-only migrations and PascalCase naming for App-owned tables.

---

## Inlined Contract

```sql
-- App-table convention (DDL pattern)
-- Rule 12: forward-only, NULLABLE additions, no DEFAULT, no DROP.
CREATE TABLE AppTableExample (
    AppTableExampleId INTEGER PRIMARY KEY AUTOINCREMENT,
    AppId             INTEGER NOT NULL REFERENCES App(AppId) ON DELETE CASCADE,
    Name              TEXT    NOT NULL,
    CreatedAt         TEXT    NOT NULL,
    UpdatedAt         TEXT    NOT NULL,
    UNIQUE(AppId, Name)
);

-- ALTER pattern for new columns (Phase 69 invariant):
ALTER TABLE AppTableExample ADD COLUMN OptionalNote TEXT NULL;
-- ^ MUST be NULL, MUST NOT have DEFAULT, MUST appear in a new migration file.
```

---

## Lifecycle Diagram

See [`lifecycle-app-migration.mmd`](./lifecycle-app-migration.mmd) for the complete authoring → validation → publication lifecycle.

```mermaid
flowchart TD
    A[App Schema Change Needed] --> B[Author Forward Migration]
    B --> C{Adds Column?}
    C -- Yes --> D[Enforce NULL + no DEFAULT]
    C -- No --> E{Adds Table?}
    E -- Yes --> F[Enforce PascalCase + PK pattern]
    D --> G[check-forbidden-strings.py]
    F --> G
    G --> H{Pass?}
    H -- No --> I[Block: APP-DB-001]
    H -- Yes --> J[Apply on Deploy]
```

---

## Cross-References

| Reference | Location |
|-----------|----------|
| Parent index | [`../00-overview.md`](../00-overview.md) |
| Acceptance criteria | [`./97-acceptance-criteria.md`](./97-acceptance-criteria.md) |
| Lifecycle diagram source | [`./lifecycle-app-migration.mmd`](./lifecycle-app-migration.mmd) |
| Changelog | [`./98-changelog.md`](./98-changelog.md) |
| Consistency report | [`./99-consistency-report.md`](./99-consistency-report.md) |
