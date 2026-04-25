# Audit v2 — `spec/02-coding-guidelines/01-cross-language/15-master-coding-guidelines`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview, 2-pass)  
**Implementability Score:** **59/100 (D)**  
**Blast radius:** 7/10

> This module provides general coding guidelines but lacks the concrete, inlined contracts necessary for a mediocre AI to implement them without human intervention. While it has good clarity and maintainability, significant improvements are needed in implementability and completeness to be truly AI-ready.


**Score justification:** The implementability is low because critical contracts like SQL DDL and JSON schemas are missing. Alignment is low because the spec describes coding guidelines, but the code index only shows linters, not the actual implementations. Consistency is impacted by a broken link. Testability is capped at 20 due to ac_count being 9 and not having clear GWT blocks.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 40 | 14.0 |
| Completeness | 20% | 80 | 16.0 |
| Alignment | 15% | 50 | 7.5 |
| Consistency | 10% | 70 | 7.0 |
| Clarity | 10% | 100 | 10.0 |
| Testability | 7% | 20 | 1.4 |
| Maintainability | 3% | 100 | 3.0 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 10,
  "overview_chars": 1564,
  "ac_chars": 5021,
  "ac_count": 9,
  "gwt_block_count": 8,
  "consistency_report": true,
  "code_blocks_total": 35,
  "code_blocks_by_lang": {
    "plain": 3,
    "php": 15,
    "go": 15,
    "typescript": 2
  },
  "has_sql_ddl": false,
  "has_json_schema": false,
  "has_ts_enums": true,
  "has_yaml_openapi": false,
  "links_total": 54,
  "links_broken": 1,
  "todo_density": 0,
  "waffle_per_kchar": 0.13,
  "child_modules": 0
}
```

## Implementability Blockers

- No SQL DDL provided for database specifications.
- No JSON schemas provided for data structures.
- Specific examples of 'apperror.Result[T]' or 'Outcome' struct are not fully defined with their methods and fields.
- The 'domain enum' types are mentioned (e.g., EntityStatus.Active) but their full definitions are not inlined.
- The full set of 'isDefined()', 'isDefinedAndValid()', 'isEmpty()', 'isInvalid()' guard methods are not explicitly defined with signatures and return types.

## Code Mapping

**Implemented by:** `linter-scripts/validate-guidelines.go`, `linter-scripts/validate-guidelines.py`
**Expected but missing:** _(none)_
**Orphan code candidates:** _(none)_

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | missing-contract | critical | 8/10 | Missing SQL DDL for database-related guidelines. |
| 2 | missing-contract | critical | 8/10 | Missing JSON schemas for data structures described in the guidelines. |
| 3 | broken-link | medium | 5/10 | One broken link reduces overall consistency. |
| 4 | missing-contract | high | 7/10 | Specific examples of 'apperror.Result[T]' or 'Outcome' struct are not fully defined with their methods and fields. |
| 5 | missing-contract | high | 7/10 | Domain enums are mentioned but their full definitions are not inlined. |
| 6 | missing-contract | high | 7/10 | The full set of 'isDefined()', 'isDefinedAndValid()', 'isEmpty()', 'isInvalid()' guard methods are not explicitly defined with signatures and return types. |

### Detail + Proposed Corrections

#### 1. [CRITICAL] Missing SQL DDL for database-related guidelines.
- **Category:** missing-contract  |  **Impact:** 8/10
- **Evidence:** has_sql_ddl=false, but 01-naming-and-database.md mentions database naming.
- **Proposed correction:** Inline all SQL DDL directly in the spec.

#### 2. [CRITICAL] Missing JSON schemas for data structures described in the guidelines.
- **Category:** missing-contract  |  **Impact:** 8/10
- **Evidence:** has_json_schema=false, but the spec discusses data-related concepts.
- **Proposed correction:** Inline all JSON schemas directly in the spec.

#### 3. [MEDIUM] One broken link reduces overall consistency.
- **Category:** broken-link  |  **Impact:** 5/10
- **Evidence:** links_broken > 0
- **Proposed correction:** Fix the broken link to ensure all references are resolvable.

#### 4. [HIGH] Specific examples of 'apperror.Result[T]' or 'Outcome' struct are not fully defined with their methods and fields.
- **Category:** missing-contract  |  **Impact:** 7/10
- **Evidence:** AC-02 describes 'apperror.Result[T]' but the full definition is not inlined.
- **Proposed correction:** Provide the full type definition, including methods and fields, for 'apperror.Result[T]' and 'Outcome' struct.

#### 5. [HIGH] Domain enums are mentioned but their full definitions are not inlined.
- **Category:** missing-contract  |  **Impact:** 7/10
- **Evidence:** AC-06 refers to 'EntityStatus.Active' and ENUMS section lists 'StatusType', 'EntityStatus', 'HttpMethodType', 'ResponseKeyType', but their complete structures are missing.
- **Proposed correction:** Inline the complete definitions of all specified enums.

#### 6. [HIGH] The full set of 'isDefined()', 'isDefinedAndValid()', 'isEmpty()', 'isInvalid()' guard methods are not explicitly defined with signatures and return types.
- **Category:** missing-contract  |  **Impact:** 7/10
- **Evidence:** AC-04 refers to these guard methods but their definitions are not provided.
- **Proposed correction:** Provide full function signatures and return types for all mentioned guard methods.
