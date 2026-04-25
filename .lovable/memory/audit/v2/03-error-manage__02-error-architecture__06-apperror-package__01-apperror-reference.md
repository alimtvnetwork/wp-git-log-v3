# Audit v2 — `spec/03-error-manage/02-error-architecture/06-apperror-package/01-apperror-reference`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview, 2-pass)  
**Implementability Score:** **43/100 (D)**  
**Blast radius:** 9/10

> This module is a high-level overview of an error management system, but it lacks the concrete code contracts required for AI implementability. It describes what should exist, but not how it should be implemented in code. The absence of acceptance criteria further hinders automated implementation and verification.


**Score justification:** The low implementability is due to the lack of concrete contracts like DDL for the database spec, forcing an AI to infer shapes. Completeness is impacted by missing acceptance criteria. Clarity is capped due to the low waffle_per_kchar, while testability is low due to ac_count being 0.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 30 | 10.5 |
| Completeness | 20% | 60 | 12.0 |
| Alignment | 15% | 0 | 0.0 |
| Consistency | 10% | 100 | 10.0 |
| Clarity | 10% | 60 | 6.0 |
| Testability | 7% | 20 | 1.4 |
| Maintainability | 3% | 100 | 3.0 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 9,
  "overview_chars": 1974,
  "ac_chars": 0,
  "ac_count": 0,
  "gwt_block_count": 0,
  "consistency_report": true,
  "code_blocks_total": 82,
  "code_blocks_by_lang": {
    "go": 74,
    "plain": 3,
    "json": 1,
    "php": 4
  },
  "has_sql_ddl": false,
  "has_json_schema": true,
  "has_ts_enums": false,
  "has_yaml_openapi": false,
  "links_total": 23,
  "links_broken": 0,
  "todo_density": 0,
  "waffle_per_kchar": 0.09,
  "child_modules": 0
}
```

## Implementability Blockers

- No explicit SQL DDL for database specs, requiring inference.
- Abstract description of 'AppError struct and constructors' without Go code for the struct definition and constructor functions.
- Result[T], ResultSlice[T], ResultMap[K,V] are abstractly mentioned without concrete Go type definitions.
- Domain error type enums are described, but the actual Go enum definitions (E1xxx–E14xxx) are mentioned as external files without being inlined.
- JSON serialization is discussed, but the explicit Go code for `MarshalJSON` and `UnmarshalJSON` methods is missing.
- Error code convention and stack trace skip rules are described as policy but not implemented as executable code or concrete schemas.
- No concrete examples or code snippets for 'Usage examples, service adapter unwrap pattern'.

## Code Mapping

**Implemented by:** _(none — pure-doc spec)_
**Expected but missing:** `src/apperror.go (including struct definition, constructors, and methods)`, `src/result.go (including Result[T], ResultSlice[T], ResultMap[K,V] definitions)`, `src/enums.go (including E1xxx-E14xxx enum definitions)`, `src/serialization.go (including JSON marshal/unmarshal logic)`
**Orphan code candidates:** `linter-scripts/audit-spec-vs-code-v2.py`, `linter-scripts/audit-spec-vs-code.py`, `linter-scripts/check-axios-version.sh`, `linter-scripts/check-forbidden-spec-paths.sh`, `linter-scripts/check-forbidden-strings.py`, `linter-scripts/check-memory-mirror-drift.py`, `linter-scripts/check-readme-canonicals.py`, `linter-scripts/check-readme-install-section.py`, `linter-scripts/check-root-readme.py`, `linter-scripts/check-spec-cross-links.py`, `linter-scripts/check-spec-folder-refs.py`, `linter-scripts/check-tree-health.cjs`, `linter-scripts/fill-missing-acceptance-criteria.cjs`, `linter-scripts/fill-missing-changelogs.cjs`, `linter-scripts/fill-missing-consistency-reports.cjs`, `linter-scripts/forbidden-strings.toml`, `linter-scripts/generate-dashboard-data.cjs`, `linter-scripts/generate-gwt-acceptance.py`, `linter-scripts/generate-spec-index.cjs`, `linter-scripts/run.ps1`, `linter-scripts/run.sh`, `linter-scripts/suggest-spec-cross-link-fixes.py`, `linter-scripts/validate-guidelines.go`, `linter-scripts/validate-guidelines.py`, `.github/workflows/spec-health.yml`, `src/`

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | missing-contract | critical | 9/10 | Missing concrete Go struct definitions for AppError and related types. |
| 2 | missing-contract | critical | 8/10 | Missing concrete Go type definitions for Result[T], ResultSlice[T], and ResultMap[K,V]. |
| 3 | missing-contract | high | 7/10 | Domain error type enums (E1xxx–E14xxx) are referenced but their concrete Go enum definitions are not inlined. |
| 4 | missing-contract | medium | 6/10 | JSON serialization mechanisms for AppError are described conceptually but lack concrete Go code for MarshalJSON and UnmarshalJSON methods. |
| 5 | missing-spec | high | 5/10 | Absence of Acceptance Criteria makes automated testing and AI validation difficult. |
| 6 | ambiguity | low | 3/10 | Ambiguous description of 'Usage examples, service adapter unwrap pattern' without concrete code. |

### Detail + Proposed Corrections

#### 1. [CRITICAL] Missing concrete Go struct definitions for AppError and related types.
- **Category:** missing-contract  |  **Impact:** 9/10
- **Evidence:** 02-apperror-struct.md describes 'AppError struct and constructors' but lacks the actual Go code.
- **Proposed correction:** Inline the full Go struct definition for AppError with all its fields and their types, including constructor functions, or provide a clear reference to a file containing them.

#### 2. [CRITICAL] Missing concrete Go type definitions for Result[T], ResultSlice[T], and ResultMap[K,V].
- **Category:** missing-contract  |  **Impact:** 8/10
- **Evidence:** 03-result-types.md describes 'Result[T], ResultSlice[T], ResultMap[K,V]' but lacks their actual Go type definitions.
- **Proposed correction:** Inline the full Go type definitions for Result[T], ResultSlice[T], and ResultMap[K,V] with all their generic parameters and underlying types, or provide a clear reference to a file containing them.

#### 3. [HIGH] Domain error type enums (E1xxx–E14xxx) are referenced but their concrete Go enum definitions are not inlined.
- **Category:** missing-contract  |  **Impact:** 7/10
- **Evidence:** 05-apperrtype-enums.md mentions 'all E1xxx–E14xxx enum definitions' but only links without inlining the Go code.
- **Proposed correction:** Inline the complete Go enum definitions for all specified error types (E1xxx–E14xxx) directly within the spec, or ensure the linked file explicitly contains these definitions and is easily accessible and parseable by an AI.

#### 4. [MEDIUM] JSON serialization mechanisms for AppError are described conceptually but lack concrete Go code for MarshalJSON and UnmarshalJSON methods.
- **Category:** missing-contract  |  **Impact:** 6/10
- **Evidence:** 06-serialization-and-guards.md discusses JSON serialization but does not provide the actual Go implementation of the `MarshalJSON` and `UnmarshalJSON` methods for AppError.
- **Proposed correction:** Provide the complete Go code for the `MarshalJSON` and `UnmarshalJSON` methods of AppError, demonstrating how serialization and deserialization are handled.

#### 5. [HIGH] Absence of Acceptance Criteria makes automated testing and AI validation difficult.
- **Category:** missing-spec  |  **Impact:** 5/10
- **Evidence:** The 'Acceptance Criteria' section is explicitly marked as '(MISSING)'.
- **Proposed correction:** Add detailed, verifiable Acceptance Criteria in a GWT (Given/When/Then) format for each significant function or behavior described in the spec.

#### 6. [LOW] Ambiguous description of 'Usage examples, service adapter unwrap pattern' without concrete code.
- **Category:** ambiguity  |  **Impact:** 3/10
- **Evidence:** 05-usage-and-adapters.md describes 'Usage examples, service adapter unwrap pattern' without providing functional code examples.
- **Proposed correction:** Include concrete, runnable Go code examples demonstrating the usage of AppError and the service adapter unwrap pattern.
