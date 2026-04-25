# Audit — `spec/16-generic-release`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview)  
**Weighted Score:** **72/100 (C)**

> The spec is an excellent 'blueprint' for a release system, but it is currently an orphan. It describes complex build/release logic that is nowhere to be found in the provided code index, while the code index contains many linter scripts that are completely undocumented.

---

## 6-Dimension Scores

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Completeness | 25% | 85 | 21.2 |
| Consistency | 25% | 95 | 23.8 |
| Alignment | 20% | 40 | 8.0 |
| Clarity | 15% | 90 | 13.5 |
| Maintainability | 10% | 90 | 9.0 |
| Testability | 5% | 60 | 3.0 |

## Code Mapping

**Implemented by:** `linter-scripts/check-tree-health.cjs`, `linter-scripts/check-spec-cross-links.py`, `linter-scripts/run.sh`
**Expected but missing:** `.github/workflows/release.yml`, `scripts/install.sh`, `scripts/install.ps1`
**Orphan code candidates:** _(none)_

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | orphan-spec | high | 8/10 | The spec describes a detailed release pipeline and install scripts, but no actual CI/CD or release scripts exist in the index. |
| 2 | missing-spec | medium | 5/10 | A large suite of linter scripts exists in the codebase but is not documented by this or any other visible spec. |
| 3 | drift | low | 2/10 | AC-05 references check-tree-health.js (JS) while the codebase contains check-tree-health.cjs (CJS). |
| 4 | untestable | medium | 4/10 | Acceptance criteria only cover meta-documentation health and not the actual technical requirements of a release pipeline. |

### Detail + Proposed Corrections

#### 1. [HIGH] The spec describes a detailed release pipeline and install scripts, but no actual CI/CD or release scripts exist in the index.
- **Category:** orphan-spec  |  **Impact:** 8/10
- **Evidence:** This folder defines a generic, reusable blueprint for releasing cross-compiled CLI binaries via CI/CD... See the Mermaid diagram: images/release-pipeline-flow.mmd
- **Proposed correction:** Implement the concrete release workflow or install scripts described in the blueprint to anchor the spec in reality.

#### 2. [MEDIUM] A large suite of linter scripts exists in the codebase but is not documented by this or any other visible spec.
- **Category:** missing-spec  |  **Impact:** 5/10
- **Evidence:** linter-scripts/audit-spec-vs-code.py, linter-scripts/check-axios-version.sh, etc.
- **Proposed correction:** Define a new spec module or section specifically for the linter-scripts utility suite.

#### 3. [LOW] AC-05 references check-tree-health.js (JS) while the codebase contains check-tree-health.cjs (CJS).
- **Category:** drift  |  **Impact:** 2/10
- **Evidence:** node linter-scripts/check-tree-health.js --min=80
- **Proposed correction:** Update AC-05 to reflect that the script is a .cjs file or rename the file.

#### 4. [MEDIUM] Acceptance criteria only cover meta-documentation health and not the actual technical requirements of a release pipeline.
- **Category:** untestable  |  **Impact:** 4/10
- **Evidence:** AC-01 through AC-05 only test file presence and linting, not implementation features.
- **Proposed correction:** Define explicit technical metrics for 'cross-compilation' and 'checksum verification' as ACs.
