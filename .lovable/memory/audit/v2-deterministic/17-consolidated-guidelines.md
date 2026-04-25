# Audit v2 — `spec/17-consolidated-guidelines`

**Date:** 2026-04-25  
**Auditor:** Deterministic rubric (no AI)  
**Implementability Score:** **86/100 (A)**  
**Blast radius:** 7/10

> Deterministic score 86/100 (A) for spec/17-consolidated-guidelines.


**Score justification:** Deterministic rubric: contracts=3/3, ac=5, gwt=5, broken_links=1, waffle/kchar=0.06.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 95 | 33.2 |
| Completeness | 20% | 55 | 11.0 |
| Alignment | 15% | 90 | 13.5 |
| Consistency | 10% | 92 | 9.2 |
| Clarity | 10% | 100 | 10.0 |
| Testability | 7% | 90 | 6.3 |
| Maintainability | 3% | 90 | 2.7 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 35,
  "mmd_files": 0,
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
  "has_mermaid": false,
  "links_total": 144,
  "links_broken": 1,
  "todo_density": 6,
  "waffle_per_kchar": 0.06,
  "child_modules": 0
}
```

## Implementability Blockers

- 1 broken cross-spec link(s)

## Code Mapping

**Implemented by:** _(none — pure-doc spec)_
**Expected but missing:** _(none)_
**Orphan code candidates:** _(none)_

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | broken-link | high | 7/10 | 1 broken cross-spec link(s) |
| 2 | drift | low | 3/10 | 6 TODO/TBD/FIXME marker(s) in module body |

### Detail + Proposed Corrections

#### 1. [HIGH] 1 broken cross-spec link(s)
- **Category:** broken-link  |  **Impact:** 7/10
- **Evidence:** links_total=144, links_broken=1
- **Proposed correction:** Run linter-scripts/check-spec-cross-links.py and fix every reported link.

#### 2. [LOW] 6 TODO/TBD/FIXME marker(s) in module body
- **Category:** drift  |  **Impact:** 3/10
- **Evidence:** todo_density=6
- **Proposed correction:** Resolve or convert markers to tracked acceptance criteria.
