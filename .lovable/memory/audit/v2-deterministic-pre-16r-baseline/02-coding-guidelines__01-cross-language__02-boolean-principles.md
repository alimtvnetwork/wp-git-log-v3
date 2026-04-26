# Audit v2 — `spec/02-coding-guidelines/01-cross-language/02-boolean-principles`

**Date:** 2026-04-25  
**Auditor:** Deterministic rubric (no AI)  
**Implementability Score:** **74/100 (C)**  
**Blast radius:** 2/10

> Deterministic score 74/100 (C) for spec/02-coding-guidelines/01-cross-language/02-boolean-principles.


**Score justification:** Deterministic rubric: contracts=1/3, ac=11, gwt=10, broken_links=2, waffle/kchar=0.2. Gates active: 1.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 50 | 17.5 |
| Completeness | 20% | 90 | 18.0 |
| Alignment | 15% | 80 | 12.0 |
| Consistency | 10% | 70 | 7.0 |
| Clarity | 10% | 100 | 10.0 |
| Testability | 7% | 100 | 7.0 |
| Maintainability | 3% | 100 | 3.0 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 8,
  "mmd_files": 0,
  "overview_chars": 3375,
  "ac_chars": 5368,
  "ac_count": 11,
  "gwt_block_count": 10,
  "consistency_report": true,
  "code_blocks_total": 40,
  "code_blocks_by_lang": {
    "php": 12,
    "typescript": 12,
    "go": 14,
    "csharp": 1,
    "python": 1
  },
  "has_sql_ddl": false,
  "has_json_schema": false,
  "has_ts_enums": true,
  "has_yaml_openapi": false,
  "has_mermaid": false,
  "links_total": 31,
  "links_broken": 2,
  "todo_density": 0,
  "waffle_per_kchar": 0.2,
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

### Detail + Proposed Corrections

#### 1. [HIGH] 2 broken cross-spec link(s)
- **Category:** broken-link  |  **Impact:** 7/10
- **Evidence:** links_total=31, links_broken=2
- **Proposed correction:** Run linter-scripts/check-spec-cross-links.py and fix every reported link.
