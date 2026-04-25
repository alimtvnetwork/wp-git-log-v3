# Audit v2 — `spec/02-coding-guidelines/03-golang/04-golang-standards-reference`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview, 2-pass)  
**Implementability Score:** **53/100 (D)**  
**Blast radius:** 8/10

> This module is a good descriptive guide for Go coding standards, but it lacks the necessary inline code contracts to enable a mediocre AI to implement these standards without human intervention. Its primary implementation is a linter, but the spec itself is not fully implementable by an AI.


**Score justification:** Implementability is low because the spec is descriptive but lacks inline contracts (like full enum definitions or database schemas) that would allow an AI to implement it without human help. Alignment is 0 as it describes coding standards but doesn't map to concrete code in the provided index, instead mapping to a linter. Consistency is capped at 70 due to a broken link.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 40 | 14.0 |
| Completeness | 20% | 80 | 16.0 |
| Alignment | 15% | 0 | 0.0 |
| Consistency | 10% | 70 | 7.0 |
| Clarity | 10% | 90 | 9.0 |
| Testability | 7% | 70 | 4.9 |
| Maintainability | 3% | 80 | 2.4 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 9,
  "overview_chars": 2742,
  "ac_chars": 4955,
  "ac_count": 10,
  "gwt_block_count": 9,
  "consistency_report": true,
  "code_blocks_total": 44,
  "code_blocks_by_lang": {
    "go": 40,
    "plain": 4
  },
  "has_sql_ddl": false,
  "has_json_schema": false,
  "has_ts_enums": false,
  "has_yaml_openapi": false,
  "links_total": 35,
  "links_broken": 1,
  "todo_density": 0,
  "waffle_per_kchar": 0.1,
  "child_modules": 0
}
```

## Implementability Blockers

- Full Go enum definition (code) for AC-09 is missing.
- Complete 'apperror' package interface (code) or detailed schema with methods is not inlined.
- Go database structs and 'dbutil' wrapper interface definitions are not provided.
- Specific regular expressions or examples for naming conventions (e.g., file suffixes) are descriptive but not in a machine-readable contract format.

## Code Mapping

**Implemented by:** `linter-scripts/validate-guidelines.go`
**Expected but missing:** _(none)_
**Orphan code candidates:** `linter-scripts/audit-spec-vs-code-v2.py`, `linter-scripts/audit-spec-vs-code.py`, `linter-scripts/check-axios-version.sh`, `linter-scripts/check-forbidden-spec-paths.sh`, `linter-scripts/check-forbidden-strings.py`, `linter-scripts/check-memory-mirror-drift.py`, `linter-scripts/check-readme-canonicals.py`, `linter-scripts/check-readme-install-section.py`, `linter-scripts/check-root-readme.py`, `linter-scripts/check-spec-cross-links.py`, `linter-scripts/check-spec-folder-refs.py`, `linter-scripts/check-tree-health.cjs`, `linter-scripts/fill-missing-acceptance-criteria.cjs`, `linter-scripts/fill-missing-changelogs.cjs`, `linter-scripts/fill-missing-consistency-reports.cjs`, `linter-scripts/forbidden-strings.toml`, `linter-scripts/generate-dashboard-data.cjs`, `linter-scripts/generate-gwt-acceptance.py`, `linter-scripts/generate-spec-index.cjs`, `linter-scripts/run.ps1`, `linter-scripts/run.sh`, `linter-scripts/suggest-spec-cross-link-fixes.py`, `linter-scripts/validate-guidelines.py`, `.github/workflows/spec-health.yml`, `src/`

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | missing-contract | high | 8/10 | Missing concrete Go code for apperror package interfaces and apperror.Result methods. |
| 2 | missing-contract | high | 7/10 | Lack of full Go enum definitions for AC-09. |
| 3 | missing-contract | high | 6/10 | No Go struct definitions or interfaces for 'dbutil' types. |
| 4 | broken-link | medium | 3/10 | One broken link detected in the spec module. |
| 5 | missing-contract | medium | 4/10 | Descriptive naming conventions lack machine-readable contract. |

### Detail + Proposed Corrections

#### 1. [HIGH] Missing concrete Go code for apperror package interfaces and apperror.Result methods.
- **Category:** missing-contract  |  **Impact:** 8/10
- **Evidence:** AC-06: Mandatory apperror usage for services acknowledges 'apperror.Result Methods' but only lists method names, not full Go interfaces or concrete implementations.
- **Proposed correction:** Inline the Go interfaces and method signatures for apperror.Result and *apperror.AppError, including their constituent types and full method definitions.

#### 2. [HIGH] Lack of full Go enum definitions for AC-09.
- **Category:** missing-contract  |  **Impact:** 7/10
- **Evidence:** AC-09: Enum naming and guard standards mentions 'enum implementation with variantLabels' but provides no concrete Go enum definition.
- **Proposed correction:** Provide a complete Go enum example with its underlying type, methods, and variant labels as a code block. Reference the 'Enum Specification' cross-link directly within the AC if the full spec is there.

#### 3. [HIGH] No Go struct definitions or interfaces for 'dbutil' types.
- **Category:** missing-contract  |  **Impact:** 6/10
- **Evidence:** AC-06 and 'Inlined Contracts' mentions 'dbutil Types: Result[T], ResultSet[T], ExecResult' but these are not defined with Go code.
- **Proposed correction:** Inline the Go struct and interface definitions for dbutil.Result, dbutil.ResultSet, and dbutil.ExecResult, including their methods and field types.

#### 4. [MEDIUM] One broken link detected in the spec module.
- **Category:** broken-link  |  **Impact:** 3/10
- **Evidence:** links_broken: 1
- **Proposed correction:** Fix the broken link in the Cross-References or Document Inventory sections.

#### 5. [MEDIUM] Descriptive naming conventions lack machine-readable contract.
- **Category:** missing-contract  |  **Impact:** 4/10
- **Evidence:** The spec describes file suffixes ('_crud.go', '_helpers.go'), case conventions (PascalCase filenames), but without regular expressions or other machine-verifiable contracts.
- **Proposed correction:** For naming conventions, provide concrete regular expressions or a formal grammar that an AI can use to validate or generate names.
