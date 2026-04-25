# Audit v2 — `spec/22-git-logs-v2`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview, 2-pass)  
**Implementability Score:** **58/100 (D)**  
**Blast radius:** 7/10

> This spec provides a comprehensive overview but falls short on implementability due to a lack of inline, machine-readable contracts for critical components like database schemas, JSON schemas, and enums. The absence of GWT blocks significantly impacts testability.


**Score justification:** The implementability is low because while it states it has DDL and JSON schemas, they are provided as markdown and not inline, which greatly hinders an AI's ability to directly implement. The testability is capped at 20 due to 'ac_count == 0'.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 45 | 15.8 |
| Completeness | 20% | 90 | 18.0 |
| Alignment | 15% | 0 | 0.0 |
| Consistency | 10% | 100 | 10.0 |
| Clarity | 10% | 100 | 10.0 |
| Testability | 7% | 20 | 1.4 |
| Maintainability | 3% | 100 | 3.0 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 33,
  "overview_chars": 6279,
  "ac_chars": 5902,
  "ac_count": 0,
  "gwt_block_count": 0,
  "consistency_report": true,
  "code_blocks_total": 89,
  "code_blocks_by_lang": {
    "json": 16,
    "text": 2,
    "bash": 12,
    "plain": 35,
    "php": 11,
    "yaml": 4,
    "sql": 1,
    "bats": 8
  },
  "has_sql_ddl": true,
  "has_json_schema": true,
  "has_ts_enums": false,
  "has_yaml_openapi": true,
  "links_total": 45,
  "links_broken": 0,
  "todo_density": 2,
  "waffle_per_kchar": 0.08,
  "child_modules": 0
}
```

## Implementability Blockers

- DDL for database schema is not inlined as code, but as markdown.
- JSON schemas are not inlined as code, but as markdown.
- Enums are documented as markdown tables, not as machine-readable code.

## Code Mapping

**Implemented by:** _(none — pure-doc spec)_
**Expected but missing:** _(none)_
**Orphan code candidates:** `linter-scripts/audit-spec-vs-code-v2.py`, `linter-scripts/audit-spec-vs-code.py`, `linter-scripts/check-axios-version.sh`, `linter-scripts/check-forbidden-spec-paths.sh`, `linter-scripts/check-forbidden-strings.py`, `linter-scripts/check-memory-mirror-drift.py`, `linter-scripts/check-readme-canonicals.py`, `linter-scripts/check-readme-install-section.py`, `linter-scripts/check-root-readme.py`, `linter-scripts/check-spec-cross-links.py`, `linter-scripts/check-spec-folder-refs.py`, `linter-scripts/check-tree-health.cjs`, `linter-scripts/fill-missing-acceptance-criteria.cjs`, `linter-scripts/fill-missing-changelogs.cjs`, `linter-scripts/fill-missing-consistency-reports.cjs`, `linter-scripts/forbidden-strings.toml`, `linter-scripts/generate-dashboard-data.cjs`, `linter-scripts/generate-gwt-acceptance.py`, `linter-scripts/generate-spec-index.cjs`, `linter-scripts/run.ps1`, `linter-scripts/run.sh`, `linter-scripts/suggest-spec-cross-link-fixes.py`, `linter-scripts/validate-guidelines.go`, `linter-scripts/validate-guidelines.py`, `.github/workflows/spec-health.yml`, `src/`

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | missing-contract | high | 8/10 | Database DDL and JSON schemas are described in markdown but not provided as inline code blocks. |
| 2 | missing-contract | medium | 5/10 | Enums are documented as markdown tables, making them harder for an AI to parse and implement directly. |
| 3 | untestable | high | 7/10 | No acceptance criteria are formatted as GWT (Given/When/Then) blocks, severely limiting testability for an AI. |

### Detail + Proposed Corrections

#### 1. [HIGH] Database DDL and JSON schemas are described in markdown but not provided as inline code blocks.
- **Category:** missing-contract  |  **Impact:** 8/10
- **Evidence:** File '02-database-schema.md' describes tables, columns, FKs, indexes but not as directly implementable DDL. 'has_sql_ddl' is true, but the DDL itself is not inline. Similarly for 'has_json_schema'.
- **Proposed correction:** Inline the SQL DDL for '02-database-schema.md' and any JSON schemas as code blocks within the spec, or link directly to machine-readable schema files.

#### 2. [MEDIUM] Enums are documented as markdown tables, making them harder for an AI to parse and implement directly.
- **Category:** missing-contract  |  **Impact:** 5/10
- **Evidence:** File '01-glossary-and-enums.md' contains 'Terms + enum catalog' but 'has_ts_enums' is false, indicating no machine-readable enums.
- **Proposed correction:** Provide enums as machine-readable code (e.g., TypeScript enums, JSON schema enums, or equivalent) instead of or in addition to markdown tables.

#### 3. [HIGH] No acceptance criteria are formatted as GWT (Given/When/Then) blocks, severely limiting testability for an AI.
- **Category:** untestable  |  **Impact:** 7/10
- **Evidence:** 'ac_count' is 0, and 'gwt_block_count' is 0.
- **Proposed correction:** Reformat all acceptance criteria into GWT (Given/When/Then) blocks to make them objectively verifiable.
