# Acceptance Criteria — Database Conventions

**Version:** 1.3.0  
**Updated:** 2026-04-30 (Phase 153 Task A21: AC-10 + AC-11 bind ORM-First and View-based-joins rules from `03-orm-and-views.md` — closes audit-v7 HIGH D2 finding "Missing Acceptance Criteria for ORM and View Rules")
**Scope:** `spec/04-database-conventions/`

---

## Purpose

This document defines testable acceptance criteria for the **Database Conventions** module. Every criterion is verifiable from the module's content alone — an AI implementer or human reviewer can check pass/fail without external context.

---

## Criteria

### AC-01: Module entry point exists and is non-trivial
- **Given** the module folder `spec/04-database-conventions/`
- **When** `00-overview.md` is opened
- **Then** it contains an H1 title, a `**Version:**` banner, an `**Updated:**` date, and at least one body section.
- **Verifies:** the structural-floor contract enforced by `check-tree-health.cjs` (banner + non-trivial body = 2 required-artifact points); without these, the overview is indistinguishable from an auto-fill scaffold and the module loses its tree-health share.
- **Source:** `00-overview.md`

### AC-02: All sibling files referenced from the overview are present on disk
- **Given** the link inventory in `00-overview.md`
- **When** each relative `.md` link is resolved
- **Then** the target file exists in this module folder.
- **Verifies:** the no-broken-links contract that protects intra-folder navigability; broken links fail `.github/workflows/spec-health.yml` Phase 81 strict gate.
- **Source:** `00-overview.md` cross-references; verified by `linter-scripts/check-spec-cross-links.py`.

### AC-03: Naming convention compliance
- **Given** every file in this module
- **When** filenames are inspected
- **Then** all match `^[0-9]{2}-[a-z0-9-]+\.md$` (or are recognized special files like `README.md`).
- **Verifies:** the slot-immutability invariant from `mem://index.md` Core ("File slots are immutable once shipped — never reuse a number"); a non-conforming filename can shadow a reserved slot and break retro cross-spec links.
- **Source:** `spec/01-spec-authoring-guide/02-naming-conventions.md`.

### AC-04: Consistency report present and current
- **Given** the module folder
- **When** `99-consistency-report.md` is opened
- **Then** it lists every `.md` file in this folder under "File Inventory" with status ✅.
- **Verifies:** the §99 inventory-completeness invariant — `mem://index.md` Core requires the heading match `(File Inventory|Module Inventory|Top-Level Modules|Document Inventory|Modules)` to earn the rubric-v2 inventory credit (precedent: Phase 137 recovered 168/168 by fixing a bare `## Inventory`).
- **Source:** `99-consistency-report.md`.

### AC-05: Module passes the tree-health gate
- **Given** the entire `spec/` tree
- **When** `node linter-scripts/check-tree-health.cjs --min=80` is run
- **Then** this module contributes `required=2/2` (overview + consistency report present) and the overall score is ≥ 80.
- **Verifies:** the project-wide ≥80 floor enforced by `.github/workflows/spec-health.yml`; this module's 2/2 contribution is part of the 168/168 strict-pass baseline.
- **Source:** `linter-scripts/check-tree-health.cjs`.

---

### AC-06: Module overview is non-trivial and version-banner-stamped
- **Given** the module file `spec/04-database-conventions/00-overview.md`
- **When** the file is read by `linter-scripts/audit-spec-vs-code-v2.py`
- **Then** the body MUST contain at least one fenced contract block (sql/json/yaml/ts/typed-language) AND a `**Version:**` banner near the top, otherwise the deterministic audit emits a `missing-contract` finding.
- **Verifies:** the rubric-v2.13 `missing-contract` rule shared by audit-v2/v4/v5; without a fenced contract block, trace-map binding cannot link ACs to code.
- **Source:** `linter-scripts/audit-spec-vs-code-v2.py` (rubric v2.13).

