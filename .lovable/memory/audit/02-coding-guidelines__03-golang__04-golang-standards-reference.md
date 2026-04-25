# Audit — `spec/02-coding-guidelines/03-golang/04-golang-standards-reference`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview)  
**Weighted Score:** **83/100 (B)**

> The spec is highly structured and opinionated, which is good for consistency, but relies on internal frameworks (apperror, dbutil) that are documented as existing but are not visible in the provided code index, creating a 'spec-only' bubble.

---

## 6-Dimension Scores

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Completeness | 25% | 85 | 21.2 |
| Consistency | 25% | 95 | 23.8 |
| Alignment | 20% | 70 | 14.0 |
| Clarity | 15% | 90 | 13.5 |
| Maintainability | 10% | 90 | 9.0 |
| Testability | 5% | 80 | 4.0 |

## Code Mapping

**Implemented by:** `linter-scripts/validate-guidelines.go`
**Expected but missing:** `internal/apperror/apperror.go`, `internal/dbutil/dbutil.go`
**Orphan code candidates:** _(none)_

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | drift | medium | 5/10 | Spec mandates PascalCase file naming which contradicts standard Go conventions (snake_case), potentially causing friction with existing Go tooling. |
| 2 | untestable | medium | 4/10 | Ban on nested 'if' blocks is difficult to enforce strictly via standard linters without specific cyclomatic complexity or custom AST rules. |
| 3 | missing-spec | high | 7/10 | The spec references a 'dbutil' wrapper as mandatory but no specification for its API exists in the provided context. |
| 4 | ambiguity | low | 3/10 | Line count limits are ambiguous regarding whether they count 'Lines of Code' (LOC) or physical lines (including whitespace/comments). |

### Detail + Proposed Corrections

#### 1. [MEDIUM] Spec mandates PascalCase file naming which contradicts standard Go conventions (snake_case), potentially causing friction with existing Go tooling.
- **Category:** drift  |  **Impact:** 5/10
- **Evidence:** AC-01: PascalCase file naming vs standard Go snake_case convention.
- **Proposed correction:** Align PascalCase file naming requirement with standard Go linter expectations or acknowledge linter-scripts as the enforcement mechanism.

#### 2. [MEDIUM] Ban on nested 'if' blocks is difficult to enforce strictly via standard linters without specific cyclomatic complexity or custom AST rules.
- **Category:** untestable  |  **Impact:** 4/10
- **Evidence:** AC-04: Absolute ban on nested if blocks.
- **Proposed correction:** Specify a regex or concrete method for identifying 'indentation level' or 'nested if' beyond a simple line count.

#### 3. [HIGH] The spec references a 'dbutil' wrapper as mandatory but no specification for its API exists in the provided context.
- **Category:** missing-spec  |  **Impact:** 7/10
- **Evidence:** AC-07: Use of 'dbutil' wrappers.
- **Proposed correction:** Define the 'dbutil' types and methods in a dedicated spec file or internal reference.

#### 4. [LOW] Line count limits are ambiguous regarding whether they count 'Lines of Code' (LOC) or physical lines (including whitespace/comments).
- **Category:** ambiguity  |  **Impact:** 3/10
- **Evidence:** AC-03: Function body with more than 15 lines of code.
- **Proposed correction:** Clarify if the 15-line limit includes comments, whitespace, or docstrings.
