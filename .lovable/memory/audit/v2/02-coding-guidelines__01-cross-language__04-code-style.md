# Audit v2 — `spec/02-coding-guidelines/01-cross-language/04-code-style`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview, 2-pass)  
**Implementability Score:** **67/100 (C)**  
**Blast radius:** 8/10

> This module provides clear and detailed cross-language code style guidelines. However, the absence of explicit linters for all languages and rules, and a broken link, reduce its overall audit score.


**Score justification:** The spec is generally well-written and clear, which helps with clarity. However, a broken link affects overall consistency. The testability is capped at 70 because, while there are ACs, there is no explicit linter in the provided code index for all rules.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 70 | 24.5 |
| Completeness | 20% | 90 | 18.0 |
| Alignment | 15% | 0 | 0.0 |
| Consistency | 10% | 70 | 7.0 |
| Clarity | 10% | 100 | 10.0 |
| Testability | 7% | 70 | 4.9 |
| Maintainability | 3% | 90 | 2.7 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 10,
  "overview_chars": 2589,
  "ac_chars": 4713,
  "ac_count": 9,
  "gwt_block_count": 8,
  "consistency_report": true,
  "code_blocks_total": 65,
  "code_blocks_by_lang": {
    "php": 21,
    "typescript": 18,
    "go": 25,
    "plain": 1
  },
  "has_sql_ddl": false,
  "has_json_schema": false,
  "has_ts_enums": true,
  "has_yaml_openapi": false,
  "links_total": 38,
  "links_broken": 1,
  "todo_density": 3,
  "waffle_per_kchar": 0.08,
  "child_modules": 0
}
```

## Implementability Blockers

- No explicit linter found for enforcing all defined code style rules (e.g., brace enforcement, nested if block ban for TS/PHP, multi-line formatting).
- The spec describes rules for PHP, TypeScript, and Go, but the code implementation index only shows a Go validator and a general 'Cross-Language Coding Guidelines Validator' script, making it unclear if all rules are enforced across all specified languages.

## Code Mapping

**Implemented by:** `linter-scripts/validate-guidelines.go`, `linter-scripts/validate-guidelines.py`
**Expected but missing:** `linter for PHP code style rules`, `linter for TypeScript code style rules`
**Orphan code candidates:** _(none)_

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | broken-link | medium | 5/10 | A broken link was found in the document inventory. |
| 2 | missing-spec | high | 7/10 | Not all code style rules appear to have active linters or enforcement mechanisms across all specified languages. |

### Detail + Proposed Corrections

#### 1. [MEDIUM] A broken link was found in the document inventory.
- **Category:** broken-link  |  **Impact:** 5/10
- **Evidence:** links_broken > 0
- **Proposed correction:** Fix the broken link to '99-consistency-report.md'.

#### 2. [HIGH] Not all code style rules appear to have active linters or enforcement mechanisms across all specified languages.
- **Category:** missing-spec  |  **Impact:** 7/10
- **Evidence:** The code index only shows a Go validator and a general Python validator. Specific linters for PHP and TypeScript enforcing all these rules are not listed.
- **Proposed correction:** Implement and integrate linters for PHP and TypeScript that enforce all rules outlined in this spec module.
