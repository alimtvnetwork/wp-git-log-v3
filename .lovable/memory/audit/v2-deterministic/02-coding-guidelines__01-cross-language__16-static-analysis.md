# Audit v2 — `spec/02-coding-guidelines/01-cross-language/16-static-analysis`

**Date:** 2026-04-25  
**Auditor:** Deterministic rubric (no AI)  
**Implementability Score:** **90/100 (A)**  
**Blast radius:** 4/10

> Deterministic score 90/100 (A) for spec/02-coding-guidelines/01-cross-language/16-static-analysis.


**Score justification:** Deterministic rubric: contracts=2/3, ac=28, gwt=20, broken_links=0, waffle/kchar=0.06. Gates active: 0.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 80 | 28.0 |
| Completeness | 20% | 85 | 17.0 |
| Alignment | 15% | 100 | 15.0 |
| Consistency | 10% | 100 | 10.0 |
| Clarity | 10% | 100 | 10.0 |
| Testability | 7% | 100 | 7.0 |
| Maintainability | 3% | 90 | 2.7 |

## Deterministic Metrics (pre-AI)

```json
{
  "kind": "future-spec",
  "md_files": 13,
  "mmd_files": 0,
  "overview_chars": 7962,
  "ac_chars": 21603,
  "ac_count": 28,
  "gwt_block_count": 20,
  "consistency_report": true,
  "code_blocks_total": 30,
  "code_blocks_by_lang": {
    "yaml": 5,
    "json": 1,
    "ts": 1,
    "bash": 6,
    "xml": 3,
    "neon": 1,
    "ini": 4,
    "toml": 4,
    "rust": 1,
    "js": 1,
    "plain": 1,
    "properties": 1,
    "text": 1
  },
  "has_sql_ddl": false,
  "has_json_schema": true,
  "has_ts_enums": true,
  "has_yaml_openapi": true,
  "has_typed_lang_contract": false,
  "has_ci_workflow": true,
  "has_normative_contract": false,
  "has_mermaid": false,
  "links_total": 155,
  "links_broken": 0,
  "todo_density": 1,
  "waffle_per_kchar": 0.06,
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
