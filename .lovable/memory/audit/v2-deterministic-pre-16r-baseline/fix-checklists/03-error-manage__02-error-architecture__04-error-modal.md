# Fix Checklist — `spec/03-error-manage/02-error-architecture/04-error-modal`

**Generated:** 2026-04-25  
**Current score:** 81/100 (B)  
**Implementability:** 65/100  
**Estimated effort:** ~15 min  
**Impact-weighted backlog:** 11 points

## Actions

| # | Pri | Category | Target file | Effort | Action |
|---:|:--:|---|---|---:|---|
| 1 | **P1** | untestable | `spec/03-error-manage/02-error-architecture/04-error-modal/97-acceptance-criteria.md` | 5m | Rewrite 1 acceptance criterion/criteria into Given/When/Then form. |
| 2 | **P1** | broken-link | `spec/03-error-manage/02-error-architecture/04-error-modal/` | 5m | Run `python3 linter-scripts/check-spec-cross-links.py --root spec/03-error-manage/02-error-architecture/04-error-modal` then either (a) fix each path or (b) add the link to `linter-scripts/spec-cross-links.allowlist` with a justification comment. |
| 3 | **P3** | maintainability | `spec/03-error-manage/02-error-architecture/04-error-modal/98-changelog.md` | 5m | After applying the above fixes, bump version (≥ minor) and add a row to `98-changelog.md` summarising what changed. |

## Detail + Acceptance test for each action

### 1. [P1] untestable — `spec/03-error-manage/02-error-architecture/04-error-modal/97-acceptance-criteria.md`

**Action:** Rewrite 1 acceptance criterion/criteria into Given/When/Then form.

**Acceptance test:** Given `spec/03-error-manage/02-error-architecture/04-error-modal/97-acceptance-criteria.md`, When parsed, Then `gwt_block_count` MUST equal `ac_count` (8).

**Effort estimate:** ~5 minutes

### 2. [P1] broken-link — `spec/03-error-manage/02-error-architecture/04-error-modal/`

**Action:** Run `python3 linter-scripts/check-spec-cross-links.py --root spec/03-error-manage/02-error-architecture/04-error-modal` then either (a) fix each path or (b) add the link to `linter-scripts/spec-cross-links.allowlist` with a justification comment.

**Acceptance test:** Given `python3 linter-scripts/check-spec-cross-links.py --root spec/03-error-manage/02-error-architecture/04-error-modal`, When run, Then exit code MUST be 0.

**Effort estimate:** ~5 minutes

### 3. [P3] maintainability — `spec/03-error-manage/02-error-architecture/04-error-modal/98-changelog.md`

**Action:** After applying the above fixes, bump version (≥ minor) and add a row to `98-changelog.md` summarising what changed.

**Acceptance test:** Given `spec/03-error-manage/02-error-architecture/04-error-modal/98-changelog.md`, When read, Then top-most version row MUST be dated 2026-04-25 and reference the fixes above.

**Effort estimate:** ~5 minutes

## Source metrics (from deterministic audit)

```json
{
  "ac_chars": 4468,
  "ac_count": 8,
  "child_modules": 4,
  "code_blocks_by_lang": {
    "css": 2,
    "json": 3,
    "plain": 14,
    "tsx": 37,
    "typescript": 24
  },
  "code_blocks_total": 80,
  "consistency_report": true,
  "gwt_block_count": 7,
  "has_json_schema": true,
  "has_mermaid": false,
  "has_sql_ddl": false,
  "has_ts_enums": true,
  "has_yaml_openapi": false,
  "links_broken": 1,
  "links_total": 51,
  "md_files": 9,
  "mmd_files": 0,
  "overview_chars": 1429,
  "todo_density": 0,
  "waffle_per_kchar": 0.0
}
```
