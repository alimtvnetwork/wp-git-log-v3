# Audit v2 — `spec/05-split-db-architecture/02-features`

**Date:** 2026-04-25  
**Auditor:** Deterministic rubric (no AI)  
**Implementability Score:** **79/100 (B)**  
**Blast radius:** 5/10

> Deterministic score 79/100 (B) for spec/05-split-db-architecture/02-features.


**Score justification:** Deterministic rubric: contracts=2/3, ac=5, gwt=5, broken_links=1, waffle/kchar=0.01. Gates active: 1.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 75 | 26.2 |
| Completeness | 20% | 65 | 13.0 |
| Alignment | 15% | 90 | 13.5 |
| Consistency | 10% | 70 | 7.0 |
| Clarity | 10% | 100 | 10.0 |
| Testability | 7% | 90 | 6.3 |
| Maintainability | 3% | 100 | 3.0 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 9,
  "mmd_files": 0,
  "overview_chars": 816,
  "ac_chars": 2771,
  "ac_count": 5,
  "gwt_block_count": 5,
  "consistency_report": true,
  "code_blocks_total": 66,
  "code_blocks_by_lang": {
    "plain": 20,
    "sql": 18,
    "json": 5,
    "go": 19,
    "ini": 2,
    "bash": 2
  },
  "has_sql_ddl": true,
  "has_json_schema": true,
  "has_ts_enums": false,
  "has_yaml_openapi": false,
  "has_mermaid": false,
  "links_total": 19,
  "links_broken": 1,
  "todo_density": 0,
  "waffle_per_kchar": 0.01,
  "child_modules": 0
}
```

## Implementability Blockers

- 1 broken cross-spec link(s)

## Code Mapping

**Implemented by:** _(none — pure-doc spec)_
**Expected but missing:** _(none)_
**Orphan code candidates:** _(none)_

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | broken-link | high | 7/10 | 1 broken cross-spec link(s) |

### Detail + Proposed Corrections

#### 1. [HIGH] 1 broken cross-spec link(s)
- **Category:** broken-link  |  **Impact:** 7/10
- **Evidence:** links_total=19, links_broken=1
- **Proposed correction:** Run linter-scripts/check-spec-cross-links.py and fix every reported link.
