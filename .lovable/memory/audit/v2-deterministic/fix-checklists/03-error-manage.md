# Fix Checklist — `spec/03-error-manage`

**Generated:** 2026-04-25  
**Current score:** 81/100 (B)  
**Implementability:** 45/100  
**Estimated effort:** ~10 min  
**Impact-weighted backlog:** 6 points

## Actions

| # | Pri | Category | Target file | Effort | Action |
|---:|:--:|---|---|---:|---|
| 1 | **P1** | untestable | `spec/03-error-manage/97-acceptance-criteria.md` | 5m | Rewrite 1 acceptance criterion/criteria into Given/When/Then form. |
| 2 | **P3** | maintainability | `spec/03-error-manage/98-changelog.md` | 5m | After applying the above fixes, bump version (≥ minor) and add a row to `98-changelog.md` summarising what changed. |

## Detail + Acceptance test for each action

### 1. [P1] untestable — `spec/03-error-manage/97-acceptance-criteria.md`

**Action:** Rewrite 1 acceptance criterion/criteria into Given/When/Then form.

**Acceptance test:** Given `spec/03-error-manage/97-acceptance-criteria.md`, When parsed, Then `gwt_block_count` MUST equal `ac_count` (8).

**Effort estimate:** ~5 minutes

### 2. [P3] maintainability — `spec/03-error-manage/98-changelog.md`

**Action:** After applying the above fixes, bump version (≥ minor) and add a row to `98-changelog.md` summarising what changed.

**Acceptance test:** Given `spec/03-error-manage/98-changelog.md`, When read, Then top-most version row MUST be dated 2026-04-25 and reference the fixes above.

**Effort estimate:** ~5 minutes

## Source metrics (from deterministic audit)

```json
{
  "ac_chars": 4252,
  "ac_count": 8,
  "child_modules": 3,
  "code_blocks_by_lang": {
    "bash": 1,
    "json": 1,
    "plain": 2
  },
  "code_blocks_total": 4,
  "consistency_report": true,
  "gwt_block_count": 7,
  "has_json_schema": true,
  "has_mermaid": false,
  "has_sql_ddl": false,
  "has_ts_enums": false,
  "has_yaml_openapi": false,
  "links_broken": 0,
  "links_total": 14,
  "md_files": 5,
  "mmd_files": 0,
  "overview_chars": 5430,
  "todo_density": 0,
  "waffle_per_kchar": 0.0
}
```
