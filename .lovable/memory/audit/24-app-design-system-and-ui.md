# Audit — `spec/24-app-design-system-and-ui`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview)  
**Weighted Score:** **28/100 (F)**

> The spec is a 'hollow shell' describing a UI system and testing pipeline that have zero presence in the provided codebase. It refers to non-existent layout conventions and lacks concrete implementation paths.

---

## 6-Dimension Scores

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Completeness | 25% | 20 | 5.0 |
| Consistency | 25% | 50 | 12.5 |
| Alignment | 20% | 0 | 0.0 |
| Clarity | 15% | 40 | 6.0 |
| Maintainability | 10% | 60 | 6.0 |
| Testability | 5% | 20 | 1.0 |

## Code Mapping

**Implemented by:** _(none — pure-doc spec)_
**Expected but missing:** `src/theme/**`, `src/components/**`, `storybook/**`, `package.json`
**Orphan code candidates:** `linter-scripts/generate-gwt-acceptance.py`

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | orphan-spec | critical | 9/10 | Spec describes an application UI and design system that do not exist in the code index. |
| 2 | ambiguity | high | 7/10 | Acceptance criteria rely on a layout convention that is explicitly empty in the overview. |
| 3 | untestable | medium | 5/10 | Verification command is generic (npm run lint) with no specific configuration for token enforcement. |
| 4 | inconsistency | low | 2/10 | Mismatch between Spec version and AC versioning metadata. |

### Detail + Proposed Corrections

#### 1. [CRITICAL] Spec describes an application UI and design system that do not exist in the code index.
- **Category:** orphan-spec  |  **Impact:** 9/10
- **Evidence:** Defines application-specific UI and design system standards... enforces theme consistency.
- **Proposed correction:** Provide pathing to the actual UI application source code and theme definitions.

#### 2. [HIGH] Acceptance criteria rely on a layout convention that is explicitly empty in the overview.
- **Category:** ambiguity  |  **Impact:** 7/10
- **Evidence:** The layout must adhere to the 'layout conventions' mentioned in the Purpose section... (empty — awaiting content)
- **Proposed correction:** Define specific layout patterns (containers, grids) instead of referencing 'awaiting content'.

#### 3. [MEDIUM] Verification command is generic (npm run lint) with no specific configuration for token enforcement.
- **Category:** untestable  |  **Impact:** 5/10
- **Evidence:** Then CSS or Style-in-JS properties must use variables (e.g., --color-primary) instead of hardcoded hex/rgb values.
- **Proposed correction:** Specify exactly how npm run lint verifies tokens (e.g. stylelint-config-css-modules).

#### 4. [LOW] Mismatch between Spec version and AC versioning metadata.
- **Category:** inconsistency  |  **Impact:** 2/10
- **Evidence:** Overview Version: 3.2.0; AC Version: 2.0.0; Reference version: 3.2.0.
- **Proposed correction:** Verify and align the version numbers across metadata and AC files.
