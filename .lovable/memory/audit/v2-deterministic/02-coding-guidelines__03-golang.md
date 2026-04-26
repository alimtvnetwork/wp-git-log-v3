# Audit v2 — `spec/02-coding-guidelines/03-golang`

**Date:** 2026-04-25  
**Auditor:** Deterministic rubric (no AI)  
**Implementability Score:** **76/100 (B)**  
**Blast radius:** 4/10

> Deterministic score 76/100 (B) for spec/02-coding-guidelines/03-golang.


**Score justification:** Deterministic rubric: contracts=0/3, ac=23, gwt=20, broken_links=0, waffle/kchar=0.18. Gates active: 0.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 40 | 14.0 |
| Completeness | 20% | 85 | 17.0 |
| Alignment | 15% | 100 | 15.0 |
| Consistency | 10% | 100 | 10.0 |
| Clarity | 10% | 100 | 10.0 |
| Testability | 7% | 100 | 7.0 |
| Maintainability | 3% | 90 | 2.7 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 11,
  "mmd_files": 0,
  "overview_chars": 1170,
  "ac_chars": 16626,
  "ac_count": 23,
  "gwt_block_count": 20,
  "consistency_report": true,
  "code_blocks_total": 46,
  "code_blocks_by_lang": {
    "go": 43,
    "plain": 2,
    "text": 1
  },
  "has_sql_ddl": false,
  "has_json_schema": false,
  "has_ts_enums": false,
  "has_yaml_openapi": false,
  "has_mermaid": false,
  "links_total": 28,
  "links_broken": 0,
  "todo_density": 1,
  "waffle_per_kchar": 0.18,
  "child_modules": 2
}
```

## Implementability Blockers

- No inlined contract (SQL DDL / JSON schema / TS enum) in module body

## Code Mapping

**Implemented by:** _(none — pure-doc spec)_
**Expected but missing:** _(none)_
**Orphan code candidates:** _(none)_

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | drift | low | 3/10 | 1 TODO/TBD/FIXME marker(s) in module body |
| 2 | missing-contract | high | 8/10 | No inlined contract (SQL DDL / JSON schema / TS enum) in module body |

### Detail + Proposed Corrections

#### 1. [LOW] 1 TODO/TBD/FIXME marker(s) in module body
- **Category:** drift  |  **Impact:** 3/10
- **Evidence:** todo_density=1
- **Proposed correction:** Resolve or convert markers to tracked acceptance criteria.

#### 2. [HIGH] No inlined contract (SQL DDL / JSON schema / TS enum) in module body
- **Category:** missing-contract  |  **Impact:** 8/10
- **Evidence:** code_blocks_by_lang={"go": 43, "plain": 2, "text": 1}
- **Proposed correction:** Inline at least one normative contract block in 00-overview.md or a dedicated contract file.
