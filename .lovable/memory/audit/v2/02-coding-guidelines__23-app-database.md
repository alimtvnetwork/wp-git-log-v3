# Audit v2 — `spec/02-coding-guidelines/23-app-database`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview, 2-pass)  
**Implementability Score:** **63/100 (C)**  
**Blast radius:** 5/10

> This module is a placeholder. It provides guidelines and acceptance criteria for database designs but lacks any actual design content, making it currently unimplementable by an AI. It serves as a good framework but needs to be filled with meaningful specifications.


**Score justification:** The implementability score is low (30) because the `has_sql_ddl` metric is false, meaning no concrete DDL is provided. Completeness is capped due to the explicit 'No content yet' statement in the overview.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 30 | 10.5 |
| Completeness | 20% | 50 | 10.0 |
| Alignment | 15% | 100 | 15.0 |
| Consistency | 10% | 100 | 10.0 |
| Clarity | 10% | 100 | 10.0 |
| Testability | 7% | 70 | 4.9 |
| Maintainability | 3% | 100 | 3.0 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 4,
  "overview_chars": 698,
  "ac_chars": 3619,
  "ac_count": 6,
  "gwt_block_count": 5,
  "consistency_report": true,
  "code_blocks_total": 0,
  "code_blocks_by_lang": {},
  "has_sql_ddl": false,
  "has_json_schema": false,
  "has_ts_enums": false,
  "has_yaml_openapi": false,
  "links_total": 8,
  "links_broken": 0,
  "todo_density": 0,
  "waffle_per_kchar": 0.0,
  "child_modules": 0
}
```

## Implementability Blockers

- No SQL DDL provided for table designs and structures. No concrete examples of query patterns. Enums are mentioned as being stored as VARCHAR or native Postgres ENUM types, but no specific enum definitions are provided.

## Code Mapping

**Implemented by:** _(none — pure-doc spec)_
**Expected but missing:** _(none)_
**Orphan code candidates:** `linter-scripts/audit-spec-vs-code-v2.py`, `linter-scripts/audit-spec-vs-code.py`, `linter-scripts/check-axios-version.sh`, `linter-scripts/check-forbidden-spec-paths.sh`, `linter-scripts/check-forbidden-strings.py`, `linter-scripts/check-memory-mirror-drift.py`, `linter-scripts/check-readme-canonicals.py`, `linter-scripts/check-readme-install-section.py`, `linter-scripts/check-root-readme.py`, `linter-scripts/check-spec-cross-links.py`, `linter-scripts/check-spec-folder-refs.py`, `linter-scripts/check-tree-health.cjs`, `linter-scripts/fill-missing-acceptance-criteria.cjs`, `linter-scripts/fill-missing-changelogs.cjs`, `linter-scripts/fill-missing-consistency-reports.cjs`, `linter-scripts/forbidden-strings.toml`, `linter-scripts/generate-dashboard-data.cjs`, `linter-scripts/generate-gwt-acceptance.py`, `linter-scripts/generate-spec-index.cjs`, `linter-scripts/run.ps1`, `linter-scripts/run.sh`, `linter-scripts/suggest-spec-cross-link-fixes.py`, `linter-scripts/validate-guidelines.go`, `linter-scripts/validate-guidelines.py`, `.github/workflows/spec-health.yml`, `src/`

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | missing-contract | critical | 8/10 | No SQL DDL is provided for any database tables or structures. |
| 2 | missing-spec | high | 7/10 | The 'Contents' section explicitly states 'No content yet', indicating significant missing information regarding actual database designs. |
| 3 | missing-contract | medium | 6/10 | While general enum guidance is given, no specific enum definitions (either as VARCHAR or native Postgres ENUMs) are provided. |

### Detail + Proposed Corrections

#### 1. [CRITICAL] No SQL DDL is provided for any database tables or structures.
- **Category:** missing-contract  |  **Impact:** 8/10
- **Evidence:** has_sql_ddl=false on a database spec
- **Proposed correction:** Add concrete SQL DDL statements for all documented table designs and structures, as well as example query patterns. This should include table creation, column definitions, constraints, and index definitions, and enum definitions if applicable.

#### 2. [HIGH] The 'Contents' section explicitly states 'No content yet', indicating significant missing information regarding actual database designs.
- **Category:** missing-spec  |  **Impact:** 7/10
- **Evidence:** Overview section: "_No content yet. Add database design documents as numbered files within this folder._"
- **Proposed correction:** Populate the module with actual database design documents, including detailed specifications for data models, table designs, and query patterns.

#### 3. [MEDIUM] While general enum guidance is given, no specific enum definitions (either as VARCHAR or native Postgres ENUMs) are provided.
- **Category:** missing-contract  |  **Impact:** 6/10
- **Evidence:** AC-05 Inlined Contracts: "Enums: Stored as VARCHAR with application-level validation or native Postgres ENUM types as specified in the conventions document."
- **Proposed correction:** Provide concrete examples or definitions of application-specific enums, specifying whether they should be VARCHARs or native Postgres ENUM types.
