# Audit v2 — `spec/02-coding-guidelines/03-golang`

**Date:** 2026-04-25  
**Auditor:** Deterministic rubric (no AI)  
**Implementability Score:** **68/100 (C)**  
**Blast radius:** 4/10

> Deterministic score 68/100 (C) for spec/02-coding-guidelines/03-golang.


**Score justification:** Deterministic rubric: contracts=0/3, ac=2, gwt=0, broken_links=0, waffle/kchar=0.19.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 40 | 14.0 |
| Completeness | 20% | 60 | 12.0 |
| Alignment | 15% | 100 | 15.0 |
| Consistency | 10% | 100 | 10.0 |
| Clarity | 10% | 100 | 10.0 |
| Testability | 7% | 52 | 3.6 |
| Maintainability | 3% | 100 | 3.0 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 11,
  "mmd_files": 0,
  "overview_chars": 1170,
  "ac_chars": 632,
  "ac_count": 2,
  "gwt_block_count": 0,
  "consistency_report": true,
  "code_blocks_total": 45,
  "code_blocks_by_lang": {
    "go": 43,
    "plain": 2
  },
  "has_sql_ddl": false,
  "has_json_schema": false,
  "has_ts_enums": false,
  "has_yaml_openapi": false,
  "has_mermaid": false,
  "links_total": 19,
  "links_broken": 0,
  "todo_density": 0,
  "waffle_per_kchar": 0.19,
  "child_modules": 2
}
```

## Implementability Blockers

- No inlined contract (SQL DDL / JSON schema / TS enum) in module body

## Code Mapping

**Implemented by:** _(none — pure-doc spec)_
**Expected but missing:** _(none)_
**Orphan code candidates:** _(none)_

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | missing-contract | high | 8/10 | No inlined contract (SQL DDL / JSON schema / TS enum) in module body |
| 2 | untestable | medium | 5/10 | Acceptance criteria present but no Given/When/Then blocks |

### Detail + Proposed Corrections

#### 1. [HIGH] No inlined contract (SQL DDL / JSON schema / TS enum) in module body
- **Category:** missing-contract  |  **Impact:** 8/10
- **Evidence:** code_blocks_by_lang={"go": 43, "plain": 2}
- **Proposed correction:** Inline at least one normative contract block in 00-overview.md or a dedicated contract file.

#### 2. [MEDIUM] Acceptance criteria present but no Given/When/Then blocks
- **Category:** untestable  |  **Impact:** 5/10
- **Evidence:** ac_count=2, gwt_block_count=0
- **Proposed correction:** Rewrite each AC as a Given/When/Then block.
