# Audit v2 — `spec/03-error-manage/02-error-architecture`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview, 2-pass)  
**Implementability Score:** **48/100 (D)**  
**Blast radius:** 7/10

> This module outlines a comprehensive error architecture, but its implementability is severely hampered by the absence of critical contracts like SQL DDL and Go interfaces. The lack of alignment with any existing code further limits its utility.


**Score justification:** Implementability is low because while it describes a database, it lacks DDL. Alignment is very low as the spec does not align with any present code. Testability is capped at 20 due to `ac_count` being 0 for module-specific criteria.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 40 | 14.0 |
| Completeness | 20% | 80 | 16.0 |
| Alignment | 15% | 5 | 0.8 |
| Consistency | 10% | 70 | 7.0 |
| Clarity | 10% | 70 | 7.0 |
| Testability | 7% | 20 | 1.4 |
| Maintainability | 3% | 50 | 1.5 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 7,
  "overview_chars": 2427,
  "ac_chars": 2687,
  "ac_count": 5,
  "gwt_block_count": 5,
  "consistency_report": true,
  "code_blocks_total": 27,
  "code_blocks_by_lang": {
    "plain": 6,
    "php": 3,
    "json": 1,
    "go": 8,
    "typescript": 2,
    "tsx": 5,
    "css": 1,
    "bash": 1
  },
  "has_sql_ddl": false,
  "has_json_schema": true,
  "has_ts_enums": true,
  "has_yaml_openapi": false,
  "links_total": 24,
  "links_broken": 1,
  "todo_density": 0,
  "waffle_per_kchar": 0.11,
  "child_modules": 4
}
```

## Implementability Blockers

- No SQL DDL provided for database specifications.
- Lack of explicit contracts (e.g., Go interfaces, concrete types) for the `apperror` package and `DelegatedRequestServer`.

## Code Mapping

**Implemented by:** _(none — pure-doc spec)_
**Expected but missing:** `error-architecture implementation (React, Go, PHP components)`, `error-modal implementation (TS/TSX)`, `response-envelope implementation (Go, Python, etc.)`, `apperror-package implementation (Go)`, `logging-and-diagnostics implementation (React, Go)`
**Orphan code candidates:** `linter-scripts/audit-spec-vs-code-v2.py`, `linter-scripts/audit-spec-vs-code.py`, `linter-scripts/check-axios-version.sh`, `linter-scripts/check-forbidden-spec-paths.sh`, `linter-scripts/check-forbidden-strings.py`, `linter-scripts/check-memory-mirror-drift.py`, `linter-scripts/check-readme-canonicals.py`, `linter-scripts/check-readme-install-section.py`, `linter-scripts/check-root-readme.py`, `linter-scripts/check-spec-cross-links.py`, `linter-scripts/check-spec-folder-refs.py`, `linter-scripts/check-tree-health.cjs`, `linter-scripts/fill-missing-acceptance-criteria.cjs`, `linter-scripts/fill-missing-changelogs.cjs`, `linter-scripts/fill-missing-consistency-reports.cjs`, `linter-scripts/forbidden-strings.toml`, `linter-scripts/generate-dashboard-data.cjs`, `linter-scripts/generate-gwt-acceptance.py`, `linter-scripts/generate-spec-index.cjs`, `linter-scripts/run.ps1`, `linter-scripts/run.sh`, `linter-scripts/suggest-spec-cross-link-fixes.py`, `linter-scripts/validate-guidelines.go`, `linter-scripts/validate-guidelines.py`, `.github/workflows/spec-health.yml`, `src/`

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | missing-contract | high | 8/10 | The spec describes a database, but no SQL DDL is provided. |
| 2 | missing-contract | high | 7/10 | Go `apperror` package and `DelegatedRequestServer` lack explicit interfaces or concrete type definitions. |
| 3 | broken-link | medium | 3/10 | One broken link detected in the module. |
| 4 | inconsistency | low | 2/10 | While `ac_count` is 5, the acceptance criteria for module-specific logic is not in the GWT format. |

### Detail + Proposed Corrections

#### 1. [HIGH] The spec describes a database, but no SQL DDL is provided.
- **Category:** missing-contract  |  **Impact:** 8/10
- **Evidence:** has_sql_ddl: false, while the purpose statement mentions 'cross-stack error handling' and 'database'
- **Proposed correction:** Provide explicit SQL DDL for any described database components.

#### 2. [HIGH] Go `apperror` package and `DelegatedRequestServer` lack explicit interfaces or concrete type definitions.
- **Category:** missing-contract  |  **Impact:** 7/10
- **Evidence:** Section '06-apperror-package/' and '02-go-delegation-fix.md' describe Go components, but no Go code contracts are inlined.
- **Proposed correction:** Include Go interface definitions and concrete type structures for `apperror` and `DelegatedRequestServer`.

#### 3. [MEDIUM] One broken link detected in the module.
- **Category:** broken-link  |  **Impact:** 3/10
- **Evidence:** links_broken: 1
- **Proposed correction:** Identify and fix the broken link within the module.

#### 4. [LOW] While `ac_count` is 5, the acceptance criteria for module-specific logic is not in the GWT format.
- **Category:** inconsistency  |  **Impact:** 2/10
- **Evidence:** ac_count: 5, but module-specific ACs are prose, not GWT blocks.
- **Proposed correction:** Rewrite module-specific acceptance criteria in the Given/When/Then (GWT) format for improved testability and clarity.
