# Audit — `spec/03-error-manage`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview)  
**Weighted Score:** **54/100 (D)**

> The specification is internally well-structured and highly detailed, but it exists in a vacuum. It describes a complex cross-stack architecture (PHP/Go/React) that is entirely absent from the provided code index, which only contains linter scripts.

---

## 6-Dimension Scores

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Completeness | 25% | 85 | 21.2 |
| Consistency | 25% | 95 | 23.8 |
| Alignment | 20% | 15 | 3.0 |
| Clarity | 15% | 90 | 13.5 |
| Maintainability | 10% | 80 | 8.0 |
| Testability | 5% | 70 | 3.5 |

## Code Mapping

**Implemented by:** `linter-scripts/generate-gwt-acceptance.py`
**Expected but missing:** `pkg/apperror/apperror.go`, `pkg/response/envelope.go`, `ui/src/hooks/useErrorModal.ts`, `ui/src/utils/statusCheck.ts`, `03-error-code-registry/error-codes-master.json`
**Orphan code candidates:** _(none)_

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | orphan-spec | critical | 8/10 | The spec describes a mature 3-tier error architecture, but the code index contains only linter scripts. |
| 2 | drift | high | 5/10 | AC-03 references a registry file that is not present in the code index. |
| 3 | missing-spec | low | 3/10 | The code index contains extensive linter scripts for specs, but the spec does not document their role in error resolution. |
| 4 | untestable | medium | 4/10 | AC-06 refers to a 'notification system' without locating the implementation in the codebase. |

### Detail + Proposed Corrections

#### 1. [CRITICAL] The spec describes a mature 3-tier error architecture, but the code index contains only linter scripts.
- **Category:** orphan-spec  |  **Impact:** 8/10
- **Evidence:** AC-01, AC-05, AC-07 describe Go packages (apperror) and Response Envelopes that do not exist in the file index.
- **Proposed correction:** Create the referenced apperror package in the backend and status check utility in the frontend.

#### 2. [HIGH] AC-03 references a registry file that is not present in the code index.
- **Category:** drift  |  **Impact:** 5/10
- **Evidence:** AC-03: 'registered in 03-error-code-registry/error-codes-master.json'
- **Proposed correction:** Ensure the code index includes the JSON registry or remove the mandate for its use from AC-03.

#### 3. [LOW] The code index contains extensive linter scripts for specs, but the spec does not document their role in error resolution.
- **Category:** missing-spec  |  **Impact:** 3/10
- **Evidence:** Individual files in linter-scripts like generate-gwt-acceptance.py are present but not detailed in the Error Management overview.
- **Proposed correction:** Add a section to Error Resolution (Category 01) explaining how to use the linter-scripts to verify spec alignment.

#### 4. [MEDIUM] AC-06 refers to a 'notification system' without locating the implementation in the codebase.
- **Category:** untestable  |  **Impact:** 4/10
- **Evidence:** AC-06: 'The notification system MUST apply specific color tokens...'
- **Proposed correction:** Define the specific 'notification system' component/file to be checked.
