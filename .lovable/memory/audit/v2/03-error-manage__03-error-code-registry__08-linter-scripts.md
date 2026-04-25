# Audit v2 — `spec/03-error-manage/03-error-code-registry/08-linter-scripts`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview, 2-pass)  
**Implementability Score:** **66/100 (C)**  
**Blast radius:** 6/10

> This module provides good acceptance criteria and defines key contracts, but lacks the actual code for the linter scripts it describes. The broken link also hurts consistency.


**Score justification:** The broken link significantly lowered consistency. Implementability is capped due to the absence of the actual linter script code, requiring inference. Alignment is low because the spec describes linter scripts in general, but the primary code listed is for generating GWT acceptance criteria, only tangentially related.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 45 | 15.8 |
| Completeness | 20% | 80 | 16.0 |
| Alignment | 15% | 60 | 9.0 |
| Consistency | 10% | 70 | 7.0 |
| Clarity | 10% | 100 | 10.0 |
| Testability | 7% | 80 | 5.6 |
| Maintainability | 3% | 100 | 3.0 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 3,
  "overview_chars": 655,
  "ac_chars": 3308,
  "ac_count": 6,
  "gwt_block_count": 5,
  "consistency_report": true,
  "code_blocks_total": 0,
  "code_blocks_by_lang": {},
  "has_sql_ddl": false,
  "has_json_schema": false,
  "has_ts_enums": false,
  "has_yaml_openapi": false,
  "links_total": 3,
  "links_broken": 1,
  "todo_density": 0,
  "waffle_per_kchar": 0.0,
  "child_modules": 0
}
```

## Implementability Blockers

- No actual linter script code is provided, only specifications for their behavior. An AI would need to infer or generate the linter logic from scratch.

## Code Mapping

**Implemented by:** `linter-scripts/generate-gwt-acceptance.py`
**Expected but missing:** `linter-scripts/error-code-linter.py`, `linter-scripts/error-code-formatter.py`
**Orphan code candidates:** `linter-scripts/audit-spec-vs-code-v2.py`, `linter-scripts/audit-spec-vs-code.py`, `linter-scripts/check-axios-version.sh`, `linter-scripts/check-forbidden-spec-paths.sh`, `linter-scripts/check-forbidden-strings.py`, `linter-scripts/check-memory-mirror-drift.py`, `linter-scripts/check-readme-canonicals.py`, `linter-scripts/check-readme-install-section.py`, `linter-scripts/check-root-readme.py`, `linter-scripts/check-spec-cross-links.py`, `linter-scripts/check-spec-folder-refs.py`, `linter-scripts/check-tree-health.cjs`, `linter-scripts/fill-missing-acceptance-criteria.cjs`, `linter-scripts/fill-missing-changelogs.cjs`, `linter-scripts/fill-missing-consistency-reports.cjs`, `linter-scripts/forbidden-strings.toml`, `linter-scripts/generate-dashboard-data.cjs`, `linter-scripts/generate-spec-index.cjs`, `linter-scripts/run.ps1`, `linter-scripts/run.sh`, `linter-scripts/suggest-spec-cross-link-fixes.py`, `linter-scripts/validate-guidelines.go`, `linter-scripts/validate-guidelines.py`, `.github/workflows/spec-health.yml`

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | broken-link | medium | 5/10 | A link to the changelog is broken. |
| 2 | missing-spec | high | 8/10 | The core linter scripts that this spec module describes are not present in the code index. |

### Detail + Proposed Corrections

#### 1. [MEDIUM] A link to the changelog is broken.
- **Category:** broken-link  |  **Impact:** 5/10
- **Evidence:** links_broken > 0
- **Proposed correction:** Update `[Module changelog](./98-changelog.md)` to a valid path or remove if not applicable.

#### 2. [HIGH] The core linter scripts that this spec module describes are not present in the code index.
- **Category:** missing-spec  |  **Impact:** 8/10
- **Evidence:** The spec describes 'Error code registry automation scripts' and 'consistency linter script', but the code index primarily shows utility scripts for generating documentation artifacts.
- **Proposed correction:** Include the actual linter scripts (e.g., those that perform duplicate detection, formatting validation, or cross-reference validation) in the code index, or adjust the spec to accurately reflect the existing code.
