# Audit v2 — `spec/04-database-conventions`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview, 2-pass)  
**Implementability Score:** **80/100 (B)**  
**Blast radius:** 7/10

> This module provides excellent, clear, and comprehensive database conventions with strong testability. However, its implementability for an AI is severely hampered by the complete lack of corresponding code implementations in the provided codebase index, making it a theoretical guide rather than an executable specification. This significantly impacts its overall utility in an AI-driven development workflow.


**Score justification:** The module is exceptionally clear and consistent with good use of code examples. It includes SQL DDL, JSON schema, and TypeScript enums, significantly boosting implementability. The ac_count of 5 demonstrates good testability. However, the alignment score is very low because the spec module defines database conventions but there are no corresponding code implementations or examples in the provided codebase index.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 85 | 29.8 |
| Completeness | 20% | 95 | 19.0 |
| Alignment | 15% | 20 | 3.0 |
| Consistency | 10% | 100 | 10.0 |
| Clarity | 10% | 100 | 10.0 |
| Testability | 7% | 80 | 5.6 |
| Maintainability | 3% | 90 | 2.7 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 11,
  "overview_chars": 6394,
  "ac_chars": 2759,
  "ac_count": 5,
  "gwt_block_count": 5,
  "consistency_report": true,
  "code_blocks_total": 84,
  "code_blocks_by_lang": {
    "bash": 2,
    "sql": 24,
    "go": 16,
    "php": 7,
    "typescript": 4,
    "plain": 21,
    "markdown": 1,
    "json": 9
  },
  "has_sql_ddl": true,
  "has_json_schema": true,
  "has_ts_enums": true,
  "has_yaml_openapi": false,
  "links_total": 66,
  "links_broken": 0,
  "todo_density": 0,
  "waffle_per_kchar": 0.14,
  "child_modules": 0
}
```

## Implementability Blockers

_(none — AI can build this)_

## Code Mapping

**Implemented by:** _(none — pure-doc spec)_
**Expected but missing:** `linter-scripts/check-database-conventions.py`, `db/User.sql`, `db/Project.sql`, `db/Transaction.sql`
**Orphan code candidates:** _(none)_

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | missing-spec | critical | 9/10 | There are no database implementation files (e.g., SQL schema definitions, ORM models) in the provided codebase index that directly correspond to the detailed database conventions specified in this module. This makes it impossible for an AI to see how these conventions are applied in practice. |
| 2 | missing-contract | medium | 5/10 | While the spec mentions 'SQLite first' and 'MySQL as fallback', it does not provide explicit DDL examples or configuration details for both database systems, particularly for MySQL. An AI might struggle to generate compliant MySQL schemas based solely on SQLite examples without explicit guidance on dialect differences. |

### Detail + Proposed Corrections

#### 1. [CRITICAL] There are no database implementation files (e.g., SQL schema definitions, ORM models) in the provided codebase index that directly correspond to the detailed database conventions specified in this module. This makes it impossible for an AI to see how these conventions are applied in practice.
- **Category:** missing-spec  |  **Impact:** 9/10
- **Evidence:** No SQL files, ORM model definitions, or database-related linter-script files are present in the 'ACTUAL CODE IMPLEMENTATION INDEX' section that reference or implement the conventions outlined in spec/04-database-conventions.
- **Proposed correction:** Create concrete SQL schemas, ORM models, and/or database-specific linter tools that demonstrate and enforce these conventions, and include them in the codebase index. For example, add `sql/user.sql` conforming to the specified conventions, or a linter `linter-scripts/check-database-pascalcase.py`.

#### 2. [MEDIUM] While the spec mentions 'SQLite first' and 'MySQL as fallback', it does not provide explicit DDL examples or configuration details for both database systems, particularly for MySQL. An AI might struggle to generate compliant MySQL schemas based solely on SQLite examples without explicit guidance on dialect differences.
- **Category:** missing-contract  |  **Impact:** 5/10
- **Evidence:** The SQL code blocks provided predominantly use SQLite syntax (e.g., AUTOINCREMENT) and do not show equivalent DDL for MySQL.
- **Proposed correction:** Expand SQL DDL examples to include both SQLite and MySQL versions where appropriate, highlighting any dialect-specific syntax or best practices for each.
