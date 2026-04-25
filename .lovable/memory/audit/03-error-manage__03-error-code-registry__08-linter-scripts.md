# Audit — `spec/03-error-manage/03-error-code-registry/08-linter-scripts`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview)  
**Weighted Score:** **40/100 (F)**

> The spec describes a specific Error Code Registry linter which is completely missing from the codebase, while ignoring the 20+ actual linter scripts present in the index.

---

## 6-Dimension Scores

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Completeness | 25% | 40 | 10.0 |
| Consistency | 25% | 80 | 20.0 |
| Alignment | 20% | 10 | 2.0 |
| Clarity | 15% | 70 | 10.5 |
| Maintainability | 10% | 60 | 6.0 |
| Testability | 5% | 30 | 1.5 |

## Code Mapping

**Implemented by:** _(none — pure-doc spec)_
**Expected but missing:** `linter-scripts/check-error-registry.py`, `linter-scripts/validate-error-codes.sh`
**Orphan code candidates:** `linter-scripts/check-spec-cross-links.py`, `linter-scripts/generate-gwt-acceptance.py`, `linter-scripts/audit-spec-vs-code.py`

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | orphan-spec | high | 8/10 | The spec describes a specific 'Error Code Registry' linter that is absent from the codebase. |
| 2 | drift | medium | 5/10 | The entry point scripts do not appear to invoke the error registry validation described. |
| 3 | missing-spec | low | 4/10 | Numerous existing linter scripts in the index have no corresponding requirements in this module. |
| 4 | untestable | medium | 3/10 | AC refers to 'the linter script' generically without identifying the file name. |

### Detail + Proposed Corrections

#### 1. [HIGH] The spec describes a specific 'Error Code Registry' linter that is absent from the codebase.
- **Category:** orphan-spec  |  **Impact:** 8/10
- **Evidence:** AC-01 through AC-05 describe an error code linter that does not exist in the file inventory.
- **Proposed correction:** Implement the error registry linter script or update the spec to reflect existing generic linting tools.

#### 2. [MEDIUM] The entry point scripts do not appear to invoke the error registry validation described.
- **Category:** drift  |  **Impact:** 5/10
- **Evidence:** linter-scripts/run.sh
- **Proposed correction:** Add the Error Code linter to the main run.sh/run.ps1 execution flow.

#### 3. [LOW] Numerous existing linter scripts in the index have no corresponding requirements in this module.
- **Category:** missing-spec  |  **Impact:** 4/10
- **Evidence:** linter-scripts/check-memory-mirror-drift.py
- **Proposed correction:** Create spec modules for the variety of existing scripts like check-axios-version.sh or check-memory-mirror-drift.py.

#### 4. [MEDIUM] AC refers to 'the linter script' generically without identifying the file name.
- **Category:** untestable  |  **Impact:** 3/10
- **Evidence:** AC-01: 'Running the consistency linter script'
- **Proposed correction:** Define the specific command or script name that satisfies the AC.
