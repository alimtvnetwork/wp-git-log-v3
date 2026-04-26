# Audit v2 — `spec/02-coding-guidelines/03-golang/04-golang-standards-reference`

**Date:** 2026-04-25  
**Auditor:** Deterministic rubric (no AI)  
**Implementability Score:** **77/100 (B)**  
**Blast radius:** 0/10

> Deterministic score 77/100 (B) for spec/02-coding-guidelines/03-golang/04-golang-standards-reference.


**Score justification:** Deterministic rubric: contracts=0/3, ac=10, gwt=9, broken_links=0, waffle/kchar=0.1. Gates active: 0.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 40 | 14.0 |
| Completeness | 20% | 90 | 18.0 |
| Alignment | 15% | 100 | 15.0 |
| Consistency | 10% | 100 | 10.0 |
| Clarity | 10% | 100 | 10.0 |
| Testability | 7% | 100 | 7.0 |
| Maintainability | 3% | 100 | 3.0 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 10,
  "mmd_files": 0,
  "overview_chars": 2742,
  "ac_chars": 4955,
  "ac_count": 10,
  "gwt_block_count": 9,
  "consistency_report": true,
  "code_blocks_total": 44,
  "code_blocks_by_lang": {
    "go": 40,
    "plain": 4
  },
  "has_sql_ddl": false,
  "has_json_schema": false,
  "has_ts_enums": false,
  "has_yaml_openapi": false,
  "has_mermaid": false,
  "links_total": 38,
  "links_broken": 0,
  "todo_density": 0,
  "waffle_per_kchar": 0.1,
  "child_modules": 0
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
- **Evidence:** code_blocks_by_lang={"go": 40, "plain": 4}
- **Proposed correction:** Inline at least one normative contract block in 00-overview.md or a dedicated contract file.
