# Fix Checklist — `spec/03-error-manage/02-error-architecture/04-error-modal/02-react-components`

**Generated:** 2026-04-25  
**Current score:** 66/100 (C)  
**Implementability:** 50/100  
**Estimated effort:** ~65 min  
**Impact-weighted backlog:** 11 points

## Actions

| # | Pri | Category | Target file | Effort | Action |
|---:|:--:|---|---|---:|---|
| 1 | **P0** | untestable | `spec/03-error-manage/02-error-architecture/04-error-modal/02-react-components/97-acceptance-criteria.md` | 60m | Run `python3 linter-scripts/generate-gwt-acceptance.py` to scaffold ACs, then hand-edit each into a Given/When/Then triplet. |
| 2 | **P3** | maintainability | `spec/03-error-manage/02-error-architecture/04-error-modal/02-react-components/98-changelog.md` | 5m | After applying the above fixes, bump version (≥ minor) and add a row to `98-changelog.md` summarising what changed. |

## Detail + Acceptance test for each action

### 1. [P0] untestable — `spec/03-error-manage/02-error-architecture/04-error-modal/02-react-components/97-acceptance-criteria.md`

**Action:** Run `python3 linter-scripts/generate-gwt-acceptance.py` to scaffold ACs, then hand-edit each into a Given/When/Then triplet.

**Acceptance test:** Given `spec/03-error-manage/02-error-architecture/04-error-modal/02-react-components/97-acceptance-criteria.md`, When parsed, Then it MUST contain ≥3 `### AC-` headings each followed by a `**Given** … **When** … **Then**` block.

**Effort estimate:** ~60 minutes

### 2. [P3] maintainability — `spec/03-error-manage/02-error-architecture/04-error-modal/02-react-components/98-changelog.md`

**Action:** After applying the above fixes, bump version (≥ minor) and add a row to `98-changelog.md` summarising what changed.

**Acceptance test:** Given `spec/03-error-manage/02-error-architecture/04-error-modal/02-react-components/98-changelog.md`, When read, Then top-most version row MUST be dated 2026-04-25 and reference the fixes above.

**Effort estimate:** ~5 minutes

## Source metrics (from deterministic audit)

```json
{
  "ac_chars": 0,
  "ac_count": 0,
  "child_modules": 0,
  "code_blocks_by_lang": {
    "css": 1,
    "plain": 2,
    "ts": 1,
    "tsx": 2,
    "typescript": 15
  },
  "code_blocks_total": 21,
  "consistency_report": true,
  "gwt_block_count": 0,
  "has_json_schema": false,
  "has_mermaid": false,
  "has_sql_ddl": false,
  "has_ts_enums": true,
  "has_yaml_openapi": false,
  "links_broken": 0,
  "links_total": 24,
  "md_files": 10,
  "mmd_files": 0,
  "overview_chars": 2918,
  "todo_density": 0,
  "waffle_per_kchar": 0.0
}
```
