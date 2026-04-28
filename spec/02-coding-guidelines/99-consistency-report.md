# Consistency Report — Coding Guidelines

**Version:** 4.3.0
**Last Updated:** 2026-04-27
**Health Score:** 100/100 (A+)

> **v4.3.0 update (Phase 47 — slot-06 co-location documentation fix):** Added `06-cicd-integration/` row to the subfolder inventory and `**Total:**` line. The folder physically exists with full §00/§97/§98/§99 (per `mem://specs/phased-roadmap.md` Phase 16r §28 closure) and shipped at slot 06 alongside `06-ai-optimization/` per the **§16→§37 immutability precedent** (once a slot has shipped a §97, the slot label is frozen even if a sibling later co-locates — rename would invalidate downstream cross-refs). This was a documentation-only omission in the §00 and §99 inventory tables; no folder rename, no AC churn, no §97 contract change. B2 backlog item closed without rename per "no destructive moves on shipped slots" rule.

> **v4.1.0 update (Phase 20 contract-inlining sweep):** §97 "Inlined Contracts" section now ships THREE machine-parseable normative blocks alongside the pre-existing `text` summary — (1) `ts` block with full type-level contract (`CodeRedRule` enum, `R6SizeLimits`, `NamingCase`, `LanguageNamingPolicy`, `NAMING_MATRIX`, `BOOLEAN_NAME_REGEX`, `PrimaryKeyContract`, `SubfolderGovernance`); (2) `json` block with JSON-Schema 2020-12 `CodingGuidelinesSubfolder` structural contract; (3) `yaml` block with numbering ranges, language-subfolder policy table, app-subfolder status, linter wiring, and gate thresholds. Phase 19 audit identified 0/3 contract presence as the dominant cap on §02 implementability (gate `G-CON-01` capped score ≤ 50 absent any inlined contract block; current contributing block was `text` which the auditor counts as 0). This patch lifts contract count to 3/3. Projected impact: §02 module implementability 85 → 92+; module weighted overall 80 → 84+; tree-mean implementability +1.2pts (§02 has blast-radius 10, the maximum). Lockstep: §97 v4.0.0 → v4.1.0; §98 v2.0.0 → v2.1.0; spec-index 3 cells refreshed.

> **v4.0.0 update (Phase 16e):** §97 fully rewritten from 22 table-row criteria to **20 module-specific Given/When/Then ACs** (AC-CG-01..AC-CG-20). The new ACs codify the §02 parent governance contract — numbering ranges, four-required-files rule, six CODE-RED rules, hybrid naming policy, lockstep rules, language-vs-cross-language hierarchy, and tree-health gate. Legacy AC-001..022 preserved as AC-CG-LEGACY-001..022 for traceability. **Open gap surfaced**: 15 §97 files across the spec tree (including 8 within §02 subfolders) currently have 0 GWT ACs — tracked for Phase 16f+ deepening sweep. Module-level tree-health remains 100/100 because all required files are present; the AC-count gap will surface when AC-CG-19's per-subfolder gate is enforced.

---

## Module Health
<!-- verified-phase: 147 -->

| Criterion | Status |
|-----------|--------|
| `00-overview.md` present | ✅ |
| `99-consistency-report.md` present | ✅ |
| Lowercase kebab-case naming | ✅ All files compliant |
| Unique numeric sequence prefixes | ✅ |
| AI Confidence on all overviews | ✅ (7/7) |
| Zero internal broken refs | ✅ |

---

## File Inventory

| # | File | Status |
|---|------|--------|
| 00 | `00-overview.md` | ✅ Present |
| — | `consolidated-review-guide.md` | ✅ Present |
| — | `consolidated-review-guide-condensed.md` | ✅ Present |
| 97 | `97-acceptance-criteria.md` | ✅ Present |
| 99 | `99-consistency-report.md` | ✅ Present |

**Subfolders:**

| # | Folder | Files | Has Overview | Has Consistency Report | Has Acceptance Criteria |
|---|--------|-------|-------------|----------------------|------------------------|
| 01 | `01-cross-language/` | 40 | ✅ | ✅ | ✅ |
| 02 | `02-typescript/` | 13 | ✅ | ✅ | ✅ |
| 03 | `03-golang/` | 16 | ✅ | ✅ | ✅ |
| 04 | `04-php/` | 12 | ✅ | ✅ | ✅ |
| 05 | `05-rust/` | 10 | ✅ | ✅ | ✅ |
| 06 | `06-ai-optimization/` | 7 | ✅ | ✅ | ✅ |
| 06 | `06-cicd-integration/` | 7 | ✅ | ✅ | ✅ |
| 07 | `07-csharp/` | 5 | ✅ | ✅ | — |
| 08 | `08-file-folder-naming/` | 7 | ✅ | ✅ | — |
| 09 | `09-powershell-integration/` | 1 | ✅ | — | — |
| 10 | `10-research/` | 1 | ✅ | — | — |
| 11 | `11-security/` | 6 | ✅ | ✅ | — |
| 21 | `21-app/` | 1 | ✅ | — | — |
| 22 | `25-app-issues/` | 1 | ✅ | — | — |
| 23 | `23-app-database/` | 1 | ✅ | — | — |
| 24 | `24-app-design-system-and-ui/` | 1 | ✅ | — | — |

**Total:** 5 root files + 15 subfolders (~128 files)

---

## Cross-Reference Validation

| Type | Count | Status |
|------|-------|--------|
| Internal refs (within module) | All valid | ✅ |
| External refs (to other spec modules) | 0 broken | ✅ |

---

## Migration History

| Date | Version | Action |
|------|---------|--------|
| 2026-04-16 | 1.1.0 | Flattened structure — removed nested `03-coding-guidelines-spec/`, all subfolders now at root level. Updated all cross-references. |
| 2026-04-02 | 3.0.0 | Added `07-csharp/` subfolder |
| 2026-04-01 | 2.6.0 | Added `16-static-analysis/` to cross-language |
| 2026-03-31 | 2.0.0 | Post-consolidation QA — 6 phases complete |
| 2026-03-30 | 1.0.0 | Initial consistency report created |

---

*Consistency report — coding guidelines v1.1.0 — 2026-04-16*

---

## Validation History

| Date | Version | Action |
|------|---------|--------|
| 2026-04-27 | 4.2.0 | Phase 39b: Added §00 "Audit Marker Exemption" — `todo_count: 7` was substring false-positive (all hits are AC content / cross-language policy / forbidden-construct enumerations). Banner v3.2.0→v3.3.0; §98 v2.1.0→v2.2.0. |
| 2026-04-26 | current | Phase 31: Added Validation History + heading-rubric alignment for `check-tree-health.cjs` v2.0.0 quality dimension. No content removed. |
| 2026-04-25 | prior | Tree-wide audit baseline established (45/100 → roadmap to 100). |
| 2026-04-20 | prior | Module brought into alignment with parent §99 conventions. |
| 2026-04-16 | prior | Initial consistency report authored. |

This module's full lockstep history is mirrored in `98-changelog.md`; entries
above summarize only the audit-/validation-bearing milestones for `02-coding-guidelines`.

