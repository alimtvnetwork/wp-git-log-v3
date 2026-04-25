# Audit — `spec/02-coding-guidelines/03-golang`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview)  
**Weighted Score:** **48/100 (D)**

> The spec describes a full-scale Go application (with DI, DB repositories, and custom error types), but the code index only contains a single utility script. This is a significant 'hallucination' drift where the guidelines are disconnected from the actual project footprint.

---

## 6-Dimension Scores

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Completeness | 25% | 40 | 10.0 |
| Consistency | 25% | 80 | 20.0 |
| Alignment | 20% | 20 | 4.0 |
| Clarity | 15% | 70 | 10.5 |
| Maintainability | 10% | 80 | 8.0 |
| Testability | 5% | 30 | 1.5 |

## Code Mapping

**Implemented by:** `linter-scripts/validate-guidelines.go`
**Expected but missing:** `pkg/apperror/result.go`, `pkg/pathutil/pathutil.go`, `pkg/fileutil/fileutil.go`
**Orphan code candidates:** `linter-scripts/validate-guidelines.go`

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | orphan-spec | high | 7/10 | Spec describes a complex backend architecture (DI, Services, Repositories) that does not exist in the code index. |
| 2 | drift | medium | 5/10 | The only Go file (validate-guidelines.go) uses standard error handling, not the mandated apperror.Result[T] pattern. |
| 3 | missing-spec | low | 3/10 | The primary Go implementation file is not referenced or governed by the spec module. |
| 4 | untestable | low | 2/10 | Acceptance criteria for naming are subjective and lack objective verification steps. |

### Detail + Proposed Corrections

#### 1. [HIGH] Spec describes a complex backend architecture (DI, Services, Repositories) that does not exist in the code index.
- **Category:** orphan-spec  |  **Impact:** 7/10
- **Evidence:** AC-02: Architecture mentions Service layers, DI, and Repository patterns.
- **Proposed correction:** Remove specific infrastructure claims like Repository patterns if the repo only contains linter scripts.

#### 2. [MEDIUM] The only Go file (validate-guidelines.go) uses standard error handling, not the mandated apperror.Result[T] pattern.
- **Category:** drift  |  **Impact:** 5/10
- **Evidence:** AC-01: Error handling uses apperror.Result[T] pattern consistently
- **Proposed correction:** Update AC-01 to reflect actual error handling in validate-guidelines.go.

#### 3. [LOW] The primary Go implementation file is not referenced or governed by the spec module.
- **Category:** missing-spec  |  **Impact:** 3/10
- **Evidence:** linter-scripts/validate-guidelines.go exists without mention in the spec.
- **Proposed correction:** Add documentation for the validator script logic to the Golang standards.

#### 4. [LOW] Acceptance criteria for naming are subjective and lack objective verification steps.
- **Category:** untestable  |  **Impact:** 2/10
- **Evidence:** AC-01: Naming conventions follow Go idioms
- **Proposed correction:** Define concrete naming rules instead of referencing 'Go idioms'.
