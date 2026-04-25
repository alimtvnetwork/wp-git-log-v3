# Fix Checklist — `spec/02-coding-guidelines`

**Generated:** 2026-04-25  
**Current score:** 82/100 (B)  
**Implementability:** 70/100  
**Estimated effort:** ~35 min  
**Impact-weighted backlog:** 11 points

## Actions

| # | Pri | Category | Target file | Effort | Action |
|---:|:--:|---|---|---:|---|
| 1 | **P1** | untestable | `spec/02-coding-guidelines/97-acceptance-criteria.md` | 25m | Rewrite 5 acceptance criterion/criteria into Given/When/Then form. |
| 2 | **P1** | broken-link | `spec/02-coding-guidelines/` | 5m | Run `python3 linter-scripts/check-spec-cross-links.py --root spec/02-coding-guidelines` then either (a) fix each path or (b) add the link to `linter-scripts/spec-cross-links.allowlist` with a justification comment. |
| 3 | **P3** | maintainability | `spec/02-coding-guidelines/98-changelog.md` | 5m | After applying the above fixes, bump version (≥ minor) and add a row to `98-changelog.md` summarising what changed. |

## Detail + Acceptance test for each action

### 1. [P1] untestable — `spec/02-coding-guidelines/97-acceptance-criteria.md`

**Action:** Rewrite 5 acceptance criterion/criteria into Given/When/Then form.

**Acceptance test:** Given `spec/02-coding-guidelines/97-acceptance-criteria.md`, When parsed, Then `gwt_block_count` MUST equal `ac_count` (5).

**Effort estimate:** ~25 minutes

### 2. [P1] broken-link — `spec/02-coding-guidelines/`

**Action:** Run `python3 linter-scripts/check-spec-cross-links.py --root spec/02-coding-guidelines` then either (a) fix each path or (b) add the link to `linter-scripts/spec-cross-links.allowlist` with a justification comment.

**Acceptance test:** Given `python3 linter-scripts/check-spec-cross-links.py --root spec/02-coding-guidelines`, When run, Then exit code MUST be 0.

**Effort estimate:** ~5 minutes

### 3. [P3] maintainability — `spec/02-coding-guidelines/98-changelog.md`

**Action:** After applying the above fixes, bump version (≥ minor) and add a row to `98-changelog.md` summarising what changed.

**Acceptance test:** Given `spec/02-coding-guidelines/98-changelog.md`, When read, Then top-most version row MUST be dated 2026-04-25 and reference the fixes above.

**Effort estimate:** ~5 minutes

## Source metrics (from deterministic audit)

```json
{
  "ac_chars": 3327,
  "ac_count": 5,
  "child_modules": 16,
  "code_blocks_by_lang": {
    "bash": 1,
    "go": 16,
    "plain": 2,
    "sql": 1,
    "ts": 1,
    "typescript": 11
  },
  "code_blocks_total": 32,
  "consistency_report": true,
  "gwt_block_count": 0,
  "has_json_schema": false,
  "has_mermaid": false,
  "has_sql_ddl": true,
  "has_ts_enums": true,
  "has_yaml_openapi": false,
  "links_broken": 1,
  "links_total": 31,
  "md_files": 6,
  "mmd_files": 0,
  "overview_chars": 10822,
  "todo_density": 0,
  "waffle_per_kchar": 0.1
}
```
