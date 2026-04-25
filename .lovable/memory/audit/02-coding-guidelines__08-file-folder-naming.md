# Audit — `spec/02-coding-guidelines/08-file-folder-naming`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview)  
**Weighted Score:** **88/100 (A)**

> The spec is highly structured and provides clear cross-language guidance that aligns with the presence of 'validate-guidelines' scripts in the codebase. The main risk is the logical contradiction between 'Universal' lowercase rules and 'C#' PascalCase rules, which needs clearer hierarchical precedence.

---

## 6-Dimension Scores

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Completeness | 25% | 90 | 22.5 |
| Consistency | 25% | 80 | 20.0 |
| Alignment | 20% | 90 | 18.0 |
| Clarity | 15% | 85 | 12.8 |
| Maintainability | 10% | 95 | 9.5 |
| Testability | 5% | 90 | 4.5 |

## Code Mapping

**Implemented by:** `linter-scripts/validate-guidelines.go`, `linter-scripts/validate-guidelines.py`, `linter-scripts/check-tree-health.cjs`
**Expected but missing:** _(none)_
**Orphan code candidates:** _(none)_

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | untestable | low | 3/10 | The AC for Go folder naming is vague regarding underscores vs hyphens. |
| 2 | ambiguity | medium | 5/10 | Contradiction between Universal rules and Language-specific rules. |
| 3 | missing-spec | low | 2/10 | Missing specific naming conventions for non-executable asset files. |

### Detail + Proposed Corrections

#### 1. [LOW] The AC for Go folder naming is vague regarding underscores vs hyphens.
- **Category:** untestable  |  **Impact:** 3/10
- **Evidence:** 00-overview.md: "Go ... Folders: lowercase (no hyphens)" vs "Examples: internal/"
- **Proposed correction:** Define specific verifiable examples of Go package naming (e.g., 'no underscores permitted in package names' vs filenames) to make the criterion objective.

#### 2. [MEDIUM] Contradiction between Universal rules and Language-specific rules.
- **Category:** ambiguity  |  **Impact:** 5/10
- **Evidence:** 00-overview.md Quick Reference: Universal (lowercase) vs C# (PascalCase).
- **Proposed correction:** Specify if 'Universal' rules (lowercase, no spaces) are enforced by linters even when language-specific rules (PascalCase) contradict them.

#### 3. [LOW] Missing specific naming conventions for non-executable asset files.
- **Category:** missing-spec  |  **Impact:** 2/10
- **Evidence:** Project contains .yml, .json, and .md files globally.
- **Proposed correction:** Specify naming rules for JSON, YAML, and MarkDown files which appear in the project but aren't explicitly detailed in the language-specific files.
