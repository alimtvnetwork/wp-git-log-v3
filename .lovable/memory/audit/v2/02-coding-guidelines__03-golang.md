# Audit v2 — `spec/02-coding-guidelines/03-golang`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview, 2-pass)  
**Implementability Score:** **51/100 (D)**  
**Blast radius:** 7/10

> This module provides general Go coding guidelines but lacks the concrete, executable examples and formal contract definitions necessary for an AI to independently implement these standards. The complete absence of relevant Go code in the provided index severely impacts its alignment and implementability.


**Score justification:** The implementability is low because the spec describes coding standards but doesn't provide concrete, executable examples or code snippets for each rule. Testability is capped at 20 due to the absence of GWT blocks. Alignment is very low because the spec describes Go coding standards, yet the code index contains only linter scripts and no actual Go code that would implement these standards.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 40 | 14.0 |
| Completeness | 20% | 60 | 12.0 |
| Alignment | 15% | 5 | 0.8 |
| Consistency | 10% | 100 | 10.0 |
| Clarity | 10% | 100 | 10.0 |
| Testability | 7% | 20 | 1.4 |
| Maintainability | 3% | 100 | 3.0 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 11,
  "overview_chars": 1170,
  "ac_chars": 632,
  "ac_count": 2,
  "gwt_block_count": 0,
  "consistency_report": true,
  "code_blocks_total": 45,
  "code_blocks_by_lang": {
    "go": 43,
    "plain": 2
  },
  "has_sql_ddl": false,
  "has_json_schema": false,
  "has_ts_enums": false,
  "has_yaml_openapi": false,
  "links_total": 19,
  "links_broken": 0,
  "todo_density": 0,
  "waffle_per_kchar": 0.19,
  "child_modules": 2
}
```

## Implementability Blockers

- Lack of concrete code examples for each standard
- Missing formal contracts (e.g., Go interfaces, structs with field types) for described patterns like `apperror.Result[T]`

## Code Mapping

**Implemented by:** _(none — pure-doc spec)_
**Expected but missing:** `Any actual Go code implementing these guidelines.`
**Orphan code candidates:** `linter-scripts/audit-spec-vs-code-v2.py`, `linter-scripts/audit-spec-vs-code.py`, `linter-scripts/check-axios-version.sh`, `linter-scripts/check-forbidden-spec-paths.sh`, `linter-scripts/check-forbidden-strings.py`, `linter-scripts/check-memory-mirror-drift.py`, `linter-scripts/check-readme-canonicals.py`, `linter-scripts/check-readme-install-section.py`, `linter-scripts/check-root-readme.py`, `linter-scripts/check-spec-cross-links.py`, `linter-scripts/check-spec-folder-refs.py`, `linter-scripts/check-tree-health.cjs`, `linter-scripts/fill-missing-acceptance-criteria.cjs`, `linter-scripts/fill-missing-changelogs.cjs`, `linter-scripts/fill-missing-consistency-reports.cjs`, `linter-scripts/forbidden-strings.toml`, `linter-scripts/generate-dashboard-data.cjs`, `linter-scripts/generate-gwt-acceptance.py`, `linter-scripts/generate-spec-index.cjs`, `linter-scripts/run.ps1`, `linter-scripts/run.sh`, `linter-scripts/suggest-spec-cross-link-fixes.py`, `linter-scripts/validate-guidelines.go`, `linter-scripts/validate-guidelines.py`, `.github/workflows/spec-health.yml`, `src/`

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | missing-contract | high | 8/10 | The spec describes coding standards like `apperror.Result[T]` but does not provide the Go struct or interface definitions for these. An AI would struggle to implement these without concrete types. |
| 2 | ambiguity | medium | 6/10 | While naming conventions are mentioned, the specific rules for 'Go idioms (exported/unexported, acronym casing)' are not fully enumerated with examples, leaving room for interpretation. |
| 3 | missing-spec | high | 8/10 | The spec broadly states 'Service layer follows interface-based dependency injection' and 'Database access uses repository pattern with prepared statements' but lacks detailed Go examples or structural diagrams for these architectural patterns. |
| 4 | drift | high | 9/10 | The spec outlines Go coding standards, yet the provided code inventory does not contain any Go code that directly implements these standards, only linter scripts. |

### Detail + Proposed Corrections

#### 1. [HIGH] The spec describes coding standards like `apperror.Result[T]` but does not provide the Go struct or interface definitions for these. An AI would struggle to implement these without concrete types.
- **Category:** missing-contract  |  **Impact:** 8/10
- **Evidence:** AC-01: 'Error handling uses `apperror.Result[T]` pattern consistently'
- **Proposed correction:** Add full Go code snippets for `apperror.Result[T]` including generic type definitions, and any related interfaces or structs.

#### 2. [MEDIUM] While naming conventions are mentioned, the specific rules for 'Go idioms (exported/unexported, acronym casing)' are not fully enumerated with examples, leaving room for interpretation.
- **Category:** ambiguity  |  **Impact:** 6/10
- **Evidence:** AC-01: 'Naming conventions follow Go idioms (exported/unexported, acronym casing)'
- **Proposed correction:** Expand on Go naming conventions with explicit rules and code examples for exported/unexported identifiers and acronym casing.

#### 3. [HIGH] The spec broadly states 'Service layer follows interface-based dependency injection' and 'Database access uses repository pattern with prepared statements' but lacks detailed Go examples or structural diagrams for these architectural patterns.
- **Category:** missing-spec  |  **Impact:** 8/10
- **Evidence:** AC-02: 'Service layer follows interface-based dependency injection', 'Database access uses repository pattern with prepared statements'
- **Proposed correction:** Include detailed Go code examples or architectural diagrams demonstrating interface-based dependency injection and the repository pattern.

#### 4. [HIGH] The spec outlines Go coding standards, yet the provided code inventory does not contain any Go code that directly implements these standards, only linter scripts.
- **Category:** drift  |  **Impact:** 9/10
- **Evidence:** The code index lists only linter scripts and no application-level Go code.
- **Proposed correction:** Provide a relevant Go codebase or update the spec to reflect its function as a set of guidelines for a *separate* Go codebase.
