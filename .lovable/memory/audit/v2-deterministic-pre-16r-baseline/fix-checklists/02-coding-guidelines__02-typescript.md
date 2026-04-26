# Fix Checklist — `spec/02-coding-guidelines/02-typescript`

**Generated:** 2026-04-25  
**Current score:** 70/100 (C)  
**Implementability:** 50/100  
**Estimated effort:** ~25 min  
**Impact-weighted backlog:** 7 points

## Actions

| # | Pri | Category | Target file | Effort | Action |
|---:|:--:|---|---|---:|---|
| 1 | **P1** | untestable | `spec/02-coding-guidelines/02-typescript/97-acceptance-criteria.md` | 10m | Rewrite 2 acceptance criterion/criteria into Given/When/Then form. |
| 2 | **P3** | drift | `spec/02-coding-guidelines/02-typescript/*.md` | 10m | Resolve 1 TODO/TBD/FIXME marker(s). Either implement the missing detail or move the marker into a tracked AC. |
| 3 | **P3** | maintainability | `spec/02-coding-guidelines/02-typescript/98-changelog.md` | 5m | After applying the above fixes, bump version (≥ minor) and add a row to `98-changelog.md` summarising what changed. |

## Detail + Acceptance test for each action

### 1. [P1] untestable — `spec/02-coding-guidelines/02-typescript/97-acceptance-criteria.md`

**Action:** Rewrite 2 acceptance criterion/criteria into Given/When/Then form.

**Acceptance test:** Given `spec/02-coding-guidelines/02-typescript/97-acceptance-criteria.md`, When parsed, Then `gwt_block_count` MUST equal `ac_count` (2).

**Effort estimate:** ~10 minutes

### 2. [P3] drift — `spec/02-coding-guidelines/02-typescript/*.md`

**Action:** Resolve 1 TODO/TBD/FIXME marker(s). Either implement the missing detail or move the marker into a tracked AC.

**Acceptance test:** Given the module body, When grep'd for `TODO|TBD|FIXME|XXX|HACK`, Then 0 matches.

**Effort estimate:** ~10 minutes

### 3. [P3] maintainability — `spec/02-coding-guidelines/02-typescript/98-changelog.md`

**Action:** After applying the above fixes, bump version (≥ minor) and add a row to `98-changelog.md` summarising what changed.

**Acceptance test:** Given `spec/02-coding-guidelines/02-typescript/98-changelog.md`, When read, Then top-most version row MUST be dated 2026-04-25 and reference the fixes above.

**Effort estimate:** ~5 minutes

## Source metrics (from deterministic audit)

```json
{
  "ac_chars": 642,
  "ac_count": 2,
  "child_modules": 0,
  "code_blocks_by_lang": {
    "bash": 1,
    "javascript": 1,
    "typescript": 73
  },
  "code_blocks_total": 75,
  "consistency_report": true,
  "gwt_block_count": 0,
  "has_json_schema": false,
  "has_mermaid": false,
  "has_sql_ddl": false,
  "has_ts_enums": true,
  "has_yaml_openapi": false,
  "links_broken": 0,
  "links_total": 70,
  "md_files": 16,
  "mmd_files": 0,
  "overview_chars": 3035,
  "todo_density": 1,
  "waffle_per_kchar": 0.09
}
```
