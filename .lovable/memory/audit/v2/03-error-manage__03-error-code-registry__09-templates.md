# Audit v2 — `spec/03-error-manage/03-error-code-registry/09-templates`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview, 2-pass)  
**Implementability Score:** **55/100 (D)**  
**Blast radius:** 8/10

> This module provides good guidance for error code templating and clear acceptance criteria. However, the lack of formal schemas, DDL, or comprehensive API definitions significantly hinders AI implementability.


**Score justification:** Implementability is low because while the acceptance criteria provide excellent examples, critical schemas for the error codes themselves (like an OpenAPI or JSON schema) are missing. Clarity is high due to the low waffle per kchar. Consistency is capped at 70 due to a broken link. Testability is good but not perfect (7 ac_count, but not all AC are GWT). Alignment is 0 as this is a pure spec and does not map to any code.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 40 | 14.0 |
| Completeness | 20% | 80 | 16.0 |
| Alignment | 15% | 0 | 0.0 |
| Consistency | 10% | 70 | 7.0 |
| Clarity | 10% | 100 | 10.0 |
| Testability | 7% | 70 | 4.9 |
| Maintainability | 3% | 100 | 3.0 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 4,
  "overview_chars": 697,
  "ac_chars": 4035,
  "ac_count": 8,
  "gwt_block_count": 7,
  "consistency_report": true,
  "code_blocks_total": 2,
  "code_blocks_by_lang": {
    "go": 1,
    "typescript": 1
  },
  "has_sql_ddl": false,
  "has_json_schema": false,
  "has_ts_enums": true,
  "has_yaml_openapi": false,
  "links_total": 3,
  "links_broken": 1,
  "todo_density": 0,
  "waffle_per_kchar": 0.0,
  "child_modules": 0
}
```

## Implementability Blockers

- Missing formal schema (e.g., JSON schema, OpenAPI) for error code structure and registry.
- No DDL for database interaction, if any is implied for registry storage (though not explicitly stated).
- Typescript enums being present is good, but full API definitions are missing.

## Code Mapping

**Implemented by:** _(none — pure-doc spec)_
**Expected but missing:** _(none)_
**Orphan code candidates:** _(none)_

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | missing-contract | critical | 9/10 | No formal schema or DDL for the error code registry. |
| 2 | broken-link | medium | 5/10 | Broken link in 'Cross-References' section. |
| 3 | missing-contract | high | 7/10 | Only Typescript enums are present, but full API definitions are missing. |

### Detail + Proposed Corrections

#### 1. [CRITICAL] No formal schema or DDL for the error code registry.
- **Category:** missing-contract  |  **Impact:** 9/10
- **Evidence:** The spec describes error code structure and mappings but provides no machine-readable contract (e.g., JSON schema, OpenAPI, DDL) that an AI could directly use to implement the registry or its storage.
- **Proposed correction:** Add a JSON schema or OpenAPI definition for the error code registry entries. If database storage is implied, include the SQL DDL.

#### 2. [MEDIUM] Broken link in 'Cross-References' section.
- **Category:** broken-link  |  **Impact:** 5/10
- **Evidence:** The link to './98-changelog.md' is broken.
- **Proposed correction:** Correct the broken link in the 'Cross-References' section.

#### 3. [HIGH] Only Typescript enums are present, but full API definitions are missing.
- **Category:** missing-contract  |  **Impact:** 7/10
- **Evidence:** The spec mentions Typescript enums but lacks complete API definitions for interacting with the error code registry, which would be crucial for AI implementation.
- **Proposed correction:** Provide full API definitions (e.g., using OpenAPI) for creating, reading, updating, and deleting error codes in the registry.
