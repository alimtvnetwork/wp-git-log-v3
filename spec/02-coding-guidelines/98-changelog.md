# Changelog — Coding Guidelines

**Version:** 2.0.0
**Updated:** 2026-04-26
**Scope:** `spec/02-coding-guidelines/`

---

## Format

- Versions follow [SemVer](https://semver.org/): MAJOR.MINOR.PATCH.
- Entries are reverse-chronological (newest first).
- Each entry lists: date (YYYY-MM-DD), version, change category, summary.
- Change categories: `Added`, `Changed`, `Deprecated`, `Removed`, `Fixed`, `Security`.

---

## Releases

### 2.0.0 — 2026-04-26
- **Changed** §97 — full GWT rewrite. Replaced 22 table-row criteria (AC-001..AC-022) with **20 module-specific Given/When/Then ACs** (AC-CG-01..AC-CG-20) covering the §02 parent governance contract: numbering ranges, four-required-files rule, six CODE-RED rules (R1 error-mgmt → R6 size limits), hybrid PascalCase/Rust-snake_case naming policy, AC-count compliance per subfolder, lockstep rule for consolidated review guides, cross-link health, language-vs-cross-language hierarchy, app-specific subfolder boundary, AI-rules canonicalization, dependency version pinning, placeholder subfolder remediation, migration-history freshness, module tree-health gate ≥ 95, and recursive self-application.
- **Preserved** legacy table-row criteria as AC-CG-LEGACY-001..022 at end of §97 for traceability.
- **Added** Phase 16e scan finding: 15 §97 files across the spec tree currently have 0 Given/When/Then ACs (only table rows or scaffolds). Highest-impact remediation targets: `02-typescript/`, `03-golang/`, `04-php/`, `05-rust/`, `07-csharp/`, `06-ai-optimization/`, `01-cross-language/`, `11-security/01-axios-version-control/`, `06-cicd-integration/`, `_archive/21-git-logs-v1/`. Tracked for Phase 16f+.
- **Bumped** §97 v3.2.0 → v4.0.0 (major; AC contract type changed from table-row to GWT). §98 v1.0.0 → v2.0.0 (minor would suffice but lockstep with §97 major). §99 v3.2.0 → v4.0.0.

### 1.0.0 — 2026-04-25
- **Added** baseline module structure (00-overview, 97-acceptance-criteria, 98-changelog, 99-consistency-report).
- **Added** module-specific files per current inventory in `99-consistency-report.md`.
- Auto-scaffolded by `linter-scripts/fill-missing-changelogs.cjs` as part of root v3.7.x Phase 2c sweep.

---

## Cross-References

- [Module overview](./00-overview.md)
- [Module acceptance criteria](./97-acceptance-criteria.md)
- [Module consistency report](./99-consistency-report.md)
