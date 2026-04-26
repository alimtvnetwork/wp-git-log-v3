# Fix Checklist — `spec/02-coding-guidelines/03-golang/01-enum-specification`

**Generated:** 2026-04-25  
**Current score:** 63/100 (C)  
**Implementability:** 40/100  
**Estimated effort:** ~95 min  
**Impact-weighted backlog:** 21 points

## Actions

| # | Pri | Category | Target file | Effort | Action |
|---:|:--:|---|---|---:|---|
| 1 | **P0** | missing-contract | `spec/02-coding-guidelines/03-golang/01-enum-specification/00-overview.md` | 30m | Inline a ```ts``` fenced block containing the TypeScript `enum` or `as const` literal union. Do not link to a sibling file — paste the contract directly. |
| 2 | **P0** | untestable | `spec/02-coding-guidelines/03-golang/01-enum-specification/97-acceptance-criteria.md` | 60m | Run `python3 linter-scripts/generate-gwt-acceptance.py` to scaffold ACs, then hand-edit each into a Given/When/Then triplet. |
| 3 | **P3** | maintainability | `spec/02-coding-guidelines/03-golang/01-enum-specification/98-changelog.md` | 5m | After applying the above fixes, bump version (≥ minor) and add a row to `98-changelog.md` summarising what changed. |

## Detail + Acceptance test for each action

### 1. [P0] missing-contract — `spec/02-coding-guidelines/03-golang/01-enum-specification/00-overview.md`

**Action:** Inline a ```ts``` fenced block containing the TypeScript `enum` or `as const` literal union. Do not link to a sibling file — paste the contract directly.

**Acceptance test:** Given `spec/02-coding-guidelines/03-golang/01-enum-specification/00-overview.md`, When grepped, Then it MUST contain at least one ```ts fenced code block ≥10 non-blank lines.

**Effort estimate:** ~30 minutes

### 2. [P0] untestable — `spec/02-coding-guidelines/03-golang/01-enum-specification/97-acceptance-criteria.md`

**Action:** Run `python3 linter-scripts/generate-gwt-acceptance.py` to scaffold ACs, then hand-edit each into a Given/When/Then triplet.

**Acceptance test:** Given `spec/02-coding-guidelines/03-golang/01-enum-specification/97-acceptance-criteria.md`, When parsed, Then it MUST contain ≥3 `### AC-` headings each followed by a `**Given** … **When** … **Then**` block.

**Effort estimate:** ~60 minutes

### 3. [P3] maintainability — `spec/02-coding-guidelines/03-golang/01-enum-specification/98-changelog.md`

**Action:** After applying the above fixes, bump version (≥ minor) and add a row to `98-changelog.md` summarising what changed.

**Acceptance test:** Given `spec/02-coding-guidelines/03-golang/01-enum-specification/98-changelog.md`, When read, Then top-most version row MUST be dated 2026-04-25 and reference the fixes above.

**Effort estimate:** ~5 minutes

## Source metrics (from deterministic audit)

```json
{
  "ac_chars": 0,
  "ac_count": 0,
  "child_modules": 0,
  "code_blocks_by_lang": {
    "bash": 2,
    "go": 55,
    "markdown": 1,
    "plain": 11
  },
  "code_blocks_total": 69,
  "consistency_report": true,
  "gwt_block_count": 0,
  "has_json_schema": false,
  "has_mermaid": false,
  "has_sql_ddl": false,
  "has_ts_enums": false,
  "has_yaml_openapi": false,
  "links_broken": 0,
  "links_total": 10,
  "md_files": 7,
  "mmd_files": 0,
  "overview_chars": 5774,
  "todo_density": 0,
  "waffle_per_kchar": 0.11
}
```
