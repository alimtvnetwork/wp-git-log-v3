# Audit v2 — `spec/12-cicd-pipeline-workflows/01-browser-extension-deploy`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview, 2-pass)  
**Implementability Score:** **64/100 (C)**  
**Blast radius:** 5/10

> This module provides a good high-level overview of the CI/CD pipelines. However, the lack of concrete schemas and specific input/output contracts for pipeline steps significantly inhibits AI implementability.


**Score justification:** The implementability is low due to the lack of concrete DDL or schema definitions, requiring significant inference from an AI. Consistency is affected by a broken link.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 40 | 14.0 |
| Completeness | 20% | 80 | 16.0 |
| Alignment | 15% | 60 | 9.0 |
| Consistency | 10% | 70 | 7.0 |
| Clarity | 10% | 90 | 9.0 |
| Testability | 7% | 80 | 5.6 |
| Maintainability | 3% | 100 | 3.0 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 6,
  "overview_chars": 1886,
  "ac_chars": 2711,
  "ac_count": 5,
  "gwt_block_count": 5,
  "consistency_report": true,
  "code_blocks_total": 16,
  "code_blocks_by_lang": {
    "plain": 4,
    "yaml": 7,
    "bash": 5
  },
  "has_sql_ddl": false,
  "has_json_schema": false,
  "has_ts_enums": false,
  "has_yaml_openapi": true,
  "links_total": 10,
  "links_broken": 1,
  "todo_density": 0,
  "waffle_per_kchar": 0.21,
  "child_modules": 0
}
```

## Implementability Blockers

- No concrete DDL or schema for the browser extension, SDK, or modules.
- Pipeline definitions (YAML/Bash) are present but lack specific types for inputs/outputs or environment variables.

## Code Mapping

**Implemented by:** _(none — pure-doc spec)_
**Expected but missing:** _(none)_
**Orphan code candidates:** _(none)_

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | missing-contract | high | 7/10 | Lack of concrete schema definitions for the browser extension, SDK, and modules. |
| 2 | broken-link | medium | 5/10 | There is a broken internal link. |
| 3 | missing-contract | medium | 6/10 | Specific types and expected values for pipeline inputs, outputs, and environment variables are not explicitly defined. |

### Detail + Proposed Corrections

#### 1. [HIGH] Lack of concrete schema definitions for the browser extension, SDK, and modules.
- **Category:** missing-contract  |  **Impact:** 7/10
- **Evidence:** Overview mentions 'Node.js and a package manager (pnpm/npm)', 'SDK must be built before dependent modules', 'all modules must be assembled into a final extension package' but no data structures are defined.
- **Proposed correction:** Add JSON schema or similar contract definitions for the browser extension artifact, SDK, and modules, detailing their structure and expected contents.

#### 2. [MEDIUM] There is a broken internal link.
- **Category:** broken-link  |  **Impact:** 5/10
- **Evidence:** links_broken > 0
- **Proposed correction:** Inspect the `linter-scripts/check-spec-cross-links.py` output and fix the broken link within the spec.

#### 3. [MEDIUM] Specific types and expected values for pipeline inputs, outputs, and environment variables are not explicitly defined.
- **Category:** missing-contract  |  **Impact:** 6/10
- **Evidence:** The YAML/Bash code blocks outline pipeline steps but lack detailed contracts for data flow.
- **Proposed correction:** For each pipeline step, explicitly define inputs, outputs, and environment variables, including their types, constraints, and examples.
