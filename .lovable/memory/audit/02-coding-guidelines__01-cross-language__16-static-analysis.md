# Audit — `spec/02-coding-guidelines/01-cross-language/16-static-analysis`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview)  
**Weighted Score:** **72/100 (C)**

> The specification is exceptionally well-structured as a documentation module but fails as a technical specification because the described linter configurations (ESLint, Ruff, StyleCop, etc.) are entirely absent from the code index, which only contains maintenance scripts for the specs themselves.

---

## 6-Dimension Scores

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Completeness | 25% | 85 | 21.2 |
| Consistency | 25% | 90 | 22.5 |
| Alignment | 20% | 40 | 8.0 |
| Clarity | 15% | 90 | 13.5 |
| Maintainability | 10% | 80 | 8.0 |
| Testability | 5% | 70 | 3.5 |

## Code Mapping

**Implemented by:** `linter-scripts/validate-guidelines.go`, `linter-scripts/validate-guidelines.py`, `.github/workflows/spec-health.yml`
**Expected but missing:** `golangci-lint.yml`, `.eslintrc.json`, `.phpcs.xml`, `stylecop.json`, `.clippy.toml`, `pyproject.toml`, `sonarqube-config.qualitygate`
**Orphan code candidates:** `linter-scripts/audit-spec-vs-code.py`, `linter-scripts/check-memory-mirror-drift.py`

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | orphan-spec | critical | 8/10 | The spec describes extensive linter configurations for 8 languages, but the code index only contains meta-scripts for spec health. |
| 2 | drift | low | 3/10 | External reference to TypeScript spec is mentioned but not visible in the provided context/index. |
| 3 | untestable | medium | 5/10 | Acceptance criteria focus on the presence of documentation rather than the correctness of the automation scripts. |
| 4 | missing-spec | medium | 4/10 | The code contains custom guideline validators (Go/Python) not explicitly detailed in the static analysis spec. |

### Detail + Proposed Corrections

#### 1. [CRITICAL] The spec describes extensive linter configurations for 8 languages, but the code index only contains meta-scripts for spec health.
- **Category:** orphan-spec  |  **Impact:** 8/10
- **Evidence:** Every guideline that can be machine-enforced MUST have a corresponding linter rule. (Overview §Purpose)
- **Proposed correction:** Provide actual configuration files (e.g., .eslintrc, golangci.yml) for the tools described in the spec.

#### 2. [LOW] External reference to TypeScript spec is mentioned but not visible in the provided context/index.
- **Category:** drift  |  **Impact:** 3/10
- **Evidence:** 01 | [11-eslint-enforcement.md](../../02-typescript/11-eslint-enforcement.md) | TypeScript | ESLint
- **Proposed correction:** Remove reference to external TypeScript ESLint spec or ensure it is tracked in the inventory.

#### 3. [MEDIUM] Acceptance criteria focus on the presence of documentation rather than the correctness of the automation scripts.
- **Category:** untestable  |  **Impact:** 5/10
- **Evidence:** Every supported language (8) has a dedicated linter spec
- **Proposed correction:** Update AC to include specific checkable file paths for configurations.

#### 4. [MEDIUM] The code contains custom guideline validators (Go/Python) not explicitly detailed in the static analysis spec.
- **Category:** missing-spec  |  **Impact:** 4/10
- **Evidence:** linter-scripts/validate-guidelines.go
- **Proposed correction:** Add documentation for the custom validation logic found in linter-scripts/.
