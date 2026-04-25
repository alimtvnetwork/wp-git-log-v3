# Audit — `spec/02-coding-guidelines/06-ai-optimization`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview)  
**Weighted Score:** **83/100 (B)**

> The spec is highly detailed and structurally sound as a documentation module, but it fails to acknowledge the existing automated linting tools (Go/Python) that implement the rules it describes. It treats the rules as purely for AI prompting rather than integrated CI/CD enforcement.

---

## 6-Dimension Scores

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Completeness | 25% | 90 | 22.5 |
| Consistency | 25% | 90 | 22.5 |
| Alignment | 20% | 75 | 15.0 |
| Clarity | 15% | 85 | 12.8 |
| Maintainability | 10% | 80 | 8.0 |
| Testability | 5% | 70 | 3.5 |

## Code Mapping

**Implemented by:** `linter-scripts/validate-guidelines.go`, `linter-scripts/check-forbidden-strings.py`, `linter-scripts/forbidden-strings.toml`
**Expected but missing:** _(none)_
**Orphan code candidates:** _(none)_

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | drift | high | 7/10 | The codebase contains active linter scripts (Go/Python) that enforce coding guidelines, but the spec refers to them as theoretical AI instructions. |
| 2 | missing-spec | medium | 5/10 | The forbidden strings configuration exists in code but is not mapped to the AH rules in the spec. |
| 3 | untestable | low | 3/10 | Acceptance Criteria for 'machine-parsable' is vague and lacks a schema or format requirement. |

### Detail + Proposed Corrections

#### 1. [HIGH] The codebase contains active linter scripts (Go/Python) that enforce coding guidelines, but the spec refers to them as theoretical AI instructions.
- **Category:** drift  |  **Impact:** 7/10
- **Evidence:** linter-scripts/validate-guidelines.go and linter-scripts/check-forbidden-strings.py
- **Proposed correction:** Update the spec to include Go as a primary validation language and reference specific linter scripts that enforce these rules.

#### 2. [MEDIUM] The forbidden strings configuration exists in code but is not mapped to the AH rules in the spec.
- **Category:** missing-spec  |  **Impact:** 5/10
- **Evidence:** linter-scripts/forbidden-strings.toml
- **Proposed correction:** Add documentation for forbidden-strings.toml and how it maps to the Anti-Hallucination rules.

#### 3. [LOW] Acceptance Criteria for 'machine-parsable' is vague and lacks a schema or format requirement.
- **Category:** untestable  |  **Impact:** 3/10
- **Evidence:** Quick-reference checklist is machine-parsable with checkboxes
- **Proposed correction:** Define what makes the checklist 'machine-parsable' (JSON, YAML, or specific Markdown format).
