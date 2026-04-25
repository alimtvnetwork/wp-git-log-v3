# Acceptance Criteria — 23 App Database

**Version:** 2.0.0  
**Updated:** 2026-04-25  
**Scope:** `spec/23-app-database/`  
**Generated:** AI-extracted Given/When/Then from module body via `linter-scripts/generate-gwt-acceptance.py`

---

## Module Summary

Defines the app-specific data model, schema migrations, and query patterns for the application database. It enforces strict SQLite partitioning and forward-only migration rules (Rule 12).

---

## Inlined Contracts

> Required artifacts inlined here so each AC is self-contained — a mediocre AI does not need to chase cross-links.

# Database Schema Rule 12 (App-Specific)
- Migrations: Forward-only logic only.
- Column Modification: New columns must be NULLABLE.
- Defaults: No DEFAULT values allowed on new columns in migrations.
- Naming: PascalCase for all Tables and Columns.

# File Paths
- Verification Script: linter-scripts/check-forbidden-strings.py
- Architecture Ref: 05-split-db-architecture/00-overview.md
- Convention Ref: 04-database-conventions/00-overview.md

---

## Acceptance Criteria

### AC-ADB-01: Forward-only Migrations  `[critical]`
- **Given** A new migration file is created for the app-database module
- **When** Inspecting the migration script structure during verification.
- **Then** The migration logic must only contain forward-moving changes without 'DOWN' or 'ROLLBACK' destructors as per Rule 12.
- **Verifies:** spec/23-app-database/00-overview.md

### AC-ADB-02: PascalCase Enforcement  `[high]`
- **Given** New table or column definitions are added to the schema
- **When** Checking naming conventions against core database rules referenced in the overview.
- **Then** All identifiers must use PascalCase naming conventions.
- **Verifies:** spec/23-app-database/00-overview.md

### AC-ADB-03: Schema Alteration Constraints  `[critical]`
- **Given** An existing table is being modified via a migration script
- **When** Adding a new column to a table with existing data.
- **Then** Any appended columns must be NULLABLE and cannot contain a DEFAULT clause (Rule 12).
- **Verifies:** spec/23-app-database/00-overview.md

### AC-ADB-04: Forbidden Strings Linter Pass  `[critical]`
- **Given** The linter script `python3 linter-scripts/check-forbidden-strings.py` is executed
- **When** Automated verification runs on the database module.
- **Then** The process must return exit code 0; any non-zero exit blocks the merge.
- **Verifies:** spec/23-app-database/00-overview.md

### AC-ADB-05: Split DB Architecture Conformance  `[medium]`
- **Given** The app-database module configuration
- **When** Evaluating table placement between main and partition databases.
- **Then** The partitioning of the SQLite database must adhere to the 'Split DB Architecture' pattern referenced in the cross-references.
- **Verifies:** spec/23-app-database/00-overview.md

---

## Cross-References

- [Module overview](./00-overview.md)
- [Module changelog](./98-changelog.md)
- [Module consistency report](./99-consistency-report.md)