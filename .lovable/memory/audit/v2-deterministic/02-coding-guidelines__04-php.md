# Audit v2 — `spec/02-coding-guidelines/04-php`

**Date:** 2026-04-25  
**Auditor:** Deterministic rubric (no AI)  
**Implementability Score:** **79/100 (B)**  
**Blast radius:** 2/10

> Deterministic score 79/100 (B) for spec/02-coding-guidelines/04-php.


**Score justification:** Deterministic rubric: contracts=0/3, ac=8, gwt=7, broken_links=0, waffle/kchar=0.09.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 40 | 14.0 |
| Completeness | 20% | 100 | 20.0 |
| Alignment | 15% | 100 | 15.0 |
| Consistency | 10% | 100 | 10.0 |
| Clarity | 10% | 100 | 10.0 |
| Testability | 7% | 100 | 7.0 |
| Maintainability | 3% | 100 | 3.0 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 12,
  "mmd_files": 0,
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
  "has_mermaid": false,
  "links_total": 30,
  "links_broken": 0,
  "todo_density": 0,
  "waffle_per_kchar": 0.09,
  "child_modules": 1
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
| 1 | missing-contract | high | 8/10 | No inlined contract (SQL DDL / JSON schema / TS enum) in module body |

### Detail + Proposed Corrections

#### 1. [HIGH] No inlined contract (SQL DDL / JSON schema / TS enum) in module body
- **Category:** missing-contract  |  **Impact:** 8/10
- **Evidence:** code_blocks_by_lang={"php": 67, "plain": 6}
- **Proposed correction:** Inline at least one normative contract block in 00-overview.md or a dedicated contract file.
