# Audit v2 — `spec/14-update/diagrams`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview, 2-pass)  
**Implementability Score:** **65/100 (C)**  
**Blast radius:** 5/10

> This module provides an index and overview of diagrams but lacks the actual diagram definitions, significantly hindering AI implementability. A broken link further reduces its consistency.


**Score justification:** The implementability is low because the actual diagrams (.mmd files) are not inlined or provided. Consistency is capped at 70 due to a broken link. Testability scores well because there are multiple GWT blocks.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 30 | 10.5 |
| Completeness | 20% | 80 | 16.0 |
| Alignment | 15% | 90 | 13.5 |
| Consistency | 10% | 70 | 7.0 |
| Clarity | 10% | 100 | 10.0 |
| Testability | 7% | 75 | 5.2 |
| Maintainability | 3% | 90 | 2.7 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 4,
  "overview_chars": 1438,
  "ac_chars": 2590,
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
  "links_total": 9,
  "links_broken": 1,
  "todo_density": 0,
  "waffle_per_kchar": 0.0,
  "child_modules": 0
}
```

## Implementability Blockers

- Actual Mermaid diagram code (.mmd files) not inlined or directly provided within the spec.
- No schema or contract provided for an AI to generate the diagrams, making it reliant on external files.

## Code Mapping

**Implemented by:** _(none — pure-doc spec)_
**Expected but missing:** _(none)_
**Orphan code candidates:** _(none)_

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | missing-contract | high | 8/10 | Mermaid diagrams are referenced but not inlined or available within the module itself. |
| 2 | broken-link | medium | 5/10 | A cross-reference link is broken, pointing to a non-existent file. |

### Detail + Proposed Corrections

#### 1. [HIGH] Mermaid diagrams are referenced but not inlined or available within the module itself.
- **Category:** missing-contract  |  **Impact:** 8/10
- **Evidence:** Overview section lists '01-self-update-workflow.mmd' and '02-update-cleanup-workflow.mmd' but their content is not provided.
- **Proposed correction:** Embed the `.mmd` file content directly into the spec using code blocks, or provide a link to a machine-readable content source.

#### 2. [MEDIUM] A cross-reference link is broken, pointing to a non-existent file.
- **Category:** broken-link  |  **Impact:** 5/10
- **Evidence:** Overview section cross-reference: `[../16-update-command-workflow.md](../22-update-command-workflow.md)` (links_broken > 0)
- **Proposed correction:** Correct the broken link `../22-update-command-workflow.md` to point to the correct, existing file.
