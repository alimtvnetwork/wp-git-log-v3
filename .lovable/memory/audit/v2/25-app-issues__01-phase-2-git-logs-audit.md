# Audit v2 — `spec/25-app-issues/01-phase-2-git-logs-audit`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview, 2-pass)  
**Implementability Score:** **57/100 (D)**  
**Blast radius:** 8/10

> This module serves as an audit report for another spec, identifying numerous critical gaps. While the audit itself is well-structured and clear, the spec it audits is severely lacking in implementability and completeness.


**Score justification:** The broken link significantly impacts consistency. The lack of SQL DDL (and other contracts) limits implementability. AC count is insufficient for strong testability.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 30 | 10.5 |
| Completeness | 20% | 60 | 12.0 |
| Alignment | 15% | 90 | 13.5 |
| Consistency | 10% | 70 | 7.0 |
| Clarity | 10% | 100 | 10.0 |
| Testability | 7% | 20 | 1.4 |
| Maintainability | 3% | 80 | 2.4 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 4,
  "overview_chars": 26938,
  "ac_chars": 2648,
  "ac_count": 5,
  "gwt_block_count": 5,
  "consistency_report": true,
  "code_blocks_total": 2,
  "code_blocks_by_lang": {
    "bash": 2
  },
  "has_sql_ddl": false,
  "has_json_schema": false,
  "has_ts_enums": false,
  "has_yaml_openapi": false,
  "links_total": 13,
  "links_broken": 1,
  "todo_density": 1,
  "waffle_per_kchar": 0.09,
  "child_modules": 0
}
```

## Implementability Blockers

- No SQL DDL provided for the git-logs v1 database schema.
- No JSON schema for REST endpoint contracts.
- No TypeScript enums for glossary (implied from missing glossary spec).
- No OpenAPI/YAML for REST API definitions.

## Code Mapping

**Implemented by:** `linter-scripts/check-spec-cross-links.py`, `linter-scripts/check-tree-health.cjs`, `linter-scripts/run.sh`
**Expected but missing:** _(none)_
**Orphan code candidates:** _(none)_

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | broken-link | medium | 3/10 | One broken link detected in the spec. |
| 2 | missing-contract | critical | 8/10 | Critical contracts (SQL DDL, JSON schemas for APIs, enums) are missing, making the spec difficult to implement. |
| 3 | untestable | high | 7/10 | Insufficient number of acceptance criteria to ensure comprehensive testability. |
| 4 | missing-spec | critical | 9/10 | Several promised spec files are missing, leading to critical gaps in the overall specification of the `git-logs` app. |

### Detail + Proposed Corrections

#### 1. [MEDIUM] One broken link detected in the spec.
- **Category:** broken-link  |  **Impact:** 3/10
- **Evidence:** links_broken > 0
- **Proposed correction:** Resolve the broken link in the spec to an existing resource.

#### 2. [CRITICAL] Critical contracts (SQL DDL, JSON schemas for APIs, enums) are missing, making the spec difficult to implement.
- **Category:** missing-contract  |  **Impact:** 8/10
- **Evidence:** has_sql_ddl=false, has_json_schema=false, has_ts_enums=false, has_yaml_openapi=false
- **Proposed correction:** Add concrete DDL for database schemas, JSON schemas for all API endpoints, and TypeScript enums where applicable.

#### 3. [HIGH] Insufficient number of acceptance criteria to ensure comprehensive testability.
- **Category:** untestable  |  **Impact:** 7/10
- **Evidence:** ac_count = 5 but should be higher for a complete spec.
- **Proposed correction:** Increase the number and granularity of acceptance criteria to cover all aspects of the module effectively.

#### 4. [CRITICAL] Several promised spec files are missing, leading to critical gaps in the overall specification of the `git-logs` app.
- **Category:** missing-spec  |  **Impact:** 9/10
- **Evidence:** Overview lists 16 content files and 3 governance files, but only a few are actually present.
- **Proposed correction:** Create the missing spec files to address the documented gaps in the `git-logs` v1 specification.
