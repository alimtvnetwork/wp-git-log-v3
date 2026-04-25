# Audit — `spec/02-coding-guidelines/24-app-design-system-and-ui`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview)  
**Weighted Score:** **33/100 (D)**

> The spec is a 'hollow' module. It defines how design documents SHOULD look and provides ACs for them, but contains zero actual design rules (colors, fonts, grids) in the overview, and there is no corresponding UI code in the index to validate against.

---

## 6-Dimension Scores

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Completeness | 25% | 20 | 5.0 |
| Consistency | 25% | 50 | 12.5 |
| Alignment | 20% | 0 | 0.0 |
| Clarity | 15% | 60 | 9.0 |
| Maintainability | 10% | 70 | 7.0 |
| Testability | 5% | 30 | 1.5 |

## Code Mapping

**Implemented by:** _(none — pure-doc spec)_
**Expected but missing:** `spec/02-coding-guidelines/24-app-design-system-and-ui/01-colors.md`, `spec/02-coding-guidelines/24-app-design-system-and-ui/02-typography.md`
**Orphan code candidates:** _(none)_

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | orphan-spec | high | 9/10 | Spec folder is an empty shell with no actual design system definitions. |
| 2 | drift | medium | 5/10 | Acceptance Criteria contains technical requirements not found in the source overview. |
| 3 | untestable | medium | 4/10 | Acceptance Criteria refers to 'code implementation' but no frontend code exists in the index to verify. |
| 4 | ambiguity | low | 3/10 | Layout constants in the AC are ambiguous (4px OR 8px). |

### Detail + Proposed Corrections

#### 1. [HIGH] Spec folder is an empty shell with no actual design system definitions.
- **Category:** orphan-spec  |  **Impact:** 9/10
- **Evidence:** _No content yet. Add design system documents as numbered files within this folder._
- **Proposed correction:** Create the actual design system content files (colors, typography, etc.) as the folder is currently empty.

#### 2. [MEDIUM] Acceptance Criteria contains technical requirements not found in the source overview.
- **Category:** drift  |  **Impact:** 5/10
- **Evidence:** AC includes an 'Inlined Contract' with specific keys (primary-color, grid-column-count) that do not exist in the overview.
- **Proposed correction:** Add the 'Design System Requirements Contract' or specific layout/theming values to the module overview (00-overview.md).

#### 3. [MEDIUM] Acceptance Criteria refers to 'code implementation' but no frontend code exists in the index to verify.
- **Category:** untestable  |  **Impact:** 4/10
- **Evidence:** AC-02: Then The implementation must align with the 'App-specific design system specifications'...
- **Proposed correction:** Specify which UI framework or file pattern in the code should be checked for alignment.

#### 4. [LOW] Layout constants in the AC are ambiguous (4px OR 8px).
- **Category:** ambiguity  |  **Impact:** 3/10
- **Evidence:** spacing-unit: 4px or 8px increments.
- **Proposed correction:** Define the specific max-content-width and spacing-unit values instead of giving ranges/options.
