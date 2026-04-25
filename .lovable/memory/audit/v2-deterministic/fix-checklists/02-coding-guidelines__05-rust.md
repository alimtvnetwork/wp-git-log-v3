# Fix Checklist — `spec/02-coding-guidelines/05-rust`

**Generated:** 2026-04-25  
**Current score:** 79/100 (B)  
**Implementability:** 60/100  
**Estimated effort:** ~35 min  
**Impact-weighted backlog:** 6 points

## Actions

| # | Pri | Category | Target file | Effort | Action |
|---:|:--:|---|---|---:|---|
| 1 | **P1** | untestable | `spec/02-coding-guidelines/05-rust/97-acceptance-criteria.md` | 30m | Rewrite 6 acceptance criterion/criteria into Given/When/Then form. |
| 2 | **P3** | maintainability | `spec/02-coding-guidelines/05-rust/98-changelog.md` | 5m | After applying the above fixes, bump version (≥ minor) and add a row to `98-changelog.md` summarising what changed. |

## Detail + Acceptance test for each action

### 1. [P1] untestable — `spec/02-coding-guidelines/05-rust/97-acceptance-criteria.md`

**Action:** Rewrite 6 acceptance criterion/criteria into Given/When/Then form.

**Acceptance test:** Given `spec/02-coding-guidelines/05-rust/97-acceptance-criteria.md`, When parsed, Then `gwt_block_count` MUST equal `ac_count` (6).

**Effort estimate:** ~30 minutes

### 2. [P3] maintainability — `spec/02-coding-guidelines/05-rust/98-changelog.md`

**Action:** After applying the above fixes, bump version (≥ minor) and add a row to `98-changelog.md` summarising what changed.

**Acceptance test:** Given `spec/02-coding-guidelines/05-rust/98-changelog.md`, When read, Then top-most version row MUST be dated 2026-04-25 and reference the fixes above.

**Effort estimate:** ~5 minutes

## Source metrics (from deterministic audit)

```json
{
  "ac_chars": 1632,
  "ac_count": 6,
  "child_modules": 0,
  "code_blocks_by_lang": {
    "plain": 5,
    "rust": 50,
    "sql": 2,
    "toml": 1
  },
  "code_blocks_total": 58,
  "consistency_report": true,
  "gwt_block_count": 0,
  "has_json_schema": false,
  "has_mermaid": false,
  "has_sql_ddl": true,
  "has_ts_enums": false,
  "has_yaml_openapi": false,
  "links_broken": 0,
  "links_total": 9,
  "md_files": 10,
  "mmd_files": 0,
  "overview_chars": 3191,
  "todo_density": 0,
  "waffle_per_kchar": 0.27
}
```
