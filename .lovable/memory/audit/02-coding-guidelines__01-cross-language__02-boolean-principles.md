# Audit — `spec/02-coding-guidelines/01-cross-language/02-boolean-principles`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview)  
**Weighted Score:** **81/100 (B)**

> The spec is well-structured and internally consistent, but it references a non-existent directory for its primary codegen tool and has a truncated acceptance criterion (AC-07). It effectively bridges DB naming with code logic.

---

## 6-Dimension Scores

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Completeness | 25% | 80 | 20.0 |
| Consistency | 25% | 90 | 22.5 |
| Alignment | 20% | 75 | 15.0 |
| Clarity | 15% | 85 | 12.8 |
| Maintainability | 10% | 85 | 8.5 |
| Testability | 5% | 70 | 3.5 |

## Code Mapping

**Implemented by:** `linter-scripts/validate-guidelines.go`, `linter-scripts/validate-guidelines.py`, `linter-scripts/generate-gwt-acceptance.py`
**Expected but missing:** `linters-cicd/codegen/`
**Orphan code candidates:** `linter-scripts/check-axios-version.sh`, `linter-scripts/check-memory-mirror-drift.py`

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | drift | medium | 4/10 | Spec references a codegen tool path that does not exist in the code index. |
| 2 | missing-spec | low | 3/10 | Implementation details for automated boolean validation are not fully specified. |
| 3 | untestable | low | 2/10 | AC-07 is cut off and contains a typo/incomplete requirement. |
| 4 | ambiguity | medium | 3/10 | The spec mentions auto-generation of traits/methods but provides no schema or template example. |

### Detail + Proposed Corrections

#### 1. [MEDIUM] Spec references a codegen tool path that does not exist in the code index.
- **Category:** drift  |  **Impact:** 4/10
- **Evidence:** `Codegen tool:` [`linters-cicd/codegen/`](../../../../linters-cicd/codegen/README.md)
- **Proposed correction:** Update the codegen path in 00-overview.md to match the actual folder structure (likely linter-scripts or similar).

#### 2. [LOW] Implementation details for automated boolean validation are not fully specified.
- **Category:** missing-spec  |  **Impact:** 3/10
- **Evidence:** linter-scripts/validate-guidelines.go exists but is not explicitly mapped to specific ACs beyond general mentions.
- **Proposed correction:** Document the specific boolean validation logic implemented in validate-guidelines.go/py within the 'Linter' section of the spec.

#### 3. [LOW] AC-07 is cut off and contains a typo/incomplete requirement.
- **Category:** untestable  |  **Impact:** 2/10
- **Evidence:** AC-07: 'The condition must be extracted into a single named boolean with positive inten' (truncated).
- **Proposed correction:** Define what 'positive intent' means for extraction or provide a code example for AC-07.

#### 4. [MEDIUM] The spec mentions auto-generation of traits/methods but provides no schema or template example.
- **Category:** ambiguity  |  **Impact:** 3/10
- **Evidence:** 'emits Go methods, PHP traits... from Is*/Has* db-tagged fields.'
- **Proposed correction:** Explicitly list which PHP traits or Go methods are generated.
