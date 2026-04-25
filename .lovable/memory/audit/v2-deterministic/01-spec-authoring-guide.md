# Audit v2 — `spec/01-spec-authoring-guide`

**Date:** 2026-04-25  
**Auditor:** Deterministic rubric (no AI)  
**Implementability Score:** **54/100 (D)**  
**Blast radius:** 0/10

> Deterministic score 54/100 (D) for spec/01-spec-authoring-guide.


**Score justification:** Deterministic rubric: contracts=0/3, ac=4, gwt=0, broken_links=11, waffle/kchar=0.33. Gates active: 1.

---

## 7-Dimension Scores (v2 weights)

| Dimension | Weight | Score | Contribution |
|---|---:|---:|---:|
| Implementability | 35% | 40 | 14.0 |
| Completeness | 20% | 60 | 12.0 |
| Alignment | 15% | 40 | 6.0 |
| Consistency | 10% | 50 | 5.0 |
| Clarity | 10% | 100 | 10.0 |
| Testability | 7% | 60 | 4.2 |
| Maintainability | 3% | 90 | 2.7 |

## Deterministic Metrics (pre-AI)

```json
{
  "md_files": 16,
  "mmd_files": 0,
  "overview_chars": 22937,
  "ac_chars": 2490,
  "ac_count": 4,
  "gwt_block_count": 0,
  "consistency_report": true,
  "code_blocks_total": 68,
  "code_blocks_by_lang": {
    "plain": 40,
    "bash": 3,
    "markdown": 23,
    "html": 2
  },
  "has_sql_ddl": false,
  "has_json_schema": false,
  "has_ts_enums": false,
  "has_yaml_openapi": false,
  "has_mermaid": false,
  "links_total": 61,
  "links_broken": 11,
  "todo_density": 2,
  "waffle_per_kchar": 0.33,
  "child_modules": 0
}
```

## Implementability Blockers

- 11 broken cross-spec link(s)
- No inlined contract (SQL DDL / JSON schema / TS enum) in module body

## Code Mapping

**Implemented by:** _(none — pure-doc spec)_
**Expected but missing:** _(none)_
**Orphan code candidates:** _(none)_

## Findings

| # | Category | Sev | Impact | Issue |
|---:|---|:-:|:-:|---|
| 1 | broken-link | high | 7/10 | 11 broken cross-spec link(s) |
| 2 | drift | low | 3/10 | 2 TODO/TBD/FIXME marker(s) in module body |
| 3 | missing-contract | high | 8/10 | No inlined contract (SQL DDL / JSON schema / TS enum) in module body |
| 4 | untestable | medium | 5/10 | Acceptance criteria present but no Given/When/Then blocks |

### Detail + Proposed Corrections

#### 1. [HIGH] 11 broken cross-spec link(s)
- **Category:** broken-link  |  **Impact:** 7/10
- **Evidence:** links_total=61, links_broken=11
- **Proposed correction:** Run linter-scripts/check-spec-cross-links.py and fix every reported link.

#### 2. [LOW] 2 TODO/TBD/FIXME marker(s) in module body
- **Category:** drift  |  **Impact:** 3/10
- **Evidence:** todo_density=2
- **Proposed correction:** Resolve or convert markers to tracked acceptance criteria.

#### 3. [HIGH] No inlined contract (SQL DDL / JSON schema / TS enum) in module body
- **Category:** missing-contract  |  **Impact:** 8/10
- **Evidence:** code_blocks_by_lang={"bash": 3, "html": 2, "markdown": 23, "plain": 40}
- **Proposed correction:** Inline at least one normative contract block in 00-overview.md or a dedicated contract file.

#### 4. [MEDIUM] Acceptance criteria present but no Given/When/Then blocks
- **Category:** untestable  |  **Impact:** 5/10
- **Evidence:** ac_count=4, gwt_block_count=0
- **Proposed correction:** Rewrite each AC as a Given/When/Then block.
