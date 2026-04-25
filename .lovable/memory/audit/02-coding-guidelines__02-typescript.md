# Audit — `spec/02-coding-guidelines/02-typescript`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview)  
**Weighted Score:** **38/100 (F)**

> The spec module is a detailed 'ghost' document; it describes a mature TypeScript architecture and specific enums that do not exist in the provided code index, which instead contains linter infrastructure.

---

## 6-Dimension Scores

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Completeness | 25% | 80 | 20.0 |
| Consistency | 25% | 70 | 17.5 |
| Alignment | 20% | 0 | 0.0 |
| Clarity | 15% | 60 | 9.0 |
| Maintainability | 10% | 70 | 7.0 |
| Testability | 5% | 30 | 1.5 |

## Code Mapping

**Implemented by:** _(none — pure-doc spec)_
**Expected but missing:** `src/types/connection-status.ts`, `src/types/entity-status.ts`, `src/types/execution-status.ts`, `src/types/export-status.ts`, `src/types/message-status.ts`, `src/types/log-level.ts`, `.eslintrc.json`, `tsconfig.json`
**Orphan code candidates:** _(none)_

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | drift | high | 7/10 | Spec focuses on ESLint while the repository uses custom linter scripts for enforcement. |
| 2 | orphan-spec | critical | 9/10 | Detailed enum specifications exist with no corresponding implementation files in the project. |
| 3 | untestable | medium | 5/10 | Acceptance criteria are high-level guidelines rather than verifiable project states. |
| 4 | ambiguity | medium | 6/10 | AC-02 introduces external library dependencies (Zustand, React Query) not mentioned in the main overview. |

### Detail + Proposed Corrections

#### 1. [HIGH] Spec focuses on ESLint while the repository uses custom linter scripts for enforcement.
- **Category:** drift  |  **Impact:** 7/10
- **Evidence:** Spec 11-eslint-enforcement.md describes ESLint/SonarQube, but the code index contains a large suite of custom Python/Go/Node linter scripts.
- **Proposed correction:** Update spec/02-coding-guidelines/02-typescript/11-eslint-enforcement.md to match the custom linter scripts actually present in linter-scripts/.

#### 2. [CRITICAL] Detailed enum specifications exist with no corresponding implementation files in the project.
- **Category:** orphan-spec  |  **Impact:** 9/10
- **Evidence:** 01-06-*.md and 10-*.md define specific enums, but no corresponding .ts files exist in the code index.
- **Proposed correction:** Create the TypeScript enum files or remove the specific enum specs if they are purely conceptual.

#### 3. [MEDIUM] Acceptance criteria are high-level guidelines rather than verifiable project states.
- **Category:** untestable  |  **Impact:** 5/10
- **Evidence:** AC-01 'Type definitions avoid any' is a general guideline, not a specific verifiable criterion for a module.
- **Proposed correction:** Add specific file paths or checkable patterns to the AC.

#### 4. [MEDIUM] AC-02 introduces external library dependencies (Zustand, React Query) not mentioned in the main overview.
- **Category:** ambiguity  |  **Impact:** 6/10
- **Evidence:** AC-02: Patterns mentions Zustand and React Query, which are architectural choices not evidenced in the file list.
- **Proposed correction:** Define which files or directories these patterns apply to.
