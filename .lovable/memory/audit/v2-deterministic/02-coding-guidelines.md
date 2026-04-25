# Audit v2 — `spec/02-coding-guidelines`

**Date:** 2026-04-25  
**Auditor:** Deterministic rubric (no AI)  
**Implementability Score:** **82/100 (B)**  
**Blast radius:** 10/10

> Deterministic score 82/100 (B) for spec/02-coding-guidelines.


**Score justification:** Deterministic rubric: contracts=2/3, ac=5, gwt=0, broken_links=1, waffle/kchar=0.1.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 70 | 24.5 |
| Completeness | 20% | 85 | 17.0 |
| Alignment | 15% | 90 | 13.5 |
| Consistency | 10% | 92 | 9.2 |
| Clarity | 10% | 100 | 10.0 |
| Testability | 7% | 70 | 4.9 |
| Maintainability | 3% | 100 | 3.0 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 6,
  "mmd_files": 0,
  "overview_chars": 10822,
  "ac_chars": 3327,
  "ac_count": 5,
  "gwt_block_count": 0,
  "consistency_report": true,
  "code_blocks_total": 32,
  "code_blocks_by_lang": {
    "plain": 2,
    "bash": 1,
    "go": 16,
    "ts": 1,
    "typescript": 11,
    "sql": 1
  },
  "has_sql_ddl": true,
  "has_json_schema": false,
  "has_ts_enums": true,
  "has_yaml_openapi": false,
  "has_mermaid": false,
  "links_total": 31,
  "links_broken": 1,
  "todo_density": 0,
  "waffle_per_kchar": 0.1,
  "child_modules": 16
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
| 2 | untestable | medium | 5/10 | Acceptance criteria present but no Given/When/Then blocks |

### Detail + Proposed Corrections

#### 1. [HIGH] 1 broken cross-spec link(s)
- **Category:** broken-link  |  **Impact:** 7/10
- **Evidence:** links_total=31, links_broken=1
- **Proposed correction:** Run linter-scripts/check-spec-cross-links.py and fix every reported link.

#### 2. [MEDIUM] Acceptance criteria present but no Given/When/Then blocks
- **Category:** untestable  |  **Impact:** 5/10
- **Evidence:** ac_count=5, gwt_block_count=0
- **Proposed correction:** Rewrite each AC as a Given/When/Then block.
