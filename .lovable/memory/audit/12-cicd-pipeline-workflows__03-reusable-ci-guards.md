# Audit — `spec/12-cicd-pipeline-workflows/03-reusable-ci-guards`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview)  
**Weighted Score:** **60/100 (C)**

> The module functions primarily as a 'Blueprint' or 'Implementation Guide' for CI patterns rather than a specification for the existing codebase. While the 'Forbidden Name' and 'Tree Health' concepts are present in the linter-scripts, the majority of the described patterns (06-09) and the specific architecture (ci-guards.yaml) are missing from the code index.

---

## 6-Dimension Scores

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Completeness | 25% | 60 | 15.0 |
| Consistency | 25% | 80 | 20.0 |
| Alignment | 20% | 40 | 8.0 |
| Clarity | 15% | 70 | 10.5 |
| Maintainability | 10% | 85 | 8.5 |
| Testability | 5% | 50 | 2.5 |

## Code Mapping

**Implemented by:** `linter-scripts/check-forbidden-strings.py`, `linter-scripts/check-tree-health.cjs`, `linter-scripts/run.sh`, `linter-scripts/check-spec-cross-links.py`
**Expected but missing:** `ci-guards.yaml`, `.github/scripts/forbidden-name-guard.py`, `.github/scripts/baseline-diff-lint.py`
**Orphan code candidates:** _(none)_

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | drift | medium | 5/10 | The spec describes generic CI guards for app code, but the code implements them as meta-linters for the spec tree. |
| 2 | orphan-spec | high | 4/10 | Several patterns described (06, 07, 08, 09) do not exist in the code index. |
| 3 | untestable | low | 3/10 | Acceptance Criteria only verify the existence of documentation, not the behavior of the guards. |
| 4 | ambiguity | medium | 4/10 | Spec vacillates between being a 'Guide' for other repos and a 'Spec' for the current repo's tooling. |

### Detail + Proposed Corrections

#### 1. [MEDIUM] The spec describes generic CI guards for app code, but the code implements them as meta-linters for the spec tree.
- **Category:** drift  |  **Impact:** 5/10
- **Evidence:** The overview says 'originally implemented in Bash and Python under .github/scripts/ of a Go monorepo' and describes them as 'reusable-ci-guards'. The code shows these scripts in 'linter-scripts/' specifically for spec validation.
- **Proposed correction:** Update the spec to clarify that these patterns are applied to the spec repository itself via linter-scripts, rather than generic CI guards for application code.

#### 2. [HIGH] Several patterns described (06, 07, 08, 09) do not exist in the code index.
- **Category:** orphan-spec  |  **Impact:** 4/10
- **Evidence:** Patterns 06 (Matrix Test Aggregator) and 07 (Shared CLI Wrapper) have no corresponding scripts in the linter-scripts directory.
- **Proposed correction:** Implement the 'Matrix Test Aggregator' and 'Shared CLI Wrapper' or remove them from the spec if they are not intended for this repository.

#### 3. [LOW] Acceptance Criteria only verify the existence of documentation, not the behavior of the guards.
- **Category:** untestable  |  **Impact:** 3/10
- **Evidence:** AC-01 through AC-05 only verify the existence of spec files and the success of the linter-scripts themselves, not the functionality of the patterns described.
- **Proposed correction:** Add specific AC for the logic of the guards (e.g., 'must detect forbidden strings') rather than just checking if files exist.

#### 4. [MEDIUM] Spec vacillates between being a 'Guide' for other repos and a 'Spec' for the current repo's tooling.
- **Category:** ambiguity  |  **Impact:** 4/10
- **Evidence:** Section 'Why These Six?' describes problems like 'redeclared in this block' (compiler errors) which don't apply to the Markdown files in this repo.
- **Proposed correction:** Specify whether the patterns are 'blueprints' for others to follow or the actual implementation used in this repo.
