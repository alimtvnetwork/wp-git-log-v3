# Audit v2 — `spec/02-coding-guidelines/01-cross-language/15-master-coding-guidelines`

**Date:** 2026-04-25  
**Auditor:** Deterministic rubric (no AI)  
**Implementability Score:** **74/100 (C)**  
**Blast radius:** 2/10

> Deterministic score 74/100 (C) for spec/02-coding-guidelines/01-cross-language/15-master-coding-guidelines.


**Score justification:** Deterministic rubric: contracts=1/3, ac=9, gwt=8, broken_links=1, waffle/kchar=0.13. Gates active: 1.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 50 | 17.5 |
| Completeness | 20% | 80 | 16.0 |
| Alignment | 15% | 90 | 13.5 |
| Consistency | 10% | 70 | 7.0 |
| Clarity | 10% | 100 | 10.0 |
| Testability | 7% | 100 | 7.0 |
| Maintainability | 3% | 100 | 3.0 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 10,
  "mmd_files": 0,
  "overview_chars": 1564,
  "ac_chars": 5021,
  "ac_count": 9,
  "gwt_block_count": 8,
  "consistency_report": true,
  "code_blocks_total": 35,
  "code_blocks_by_lang": {
    "plain": 3,
    "php": 15,
    "go": 15,
    "typescript": 2
  },
  "has_sql_ddl": false,
  "has_json_schema": false,
  "has_ts_enums": true,
  "has_yaml_openapi": false,
  "has_mermaid": false,
  "links_total": 54,
  "links_broken": 1,
  "todo_density": 0,
  "waffle_per_kchar": 0.13,
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

### Detail + Proposed Corrections

#### 1. [HIGH] 1 broken cross-spec link(s)
- **Category:** broken-link  |  **Impact:** 7/10
- **Evidence:** links_total=54, links_broken=1
- **Proposed correction:** Run linter-scripts/check-spec-cross-links.py and fix every reported link.
