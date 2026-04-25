# Audit v2 — `spec/03-error-manage`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview, 2-pass)  
**Implementability Score:** **74/100 (C)**  
**Blast radius:** 8/10

> This spec is well-structured and clear with strong internal consistency and maintainability. However, its implementability is hindered by a lack of concrete contracts (DDL, formal schemas, enum definitions), and its alignment with the provided code index is extremely poor, as the code consists only of linter scripts and scaffolding, suggesting the described system is largely unimplemented.


**Score justification:** The implementability score is capped at 70 because while the spec describes a database, it lacks concrete DDL. The alignment score is very low because the spec describes error management, but the provided code index contains only linter scripts and scaffolding, indicating a significant misalignment between the spec and the current codebase.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 70 | 24.5 |
| Completeness | 20% | 90 | 18.0 |
| Alignment | 15% | 20 | 3.0 |
| Consistency | 10% | 100 | 10.0 |
| Clarity | 10% | 100 | 10.0 |
| Testability | 7% | 80 | 5.6 |
| Maintainability | 3% | 100 | 3.0 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 5,
  "overview_chars": 5430,
  "ac_chars": 4252,
  "ac_count": 8,
  "gwt_block_count": 7,
  "consistency_report": true,
  "code_blocks_total": 4,
  "code_blocks_by_lang": {
    "json": 1,
    "bash": 1,
    "plain": 2
  },
  "has_sql_ddl": false,
  "has_json_schema": true,
  "has_ts_enums": false,
  "has_yaml_openapi": false,
  "links_total": 14,
  "links_broken": 0,
  "todo_density": 0,
  "waffle_per_kchar": 0.0,
  "child_modules": 3
}
```

## Implementability Blockers

- No SQL DDL for database-related error handling.
- Specific error code structures for PHP and Go are described but not fully defined with inline types or schemas for direct implementation.
- The Universal Response Envelope is defined structurally, but a formal schema (e.g., JSON Schema) is needed for automated validation and implementation.
- Missing concrete contract for 'AppErrType' enums in the apperror package.

## Code Mapping

**Implemented by:** _(none — pure-doc spec)_
**Expected but missing:** `error handling logic in Go backend`, `PHP error handling`, `frontend error modal implementation`, `error code registry (e.g., JSON or database table)`, `apperror package in Go`
**Orphan code candidates:** `linter-scripts/audit-spec-vs-code-v2.py`, `linter-scripts/audit-spec-vs-code.py`, `linter-scripts/check-axios-version.sh`, `linter-scripts/check-forbidden-spec-paths.sh`, `linter-scripts/check-forbidden-strings.py`, `linter-scripts/check-memory-mirror-drift.py`, `linter-scripts/check-readme-canonicals.py`, `linter-scripts/check-readme-install-section.py`, `linter-scripts/check-root-readme.py`, `linter-scripts/check-spec-cross-links.py`, `linter-scripts/check-spec-folder-refs.py`, `linter-scripts/check-tree-health.cjs`, `linter-scripts/fill-missing-acceptance-criteria.cjs`, `linter-scripts/fill-missing-changelogs.cjs`, `linter-scripts/fill-missing-consistency-reports.cjs`, `linter-scripts/forbidden-strings.toml`, `linter-scripts/generate-dashboard-data.cjs`, `linter-scripts/generate-gwt-acceptance.py`, `linter-scripts/generate-spec-index.cjs`, `linter-scripts/run.ps1`, `linter-scripts/run.sh`, `linter-scripts/suggest-spec-cross-link-fixes.py`, `linter-scripts/validate-guidelines.go`, `linter-scripts/validate-guidelines.py`, `.github/workflows/spec-health.yml`, `src/`

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | missing-contract | critical | 8/10 | Lack of concrete DDL for any database schemas referenced in error management (e.g., error code registry). |
| 2 | missing-contract | high | 7/10 | Specific error code formats for PHP and Go are described (e.g., regex for PHP, integer ranges for Go), but no formal enums or type definitions are inlined. |
| 3 | missing-contract | high | 7/10 | The Universal Response Envelope is defined structurally with keys, but a formal, machine-readable schema (e.g., JSON Schema) is not inlined. |
| 4 | missing-contract | medium | 5/10 | The `AppErrType` enums for the Go backend 'apperror' package are referenced but not fully defined within the spec. |
| 5 | drift | critical | 10/10 | The spec describes a comprehensive error management system, but the codebase index consists solely of linter scripts and scaffolding, indicating a complete absence of the described implementation. |

### Detail + Proposed Corrections

#### 1. [CRITICAL] Lack of concrete DDL for any database schemas referenced in error management (e.g., error code registry).
- **Category:** missing-contract  |  **Impact:** 8/10
- **Evidence:** has_sql_ddl=false
- **Proposed correction:** Inline the SQL DDL for all database artifacts related to error management, especially the error code registry.

#### 2. [HIGH] Specific error code formats for PHP and Go are described (e.g., regex for PHP, integer ranges for Go), but no formal enums or type definitions are inlined.
- **Category:** missing-contract  |  **Impact:** 7/10
- **Evidence:** AC-03, AC-04 specify formats but no direct contract.
- **Proposed correction:** Inline formal definitions (e.g., TypeScript enums for the range, or a grammar for the regex) for error codes.

#### 3. [HIGH] The Universal Response Envelope is defined structurally with keys, but a formal, machine-readable schema (e.g., JSON Schema) is not inlined.
- **Category:** missing-contract  |  **Impact:** 7/10
- **Evidence:** AC-01 describes the structure but not a formal schema for validation.
- **Proposed correction:** Inline a complete JSON Schema definition for the Universal Response Envelope.

#### 4. [MEDIUM] The `AppErrType` enums for the Go backend 'apperror' package are referenced but not fully defined within the spec.
- **Category:** missing-contract  |  **Impact:** 5/10
- **Evidence:** AC-07 mentions 'specialized domain enums (AppErrType)' but does not inline their definitions.
- **Proposed correction:** Inline the complete definitions of `AppErrType` and associated enums within the spec.

#### 5. [CRITICAL] The spec describes a comprehensive error management system, but the codebase index consists solely of linter scripts and scaffolding, indicating a complete absence of the described implementation.
- **Category:** drift  |  **Impact:** 10/10
- **Evidence:** Codebase index lists only linter scripts (`linter-scripts/`) and generic `src/` (scaffolding).
- **Proposed correction:** Either provide the actual codebase that implements this error management spec or downgrade the 'AI Confidence' to 'Draft' and revise the spec to reflect the current state of the codebase.
