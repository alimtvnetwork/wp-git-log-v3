# Audit v2 — `spec/01-spec-authoring-guide`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview, 2-pass)  
**Implementability Score:** **56/100 (D)**  
**Blast radius:** 8/10

> This spec provides a comprehensive guide for authoring specs, but its implementability by AI is hampered by missing explicit contracts and testable granular ACs. The presence of numerous broken links also reduces its overall consistency.


**Score justification:** The spec has 11 broken links, capping consistency at 70. There are no GWT blocks, and only 4 ACs which are not granular enough, capping testability at 20. Implementability suffers due to lack of explicit contracts. Too many 'plain' code blocks. clarity is capped at 70 because the waffle_per_kchar is greater than 5.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 40 | 14.0 |
| Completeness | 20% | 60 | 12.0 |
| Alignment | 15% | 80 | 12.0 |
| Consistency | 10% | 70 | 7.0 |
| Clarity | 10% | 70 | 7.0 |
| Testability | 7% | 20 | 1.4 |
| Maintainability | 3% | 100 | 3.0 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 16,
  "overview_chars": 22937,
  "ac_chars": 2490,
  "ac_count": 4,
  "gwt_block_count": 0,
  "consistency_report": true,
  "code_blocks_total": 68,
  "code_blocks_by_lang": {
    "plain": 40,
    "bash": 3,
    "markdown": 23,
    "html": 2
  },
  "has_sql_ddl": false,
  "has_json_schema": false,
  "has_ts_enums": false,
  "has_yaml_openapi": false,
  "links_total": 61,
  "links_broken": 11,
  "todo_density": 2,
  "waffle_per_kchar": 0.33,
  "child_modules": 0
}
```

## Implementability Blockers

- Lack of explicit schema or contracts for the overview metadata block, e.g., 'Version', 'Updated', 'Status', 'AI Confidence', 'Ambiguity'.
- No explicit schema or contracts for the 'Scoring Metrics' table format and expected values.
- No explicit schema or contracts for the 'Keywords' section.
- No explicit schema or contracts for the 'File Categories' definition. Although a table is present, the values are not strictly defined.
- Missing explicit guidance or code examples for *how* to implement the 'Health Score' calculation.
- The 'AC-01: Folder Structure & Required Files' criteria are descriptive but lack executable checks or explicit code for validation.
- The 'AC-02: Naming Conventions' criteria are descriptive but lack executable checks or explicit code for validation.
- The 'AC-03: Overview Content Standards' criteria are descriptive but lack executable checks or explicit code for validation.
- The 'AC-04: Cross-References & Validation' criteria are descriptive but lack executable checks or explicit code for validation.

## Code Mapping

**Implemented by:** `linter-scripts/check-root-readme.py`, `linter-scripts/check-spec-cross-links.py`, `linter-scripts/check-spec-folder-refs.py`, `linter-scripts/generate-spec-index.cjs`, `linter-scripts/fill-missing-consistency-reports.cjs`
**Expected but missing:** _(none)_
**Orphan code candidates:** `linter-scripts/audit-spec-vs-code-v2.py`, `linter-scripts/audit-spec-vs-code.py`, `linter-scripts/check-axios-version.sh`, `linter-scripts/check-forbidden-spec-paths.sh`, `linter-scripts/check-forbidden-strings.py`, `linter-scripts/check-memory-mirror-drift.py`, `linter-scripts/check-readme-canonicals.py`, `linter-scripts/check-readme-install-section.py`, `linter-scripts/check-tree-health.cjs`, `linter-scripts/fill-missing-acceptance-criteria.cjs`, `linter-scripts/fill-missing-changelogs.cjs`, `linter-scripts/forbidden-strings.toml`, `linter-scripts/generate-dashboard-data.cjs`, `linter-scripts/generate-gwt-acceptance.py`, `linter-scripts/run.ps1`, `linter-scripts/run.sh`, `linter-scripts/suggest-spec-cross-link-fixes.py`, `linter-scripts/validate-guidelines.go`, `linter-scripts/validate-guidelines.py`, `.github/workflows/spec-health.yml`, `src/`

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | broken-link | high | 5/10 | There are 11 broken links within the spec module. |
| 2 | missing-contract | high | 8/10 | The spec lacks explicit contracts (e.g., JSON schemas, YAML OpenAPI) for the structured data elements it defines (e.g., metadata blocks, scoring metrics, keywords, file categories). |
| 3 | untestable | high | 7/10 | The acceptance criteria are high-level descriptions rather than granular, objectively verifiable GWT (Given/When/Then) blocks, making automated testing difficult. |
| 4 | ambiguity | medium | 6/10 | There are numerous code blocks marked as 'plain' without a specific language, which hinders AI's ability to understand the content's context and use it effectively for code generation or analysis. |
| 5 | missing-contract | high | 6/10 | The 'Health Score' calculation criteria are listed, but the actual implementation or formula for calculating this score is not explicitly provided, making it challenging for an AI to independently compute it. |

### Detail + Proposed Corrections

#### 1. [HIGH] There are 11 broken links within the spec module.
- **Category:** broken-link  |  **Impact:** 5/10
- **Evidence:** links_broken: 11
- **Proposed correction:** Review and correct all broken internal links within the spec files to ensure consistency and navigability.

#### 2. [HIGH] The spec lacks explicit contracts (e.g., JSON schemas, YAML OpenAPI) for the structured data elements it defines (e.g., metadata blocks, scoring metrics, keywords, file categories).
- **Category:** missing-contract  |  **Impact:** 8/10
- **Evidence:** has_json_schema: false, has_yaml_openapi: false
- **Proposed correction:** For all structured data elements (metadata, scoring metrics, keywords, file categories), provide explicit JSON schemas or YAML OpenAPI definitions to facilitate AI implementation.

#### 3. [HIGH] The acceptance criteria are high-level descriptions rather than granular, objectively verifiable GWT (Given/When/Then) blocks, making automated testing difficult.
- **Category:** untestable  |  **Impact:** 7/10
- **Evidence:** ac_count: 4, gwt_block_count: 0
- **Proposed correction:** Refactor all acceptance criteria into a GWT format to provide clear, testable statements for automated verification.

#### 4. [MEDIUM] There are numerous code blocks marked as 'plain' without a specific language, which hinders AI's ability to understand the content's context and use it effectively for code generation or analysis.
- **Category:** ambiguity  |  **Impact:** 6/10
- **Evidence:** code_blocks_by_lang: {'plain': 40}
- **Proposed correction:** Review all 'plain' code blocks and assign the correct language (e.g., 'python', 'javascript', 'bash', 'markdown') to improve clarity and utility for AI agents.

#### 5. [HIGH] The 'Health Score' calculation criteria are listed, but the actual implementation or formula for calculating this score is not explicitly provided, making it challenging for an AI to independently compute it.
- **Category:** missing-contract  |  **Impact:** 6/10
- **Evidence:** The spec lists criteria and weights for Health Score but not the calculation logic.
- **Proposed correction:** Provide the explicit formula or algorithm for calculating the 'Health Score' to ensure AI agents can accurately reproduce the metric.
