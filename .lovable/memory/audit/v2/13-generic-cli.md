# Audit v2 — `spec/13-generic-cli`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview, 2-pass)  
**Implementability Score:** **56/100 (D)**  
**Blast radius:** 8/10

> This spec provides a comprehensive set of guidelines for building a generic CLI. However, its implementability by an AI is severely hampered by the lack of concrete, executable contracts and the absence of a corresponding codebase.


**Score justification:** Implementability is low because the spec provides guidelines and examples, but not executable contracts or full DDL for the database. Alignment is 0 as there is no code that implements the generic CLI in the provided codebase index. Consistency is capped at 70 due to a broken link.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 45 | 15.8 |
| Completeness | 20% | 80 | 16.0 |
| Alignment | 15% | 0 | 0.0 |
| Consistency | 10% | 70 | 7.0 |
| Clarity | 10% | 90 | 9.0 |
| Testability | 7% | 80 | 5.6 |
| Maintainability | 3% | 90 | 2.7 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 24,
  "overview_chars": 5356,
  "ac_chars": 3117,
  "ac_count": 5,
  "gwt_block_count": 5,
  "consistency_report": true,
  "code_blocks_total": 155,
  "code_blocks_by_lang": {
    "bash": 3,
    "plain": 66,
    "go": 76,
    "json": 2,
    "markdown": 1,
    "sql": 4,
    "powershell": 2,
    "fish": 1
  },
  "has_sql_ddl": true,
  "has_json_schema": true,
  "has_ts_enums": false,
  "has_yaml_openapi": false,
  "links_total": 94,
  "links_broken": 1,
  "todo_density": 0,
  "waffle_per_kchar": 0.08,
  "child_modules": 0
}
```

## Implementability Blockers

- No executable code or contracts provided for the generic CLI, only guidelines and examples that require manual translation.
- Database DDL is provided, but it's not inline and would require manual extraction and execution (assuming the sql code blocks are meant for DDL).
- Missing concrete schemas/enums for data structures (e.g., configuration, output formats).

## Code Mapping

**Implemented by:** _(none — pure-doc spec)_
**Expected but missing:** _(none)_
**Orphan code candidates:** _(none)_

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | broken-link | medium | 5/10 | One broken link found in the spec. |
| 2 | missing-contract | high | 8/10 | The spec lacks inline, executable contracts for key components like configuration schemas, output formats, and error structures. |

### Detail + Proposed Corrections

#### 1. [MEDIUM] One broken link found in the spec.
- **Category:** broken-link  |  **Impact:** 5/10
- **Evidence:** links_broken = 1
- **Proposed correction:** Fix the broken link in the specification.

#### 2. [HIGH] The spec lacks inline, executable contracts for key components like configuration schemas, output formats, and error structures.
- **Category:** missing-contract  |  **Impact:** 8/10
- **Evidence:** The spec provides prose descriptions and Go examples, but no language-agnostic, machine-readable contracts (e.g., JSON schema, protobuf, DDL with create statements inlined).
- **Proposed correction:** For all data structures (configuration, API responses, database schemas), provide executable contracts (e.g., JSON Schema, SQL DDL with CREATE statements, Protobuf definitions) directly within the spec, rather than relying on Go code examples to convey the structure.
