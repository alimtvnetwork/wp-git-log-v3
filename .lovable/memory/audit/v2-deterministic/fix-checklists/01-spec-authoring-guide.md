# Fix Checklist — `spec/01-spec-authoring-guide`

**Generated:** 2026-04-25  
**Current score:** 54/100 (D)  
**Implementability:** 40/100  
**Estimated effort:** ~130 min  
**Impact-weighted backlog:** 22 points

## Actions

| # | Pri | Category | Target file | Effort | Action |
|---:|:--:|---|---|---:|---|
| 1 | **P0** | missing-contract | `spec/01-spec-authoring-guide/00-overview.md` | 30m | Inline a ```text``` fenced block containing the normative contract block (DDL / schema / enum / OpenAPI). Do not link to a sibling file — paste the contract directly. |
| 2 | **P1** | untestable | `spec/01-spec-authoring-guide/97-acceptance-criteria.md` | 20m | Rewrite 4 acceptance criterion/criteria into Given/When/Then form. |
| 3 | **P1** | broken-link | `spec/01-spec-authoring-guide/` | 55m | Run `python3 linter-scripts/check-spec-cross-links.py --root spec/01-spec-authoring-guide` then either (a) fix each path or (b) add the link to `linter-scripts/spec-cross-links.allowlist` with a justification comment. |
| 4 | **P3** | drift | `spec/01-spec-authoring-guide/*.md` | 20m | Resolve 2 TODO/TBD/FIXME marker(s). Either implement the missing detail or move the marker into a tracked AC. |
| 5 | **P3** | maintainability | `spec/01-spec-authoring-guide/98-changelog.md` | 5m | After applying the above fixes, bump version (≥ minor) and add a row to `98-changelog.md` summarising what changed. |

## Detail + Acceptance test for each action

### 1. [P0] missing-contract — `spec/01-spec-authoring-guide/00-overview.md`

**Action:** Inline a ```text``` fenced block containing the normative contract block (DDL / schema / enum / OpenAPI). Do not link to a sibling file — paste the contract directly.

**Acceptance test:** Given `spec/01-spec-authoring-guide/00-overview.md`, When grepped, Then it MUST contain at least one ```text fenced code block ≥10 non-blank lines.

**Effort estimate:** ~30 minutes

### 2. [P1] untestable — `spec/01-spec-authoring-guide/97-acceptance-criteria.md`

**Action:** Rewrite 4 acceptance criterion/criteria into Given/When/Then form.

**Acceptance test:** Given `spec/01-spec-authoring-guide/97-acceptance-criteria.md`, When parsed, Then `gwt_block_count` MUST equal `ac_count` (4).

**Effort estimate:** ~20 minutes

### 3. [P1] broken-link — `spec/01-spec-authoring-guide/`

**Action:** Run `python3 linter-scripts/check-spec-cross-links.py --root spec/01-spec-authoring-guide` then either (a) fix each path or (b) add the link to `linter-scripts/spec-cross-links.allowlist` with a justification comment.

**Acceptance test:** Given `python3 linter-scripts/check-spec-cross-links.py --root spec/01-spec-authoring-guide`, When run, Then exit code MUST be 0.

**Effort estimate:** ~55 minutes

### 4. [P3] drift — `spec/01-spec-authoring-guide/*.md`

**Action:** Resolve 2 TODO/TBD/FIXME marker(s). Either implement the missing detail or move the marker into a tracked AC.

**Acceptance test:** Given the module body, When grep'd for `TODO|TBD|FIXME|XXX|HACK`, Then 0 matches.

**Effort estimate:** ~20 minutes

### 5. [P3] maintainability — `spec/01-spec-authoring-guide/98-changelog.md`

**Action:** After applying the above fixes, bump version (≥ minor) and add a row to `98-changelog.md` summarising what changed.

**Acceptance test:** Given `spec/01-spec-authoring-guide/98-changelog.md`, When read, Then top-most version row MUST be dated 2026-04-25 and reference the fixes above.

**Effort estimate:** ~5 minutes

## Source metrics (from deterministic audit)

```json
{
  "ac_chars": 2490,
  "ac_count": 4,
  "child_modules": 0,
  "code_blocks_by_lang": {
    "bash": 3,
    "html": 2,
    "markdown": 23,
    "plain": 40
  },
  "code_blocks_total": 68,
  "consistency_report": true,
  "gwt_block_count": 0,
  "has_json_schema": false,
  "has_mermaid": false,
  "has_sql_ddl": false,
  "has_ts_enums": false,
  "has_yaml_openapi": false,
  "links_broken": 11,
  "links_total": 61,
  "md_files": 16,
  "mmd_files": 0,
  "overview_chars": 22937,
  "todo_density": 2,
  "waffle_per_kchar": 0.33
}
```
