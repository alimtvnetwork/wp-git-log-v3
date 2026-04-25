# Fix Checklist — `spec/13-generic-cli`

**Generated:** 2026-04-25  
**Current score:** 83/100 (B)  
**Implementability:** 75/100  
**Estimated effort:** ~10 min  
**Impact-weighted backlog:** 6 points

## Actions

| # | Pri | Category | Target file | Effort | Action |
|---:|:--:|---|---|---:|---|
| 1 | **P1** | broken-link | `spec/13-generic-cli/` | 5m | Run `python3 linter-scripts/check-spec-cross-links.py --root spec/13-generic-cli` then either (a) fix each path or (b) add the link to `linter-scripts/spec-cross-links.allowlist` with a justification comment. |
| 2 | **P3** | maintainability | `spec/13-generic-cli/98-changelog.md` | 5m | After applying the above fixes, bump version (≥ minor) and add a row to `98-changelog.md` summarising what changed. |

## Detail + Acceptance test for each action

### 1. [P1] broken-link — `spec/13-generic-cli/`

**Action:** Run `python3 linter-scripts/check-spec-cross-links.py --root spec/13-generic-cli` then either (a) fix each path or (b) add the link to `linter-scripts/spec-cross-links.allowlist` with a justification comment.

**Acceptance test:** Given `python3 linter-scripts/check-spec-cross-links.py --root spec/13-generic-cli`, When run, Then exit code MUST be 0.

**Effort estimate:** ~5 minutes

### 2. [P3] maintainability — `spec/13-generic-cli/98-changelog.md`

**Action:** After applying the above fixes, bump version (≥ minor) and add a row to `98-changelog.md` summarising what changed.

**Acceptance test:** Given `spec/13-generic-cli/98-changelog.md`, When read, Then top-most version row MUST be dated 2026-04-25 and reference the fixes above.

**Effort estimate:** ~5 minutes

## Source metrics (from deterministic audit)

```json
{
  "ac_chars": 3117,
  "ac_count": 5,
  "child_modules": 0,
  "code_blocks_by_lang": {
    "bash": 3,
    "fish": 1,
    "go": 76,
    "json": 2,
    "markdown": 1,
    "plain": 66,
    "powershell": 2,
    "sql": 4
  },
  "code_blocks_total": 155,
  "consistency_report": true,
  "gwt_block_count": 5,
  "has_json_schema": true,
  "has_mermaid": false,
  "has_sql_ddl": true,
  "has_ts_enums": false,
  "has_yaml_openapi": false,
  "links_broken": 1,
  "links_total": 94,
  "md_files": 24,
  "mmd_files": 0,
  "overview_chars": 5356,
  "todo_density": 0,
  "waffle_per_kchar": 0.08
}
```
