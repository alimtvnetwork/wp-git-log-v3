# Audit — `spec/15-distribution-and-runner`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview)  
**Weighted Score:** **64/100 (D)**

> The spec is well-written and clear, but there is a massive disconnect with the actual codebase: common installers and the release pipeline are entirely missing, and the runner scripts are in the wrong directory.

---

## 6-Dimension Scores

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Completeness | 25% | 90 | 22.5 |
| Consistency | 25% | 80 | 20.0 |
| Alignment | 20% | 30 | 6.0 |
| Clarity | 15% | 85 | 12.8 |
| Maintainability | 10% | 90 | 9.0 |
| Testability | 5% | 95 | 4.8 |

## Code Mapping

**Implemented by:** `linter-scripts/run.sh`, `linter-scripts/run.ps1`, `linter-scripts/check-tree-health.cjs`, `linter-scripts/check-spec-cross-links.py`
**Expected but missing:** `install.sh`, `install.ps1`, `run.sh`, `run.ps1`, `.github/workflows/release.yml`, `install-config.json`, `linters-cicd/install.sh`
**Orphan code candidates:** `linter-scripts/validate-guidelines.go`

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | drift | high | 8/10 | Runner scripts are located in linter-scripts/ instead of the repository root as mandated by the spec. |
| 2 | orphan-spec | critical | 9/10 | The primary distribution mechanism (installers and release pipeline) is missing from the codebase. |
| 3 | missing-spec | low | 3/10 | The Go validator implementation is an orphan without a clear architectural home in the spec. |
| 4 | ambiguity | medium | 2/10 | Contradiction between 'Every GitHub Release MUST publish all' and the tree being 'not a release asset'. |

### Detail + Proposed Corrections

#### 1. [HIGH] Runner scripts are located in linter-scripts/ instead of the repository root as mandated by the spec.
- **Category:** drift  |  **Impact:** 8/10
- **Evidence:** The root runner scripts (run.sh / run.ps1) at the repo root... (Overview) vs linter-scripts/run.sh in code index.
- **Proposed correction:** Relocate run.sh/run.ps1 from linter-scripts/ to the repository root as required by the spec.

#### 2. [CRITICAL] The primary distribution mechanism (installers and release pipeline) is missing from the codebase.
- **Category:** orphan-spec  |  **Impact:** 9/10
- **Evidence:** The CI/CD release pipeline (.github/workflows/release.yml)... (Overview)
- **Proposed correction:** Create the release.yml workflow and the primary installation scripts (install.sh/ps1).

#### 3. [LOW] The Go validator implementation is an orphan without a clear architectural home in the spec.
- **Category:** missing-spec  |  **Impact:** 3/10
- **Evidence:** linter-scripts/validate-guidelines.go exists but is not explicitly detailed in the distribution architecture beyond a mention of 'legacy' behavior.
- **Proposed correction:** Add the Go validator into the 'Distribution and Runner' spec folder as a core component of the 'lint' command.

#### 4. [MEDIUM] Contradiction between 'Every GitHub Release MUST publish all' and the tree being 'not a release asset'.
- **Category:** ambiguity  |  **Impact:** 2/10
- **Evidence:** sourced via codeload.github.com archive — not a release asset
- **Proposed correction:** Clarify if 'codeload.github.com' is the only source or if a tagged zip artifact is required for the tree.
