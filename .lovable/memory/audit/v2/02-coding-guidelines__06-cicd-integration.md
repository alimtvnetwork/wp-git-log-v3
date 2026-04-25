# Audit v2 — `spec/02-coding-guidelines/06-cicd-integration`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview, 2-pass)  
**Implementability Score:** **79/100 (B)**  
**Blast radius:** 8/10

> This module outlines a linter pack but lacks the corresponding code and critical inlined contracts, preventing AI from implementing it without human intervention. The numerous orphan code files suggest a mismatch between documentation and actual implementation.


**Score justification:** The spec has a high waffle_per_kchar (0.17) but this is offset by many code blocks clarifying intent. All links are unbroken (0 links_broken). AC count is 7 (ac_count), which gives a decent testability score. The consistency report is present, boosting maintainability.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 70 | 24.5 |
| Completeness | 20% | 90 | 18.0 |
| Alignment | 15% | 60 | 9.0 |
| Consistency | 10% | 100 | 10.0 |
| Clarity | 10% | 90 | 9.0 |
| Testability | 7% | 80 | 5.6 |
| Maintainability | 3% | 90 | 2.7 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 13,
  "overview_chars": 3154,
  "ac_chars": 1410,
  "ac_count": 7,
  "gwt_block_count": 0,
  "consistency_report": true,
  "code_blocks_total": 58,
  "code_blocks_by_lang": {
    "json": 2,
    "plain": 10,
    "bash": 27,
    "yaml": 7,
    "python": 1,
    "go": 1,
    "ts": 2,
    "php": 1,
    "toml": 7
  },
  "has_sql_ddl": false,
  "has_json_schema": true,
  "has_ts_enums": true,
  "has_yaml_openapi": true,
  "links_total": 34,
  "links_broken": 0,
  "todo_density": 0,
  "waffle_per_kchar": 0.17,
  "child_modules": 0
}
```

## Implementability Blockers

- Python 3 version not locked down ('>= 3.10' is too broad)
- Details of sarif validation (`validate-sarif.py`) are not inlined.
- YAML OpenAPI schemas mentioned but not inlined.

## Code Mapping

**Implemented by:** _(none — pure-doc spec)_
**Expected but missing:** `linter-cicd/`, `linters-cicd/checks/`, `linters-cicd/configs/`, `linters-cicd/ci/`, `linters-cicd/action.yml`, `linters-cicd/run-all.sh`
**Orphan code candidates:** `linter-scripts/audit-spec-vs-code-v2.py`, `linter-scripts/audit-spec-vs-code.py`, `linter-scripts/check-axios-version.sh`, `linter-scripts/check-forbidden-spec-paths.sh`, `linter-scripts/check-forbidden-strings.py`, `linter-scripts/check-memory-mirror-drift.py`, `linter-scripts/check-readme-canonicals.py`, `linter-scripts/check-readme-install-section.py`, `linter-scripts/check-root-readme.py`, `linter-scripts/check-spec-cross-links.py`, `linter-scripts/check-spec-folder-refs.py`, `linter-scripts/check-tree-health.cjs`, `linter-scripts/fill-missing-acceptance-criteria.cjs`, `linter-scripts/fill-missing-changelogs.cjs`, `linter-scripts/fill-missing-consistency-reports.cjs`, `linter-scripts/forbidden-strings.toml`, `linter-scripts/generate-dashboard-data.cjs`, `linter-scripts/generate-gwt-acceptance.py`, `linter-scripts/generate-spec-index.cjs`, `linter-scripts/run.ps1`, `linter-scripts/run.sh`, `linter-scripts/suggest-spec-cross-link-fixes.py`, `linter-scripts/validate-guidelines.go`, `linter-scripts/validate-guidelines.py`, `.github/workflows/spec-health.yml`, `src/`

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | missing-contract | critical | 8/10 | The spec describes a CI/CD linter pack ('linters-cicd/') but no corresponding files or directories are found in the code index. |
| 2 | missing-contract | high | 6/10 | The SARIF 2.1.0 output schema (01-sarif-contract.md) and plugin model (02-plugin-model.md) are referenced but not inlined, making AI implementation difficult. |
| 3 | missing-contract | medium | 4/10 | The Python 3 version requirement '>= 3.10' is too broad for deterministic AI implementation and could lead to environmental inconsistencies. |

### Detail + Proposed Corrections

#### 1. [CRITICAL] The spec describes a CI/CD linter pack ('linters-cicd/') but no corresponding files or directories are found in the code index.
- **Category:** missing-contract  |  **Impact:** 8/10
- **Evidence:** Purpose section: 'Ship a portable, language-agnostic linter pack — `linters-cicd/`'. Architecture sections also reference this path.
- **Proposed correction:** Either provide the code for 'linters-cicd/' or update the spec to reflect the codebase.

#### 2. [HIGH] The SARIF 2.1.0 output schema (01-sarif-contract.md) and plugin model (02-plugin-model.md) are referenced but not inlined, making AI implementation difficult.
- **Category:** missing-contract  |  **Impact:** 6/10
- **Evidence:** Document Inventory lists '01-sarif-contract.md' and '02-plugin-model.md'.
- **Proposed correction:** Inline the content of '01-sarif-contract.md' and '02-plugin-model.md' directly into this spec module.

#### 3. [MEDIUM] The Python 3 version requirement '>= 3.10' is too broad for deterministic AI implementation and could lead to environmental inconsistencies.
- **Category:** missing-contract  |  **Impact:** 4/10
- **Evidence:** AC-CI-001: 'python3 (≥ 3.10)'
- **Proposed correction:** Specify an exact Python 3 version (e.g., 'python3.10') or a fixed range (e.g., 'python3.10-3.12').
