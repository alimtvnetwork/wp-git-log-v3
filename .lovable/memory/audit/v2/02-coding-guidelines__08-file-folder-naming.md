# Audit v2 — `spec/02-coding-guidelines/08-file-folder-naming`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview, 2-pass)  
**Implementability Score:** **64/100 (C)**  
**Blast radius:** 8/10

> This module provides comprehensive naming guidelines but suffers from a broken link and lacks structured, machine-readable contracts, which hinders AI implementability.


**Score justification:** The broken link significantly impacts consistency. The module also requires reading sibling files for full context, lowering implementability. While ACs exist, they are not in the preferred GWT format.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 40 | 14.0 |
| Completeness | 20% | 80 | 16.0 |
| Alignment | 15% | 60 | 9.0 |
| Consistency | 10% | 70 | 7.0 |
| Clarity | 10% | 100 | 10.0 |
| Testability | 7% | 70 | 4.9 |
| Maintainability | 3% | 100 | 3.0 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 9,
  "overview_chars": 2882,
  "ac_chars": 2752,
  "ac_count": 5,
  "gwt_block_count": 5,
  "consistency_report": true,
  "code_blocks_total": 36,
  "code_blocks_by_lang": {
    "plain": 34,
    "php": 1,
    "bash": 1
  },
  "has_sql_ddl": false,
  "has_json_schema": false,
  "has_ts_enums": false,
  "has_yaml_openapi": false,
  "links_total": 26,
  "links_broken": 1,
  "todo_density": 0,
  "waffle_per_kchar": 0.0,
  "child_modules": 0
}
```

## Implementability Blockers

- Requires reading multiple sibling markdown files to fully understand naming conventions for each language.
- No structured data (e.g., config files, DDL) provided for the naming conventions, only prose within markdown.

## Code Mapping

**Implemented by:** `linter-scripts/check-spec-cross-links.py`, `linter-scripts/check-tree-health.cjs`, `linter-scripts/run.sh`, `linter-scripts/validate-guidelines.go`, `linter-scripts/validate-guidelines.py`
**Expected but missing:** _(none)_
**Orphan code candidates:** _(none)_

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | broken-link | medium | 5/10 | A cross-reference link in the overview document is broken. |
| 2 | missing-contract | medium | 6/10 | The naming conventions are described in prose across multiple markdown files rather than in a structured, machine-readable format. |

### Detail + Proposed Corrections

#### 1. [MEDIUM] A cross-reference link in the overview document is broken.
- **Category:** broken-link  |  **Impact:** 5/10
- **Evidence:** Broken link: '../01-cross-language/00-overview.md' in 00-overview.md
- **Proposed correction:** Correct the broken link to point to an existing document.

#### 2. [MEDIUM] The naming conventions are described in prose across multiple markdown files rather than in a structured, machine-readable format.
- **Category:** missing-contract  |  **Impact:** 6/10
- **Evidence:** All naming rules are embedded in markdown files (e.g., 01-cross-language.md, 02-php-wordpress.md).
- **Proposed correction:** Translate the naming conventions into a machine-readable format, such as a YAML configuration file or a data structure that linters can directly consume.
