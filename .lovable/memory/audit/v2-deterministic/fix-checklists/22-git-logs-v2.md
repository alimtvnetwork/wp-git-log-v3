# Fix Checklist — `spec/22-git-logs-v2`

**Generated:** 2026-04-25  
**Current score:** 76/100 (B)  
**Implementability:** 85/100  
**Estimated effort:** ~85 min  
**Impact-weighted backlog:** 12 points

## Actions

| # | Pri | Category | Target file | Effort | Action |
|---:|:--:|---|---|---:|---|
| 1 | **P0** | untestable | `spec/22-git-logs-v2/97-acceptance-criteria.md` | 60m | Run `python3 linter-scripts/generate-gwt-acceptance.py` to scaffold ACs, then hand-edit each into a Given/When/Then triplet. |
| 2 | **P3** | drift | `spec/22-git-logs-v2/*.md` | 20m | Resolve 2 TODO/TBD/FIXME marker(s). Either implement the missing detail or move the marker into a tracked AC. |
| 3 | **P3** | maintainability | `spec/22-git-logs-v2/98-changelog.md` | 5m | After applying the above fixes, bump version (≥ minor) and add a row to `98-changelog.md` summarising what changed. |

## Detail + Acceptance test for each action

### 1. [P0] untestable — `spec/22-git-logs-v2/97-acceptance-criteria.md`

**Action:** Run `python3 linter-scripts/generate-gwt-acceptance.py` to scaffold ACs, then hand-edit each into a Given/When/Then triplet.

**Acceptance test:** Given `spec/22-git-logs-v2/97-acceptance-criteria.md`, When parsed, Then it MUST contain ≥3 `### AC-` headings each followed by a `**Given** … **When** … **Then**` block.

**Effort estimate:** ~60 minutes

### 2. [P3] drift — `spec/22-git-logs-v2/*.md`

**Action:** Resolve 2 TODO/TBD/FIXME marker(s). Either implement the missing detail or move the marker into a tracked AC.

**Acceptance test:** Given the module body, When grep'd for `TODO|TBD|FIXME|XXX|HACK`, Then 0 matches.

**Effort estimate:** ~20 minutes

### 3. [P3] maintainability — `spec/22-git-logs-v2/98-changelog.md`

**Action:** After applying the above fixes, bump version (≥ minor) and add a row to `98-changelog.md` summarising what changed.

**Acceptance test:** Given `spec/22-git-logs-v2/98-changelog.md`, When read, Then top-most version row MUST be dated 2026-04-25 and reference the fixes above.

**Effort estimate:** ~5 minutes

## Source metrics (from deterministic audit)

```json
{
  "ac_chars": 5902,
  "ac_count": 0,
  "child_modules": 0,
  "code_blocks_by_lang": {
    "bash": 12,
    "bats": 8,
    "json": 16,
    "php": 11,
    "plain": 35,
    "sql": 1,
    "text": 2,
    "yaml": 4
  },
  "code_blocks_total": 89,
  "consistency_report": true,
  "gwt_block_count": 0,
  "has_json_schema": true,
  "has_mermaid": false,
  "has_sql_ddl": true,
  "has_ts_enums": false,
  "has_yaml_openapi": true,
  "links_broken": 0,
  "links_total": 45,
  "md_files": 33,
  "mmd_files": 0,
  "overview_chars": 6279,
  "todo_density": 2,
  "waffle_per_kchar": 0.08
}
```
