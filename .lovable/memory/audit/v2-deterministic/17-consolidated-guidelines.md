# Audit v2 — `spec/17-consolidated-guidelines`

**Date:** 2026-04-25  
**Auditor:** Deterministic rubric (no AI)  
**Implementability Score:** **97/100 (A+)**  
**Blast radius:** 7/10

> Deterministic score 97/100 (A+) for spec/17-consolidated-guidelines.


**Score justification:** Deterministic rubric: contracts=3/3, ac=9, gwt=9, broken_links=0, waffle/kchar=0.09. Gates active: 0.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 100 | 35.0 |
| Completeness | 20% | 85 | 17.0 |
| Alignment | 15% | 100 | 15.0 |
| Consistency | 10% | 100 | 10.0 |
| Clarity | 10% | 100 | 10.0 |
| Testability | 7% | 100 | 7.0 |
| Maintainability | 3% | 90 | 2.7 |

## Deterministic Metrics (pre-AI)

```json
{
  "kind": "",
  "md_files": 38,
  "mmd_files": 0,
  "overview_chars": 10150,
  "ac_chars": 16173,
  "ac_count": 9,
  "gwt_block_count": 9,
  "consistency_report": true,
  "code_blocks_total": 354,
  "code_blocks_by_lang": {
    "bash": 20,
    "markdown": 15,
    "plain": 91,
    "go": 55,
    "typescript": 22,
    "php": 23,
    "csharp": 1,
    "json": 21,
    "ts": 2,
    "toml": 1,
    "rust": 4,
    "yaml": 6,
    "sql": 41,
    "ini": 1,
    "css": 27,
    "html": 4,
    "regex": 1,
    "powershell": 13,
    "gitignore": 1,
    "tsx": 2,
    "python": 3
  },
  "has_sql_ddl": true,
  "has_json_schema": true,
  "has_ts_enums": true,
  "has_yaml_openapi": true,
  "has_typed_lang_contract": true,
  "has_ci_workflow": true,
  "has_normative_contract": false,
  "has_mermaid": false,
  "links_total": 55,
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

### Detail + Proposed Corrections

#### 1. [LOW] 1 TODO/TBD/FIXME marker(s) in module body
- **Category:** drift  |  **Impact:** 3/10
- **Evidence:** todo_density=1
- **Proposed correction:** Resolve or convert markers to tracked acceptance criteria.
