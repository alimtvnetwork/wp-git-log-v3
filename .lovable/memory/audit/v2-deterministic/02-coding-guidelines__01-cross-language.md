# Audit v2 — `spec/02-coding-guidelines/01-cross-language`

**Date:** 2026-04-25  
**Auditor:** Deterministic rubric (no AI)  
**Implementability Score:** **96/100 (A+)**  
**Blast radius:** 10/10

> Deterministic score 96/100 (A+) for spec/02-coding-guidelines/01-cross-language.


**Score justification:** Deterministic rubric: contracts=3/3, ac=23, gwt=20, broken_links=1, waffle/kchar=0.22. Gates active: 1.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 100 | 35.0 |
| Completeness | 20% | 100 | 20.0 |
| Alignment | 15% | 90 | 13.5 |
| Consistency | 10% | 70 | 7.0 |
| Clarity | 10% | 100 | 10.0 |
| Testability | 7% | 100 | 7.0 |
| Maintainability | 3% | 100 | 3.0 |

## Deterministic Metrics (pre-AI)

```json
{
  "kind": "future-spec",
  "md_files": 31,
  "mmd_files": 0,
  "overview_chars": 7547,
  "ac_chars": 16790,
  "ac_count": 23,
  "gwt_block_count": 20,
  "consistency_report": true,
  "code_blocks_total": 258,
  "code_blocks_by_lang": {
    "php": 59,
    "sql": 9,
    "go": 112,
    "bash": 2,
    "csharp": 7,
    "plain": 22,
    "typescript": 40,
    "json": 1,
    "rust": 4,
    "yaml": 1,
    "text": 1
  },
  "has_sql_ddl": true,
  "has_json_schema": true,
  "has_ts_enums": true,
  "has_yaml_openapi": true,
  "has_typed_lang_contract": true,
  "has_ci_workflow": false,
  "has_mermaid": false,
  "links_total": 125,
  "links_broken": 1,
  "todo_density": 0,
  "waffle_per_kchar": 0.22,
  "child_modules": 4
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
- **Evidence:** links_total=125, links_broken=1
- **Proposed correction:** Run linter-scripts/check-spec-cross-links.py and fix every reported link.
