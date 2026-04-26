# Audit v2 — `spec/02-coding-guidelines/11-security/01-axios-version-control`

**Date:** 2026-04-25  
**Auditor:** Deterministic rubric (no AI)  
**Implementability Score:** **71/100 (C)**  
**Blast radius:** 2/10

> Deterministic score 71/100 (C) for spec/02-coding-guidelines/11-security/01-axios-version-control.


**Score justification:** Deterministic rubric: contracts=1/3, ac=0, gwt=0, broken_links=0, waffle/kchar=0.4. Gates active: 0.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 65 | 22.8 |
| Completeness | 20% | 50 | 10.0 |
| Alignment | 15% | 100 | 15.0 |
| Consistency | 10% | 100 | 10.0 |
| Clarity | 10% | 100 | 10.0 |
| Testability | 7% | 10 | 0.7 |
| Maintainability | 3% | 100 | 3.0 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 4,
  "mmd_files": 0,
  "overview_chars": 3375,
  "ac_chars": 0,
  "ac_count": 0,
  "gwt_block_count": 0,
  "consistency_report": true,
  "code_blocks_total": 9,
  "code_blocks_by_lang": {
    "json": 4,
    "plain": 3,
    "yaml": 1,
    "bash": 1
  },
  "has_sql_ddl": false,
  "has_json_schema": true,
  "has_ts_enums": false,
  "has_yaml_openapi": true,
  "has_mermaid": false,
  "links_total": 8,
  "links_broken": 0,
  "todo_density": 0,
  "waffle_per_kchar": 0.4,
  "child_modules": 0
}
```

## Implementability Blockers

- No acceptance criteria found

## Code Mapping

**Implemented by:** _(none — pure-doc spec)_
**Expected but missing:** _(none)_
**Orphan code candidates:** _(none)_

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | untestable | high | 8/10 | No acceptance criteria found |

### Detail + Proposed Corrections

#### 1. [HIGH] No acceptance criteria found
- **Category:** untestable  |  **Impact:** 8/10
- **Evidence:** ac_count=0 in 97-acceptance-criteria.md
- **Proposed correction:** Run linter-scripts/generate-gwt-acceptance.py to scaffold AC blocks.
