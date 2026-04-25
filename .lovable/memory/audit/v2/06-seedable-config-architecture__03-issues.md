# Audit v2 — `spec/06-seedable-config-architecture/03-issues`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview, 2-pass)  
**Implementability Score:** **55/100 (D)**  
**Blast radius:** 6/10

> This module is a placeholder. It provides metadata about itself (ACs for linting its own structure) but completely lacks content regarding the 'issues' it claims to index or track. It is not implementable.


**Score justification:** The spec has a broken link, reducing consistency. AC count is 5, but the ACs revolve around linting other documents and not covering the module's core purpose, severely capping testability. The overview has no actual content about 'issues', thus implementability is low.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 30 | 10.5 |
| Completeness | 20% | 80 | 16.0 |
| Alignment | 15% | 50 | 7.5 |
| Consistency | 10% | 70 | 7.0 |
| Clarity | 10% | 100 | 10.0 |
| Testability | 7% | 20 | 1.4 |
| Maintainability | 3% | 100 | 3.0 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 4,
  "overview_chars": 237,
  "ac_chars": 2652,
  "ac_count": 5,
  "gwt_block_count": 5,
  "consistency_report": true,
  "code_blocks_total": 1,
  "code_blocks_by_lang": {
    "bash": 1
  },
  "has_sql_ddl": false,
  "has_json_schema": false,
  "has_ts_enums": false,
  "has_yaml_openapi": false,
  "links_total": 6,
  "links_broken": 1,
  "todo_density": 0,
  "waffle_per_kchar": 0.0,
  "child_modules": 0
}
```

## Implementability Blockers

- No structured data for 'issues' is provided (e.g., schema, DDL, JSON).
- The core purpose of the 'issues' module is not defined; what is being indexed/tracked?

## Code Mapping

**Implemented by:** _(none — pure-doc spec)_
**Expected but missing:** _(none)_
**Orphan code candidates:** _(none)_

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | broken-link | medium | 5/10 | One broken link detected in the Acceptance Criteria. |
| 2 | missing-contract | critical | 10/10 | No definition or structure for 'issues' exists. |
| 3 | missing-spec | high | 8/10 | The core purpose and content of the 'issues' module is missing. |

### Detail + Proposed Corrections

#### 1. [MEDIUM] One broken link detected in the Acceptance Criteria.
- **Category:** broken-link  |  **Impact:** 5/10
- **Evidence:** links_broken > 0
- **Proposed correction:** Resolve the broken link [Spec authoring guide — acceptance criteria template](../01-spec-authoring-guide/03-required-files.md).

#### 2. [CRITICAL] No definition or structure for 'issues' exists.
- **Category:** missing-contract  |  **Impact:** 10/10
- **Evidence:** The entire module is about 'issues index' but doesn't define what an 'issue' is or how it's structured.
- **Proposed correction:** Add a schema (JSON, DDL, or similar) defining the structure of an 'issue'.

#### 3. [HIGH] The core purpose and content of the 'issues' module is missing.
- **Category:** missing-spec  |  **Impact:** 8/10
- **Evidence:** The overview contains a table with no open issues and is otherwise empty, stating 'No open issues'.
- **Proposed correction:** Flesh out the 00-overview.md with actual content regarding what this 'issues index' module aims to achieve, how issues are defined, and how they interact with the 'seedable config architecture'.
