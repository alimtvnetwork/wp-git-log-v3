# Audit v2 — `spec/02-coding-guidelines/05-rust`

**Date:** 2026-04-25  
**Auditor:** Deterministic rubric (no AI)  
**Implementability Score:** **80/100 (B)**  
**Blast radius:** 3/10

> Deterministic score 80/100 (B) for spec/02-coding-guidelines/05-rust.


**Score justification:** Deterministic rubric: contracts=1/3, ac=6, gwt=0, broken_links=0, waffle/kchar=0.27.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 60 | 21.0 |
| Completeness | 20% | 80 | 16.0 |
| Alignment | 15% | 100 | 15.0 |
| Consistency | 10% | 100 | 10.0 |
| Clarity | 10% | 100 | 10.0 |
| Testability | 7% | 76 | 5.3 |
| Maintainability | 3% | 100 | 3.0 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 10,
  "mmd_files": 0,
  "overview_chars": 3191,
  "ac_chars": 1632,
  "ac_count": 6,
  "gwt_block_count": 0,
  "consistency_report": true,
  "code_blocks_total": 58,
  "code_blocks_by_lang": {
    "plain": 5,
    "sql": 2,
    "rust": 50,
    "toml": 1
  },
  "has_sql_ddl": true,
  "has_json_schema": false,
  "has_ts_enums": false,
  "has_yaml_openapi": false,
  "has_mermaid": false,
  "links_total": 9,
  "links_broken": 0,
  "todo_density": 0,
  "waffle_per_kchar": 0.27,
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
| 1 | untestable | medium | 5/10 | Acceptance criteria present but no Given/When/Then blocks |

### Detail + Proposed Corrections

#### 1. [MEDIUM] Acceptance criteria present but no Given/When/Then blocks
- **Category:** untestable  |  **Impact:** 5/10
- **Evidence:** ac_count=6, gwt_block_count=0
- **Proposed correction:** Rewrite each AC as a Given/When/Then block.
