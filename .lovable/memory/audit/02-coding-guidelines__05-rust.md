# Audit — `spec/02-coding-guidelines/05-rust`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview)  
**Weighted Score:** **48/100 (F)**

> The spec is well-written but describes a non-existent codebase (Time Log CLI) while ignoring the actual codebase (a Large collection of Python/Node/Go linter scripts). It is a 'ghost spec' for a project not currently indexed.

---

## 6-Dimension Scores

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Completeness | 25% | 30 | 7.5 |
| Consistency | 25% | 85 | 21.2 |
| Alignment | 20% | 5 | 1.0 |
| Clarity | 15% | 90 | 13.5 |
| Maintainability | 10% | 80 | 8.0 |
| Testability | 5% | 60 | 3.0 |

## Code Mapping

**Implemented by:** `linter-scripts/validate-guidelines.py`
**Expected but missing:** `src/cli-rust/** (implied by "Time Log CLI")`, `Cargo.toml`
**Orphan code candidates:** _(none)_

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | orphan-spec | high | 7/10 | The spec describes standards for a "Time Log CLI" which is completely absent from the provided code index. |
| 2 | drift | medium | 4/10 | The code contains a Go validator for guidelines, while the spec focuses heavily on Rust standards, suggesting a language mismatch in the tooling layer. |
| 3 | untestable | low | 3/10 | Acceptance criteria AC-04 uses subjective terms ("unnecessary", "large") that cannot be objectively verified. |
| 4 | missing-spec | medium | 6/10 | The code index is composed entirely of maintenance scripts (Python, Node, Bash), but there are no coding guidelines for these languages in the provided spec. |

### Detail + Proposed Corrections

#### 1. [HIGH] The spec describes standards for a "Time Log CLI" which is completely absent from the provided code index.
- **Category:** orphan-spec  |  **Impact:** 7/10
- **Evidence:** Rust-specific coding standards for the Time Log CLI and any future Rust-based projects.
- **Proposed correction:** Include the Time Log CLI Rust source files in the codebase index or remove specific references to it from the spec until it exists.

#### 2. [MEDIUM] The code contains a Go validator for guidelines, while the spec focuses heavily on Rust standards, suggesting a language mismatch in the tooling layer.
- **Category:** drift  |  **Impact:** 4/10
- **Evidence:** linter-scripts/validate-guidelines.go
- **Proposed correction:** Update the spec to include Go guidelines or update the codebase to use Rust for linter validation if that is the intent.

#### 3. [LOW] Acceptance criteria AC-04 uses subjective terms ("unnecessary", "large") that cannot be objectively verified.
- **Category:** untestable  |  **Impact:** 3/10
- **Evidence:** No unnecessary Clone derives on large structs
- **Proposed correction:** Provide concrete quantitative metrics for "unnecessary" or "large".

#### 4. [MEDIUM] The code index is composed entirely of maintenance scripts (Python, Node, Bash), but there are no coding guidelines for these languages in the provided spec.
- **Category:** missing-spec  |  **Impact:** 6/10
- **Evidence:** linter-scripts/check-memory-mirror-drift.py, etc.
- **Proposed correction:** Add documentation for the Python/Bash/Node scripts used in the linter-scripts directory.
