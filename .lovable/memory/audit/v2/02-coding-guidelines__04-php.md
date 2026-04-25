# Audit v2 — `spec/02-coding-guidelines/04-php`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview, 2-pass)  
**Implementability Score:** **54/100 (D)**  
**Blast radius:** 8/10

> This module provides decent coding guidelines but suffers significantly from implementability and alignment issues. Without actual PHP code for enums, helpers, and examples of forbidden patterns, a mediocre AI coder cannot implement this spec without human intervention. The absence of any PHP code in the provided codebase index makes alignment impossible, essentially rendering this a purely theoretical document.


**Score justification:** Implementability is low because while the spec provides contracts, it doesn't provide the actual PHP code or DDL for these. Alignment is 0 as there is no PHP code in the codebase index. Consistency and clarity are high due to no broken links and low waffle. Testability is good due to sufficient ACs.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 30 | 10.5 |
| Completeness | 20% | 80 | 16.0 |
| Alignment | 15% | 0 | 0.0 |
| Consistency | 10% | 100 | 10.0 |
| Clarity | 10% | 100 | 10.0 |
| Testability | 7% | 70 | 4.9 |
| Maintainability | 3% | 100 | 3.0 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 12,
  "overview_chars": 2063,
  "ac_chars": 4761,
  "ac_count": 8,
  "gwt_block_count": 7,
  "consistency_report": true,
  "code_blocks_total": 73,
  "code_blocks_by_lang": {
    "php": 67,
    "plain": 6
  },
  "has_sql_ddl": false,
  "has_json_schema": false,
  "has_ts_enums": false,
  "has_yaml_openapi": false,
  "links_total": 30,
  "links_broken": 0,
  "todo_density": 0,
  "waffle_per_kchar": 0.09,
  "child_modules": 1
}
```

## Implementability Blockers

- No DDL for enums (only method signature)
- No actual PHP code implementation of ResultHelper or Enums
- No concrete examples of forbidden patterns in code

## Code Mapping

**Implemented by:** _(none — pure-doc spec)_
**Expected but missing:** `RiseupAsia\Enums`, `RiseupAsia\Helpers\ResultHelper`, `PHP implementations conforming to the described guidelines`
**Orphan code candidates:** `linter-scripts/audit-spec-vs-code-v2.py`, `linter-scripts/audit-spec-vs-code.py`, `linter-scripts/check-axios-version.sh`, `linter-scripts/check-forbidden-spec-paths.sh`, `linter-scripts/check-forbidden-strings.py`, `linter-scripts/check-memory-mirror-drift.py`, `linter-scripts/check-readme-canonicals.py`, `linter-scripts/check-readme-install-section.py`, `linter-scripts/check-root-readme.py`, `linter-scripts/check-spec-cross-links.py`, `linter-scripts/check-spec-folder-refs.py`, `linter-scripts/check-tree-health.cjs`, `linter-scripts/fill-missing-acceptance-criteria.cjs`, `linter-scripts/fill-missing-changelogs.cjs`, `linter-scripts/fill-missing-consistency-reports.cjs`, `linter-scripts/forbidden-strings.toml`, `linter-scripts/generate-dashboard-data.cjs`, `linter-scripts/generate-gwt-acceptance.py`, `linter-scripts/generate-spec-index.cjs`, `linter-scripts/run.ps1`, `linter-scripts/run.sh`, `linter-scripts/suggest-spec-cross-link-fixes.py`, `linter-scripts/validate-guidelines.go`, `linter-scripts/validate-guidelines.py`, `.github/workflows/spec-health.yml`, `src/`

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | missing-contract | critical | 8/10 | Missing concrete PHP code for enums and ResultHelper. |
| 2 | missing-contract | high | 7/10 | Lack of code examples for forbidden patterns. |
| 3 | missing-spec | critical | 9/10 | The codebase index does not contain any PHP files to align against. |

### Detail + Proposed Corrections

#### 1. [CRITICAL] Missing concrete PHP code for enums and ResultHelper.
- **Category:** missing-contract  |  **Impact:** 8/10
- **Evidence:** Inlined Contracts section describes required enum method `isEqual` and `ResultHelper` methods, but provides signatures instead of full PHP code.
- **Proposed correction:** Provide full PHP code implementations for all mentioned enums and the `ResultHelper` class, including all methods and properties.

#### 2. [HIGH] Lack of code examples for forbidden patterns.
- **Category:** missing-contract  |  **Impact:** 7/10
- **Evidence:** `02-forbidden-patterns.md` is mentioned, but the overview doesn't present actual code examples of these patterns, only refers to the document.
- **Proposed correction:** Include concrete PHP code examples of both correct and forbidden patterns directly within the spec, especially for `02-forbidden-patterns.md`.

#### 3. [CRITICAL] The codebase index does not contain any PHP files to align against.
- **Category:** missing-spec  |  **Impact:** 9/10
- **Evidence:** The 'ACTUAL CODE IMPLEMENTATION INDEX' lists only linter scripts and GitHub workflow files in Python, Javascript, and Go, but no PHP source code.
- **Proposed correction:** Either provide a representative set of PHP code files for alignment or explicitly state that this is a pure documentation spec with no directly allocatable code.
