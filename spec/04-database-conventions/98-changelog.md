# Changelog — Database Conventions

**Version:** 3.4.0  
**Updated:** 2026-04-29  
**Scope:** `spec/04-database-conventions/`

---

## Format

- Versions follow [SemVer](https://semver.org/): MAJOR.MINOR.PATCH.
- Entries are reverse-chronological (newest first).
- Each entry lists: date (YYYY-MM-DD), version, change category, summary.
- Change categories: `Added`, `Changed`, `Deprecated`, `Removed`, `Fixed`, `Security`.

---

## Releases

### 3.4.0 — 2026-04-29 — Phase 153 P48-2: Cross-Language Boolean Storage Convention (closes P47-fu1 critical finding)
- **Action:** Added `## 2.1 Cross-Language Boolean Storage Convention (Normative)` to `02-schema-design.md` (file v3.3.0 → v3.4.0). Section contains four normative subsections: §2.1 Why-this-is-normative preamble; §2.1.1 per-engine storage table (SQLite / MySQL/MariaDB / PostgreSQL × allowed values × forbidden alternatives); §2.1.2 per-language scan/insert pattern table (Go / PHP / Rust / C# / TypeScript × scan target × insert literal × notes); §2.1.3 migration discipline (NOT NULL DEFAULT 0, positive-only rename rule, type-swap precondition with orphan-value check). Closes the **P47-fu1 critical finding** "04-db cross-lang boolean conventions" — previously a single ambiguous table row ("Boolean | INTEGER | TINYINT(1) or BOOLEAN") that left every consumer (Go `database/sql`, PHP PDO, Rust `sqlx`, C# ADO.NET, TS `mysql2`/`better-sqlite3`/`pg`) free to pick its own representation, producing silent data corruption when one consumer interprets `0` as false but another interprets `'0'` (string) as truthy.
- **AC binding:** AC-09 added to §97 (count 8 → 9; §97 v1.1.0 → v1.2.0). AC-09 verifies the four required subsections + per-engine + per-language coverage + tri-state exception clause + migration discipline; cross-references `spec/02-coding-guidelines/01-cross-language/02-boolean-principles/00-overview.md` for the storage-vs-naming axis split (naming = §02 CG, storage = §04 DB-conv — adjacent specs share the same Is/Has prefix rule but different normative surfaces).
- **Lockstep:** §00 banner v3.3.3 → **v3.4.0** (minor — new normative subsection in §02-schema-design + new AC); §97 v1.1.0 → **v1.2.0**; §02-schema-design v3.3.0 → **v3.4.0**; §98 v3.3.3 → **v3.4.0**; §99 banner v3.5.1 → **v3.6.0**. h10 stamp 153 (no refresh needed). **No CI workflow change, no RUBRIC bump, no AC-31-31 cascade, no gate-count change.**
- **Lesson #32 (codify):** P47-fu1 backlog items survived 7+ phases of accumulated work without resolution because their finding text ("Cross-language boolean conventions lack normative table") was buried in a single index-line memo (`mem://index.md` line 55) with no per-finding tracker file. Future phase-spanning audit findings (P47-fu1-style "5 critical findings into the P48 backlog") MUST get a one-finding-per-file tracker under `.lovable/memory/audit/` so individual closures are discoverable; otherwise inherited backlog labels (#16/#17/#18) get fabricated by summarisers without anchoring to source findings (cf. Lesson #30). Precedent: Phase 153 P49 closed via concrete §27 AC-T-13 anchor; P48-2 needed a dig back through P47-fu1 to find the source.

### 3.3.3 — 2026-04-29 — Phase 153 Task A2 (envelope inlining for context-bounded AI)

- **Added** "Universal Response Envelope — Inlined Summary" section at the end of `00-overview.md` so a context-bounded AI implementing REST endpoints from `spec/04` alone has the full envelope shape (PascalCase JSON keys, top-level field table, conditional-field semantics, Go `omitempty` note) without needing to fetch `spec/03-error-manage/.../05-response-envelope/`.
- **Lockstep:** §00 banner v3.3.2 → v3.3.3 (h10 stamp 30 → 153). §99 v3.5.0 → v3.5.1.
- **No AC change.** Spec content is a pure inlining of the existing source-of-truth reference; if upstream and the inlined summary diverge, upstream wins (declared in the new section's preamble).
- Closes Phase 153 Task A2 finding "spec/04 D5 cross-module dep" from audit-v2.

### 3.3.2 — 2026-04-29 — Phase P48-1-fu1-batch P3 sweep slot 3 (AC-01..AC-08 Verifies clauses)
- **Added** `**Verifies:**` clauses to AC-01 through AC-08 in `97-acceptance-criteria.md` (v1.0.0 → v1.1.0). Each clause cites the precise invariant defended (structural floor, no-broken-links, slot-immutability, §99 inventory-heading rubric, ≥80 floor, missing-contract rule, cross-folder links, four-file lockstep). Closes the P3-tier gap (0/8 → 8/8 Verifies) and graduates this module's AC-block from Medium → High AI-confidence per `01-spec-authoring.md` § *AI Confidence Rubric (normative)*. No semantic change to acceptance surface — purely a verifiability uplift. §00 banner 3.3.1 → 3.3.2; §97 1.0.0 → 1.1.0; §99 row added.

### 3.3.1 — 2026-04-28 — Phase P30 batch reconciliation
- §98 header bumped to align with §00 banner; H10 stamp dropped on §00; date sweep `2026-04-27`→`2026-04-28`. Pure metadata patch — no module-rule change.

### 3.3.0 — 2026-04-28 — Phase P30 (P30 batch — dual-stream alignment)
- Reconciles §98 release stream with §00 banner stream (`3.3.0`). Prior §98 ladder ended at `1.1.0` (after promoting any post-footer prose) but §00 banner already tracked `3.3.0` from independent module-version stream (P25 subcase). Per P25 precedent, single alignment row added at §00 banner version; lockstep gate now satisfied.

### 1.1.0 — 2026-04-26 (Phase 20, Module #6)
- **Added** Canonical Reference DDL block in `00-overview.md` covering all 10 Golden Rules in a single normative SQL contract (User / ProjectStatus / Project / ProjectWithOwnerView).
- **Added** "Forbidden Tokens" lint table mapping disallowed → required SQL identifiers.
- **Added** Acceptance test `AC-DB-CANON-01` for DDL conformance.
- **Fixed** `99-consistency-report.md` inventory: added missing rows for `07-split-db-pattern.md`, `97-acceptance-criteria.md`, and `98-changelog.md`.
- **Bumped** `00-overview.md` 3.2.0 → 3.3.0; consistency report 3.2.0 → 3.3.0.

### 1.0.0 — 2026-04-25
- **Added** baseline module structure (00-overview, 97-acceptance-criteria, 98-changelog, 99-consistency-report).
- **Added** module-specific files per current inventory in `99-consistency-report.md`.
- **Auto-scaffolded** by `linter-scripts/fill-missing-changelogs.cjs` as part of root v3.7.x Phase 2c sweep.

---

## Cross-References

- [Module overview](./00-overview.md)
- [Module acceptance criteria](./97-acceptance-criteria.md)
- [Module consistency report](./99-consistency-report.md)

---

## Cross-References

- [Module overview](./00-overview.md)
- [Module acceptance criteria](./97-acceptance-criteria.md)
- [Module consistency report](./99-consistency-report.md)

| Date | Bump | Notes |
|------|------|-------|
| 2026-04-26 | patch | Phase 27d: Added Drift Acknowledgment for low-severity doc-hygiene findings. |
