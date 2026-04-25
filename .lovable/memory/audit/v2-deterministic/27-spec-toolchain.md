# Audit v2 — `spec/27-spec-toolchain`

**Date:** 2026-04-25  
**Auditor:** Deterministic rubric (no AI)  
**Implementability Score:** **74/100 (C)**  
**Blast radius:** 0/10

> Deterministic score 74/100 (C) for spec/27-spec-toolchain.


**Score justification:** Deterministic rubric: contracts=0/3, ac=10, gwt=10, broken_links=0, waffle/kchar=0.12.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 40 | 14.0 |
| Completeness | 20% | 75 | 15.0 |
| Alignment | 15% | 100 | 15.0 |
| Consistency | 10% | 100 | 10.0 |
| Clarity | 10% | 100 | 10.0 |
| Testability | 7% | 100 | 7.0 |
| Maintainability | 3% | 90 | 2.7 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 34,
  "mmd_files": 0,
  "overview_chars": 8185,
  "ac_chars": 3934,
  "ac_count": 10,
  "gwt_block_count": 10,
  "consistency_report": true,
  "code_blocks_total": 30,
  "code_blocks_by_lang": {
    "bash": 24,
    "powershell": 1,
    "toml": 2,
    "plain": 1,
    "ini": 1,
    "markdown": 1
  },
  "has_sql_ddl": false,
  "has_json_schema": false,
  "has_ts_enums": false,
  "has_yaml_openapi": false,
  "has_mermaid": false,
  "links_total": 112,
  "links_broken": 0,
  "todo_density": 3,
  "waffle_per_kchar": 0.12,
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
| 1 | drift | low | 3/10 | 3 TODO/TBD/FIXME marker(s) in module body |
| 2 | missing-contract | high | 8/10 | No inlined contract (SQL DDL / JSON schema / TS enum) in module body |

### Detail + Proposed Corrections

#### 1. [LOW] 3 TODO/TBD/FIXME marker(s) in module body
- **Category:** drift  |  **Impact:** 3/10
- **Evidence:** todo_density=3
- **Proposed correction:** Resolve or convert markers to tracked acceptance criteria.

#### 2. [HIGH] No inlined contract (SQL DDL / JSON schema / TS enum) in module body
- **Category:** missing-contract  |  **Impact:** 8/10
- **Evidence:** code_blocks_by_lang={"bash": 24, "ini": 1, "markdown": 1, "plain": 1, "powershell": 1, "toml": 2}
- **Proposed correction:** Inline at least one normative contract block in 00-overview.md or a dedicated contract file.
