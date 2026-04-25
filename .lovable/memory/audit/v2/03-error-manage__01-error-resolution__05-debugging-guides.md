# Audit v2 — `spec/03-error-manage/01-error-resolution/05-debugging-guides`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview, 2-pass)  
**Implementability Score:** **53/100 (D)**  
**Blast radius:** 5/10

> This module is a decent conceptual guide but severely lacks implementability due to the absence of concrete code, DDL, and fully defined contracts. It serves more as a prose description than an implementable specification.


**Score justification:** Implementability is low because while contracts are inlined, the spec relies on prose-heavy debugging guides rather than concrete, actionable steps or code definitions an AI could directly use. Consistency is capped due to one broken link. Clarity is high but not perfect, given the reliance on textual explanation over strict contracts for many debugging steps.

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
  "md_files": 6,
  "overview_chars": 820,
  "ac_chars": 5631,
  "ac_count": 9,
  "gwt_block_count": 8,
  "consistency_report": true,
  "code_blocks_total": 63,
  "code_blocks_by_lang": {
    "plain": 8,
    "php": 4,
    "go": 19,
    "bash": 7,
    "text": 1,
    "typescript": 23,
    "javascript": 1
  },
  "has_sql_ddl": false,
  "has_json_schema": false,
  "has_ts_enums": true,
  "has_yaml_openapi": false,
  "links_total": 10,
  "links_broken": 1,
  "todo_density": 0,
  "waffle_per_kchar": 0.02,
  "child_modules": 0
}
```

## Implementability Blockers

- No SQL DDL provided (if a database is implied by 'ensure_database_ready()')
- Debugging steps are described in prose, not as executable code or detailed configurations for an AI to implement directly.
- Specific logging configurations (e.g., `zerolog.ConsoleWriter` with `time.RFC3339`) are mentioned but not fully defined in a machine-readable format.
- No explicit contract for `ensure_directories_exist()` or `ensure_database_ready()` beyond their names.
- The scope of 'component-level exception or success event' in AC-02 is vague for an AI.

## Code Mapping

**Implemented by:** _(none — pure-doc spec)_
**Expected but missing:** `src/php/plugin-init.php`, `src/go/cli-service.go`, `src/ts/api-client.ts`
**Orphan code candidates:** `linter-scripts/audit-spec-vs-code-v2.py`, `linter-scripts/audit-spec-vs-code.py`, `linter-scripts/check-axios-version.sh`, `linter-scripts/check-forbidden-spec-paths.sh`, `linter-scripts/check-forbidden-strings.py`, `linter-scripts/check-memory-mirror-drift.py`, `linter-scripts/check-readme-canonicals.py`, `linter-scripts/check-readme-install-section.py`, `linter-scripts/check-root-readme.py`, `linter-scripts/check-spec-cross-links.py`, `linter-scripts/check-spec-folder-refs.py`, `linter-scripts/check-tree-health.cjs`, `linter-scripts/fill-missing-acceptance-criteria.cjs`, `linter-scripts/fill-missing-changelogs.cjs`, `linter-scripts/fill-missing-consistency-reports.cjs`, `linter-scripts/forbidden-strings.toml`, `linter-scripts/generate-dashboard-data.cjs`, `linter-scripts/generate-gwt-acceptance.py`, `linter-scripts/generate-spec-index.cjs`, `linter-scripts/run.ps1`, `linter-scripts/run.sh`, `linter-scripts/suggest-spec-cross-link-fixes.py`, `linter-scripts/validate-guidelines.go`, `linter-scripts/validate-guidelines.py`, `.github/workflows/spec-health.yml`, `src/`

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | broken-link | medium | 3/10 | One broken link detected within the spec module. |
| 2 | missing-contract | high | 8/10 | The spec describes database initialization with `ensure_database_ready()` but lacks SQL DDL. |
| 3 | missing-contract | high | 7/10 | The functions `ensure_directories_exist()` and `ensure_database_ready()` are mentioned but their explicit contracts (parameters, return types, precise behavior) are not defined. |
| 4 | ambiguity | medium | 5/10 | Debugging steps are described in prose, which requires AI inference rather than direct implementation. |
| 5 | missing-spec | critical | 9/10 | There is no actual code implementation for the debugging guides specified, making the module purely theoretical from a coding perspective. |

### Detail + Proposed Corrections

#### 1. [MEDIUM] One broken link detected within the spec module.
- **Category:** broken-link  |  **Impact:** 3/10
- **Evidence:** links_broken: 1
- **Proposed correction:** Identify and correct the broken link to ensure full consistency and navigability.

#### 2. [HIGH] The spec describes database initialization with `ensure_database_ready()` but lacks SQL DDL.
- **Category:** missing-contract  |  **Impact:** 8/10
- **Evidence:** has_sql_ddl: false
- **Proposed correction:** Inline the necessary SQL DDL for database schema definition to fully specify database requirements.

#### 3. [HIGH] The functions `ensure_directories_exist()` and `ensure_database_ready()` are mentioned but their explicit contracts (parameters, return types, precise behavior) are not defined.
- **Category:** missing-contract  |  **Impact:** 7/10
- **Evidence:** PHP Helper Functions section in ACs.
- **Proposed correction:** Add detailed function signatures and explicit contracts for `ensure_directories_exist()` and `ensure_database_ready()`.

#### 4. [MEDIUM] Debugging steps are described in prose, which requires AI inference rather than direct implementation.
- **Category:** ambiguity  |  **Impact:** 5/10
- **Evidence:** Content of 01-debugging-php.md, 02-debugging-go.md, 03-debugging-typescript.md.
- **Proposed correction:** Refactor debugging guides to use more structured code snippets, configuration files, or pseudo-code that an AI can directly translate into an implementation.

#### 5. [CRITICAL] There is no actual code implementation for the debugging guides specified, making the module purely theoretical from a coding perspective.
- **Category:** missing-spec  |  **Impact:** 9/10
- **Evidence:** code_mapping shows no 'implemented_by' files, but expects PHP, Go, and TypeScript debugging code.
- **Proposed correction:** Provide concrete code implementations or detailed configuration examples in the expected languages (PHP, Go, TypeScript) as described in the module.
