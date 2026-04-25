# Audit — `spec/01-spec-authoring-guide`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview)  
**Weighted Score:** **89/100 (A)**

> The spec is exceptionally well-structured and aligns closely with the extensive linter infrastructure provided in the codebase. Minor drift exists regarding the manual vs. automated nature of filling spec files.

---

## 6-Dimension Scores

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Completeness | 25% | 85 | 21.2 |
| Consistency | 25% | 90 | 22.5 |
| Alignment | 20% | 90 | 18.0 |
| Clarity | 15% | 95 | 14.2 |
| Maintainability | 10% | 95 | 9.5 |
| Testability | 5% | 80 | 4.0 |

## Code Mapping

**Implemented by:** `linter-scripts/check-spec-cross-links.py`, `linter-scripts/check-tree-health.cjs`, `linter-scripts/fill-missing-acceptance-criteria.cjs`, `linter-scripts/fill-missing-changelogs.cjs`, `linter-scripts/fill-missing-consistency-reports.cjs`, `linter-scripts/generate-dashboard-data.cjs`, `linter-scripts/generate-spec-index.cjs`, `.github/workflows/spec-health.yml`
**Expected but missing:** _(none)_
**Orphan code candidates:** `linter-scripts/audit-spec-vs-code.py`, `linter-scripts/check-axios-version.sh`, `linter-scripts/check-forbidden-spec-paths.sh`, `linter-scripts/check-forbidden-strings.py`, `linter-scripts/check-memory-mirror-drift.py`, `linter-scripts/check-readme-canonicals.py`, `linter-scripts/check-readme-install-section.py`, `linter-scripts/check-root-readme.py`, `linter-scripts/check-spec-folder-refs.py`, `linter-scripts/run.sh`, `linter-scripts/suggest-spec-cross-link-fixes.py`, `linter-scripts/validate-guidelines.py`

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | drift | medium | 7/10 | The spec defines specific weights for health scores (25% each), which may not align with the actual JavaScript linter implementation. |
| 2 | missing-spec | low | 5/10 | Linter scripts for auto-generating spec files are present in code but not documented in the spec module. |
| 3 | untestable | low | 3/10 | AI Confidence and Ambiguity scores are subjective and lack an automated validation path in the current linter index. |
| 4 | orphan-spec | medium | 4/10 | The spec references a 'dashboard' which is implied by generate-dashboard-data.cjs but the UI/frontend is not in the index. |

### Detail + Proposed Corrections

#### 1. [MEDIUM] The spec defines specific weights for health scores (25% each), which may not align with the actual JavaScript linter implementation.
- **Category:** drift  |  **Impact:** 7/10
- **Evidence:** Health Score (0–100) section in 00-overview.md vs linter-scripts/check-tree-health.cjs
- **Proposed correction:** Update Health Score weights in 00-overview.md to match the logic in check-tree-health.cjs and dashboard generation.

#### 2. [LOW] Linter scripts for auto-generating spec files are present in code but not documented in the spec module.
- **Category:** missing-spec  |  **Impact:** 5/10
- **Evidence:** Scripts like fill-missing-acceptance-criteria.cjs exist but are not detailed in the authoring guide.
- **Proposed correction:** Add a section to 10-mandatory-linter-infrastructure.md describing the automation scripts for filling missing AC, changelogs, and reports.

#### 3. [LOW] AI Confidence and Ambiguity scores are subjective and lack an automated validation path in the current linter index.
- **Category:** untestable  |  **Impact:** 3/10
- **Evidence:** Scoring Metrics tables in 00-overview.md.
- **Proposed correction:** Define specific metrics or tools used to objective calculate 'Ambiguity' and 'AI Confidence' levels.

#### 4. [MEDIUM] The spec references a 'dashboard' which is implied by generate-dashboard-data.cjs but the UI/frontend is not in the index.
- **Category:** orphan-spec  |  **Impact:** 4/10
- **Evidence:** "calculated by the dashboard scanner" in 00-overview.md
- **Proposed correction:** Create or link the dashboard UI code that consumes the reported Health Score metrics.
