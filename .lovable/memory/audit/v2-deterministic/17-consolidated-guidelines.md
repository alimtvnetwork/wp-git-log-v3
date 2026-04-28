# Audit v2 — `spec/17-consolidated-guidelines`

**Date:** 2026-04-25  
**Auditor:** Deterministic rubric (no AI)  
**Implementability Score:** **97/100 (A+)**  
**Blast radius:** 7/10

> Deterministic score 97/100 (A+) for spec/17-consolidated-guidelines.


**Score justification:** Deterministic rubric: contracts=3/3, ac=8, gwt=8, broken_links=0, waffle/kchar=0.09. Gates active: 0.

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
  "md_files": 37,
  "mmd_files": 0,
  "overview_chars": 9194,
  "ac_chars": 13158,
  "ac_count": 8,
  "gwt_block_count": 8,
  "consistency_report": true,
  "code_blocks_total": 352,
  "code_blocks_by_lang": {
    "bash": 6,
    "markdown": 7,
    "plain": 189,
    "go": 42,
    "typescript": 18,
    "php": 13,
    "csharp": 1,
    "json": 13,
    "ts": 2,
    "toml": 1,
    "rust": 4,
    "yaml": 3,
    "sql": 10,
    "ini": 1,
    "css": 24,
    "html": 4,
    "regex": 1,
    "powershell": 12,
    "gitignore": 1
  },
  "has_sql_ddl": true,
  "has_json_schema": true,
  "has_ts_enums": true,
  "has_yaml_openapi": true,
  "has_typed_lang_contract": true,
  "has_ci_workflow": false,
  "has_normative_contract": false,
  "has_mermaid": false,
  "links_total": 44,
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
