# Audit v2 — `spec/26-gitlogs-diagrams`

**Date:** 2026-04-25  
**Auditor:** Deterministic rubric (no AI)  
**Implementability Score:** **71/100 (C)**  
**Blast radius:** 0/10

> Deterministic score 71/100 (C) for spec/26-gitlogs-diagrams.


**Score justification:** Deterministic rubric: contracts=0/3, ac=21, gwt=20, broken_links=1, waffle/kchar=0.06. Gates active: 1.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 35 | 12.2 |
| Completeness | 20% | 90 | 18.0 |
| Alignment | 15% | 90 | 13.5 |
| Consistency | 10% | 70 | 7.0 |
| Clarity | 10% | 100 | 10.0 |
| Testability | 7% | 100 | 7.0 |
| Maintainability | 3% | 100 | 3.0 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 4,
  "mmd_files": 6,
  "overview_chars": 4264,
  "ac_chars": 17103,
  "ac_count": 21,
  "gwt_block_count": 20,
  "consistency_report": true,
  "code_blocks_total": 1,
  "code_blocks_by_lang": {
    "text": 1
  },
  "has_sql_ddl": false,
  "has_json_schema": false,
  "has_ts_enums": false,
  "has_yaml_openapi": false,
  "has_mermaid": true,
  "links_total": 18,
  "links_broken": 1,
  "todo_density": 0,
  "waffle_per_kchar": 0.06,
  "child_modules": 0
}
```

## Implementability Blockers

- 1 broken cross-spec link(s)
- No inlined contract (SQL DDL / JSON schema / TS enum) in module body

## Code Mapping

**Implemented by:** _(none — pure-doc spec)_
**Expected but missing:** _(none)_
**Orphan code candidates:** _(none)_

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | broken-link | high | 7/10 | 1 broken cross-spec link(s) |
| 2 | missing-contract | high | 8/10 | No inlined contract (SQL DDL / JSON schema / TS enum) in module body |

### Detail + Proposed Corrections

#### 1. [HIGH] 1 broken cross-spec link(s)
- **Category:** broken-link  |  **Impact:** 7/10
- **Evidence:** links_total=18, links_broken=1
- **Proposed correction:** Run linter-scripts/check-spec-cross-links.py and fix every reported link.

#### 2. [HIGH] No inlined contract (SQL DDL / JSON schema / TS enum) in module body
- **Category:** missing-contract  |  **Impact:** 8/10
- **Evidence:** code_blocks_by_lang={"text": 1}
- **Proposed correction:** Inline at least one normative contract block in 00-overview.md or a dedicated contract file.
