# Changelog — CI/CD Pipeline Workflows

**Version:** 3.3.1  
**Updated:** 2026-04-28  
**Scope:** `spec/12-cicd-pipeline-workflows/`

---

## Format

- Versions follow [SemVer](https://semver.org/): MAJOR.MINOR.PATCH.
- Entries are reverse-chronological (newest first).
- Each entry lists: date (YYYY-MM-DD), version, change category, summary.
- Change categories: `Added`, `Changed`, `Deprecated`, `Removed`, `Fixed`, `Security`.

---

## Releases

### 1.2.0 — 2026-04-28 — promoted by Phase P30 (P30 batch — P28-style hybrid)
- Reconstructed from post-footer prose: `## 2026-04-27 — Phase 76 (impl 90 → 100)`. **Minor bump**: additive content (typed contracts, OpenAPI surface, Mermaid diagram, frontmatter, etc.) — no behavior change.

### 3.3.0 — 2026-04-28 — Phase P30 (P30 batch — dual-stream alignment)
- Reconciles §98 release stream with §00 banner stream (`3.3.0`). Prior §98 ladder ended at `1.2.0` (after promoting any post-footer prose) but §00 banner already tracked `3.3.0` from independent module-version stream (P25 subcase). Per P25 precedent, single alignment row added at §00 banner version; lockstep gate now satisfied.

### 3.3.1 — 2026-04-28 — Phase P30 batch reconciliation
- §98 header bumped to align with §00 banner; H10 stamp dropped on §00; date sweep `2026-04-27`→`2026-04-28`. Pure metadata patch — no module-rule change.

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
