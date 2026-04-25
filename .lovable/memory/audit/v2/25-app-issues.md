# Audit v2 — `spec/25-app-issues`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview, 2-pass)  
**Implementability Score:** **69/100 (C)**  
**Blast radius:** 6/10

> This module is a good organizational document but lacks the specific implementation-level detail required for an AI to independently generate code. It primarily references external scripts and processes without defining their internal contracts.


**Score justification:** The spec lacks explicit contracts like JSON schemas or SQL DDL, significantly reducing implementability. Although there are ACs, the main verification command is external.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 40 | 14.0 |
| Completeness | 20% | 80 | 16.0 |
| Alignment | 15% | 70 | 10.5 |
| Consistency | 10% | 100 | 10.0 |
| Clarity | 10% | 100 | 10.0 |
| Testability | 7% | 80 | 5.6 |
| Maintainability | 3% | 90 | 2.7 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 4,
  "overview_chars": 2015,
  "ac_chars": 2530,
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
  "links_total": 10,
  "links_broken": 0,
  "todo_density": 0,
  "waffle_per_kchar": 0.0,
  "child_modules": 2
}
```

## Implementability Blockers

- No explicit data structures (like JSON schema or SQL DDL) for audit findings.
- Lack of defined interfaces for integration with external linter scripts.

## Code Mapping

**Implemented by:** `linter-scripts/check-spec-cross-links.py`, `linter-scripts/check-tree-health.cjs`, `linter-scripts/run.sh`
**Expected but missing:** _(none)_
**Orphan code candidates:** _(none)_

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | missing-contract | high | 7/10 | The spec describes 'audit issue write-ups' but provides no concrete schema or example of what these write-ups should look like beyond required sections. |
| 2 | missing-contract | medium | 5/10 | The 'Verification command' and 'Validation' sections refer to external scripts without inlining their expected behavior or output contracts. |
| 3 | ambiguity | low | 3/10 | The 'Placement Rule' states that 'General coding principle violations or cross-cutting concerns belong in the core fundamentals range (01–20)' but doesn't provide clear examples of this distinction. |

### Detail + Proposed Corrections

#### 1. [HIGH] The spec describes 'audit issue write-ups' but provides no concrete schema or example of what these write-ups should look like beyond required sections.
- **Category:** missing-contract  |  **Impact:** 7/10
- **Evidence:** AC-AI-000: App issues triage conformance: Overview mentions 'Reproduction / Cause / Fix / Prevention sections' but no structure.
- **Proposed correction:** Add a dedicated section with a JSON schema or example markdown template for 'audit issue write-ups' including expected fields and types.

#### 2. [MEDIUM] The 'Verification command' and 'Validation' sections refer to external scripts without inlining their expected behavior or output contracts.
- **Category:** missing-contract  |  **Impact:** 5/10
- **Evidence:** References `linter-scripts/check-spec-cross-links.py` and `linter-scripts/run.sh` without detailing their internal logic or full output structure.
- **Proposed correction:** For each external script referenced, describe its interface (inputs, outputs, error conditions) in detail or inline critical sections of its code/logic.

#### 3. [LOW] The 'Placement Rule' states that 'General coding principle violations or cross-cutting concerns belong in the core fundamentals range (01–20)' but doesn't provide clear examples of this distinction.
- **Category:** ambiguity  |  **Impact:** 3/10
- **Evidence:** Pure prose description of 'Placement Rule' without explicit examples or decision criteria.
- **Proposed correction:** Provide 2-3 concrete examples of issues that belong here versus those that belong in 'core fundamentals' (01-20) to clarify the boundary.
