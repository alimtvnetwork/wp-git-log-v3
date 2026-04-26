# Fix Checklist — `spec/14-update/24-update-check-mechanism`

**Generated:** 2026-04-25  
**Current score:** 75/100 (B)  
**Implementability:** 75/100  
**Estimated effort:** ~65 min  
**Impact-weighted backlog:** 11 points

## Actions

| # | Pri | Category | Target file | Effort | Action |
|---:|:--:|---|---|---:|---|
| 1 | **P0** | untestable | `spec/14-update/24-update-check-mechanism/97-acceptance-criteria.md` | 60m | Run `python3 linter-scripts/generate-gwt-acceptance.py` to scaffold ACs, then hand-edit each into a Given/When/Then triplet. |
| 2 | **P3** | maintainability | `spec/14-update/24-update-check-mechanism/98-changelog.md` | 5m | After applying the above fixes, bump version (≥ minor) and add a row to `98-changelog.md` summarising what changed. |

## Detail + Acceptance test for each action

### 1. [P0] untestable — `spec/14-update/24-update-check-mechanism/97-acceptance-criteria.md`

**Action:** Run `python3 linter-scripts/generate-gwt-acceptance.py` to scaffold ACs, then hand-edit each into a Given/When/Then triplet.

**Acceptance test:** Given `spec/14-update/24-update-check-mechanism/97-acceptance-criteria.md`, When parsed, Then it MUST contain ≥3 `### AC-` headings each followed by a `**Given** … **When** … **Then**` block.

**Effort estimate:** ~60 minutes

### 2. [P3] maintainability — `spec/14-update/24-update-check-mechanism/98-changelog.md`

**Action:** After applying the above fixes, bump version (≥ minor) and add a row to `98-changelog.md` summarising what changed.

**Acceptance test:** Given `spec/14-update/24-update-check-mechanism/98-changelog.md`, When read, Then top-most version row MUST be dated 2026-04-25 and reference the fixes above.

**Effort estimate:** ~5 minutes

## Source metrics (from deterministic audit)

```json
{
  "ac_chars": 4402,
  "ac_count": 0,
  "child_modules": 0,
  "code_blocks_by_lang": {
    "bash": 1,
    "go": 1,
    "json": 4,
    "jsonc": 1,
    "plain": 17,
    "powershell": 1,
    "sql": 1
  },
  "code_blocks_total": 26,
  "consistency_report": true,
  "gwt_block_count": 0,
  "has_json_schema": true,
  "has_mermaid": false,
  "has_sql_ddl": true,
  "has_ts_enums": false,
  "has_yaml_openapi": false,
  "links_broken": 0,
  "links_total": 60,
  "md_files": 14,
  "mmd_files": 0,
  "overview_chars": 5211,
  "todo_density": 0,
  "waffle_per_kchar": 0.06
}
```
