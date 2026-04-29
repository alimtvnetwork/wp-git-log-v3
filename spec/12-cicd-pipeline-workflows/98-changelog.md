# Changelog — CI/CD Pipeline Workflows

**Version:** 3.4.2  
**Updated:** 2026-04-29  
**Scope:** `spec/12-cicd-pipeline-workflows/`

---

## Format

- Versions follow [SemVer](https://semver.org/): MAJOR.MINOR.PATCH.
- Entries are reverse-chronological (newest first).
- Each entry lists: date (YYYY-MM-DD), version, change category, summary.
- Change categories: `Added`, `Changed`, `Deprecated`, `Removed`, `Fixed`, `Security`.

---

## Releases

### 3.4.2 — 2026-04-29 — Phase P48-1-fu1-batch P3 sweep slot 5 (AC-01..AC-08 Verifies clauses)
- **Added** `**Verifies:**` clauses to AC-01 through AC-08 in `97-acceptance-criteria.md` (v1.0.0 → v1.1.0). Each clause cites the precise invariant defended; AC-01/AC-06 also document this module's `kind: future-spec` YAML exemption. Closes the P3-tier gap (0/8 → 8/8 Verifies) and graduates the AC-block from Medium → High AI-confidence per `01-spec-authoring.md` § *AI Confidence Rubric (normative)*. §00 banner 3.4.1 → 3.4.2; §97 1.0.0 → 1.1.0; §99 row added.

### 3.4.1 — 2026-04-29 — Phase P48-1-fu1-batch slot 4 (P1 inventory sync)
- §00 Feature Inventory now lists all 4 previously-missing root files: `readme.md`, `01-ci-pipeline.md`, `02-release-pipeline.md`, `11-technical-interface.md`. Slots 01/02 receive co-located rows (precedent: spec/01 slot 04, spec/17 slot 33). No spec rule changes — pure inventory reconciliation. Linter: P1 driver eliminated for `spec/12`.

### 3.3.1 — 2026-04-28 — Phase P30 batch reconciliation
- §98 header bumped to align with §00 banner; H10 stamp dropped on §00; date sweep `2026-04-27`→`2026-04-28`. Pure metadata patch — no module-rule change.

### 3.3.0 — 2026-04-28 — Phase P30 (P30 batch — dual-stream alignment)
- Reconciles §98 release stream with §00 banner stream (`3.3.0`). Prior §98 ladder ended at `1.2.0` (after promoting any post-footer prose) but §00 banner already tracked `3.3.0` from independent module-version stream (P25 subcase). Per P25 precedent, single alignment row added at §00 banner version; lockstep gate now satisfied.

### 1.2.0 — 2026-04-28 — promoted by Phase P30 (P30 batch — P28-style hybrid)
- Reconstructed from post-footer prose: `## 2026-04-27 — Phase 76 (impl 90 → 100)`. **Minor bump**: additive content (typed contracts, OpenAPI surface, Mermaid diagram, frontmatter, etc.) — no behavior change.

### 1.1.0 — 2026-04-27
- **Phase 39c — Added** `11-technical-interface.md` defining CI platform, runner OS matrix, required secrets schema (SignPath, Chrome Web Store, Homebrew, Scoop), workflow env variables, permissions, and the `asset-matrix.json` JSON Schema. Closes audit finding *HIGH — Missing Pipeline Infrastructure Interfaces*.
- §00 banner v3.2.0 → v3.3.0; §99 lockstep update.

### 1.0.0 — 2026-04-25
- **Added** baseline module structure (00-overview, 97-acceptance-criteria, 98-changelog, 99-consistency-report).
- **Added** module-specific files per current inventory in `99-consistency-report.md`.
- Auto-scaffolded by `linter-scripts/fill-missing-changelogs.cjs` as part of root v3.7.x Phase 2c sweep.

---

## Cross-References

- [Module overview](./00-overview.md)
- [Module acceptance criteria](./97-acceptance-criteria.md)
- [Module consistency report](./99-consistency-report.md)

| Date | Bump | Notes |
|------|------|-------|
| 2026-04-26 | minor | Phase 27c: Added `kind: future-spec` frontmatter + Drift Acknowledgment. Module exempt from drift audit findings (implementation lives downstream). |
