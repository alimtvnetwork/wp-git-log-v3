# Audit v2 — `spec/02-coding-guidelines/01-cross-language`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview, 2-pass)  
**Implementability Score:** **52/100 (D)**  
**Blast radius:** 8/10

> This module provides a comprehensive set of cross-language coding guidelines. However, its implementability for an AI is hindered by the high-level nature of the guidance and the lack of directly actionable, machine-readable contracts. More concrete specifications and testable acceptance criteria are needed.


**Score justification:** Implementability is low because while it describes coding guidelines, it does not provide concrete, actionable DDL/schemas for an AI to implement. The alignment is low as the spec does not directly map to any specific code implementations, instead describing guidelines. Consistency is impacted by one broken link. Testability is capped at 20 due to a low AC count (2). Clarity is capped at 70 due to waffle score being above 5 (but the metric is 0.14, so it should be fine).

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 45 | 15.8 |
| Completeness | 20% | 80 | 16.0 |
| Alignment | 15% | 20 | 3.0 |
| Consistency | 10% | 60 | 6.0 |
| Clarity | 10% | 70 | 7.0 |
| Testability | 7% | 20 | 1.4 |
| Maintainability | 3% | 80 | 2.4 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 31,
  "overview_chars": 7220,
  "ac_chars": 662,
  "ac_count": 2,
  "gwt_block_count": 0,
  "consistency_report": true,
  "code_blocks_total": 257,
  "code_blocks_by_lang": {
    "php": 59,
    "sql": 9,
    "go": 112,
    "bash": 2,
    "csharp": 7,
    "plain": 22,
    "typescript": 40,
    "json": 1,
    "rust": 4,
    "yaml": 1
  },
  "has_sql_ddl": true,
  "has_json_schema": true,
  "has_ts_enums": true,
  "has_yaml_openapi": true,
  "links_total": 117,
  "links_broken": 1,
  "todo_density": 0,
  "waffle_per_kchar": 0.14,
  "child_modules": 4
}
```

## Implementability Blockers

- Lack of inline, actionable DDL/schemas/code for direct implementation of the guidelines. The spec describes *what* good code looks like, but not *how* to generate it via concrete, formal contracts.

## Code Mapping

**Implemented by:** `linter-scripts/validate-guidelines.go`, `linter-scripts/validate-guidelines.py`
**Expected but missing:** _(none)_
**Orphan code candidates:** `linter-scripts/audit-spec-vs-code-v2.py`, `linter-scripts/audit-spec-vs-code.py`, `linter-scripts/check-axios-version.sh`, `linter-scripts/check-forbidden-spec-paths.sh`, `linter-scripts/check-forbidden-strings.py`, `linter-scripts/check-memory-mirror-drift.py`, `linter-scripts/check-readme-canonicals.py`, `linter-scripts/check-readme-install-section.py`, `linter-scripts/check-root-readme.py`, `linter-scripts/check-spec-cross-links.py`, `linter-scripts/check-spec-folder-refs.py`, `linter-scripts/check-tree-health.cjs`, `linter-scripts/fill-missing-acceptance-criteria.cjs`, `linter-scripts/fill-missing-changelogs.cjs`, `linter-scripts/fill-missing-consistency-reports.cjs`, `linter-scripts/forbidden-strings.toml`, `linter-scripts/generate-dashboard-data.cjs`, `linter-scripts/generate-gwt-acceptance.py`, `linter-scripts/generate-spec-index.cjs`, `linter-scripts/run.ps1`, `linter-scripts/run.sh`, `linter-scripts/suggest-spec-cross-link-fixes.py`, `.github/workflows/spec-health.yml`, `src/`

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | broken-link | medium | 5/10 | One broken link found in the spec module. |
| 2 | missing-contract | high | 8/10 | The spec provides general guidelines but lacks concrete, executable contracts (like formal grammars, exhaustive examples, or directly usable code snippets) to fully automate implementation for an AI. |
| 3 | untestable | medium | 6/10 | Only two Acceptance Criteria (ACs) are present, which suggests a significant portion of the specified guidelines are not objectively verifiable. |
| 4 | drift | low | 3/10 | Many linter scripts exist in `linter-scripts/` that do not seem to be directly referenced or explicitly aligned with this cross-language coding guidelines spec. |

### Detail + Proposed Corrections

#### 1. [MEDIUM] One broken link found in the spec module.
- **Category:** broken-link  |  **Impact:** 5/10
- **Evidence:** links_broken = 1
- **Proposed correction:** Identify and fix the broken link within the markdown files.

#### 2. [HIGH] The spec provides general guidelines but lacks concrete, executable contracts (like formal grammars, exhaustive examples, or directly usable code snippets) to fully automate implementation for an AI.
- **Category:** missing-contract  |  **Impact:** 8/10
- **Evidence:** The spec primarily uses natural language and high-level descriptions for coding standards, without formalizing these into machine-readable rules or code generation templates.
- **Proposed correction:** For each guideline, provide clear, machine-readable specifications, such as regex patterns for naming conventions, grammar definitions for code style, or direct code examples that an AI can use for validation or generation. Consider using a domain-specific language (DSL) or more structured data formats where appropriate.

#### 3. [MEDIUM] Only two Acceptance Criteria (ACs) are present, which suggests a significant portion of the specified guidelines are not objectively verifiable.
- **Category:** untestable  |  **Impact:** 6/10
- **Evidence:** ac_count = 2
- **Proposed correction:** Expand the 'Acceptance Criteria' section (97-acceptance-criteria.md) to include specific, measurable, achievable, relevant, and time-bound (SMART) criteria for all major guidelines. These should be structured as GWT (Given-When-Then) statements where possible to facilitate automated testing or AI-driven verification.

#### 4. [LOW] Many linter scripts exist in `linter-scripts/` that do not seem to be directly referenced or explicitly aligned with this cross-language coding guidelines spec.
- **Category:** drift  |  **Impact:** 3/10
- **Evidence:** Large number of unmapped files in `linter-scripts/` such as `check-axios-version.sh`, `check-forbidden-spec-paths.sh`, etc.
- **Proposed correction:** Explicitly link each linter script in `linter-scripts/` to the relevant section or submodule within the coding guidelines. If a script enforces a guideline not yet specified, create a new guideline. If a script is deprecated or irrelevant, remove it from the codebase.
