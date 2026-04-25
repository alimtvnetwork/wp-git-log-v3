# Audit — `spec/02-coding-guidelines/01-cross-language/15-master-coding-guidelines`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview)  
**Weighted Score:** **90/100 (A)**

> The spec is exceptionally well-structured and clear. Minor drift exists between the strict AC (zero-nesting) and the complexity typically allowed in validator scripts, and some linter configuration details (forbidden strings) are missing from the prose.

---

## 6-Dimension Scores

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Completeness | 25% | 90 | 22.5 |
| Consistency | 25% | 95 | 23.8 |
| Alignment | 20% | 85 | 17.0 |
| Clarity | 15% | 95 | 14.2 |
| Maintainability | 10% | 90 | 9.0 |
| Testability | 5% | 80 | 4.0 |

## Code Mapping

**Implemented by:** `linter-scripts/validate-guidelines.go`, `linter-scripts/validate-guidelines.py`, `linter-scripts/forbidden-strings.toml`
**Expected but missing:** _(none)_
**Orphan code candidates:** `linter-scripts/generate-gwt-acceptance.py`

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | drift | high | 8/10 | High risk of drift between the 'master' spec and the implementation of regional linters. |
| 2 | untestable | low | 4/10 | Allowing 'custom Outcome structs' without a naming standard makes automated enforcement difficult. |
| 3 | missing-spec | medium | 5/10 | The forbidden strings configuration exists in code but is not explicitly listed in the spec content. |

### Detail + Proposed Corrections

#### 1. [HIGH] High risk of drift between the 'master' spec and the implementation of regional linters.
- **Category:** drift  |  **Impact:** 8/10
- **Evidence:** AC-05: MUST NOT contain any nested 'if' statements (nesting level 0)
- **Proposed correction:** Ensure all linter scripts (Go/Python) consistently enforce the '0 nested ifs' rule as defined in AC-05.

#### 2. [LOW] Allowing 'custom Outcome structs' without a naming standard makes automated enforcement difficult.
- **Category:** untestable  |  **Impact:** 4/10
- **Evidence:** AC-02: using 'apperror.Result[T]' or a custom 'Outcome' struct
- **Proposed correction:** Specify the exact struct/package name for 'Outcome' or require a specific interface for result types.

#### 3. [MEDIUM] The forbidden strings configuration exists in code but is not explicitly listed in the spec content.
- **Category:** missing-spec  |  **Impact:** 5/10
- **Evidence:** linter-scripts/forbidden-strings.toml
- **Proposed correction:** Add a section in 03-code-style-and-errors.md detailing specific forbidden strings (e.g., 'var', 'any', 'TODO').
