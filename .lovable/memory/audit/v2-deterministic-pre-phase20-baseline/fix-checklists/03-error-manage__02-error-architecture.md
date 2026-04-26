# Fix Checklist — `spec/03-error-manage/02-error-architecture`

**Generated:** 2026-04-25  
**Current score:** 80/100 (B)  
**Implementability:** 65/100  
**Estimated effort:** ~10 min  
**Impact-weighted backlog:** 6 points

## Actions

| # | Pri | Category | Target file | Effort | Action |
|---:|:--:|---|---|---:|---|
| 1 | **P1** | broken-link | `spec/03-error-manage/02-error-architecture/` | 5m | Run `python3 linter-scripts/check-spec-cross-links.py --root spec/03-error-manage/02-error-architecture` then either (a) fix each path or (b) add the link to `linter-scripts/spec-cross-links.allowlist` with a justification comment. |
| 2 | **P3** | maintainability | `spec/03-error-manage/02-error-architecture/98-changelog.md` | 5m | After applying the above fixes, bump version (≥ minor) and add a row to `98-changelog.md` summarising what changed. |

## Detail + Acceptance test for each action

### 1. [P1] broken-link — `spec/03-error-manage/02-error-architecture/`

**Action:** Run `python3 linter-scripts/check-spec-cross-links.py --root spec/03-error-manage/02-error-architecture` then either (a) fix each path or (b) add the link to `linter-scripts/spec-cross-links.allowlist` with a justification comment.

**Acceptance test:** Given `python3 linter-scripts/check-spec-cross-links.py --root spec/03-error-manage/02-error-architecture`, When run, Then exit code MUST be 0.

**Effort estimate:** ~5 minutes

### 2. [P3] maintainability — `spec/03-error-manage/02-error-architecture/98-changelog.md`

**Action:** After applying the above fixes, bump version (≥ minor) and add a row to `98-changelog.md` summarising what changed.

**Acceptance test:** Given `spec/03-error-manage/02-error-architecture/98-changelog.md`, When read, Then top-most version row MUST be dated 2026-04-25 and reference the fixes above.

**Effort estimate:** ~5 minutes

## Source metrics (from deterministic audit)

```json
{
  "ac_chars": 2687,
  "ac_count": 5,
  "child_modules": 4,
  "code_blocks_by_lang": {
    "bash": 1,
    "css": 1,
    "go": 8,
    "json": 1,
    "php": 3,
    "plain": 6,
    "tsx": 5,
    "typescript": 2
  },
  "code_blocks_total": 27,
  "consistency_report": true,
  "gwt_block_count": 5,
  "has_json_schema": true,
  "has_mermaid": false,
  "has_sql_ddl": false,
  "has_ts_enums": true,
  "has_yaml_openapi": false,
  "links_broken": 1,
  "links_total": 24,
  "md_files": 7,
  "mmd_files": 0,
  "overview_chars": 2427,
  "todo_density": 0,
  "waffle_per_kchar": 0.11
}
```
