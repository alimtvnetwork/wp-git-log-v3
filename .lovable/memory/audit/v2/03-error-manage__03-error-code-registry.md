# Audit v2 — `spec/03-error-manage/03-error-code-registry`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview, 2-pass)  
**Implementability Score:** **61/100 (C)**  
**Blast radius:** 8/10

> This module provides a good overview of an error code registry but falls short on implementability due to missing crucial contracts like SQL DDL, JSON schemas, and TypeScript enums. Completeness is also impacted by these omissions, despite a solid core idea.


**Score justification:** The implementability score is capped at 50 because it deals with a database-like registry but lacks SQL DDL. Testability is capped at 20 due to the low AC count (5). Consistency is capped at 70 due to a broken link. Completeness suffers from missing contracts for JSON schemas and TS enums.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 45 | 15.8 |
| Completeness | 20% | 60 | 12.0 |
| Alignment | 15% | 90 | 13.5 |
| Consistency | 10% | 70 | 7.0 |
| Clarity | 10% | 90 | 9.0 |
| Testability | 7% | 20 | 1.4 |
| Maintainability | 3% | 70 | 2.1 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 9,
  "overview_chars": 3690,
  "ac_chars": 2752,
  "ac_count": 5,
  "gwt_block_count": 5,
  "consistency_report": true,
  "code_blocks_total": 19,
  "code_blocks_by_lang": {
    "plain": 6,
    "markdown": 1,
    "go": 3,
    "typescript": 5,
    "powershell": 1,
    "bash": 2,
    "json": 1
  },
  "has_sql_ddl": false,
  "has_json_schema": true,
  "has_ts_enums": true,
  "has_yaml_openapi": false,
  "links_total": 22,
  "links_broken": 1,
  "todo_density": 0,
  "waffle_per_kchar": 0.05,
  "child_modules": 3
}
```

## Implementability Blockers

- No SQL DDL for error code registry (even though it's a database-like structure)
- Missing explicit contracts for JSON schemas (though 'has_json_schema: true' is noted, the schemas themselves aren't fully inlined or thoroughly described in the provided overview/AC)
- Missing explicit contracts for TypeScript enums (similar to JSON schemas, 'has_ts_enums: true' is noted but contracts are not detailed)

## Code Mapping

**Implemented by:** `linter-scripts/check-spec-cross-links.py`, `linter-scripts/check-tree-health.cjs`, `linter-scripts/run.sh`
**Expected but missing:** `error-codes-master.json`, `07-schemas`, `08-linter-scripts`, `09-templates`
**Orphan code candidates:** _(none)_

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | broken-link | medium | 5/10 | One broken link detected in the reference section of the overview. |
| 2 | missing-contract | high | 8/10 | The spec references JSON schemas and TypeScript enums but does not fully inline their contracts. |
| 3 | missing-contract | high | 10/10 | The error code registry describes ranges and codes but lacks SQL DDL for a potential database implementation. |
| 4 | untestable | medium | 5/10 | The acceptance criteria count is very low, limiting the testability of the module. |
| 5 | missing-spec | low | 3/10 | The `error-codes-master.json`, `07-schemas`, `08-linter-scripts`, and `09-templates` are listed as part of the module but are not provided in the code index. |

### Detail + Proposed Corrections

#### 1. [MEDIUM] One broken link detected in the reference section of the overview.
- **Category:** broken-link  |  **Impact:** 5/10
- **Evidence:** links_broken: 1
- **Proposed correction:** Fix the broken link in `00-overview.md` to ensure all cross-references are valid.

#### 2. [HIGH] The spec references JSON schemas and TypeScript enums but does not fully inline their contracts.
- **Category:** missing-contract  |  **Impact:** 8/10
- **Evidence:** has_json_schema: true, has_ts_enums: true, but no inlined schemas/enums in the provided content.
- **Proposed correction:** Inline the full JSON schema definitions and TypeScript enum contracts directly into the spec, or provide clear external references and examples of their structure.

#### 3. [HIGH] The error code registry describes ranges and codes but lacks SQL DDL for a potential database implementation.
- **Category:** missing-contract  |  **Impact:** 10/10
- **Evidence:** has_sql_ddl: false, despite describing a 'registry' of error codes and ranges.
- **Proposed correction:** Provide the complete SQL DDL for the error code registry to enable a mediocre AI to implement the database structure directly from the spec.

#### 4. [MEDIUM] The acceptance criteria count is very low, limiting the testability of the module.
- **Category:** untestable  |  **Impact:** 5/10
- **Evidence:** ac_count: 5
- **Proposed correction:** Add more detailed and specific acceptance criteria to cover a broader range of functionalities and edge cases described in the module.

#### 5. [LOW] The `error-codes-master.json`, `07-schemas`, `08-linter-scripts`, and `09-templates` are listed as part of the module but are not provided in the code index.
- **Category:** missing-spec  |  **Impact:** 3/10
- **Evidence:** Listed in 'Document Inventory' and 'Subfolders' but not in 'File Inventory' or child modules.
- **Proposed correction:** Ensure all files and folders referenced in the spec are included in the codebase index or clearly delineate what is part of the spec versus what is an external artifact.
