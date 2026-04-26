# Audit v2 — `spec/22-git-logs-v2`

**Date:** 2026-04-25  
**Auditor:** Deterministic rubric (no AI)  
**Implementability Score:** **89/100 (A)**  
**Blast radius:** 5/10

> Deterministic score 89/100 (A) for spec/22-git-logs-v2.


**Score justification:** Deterministic rubric: contracts=2/3, ac=68, gwt=68, broken_links=1, waffle/kchar=0.09. Gates active: 1.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 100 | 35.0 |
| Completeness | 20% | 70 | 14.0 |
| Alignment | 15% | 90 | 13.5 |
| Consistency | 10% | 70 | 7.0 |
| Clarity | 10% | 100 | 10.0 |
| Testability | 7% | 100 | 7.0 |
| Maintainability | 3% | 90 | 2.7 |

## Deterministic Metrics (pre-AI)

```json
{
  "kind": "",
  "md_files": 35,
  "mmd_files": 0,
  "overview_chars": 6779,
  "ac_chars": 61197,
  "ac_count": 68,
  "gwt_block_count": 68,
  "consistency_report": true,
  "code_blocks_total": 100,
  "code_blocks_by_lang": {
    "sql": 3,
    "json": 22,
    "plain": 38,
    "text": 2,
    "bash": 11,
    "yaml": 5,
    "php": 9,
    "bats": 8,
    "md": 2
  },
  "has_sql_ddl": true,
  "has_json_schema": true,
  "has_ts_enums": false,
  "has_yaml_openapi": true,
  "has_typed_lang_contract": true,
  "has_ci_workflow": true,
  "has_mermaid": false,
  "links_total": 64,
  "links_broken": 1,
  "todo_density": 12,
  "waffle_per_kchar": 0.09,
  "child_modules": 0
}
```

## Implementability Blockers

- 1 broken cross-spec link(s)

## Code Mapping

**Implemented by:** _(none — pure-doc spec)_
**Expected but missing:** _(none)_
**Orphan code candidates:** _(none)_

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | broken-link | high | 7/10 | 1 broken cross-spec link(s) |
| 2 | drift | low | 3/10 | 12 TODO/TBD/FIXME marker(s) in module body |

### Detail + Proposed Corrections

#### 1. [HIGH] 1 broken cross-spec link(s)
- **Category:** broken-link  |  **Impact:** 7/10
- **Evidence:** links_total=64, links_broken=1
- **Proposed correction:** Run linter-scripts/check-spec-cross-links.py and fix every reported link.

#### 2. [LOW] 12 TODO/TBD/FIXME marker(s) in module body
- **Category:** drift  |  **Impact:** 3/10
- **Evidence:** todo_density=12
- **Proposed correction:** Resolve or convert markers to tracked acceptance criteria.
