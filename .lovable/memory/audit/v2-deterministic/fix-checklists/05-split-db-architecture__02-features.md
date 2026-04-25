# Fix Checklist — `spec/05-split-db-architecture/02-features`

**Generated:** 2026-04-25  
**Current score:** 81/100 (B)  
**Implementability:** 75/100  
**Estimated effort:** ~10 min  
**Impact-weighted backlog:** 6 points

## Actions

| # | Pri | Category | Target file | Effort | Action |
|---:|:--:|---|---|---:|---|
| 1 | **P1** | broken-link | `spec/05-split-db-architecture/02-features/` | 5m | Run `python3 linter-scripts/check-spec-cross-links.py --root spec/05-split-db-architecture/02-features` then either (a) fix each path or (b) add the link to `linter-scripts/spec-cross-links.allowlist` with a justification comment. |
| 2 | **P3** | maintainability | `spec/05-split-db-architecture/02-features/98-changelog.md` | 5m | After applying the above fixes, bump version (≥ minor) and add a row to `98-changelog.md` summarising what changed. |

## Detail + Acceptance test for each action

### 1. [P1] broken-link — `spec/05-split-db-architecture/02-features/`

**Action:** Run `python3 linter-scripts/check-spec-cross-links.py --root spec/05-split-db-architecture/02-features` then either (a) fix each path or (b) add the link to `linter-scripts/spec-cross-links.allowlist` with a justification comment.

**Acceptance test:** Given `python3 linter-scripts/check-spec-cross-links.py --root spec/05-split-db-architecture/02-features`, When run, Then exit code MUST be 0.

**Effort estimate:** ~5 minutes

### 2. [P3] maintainability — `spec/05-split-db-architecture/02-features/98-changelog.md`

**Action:** After applying the above fixes, bump version (≥ minor) and add a row to `98-changelog.md` summarising what changed.

**Acceptance test:** Given `spec/05-split-db-architecture/02-features/98-changelog.md`, When read, Then top-most version row MUST be dated 2026-04-25 and reference the fixes above.

**Effort estimate:** ~5 minutes

## Source metrics (from deterministic audit)

```json
{
  "ac_chars": 2771,
  "ac_count": 5,
  "child_modules": 0,
  "code_blocks_by_lang": {
    "bash": 2,
    "go": 19,
    "ini": 2,
    "json": 5,
    "plain": 20,
    "sql": 18
  },
  "code_blocks_total": 66,
  "consistency_report": true,
  "gwt_block_count": 5,
  "has_json_schema": true,
  "has_mermaid": false,
  "has_sql_ddl": true,
  "has_ts_enums": false,
  "has_yaml_openapi": false,
  "links_broken": 1,
  "links_total": 19,
  "md_files": 9,
  "mmd_files": 0,
  "overview_chars": 816,
  "todo_density": 0,
  "waffle_per_kchar": 0.01
}
```
