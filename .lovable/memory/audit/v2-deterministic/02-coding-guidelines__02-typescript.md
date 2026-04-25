# Audit v2 — `spec/02-coding-guidelines/02-typescript`

**Date:** 2026-04-25  
**Auditor:** Deterministic rubric (no AI)  
**Implementability Score:** **70/100 (C)**  
**Blast radius:** 2/10

> Deterministic score 70/100 (C) for spec/02-coding-guidelines/02-typescript.


**Score justification:** Deterministic rubric: contracts=1/3, ac=2, gwt=0, broken_links=0, waffle/kchar=0.09. Gates active: 0.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 50 | 17.5 |
| Completeness | 20% | 55 | 11.0 |
| Alignment | 15% | 100 | 15.0 |
| Consistency | 10% | 100 | 10.0 |
| Clarity | 10% | 100 | 10.0 |
| Testability | 7% | 52 | 3.6 |
| Maintainability | 3% | 90 | 2.7 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 16,
  "mmd_files": 0,
  "overview_chars": 3035,
  "ac_chars": 642,
  "ac_count": 2,
  "gwt_block_count": 0,
  "consistency_report": true,
  "code_blocks_total": 75,
  "code_blocks_by_lang": {
    "typescript": 73,
    "bash": 1,
    "javascript": 1
  },
  "has_sql_ddl": false,
  "has_json_schema": false,
  "has_ts_enums": true,
  "has_yaml_openapi": false,
  "has_mermaid": false,
  "links_total": 70,
  "links_broken": 0,
  "todo_density": 1,
  "waffle_per_kchar": 0.09,
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
| 2 | untestable | medium | 5/10 | Acceptance criteria present but no Given/When/Then blocks |

### Detail + Proposed Corrections

#### 1. [LOW] 1 TODO/TBD/FIXME marker(s) in module body
- **Category:** drift  |  **Impact:** 3/10
- **Evidence:** todo_density=1
- **Proposed correction:** Resolve or convert markers to tracked acceptance criteria.

#### 2. [MEDIUM] Acceptance criteria present but no Given/When/Then blocks
- **Category:** untestable  |  **Impact:** 5/10
- **Evidence:** ac_count=2, gwt_block_count=0
- **Proposed correction:** Rewrite each AC as a Given/When/Then block.
