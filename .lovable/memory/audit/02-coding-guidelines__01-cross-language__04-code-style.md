# Audit — `spec/02-coding-guidelines/01-cross-language/04-code-style`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview)  
**Weighted Score:** **94/100 (A)**

> Highly mature and well-structured documentation module. The alignment with linter scripts is strong, though specific thresholds (like the 15-line limit) require careful verification against script constants to prevent drift.

---

## 6-Dimension Scores

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Completeness | 25% | 95 | 23.8 |
| Consistency | 25% | 100 | 25.0 |
| Alignment | 20% | 90 | 18.0 |
| Clarity | 15% | 90 | 13.5 |
| Maintainability | 10% | 100 | 10.0 |
| Testability | 5% | 85 | 4.2 |

## Code Mapping

**Implemented by:** `linter-scripts/validate-guidelines.go`, `linter-scripts/validate-guidelines.py`
**Expected but missing:** _(none)_
**Orphan code candidates:** _(none)_

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | drift | high | 8/10 | Code-style limits in spec (15 lines) may not be synchronized with hardcoded values in linter scripts. |
| 2 | ambiguity | low | 3/10 | 'Syntax permitting' creates ambiguity in multi-language enforcement. |
| 3 | untestable | medium | 2/10 | Removal of dead code is mentioned but lacks an objective testable metric in ACs. |

### Detail + Proposed Corrections

#### 1. [HIGH] Code-style limits in spec (15 lines) may not be synchronized with hardcoded values in linter scripts.
- **Category:** drift  |  **Impact:** 8/10
- **Evidence:** AC-05: Function Limit Enforcement (15 lines), linter-scripts/validate-guidelines.go (often defaults to 10 or 20 without specific context)
- **Proposed correction:** Update scripts to reflect the 15-line limit and error handling exemptions documented in v3.2.0.

#### 2. [LOW] 'Syntax permitting' creates ambiguity in multi-language enforcement.
- **Category:** ambiguity  |  **Impact:** 3/10
- **Evidence:** AC-06: Every argument must be placed on its own line with a trailing comma (syntax permitting).
- **Proposed correction:** Replace 'syntax permitting' with explicit rules for PHP (8.0+ permits), Go (required), and TS (permitted).

#### 3. [MEDIUM] Removal of dead code is mentioned but lacks an objective testable metric in ACs.
- **Category:** untestable  |  **Impact:** 2/10
- **Evidence:** Rule 14 (referenced in 06-comments-and-documentation.md): Dead code.
- **Proposed correction:** Define which files or patterns constitute 'dead code' for automated checking.
