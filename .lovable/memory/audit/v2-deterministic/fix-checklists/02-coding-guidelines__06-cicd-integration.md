# Fix Checklist — `spec/02-coding-guidelines/06-cicd-integration`

**Generated:** 2026-04-25  
**Current score:** 87/100 (A)  
**Implementability:** 75/100  
**Estimated effort:** ~40 min  
**Impact-weighted backlog:** 6 points

## Actions

| # | Pri | Category | Target file | Effort | Action |
|---:|:--:|---|---|---:|---|
| 1 | **P1** | untestable | `spec/02-coding-guidelines/06-cicd-integration/97-acceptance-criteria.md` | 35m | Rewrite 7 acceptance criterion/criteria into Given/When/Then form. |
| 2 | **P3** | maintainability | `spec/02-coding-guidelines/06-cicd-integration/98-changelog.md` | 5m | After applying the above fixes, bump version (≥ minor) and add a row to `98-changelog.md` summarising what changed. |

## Detail + Acceptance test for each action

### 1. [P1] untestable — `spec/02-coding-guidelines/06-cicd-integration/97-acceptance-criteria.md`

**Action:** Rewrite 7 acceptance criterion/criteria into Given/When/Then form.

**Acceptance test:** Given `spec/02-coding-guidelines/06-cicd-integration/97-acceptance-criteria.md`, When parsed, Then `gwt_block_count` MUST equal `ac_count` (7).

**Effort estimate:** ~35 minutes

### 2. [P3] maintainability — `spec/02-coding-guidelines/06-cicd-integration/98-changelog.md`

**Action:** After applying the above fixes, bump version (≥ minor) and add a row to `98-changelog.md` summarising what changed.

**Acceptance test:** Given `spec/02-coding-guidelines/06-cicd-integration/98-changelog.md`, When read, Then top-most version row MUST be dated 2026-04-25 and reference the fixes above.

**Effort estimate:** ~5 minutes

## Source metrics (from deterministic audit)

```json
{
  "ac_chars": 1410,
  "ac_count": 7,
  "child_modules": 0,
  "code_blocks_by_lang": {
    "bash": 27,
    "go": 1,
    "json": 2,
    "php": 1,
    "plain": 10,
    "python": 1,
    "toml": 7,
    "ts": 2,
    "yaml": 7
  },
  "code_blocks_total": 58,
  "consistency_report": true,
  "gwt_block_count": 0,
  "has_json_schema": true,
  "has_mermaid": false,
  "has_sql_ddl": false,
  "has_ts_enums": true,
  "has_yaml_openapi": true,
  "links_broken": 0,
  "links_total": 34,
  "md_files": 13,
  "mmd_files": 0,
  "overview_chars": 3154,
  "todo_density": 0,
  "waffle_per_kchar": 0.17
}
```
