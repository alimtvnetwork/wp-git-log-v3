# Audit v2 — `spec/27-spec-toolchain`

**Date:** 2026-04-25  
**Auditor:** Deterministic rubric (no AI)  
**Implementability Score:** **72/100 (C)**  
**Blast radius:** 2/10

> Deterministic score 72/100 (C) for spec/27-spec-toolchain.


**Score justification:** Deterministic rubric: contracts=1/3, ac=19, gwt=19, broken_links=2, waffle/kchar=0.18. Gates active: 1.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 55 | 19.2 |
| Completeness | 20% | 70 | 14.0 |
| Alignment | 15% | 80 | 12.0 |
| Consistency | 10% | 70 | 7.0 |
| Clarity | 10% | 100 | 10.0 |
| Testability | 7% | 100 | 7.0 |
| Maintainability | 3% | 90 | 2.7 |

## Deterministic Metrics (pre-AI)

```json
{
  "kind": "meta-toolchain",
  "md_files": 40,
  "mmd_files": 0,
  "overview_chars": 11070,
  "ac_chars": 13558,
  "ac_count": 19,
  "gwt_block_count": 19,
  "consistency_report": true,
  "code_blocks_total": 39,
  "code_blocks_by_lang": {
    "bash": 29,
    "toml": 2,
    "json": 1,
    "plain": 4,
    "powershell": 1,
    "ini": 1,
    "markdown": 1
  },
  "has_sql_ddl": false,
  "has_json_schema": true,
  "has_ts_enums": false,
  "has_yaml_openapi": false,
  "has_typed_lang_contract": false,
  "has_ci_workflow": false,
  "has_mermaid": false,
  "links_total": 79,
  "links_broken": 2,
  "todo_density": 30,
  "waffle_per_kchar": 0.18,
  "child_modules": 0
}
```

## Implementability Blockers

- 2 broken cross-spec link(s)

## Code Mapping

**Implemented by:** _(none — pure-doc spec)_
**Expected but missing:** _(none)_
**Orphan code candidates:** _(none)_

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | broken-link | high | 7/10 | 2 broken cross-spec link(s) |
| 2 | drift | low | 3/10 | 30 TODO/TBD/FIXME marker(s) in module body |

### Detail + Proposed Corrections

#### 1. [HIGH] 2 broken cross-spec link(s)
- **Category:** broken-link  |  **Impact:** 7/10
- **Evidence:** links_total=79, links_broken=2
- **Proposed correction:** Run linter-scripts/check-spec-cross-links.py and fix every reported link.

#### 2. [LOW] 30 TODO/TBD/FIXME marker(s) in module body
- **Category:** drift  |  **Impact:** 3/10
- **Evidence:** todo_density=30
- **Proposed correction:** Resolve or convert markers to tracked acceptance criteria.
