# Audit v2 — `spec/12-cicd-pipeline-workflows/03-reusable-ci-guards`

**Date:** 2026-04-25  
**Auditor:** Deterministic rubric (no AI)  
**Implementability Score:** **80/100 (B)**  
**Blast radius:** 2/10

> Deterministic score 80/100 (B) for spec/12-cicd-pipeline-workflows/03-reusable-ci-guards.


**Score justification:** Deterministic rubric: contracts=1/3, ac=5, gwt=5, broken_links=1, waffle/kchar=0.12.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 65 | 22.8 |
| Completeness | 20% | 75 | 15.0 |
| Alignment | 15% | 90 | 13.5 |
| Consistency | 10% | 92 | 9.2 |
| Clarity | 10% | 100 | 10.0 |
| Testability | 7% | 90 | 6.3 |
| Maintainability | 3% | 100 | 3.0 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 14,
  "mmd_files": 0,
  "overview_chars": 5856,
  "ac_chars": 2995,
  "ac_count": 5,
  "gwt_block_count": 5,
  "consistency_report": true,
  "code_blocks_total": 47,
  "code_blocks_by_lang": {
    "plain": 14,
    "bash": 10,
    "go": 1,
    "python": 3,
    "markdown": 1,
    "yaml": 16,
    "json": 2
  },
  "has_sql_ddl": false,
  "has_json_schema": true,
  "has_ts_enums": false,
  "has_yaml_openapi": true,
  "has_mermaid": false,
  "links_total": 53,
  "links_broken": 1,
  "todo_density": 0,
  "waffle_per_kchar": 0.12,
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
- **Evidence:** links_total=53, links_broken=1
- **Proposed correction:** Run linter-scripts/check-spec-cross-links.py and fix every reported link.
