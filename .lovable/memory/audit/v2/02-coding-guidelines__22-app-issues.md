# Audit v2 — `spec/02-coding-guidelines/22-app-issues`

**Date:** 2026-04-25  
**Auditor:** Lovable AI (gemini-3-flash-preview, 2-pass)  
**Implementability Score:** **65/100 (C)**  
**Blast radius:** 0/10

> This module is well-structured and clear but suffers from a broken link and, more critically, a lack of concrete examples or contracts for the 'App Issues' it's meant to contain. This significantly hinders implementability.


**Score justification:** The broken link significantly impacted consistency. The module also lacks specific contracts or examples, hindering implementability. However, the clarity is excellent due to a low waffle per kchar.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 30 | 10.5 |
| Completeness | 20% | 80 | 16.0 |
| Alignment | 15% | 90 | 13.5 |
| Consistency | 10% | 70 | 7.0 |
| Clarity | 10% | 100 | 10.0 |
| Testability | 7% | 70 | 4.9 |
| Maintainability | 3% | 100 | 3.0 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 4,
  "overview_chars": 832,
  "ac_chars": 2572,
  "ac_count": 5,
  "gwt_block_count": 5,
  "consistency_report": true,
  "code_blocks_total": 1,
  "code_blocks_by_lang": {
    "bash": 1
  },
  "has_sql_ddl": false,
  "has_json_schema": false,
  "has_ts_enums": false,
  "has_yaml_openapi": false,
  "links_total": 8,
  "links_broken": 1,
  "todo_density": 0,
  "waffle_per_kchar": 0.0,
  "child_modules": 0
}
```

## Implementability Blockers

- No concrete examples of 'App Issues' are provided, making it difficult to understand the expected content shape.
- No explicit contracts (e.g., JSON schema, DDL) for how app issues should be structured.

## Code Mapping

**Implemented by:** _(none — pure-doc spec)_
**Expected but missing:** _(none)_
**Orphan code candidates:** _(none)_

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | broken-link | medium | 5/10 | A broken link in the overview section. |
| 2 | missing-contract | high | 7/10 | Lack of concrete examples or contracts for 'App Issues'. |

### Detail + Proposed Corrections

#### 1. [MEDIUM] A broken link in the overview section.
- **Category:** broken-link  |  **Impact:** 5/10
- **Evidence:** Cross-References table in `00-overview.md` contains a broken link: `../00-overview.md`
- **Proposed correction:** Fix the broken link to point to a valid resource.

#### 2. [HIGH] Lack of concrete examples or contracts for 'App Issues'.
- **Category:** missing-contract  |  **Impact:** 7/10
- **Evidence:** The 'Contents' section states 'No content yet. Add app issue analyses as numbered files within this folder.' This makes it difficult for an AI to understand what an 'app issue' should look like.
- **Proposed correction:** Provide a template or a few examples of app issue analyses, including recommended sections and data structures (e.g., proposed markdown structure, YAML front matter for metadata).
