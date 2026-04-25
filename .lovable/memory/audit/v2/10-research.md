# Audit v2 — `spec/10-research`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview, 2-pass)  
**Implementability Score:** **64/100 (C)**  
**Blast radius:** 5/10

> This module serves as a placeholder for research documents but currently contains no actual research. Its implementability is severely limited because there's nothing to implement.


**Score justification:** The implementability is low because it's a documentation module with no code to implement. Completeness is capped at 60 because it's an empty module. Testability is 70 due to AC-01 to AC-05, and AC-RES-000 which have explicit steps.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 30 | 10.5 |
| Completeness | 20% | 60 | 12.0 |
| Alignment | 15% | 90 | 13.5 |
| Consistency | 10% | 100 | 10.0 |
| Clarity | 10% | 100 | 10.0 |
| Testability | 7% | 70 | 4.9 |
| Maintainability | 3% | 100 | 3.0 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 4,
  "overview_chars": 2199,
  "ac_chars": 2522,
  "ac_count": 5,
  "gwt_block_count": 5,
  "consistency_report": true,
  "code_blocks_total": 2,
  "code_blocks_by_lang": {
    "bash": 2
  },
  "has_sql_ddl": false,
  "has_json_schema": false,
  "has_ts_enums": false,
  "has_yaml_openapi": false,
  "links_total": 9,
  "links_broken": 0,
  "todo_density": 0,
  "waffle_per_kchar": 0.0,
  "child_modules": 0
}
```

## Implementability Blockers

- This spec module defines a folder for research documents, but it does not specify any actual research content or provide contracts for what that content should look like.

## Code Mapping

**Implemented by:** _(none — pure-doc spec)_
**Expected but missing:** _(none)_
**Orphan code candidates:** _(none)_

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | missing-contract | medium | 5/10 | The spec states 'No research documents added yet.', indicating a lack of actual content. |
| 2 | missing-contract | medium | 5/10 | The overview defines what belongs here but doesn't provide any concrete schema or structure for a 'research document'. |

### Detail + Proposed Corrections

#### 1. [MEDIUM] The spec states 'No research documents added yet.', indicating a lack of actual content.
- **Category:** missing-contract  |  **Impact:** 5/10
- **Evidence:** Overview section 'Contents' explicitly states 'No research documents added yet.'
- **Proposed correction:** Add at least one example research document with a clear structure and purpose in this module to demonstrate expected content.

#### 2. [MEDIUM] The overview defines what belongs here but doesn't provide any concrete schema or structure for a 'research document'.
- **Category:** missing-contract  |  **Impact:** 5/10
- **Evidence:** Table 'What Belongs Here' lists content types but lacks structural guidance. AC-RES-000 only mentions front-matter, dated filenames, source links, and 'Decision:' or 'Outcome:' sections, which are minimal.
- **Proposed correction:** Provide a template or a detailed schema (e.g., using a JSON Schema or a Markdown structure example) for a typical research document, including expected sections and their markdown formatting.
