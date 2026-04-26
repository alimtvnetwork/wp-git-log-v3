# Fix Checklist — `spec/02-coding-guidelines/11-security`

**Generated:** 2026-04-25  
**Current score:** 73/100 (C)  
**Implementability:** 30/100  
**Estimated effort:** ~40 min  
**Impact-weighted backlog:** 16 points

## Actions

| # | Pri | Category | Target file | Effort | Action |
|---:|:--:|---|---|---:|---|
| 1 | **P0** | missing-contract | `spec/02-coding-guidelines/11-security/00-overview.md` | 30m | Inline a ```text``` fenced block containing the normative contract block (DDL / schema / enum / OpenAPI). Do not link to a sibling file — paste the contract directly. |
| 2 | **P1** | untestable | `spec/02-coding-guidelines/11-security/97-acceptance-criteria.md` | 5m | Rewrite 1 acceptance criterion/criteria into Given/When/Then form. |
| 3 | **P3** | maintainability | `spec/02-coding-guidelines/11-security/98-changelog.md` | 5m | After applying the above fixes, bump version (≥ minor) and add a row to `98-changelog.md` summarising what changed. |

## Detail + Acceptance test for each action

### 1. [P0] missing-contract — `spec/02-coding-guidelines/11-security/00-overview.md`

**Action:** Inline a ```text``` fenced block containing the normative contract block (DDL / schema / enum / OpenAPI). Do not link to a sibling file — paste the contract directly.

**Acceptance test:** Given `spec/02-coding-guidelines/11-security/00-overview.md`, When grepped, Then it MUST contain at least one ```text fenced code block ≥10 non-blank lines.

**Effort estimate:** ~30 minutes

### 2. [P1] untestable — `spec/02-coding-guidelines/11-security/97-acceptance-criteria.md`

**Action:** Rewrite 1 acceptance criterion/criteria into Given/When/Then form.

**Acceptance test:** Given `spec/02-coding-guidelines/11-security/97-acceptance-criteria.md`, When parsed, Then `gwt_block_count` MUST equal `ac_count` (6).

**Effort estimate:** ~5 minutes

### 3. [P3] maintainability — `spec/02-coding-guidelines/11-security/98-changelog.md`

**Action:** After applying the above fixes, bump version (≥ minor) and add a row to `98-changelog.md` summarising what changed.

**Acceptance test:** Given `spec/02-coding-guidelines/11-security/98-changelog.md`, When read, Then top-most version row MUST be dated 2026-04-25 and reference the fixes above.

**Effort estimate:** ~5 minutes

## Source metrics (from deterministic audit)

```json
{
  "ac_chars": 3192,
  "ac_count": 6,
  "child_modules": 1,
  "code_blocks_by_lang": {
    "plain": 1
  },
  "code_blocks_total": 1,
  "consistency_report": true,
  "gwt_block_count": 5,
  "has_json_schema": false,
  "has_mermaid": false,
  "has_sql_ddl": false,
  "has_ts_enums": false,
  "has_yaml_openapi": false,
  "links_broken": 0,
  "links_total": 10,
  "md_files": 4,
  "mmd_files": 0,
  "overview_chars": 2282,
  "todo_density": 0,
  "waffle_per_kchar": 0.13
}
```
