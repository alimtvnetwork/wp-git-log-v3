# Audit — `spec/10-research`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview)  
**Weighted Score:** **83/100 (B)**

> The spec is well-structured and maps correctly to the linter scripts in the codebase, but contains a significant internal contradiction between its general naming convention and its specific 'research note' formatting requirements.

---

## 6-Dimension Scores

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Completeness | 25% | 85 | 21.2 |
| Consistency | 25% | 70 | 17.5 |
| Alignment | 20% | 95 | 19.0 |
| Clarity | 15% | 80 | 12.0 |
| Maintainability | 10% | 90 | 9.0 |
| Testability | 5% | 80 | 4.0 |

## Code Mapping

**Implemented by:** `linter-scripts/check-spec-folder-refs.py`, `linter-scripts/check-spec-cross-links.py`, `linter-scripts/check-tree-health.cjs`, `linter-scripts/run.sh`
**Expected but missing:** _(none)_
**Orphan code candidates:** _(none)_

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | ambiguity | medium | 5/10 | AC-RES-000 specifies a 'date prefix' and specific sections not defined in the naming convention (AC-03). |
| 2 | inconsistency | medium | 4/10 | The filename regex allows only 2 digits, which contradicts a standard date prefix (e.g. 2024-01-01). |
| 3 | untestable | low | 3/10 | The module currently contains zero research notes, making AC-RES-000 impossible to verify against the current state. |

### Detail + Proposed Corrections

#### 1. [MEDIUM] AC-RES-000 specifies a 'date prefix' and specific sections not defined in the naming convention (AC-03).
- **Category:** ambiguity  |  **Impact:** 5/10
- **Evidence:** Every research note has a date prefix... and a 'Decision:' or 'Outcome:' section.
- **Proposed correction:** Define exactly which characters are allowed in the prefix and the expected date format (e.g., YYYY-MM-DD).

#### 2. [MEDIUM] The filename regex allows only 2 digits, which contradicts a standard date prefix (e.g. 2024-01-01).
- **Category:** inconsistency  |  **Impact:** 4/10
- **Evidence:** AC-03: ^[0-9]{2}-[a-z0-9-]+\.md$ vs AC-RES-000: Every research note has a date prefix.
- **Proposed correction:** Harmonize the filename regex in AC-03 with the 'date prefix' requirement in AC-RES-000.

#### 3. [LOW] The module currently contains zero research notes, making AC-RES-000 impossible to verify against the current state.
- **Category:** untestable  |  **Impact:** 3/10
- **Evidence:** _No research documents added yet._ vs AC-RES-000: Every research note has... a 'Decision:' or 'Outcome:' section.
- **Proposed correction:** Replace the 'Contents' placeholder with at least one example file or remove the requirement for specific internal sections until content exists.
