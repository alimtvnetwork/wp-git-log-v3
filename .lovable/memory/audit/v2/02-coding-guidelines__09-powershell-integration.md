# Audit v2 — `spec/02-coding-guidelines/09-powershell-integration`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview, 2-pass)  
**Implementability Score:** **50/100 (D)**  
**Blast radius:** 6/10

> This module is a placeholder for PowerShell integration guidelines. It lacks actual content and code examples, making it impossible for an AI to implement.


**Score justification:** The implementability score is low because the spec is pure prose and doesn't inline any actual code or DDL. There is also an explicit 'No content yet' notice. Alignment is 0 as there is no corresponding code in the index. Completeness is impacted by the missing content.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 30 | 10.5 |
| Completeness | 20% | 60 | 12.0 |
| Alignment | 15% | 0 | 0.0 |
| Consistency | 10% | 100 | 10.0 |
| Clarity | 10% | 100 | 10.0 |
| Testability | 7% | 70 | 4.9 |
| Maintainability | 3% | 100 | 3.0 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 4,
  "overview_chars": 583,
  "ac_chars": 3580,
  "ac_count": 7,
  "gwt_block_count": 6,
  "consistency_report": true,
  "code_blocks_total": 0,
  "code_blocks_by_lang": {},
  "has_sql_ddl": false,
  "has_json_schema": false,
  "has_ts_enums": false,
  "has_yaml_openapi": false,
  "links_total": 8,
  "links_broken": 0,
  "todo_density": 0,
  "waffle_per_kchar": 0.0,
  "child_modules": 0
}
```

## Implementability Blockers

- No PowerShell code examples or templates provided.
- No concrete examples of 'Verb-Noun' or 'camelCase' naming in code.

## Code Mapping

**Implemented by:** _(none — pure-doc spec)_
**Expected but missing:** _(none)_
**Orphan code candidates:** _(none)_

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | missing-spec | critical | 8/10 | The overview explicitly states 'No content yet.' making the spec effectively a placeholder. |
| 2 | missing-contract | high | 7/10 | Despite defining naming conventions and other standards, no actual PowerShell code examples are provided to concretely illustrate these rules. |

### Detail + Proposed Corrections

#### 1. [CRITICAL] The overview explicitly states 'No content yet.' making the spec effectively a placeholder.
- **Category:** missing-spec  |  **Impact:** 8/10
- **Evidence:** Overview section: 'No content yet. Add PowerShell-related specs as numbered files within this folder.'
- **Proposed correction:** Add detailed PowerShell integration guidelines, conventions, and best practices.

#### 2. [HIGH] Despite defining naming conventions and other standards, no actual PowerShell code examples are provided to concretely illustrate these rules.
- **Category:** missing-contract  |  **Impact:** 7/10
- **Evidence:** Absence of 'code_blocks_total' and 'code_blocks_by_lang' in deterministic metrics.
- **Proposed correction:** Inline code examples for each standard, including naming conventions, error handling, parameter validation, and `ShouldProcess` usage.
