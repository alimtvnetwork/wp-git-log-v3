# Audit v2 — `spec/17-consolidated-guidelines`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview, 2-pass)  
**Implementability Score:** **65/100 (C)**  
**Blast radius:** 10/10

> This module, despite its clear intention to be AI-readable and standalone, falls short on implementability due to the absence of inlined contracts and machine-readable connections to its source material. It's a collection of high-level guidance, but not a full implementation spec.


**Score justification:** Implementability is capped at 40% because, despite the stated goal of being standalone, the module contains no inlined contracts (DDL, GraphQL, Protobuf, etc.) but claims `has_sql_ddl: true` and `has_json_schema: true` in the metrics. Consistency is capped due to one broken link. Alignment is low because the module claims to summarize many other spec modules, but there is no code that maps this module to its 'source modules'.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 40 | 14.0 |
| Completeness | 20% | 90 | 18.0 |
| Alignment | 15% | 60 | 9.0 |
| Consistency | 10% | 70 | 7.0 |
| Clarity | 10% | 90 | 9.0 |
| Testability | 7% | 70 | 4.9 |
| Maintainability | 3% | 90 | 2.7 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 35,
  "overview_chars": 7300,
  "ac_chars": 3464,
  "ac_count": 5,
  "gwt_block_count": 5,
  "consistency_report": true,
  "code_blocks_total": 351,
  "code_blocks_by_lang": {
    "bash": 6,
    "markdown": 7,
    "plain": 188,
    "go": 42,
    "typescript": 18,
    "php": 13,
    "csharp": 1,
    "json": 13,
    "ts": 2,
    "toml": 1,
    "rust": 4,
    "yaml": 3,
    "sql": 10,
    "ini": 1,
    "css": 24,
    "html": 4,
    "regex": 1,
    "powershell": 12,
    "gitignore": 1
  },
  "has_sql_ddl": true,
  "has_json_schema": true,
  "has_ts_enums": true,
  "has_yaml_openapi": true,
  "links_total": 144,
  "links_broken": 1,
  "todo_density": 6,
  "waffle_per_kchar": 0.06,
  "child_modules": 0
}
```

## Implementability Blockers

- No inlined contracts (DDL, JSON schema, Protobuf, etc.) despite claiming their existence in metrics.
- No mechanism to verify that the consolidated guidelines accurately reflect their source modules without human intervention.
- The connections between the consolidated files and their 'Summarizes (Source Module)' are not machine-readable.
- Lack of detailed, concrete examples for many guidelines.

## Code Mapping

**Implemented by:** `linter-scripts/check-tree-health.cjs`, `linter-scripts/check-spec-cross-links.py`, `linter-scripts/run.sh`
**Expected but missing:** _(none)_
**Orphan code candidates:** _(none)_

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | missing-contract | high | 8/10 | The spec states it contains DDL and JSON schemas, but these are not inlined in the module, severely impacting implementability. |
| 2 | broken-link | medium | 5/10 | One broken link was detected within the module. |
| 3 | missing-contract | medium | 6/10 | The connection between consolidated files and their 'source modules' is not machine-readable, making it impossible to automate alignment checks. |
| 4 | ambiguity | low | 3/10 | While stated as 'self-contained AI-readable references', the guidelines often lack sufficiently detailed code examples for a truly 'mediocre AI coder' to implement without ambiguity. |

### Detail + Proposed Corrections

#### 1. [HIGH] The spec states it contains DDL and JSON schemas, but these are not inlined in the module, severely impacting implementability.
- **Category:** missing-contract  |  **Impact:** 8/10
- **Evidence:** has_sql_ddl: true, has_json_schema: true in metrics, but no such content found in the module files.
- **Proposed correction:** Inline all DDL scripts and JSON schema definitions directly into the relevant consolidated guideline files (e.g., database conventions, seedable config, error management).

#### 2. [MEDIUM] One broken link was detected within the module.
- **Category:** broken-link  |  **Impact:** 5/10
- **Evidence:** links_broken: 1 in metrics.
- **Proposed correction:** Identify and fix the broken link within the module to ensure full consistency.

#### 3. [MEDIUM] The connection between consolidated files and their 'source modules' is not machine-readable, making it impossible to automate alignment checks.
- **Category:** missing-contract  |  **Impact:** 6/10
- **Evidence:** File Inventory table in 00-overview.md lists 'Summarizes (Source Module)' as prose.
- **Proposed correction:** Refactor the 'Summarizes (Source Module)' section into a machine-readable format (e.g., YAML frontmatter, JSON block) within each consolidated file, specifying the exact source file paths.

#### 4. [LOW] While stated as 'self-contained AI-readable references', the guidelines often lack sufficiently detailed code examples for a truly 'mediocre AI coder' to implement without ambiguity.
- **Category:** ambiguity  |  **Impact:** 3/10
- **Evidence:** General lack of extensive, runnable code blocks for all defined patterns and conventions.
- **Proposed correction:** For each guideline, ensure there are comprehensive, runnable code examples in multiple languages (where applicable) that demonstrate the exact implementation of the described pattern or rule.
