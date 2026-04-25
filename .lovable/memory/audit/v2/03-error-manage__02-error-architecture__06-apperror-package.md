# Audit v2 — `spec/03-error-manage/02-error-architecture/06-apperror-package`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview, 2-pass)  
**Implementability Score:** **56/100 (D)**  
**Blast radius:** 7/10

> This module provides a good high-level overview and acceptance criteria for an AppError package. However, it critically lacks concrete, inlined code contracts for key components like enums and structs, significantly impacting its implementability by an AI.


**Score justification:** The broken link, lack of concrete code examples, and missing type definitions for critical components like `AppErrType` significantly hinder implementability. Additionally, the spec does not align with any found code, suggesting it's purely documentation.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 40 | 14.0 |
| Completeness | 20% | 80 | 16.0 |
| Alignment | 15% | 0 | 0.0 |
| Consistency | 10% | 70 | 7.0 |
| Clarity | 10% | 100 | 10.0 |
| Testability | 7% | 80 | 5.6 |
| Maintainability | 3% | 100 | 3.0 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 4,
  "overview_chars": 840,
  "ac_chars": 4506,
  "ac_count": 8,
  "gwt_block_count": 7,
  "consistency_report": true,
  "code_blocks_total": 0,
  "code_blocks_by_lang": {},
  "has_sql_ddl": false,
  "has_json_schema": false,
  "has_ts_enums": false,
  "has_yaml_openapi": false,
  "links_total": 4,
  "links_broken": 1,
  "todo_density": 0,
  "waffle_per_kchar": 0.0,
  "child_modules": 1
}
```

## Implementability Blockers

- No concrete code examples for implementation.
- No SQL DDL provided for database specifications (if applicable).
- Missing type definition for `AppErrType` enum.
- The referenced files for `AppError` details, `Result` types, and enum patterns are not provided as inlined contracts, requiring external lookups.
- Missing concrete contract for `StackTrace` structure.

## Code Mapping

**Implemented by:** _(none — pure-doc spec)_
**Expected but missing:** `apperror/package`
**Orphan code candidates:** `linter-scripts/audit-spec-vs-code-v2.py`, `linter-scripts/audit-spec-vs-code.py`, `linter-scripts/check-axios-version.sh`, `linter-scripts/check-forbidden-spec-paths.sh`, `linter-scripts/check-forbidden-strings.py`, `linter-scripts/check-memory-mirror-drift.py`, `linter-scripts/check-readme-canonicals.py`, `linter-scripts/check-readme-install-section.py`, `linter-scripts/check-root-readme.py`, `linter-scripts/check-spec-cross-links.py`, `linter-scripts/check-spec-folder-refs.py`, `linter-scripts/check-tree-health.cjs`, `linter-scripts/fill-missing-acceptance-criteria.cjs`, `linter-scripts/fill-missing-changelogs.cjs`, `linter-scripts/fill-missing-consistency-reports.cjs`, `linter-scripts/forbidden-strings.toml`, `linter-scripts/generate-dashboard-data.cjs`, `linter-scripts/generate-gwt-acceptance.py`, `linter-scripts/generate-spec-index.cjs`, `linter-scripts/run.ps1`, `linter-scripts/run.sh`, `linter-scripts/suggest-spec-cross-link-fixes.py`, `linter-scripts/validate-guidelines.go`, `linter-scripts/validate-guidelines.py`, `.github/workflows/spec-health.yml`, `src/`

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | broken-link | high | 5/10 | Broken link in cross-references. |
| 2 | missing-contract | critical | 8/10 | The `AppErrType` enum is referenced but not fully defined within the inlined contracts. |
| 3 | missing-contract | critical | 8/10 | The `StackTrace` structure is referenced but its complete definition is not inlined. |
| 4 | missing-contract | medium | 6/10 | The 'Enum Pattern' cross-reference describes behavior but doesn't provide a concrete contract. |
| 5 | missing-contract | medium | 6/10 | The `AC-06: Result Guard Rule Enforcement` describes a desired behavior but lacks a code-level contract for how this enforcement is achieved. |

### Detail + Proposed Corrections

#### 1. [HIGH] Broken link in cross-references.
- **Category:** broken-link  |  **Impact:** 5/10
- **Evidence:** ./99-consistency-repo
- **Proposed correction:** Correct the broken link to './99-consistency-report.md'.

#### 2. [CRITICAL] The `AppErrType` enum is referenced but not fully defined within the inlined contracts.
- **Category:** missing-contract  |  **Impact:** 8/10
- **Evidence:** AC-03: Domain-Specific Error Code Mapping, 'The error code must follow the E1xxx–E14xxx domain mapping defined in 05-apperrtype-enums.md.' The definition itself is missing.
- **Proposed correction:** Inline the full definition of the `AppErrType` enum, including all possible values and their mappings, or provide a clear reference to where its complete definition can be found.

#### 3. [CRITICAL] The `StackTrace` structure is referenced but its complete definition is not inlined.
- **Category:** missing-contract  |  **Impact:** 8/10
- **Evidence:** AC-01: StackTrace Injection in AppError Constructors, 'The returned object must contain a StackTrace as defined in 01-overview-and-stack.md with accurate file and line information.' The definition is missing.
- **Proposed correction:** Inline the complete definition of the `StackTrace` structure within the `Inlined Contracts` section.

#### 4. [MEDIUM] The 'Enum Pattern' cross-reference describes behavior but doesn't provide a concrete contract.
- **Category:** missing-contract  |  **Impact:** 6/10
- **Evidence:** Inlined Contracts: 'Enum Pattern (from cross-refs) // Using byte-based enums with custom MarshalJSON/UnmarshalJSON.' This is descriptive, not prescriptive.
- **Proposed correction:** Provide a concrete Go code snippet demonstrating the byte-based enum pattern with custom `MarshalJSON`/`UnmarshalJSON` for an example enum.

#### 5. [MEDIUM] The `AC-06: Result Guard Rule Enforcement` describes a desired behavior but lacks a code-level contract for how this enforcement is achieved.
- **Category:** missing-contract  |  **Impact:** 6/10
- **Evidence:** AC-06: Result Guard Rule Enforcement: 'The Result object must prevent access to the data payload if the internal AppError is non-nil/contains a failure code.'
- **Proposed correction:** Add a concrete code snippet (e.g., Go interface or struct method) demonstrating how the guard rule for `Result` is implemented to prevent access to `Value` when `Error` is present.
