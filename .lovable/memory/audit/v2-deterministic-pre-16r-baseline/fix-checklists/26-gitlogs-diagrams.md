# Fix Checklist — `spec/26-gitlogs-diagrams`

**Generated:** 2026-04-25  
**Current score:** 59/100 (D)  
**Implementability:** 35/100  
**Estimated effort:** ~95 min  
**Impact-weighted backlog:** 21 points

## Actions

| # | Pri | Category | Target file | Effort | Action |
|---:|:--:|---|---|---:|---|
| 1 | **P0** | missing-contract | `spec/26-gitlogs-diagrams/00-overview.md` | 30m | Inline a ```text``` fenced block containing the normative contract block (DDL / schema / enum / OpenAPI). Do not link to a sibling file — paste the contract directly. |
| 2 | **P0** | untestable | `spec/26-gitlogs-diagrams/97-acceptance-criteria.md` | 60m | Run `python3 linter-scripts/generate-gwt-acceptance.py` to scaffold ACs, then hand-edit each into a Given/When/Then triplet. |
| 3 | **P3** | maintainability | `spec/26-gitlogs-diagrams/98-changelog.md` | 5m | After applying the above fixes, bump version (≥ minor) and add a row to `98-changelog.md` summarising what changed. |

## Detail + Acceptance test for each action

### 1. [P0] missing-contract — `spec/26-gitlogs-diagrams/00-overview.md`

**Action:** Inline a ```text``` fenced block containing the normative contract block (DDL / schema / enum / OpenAPI). Do not link to a sibling file — paste the contract directly.

**Acceptance test:** Given `spec/26-gitlogs-diagrams/00-overview.md`, When grepped, Then it MUST contain at least one ```text fenced code block ≥10 non-blank lines.

**Effort estimate:** ~30 minutes

### 2. [P0] untestable — `spec/26-gitlogs-diagrams/97-acceptance-criteria.md`

**Action:** Run `python3 linter-scripts/generate-gwt-acceptance.py` to scaffold ACs, then hand-edit each into a Given/When/Then triplet.

**Acceptance test:** Given `spec/26-gitlogs-diagrams/97-acceptance-criteria.md`, When parsed, Then it MUST contain ≥3 `### AC-` headings each followed by a `**Given** … **When** … **Then**` block.

**Effort estimate:** ~60 minutes

### 3. [P3] maintainability — `spec/26-gitlogs-diagrams/98-changelog.md`

**Action:** After applying the above fixes, bump version (≥ minor) and add a row to `98-changelog.md` summarising what changed.

**Acceptance test:** Given `spec/26-gitlogs-diagrams/98-changelog.md`, When read, Then top-most version row MUST be dated 2026-04-25 and reference the fixes above.

**Effort estimate:** ~5 minutes

## Source metrics (from deterministic audit)

```json
{
  "ac_chars": 1837,
  "ac_count": 0,
  "child_modules": 0,
  "code_blocks_by_lang": {},
  "code_blocks_total": 0,
  "consistency_report": true,
  "gwt_block_count": 0,
  "has_json_schema": false,
  "has_mermaid": true,
  "has_sql_ddl": false,
  "has_ts_enums": false,
  "has_yaml_openapi": false,
  "links_broken": 0,
  "links_total": 11,
  "md_files": 4,
  "mmd_files": 8,
  "overview_chars": 1490,
  "todo_density": 0,
  "waffle_per_kchar": 0.0
}
```
