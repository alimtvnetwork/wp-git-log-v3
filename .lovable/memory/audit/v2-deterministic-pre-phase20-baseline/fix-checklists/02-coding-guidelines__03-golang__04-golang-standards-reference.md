# Fix Checklist — `spec/02-coding-guidelines/03-golang/04-golang-standards-reference`

**Generated:** 2026-04-25  
**Current score:** 72/100 (C)  
**Implementability:** 40/100  
**Estimated effort:** ~45 min  
**Impact-weighted backlog:** 21 points

## Actions

| # | Pri | Category | Target file | Effort | Action |
|---:|:--:|---|---|---:|---|
| 1 | **P0** | missing-contract | `spec/02-coding-guidelines/03-golang/04-golang-standards-reference/00-overview.md` | 30m | Inline a ```text``` fenced block containing the normative contract block (DDL / schema / enum / OpenAPI). Do not link to a sibling file — paste the contract directly. |
| 2 | **P1** | untestable | `spec/02-coding-guidelines/03-golang/04-golang-standards-reference/97-acceptance-criteria.md` | 5m | Rewrite 1 acceptance criterion/criteria into Given/When/Then form. |
| 3 | **P1** | broken-link | `spec/02-coding-guidelines/03-golang/04-golang-standards-reference/` | 5m | Run `python3 linter-scripts/check-spec-cross-links.py --root spec/02-coding-guidelines/03-golang/04-golang-standards-reference` then either (a) fix each path or (b) add the link to `linter-scripts/spec-cross-links.allowlist` with a justification comment. |
| 4 | **P3** | maintainability | `spec/02-coding-guidelines/03-golang/04-golang-standards-reference/98-changelog.md` | 5m | After applying the above fixes, bump version (≥ minor) and add a row to `98-changelog.md` summarising what changed. |

## Detail + Acceptance test for each action

### 1. [P0] missing-contract — `spec/02-coding-guidelines/03-golang/04-golang-standards-reference/00-overview.md`

**Action:** Inline a ```text``` fenced block containing the normative contract block (DDL / schema / enum / OpenAPI). Do not link to a sibling file — paste the contract directly.

**Acceptance test:** Given `spec/02-coding-guidelines/03-golang/04-golang-standards-reference/00-overview.md`, When grepped, Then it MUST contain at least one ```text fenced code block ≥10 non-blank lines.

**Effort estimate:** ~30 minutes

### 2. [P1] untestable — `spec/02-coding-guidelines/03-golang/04-golang-standards-reference/97-acceptance-criteria.md`

**Action:** Rewrite 1 acceptance criterion/criteria into Given/When/Then form.

**Acceptance test:** Given `spec/02-coding-guidelines/03-golang/04-golang-standards-reference/97-acceptance-criteria.md`, When parsed, Then `gwt_block_count` MUST equal `ac_count` (10).

**Effort estimate:** ~5 minutes

### 3. [P1] broken-link — `spec/02-coding-guidelines/03-golang/04-golang-standards-reference/`

**Action:** Run `python3 linter-scripts/check-spec-cross-links.py --root spec/02-coding-guidelines/03-golang/04-golang-standards-reference` then either (a) fix each path or (b) add the link to `linter-scripts/spec-cross-links.allowlist` with a justification comment.

**Acceptance test:** Given `python3 linter-scripts/check-spec-cross-links.py --root spec/02-coding-guidelines/03-golang/04-golang-standards-reference`, When run, Then exit code MUST be 0.

**Effort estimate:** ~5 minutes

### 4. [P3] maintainability — `spec/02-coding-guidelines/03-golang/04-golang-standards-reference/98-changelog.md`

**Action:** After applying the above fixes, bump version (≥ minor) and add a row to `98-changelog.md` summarising what changed.

**Acceptance test:** Given `spec/02-coding-guidelines/03-golang/04-golang-standards-reference/98-changelog.md`, When read, Then top-most version row MUST be dated 2026-04-25 and reference the fixes above.

**Effort estimate:** ~5 minutes

## Source metrics (from deterministic audit)

```json
{
  "ac_chars": 4955,
  "ac_count": 10,
  "child_modules": 0,
  "code_blocks_by_lang": {
    "go": 40,
    "plain": 4
  },
  "code_blocks_total": 44,
  "consistency_report": true,
  "gwt_block_count": 9,
  "has_json_schema": false,
  "has_mermaid": false,
  "has_sql_ddl": false,
  "has_ts_enums": false,
  "has_yaml_openapi": false,
  "links_broken": 1,
  "links_total": 35,
  "md_files": 9,
  "mmd_files": 0,
  "overview_chars": 2742,
  "todo_density": 0,
  "waffle_per_kchar": 0.1
}
```
