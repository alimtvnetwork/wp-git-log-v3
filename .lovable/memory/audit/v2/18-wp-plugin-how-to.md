# Audit v2 — `spec/18-wp-plugin-how-to`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview, 2-pass)  
**Implementability Score:** **90/100 (A)**  
**Blast radius:** 8/10

> This module is very well-structured and highly detailed, providing an excellent blueprint for WordPress plugin development. Its clarity, consistency, and inclusion of key elements like GWT blocks and a consistency report make it a strong candidate for AI-driven implementation, pending the inlining of critical data contracts.


**Score justification:** The module is exceptionally clear with very low waffle and no broken links. It has a high AC count and a consistency report, demonstrating strong testability and maintainability. While most contracts are present, the absence of directly inlined DDL or comprehensive API schemas prevents a perfect implementability score.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 85 | 29.8 |
| Completeness | 20% | 90 | 18.0 |
| Alignment | 15% | 90 | 13.5 |
| Consistency | 10% | 100 | 10.0 |
| Clarity | 10% | 100 | 10.0 |
| Testability | 7% | 80 | 5.6 |
| Maintainability | 3% | 100 | 3.0 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 27,
  "overview_chars": 6330,
  "ac_chars": 3314,
  "ac_count": 5,
  "gwt_block_count": 5,
  "consistency_report": true,
  "code_blocks_total": 441,
  "code_blocks_by_lang": {
    "plain": 99,
    "bash": 7,
    "php": 237,
    "json": 26,
    "markdown": 2,
    "javascript": 23,
    "xml": 1,
    "yaml": 2,
    "typescript": 1,
    "css": 41,
    "html": 1,
    "sql": 1
  },
  "has_sql_ddl": true,
  "has_json_schema": true,
  "has_ts_enums": true,
  "has_yaml_openapi": true,
  "links_total": 112,
  "links_broken": 0,
  "todo_density": 0,
  "waffle_per_kchar": 0.07,
  "child_modules": 1
}
```

## Implementability Blockers

_(none — AI can build this)_

## Code Mapping

**Implemented by:** _(none — pure-doc spec)_
**Expected but missing:** _(none)_
**Orphan code candidates:** `linter-scripts/audit-spec-vs-code-v2.py`, `linter-scripts/audit-spec-vs-code.py`, `linter-scripts/check-axios-version.sh`, `linter-scripts/check-forbidden-spec-paths.sh`, `linter-scripts/check-forbidden-strings.py`, `linter-scripts/check-memory-mirror-drift.py`, `linter-scripts/check-readme-canonicals.py`, `linter-scripts/check-readme-install-section.py`, `linter-scripts/check-root-readme.py`, `linter-scripts/check-spec-cross-links.py`, `linter-scripts/check-spec-folder-refs.py`, `linter-scripts/check-tree-health.cjs`, `linter-scripts/fill-missing-acceptance-criteria.cjs`, `linter-scripts/fill-missing-changelogs.cjs`, `linter-scripts/fill-missing-consistency-reports.cjs`, `linter-scripts/forbidden-strings.toml`, `linter-scripts/generate-dashboard-data.cjs`, `linter-scripts/generate-gwt-acceptance.py`, `linter-scripts/generate-spec-index.cjs`, `linter-scripts/run.ps1`, `linter-scripts/run.sh`, `linter-scripts/suggest-spec-cross-link-fixes.py`, `linter-scripts/validate-guidelines.go`, `linter-scripts/validate-guidelines.py`, `.github/workflows/spec-health.yml`, `src/`

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | missing-contract | medium | 5/10 | While mentions of SQL DDL, JSON Schema, and TypeScript enums are made, the actual inlined DDL or full schemas are not presented in the overview. This forces an AI to infer or look elsewhere for these critical contracts. |
| 2 | ambiguity | low | 2/10 | The 'Overview (first 4500 chars)' section mentions 'AI Confidence: Production-Ready' and 'Ambiguity: None', which is a self-assessment by the spec. While aspirational, this can create a false sense of security and isn't a neutral assessment. |

### Detail + Proposed Corrections

#### 1. [MEDIUM] While mentions of SQL DDL, JSON Schema, and TypeScript enums are made, the actual inlined DDL or full schemas are not presented in the overview. This forces an AI to infer or look elsewhere for these critical contracts.
- **Category:** missing-contract  |  **Impact:** 5/10
- **Evidence:** Overview mentions 'has_sql_ddl: true', 'has_json_schema: true', 'has_ts_enums: true' in deterministic metrics, but the overview content itself doesn't contain these definitions.
- **Proposed correction:** Inline the most critical SQL DDL, JSON Schemas, and TypeScript enum definitions directly within relevant sections of the spec or explicitly link to their definitions within child modules.

#### 2. [LOW] The 'Overview (first 4500 chars)' section mentions 'AI Confidence: Production-Ready' and 'Ambiguity: None', which is a self-assessment by the spec. While aspirational, this can create a false sense of security and isn't a neutral assessment.
- **Category:** ambiguity  |  **Impact:** 2/10
- **Evidence:** Overview section under 'Scoring' declares 'AI Confidence: Production-Ready' and 'Ambiguity: None'.
- **Proposed correction:** Remove or clarify self-assessments of 'AI Confidence' and 'Ambiguity' to avoid suggesting a neutral audit has already been performed by the spec itself. These should be external judgments.
