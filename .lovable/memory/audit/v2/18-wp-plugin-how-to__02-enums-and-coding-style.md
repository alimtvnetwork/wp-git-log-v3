# Audit v2 — `spec/18-wp-plugin-how-to/02-enums-and-coding-style`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview, 2-pass)  
**Implementability Score:** **66/100 (C)**  
**Blast radius:** 6/10

> This module provides a good foundation for PHP enum patterns with clear examples and well-defined acceptance criteria, but suffers from a critical lack of inline enum definitions, a broken link, and misalignment with the codebase.


**Score justification:** The implementability score is capped at 70 because while code examples are provided, explicit PHP enum definitions are not fully inlined for all cases, forcing an AI to infer or copy-paste from other files. The consistency score is capped due to the presence of a broken link. Testability suffers because AC-05 relies on an external script with a numeric threshold, making it less directly verifiable from the spec alone.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 70 | 24.5 |
| Completeness | 20% | 90 | 18.0 |
| Alignment | 15% | 0 | 0.0 |
| Consistency | 10% | 70 | 7.0 |
| Clarity | 10% | 90 | 9.0 |
| Testability | 7% | 70 | 4.9 |
| Maintainability | 3% | 100 | 3.0 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 8,
  "overview_chars": 2088,
  "ac_chars": 2759,
  "ac_count": 5,
  "gwt_block_count": 5,
  "consistency_report": true,
  "code_blocks_total": 21,
  "code_blocks_by_lang": {
    "php": 12,
    "plain": 8,
    "bash": 1
  },
  "has_sql_ddl": false,
  "has_json_schema": false,
  "has_ts_enums": false,
  "has_yaml_openapi": false,
  "links_total": 27,
  "links_broken": 1,
  "todo_density": 0,
  "waffle_per_kchar": 0.19,
  "child_modules": 0
}
```

## Implementability Blockers

- Full PHP enum definitions are not inlined for all enum types (e.g., SelfUpdateStatusType, ActionType), requiring an AI to cross-reference or infer the complete structure.
- No explicit PHP interfaces or abstract classes are defined for the enum architecture, leaving abstraction to inference.
- The file inventory doesn't explicitly state the expected content structure of every '.md' file, beyond H1 and version banner, which risks an AI producing a less complete implementation.

## Code Mapping

**Implemented by:** _(none — pure-doc spec)_
**Expected but missing:** _(none)_
**Orphan code candidates:** `linter-scripts/audit-spec-vs-code-v2.py`, `linter-scripts/audit-spec-vs-code.py`, `linter-scripts/check-axios-version.sh`, `linter-scripts/check-forbidden-spec-paths.sh`, `linter-scripts/check-forbidden-strings.py`, `linter-scripts/check-memory-mirror-drift.py`, `linter-scripts/check-readme-canonicals.py`, `linter-scripts/check-readme-install-section.py`, `linter-scripts/check-root-readme.py`, `linter-scripts/check-spec-cross-links.py`, `linter-scripts/check-spec-folder-refs.py`, `linter-scripts/check-tree-health.cjs`, `linter-scripts/fill-missing-acceptance-criteria.cjs`, `linter-scripts/fill-missing-changelogs.cjs`, `linter-scripts/fill-missing-consistency-reports.cjs`, `linter-scripts/forbidden-strings.toml`, `linter-scripts/generate-dashboard-data.cjs`, `linter-scripts/generate-gwt-acceptance.py`, `linter-scripts/generate-spec-index.cjs`, `linter-scripts/run.ps1`, `linter-scripts/run.sh`, `linter-scripts/suggest-spec-cross-link-fixes.py`, `linter-scripts/validate-guidelines.go`, `linter-scripts/validate-guidelines.py`, `.github/workflows/spec-health.yml`, `src/`

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | broken-link | medium | 5/10 | A broken link was identified in the overview section. |
| 2 | missing-contract | high | 7/10 | The spec describes enum patterns but does not inline complete PHP enum definitions for all listed enums. |
| 3 | untestable | medium | 4/10 | AC-05 relies on an external script and a hardcoded numeric threshold, making it less directly verifiable from the spec content alone. |

### Detail + Proposed Corrections

#### 1. [MEDIUM] A broken link was identified in the overview section.
- **Category:** broken-link  |  **Impact:** 5/10
- **Evidence:** links_broken > 0
- **Proposed correction:** Update the broken link in '00-overview.md' to a valid target.

#### 2. [HIGH] The spec describes enum patterns but does not inline complete PHP enum definitions for all listed enums.
- **Category:** missing-contract  |  **Impact:** 7/10
- **Evidence:** The spec references `SelfUpdateStatusType` and `ActionType` but only provides templates, not full implementations.
- **Proposed correction:** Inline the full PHP enum definitions for `SelfUpdateStatusType` and `ActionType` directly within the spec, including all cases and standard methods.

#### 3. [MEDIUM] AC-05 relies on an external script and a hardcoded numeric threshold, making it less directly verifiable from the spec content alone.
- **Category:** untestable  |  **Impact:** 4/10
- **Evidence:** AC-05 states 'this module contributes required=2/2... and the overall score is ≥ 80', referencing 'linter-scripts/check-tree-health.cjs'.
- **Proposed correction:** Refactor AC-05 to focus on verifiable aspects directly within the module, or provide a clear, inlined definition of how the 'tree-health gate' score is calculated to make it self-contained for testing.
