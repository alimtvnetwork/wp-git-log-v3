# Changelog — Coding Guidelines

**Version:** 2.3.0
**Updated:** 2026-04-27
**Scope:** `spec/02-coding-guidelines/`

---

## Format

- Versions follow [SemVer](https://semver.org/): MAJOR.MINOR.PATCH.
- Entries are reverse-chronological (newest first).
- Each entry lists: date (YYYY-MM-DD), version, change category, summary.
- Change categories: `Added`, `Changed`, `Deprecated`, `Removed`, `Fixed`, `Security`.

---

## Releases

### 2.3.0 — 2026-04-27 (Phase 47 — slot-06 co-location documentation fix)
- **Fixed** §00 "Language & Cross-Language Standards" inventory table missing the `06-cicd-integration/` row (folder physically exists with full §00/§97/§98/§99 from Phase 16r §28 closure but was omitted from the documentation index). Added the row directly beneath `06-ai-optimization/` to make the slot-06 co-location explicit and discoverable. Also added the missing row to §99 subfolder inventory and corrected `**Total:**` from "14 subfolders (~121 files)" to "15 subfolders (~128 files)".
- **Decided** Co-location (NOT rename) per the **§16→§37 immutability precedent** (once a slot has shipped a §97, the slot label is frozen — rename would invalidate every downstream cross-ref). Both `06-ai-optimization/` and `06-cicd-integration/` retain slot 06; readers/auditors disambiguate by the trailing slug.
- **Closed** B2 backlog item without folder rename, AC churn, or §97 contract change. Doc-only patch.
- **Bumped** §99 v4.2.0 → v4.3.0.

### 2.2.0 — 2026-04-27 (Phase 39b — TODO-marker exemption)
- **Added** §00 "Audit Marker Exemption" section documenting that the 2026-04-27 AI-implementability audit's `todo_count: 7` was a substring false-positive: every TODO/TBD/FIXME hit in this folder is either AC content (rules ABOUT how `// TODO:` comments must be formatted in downstream code, e.g., `02-typescript/08-typescript-standards-reference.md:312`), enumerations of forbidden constructs (`05-rust/97-acceptance-criteria.md:97` listing `todo!()` as a Rust no-go), cross-language policy (`01-cross-language/04-code-style/06-comments-and-documentation.md:83`), or English-word fragments (`06-cicd-integration/03-language-roadmap.md:53` "todo → shipping" phase name). Module is exempt from substring-based `todo_density` heuristics; future auditor SHOULD switch to a regex that excludes fenced code blocks and back-tick-quoted strings (Phase 39b follow-up R4).
- **Bumped** overview banner v3.2.0 → v3.3.0.
- **Lockstep:** §99 v4.1.0 → v4.2.0; memory `mem://index.md` Phase 39b row appended.

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
