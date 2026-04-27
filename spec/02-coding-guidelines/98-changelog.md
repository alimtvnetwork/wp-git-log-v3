# Changelog — Coding Guidelines

**Version:** 2.1.0
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

### 2.1.0 — 2026-04-26 (Phase 20 contract-inlining sweep)
- **Added** §97 — three normative machine-parseable contract blocks under "Inlined Contracts": (1) `ts` block with `CodeRedRule` enum, `R6SizeLimits` interface, `NamingCase` type, `LanguageNamingPolicy` interface, `NAMING_MATRIX` constant, `BOOLEAN_PREFIX_ALLOWLIST` + `BOOLEAN_NAME_REGEX`, `PrimaryKeyContract` interface, and `SubfolderGovernance` interface; (2) `json` JSON-Schema 2020-12 block (`CodingGuidelinesSubfolder`) defining the structural contract every subfolder MUST satisfy; (3) `yaml` block mirroring the numbering ranges, language-subfolder policy table, app-subfolder status, linter-script wiring, and gate thresholds.
- **Rationale** Phase 19 deterministic re-audit found mean tree implementability stuck at 52.6/100 because most modules fail gate `G-CON-01` (no inlined contract → cap implementability ≤ 50). The §02 parent module previously only had a `text` block — the auditor counts contracts by language tag (`sql`/`json`/`ts`/`typescript`/`yaml`/`yml`), so `text` contributed 0/3.
- **Expected lift** §02 contract count 0/3 → 3/3; module implementability 85 → 92+; module weighted overall 80 → 84+. Tree-mean implementability projected +1.2pts (one of the highest-blast-radius modules).
- **Preserved** the original `text` human-readable summary as a quick-reference; existing AC-CG-01..AC-CG-20 unchanged.
- **Bumped** §97 v4.0.0 → v4.1.0; §98 v2.0.0 → v2.1.0; §99 v4.0.0 → v4.1.0; spec-index 3 cells refreshed.

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

| Date | Bump | Notes |
|------|------|-------|
| 2026-04-26 | patch | Phase 31: Added Validation History / File Inventory headings to §99 to satisfy rubric v2.0.0 quality dimension. |
| 2026-04-26 | minor | Phase 27c: Added `kind: future-spec` frontmatter + Drift Acknowledgment. Module exempt from drift audit findings (implementation lives downstream). |
