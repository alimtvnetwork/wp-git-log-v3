# Audit v2 — `spec/25-app-issues/02-consolidated-audit-findings`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview, 2-pass)  
**Implementability Score:** **65/100 (C)**  
**Blast radius:** 7/10

> This module provides a good overview of findings, but its implementability is severely hampered by missing API contracts and unresolved cryptographic contradictions. Significant effort is required to define explicit schemas and resolve ambiguities to enable AI-driven implementation.


**Score justification:** The low implementability score is primarily due to the lack of inline contracts, specifically the absence of SQL DDL for database specifications referenced in the document. The completeness is impacted by the numerous findings regarding missing API endpoint definitions and cryptographic contradictions. Consistency is affected by the 14 broken links.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 40 | 14.0 |
| Completeness | 20% | 60 | 12.0 |
| Alignment | 15% | 90 | 13.5 |
| Consistency | 10% | 70 | 7.0 |
| Clarity | 10% | 100 | 10.0 |
| Testability | 7% | 80 | 5.6 |
| Maintainability | 3% | 90 | 2.7 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 4,
  "overview_chars": 28968,
  "ac_chars": 2688,
  "ac_count": 5,
  "gwt_block_count": 5,
  "consistency_report": true,
  "code_blocks_total": 23,
  "code_blocks_by_lang": {
    "plain": 21,
    "bash": 2
  },
  "has_sql_ddl": false,
  "has_json_schema": false,
  "has_ts_enums": false,
  "has_yaml_openapi": false,
  "links_total": 32,
  "links_broken": 14,
  "todo_density": 0,
  "waffle_per_kchar": 0.03,
  "child_modules": 0
}
```

## Implementability Blockers

- No SQL DDL provided for database schemas mentioned (e.g., `LogSenderTokenVerifier` in F-02)
- Missing API endpoint definitions (request/response schemas, parameter validation, HTTP status codes, etc.) as detailed in F-01
- Unresolved cryptographic contradiction (F-02) requiring a choice between two implementation paths with different schema implications

## Code Mapping

**Implemented by:** `linter-scripts/check-spec-cross-links.py`, `linter-scripts/check-tree-health.cjs`, `linter-scripts/run.sh`
**Expected but missing:** `spec/_archive/21-git-logs-v1/04-rest-api-endpoints.md`
**Orphan code candidates:** _(none)_

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | missing-contract | critical | 8/10 | REST endpoint contracts are not consolidated and the referenced file is missing. |
| 2 | ambiguity | critical | 9/10 | Cryptographic contradiction acknowledged but unresolved, leading to incompatible schema implementations. |
| 3 | broken-link | high | 5/10 | Numerous broken links within the document. |
| 4 | missing-spec | medium | 6/10 | Missing data shapes and type definitions for several fields referenced in the document implicitly. |

### Detail + Proposed Corrections

#### 1. [CRITICAL] REST endpoint contracts are not consolidated and the referenced file is missing.
- **Category:** missing-contract  |  **Impact:** 8/10
- **Evidence:** F-01 findings, specifically 'The inventory promises `04-rest-api-endpoints.md`, but `ls spec/_archive/21-git-logs-v1/` shows the file does not exist.'
- **Proposed correction:** Author `04-rest-api-endpoints.md` with full API contract details (HTTP method, auth, schemas, error envelopes, rate limits, audit events).

#### 2. [CRITICAL] Cryptographic contradiction acknowledged but unresolved, leading to incompatible schema implementations.
- **Category:** ambiguity  |  **Impact:** 9/10
- **Evidence:** F-02 findings, specifically referencing `02-database-schema-and-erd.md` showing only `LogSenderTokenHash` while narrative introduces `LogSenderTokenVerifier`.
- **Proposed correction:** Decide on a single cryptographic mechanism and update `02-database-schema-and-erd.md` with the corresponding definitive schema (e.g., add `LogSenderTokenVerifier VARBINARY(255) NOT NULL` if AEAD path is chosen).

#### 3. [HIGH] Numerous broken links within the document.
- **Category:** broken-link  |  **Impact:** 5/10
- **Evidence:** Deterministic metrics show `links_broken: 14`.
- **Proposed correction:** Resolve all 14 broken links by updating references or ensuring target files exist.

#### 4. [MEDIUM] Missing data shapes and type definitions for several fields referenced in the document implicitly.
- **Category:** missing-spec  |  **Impact:** 6/10
- **Evidence:** The overall nature of errors F-01 and F-02 implies data and type definitions like `PipelineName field type` are missing for a complete implementation.
- **Proposed correction:** Ensure all referenced data structures and types have explicit definitions, ideally in a centralized schema or inlined with their first mention.
