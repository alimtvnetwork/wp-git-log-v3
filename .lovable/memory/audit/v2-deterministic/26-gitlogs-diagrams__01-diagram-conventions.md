# Audit v2 — `spec/26-gitlogs-diagrams/01-diagram-conventions`

**Date:** 2026-04-25  
**Auditor:** Deterministic rubric (no AI)  
**Implementability Score:** **64/100 (C)**  
**Blast radius:** 2/10

> Deterministic score 64/100 (C) for spec/26-gitlogs-diagrams/01-diagram-conventions.


**Score justification:** Deterministic rubric: contracts=1/3, ac=0, gwt=6, broken_links=0, waffle/kchar=0.0. Gates active: 0.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 45 | 15.8 |
| Completeness | 20% | 50 | 10.0 |
| Alignment | 15% | 100 | 15.0 |
| Consistency | 10% | 100 | 10.0 |
| Clarity | 10% | 100 | 10.0 |
| Testability | 7% | 10 | 0.7 |
| Maintainability | 3% | 100 | 3.0 |

## Deterministic Metrics (pre-AI)

```json
{
  "kind": "",
  "md_files": 4,
  "mmd_files": 1,
  "overview_chars": 2153,
  "ac_chars": 1614,
  "ac_count": 0,
  "gwt_block_count": 6,
  "consistency_report": true,
  "code_blocks_total": 2,
  "code_blocks_by_lang": {
    "ts": 1,
    "mermaid": 1
  },
  "has_sql_ddl": false,
  "has_json_schema": false,
  "has_ts_enums": true,
  "has_yaml_openapi": false,
  "has_typed_lang_contract": false,
  "has_ci_workflow": false,
  "has_normative_contract": false,
  "has_mermaid": true,
  "links_total": 0,
  "links_broken": 0,
  "todo_density": 0,
  "waffle_per_kchar": 0.0,
  "child_modules": 0
}
```

## Implementability Blockers

- No acceptance criteria found

## Code Mapping

**Implemented by:** _(none — pure-doc spec)_
**Expected but missing:** _(none)_
**Orphan code candidates:** _(none)_

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | untestable | high | 8/10 | No acceptance criteria found |

### Detail + Proposed Corrections

#### 1. [HIGH] No acceptance criteria found
- **Category:** untestable  |  **Impact:** 8/10
- **Evidence:** ac_count=0 in 97-acceptance-criteria.md
- **Proposed correction:** Run linter-scripts/generate-gwt-acceptance.py to scaffold AC blocks.
