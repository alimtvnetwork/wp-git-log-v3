# Changelog — Database Conventions

**Version:** 3.3.1  
**Updated:** 2026-04-28  
**Scope:** `spec/04-database-conventions/`

---

## Format

- Versions follow [SemVer](https://semver.org/): MAJOR.MINOR.PATCH.
- Entries are reverse-chronological (newest first).
- Each entry lists: date (YYYY-MM-DD), version, change category, summary.
- Change categories: `Added`, `Changed`, `Deprecated`, `Removed`, `Fixed`, `Security`.

---

## Releases

### 3.3.0 — 2026-04-28 — Phase P30 (P30 batch — dual-stream alignment)
- Reconciles §98 release stream with §00 banner stream (`3.3.0`). Prior §98 ladder ended at `1.1.0` (after promoting any post-footer prose) but §00 banner already tracked `3.3.0` from independent module-version stream (P25 subcase). Per P25 precedent, single alignment row added at §00 banner version; lockstep gate now satisfied.

### 3.3.1 — 2026-04-28 — Phase P30 batch reconciliation
- §98 header bumped to align with §00 banner; H10 stamp dropped on §00; date sweep `2026-04-27`→`2026-04-28`. Pure metadata patch — no module-rule change.

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
