# Audit v2 — `spec/02-coding-guidelines/01-cross-language/16-static-analysis`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview, 2-pass)  
**Implementability Score:** **62/100 (C)**  
**Blast radius:** 8/10

> This module provides a good overview of static analysis and linter enforcement, but its low implementability stems from a lack of concrete, inlined code contracts for setting up and configuring the mentioned tools. The absence of GWT-formatted acceptance criteria significantly reduces its testability for an AI.


**Score justification:** The implementability is low due to the lack of concrete code contracts and the absence of DDL. Testability is capped at 20 because ac_count is 0. Alignment is impacted by the discrepancy between the spec and codebase.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 40 | 14.0 |
| Completeness | 20% | 80 | 16.0 |
| Alignment | 15% | 60 | 9.0 |
| Consistency | 10% | 100 | 10.0 |
| Clarity | 10% | 90 | 9.0 |
| Testability | 7% | 20 | 1.4 |
| Maintainability | 3% | 80 | 2.4 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 13,
  "overview_chars": 5988,
  "ac_chars": 1024,
  "ac_count": 0,
  "gwt_block_count": 0,
  "consistency_report": true,
  "code_blocks_total": 27,
  "code_blocks_by_lang": {
    "yaml": 5,
    "bash": 6,
    "xml": 3,
    "neon": 1,
    "ini": 4,
    "toml": 4,
    "rust": 1,
    "js": 1,
    "plain": 1,
    "properties": 1
  },
  "has_sql_ddl": false,
  "has_json_schema": false,
  "has_ts_enums": false,
  "has_yaml_openapi": true,
  "links_total": 155,
  "links_broken": 0,
  "todo_density": 2,
  "waffle_per_kchar": 0.22,
  "child_modules": 0
}
```

## Implementability Blockers

- Lack of inline code contracts for linter configurations (e.g., `.eslintrc.js` snippets for ESLint, `golangci-lint` configuration examples)
- Absence of concrete examples for integrating linters into actual build systems or CI/CD pipelines beyond conceptual descriptions.
- Mapping of general guidelines to specific linter rules is present, but detailed configuration for each isn't provided.
- No DDL or JSON schemas for any associated data structures or enforcement results.

## Code Mapping

**Implemented by:** `linter-scripts/validate-guidelines.go`, `linter-scripts/validate-guidelines.py`
**Expected but missing:** `No specific linter configuration files (e.g., .eslintrc.js, .golangci.yml) mapped to code.`, `The spec describes CI pipeline quality gates, but no corresponding CI configuration files (e.g., .github/workflows/linter-quality-gate.yml) are present or referenced.`, `No direct code implementation of the 'Cross-Language Rule Matrix'.`
**Orphan code candidates:** `linter-scripts/audit-spec-vs-code-v2.py`, `linter-scripts/audit-spec-vs-code.py`, `linter-scripts/check-axios-version.sh`, `linter-scripts/check-forbidden-spec-paths.sh`, `linter-scripts/check-forbidden-strings.py`, `linter-scripts/check-memory-mirror-drift.py`, `linter-scripts/check-readme-canonicals.py`, `linter-scripts/check-readme-install-section.py`, `linter-scripts/check-root-readme.py`, `linter-scripts/check-spec-cross-links.py`, `linter-scripts/check-spec-folder-refs.py`, `linter-scripts/check-tree-health.cjs`, `linter-scripts/fill-missing-acceptance-criteria.cjs`, `linter-scripts/fill-missing-changelogs.cjs`, `linter-scripts/fill-missing-consistency-reports.cjs`, `linter-scripts/forbidden-strings.toml`, `linter-scripts/generate-dashboard-data.cjs`, `linter-scripts/generate-gwt-acceptance.py`, `linter-scripts/generate-spec-index.cjs`, `linter-scripts/run.ps1`, `linter-scripts/run.sh`, `linter-scripts/suggest-spec-cross-link-fixes.py`, `.github/workflows/spec-health.yml`, `src/ (all files)`

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | missing-contract | high | 8/10 | The spec lacks concrete, inline code snippets for linter configurations and integration examples. |
| 2 | missing-contract | medium | 5/10 | The CI pipeline quality gate definition is present conceptually, but concrete CI/CD configuration files are missing. |
| 3 | untestable | high | 7/10 | The acceptance criteria (AC) are not written in a 'Given/When/Then' (GWT) format, making them less objectively verifiable for an AI. |
| 4 | drift | medium | 4/10 | The codebase contains numerous 'linter-scripts/' that are not directly referenced or implemented by this spec module. |

### Detail + Proposed Corrections

#### 1. [HIGH] The spec lacks concrete, inline code snippets for linter configurations and integration examples.
- **Category:** missing-contract  |  **Impact:** 8/10
- **Evidence:** The overview mentions linters like ESLint, golangci-lint, and StyleCop, but the spec does not include configurable code contracts for these tools.
- **Proposed correction:** Add inline configuration examples (e.g., '.eslintrc.js', '.golangci.yml', StyleCop configuration files) for each linter used across all supported languages.

#### 2. [MEDIUM] The CI pipeline quality gate definition is present conceptually, but concrete CI/CD configuration files are missing.
- **Category:** missing-contract  |  **Impact:** 5/10
- **Evidence:** The 'SonarQube Quality Gate' section provides a YAML snippet, but there are no corresponding, complete CI/CD configuration files (e.g., for GitHub Actions, GitLab CI) that demonstrate its application.
- **Proposed correction:** Provide full CI/CD pipeline configuration examples (e.g., '.github/workflows/linter-checks.yml', '.gitlab-ci.yml') that integrate the specified quality gate conditions for each language.

#### 3. [HIGH] The acceptance criteria (AC) are not written in a 'Given/When/Then' (GWT) format, making them less objectively verifiable for an AI.
- **Category:** untestable  |  **Impact:** 7/10
- **Evidence:** The '97-acceptance-criteria.md' file lists criteria as bullet points (e.g., 'Every supported language (8) has a dedicated linter spec') rather than structured GWT blocks.
- **Proposed correction:** Rewrite all acceptance criteria into the 'Given/When/Then' format to enable objective, automated verification.

#### 4. [MEDIUM] The codebase contains numerous 'linter-scripts/' that are not directly referenced or implemented by this spec module.
- **Category:** drift  |  **Impact:** 4/10
- **Evidence:** The 'linter-scripts/' directory lists many scripts (e.g., 'check-axios-version.sh', 'check-forbidden-spec-paths.sh') that seem related to linting/validation but are not mentioned in the spec's 'Document Inventory' or 'Tool Selection per Language' sections. Although 'validate-guidelines.go' and 'validate-guidelines.py' are present, the overall scope of 'linter-scripts' is much broader than what the spec outlines as direct implementations.
- **Proposed correction:** Either update the spec to document and integrate all relevant 'linter-scripts', or reclassify unaddressed scripts as outside the scope of this module, with appropriate cross-references if they're covered elsewhere.
