# Audit v2 — `spec/06-seedable-config-architecture/02-features`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview, 2-pass)  
**Implementability Score:** **50/100 (D)**  
**Blast radius:** 5/10

> This module serves primarily as an index for other specification modules. While well-structured internally, its utility for AI implementation is severely limited by the lack of inlined contracts and the fact that the actual feature files it indexes are not present in the code inventory.


**Score justification:** Implementability is low because while DDL and JSON schemas are present in the child modules, this index module itself doesn't inline any contracts. Testability is capped at 20 because ACs are for the module's structure, not its content. Alignment is low because the spec describes files that are not referenced in the code index.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 30 | 10.5 |
| Completeness | 20% | 60 | 12.0 |
| Alignment | 15% | 40 | 6.0 |
| Consistency | 10% | 70 | 7.0 |
| Clarity | 10% | 100 | 10.0 |
| Testability | 7% | 20 | 1.4 |
| Maintainability | 3% | 100 | 3.0 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 10,
  "overview_chars": 972,
  "ac_chars": 2850,
  "ac_count": 5,
  "gwt_block_count": 5,
  "consistency_report": true,
  "code_blocks_total": 45,
  "code_blocks_by_lang": {
    "json": 7,
    "go": 26,
    "plain": 7,
    "sql": 3,
    "bash": 2
  },
  "has_sql_ddl": true,
  "has_json_schema": true,
  "has_ts_enums": false,
  "has_yaml_openapi": false,
  "links_total": 27,
  "links_broken": 1,
  "todo_density": 1,
  "waffle_per_kchar": 0.13,
  "child_modules": 0
}
```

## Implementability Blockers

- No inlined contracts in the index module itself.
- Child module 01-rag-chunk-settings.md is not referenced in the code index.
- Child module 02-rag-validation-helpers.md is not referenced in the code index.
- Child module 03-rag-validation-tests.md is not referenced in the code index.
- Child module 04-rag-test-coverage-matrix.md is not referenced in the code index.
- Child module 05-validation-data-seeding.md is not referenced in the code index.
- Child module 06-update-check-keys.md is not referenced in the code index.

## Code Mapping

**Implemented by:** `linter-scripts/check-spec-cross-links.py`, `linter-scripts/check-tree-health.cjs`, `linter-scripts/run.sh`
**Expected but missing:** `01-rag-chunk-settings.md`, `02-rag-validation-helpers.md`, `03-rag-validation-tests.md`, `04-rag-test-coverage-matrix.md`, `05-validation-data-seeding.md`, `06-update-check-keys.md`
**Orphan code candidates:** `linter-scripts/audit-spec-vs-code-v2.py`, `linter-scripts/audit-spec-vs-code.py`, `linter-scripts/check-axios-version.sh`, `linter-scripts/check-forbidden-spec-paths.sh`, `linter-scripts/check-forbidden-strings.py`, `linter-scripts/check-memory-mirror-drift.py`, `linter-scripts/check-readme-canonicals.py`, `linter-scripts/check-readme-install-section.py`, `linter-scripts/check-root-readme.py`, `linter-scripts/check-spec-folder-refs.py`, `linter-scripts/fill-missing-acceptance-criteria.cjs`, `linter-scripts/fill-missing-changelogs.cjs`, `linter-scripts/fill-missing-consistency-reports.cjs`, `linter-scripts/forbidden-strings.toml`, `linter-scripts/generate-dashboard-data.cjs`, `linter-scripts/generate-gwt-acceptance.py`, `linter-scripts/generate-spec-index.cjs`, `linter-scripts/run.ps1`, `linter-scripts/suggest-spec-cross-link-fixes.py`, `linter-scripts/validate-guidelines.go`, `linter-scripts/validate-guidelines.py`, `.github/workflows/spec-health.yml`, `src/`

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | missing-contract | high | 7/10 | This index module itself does not contain any inlined contracts (DDL, JSON schema, etc.), only links to other modules. |
| 2 | broken-link | medium | 3/10 | One broken link detected. |
| 3 | drift | high | 8/10 | The spec module describes several files that are not present in the provided code index. |
| 4 | untestable | medium | 5/10 | Acceptance criteria primarily validate the module's structure rather than the actual functional content of the features it indexes. |

### Detail + Proposed Corrections

#### 1. [HIGH] This index module itself does not contain any inlined contracts (DDL, JSON schema, etc.), only links to other modules.
- **Category:** missing-contract  |  **Impact:** 7/10
- **Evidence:** has_sql_ddl=true, has_json_schema=true, but these are from child modules, not in this index module itself.
- **Proposed correction:** Inline relevant contracts or excerpts directly into this index module.

#### 2. [MEDIUM] One broken link detected.
- **Category:** broken-link  |  **Impact:** 3/10
- **Evidence:** links_broken > 0
- **Proposed correction:** Fix the broken link in the spec.

#### 3. [HIGH] The spec module describes several files that are not present in the provided code index.
- **Category:** drift  |  **Impact:** 8/10
- **Evidence:** Child modules 01-rag-chunk-settings.md through 06-update-check-keys.md are not found in the code index.
- **Proposed correction:** Either update the spec to reflect the codebase or add the missing code artifacts and ensure they are indexed.

#### 4. [MEDIUM] Acceptance criteria primarily validate the module's structure rather than the actual functional content of the features it indexes.
- **Category:** untestable  |  **Impact:** 5/10
- **Evidence:** ac_count > 0, but the ACs describe the presence of files, headers, and linting, not the features themselves.
- **Proposed correction:** Add acceptance criteria that verify the content and functionality of the features described by this index module, possibly by including GWT blocks for each listed feature.
