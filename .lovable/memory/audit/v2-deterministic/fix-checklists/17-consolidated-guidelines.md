# Fix Checklist — `spec/17-consolidated-guidelines`

**Generated:** 2026-04-25  
**Current score:** 86/100 (A)  
**Implementability:** 95/100  
**Estimated effort:** ~70 min  
**Impact-weighted backlog:** 7 points

## Actions

| # | Pri | Category | Target file | Effort | Action |
|---:|:--:|---|---|---:|---|
| 1 | **P1** | broken-link | `spec/17-consolidated-guidelines/` | 5m | Run `python3 linter-scripts/check-spec-cross-links.py --root spec/17-consolidated-guidelines` then either (a) fix each path or (b) add the link to `linter-scripts/spec-cross-links.allowlist` with a justification comment. |
| 2 | **P3** | drift | `spec/17-consolidated-guidelines/*.md` | 60m | Resolve 6 TODO/TBD/FIXME marker(s). Either implement the missing detail or move the marker into a tracked AC. |
| 3 | **P3** | maintainability | `spec/17-consolidated-guidelines/98-changelog.md` | 5m | After applying the above fixes, bump version (≥ minor) and add a row to `98-changelog.md` summarising what changed. |

## Detail + Acceptance test for each action

### 1. [P1] broken-link — `spec/17-consolidated-guidelines/`

**Action:** Run `python3 linter-scripts/check-spec-cross-links.py --root spec/17-consolidated-guidelines` then either (a) fix each path or (b) add the link to `linter-scripts/spec-cross-links.allowlist` with a justification comment.

**Acceptance test:** Given `python3 linter-scripts/check-spec-cross-links.py --root spec/17-consolidated-guidelines`, When run, Then exit code MUST be 0.

**Effort estimate:** ~5 minutes

### 2. [P3] drift — `spec/17-consolidated-guidelines/*.md`

**Action:** Resolve 6 TODO/TBD/FIXME marker(s). Either implement the missing detail or move the marker into a tracked AC.

**Acceptance test:** Given the module body, When grep'd for `TODO|TBD|FIXME|XXX|HACK`, Then 0 matches.

**Effort estimate:** ~60 minutes

### 3. [P3] maintainability — `spec/17-consolidated-guidelines/98-changelog.md`

**Action:** After applying the above fixes, bump version (≥ minor) and add a row to `98-changelog.md` summarising what changed.

**Acceptance test:** Given `spec/17-consolidated-guidelines/98-changelog.md`, When read, Then top-most version row MUST be dated 2026-04-25 and reference the fixes above.

**Effort estimate:** ~5 minutes

## Source metrics (from deterministic audit)

```json
{
  "ac_chars": 3464,
  "ac_count": 5,
  "child_modules": 0,
  "code_blocks_by_lang": {
    "bash": 6,
    "csharp": 1,
    "css": 24,
    "gitignore": 1,
    "go": 42,
    "html": 4,
    "ini": 1,
    "json": 13,
    "markdown": 7,
    "php": 13,
    "plain": 188,
    "powershell": 12,
    "regex": 1,
    "rust": 4,
    "sql": 10,
    "toml": 1,
    "ts": 2,
    "typescript": 18,
    "yaml": 3
  },
  "code_blocks_total": 351,
  "consistency_report": true,
  "gwt_block_count": 5,
  "has_json_schema": true,
  "has_mermaid": false,
  "has_sql_ddl": true,
  "has_ts_enums": true,
  "has_yaml_openapi": true,
  "links_broken": 1,
  "links_total": 144,
  "md_files": 35,
  "mmd_files": 0,
  "overview_chars": 7300,
  "todo_density": 6,
  "waffle_per_kchar": 0.06
}
```
