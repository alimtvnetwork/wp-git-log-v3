# Audit v2 — `spec/02-coding-guidelines/06-cicd-integration`

**Date:** 2026-04-25  
**Auditor:** Deterministic rubric (no AI)  
**Implementability Score:** **87/100 (A)**  
**Blast radius:** 4/10

> Deterministic score 87/100 (A) for spec/02-coding-guidelines/06-cicd-integration.


**Score justification:** Deterministic rubric: contracts=2/3, ac=7, gwt=0, broken_links=0, waffle/kchar=0.17.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 75 | 26.2 |
| Completeness | 20% | 85 | 17.0 |
| Alignment | 15% | 100 | 15.0 |
| Consistency | 10% | 100 | 10.0 |
| Clarity | 10% | 100 | 10.0 |
| Testability | 7% | 80 | 5.6 |
| Maintainability | 3% | 100 | 3.0 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 13,
  "mmd_files": 0,
  "overview_chars": 3154,
  "ac_chars": 1410,
  "ac_count": 7,
  "gwt_block_count": 0,
  "consistency_report": true,
  "code_blocks_total": 58,
  "code_blocks_by_lang": {
    "json": 2,
    "plain": 10,
    "bash": 27,
    "yaml": 7,
    "python": 1,
    "go": 1,
    "ts": 2,
    "php": 1,
    "toml": 7
  },
  "has_sql_ddl": false,
  "has_json_schema": true,
  "has_ts_enums": true,
  "has_yaml_openapi": true,
  "has_mermaid": false,
  "links_total": 34,
  "links_broken": 0,
  "todo_density": 0,
  "waffle_per_kchar": 0.17,
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
| 1 | untestable | medium | 5/10 | Acceptance criteria present but no Given/When/Then blocks |

### Detail + Proposed Corrections

#### 1. [MEDIUM] Acceptance criteria present but no Given/When/Then blocks
- **Category:** untestable  |  **Impact:** 5/10
- **Evidence:** ac_count=7, gwt_block_count=0
- **Proposed correction:** Rewrite each AC as a Given/When/Then block.
