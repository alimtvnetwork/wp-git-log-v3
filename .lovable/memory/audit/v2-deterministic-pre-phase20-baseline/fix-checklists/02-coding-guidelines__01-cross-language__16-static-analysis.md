# Fix Checklist — `spec/02-coding-guidelines/01-cross-language/16-static-analysis`

**Generated:** 2026-04-25  
**Current score:** 64/100 (C)  
**Implementability:** 50/100  
**Estimated effort:** ~85 min  
**Impact-weighted backlog:** 12 points

## Actions

| # | Pri | Category | Target file | Effort | Action |
|---:|:--:|---|---|---:|---|
| 1 | **P0** | untestable | `spec/02-coding-guidelines/01-cross-language/16-static-analysis/97-acceptance-criteria.md` | 60m | Run `python3 linter-scripts/generate-gwt-acceptance.py` to scaffold ACs, then hand-edit each into a Given/When/Then triplet. |
| 2 | **P3** | drift | `spec/02-coding-guidelines/01-cross-language/16-static-analysis/*.md` | 20m | Resolve 2 TODO/TBD/FIXME marker(s). Either implement the missing detail or move the marker into a tracked AC. |
| 3 | **P3** | maintainability | `spec/02-coding-guidelines/01-cross-language/16-static-analysis/98-changelog.md` | 5m | After applying the above fixes, bump version (≥ minor) and add a row to `98-changelog.md` summarising what changed. |

## Detail + Acceptance test for each action

### 1. [P0] untestable — `spec/02-coding-guidelines/01-cross-language/16-static-analysis/97-acceptance-criteria.md`

**Action:** Run `python3 linter-scripts/generate-gwt-acceptance.py` to scaffold ACs, then hand-edit each into a Given/When/Then triplet.

**Acceptance test:** Given `spec/02-coding-guidelines/01-cross-language/16-static-analysis/97-acceptance-criteria.md`, When parsed, Then it MUST contain ≥3 `### AC-` headings each followed by a `**Given** … **When** … **Then**` block.

**Effort estimate:** ~60 minutes

### 2. [P3] drift — `spec/02-coding-guidelines/01-cross-language/16-static-analysis/*.md`

**Action:** Resolve 2 TODO/TBD/FIXME marker(s). Either implement the missing detail or move the marker into a tracked AC.

**Acceptance test:** Given the module body, When grep'd for `TODO|TBD|FIXME|XXX|HACK`, Then 0 matches.

**Effort estimate:** ~20 minutes

### 3. [P3] maintainability — `spec/02-coding-guidelines/01-cross-language/16-static-analysis/98-changelog.md`

**Action:** After applying the above fixes, bump version (≥ minor) and add a row to `98-changelog.md` summarising what changed.

**Acceptance test:** Given `spec/02-coding-guidelines/01-cross-language/16-static-analysis/98-changelog.md`, When read, Then top-most version row MUST be dated 2026-04-25 and reference the fixes above.

**Effort estimate:** ~5 minutes

## Source metrics (from deterministic audit)

```json
{
  "ac_chars": 1024,
  "ac_count": 0,
  "child_modules": 0,
  "code_blocks_by_lang": {
    "bash": 6,
    "ini": 4,
    "js": 1,
    "neon": 1,
    "plain": 1,
    "properties": 1,
    "rust": 1,
    "toml": 4,
    "xml": 3,
    "yaml": 5
  },
  "code_blocks_total": 27,
  "consistency_report": true,
  "gwt_block_count": 0,
  "has_json_schema": false,
  "has_mermaid": false,
  "has_sql_ddl": false,
  "has_ts_enums": false,
  "has_yaml_openapi": true,
  "links_broken": 0,
  "links_total": 155,
  "md_files": 13,
  "mmd_files": 0,
  "overview_chars": 5988,
  "todo_density": 2,
  "waffle_per_kchar": 0.22
}
```
