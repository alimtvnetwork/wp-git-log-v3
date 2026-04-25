# Audit v2 — `spec/02-coding-guidelines/04-php/07-php-standards-reference`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview, 2-pass)  
**Implementability Score:** **59/100 (D)**  
**Blast radius:** 7/10

> This spec provides a comprehensive set of PHP coding guidelines with clear acceptance criteria. However, the lack of actual PHP code and critical contract definitions (like SQL DDL and enum implementations) severely hinders its implementability by an AI.


**Score justification:** Implementability is capped at 50% because the spec describes a database wrapper but lacks crucial SQL DDL. Alignment is low because the spec describes PHP coding guidelines, but the provided code index contains only linter scripts and no PHP code that would implement these guidelines. Consistency is capped at 70% due to a broken link. Testability scores 70% as there are 11 ACs, but the lack of actual code implementation makes full automated testing impossible.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 45 | 15.8 |
| Completeness | 20% | 85 | 17.0 |
| Alignment | 15% | 20 | 3.0 |
| Consistency | 10% | 70 | 7.0 |
| Clarity | 10% | 90 | 9.0 |
| Testability | 7% | 70 | 4.9 |
| Maintainability | 3% | 80 | 2.4 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 8,
  "overview_chars": 2038,
  "ac_chars": 6427,
  "ac_count": 11,
  "gwt_block_count": 10,
  "consistency_report": true,
  "code_blocks_total": 26,
  "code_blocks_by_lang": {
    "php": 22,
    "json": 1,
    "plain": 1,
    "typescript": 1,
    "go": 1
  },
  "has_sql_ddl": false,
  "has_json_schema": true,
  "has_ts_enums": true,
  "has_yaml_openapi": false,
  "links_total": 31,
  "links_broken": 1,
  "todo_density": 0,
  "waffle_per_kchar": 0.12,
  "child_modules": 0
}
```

## Implementability Blockers

- No SQL DDL for the database wrapper is provided, making it impossible to implement the database-related sections without human inference.
- PHP enums and class definitions implied by ACs (e.g., `HookType`, `HttpMethodType`, `DbResult`) are not inlined.
- The full definitions of `DbResult`, `DbResultSet`, and `DbExecResult` are not provided, only their methods.

## Code Mapping

**Implemented by:** _(none — pure-doc spec)_
**Expected but missing:** `PHP codebase demonstrating the coding standards`, `Implementations of `HookType`, `HttpMethodType`, `CapabilityType`, `ErrorType` enums`, `Implementations of `ErrorChecker` and `FileLogger``, `Implementations of `DbResult`, `DbResultSet`, `DbExecResult``, `Implementation of `PathHelper``
**Orphan code candidates:** `linter-scripts/audit-spec-vs-code-v2.py`, `linter-scripts/audit-spec-vs-code.py`, `linter-scripts/check-axios-version.sh`, `linter-scripts/check-forbidden-spec-paths.sh`, `linter-scripts/check-forbidden-strings.py`, `linter-scripts/check-memory-mirror-drift.py`, `linter-scripts/check-readme-canonicals.py`, `linter-scripts/check-readme-install-section.py`, `linter-scripts/check-root-readme.py`, `linter-scripts/check-spec-cross-links.py`, `linter-scripts/check-spec-folder-refs.py`, `linter-scripts/check-tree-health.cjs`, `linter-scripts/fill-missing-acceptance-criteria.cjs`, `linter-scripts/fill-missing-changelogs.cjs`, `linter-scripts/fill-missing-consistency-reports.cjs`, `linter-scripts/forbidden-strings.toml`, `linter-scripts/generate-dashboard-data.cjs`, `linter-scripts/generate-gwt-acceptance.py`, `linter-scripts/generate-spec-index.cjs`, `linter-scripts/run.ps1`, `linter-scripts/run.sh`, `linter-scripts/suggest-spec-cross-link-fixes.py`, `linter-scripts/validate-guidelines.go`, `linter-scripts/validate-guidelines.py`, `.github/workflows/spec-health.yml`, `src/`

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | missing-contract | critical | 8/10 | SQL DDL is missing for the described database wrapper. |
| 2 | missing-contract | high | 7/10 | PHP enum and class definitions are not inlined. |
| 3 | broken-link | medium | 3/10 | One broken link found within the spec. |
| 4 | missing-spec | critical | 10/10 | The spec describes PHP coding guidelines, but no actual PHP code implementation is provided in the index. |

### Detail + Proposed Corrections

#### 1. [CRITICAL] SQL DDL is missing for the described database wrapper.
- **Category:** missing-contract  |  **Impact:** 8/10
- **Evidence:** has_sql_ddl=false, and '05-forbidden-and-database.md' mentions a database wrapper.
- **Proposed correction:** Add complete SQL DDL for the database wrapper described in '05-forbidden-and-database.md' to the spec.

#### 2. [HIGH] PHP enum and class definitions are not inlined.
- **Category:** missing-contract  |  **Impact:** 7/10
- **Evidence:** ACs refer to enums like `HookType` and `HttpMethodType`, and classes like `DbResult`, but their definitions are not provided in the spec.
- **Proposed correction:** Inline the full PHP definitions for all mentioned enums and database wrapper classes (e.g., `HookType.php`, `DbResult` with all methods) directly into the spec.

#### 3. [MEDIUM] One broken link found within the spec.
- **Category:** broken-link  |  **Impact:** 3/10
- **Evidence:** links_broken = 1
- **Proposed correction:** Locate and correct the broken link in the spec.

#### 4. [CRITICAL] The spec describes PHP coding guidelines, but no actual PHP code implementation is provided in the index.
- **Category:** missing-spec  |  **Impact:** 10/10
- **Evidence:** The code index contains only linter scripts and no PHP source code files.
- **Proposed correction:** Provide a representative PHP codebase that demonstrates compliance with these coding guidelines, or clarify that this spec is purely theoretical and not tied to a specific implementation in this repository.
