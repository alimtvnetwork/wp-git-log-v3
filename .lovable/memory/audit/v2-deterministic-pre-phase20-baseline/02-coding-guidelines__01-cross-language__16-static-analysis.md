# Audit v2 — `spec/02-coding-guidelines/01-cross-language/16-static-analysis`

**Date:** 2026-04-25  
**Auditor:** Deterministic rubric (no AI)  
**Implementability Score:** **78/100 (B)**  
**Blast radius:** 0/10

> Deterministic score 78/100 (B) for spec/02-coding-guidelines/01-cross-language/16-static-analysis.


**Score justification:** Deterministic rubric: contracts=0/3, ac=28, gwt=20, broken_links=0, waffle/kchar=0.17. Gates active: 0.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 50 | 17.5 |
| Completeness | 20% | 80 | 16.0 |
| Alignment | 15% | 100 | 15.0 |
| Consistency | 10% | 100 | 10.0 |
| Clarity | 10% | 100 | 10.0 |
| Testability | 7% | 100 | 7.0 |
| Maintainability | 3% | 90 | 2.7 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 13,
  "mmd_files": 0,
  "overview_chars": 5988,
  "ac_chars": 21603,
  "ac_count": 28,
  "gwt_block_count": 20,
  "consistency_report": true,
  "code_blocks_total": 28,
  "code_blocks_by_lang": {
    "yaml": 5,
    "bash": 6,
    "xml": 3,
    "neon": 1,
    "ini": 4,
    "toml": 4,
    "rust": 1,
    "js": 1,
    "plain": 1,
    "properties": 1,
    "text": 1
  },
  "has_sql_ddl": false,
  "has_json_schema": false,
  "has_ts_enums": false,
  "has_yaml_openapi": true,
  "has_mermaid": false,
  "links_total": 155,
  "links_broken": 0,
  "todo_density": 2,
  "waffle_per_kchar": 0.17,
  "child_modules": 0
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
| 1 | drift | low | 3/10 | 2 TODO/TBD/FIXME marker(s) in module body |
| 2 | missing-contract | high | 8/10 | No inlined contract (SQL DDL / JSON schema / TS enum) in module body |

### Detail + Proposed Corrections

#### 1. [LOW] 2 TODO/TBD/FIXME marker(s) in module body
- **Category:** drift  |  **Impact:** 3/10
- **Evidence:** todo_density=2
- **Proposed correction:** Resolve or convert markers to tracked acceptance criteria.

#### 2. [HIGH] No inlined contract (SQL DDL / JSON schema / TS enum) in module body
- **Category:** missing-contract  |  **Impact:** 8/10
- **Evidence:** code_blocks_by_lang={"bash": 6, "ini": 4, "js": 1, "neon": 1, "plain": 1, "properties": 1, "rust": 1, "text": 1, "toml": 4, "xml": 3, "yaml": 5}
- **Proposed correction:** Inline at least one normative contract block in 00-overview.md or a dedicated contract file.
