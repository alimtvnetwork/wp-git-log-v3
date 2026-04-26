# Audit v2 — `spec/02-coding-guidelines/05-rust`

**Date:** 2026-04-25  
**Auditor:** Deterministic rubric (no AI)  
**Implementability Score:** **83/100 (B)**  
**Blast radius:** 3/10

> Deterministic score 83/100 (B) for spec/02-coding-guidelines/05-rust.


**Score justification:** Deterministic rubric: contracts=1/3, ac=27, gwt=20, broken_links=0, waffle/kchar=0.25. Gates active: 0.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 60 | 21.0 |
| Completeness | 20% | 85 | 17.0 |
| Alignment | 15% | 100 | 15.0 |
| Consistency | 10% | 100 | 10.0 |
| Clarity | 10% | 100 | 10.0 |
| Testability | 7% | 100 | 7.0 |
| Maintainability | 3% | 90 | 2.7 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 10,
  "mmd_files": 0,
  "overview_chars": 3191,
  "ac_chars": 20751,
  "ac_count": 27,
  "gwt_block_count": 20,
  "consistency_report": true,
  "code_blocks_total": 59,
  "code_blocks_by_lang": {
    "plain": 5,
    "sql": 2,
    "rust": 50,
    "toml": 1,
    "text": 1
  },
  "has_sql_ddl": true,
  "has_json_schema": false,
  "has_ts_enums": false,
  "has_yaml_openapi": false,
  "has_mermaid": false,
  "links_total": 16,
  "links_broken": 0,
  "todo_density": 1,
  "waffle_per_kchar": 0.25,
  "child_modules": 0
}
```

## Implementability Blockers

_(none — AI can build this)_

## Code Mapping

**Implemented by:** _(none — pure-doc spec)_
**Expected but missing:** _(none)_
**Orphan code candidates:** _(none)_

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | drift | low | 3/10 | 1 TODO/TBD/FIXME marker(s) in module body |

### Detail + Proposed Corrections

#### 1. [LOW] 1 TODO/TBD/FIXME marker(s) in module body
- **Category:** drift  |  **Impact:** 3/10
- **Evidence:** todo_density=1
- **Proposed correction:** Resolve or convert markers to tracked acceptance criteria.
