# Audit v2 — `spec/05-split-db-architecture/03-issues`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview, 2-pass)  
**Implementability Score:** **66/100 (C)**  
**Blast radius:** 5/10

> This module is primarily a documentation health description rather than a functional specification. While it describes how its own documentation should be structured, it fails to specify any actual 'issue' system in an implementable way.


**Score justification:** The spec describes an issue index but provides no contracts like DDL for storing issues, leading to low implementability. The broken link also negatively impacts consistency. Testability is low due to ACs primarily testing documentation health rather than functional requirements.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 40 | 14.0 |
| Completeness | 20% | 80 | 16.0 |
| Alignment | 15% | 90 | 13.5 |
| Consistency | 10% | 70 | 7.0 |
| Clarity | 10% | 100 | 10.0 |
| Testability | 7% | 40 | 2.8 |
| Maintainability | 3% | 100 | 3.0 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 4,
  "overview_chars": 230,
  "ac_chars": 2624,
  "ac_count": 5,
  "gwt_block_count": 5,
  "consistency_report": true,
  "code_blocks_total": 1,
  "code_blocks_by_lang": {
    "bash": 1
  },
  "has_sql_ddl": false,
  "has_json_schema": false,
  "has_ts_enums": false,
  "has_yaml_openapi": false,
  "links_total": 6,
  "links_broken": 1,
  "todo_density": 0,
  "waffle_per_kchar": 0.0,
  "child_modules": 0
}
```

## Implementability Blockers

- No SQL DDL provided for issue storage
- No schema provided for issue data structure
- No API contracts defined for interacting with issues

## Code Mapping

**Implemented by:** `linter-scripts/check-tree-health.cjs`, `linter-scripts/check-spec-cross-links.py`, `linter-scripts/run.sh`
**Expected but missing:** _(none)_
**Orphan code candidates:** _(none)_

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | missing-contract | high | 8/10 | No data contracts or schemas for issues are defined. |
| 2 | broken-link | low | 2/10 | A link within the acceptance criteria is broken. |

### Detail + Proposed Corrections

#### 1. [HIGH] No data contracts or schemas for issues are defined.
- **Category:** missing-contract  |  **Impact:** 8/10
- **Evidence:** The entire module lacks any definition of how issues are structured or stored.
- **Proposed correction:** Add SQL DDL (or equivalent) for issue storage and a JSON schema (or equivalent) for issue data representation. Define API contracts for issue creation, retrieval, and updates.

#### 2. [LOW] A link within the acceptance criteria is broken.
- **Category:** broken-link  |  **Impact:** 2/10
- **Evidence:** One broken link reported in deterministic metrics.
- **Proposed correction:** Fix the broken link in the acceptance criteria document to ensure all cross-references are valid.
