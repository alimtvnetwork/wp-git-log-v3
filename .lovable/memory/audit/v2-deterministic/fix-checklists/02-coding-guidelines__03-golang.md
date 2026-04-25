# Fix Checklist — `spec/02-coding-guidelines/03-golang`

**Generated:** 2026-04-25  
**Current score:** 68/100 (C)  
**Implementability:** 40/100  
**Estimated effort:** ~45 min  
**Impact-weighted backlog:** 16 points

## Actions

| # | Pri | Category | Target file | Effort | Action |
|---:|:--:|---|---|---:|---|
| 1 | **P0** | missing-contract | `spec/02-coding-guidelines/03-golang/00-overview.md` | 30m | Inline a ```text``` fenced block containing the normative contract block (DDL / schema / enum / OpenAPI). Do not link to a sibling file — paste the contract directly. |
| 2 | **P1** | untestable | `spec/02-coding-guidelines/03-golang/97-acceptance-criteria.md` | 10m | Rewrite 2 acceptance criterion/criteria into Given/When/Then form. |
| 3 | **P3** | maintainability | `spec/02-coding-guidelines/03-golang/98-changelog.md` | 5m | After applying the above fixes, bump version (≥ minor) and add a row to `98-changelog.md` summarising what changed. |

## Detail + Acceptance test for each action

### 1. [P0] missing-contract — `spec/02-coding-guidelines/03-golang/00-overview.md`

**Action:** Inline a ```text``` fenced block containing the normative contract block (DDL / schema / enum / OpenAPI). Do not link to a sibling file — paste the contract directly.

**Acceptance test:** Given `spec/02-coding-guidelines/03-golang/00-overview.md`, When grepped, Then it MUST contain at least one ```text fenced code block ≥10 non-blank lines.

**Effort estimate:** ~30 minutes

### 2. [P1] untestable — `spec/02-coding-guidelines/03-golang/97-acceptance-criteria.md`

**Action:** Rewrite 2 acceptance criterion/criteria into Given/When/Then form.

**Acceptance test:** Given `spec/02-coding-guidelines/03-golang/97-acceptance-criteria.md`, When parsed, Then `gwt_block_count` MUST equal `ac_count` (2).

**Effort estimate:** ~10 minutes

### 3. [P3] maintainability — `spec/02-coding-guidelines/03-golang/98-changelog.md`

**Action:** After applying the above fixes, bump version (≥ minor) and add a row to `98-changelog.md` summarising what changed.

**Acceptance test:** Given `spec/02-coding-guidelines/03-golang/98-changelog.md`, When read, Then top-most version row MUST be dated 2026-04-25 and reference the fixes above.

**Effort estimate:** ~5 minutes

## Source metrics (from deterministic audit)

```json
{
  "ac_chars": 632,
  "ac_count": 2,
  "child_modules": 2,
  "code_blocks_by_lang": {
    "go": 43,
    "plain": 2
  },
  "code_blocks_total": 45,
  "consistency_report": true,
  "gwt_block_count": 0,
  "has_json_schema": false,
  "has_mermaid": false,
  "has_sql_ddl": false,
  "has_ts_enums": false,
  "has_yaml_openapi": false,
  "links_broken": 0,
  "links_total": 19,
  "md_files": 11,
  "mmd_files": 0,
  "overview_chars": 1170,
  "todo_density": 0,
  "waffle_per_kchar": 0.19
}
```
