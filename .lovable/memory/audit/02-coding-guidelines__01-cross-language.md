# Audit — `spec/02-coding-guidelines/01-cross-language`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview)  
**Weighted Score:** **76/100 (B)**

> A high-quality set of structural guidelines with good linter support, but suffers from inconsistencies between the overview table and the actual file inventory (specifically missing subfolders).

---

## 6-Dimension Scores

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Completeness | 25% | 75 | 18.8 |
| Consistency | 25% | 70 | 17.5 |
| Alignment | 20% | 85 | 17.0 |
| Clarity | 15% | 80 | 12.0 |
| Maintainability | 10% | 85 | 8.5 |
| Testability | 5% | 60 | 3.0 |

## Code Mapping

**Implemented by:** `linter-scripts/validate-guidelines.go`, `linter-scripts/validate-guidelines.py`, `linter-scripts/forbidden-strings.toml`
**Expected but missing:** _(none)_
**Orphan code candidates:** `linter-scripts/audit-spec-vs-code.py`, `linter-scripts/check-forbidden-spec-paths.sh`

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | orphan-spec | high | 7/10 | The spec claims linter rules are documented, but provides no specific config paths or rule names. |
| 2 | ambiguity | medium | 4/10 | No conflict resolution policy if a language-specific spec contradicts this global spec. |
| 3 | drift | low | 3/10 | AC references file 03 (casting elimination) but the module focuses heavily on naming/formatting; depth of 03 is unknown without implementation details. |
| 4 | inconsistency | medium | 5/10 | The Overview references a subfolder (04-code-style) that does not appear in the file inventory. |

### Detail + Proposed Corrections

#### 1. [HIGH] The spec claims linter rules are documented, but provides no specific config paths or rule names.
- **Category:** orphan-spec  |  **Impact:** 7/10
- **Evidence:** AC-02: ESLint/linter rules are documented for automated enforcement
- **Proposed correction:** Assign specific linter rule mappings (e.g., ESlint, PHPStan) to each file in this module.

#### 2. [MEDIUM] No conflict resolution policy if a language-specific spec contradicts this global spec.
- **Category:** ambiguity  |  **Impact:** 4/10
- **Evidence:** Language-specific specs reference these as the single source of truth.
- **Proposed correction:** Define the specific project tiers or labels where these 'cross-language' rules take precedence over language-specific ones.

#### 3. [LOW] AC references file 03 (casting elimination) but the module focuses heavily on naming/formatting; depth of 03 is unknown without implementation details.
- **Category:** drift  |  **Impact:** 3/10
- **Evidence:** AC-01: Casting elimination patterns cover type-safe alternatives to type assertions
- **Proposed correction:** Remove mention of 'casting elimination patterns' from AC or provide the missing 03-casting-elimination-patterns.md file content.

#### 4. [MEDIUM] The Overview references a subfolder (04-code-style) that does not appear in the file inventory.
- **Category:** inconsistency  |  **Impact:** 5/10
- **Evidence:** Overview table lists #04 code-style subfolder, but file inventory only shows 21-newline-styling-examples.md.
- **Proposed correction:** Update the overview table to list file 04 as existing or clarify if it is external to this module.