### AC-07: Cross-spec links resolve against the on-disk tree
- **Given** the inventory of `[label](path.md)` links in this module's `00-overview.md`
- **When** `python3 linter-scripts/check-spec-cross-links.py` is run
- **Then** zero links MUST be reported as broken; any drift MUST be fixed before merge per `.github/workflows/spec-health.yml` Phase 81 strict gate.
- **Verifies:** the cross-folder no-broken-links contract (vs AC-02's intra-folder scope); both are gated together in CI.
- **Source:** `linter-scripts/check-spec-cross-links.py`.

### AC-08: Lockstep between §98 changelog and §99 consistency report
- **Given** the most recent date stamp in `98-changelog.md`
- **When** `node linter-scripts/check-lockstep.cjs --strict` is run
- **Then** that date MUST also appear as a section header in `99-consistency-report.md`; the strict gate (Phase 81) blocks merge on any mismatch.
- **Verifies:** the four-file lockstep invariant from `mem://index.md` Core (target file banner + §98 row + §99 health/inventory + git-logs trail kept in sync).
- **Source:** `linter-scripts/check-lockstep.cjs`.

### AC-09: Cross-language boolean storage convention is normative and complete
- **Given** the module's §02-schema-design file `02-schema-design.md`
- **When** §2.1 "Cross-Language Boolean Storage Convention (Normative)" is read
- **Then** it MUST contain (a) a per-engine storage table covering at minimum SQLite, MySQL/MariaDB, and PostgreSQL with allowed values + forbidden alternatives; (b) a per-language scan/insert pattern table covering at minimum Go, PHP, Rust, C#, and TypeScript; (c) the tri-state `NULL` exception clause; (d) the migration discipline subsection (NOT NULL default, positive-only rename rule, type-swap precondition). Adding a NEW supported language (per §02 Coding Guidelines) requires extending the §2.1.2 table in the same PR.
- **Verifies:** the cross-language storage contract that prevents silent boolean data corruption when one consumer reads `0` as false and another reads `'0'` (string) as truthy; closes the **P47-fu1 critical finding** "04-db cross-lang boolean conventions" surfaced in `mem://index.md` line 55. Cross-references the cross-language naming rule in `spec/02-coding-guidelines/01-cross-language/02-boolean-principles/00-overview.md` (storage-vs-naming axis split — naming lives in §02 Coding Guidelines, storage lives in §04 DB Conventions).
- **Source:** `02-schema-design.md` §2.1; cross-language naming rule in `spec/02-coding-guidelines/01-cross-language/02-boolean-principles/00-overview.md`.

### AC-10: ORM-First rule has zero raw SQL in business/service layers  `[high]`
- **Given** any business-logic, service, or repository file in any language listed in `03-orm-and-views.md` §1.2 (Go, PHP, TypeScript, Rust, C#)
- **When** the file is scanned for raw SQL string literals (e.g. `"SELECT "`, `"INSERT INTO "`, `"UPDATE "`, `"DELETE FROM "`, `` `SELECT ` ``, `` `INSERT INTO ` ``, etc., case-insensitive)
- **Then** ZERO matches MUST appear outside the four allowed surfaces enumerated in `03-orm-and-views.md` §1.1 table: (a) migration files (path matches `**/migrations/**`), (b) `CREATE VIEW` statements in view-definition files (path matches `**/views/**` or files containing only `CREATE VIEW` DDL), (c) one-off scripts under `scripts/` flagged as approved (header comment `// orm-exempt: <reason>` or `# orm-exempt: <reason>`), (d) test fixtures under `**/test/**` or `**/tests/**` constructing seed data
- **And** the `linter-scripts/check-orm-first.sh` (or equivalent CI gate when materialised) MUST execute the following grep contract and exit non-zero on any match outside allowed surfaces:
  ```bash
  rg -i --type-add 'biz:*.{go,php,ts,tsx,rs,cs}' -t biz \
     -e '"\s*(SELECT|INSERT INTO|UPDATE\s+\w+\s+SET|DELETE FROM)\s' \
     -e '`\s*(SELECT|INSERT INTO|UPDATE\s+\w+\s+SET|DELETE FROM)\s' \
     --glob '!**/migrations/**' --glob '!**/views/**' \
     --glob '!**/test/**' --glob '!**/tests/**' \
     --glob '!**/scripts/**'
  ```
- **Verifies:** §03-orm-and-views.md §1.1 "ORM-First Rule" + §1.3 forbidden patterns; closes audit-v7 HIGH D2 finding "Missing Acceptance Criteria for ORM and View Rules" (spec/04 cache 2026-04-30, finding [0]).
- **Source:** `03-orm-and-views.md` §1 (ORM-First Rule), table §1.1 (allowed-surface enumeration), examples §1.3 (forbidden vs correct patterns).

### AC-11: Multi-table joins use database views, not on-the-fly SQL  `[high]`
- **Given** any business-logic, service, or repository file in any language listed in `03-orm-and-views.md` §1.2
- **When** the file is scanned for ORM/query-builder calls that compose joins at query time (e.g. PHP `->join(`, `->leftJoin(`, `->innerJoin(`; Go GORM `.Joins(`; TS Prisma `include:` with nested relations beyond depth 1; raw SQL `JOIN` keyword in string literals already covered by AC-10)
- **Then** ZERO matches MUST appear in business-logic surfaces — joins MUST be pre-defined as `CREATE VIEW` DDL (path matches `**/views/**` or `**/migrations/**` per §1.1) and the business layer MUST query the resulting flat view as if it were a table (single-table SELECT semantics)
- **And** the `linter-scripts/check-no-on-the-fly-joins.sh` (or equivalent CI gate when materialised) MUST execute the following grep contract and exit non-zero on any match outside allowed surfaces:
  ```bash
  rg --type-add 'biz:*.{go,php,ts,tsx,rs,cs}' -t biz \
     -e '->join\(' -e '->leftJoin\(' -e '->innerJoin\(' -e '->rightJoin\(' \
     -e '\.Joins\(' -e '\.JoinAndSelect\(' \
     --glob '!**/migrations/**' --glob '!**/views/**' \
     --glob '!**/test/**' --glob '!**/tests/**'
  ```
- **And** EXCEPTIONS are limited to two cases: (a) ORM eager-loading of a single direct foreign-key relation (depth-1 includes — e.g. `Transaction.with('plugin')` loading the parent Plugin; depth-2+ MUST become a view); (b) admin-only debugging tools under `**/admin/**` paths flagged with header comment `// joins-exempt: <reason>`.
- **Verifies:** §03-orm-and-views.md §2 "Database Views for Joins" + §1.1 forbidden cell "Complex joins MUST be pre-defined as database views"; closes audit-v7 HIGH D2 finding "Missing Acceptance Criteria for ORM and View Rules" (spec/04 cache 2026-04-30, finding [0]) — companion AC to AC-10.
- **Source:** `03-orm-and-views.md` §1.1 (allowed-surface table) + §2 (View-based join discipline).


---

## Module-Specific Files

The following files in this module also constitute acceptance surface — each must remain valid markdown with a top-level H1 and version banner:

- `00-overview.md`
- `01-naming-conventions.md`
- `02-schema-design.md`
- `03-orm-and-views.md`
- `04-testing-strategy.md`
- `05-relationship-diagrams.md`
- `06-rest-api-format.md`
- `07-split-db-pattern.md`

---

## Validation

Run the full pipeline:

```bash
bash linter-scripts/run.sh
```

This executes: validator → self-heal → regen index → tree-health gate. All steps must exit 0 for this module's acceptance to hold.

---

## Cross-References

- [Module overview](./00-overview.md)
- [Module consistency report](./99-consistency-report.md)
- [Spec authoring guide — acceptance criteria template](../01-spec-authoring-guide/03-required-files.md)
