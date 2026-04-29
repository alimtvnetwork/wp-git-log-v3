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
