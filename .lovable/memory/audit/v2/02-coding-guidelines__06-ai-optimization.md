# Audit v2 — `spec/02-coding-guidelines/06-ai-optimization`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview, 2-pass)  
**Implementability Score:** **49/100 (D)**  
**Blast radius:** 7/10

> This module provides comprehensive coding guidelines for AI, but it is not directly implementable by an AI as it lacks machine-executable contracts. It also suffers from untestable acceptance criteria and a disconnect from existing linter implementations.


**Score justification:** Implementability is low because while the spec contains coding guidelines, it is not a direct API or data model that can be implemented by an AI. Testability is low because ac_count is 0. Alignment is low because the spec does not align with any listed code.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 30 | 10.5 |
| Completeness | 20% | 80 | 16.0 |
| Alignment | 15% | 0 | 0.0 |
| Consistency | 10% | 100 | 10.0 |
| Clarity | 10% | 90 | 9.0 |
| Testability | 7% | 20 | 1.4 |
| Maintainability | 3% | 70 | 2.1 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 9,
  "overview_chars": 1647,
  "ac_chars": 596,
  "ac_count": 0,
  "gwt_block_count": 0,
  "consistency_report": true,
  "code_blocks_total": 34,
  "code_blocks_by_lang": {
    "typescript": 13,
    "json": 1,
    "go": 11,
    "php": 4,
    "plain": 2,
    "rust": 3
  },
  "has_sql_ddl": false,
  "has_json_schema": true,
  "has_ts_enums": true,
  "has_yaml_openapi": false,
  "links_total": 31,
  "links_broken": 0,
  "todo_density": 0,
  "waffle_per_kchar": 0.13,
  "child_modules": 0
}
```

## Implementability Blockers

- Spec does not describe an API or data model for an AI to implement. It is a set of guidelines.
- No executable contracts are provided for the guidelines listed.

## Code Mapping

**Implemented by:** _(none — pure-doc spec)_
**Expected but missing:** _(none)_
**Orphan code candidates:** `linter-scripts/validate-guidelines.go`, `linter-scripts/validate-guidelines.py`

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | missing-contract | high | 7/10 | The spec provides coding guidelines but lacks machine-executable contracts (e.g., in a linter rule format) that an AI could directly use for implementation or validation. |
| 2 | untestable | medium | 5/10 | The spec has 0 acceptance criteria, making it untestable by AI. |
| 3 | drift | medium | 3/10 | The spec module describes 'AI Optimization' guidelines, and there are linter scripts (`linter-scripts/validate-guidelines.go`, `linter-scripts/validate-guidelines.py`) which sound related, but they are not listed as implementations of this spec, nor is this spec referenced by them. |

### Detail + Proposed Corrections

#### 1. [HIGH] The spec provides coding guidelines but lacks machine-executable contracts (e.g., in a linter rule format) that an AI could directly use for implementation or validation.
- **Category:** missing-contract  |  **Impact:** 7/10
- **Evidence:** The spec contains rules like 'Anti-hallucination rules cover all 5 language categories', but these are prose and not executable.
- **Proposed correction:** Convert all guidelines into machine-executable linter rules or formal contracts that AI can interpret and implement as code.

#### 2. [MEDIUM] The spec has 0 acceptance criteria, making it untestable by AI.
- **Category:** untestable  |  **Impact:** 5/10
- **Evidence:** ac_count == 0.
- **Proposed correction:** Rewrite acceptance criteria using Gherkin (Given/When/Then) format to make them objectively verifiable.

#### 3. [MEDIUM] The spec module describes 'AI Optimization' guidelines, and there are linter scripts (`linter-scripts/validate-guidelines.go`, `linter-scripts/validate-guidelines.py`) which sound related, but they are not listed as implementations of this spec, nor is this spec referenced by them.
- **Category:** drift  |  **Impact:** 3/10
- **Evidence:** The spec is about 'Coding Guidelines' and there are 'validate-guidelines.go' and 'validate-guidelines.py' in the codebase which are orphaned from the spec.
- **Proposed correction:** Either explicitly link the `validate-guidelines` scripts to this spec module as implementations, or clarify why they are not considered part of this spec. Conversely, the `validate-guidelines` scripts should reference this spec as their source of truth.
