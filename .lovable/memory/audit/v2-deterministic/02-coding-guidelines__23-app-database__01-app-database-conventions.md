# Audit v2 — `spec/02-coding-guidelines/23-app-database/01-app-database-conventions`

**Date:** 2026-04-25  
**Auditor:** Deterministic rubric (no AI)  
**Implementability Score:** **57/100 (D)**  
**Blast radius:** 0/10

> Deterministic score 57/100 (D) for spec/02-coding-guidelines/23-app-database/01-app-database-conventions.


**Score justification:** Deterministic rubric: contracts=0/3, ac=0, gwt=2, broken_links=0, waffle/kchar=0.0. Gates active: 0.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 30 | 10.5 |
| Completeness | 20% | 40 | 8.0 |
| Alignment | 15% | 100 | 15.0 |
| Consistency | 10% | 100 | 10.0 |
| Clarity | 10% | 100 | 10.0 |
| Testability | 7% | 10 | 0.7 |
| Maintainability | 3% | 100 | 3.0 |

## Deterministic Metrics (pre-AI)

```json
{
  "kind": "",
  "md_files": 4,
  "mmd_files": 0,
  "overview_chars": 1352,
  "ac_chars": 691,
  "ac_count": 0,
  "gwt_block_count": 2,
  "consistency_report": true,
  "code_blocks_total": 1,
  "code_blocks_by_lang": {
    "text": 1
  },
  "has_sql_ddl": false,
  "has_json_schema": false,
  "has_ts_enums": false,
  "has_yaml_openapi": false,
  "has_typed_lang_contract": false,
  "has_ci_workflow": false,
  "has_normative_contract": false,
  "has_mermaid": false,
  "links_total": 0,
  "links_broken": 0,
  "todo_density": 0,
  "waffle_per_kchar": 0.0,
  "child_modules": 0
}
```

## Implementability Blockers

- No acceptance criteria found
- No inlined contract (SQL DDL / JSON schema / TS enum / OpenAPI / typed-language reference / CI workflow) in module body

## Code Mapping

**Implemented by:** _(none — pure-doc spec)_
**Expected but missing:** _(none)_
**Orphan code candidates:** _(none)_

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | missing-contract | high | 8/10 | No inlined contract (SQL DDL / JSON schema / TS enum / OpenAPI / typed-language reference / CI workflow) in module body |
| 2 | untestable | high | 8/10 | No acceptance criteria found |

### Detail + Proposed Corrections

#### 1. [HIGH] No inlined contract (SQL DDL / JSON schema / TS enum / OpenAPI / typed-language reference / CI workflow) in module body
- **Category:** missing-contract  |  **Impact:** 8/10
- **Evidence:** code_blocks_by_lang={"text": 1}
- **Proposed correction:** Inline at least one normative contract block in 00-overview.md or a dedicated contract file.

#### 2. [HIGH] No acceptance criteria found
- **Category:** untestable  |  **Impact:** 8/10
- **Evidence:** ac_count=0 in 97-acceptance-criteria.md
- **Proposed correction:** Run linter-scripts/generate-gwt-acceptance.py to scaffold AC blocks.
