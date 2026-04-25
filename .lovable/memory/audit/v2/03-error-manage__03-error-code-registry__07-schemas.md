# Audit v2 — `spec/03-error-manage/03-error-code-registry/07-schemas`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview, 2-pass)  
**Implementability Score:** **68/100 (C)**  
**Blast radius:** 7/10

> This module provides a good high-level overview and acceptance criteria for error code schemas, but critically lacks the actual JSON schema definitions. Providing the schemas in-line is essential for AI implementability.


**Score justification:** The implementability score is low due to the absence of inlined JSON schemas for the error codes. Consistency is capped at 70 due to one broken link. Testability scores 70 due to the presence of 6 ACs, even without a GWT block per AC.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 40 | 14.0 |
| Completeness | 20% | 80 | 16.0 |
| Alignment | 15% | 90 | 13.5 |
| Consistency | 10% | 70 | 7.0 |
| Clarity | 10% | 100 | 10.0 |
| Testability | 7% | 70 | 4.9 |
| Maintainability | 3% | 100 | 3.0 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 3,
  "overview_chars": 635,
  "ac_chars": 3227,
  "ac_count": 6,
  "gwt_block_count": 5,
  "consistency_report": true,
  "code_blocks_total": 0,
  "code_blocks_by_lang": {},
  "has_sql_ddl": false,
  "has_json_schema": false,
  "has_ts_enums": false,
  "has_yaml_openapi": false,
  "links_total": 3,
  "links_broken": 1,
  "todo_density": 0,
  "waffle_per_kchar": 0.21,
  "child_modules": 0
}
```

## Implementability Blockers

- No JSON schema DDL provided for the error code registry entities.
- The 'parameters' object fields are not fully specified (only keys as strings and values as type from [string, number, boolean] is mentioned, but exact structure is missing).

## Code Mapping

**Implemented by:** _(none — pure-doc spec)_
**Expected but missing:** _(none)_
**Orphan code candidates:** _(none)_

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | missing-contract | critical | 8/10 | Absence of inlined JSON schemas for the error code registry. |
| 2 | missing-contract | high | 6/10 | Missing full specification for the 'parameters' object in AC-05. |
| 3 | broken-link | medium | 3/10 | Broken link to 'Module changelog'. |

### Detail + Proposed Corrections

#### 1. [CRITICAL] Absence of inlined JSON schemas for the error code registry.
- **Category:** missing-contract  |  **Impact:** 8/10
- **Evidence:** The spec states 'Purpose: Error code registry JSON schemas.' but no actual schemas are provided.
- **Proposed correction:** Embed the complete JSON schema definitions directly within the spec, either in the overview or in a dedicated section.

#### 2. [HIGH] Missing full specification for the 'parameters' object in AC-05.
- **Category:** missing-contract  |  **Impact:** 6/10
- **Evidence:** AC-05 mentions 'The 'parameters' object must define keys as strings and values must specify a 'type' from [string, number, boolean]' but lacks the full schema structure of this object.
- **Proposed correction:** Provide a complete JSON schema snippet for the 'parameters' object, detailing its structure and constraints.

#### 3. [MEDIUM] Broken link to 'Module changelog'.
- **Category:** broken-link  |  **Impact:** 3/10
- **Evidence:** The link `./98-changelog.md` in the 'Cross-References' section is broken.
- **Proposed correction:** Verify the path and existence of `98-changelog.md` and correct the link.
