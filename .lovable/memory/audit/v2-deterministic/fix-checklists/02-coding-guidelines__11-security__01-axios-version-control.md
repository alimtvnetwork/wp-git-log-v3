# Fix Checklist — `spec/02-coding-guidelines/11-security/01-axios-version-control`

**Generated:** 2026-04-25  
**Current score:** 71/100 (C)  
**Implementability:** 65/100  
**Estimated effort:** ~65 min  
**Impact-weighted backlog:** 11 points

## Actions

| # | Pri | Category | Target file | Effort | Action |
|---:|:--:|---|---|---:|---|
| 1 | **P0** | untestable | `spec/02-coding-guidelines/11-security/01-axios-version-control/97-acceptance-criteria.md` | 60m | Run `python3 linter-scripts/generate-gwt-acceptance.py` to scaffold ACs, then hand-edit each into a Given/When/Then triplet. |
| 2 | **P3** | maintainability | `spec/02-coding-guidelines/11-security/01-axios-version-control/98-changelog.md` | 5m | After applying the above fixes, bump version (≥ minor) and add a row to `98-changelog.md` summarising what changed. |

## Detail + Acceptance test for each action

### 1. [P0] untestable — `spec/02-coding-guidelines/11-security/01-axios-version-control/97-acceptance-criteria.md`

**Action:** Run `python3 linter-scripts/generate-gwt-acceptance.py` to scaffold ACs, then hand-edit each into a Given/When/Then triplet.

**Acceptance test:** Given `spec/02-coding-guidelines/11-security/01-axios-version-control/97-acceptance-criteria.md`, When parsed, Then it MUST contain ≥3 `### AC-` headings each followed by a `**Given** … **When** … **Then**` block.

**Effort estimate:** ~60 minutes

### 2. [P3] maintainability — `spec/02-coding-guidelines/11-security/01-axios-version-control/98-changelog.md`

**Action:** After applying the above fixes, bump version (≥ minor) and add a row to `98-changelog.md` summarising what changed.

**Acceptance test:** Given `spec/02-coding-guidelines/11-security/01-axios-version-control/98-changelog.md`, When read, Then top-most version row MUST be dated 2026-04-25 and reference the fixes above.

**Effort estimate:** ~5 minutes

## Source metrics (from deterministic audit)

```json
{
  "ac_chars": 0,
  "ac_count": 0,
  "child_modules": 0,
  "code_blocks_by_lang": {
    "bash": 1,
    "json": 4,
    "plain": 3,
    "yaml": 1
  },
  "code_blocks_total": 9,
  "consistency_report": true,
  "gwt_block_count": 0,
  "has_json_schema": true,
  "has_mermaid": false,
  "has_sql_ddl": false,
  "has_ts_enums": false,
  "has_yaml_openapi": true,
  "links_broken": 0,
  "links_total": 8,
  "md_files": 4,
  "mmd_files": 0,
  "overview_chars": 3375,
  "todo_density": 0,
  "waffle_per_kchar": 0.4
}
```
