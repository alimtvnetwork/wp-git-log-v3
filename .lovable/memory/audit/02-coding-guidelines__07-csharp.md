# Audit — `spec/02-coding-guidelines/07-csharp`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview)  
**Weighted Score:** **64/100 (C)**

> The spec is internally consistent and very well-written, but it describes a C# environment that does not exist in the provided codebase index, resulting in a total lack of alignment.

---

## 6-Dimension Scores

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Completeness | 25% | 95 | 23.8 |
| Consistency | 25% | 95 | 23.8 |
| Alignment | 20% | 0 | 0.0 |
| Clarity | 15% | 90 | 13.5 |
| Maintainability | 10% | 90 | 9.0 |
| Testability | 5% | 60 | 3.0 |

## Code Mapping

**Implemented by:** `linter-scripts/validate-guidelines.py`, `linter-scripts/validate-guidelines.go`
**Expected but missing:** _(none)_
**Orphan code candidates:** _(none)_

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | orphan-spec | high | 8/10 | The spec describes C# coding standards but the code index contains zero C# files (.cs). |
| 2 | ambiguity | medium | 3/10 | Wording implies active C# development, yet the repo only contains Python/Node/Go/Shell scripts. |
| 3 | untestable | low | 4/10 | Criteria like 'Method bodies ≤15 lines' are not enforced by any tooling found in the code index. |

### Detail + Proposed Corrections

#### 1. [HIGH] The spec describes C# coding standards but the code index contains zero C# files (.cs).
- **Category:** orphan-spec  |  **Impact:** 8/10
- **Evidence:** C#-specific coding standards that extend the [cross-language guidelines]... applies to all .NET/C# code
- **Proposed correction:** Provide C# source code examples or link to a C# project managed by these standards.

#### 2. [MEDIUM] Wording implies active C# development, yet the repo only contains Python/Node/Go/Shell scripts.
- **Category:** ambiguity  |  **Impact:** 3/10
- **Evidence:** These rules apply to all .NET/C# code and align with the project's naming conventions
- **Proposed correction:** Explicitly state if these standards are intended for future C# development or apply to external plugins.

#### 3. [LOW] Criteria like 'Method bodies ≤15 lines' are not enforced by any tooling found in the code index.
- **Category:** untestable  |  **Impact:** 4/10
- **Evidence:** All criteria are testable via code review or static analysis.
- **Proposed correction:** Define how standards like 'Method bodies ≤15 lines' are enforced (e.g., via EditorConfig or Roslyn Analyzers).
