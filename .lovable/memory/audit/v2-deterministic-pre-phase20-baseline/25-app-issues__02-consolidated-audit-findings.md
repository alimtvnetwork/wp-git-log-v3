# Audit v2 — `spec/25-app-issues/02-consolidated-audit-findings`

**Date:** 2026-04-25  
**Auditor:** Deterministic rubric (no AI)  
**Implementability Score:** **59/100 (D)**  
**Blast radius:** 0/10

> Deterministic score 59/100 (D) for spec/25-app-issues/02-consolidated-audit-findings.


**Score justification:** Deterministic rubric: contracts=0/3, ac=5, gwt=5, broken_links=13, waffle/kchar=0.03. Gates active: 0.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 40 | 14.0 |
| Completeness | 20% | 75 | 15.0 |
| Alignment | 15% | 40 | 6.0 |
| Consistency | 10% | 50 | 5.0 |
| Clarity | 10% | 100 | 10.0 |
| Testability | 7% | 90 | 6.3 |
| Maintainability | 3% | 100 | 3.0 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 4,
  "mmd_files": 0,
  "overview_chars": 28968,
  "ac_chars": 2692,
  "ac_count": 5,
  "gwt_block_count": 5,
  "consistency_report": true,
  "code_blocks_total": 23,
  "code_blocks_by_lang": {
    "plain": 21,
    "bash": 2
  },
  "has_sql_ddl": false,
  "has_json_schema": false,
  "has_ts_enums": false,
  "has_yaml_openapi": false,
  "has_mermaid": false,
  "links_total": 32,
  "links_broken": 13,
  "todo_density": 0,
  "waffle_per_kchar": 0.03,
  "child_modules": 0
}
```

## Implementability Blockers

- 13 broken cross-spec link(s)
- No inlined contract (SQL DDL / JSON schema / TS enum) in module body

## Code Mapping

**Implemented by:** _(none — pure-doc spec)_
**Expected but missing:** _(none)_
**Orphan code candidates:** _(none)_

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | broken-link | high | 7/10 | 13 broken cross-spec link(s) |
| 2 | missing-contract | high | 8/10 | No inlined contract (SQL DDL / JSON schema / TS enum) in module body |

### Detail + Proposed Corrections

#### 1. [HIGH] 13 broken cross-spec link(s)
- **Category:** broken-link  |  **Impact:** 7/10
- **Evidence:** links_total=32, links_broken=13
- **Proposed correction:** Run linter-scripts/check-spec-cross-links.py and fix every reported link.

#### 2. [HIGH] No inlined contract (SQL DDL / JSON schema / TS enum) in module body
- **Category:** missing-contract  |  **Impact:** 8/10
- **Evidence:** code_blocks_by_lang={"bash": 2, "plain": 21}
- **Proposed correction:** Inline at least one normative contract block in 00-overview.md or a dedicated contract file.
