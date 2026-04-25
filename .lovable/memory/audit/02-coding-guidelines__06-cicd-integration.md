# Audit — `spec/02-coding-guidelines/06-cicd-integration`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview)  
**Weighted Score:** **60/100 (F)**

> The spec describes a 'linters-cicd' package and a GitHub Composite Action that do not exist in the codebase. While there is a 'linter-scripts' folder, it lacks the structure, SARIF validation, and distribution files (action.yml) promised in the spec.

---

## 6-Dimension Scores

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Completeness | 25% | 90 | 22.5 |
| Consistency | 25% | 80 | 20.0 |
| Alignment | 20% | 20 | 4.0 |
| Clarity | 15% | 85 | 12.8 |
| Maintainability | 10% | 70 | 7.0 |
| Testability | 5% | 60 | 3.0 |

## Code Mapping

**Implemented by:** `linter-scripts/run.sh`, `linter-scripts/validate-guidelines.py`, `.github/workflows/spec-health.yml`
**Expected but missing:** `linters-cicd/checks/`, `linters-cicd/configs/`, `linters-cicd/ci/`, `linters-cicd/action.yml`, `linters-cicd/run-all.sh`, `linters-cicd/scripts/validate-sarif.py`
**Orphan code candidates:** `linter-scripts/audit-spec-vs-code.py`, `linter-scripts/check-axios-version.sh`

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | drift | high | 8/10 | Fundamental directory naming mismatch between spec and implementation. |
| 2 | orphan-spec | high | 7/10 | SARIF validation script mentioned in AC does not exist in the code index. |
| 3 | orphan-spec | critical | 9/10 | The primary deliverable (CI integration/Composite Action) is missing from the codebase. |
| 4 | missing-spec | low | 3/10 | Go implementation of the validator exists but spec focuses exclusively on Python/Bash. |
| 5 | untestable | medium | 4/10 | 'CODE RED' is a symbolic link to a guideline set not explicitly defined within the testable context of this spec module. |

### Detail + Proposed Corrections

#### 1. [HIGH] Fundamental directory naming mismatch between spec and implementation.
- **Category:** drift  |  **Impact:** 8/10
- **Evidence:** Spec expects `linters-cicd/`; Code uses `linter-scripts/`.
- **Proposed correction:** Unify the directory structure: Rename `linter-scripts/` to `linters-cicd/` or update the spec to reflect the existing directory.

#### 2. [HIGH] SARIF validation script mentioned in AC does not exist in the code index.
- **Category:** orphan-spec  |  **Impact:** 7/10
- **Evidence:** AC-CI-002: `linters-cicd/scripts/validate-sarif.py` validates every emitted file...
- **Proposed correction:** Create the SARIF validation script or remove the requirement from AC-CI-002.

#### 3. [CRITICAL] The primary deliverable (CI integration/Composite Action) is missing from the codebase.
- **Category:** orphan-spec  |  **Impact:** 9/10
- **Evidence:** Layer 3 — CI templates (`linters-cicd/ci/`) ... GitHub composite Action under `linters-cicd/action.yml`.
- **Proposed correction:** Create action.yml and the CI templates directory described in Layer 3.

#### 4. [LOW] Go implementation of the validator exists but spec focuses exclusively on Python/Bash.
- **Category:** missing-spec  |  **Impact:** 3/10
- **Evidence:** linter-scripts/validate-guidelines.go
- **Proposed correction:** Add documentation for the Go-based validator existing in the linter-scripts directory.

#### 5. [MEDIUM] 'CODE RED' is a symbolic link to a guideline set not explicitly defined within the testable context of this spec module.
- **Category:** untestable  |  **Impact:** 4/10
- **Evidence:** AC-CI-003: produces a SARIF file with zero CODE RED findings.
- **Proposed correction:** Define the specific 'zero CODE RED findings' criteria or link to the rule set.
