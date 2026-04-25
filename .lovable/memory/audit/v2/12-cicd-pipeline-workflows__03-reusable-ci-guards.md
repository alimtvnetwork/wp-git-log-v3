# Audit v2 — `spec/12-cicd-pipeline-workflows/03-reusable-ci-guards`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview, 2-pass)  
**Implementability Score:** **57/100 (D)**  
**Blast radius:** 8/10

> This module is a well-structured conceptual guide for CI guards, but it falls short on implementability for an AI due to the lack of concrete, portable code examples or inline executable contracts. It describes what to build, not how to directly build it for an AI.


**Score justification:** The implementability score is low because the spec describes a conceptual framework without concrete code implementations or DDL. The alignment score is low because the spec describes something that is not present in the provided codebase. Consistency is capped at 70 due to a broken link.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 40 | 14.0 |
| Completeness | 20% | 80 | 16.0 |
| Alignment | 15% | 20 | 3.0 |
| Consistency | 10% | 70 | 7.0 |
| Clarity | 10% | 90 | 9.0 |
| Testability | 7% | 70 | 4.9 |
| Maintainability | 3% | 90 | 2.7 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 14,
  "overview_chars": 5856,
  "ac_chars": 2995,
  "ac_count": 5,
  "gwt_block_count": 5,
  "consistency_report": true,
  "code_blocks_total": 47,
  "code_blocks_by_lang": {
    "plain": 14,
    "bash": 10,
    "go": 1,
    "python": 3,
    "markdown": 1,
    "yaml": 16,
    "json": 2
  },
  "has_sql_ddl": false,
  "has_json_schema": true,
  "has_ts_enums": false,
  "has_yaml_openapi": true,
  "links_total": 53,
  "links_broken": 1,
  "todo_density": 0,
  "waffle_per_kchar": 0.12,
  "child_modules": 0
}
```

## Implementability Blockers

- No concrete code implementations for the described CI guards.
- Lack of DDL for any potential database interactions (though not explicitly stated as needed, often implied by 'baselines').
- Conceptual pseudocode needs to be translated into actual programming language constructs by an AI.

## Code Mapping

**Implemented by:** _(none — pure-doc spec)_
**Expected but missing:** `01-forbidden-name-guard.md`, `02-grandfather-baseline-naming.md`, `03-cross-file-collision-audit.md`, `04-baseline-diff-lint-gate.md`, `05-actionable-lint-suggestions.md`, `06-matrix-test-aggregator.md`, `07-shared-cli-wrapper.md`, `08-config-schema.md`, `09-workflow-templates.md`, `99-ai-implementation-guide.md`
**Orphan code candidates:** `linter-scripts/audit-spec-vs-code-v2.py`, `linter-scripts/audit-spec-vs-code.py`, `linter-scripts/check-axios-version.sh`, `linter-scripts/check-forbidden-spec-paths.sh`, `linter-scripts/check-forbidden-strings.py`, `linter-scripts/check-memory-mirror-drift.py`, `linter-scripts/check-readme-canonicals.py`, `linter-scripts/check-readme-install-section.py`, `linter-scripts/check-root-readme.py`, `linter-scripts/check-spec-cross-links.py`, `linter-scripts/check-spec-folder-refs.py`, `linter-scripts/check-tree-health.cjs`, `linter-scripts/fill-missing-acceptance-criteria.cjs`, `linter-scripts/fill-missing-changelogs.cjs`, `linter-scripts/fill-missing-consistency-reports.cjs`, `linter-scripts/forbidden-strings.toml`, `linter-scripts/generate-dashboard-data.cjs`, `linter-scripts/generate-gwt-acceptance.py`, `linter-scripts/generate-spec-index.cjs`, `linter-scripts/run.ps1`, `linter-scripts/run.sh`, `linter-scripts/suggest-spec-cross-link-fixes.py`, `linter-scripts/validate-guidelines.go`, `linter-scripts/validate-guidelines.py`, `.github/workflows/spec-health.yml`, `src/`

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | broken-link | medium | 5/10 | One broken link detected within the module. |
| 2 | missing-contract | high | 8/10 | The spec describes abstract CI guard patterns but lacks concrete, portable code implementations. |
| 3 | missing-contract | medium | 6/10 | While YAML OpenAPI is present, a full JSON schema for all configuration surfaces is not explicitly declared as inline or directly consumable by an AI. |
| 4 | missing-spec | high | 8/10 | The entire module describes conceptual CI patterns, but no corresponding code exists in the provided codebase index. |

### Detail + Proposed Corrections

#### 1. [MEDIUM] One broken link detected within the module.
- **Category:** broken-link  |  **Impact:** 5/10
- **Evidence:** links_broken > 0
- **Proposed correction:** Fix the broken link in the overview or relevant markdown file.

#### 2. [HIGH] The spec describes abstract CI guard patterns but lacks concrete, portable code implementations.
- **Category:** missing-contract  |  **Impact:** 8/10
- **Evidence:** The overview states: 'The intent is that an AI assistant (or human engineer) reading this folder can re-implement any of the six guards for any repository in any language without ever seeing the original Go-specific source.' However, no such portable code is provided. Only descriptive pseudocode and adaptations.
- **Proposed correction:** Provide concrete, language-agnostic code implementations (e.g., in pseudocode with explicit data structures, or in a generic scripting language) that an AI can directly translate into target languages, rather than just abstract algorithms and problem statements.

#### 3. [MEDIUM] While YAML OpenAPI is present, a full JSON schema for all configuration surfaces is not explicitly declared as inline or directly consumable by an AI.
- **Category:** missing-contract  |  **Impact:** 6/10
- **Evidence:** has_json_schema is true, has_yaml_openapi is true, but `08-config-schema.md` likely describes the schema rather than providing a directly consumable schema file.
- **Proposed correction:** Ensure that the `08-config-schema.md` file contains a directly parsable JSON schema or YAML OpenAPI definition for the `ci-guards.yaml` configuration, rather than just describing it in prose.

#### 4. [HIGH] The entire module describes conceptual CI patterns, but no corresponding code exists in the provided codebase index.
- **Category:** missing-spec  |  **Impact:** 8/10
- **Evidence:** The `expected_but_missing` list contains all the files of this module, implying no actual implementation is present in the `linter-scripts` or `.github/workflows` directories.
- **Proposed correction:** Either provide the actual implementations of these CI guards in the codebase index, or explicitly state that this module is purely theoretical and has no current implementation in the given codebase.
