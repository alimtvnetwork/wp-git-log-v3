# Audit v2 — `spec/27-spec-toolchain`

**Date:** 2026-04-25  
**Auditor:** Deterministic rubric (no AI)  
**Implementability Score:** **78/100 (B)**  
**Blast radius:** 2/10

> Deterministic score 78/100 (B) for spec/27-spec-toolchain.


**Score justification:** Deterministic rubric: contracts=1/3, ac=19, gwt=19, broken_links=0, waffle/kchar=0.12. Gates active: 0.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 55 | 19.2 |
| Completeness | 20% | 70 | 14.0 |
| Alignment | 15% | 100 | 15.0 |
| Consistency | 10% | 100 | 10.0 |
| Clarity | 10% | 100 | 10.0 |
| Testability | 7% | 100 | 7.0 |
| Maintainability | 3% | 90 | 2.7 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 37,
  "mmd_files": 0,
  "overview_chars": 8720,
  "ac_chars": 13558,
  "ac_count": 19,
  "gwt_block_count": 19,
  "consistency_report": true,
  "code_blocks_total": 34,
  "code_blocks_by_lang": {
    "bash": 27,
    "toml": 2,
    "json": 1,
    "powershell": 1,
    "plain": 1,
    "ini": 1,
    "markdown": 1
  },
  "has_sql_ddl": false,
  "has_json_schema": true,
  "has_ts_enums": false,
  "has_yaml_openapi": false,
  "has_mermaid": false,
  "links_total": 131,
  "links_broken": 0,
  "todo_density": 7,
  "waffle_per_kchar": 0.12,
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
| 1 | drift | low | 3/10 | 7 TODO/TBD/FIXME marker(s) in module body |

### Detail + Proposed Corrections

#### 1. [LOW] 7 TODO/TBD/FIXME marker(s) in module body
- **Category:** drift  |  **Impact:** 3/10
- **Evidence:** todo_density=7
- **Proposed correction:** Resolve or convert markers to tracked acceptance criteria.
