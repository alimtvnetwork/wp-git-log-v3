# Audit v2 — `spec/22-git-logs-v2`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview, 2-pass)  
**Implementability Score:** **54/100 (D)**  
**Blast radius:** 8/10

> This module provides a very comprehensive overview with good consistency and minimal waffle. However, the lack of machine-readable contracts for enums and JSON schemas, and critically, the complete absence of formal acceptance criteria, significantly hinder implementability and testability. The module also suffers from a complete misalignment with the provided code index.


**Score justification:** The implementability score is low because while SQL DDL is present, critical components like enums and all JSON schemas are missing. Testability is severely impacted by the complete absence of acceptance criteria (ac_count=0). Alignment is 0 as the spec does not align with any of the provided code.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 45 | 15.8 |
| Completeness | 20% | 80 | 16.0 |
| Alignment | 15% | 0 | 0.0 |
| Consistency | 10% | 100 | 10.0 |
| Clarity | 10% | 90 | 9.0 |
| Testability | 7% | 20 | 1.4 |
| Maintainability | 3% | 70 | 2.1 |

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

- Missing enum definitions in a machine-readable format (e.g., TypeScript or equivalent).
- Missing complete JSON schemas for all request/response bodies, as the spec only mentions has_json_schema: true but doesn't inline them or point to a comprehensive machine-readable definition.
- The OpenAPI spec (17-openapi.yaml) is referenced but not inlined, which would increase implementability.
- No concrete examples of AppLink polymorphism with CHECK constraints.
- DB table prefix not specified for SQLite (though 'none' is mentioned, concrete DDL should reflect this consistently).
- Many fields and data types are mentioned in prose (e.g., 'GeneratedKeyApi', 'Token', 'TempToken') but lack explicit type definitions or constraints beyond their names.

## Code Mapping

**Implemented by:** _(none — pure-doc spec)_
**Expected but missing:** _(none)_
**Orphan code candidates:** `linter-scripts/audit-spec-vs-code-v2.py`, `linter-scripts/audit-spec-vs-code.py`, `linter-scripts/check-axios-version.sh`, `linter-scripts/check-forbidden-spec-paths.sh`, `linter-scripts/check-forbidden-strings.py`, `linter-scripts/check-memory-mirror-drift.py`, `linter-scripts/check-readme-canonicals.py`, `linter-scripts/check-readme-install-section.py`, `linter-scripts/check-root-readme.py`, `linter-scripts/check-spec-cross-links.py`, `linter-scripts/check-spec-folder-refs.py`, `linter-scripts/check-tree-health.cjs`, `linter-scripts/fill-missing-acceptance-criteria.cjs`, `linter-scripts/fill-missing-changelogs.cjs`, `linter-scripts/fill-missing-consistency-reports.cjs`, `linter-scripts/forbidden-strings.toml`, `linter-scripts/generate-dashboard-data.cjs`, `linter-scripts/generate-gwt-acceptance.py`, `linter-scripts/generate-spec-index.cjs`, `linter-scripts/run.ps1`, `linter-scripts/run.sh`, `linter-scripts/suggest-spec-cross-link-fixes.py`, `linter-scripts/validate-guidelines.go`, `linter-scripts/validate-guidelines.py`, `.github/workflows/spec-health.yml`, `src/`

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | missing-contract | high | 8/10 | Enums are mentioned but not defined concretely in a machine-readable format. |
| 2 | missing-contract | high | 7/10 | Complete JSON schemas for all request/response bodies are missing. |
| 3 | ambiguity | medium | 5/10 | Despite having 'has_sql_ddl': true, a potential ambiguity exists around SQLite's dynamic typing behavior and explicit column constraints. |
| 4 | untestable | critical | 10/10 | The spec states 'ac_count: 0', meaning no measurable acceptance criteria are defined. |
| 5 | missing-contract | medium | 6/10 | The OpenAPI 3.1 spec (17-openapi.yaml) is referenced but not inlined, forcing implementers to look elsewhere. |

### Detail + Proposed Corrections

#### 1. [HIGH] Enums are mentioned but not defined concretely in a machine-readable format.
- **Category:** missing-contract  |  **Impact:** 8/10
- **Evidence:** 01-glossary-and-enums.md mentions enums like AppStatus, but the actual enum definitions (e.g., TypeScript, JSON) are not provided or inlined. The Deterministic metrics report has_ts_enums: false.
- **Proposed correction:** Add TypeScript or JSON definitions for all enums in 01-glossary-and-enums.md or a new dedicated file, referencing them from relevant sections.

#### 2. [HIGH] Complete JSON schemas for all request/response bodies are missing.
- **Category:** missing-contract  |  **Impact:** 7/10
- **Evidence:** The spec mentions 04-rest-api-endpoints.md and 14-endpoint-examples.md for request/response shapes and examples, and has_json_schema: true, but the full machine-readable JSON schemas are not inlined or provided in a single, comprehensive location.
- **Proposed correction:** Inline the full JSON schemas for all API endpoints within 04-rest-api-endpoints.md or provide a dedicated, referenced file with all schemas.

#### 3. [MEDIUM] Despite having 'has_sql_ddl': true, a potential ambiguity exists around SQLite's dynamic typing behavior and explicit column constraints.
- **Category:** ambiguity  |  **Impact:** 5/10
- **Evidence:** 02-database-schema.md and 18-schema.sql contain DDL, but without explicit type constraints (e.g., `CHECK (type IN ('value1', 'value2'))`) for string-based 'enums' or other domain-specific types, AI implementation could interpret these loosely.
- **Proposed correction:** Augment DDL in 02-database-schema.md and 18-schema.sql with explicit `CHECK` constraints for all columns that have restricted values, especially those representing enums or other limited sets.

#### 4. [CRITICAL] The spec states 'ac_count: 0', meaning no measurable acceptance criteria are defined.
- **Category:** untestable  |  **Impact:** 10/10
- **Evidence:** Deterministic metrics report ac_count: 0.
- **Proposed correction:** Refactor 97-acceptance-criteria.md to include concrete, verifiable Given/When/Then (GWT) scenarios for all critical functionalities. Aim for at least one AC per key feature described.

#### 5. [MEDIUM] The OpenAPI 3.1 spec (17-openapi.yaml) is referenced but not inlined, forcing implementers to look elsewhere.
- **Category:** missing-contract  |  **Impact:** 6/10
- **Evidence:** Document Inventory lists 17-openapi.yaml but the content is not in this module. While referenced, an AI coder would need to be pointed to or given this file separately.
- **Proposed correction:** Inline the content of 17-openapi.yaml into the spec module, ideally within 04-rest-api-endpoints.md or as a dedicated, inlined subsection, or provide a direct link to its content.
