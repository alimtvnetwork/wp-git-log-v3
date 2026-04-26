# Audit v2 — `spec/02-coding-guidelines/01-cross-language`

**Date:** 2026-04-25  
**Auditor:** Deterministic rubric (no AI)  
**Implementability Score:** **84/100 (B)**  
**Blast radius:** 10/10

> Deterministic score 84/100 (B) for spec/02-coding-guidelines/01-cross-language.


**Score justification:** Deterministic rubric: contracts=3/3, ac=2, gwt=0, broken_links=1, waffle/kchar=0.14. Gates active: 1.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 95 | 33.2 |
| Completeness | 20% | 70 | 14.0 |
| Alignment | 15% | 90 | 13.5 |
| Consistency | 10% | 70 | 7.0 |
| Clarity | 10% | 100 | 10.0 |
| Testability | 7% | 52 | 3.6 |
| Maintainability | 3% | 100 | 3.0 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 31,
  "mmd_files": 0,
  "overview_chars": 7220,
  "ac_chars": 662,
  "ac_count": 2,
  "gwt_block_count": 0,
  "consistency_report": true,
  "code_blocks_total": 257,
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
    "yaml": 1
  },
  "has_sql_ddl": true,
  "has_json_schema": true,
  "has_ts_enums": true,
  "has_yaml_openapi": true,
  "has_mermaid": false,
  "links_total": 117,
  "links_broken": 1,
  "todo_density": 0,
  "waffle_per_kchar": 0.14,
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
| 2 | untestable | medium | 5/10 | Acceptance criteria present but no Given/When/Then blocks |

### Detail + Proposed Corrections

#### 1. [HIGH] 1 broken cross-spec link(s)
- **Category:** broken-link  |  **Impact:** 7/10
- **Evidence:** links_total=117, links_broken=1
- **Proposed correction:** Run linter-scripts/check-spec-cross-links.py and fix every reported link.

#### 2. [MEDIUM] Acceptance criteria present but no Given/When/Then blocks
- **Category:** untestable  |  **Impact:** 5/10
- **Evidence:** ac_count=2, gwt_block_count=0
- **Proposed correction:** Rewrite each AC as a Given/When/Then block.
