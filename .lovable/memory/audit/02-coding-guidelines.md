# Audit — `spec/02-coding-guidelines`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview)  
**Weighted Score:** **88/100 (A)**

> A high-quality, authoritative spec with clear rules. The main risk is the 'PascalCase for JSON' mandate which likely drifts from real-world API consumers, and the lack of explicit connection to the validation scripts in the codebase.

---

## 6-Dimension Scores

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Completeness | 25% | 85 | 21.2 |
| Consistency | 25% | 85 | 21.2 |
| Alignment | 20% | 90 | 18.0 |
| Clarity | 15% | 90 | 13.5 |
| Maintainability | 10% | 95 | 9.5 |
| Testability | 5% | 80 | 4.0 |

## Code Mapping

**Implemented by:** `linter-scripts/validate-guidelines.go`, `linter-scripts/validate-guidelines.py`, `linter-scripts/forbidden-strings.toml`
**Expected but missing:** _(none)_
**Orphan code candidates:** _(none)_

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | drift | medium | 7/10 | The spec mandates PascalCase for JSON keys in TypeScript/PHP/Go, which contradicts standard web/API practices (camelCase/snake_case for JSON). |
| 2 | untestable | low | 4/10 | Function metric limits are specified generally but lack language-specific nuances (e.g., PHP/C# class methods). |
| 3 | ambiguity | low | 2/10 | The hybrid naming section is slightly confusing regarding whether Rust PascalCase for types is a project override or just acknowledging the standard. |
| 4 | missing-spec | medium | 5/10 | The code contains automated validators for these guidelines, but the spec does not reference the automated enforcement tools. |

### Detail + Proposed Corrections

#### 1. [MEDIUM] The spec mandates PascalCase for JSON keys in TypeScript/PHP/Go, which contradicts standard web/API practices (camelCase/snake_case for JSON).
- **Category:** drift  |  **Impact:** 7/10
- **Evidence:** KEY INSIGHT: Go, TypeScript, PHP... PascalCase is the DEFAULT for identifiers, keys, JSON.
- **Proposed correction:** Update documentation to confirm that PascalCase is enforced for JSON keys in TS/Go/PHP, as the current text implies a hybrid but then asserts PascalCase for JSON in those languages.

#### 2. [LOW] Function metric limits are specified generally but lack language-specific nuances (e.g., PHP/C# class methods).
- **Category:** untestable  |  **Impact:** 4/10
- **Evidence:** 6. Function metrics — Functions: 8–15 lines. Files: < 300 lines. React components: < 100 lines.
- **Proposed correction:** Define specific line count thresholds for PHP and C# or clarify if the 'Function metrics' rule applies globally across all non-Rust languages.

#### 3. [LOW] The hybrid naming section is slightly confusing regarding whether Rust PascalCase for types is a project override or just acknowledging the standard.
- **Category:** ambiguity  |  **Impact:** 2/10
- **Evidence:** Types/Enums → PascalCase: struct BrowserActivity (Rust standard)
- **Proposed correction:** Explicitly state that Rust types/enums use PascalCase AS PER COMMUNITY STANDARD, rather than framing it as a project-specific mandate that happens to align.

#### 4. [MEDIUM] The code contains automated validators for these guidelines, but the spec does not reference the automated enforcement tools.
- **Category:** missing-spec  |  **Impact:** 5/10
- **Evidence:** linter-scripts/validate-guidelines.go exists in the file index.
- **Proposed correction:** Add a section detailing how linter-scripts/validate-guidelines.go enforces these rules.
