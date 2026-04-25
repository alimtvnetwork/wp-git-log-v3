# Audit v2 — `spec/03-error-manage/02-error-architecture/04-error-modal/01-copy-formats`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview, 2-pass)  
**Implementability Score:** **47/100 (D)**  
**Blast radius:** 8/10

> This module is a prose-heavy index that lacks inline contracts and executable specifications, severely hindering AI implementability. It serves as an index but does not provide sufficient detail for a 'mediocre AI' to implement the functionality described without human clarification.


**Score justification:** The implementability is low because there are no contracts, such as DDL, in the overview. The completeness is low because of the missing acceptance criteria. The testability is capped at 20 because ac_count is 0.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 30 | 10.5 |
| Completeness | 20% | 60 | 12.0 |
| Alignment | 15% | 0 | 0.0 |
| Consistency | 10% | 100 | 10.0 |
| Clarity | 10% | 100 | 10.0 |
| Testability | 7% | 20 | 1.4 |
| Maintainability | 3% | 100 | 3.0 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 11,
  "overview_chars": 5375,
  "ac_chars": 0,
  "ac_count": 0,
  "gwt_block_count": 0,
  "consistency_report": true,
  "code_blocks_total": 29,
  "code_blocks_by_lang": {
    "plain": 20,
    "markdown": 3,
    "typescript": 4,
    "json": 2
  },
  "has_sql_ddl": false,
  "has_json_schema": true,
  "has_ts_enums": true,
  "has_yaml_openapi": false,
  "links_total": 31,
  "links_broken": 0,
  "todo_density": 0,
  "waffle_per_kchar": 0.0,
  "child_modules": 0
}
```

## Implementability Blockers

- No explicit contracts (schemas, enums) are inlined in the overview for any of the described copy formats. Although `has_json_schema` and `has_ts_enums` are true, they are not presented in the overview to be immediately consumable by a 'mediocre AI coder'.
- The spec describes files that are not shown in the overview, requiring navigation to understand their content.
- The spec refers to `CapturedError` and `generateCompactReport()`, `generateErrorReport()` functions, but their definitions (types, parameters, return values) are not provided.
- The spec refers to API endpoints like `/api/v1/logs/error` and `/api/v1/logs/full` without providing their full OpenAPI specifications.

## Code Mapping

**Implemented by:** _(none — pure-doc spec)_
**Expected but missing:** `src/components/ErrorModal.tsx`, `src/utils/error-report-generators.ts`, `src/api/logs.ts`
**Orphan code candidates:** `linter-scripts/audit-spec-vs-code-v2.py`, `linter-scripts/audit-spec-vs-code.py`, `linter-scripts/check-axios-version.sh`, `linter-scripts/check-forbidden-spec-paths.sh`, `linter-scripts/check-forbidden-strings.py`, `linter-scripts/check-memory-mirror-drift.py`, `linter-scripts/check-readme-canonicals.py`, `linter-scripts/check-readme-install-section.py`, `linter-scripts/check-root-readme.py`, `linter-scripts/check-spec-cross-links.py`, `linter-scripts/check-spec-folder-refs.py`, `linter-scripts/check-tree-health.cjs`, `linter-scripts/fill-missing-acceptance-criteria.cjs`, `linter-scripts/fill-missing-changelogs.cjs`, `linter-scripts/fill-missing-consistency-reports.cjs`, `linter-scripts/forbidden-strings.toml`, `linter-scripts/generate-dashboard-data.cjs`, `linter-scripts/generate-gwt-acceptance.py`, `linter-scripts/generate-spec-index.cjs`, `linter-scripts/run.ps1`, `linter-scripts/run.sh`, `linter-scripts/suggest-spec-cross-link-fixes.py`, `linter-scripts/validate-guidelines.go`, `linter-scripts/validate-guidelines.py`, `.github/workflows/spec-health.yml`, `src/`

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | missing-contract | critical | 8/10 | No inlined contracts for data structures and API endpoints. |
| 2 | missing-spec | high | 7/10 | Missing Acceptance Criteria. |
| 3 | missing-contract | medium | 5/10 | Undefined functions and their return types. |
| 4 | inconsistency | low | 2/10 | The spec states 'AI Confidence: 95%' but lacks details to justify this. |

### Detail + Proposed Corrections

#### 1. [CRITICAL] No inlined contracts for data structures and API endpoints.
- **Category:** missing-contract  |  **Impact:** 8/10
- **Evidence:** Overview mentions 'CapturedError', API endpoints like `/api/v1/logs/error`, and `Delegated Server Info` without defining their schemas or types.
- **Proposed correction:** Inline JSON schemas for `CapturedError`, `Delegated Server Info`, and OpenAPI specifications for all mentioned API endpoints in the `00-overview.md` or link to them explicitly within the overview.

#### 2. [HIGH] Missing Acceptance Criteria.
- **Category:** missing-spec  |  **Impact:** 7/10
- **Evidence:** The `ac_count` is 0 and the 'Acceptance Criteria' section is explicitly marked as `(MISSING)`.
- **Proposed correction:** Add comprehensive Given/When/Then (GWT) acceptance criteria for each copy and export format, covering success cases, edge cases, and error handling, in the `00-overview.md` file.

#### 3. [MEDIUM] Undefined functions and their return types.
- **Category:** missing-contract  |  **Impact:** 5/10
- **Evidence:** The spec refers to `generateCompactReport()`, `generateErrorReport()` without defining their signatures, parameters, or return types.
- **Proposed correction:** Provide function signatures (in TypeScript, for example) for `generateCompactReport()` and `generateErrorReport()` including their input parameters and expected output types.

#### 4. [LOW] The spec states 'AI Confidence: 95%' but lacks details to justify this.
- **Category:** inconsistency  |  **Impact:** 2/10
- **Evidence:** The `00-overview.md` states 'AI Confidence: 95%', but the implementability score is low due to missing contracts and context.
- **Proposed correction:** Re-evaluate the AI Confidence score based on the actual readiness for AI implementation, or add explicit justifications within the spec for the stated confidence level.
