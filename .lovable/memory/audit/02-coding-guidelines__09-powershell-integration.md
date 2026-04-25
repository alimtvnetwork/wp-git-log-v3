# Audit — `spec/02-coding-guidelines/09-powershell-integration`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview)  
**Weighted Score:** **51/100 (D)**

> The spec is a 'ghost spec'—it provides high-quality guidelines for a technology (PowerShell) that is virtually non-existent in the provided code index (1 file vs 20+ non-PS files). It represents significant overhead for a toolchain that primarily uses Python and JavaScript.

---

## 6-Dimension Scores

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Completeness | 25% | 40 | 10.0 |
| Consistency | 25% | 90 | 22.5 |
| Alignment | 20% | 20 | 4.0 |
| Clarity | 15% | 85 | 12.8 |
| Maintainability | 10% | 70 | 7.0 |
| Testability | 5% | 60 | 3.0 |

## Code Mapping

**Implemented by:** `linter-scripts/run.ps1`
**Expected but missing:** _(none)_
**Orphan code candidates:** `linter-scripts/check-axios-version.sh`, `linter-scripts/run.sh`, `linter-scripts/validate-guidelines.go`

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | orphan-spec | high | 7/10 | The spec defines comprehensive guidelines for a language that is almost entirely absent from the codebase. |
| 2 | missing-spec | medium | 5/10 | Codebase is 95% non-PowerShell script, but no guidelines for those languages are visible in this module. |
| 3 | ambiguity | low | 2/10 | Version '7.x' is too broad for a strict technical specification. |
| 4 | untestable | low | 3/10 | AC-03 requires ShouldProcess for scripts impacting 'system state' but doesn't define what constitutes state in this project. |

### Detail + Proposed Corrections

#### 1. [HIGH] The spec defines comprehensive guidelines for a language that is almost entirely absent from the codebase.
- **Category:** orphan-spec  |  **Impact:** 7/10
- **Evidence:** Spec defines extensive naming and error handling rules, but only one .ps1 file exists in the index.
- **Proposed correction:** Either implement the PowerShell guidelines in new scripts or remove the spec if PowerShell is no longer a primary automation tool.

#### 2. [MEDIUM] Codebase is 95% non-PowerShell script, but no guidelines for those languages are visible in this module.
- **Category:** missing-spec  |  **Impact:** 5/10
- **Evidence:** linter-scripts/ contains .py, .sh, .cjs, and .go files.
- **Proposed correction:** Add guidance for Python, Shell, and Node.js which dominate the linter-scripts directory.

#### 3. [LOW] Version '7.x' is too broad for a strict technical specification.
- **Category:** ambiguity  |  **Impact:** 2/10
- **Evidence:** Runtime: PowerShell Core 7.x (pwsh)
- **Proposed correction:** Define specific PowerShell Core version (e.g., 7.4 LTS) instead of '7.x'.

#### 4. [LOW] AC-03 requires ShouldProcess for scripts impacting 'system state' but doesn't define what constitutes state in this project.
- **Category:** untestable  |  **Impact:** 3/10
- **Evidence:** AC-03: Safe Execution with ShouldProcess
- **Proposed correction:** Update AC-03 to specify which files or function prefixes must implement ShouldProcess.
