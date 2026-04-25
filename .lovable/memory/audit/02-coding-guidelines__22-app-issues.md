# Audit — `spec/02-coding-guidelines/22-app-issues`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview)  
**Weighted Score:** **80/100 (B)**

> The spec module is a well-structured shell and correctly integrates with existing linter tools, but it currently lacks any actual content (app issues) and contains minor versioning inconsistencies.

---

## 6-Dimension Scores

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Completeness | 25% | 70 | 17.5 |
| Consistency | 25% | 80 | 20.0 |
| Alignment | 20% | 90 | 18.0 |
| Clarity | 15% | 85 | 12.8 |
| Maintainability | 10% | 90 | 9.0 |
| Testability | 5% | 85 | 4.2 |

## Code Mapping

**Implemented by:** `linter-scripts/check-spec-cross-links.py`, `linter-scripts/check-tree-health.cjs`, `linter-scripts/run.sh`
**Expected but missing:** _(none)_
**Orphan code candidates:** _(none)_

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | orphan-spec | medium | 3/10 | The spec defines a container for content that does not yet exist. |
| 2 | inconsistency | low | 2/10 | Internal versioning mismatch between overview and acceptance criteria. |
| 3 | untestable | low | 1/10 | 'Non-trivial' is subjective and difficult to verify programmatically without clearer constraints. |

### Detail + Proposed Corrections

#### 1. [MEDIUM] The spec defines a container for content that does not yet exist.
- **Category:** orphan-spec  |  **Impact:** 3/10
- **Evidence:** ## Contents \n\n _No content yet. Add app issue analyses as numbered files within this folder._
- **Proposed correction:** Populate the module with at least one example bug analysis or actual app issue document as the overview currently states 'No content yet'.

#### 2. [LOW] Internal versioning mismatch between overview and acceptance criteria.
- **Category:** inconsistency  |  **Impact:** 2/10
- **Evidence:** Overview Version: 3.2.0; AC Version: 1.0.0
- **Proposed correction:** Align the version numbers between 00-overview.md (3.2.0) and 97-acceptance-criteria.md (1.0.0).

#### 3. [LOW] 'Non-trivial' is subjective and difficult to verify programmatically without clearer constraints.
- **Category:** untestable  |  **Impact:** 1/10
- **Evidence:** AC-01: Module entry point exists and is non-trivial
- **Proposed correction:** Specify the exact criteria for what constitutes a 'non-trivial' entry point beyond header metadata.
