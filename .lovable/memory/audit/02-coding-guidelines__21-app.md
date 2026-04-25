# Audit — `spec/02-coding-guidelines/21-app`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview)  
**Weighted Score:** **79/100 (B)**

> The spec provides a clear organizational framework for application-specific documentation, supported by linter scripts, but currently functions as an empty shell without concrete implementation details.

---

## 6-Dimension Scores

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Completeness | 25% | 70 | 17.5 |
| Consistency | 25% | 75 | 18.8 |
| Alignment | 20% | 80 | 16.0 |
| Clarity | 15% | 85 | 12.8 |
| Maintainability | 10% | 90 | 9.0 |
| Testability | 5% | 95 | 4.8 |

## Code Mapping

**Implemented by:** `linter-scripts/check-forbidden-spec-paths.sh`, `linter-scripts/check-spec-folder-refs.py`, `linter-scripts/validate-guidelines.py`
**Expected but missing:** _(none)_
**Orphan code candidates:** _(none)_

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | orphan-spec | medium | 5/10 | The spec defines a structure for application features but contains no actual feature content. |
| 2 | drift | low | 2/10 | Version and date mismatch between Overview and Acceptance Criteria. |
| 3 | ambiguity | low | 3/10 | Conflicting numbering guidance: folder is '21', but AC suggests '01' for sub-files. |

### Detail + Proposed Corrections

#### 1. [MEDIUM] The spec defines a structure for application features but contains no actual feature content.
- **Category:** orphan-spec  |  **Impact:** 5/10
- **Evidence:** _No content yet. Add app-specific specs as numbered files within this folder._
- **Proposed correction:** Remove the placeholder 'No content yet' and add actual application feature specifications.

#### 2. [LOW] Version and date mismatch between Overview and Acceptance Criteria.
- **Category:** drift  |  **Impact:** 2/10
- **Evidence:** Overview: Version 3.2.0 (2026-04-16) vs AC: Version 2.0.0 (2026-04-25)
- **Proposed correction:** Update Version and Updated dates in 00-overview.md and 97-acceptance-criteria.md to match.

#### 3. [LOW] Conflicting numbering guidance: folder is '21', but AC suggests '01' for sub-files.
- **Category:** ambiguity  |  **Impact:** 3/10
- **Evidence:** The filename must follow the numbered prefix convention (e.g., 01-feature-name.md)
- **Proposed correction:** Clarify if the numbering starts at 01 or follows a different convention given the folder is '21-app'.
