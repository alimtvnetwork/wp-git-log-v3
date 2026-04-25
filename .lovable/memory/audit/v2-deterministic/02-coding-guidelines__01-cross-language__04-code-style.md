# Audit v2 — `spec/02-coding-guidelines/01-cross-language/04-code-style`

**Date:** 2026-04-25  
**Auditor:** Deterministic rubric (no AI)  
**Implementability Score:** **75/100 (B)**  
**Blast radius:** 2/10

> Deterministic score 75/100 (B) for spec/02-coding-guidelines/01-cross-language/04-code-style.


**Score justification:** Deterministic rubric: contracts=1/3, ac=9, gwt=8, broken_links=1, waffle/kchar=0.08.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 50 | 17.5 |
| Completeness | 20% | 75 | 15.0 |
| Alignment | 15% | 90 | 13.5 |
| Consistency | 10% | 92 | 9.2 |
| Clarity | 10% | 100 | 10.0 |
| Testability | 7% | 100 | 7.0 |
| Maintainability | 3% | 90 | 2.7 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 10,
  "mmd_files": 0,
  "overview_chars": 2589,
  "ac_chars": 4713,
  "ac_count": 9,
  "gwt_block_count": 8,
  "consistency_report": true,
  "code_blocks_total": 65,
  "code_blocks_by_lang": {
    "php": 21,
    "typescript": 18,
    "go": 25,
    "plain": 1
  },
  "has_sql_ddl": false,
  "has_json_schema": false,
  "has_ts_enums": true,
  "has_yaml_openapi": false,
  "has_mermaid": false,
  "links_total": 38,
  "links_broken": 1,
  "todo_density": 3,
  "waffle_per_kchar": 0.08,
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
| 2 | drift | low | 3/10 | 3 TODO/TBD/FIXME marker(s) in module body |

### Detail + Proposed Corrections

#### 1. [HIGH] 1 broken cross-spec link(s)
- **Category:** broken-link  |  **Impact:** 7/10
- **Evidence:** links_total=38, links_broken=1
- **Proposed correction:** Run linter-scripts/check-spec-cross-links.py and fix every reported link.

#### 2. [LOW] 3 TODO/TBD/FIXME marker(s) in module body
- **Category:** drift  |  **Impact:** 3/10
- **Evidence:** todo_density=3
- **Proposed correction:** Resolve or convert markers to tracked acceptance criteria.
