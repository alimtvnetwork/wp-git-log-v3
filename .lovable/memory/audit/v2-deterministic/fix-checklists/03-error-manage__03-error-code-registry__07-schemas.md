# Fix Checklist — `spec/03-error-manage/03-error-code-registry/07-schemas`

**Generated:** 2026-04-25  
**Current score:** 65/100 (C)  
**Implementability:** 30/100  
**Estimated effort:** ~45 min  
**Impact-weighted backlog:** 21 points

## Actions

| # | Pri | Category | Target file | Effort | Action |
|---:|:--:|---|---|---:|---|
| 1 | **P0** | missing-contract | `spec/03-error-manage/03-error-code-registry/07-schemas/00-overview.md` | 30m | Inline a ```json``` fenced block containing the JSON Schema document. Do not link to a sibling file — paste the contract directly. |
| 2 | **P1** | untestable | `spec/03-error-manage/03-error-code-registry/07-schemas/97-acceptance-criteria.md` | 5m | Rewrite 1 acceptance criterion/criteria into Given/When/Then form. |
| 3 | **P1** | broken-link | `spec/03-error-manage/03-error-code-registry/07-schemas/` | 5m | Run `python3 linter-scripts/check-spec-cross-links.py --root spec/03-error-manage/03-error-code-registry/07-schemas` then either (a) fix each path or (b) add the link to `linter-scripts/spec-cross-links.allowlist` with a justification comment. |
| 4 | **P3** | maintainability | `spec/03-error-manage/03-error-code-registry/07-schemas/98-changelog.md` | 5m | After applying the above fixes, bump version (≥ minor) and add a row to `98-changelog.md` summarising what changed. |

## Detail + Acceptance test for each action

### 1. [P0] missing-contract — `spec/03-error-manage/03-error-code-registry/07-schemas/00-overview.md`

**Action:** Inline a ```json``` fenced block containing the JSON Schema document. Do not link to a sibling file — paste the contract directly.

**Acceptance test:** Given `spec/03-error-manage/03-error-code-registry/07-schemas/00-overview.md`, When grepped, Then it MUST contain at least one ```json fenced code block ≥10 non-blank lines.

**Effort estimate:** ~30 minutes

### 2. [P1] untestable — `spec/03-error-manage/03-error-code-registry/07-schemas/97-acceptance-criteria.md`

**Action:** Rewrite 1 acceptance criterion/criteria into Given/When/Then form.

**Acceptance test:** Given `spec/03-error-manage/03-error-code-registry/07-schemas/97-acceptance-criteria.md`, When parsed, Then `gwt_block_count` MUST equal `ac_count` (6).

**Effort estimate:** ~5 minutes

### 3. [P1] broken-link — `spec/03-error-manage/03-error-code-registry/07-schemas/`

**Action:** Run `python3 linter-scripts/check-spec-cross-links.py --root spec/03-error-manage/03-error-code-registry/07-schemas` then either (a) fix each path or (b) add the link to `linter-scripts/spec-cross-links.allowlist` with a justification comment.

**Acceptance test:** Given `python3 linter-scripts/check-spec-cross-links.py --root spec/03-error-manage/03-error-code-registry/07-schemas`, When run, Then exit code MUST be 0.

**Effort estimate:** ~5 minutes

### 4. [P3] maintainability — `spec/03-error-manage/03-error-code-registry/07-schemas/98-changelog.md`

**Action:** After applying the above fixes, bump version (≥ minor) and add a row to `98-changelog.md` summarising what changed.

**Acceptance test:** Given `spec/03-error-manage/03-error-code-registry/07-schemas/98-changelog.md`, When read, Then top-most version row MUST be dated 2026-04-25 and reference the fixes above.

**Effort estimate:** ~5 minutes

## Source metrics (from deterministic audit)

```json
{
  "ac_chars": 3227,
  "ac_count": 6,
  "child_modules": 0,
  "code_blocks_by_lang": {},
  "code_blocks_total": 0,
  "consistency_report": true,
  "gwt_block_count": 5,
  "has_json_schema": false,
  "has_mermaid": false,
  "has_sql_ddl": false,
  "has_ts_enums": false,
  "has_yaml_openapi": false,
  "links_broken": 1,
  "links_total": 3,
  "md_files": 3,
  "mmd_files": 0,
  "overview_chars": 635,
  "todo_density": 0,
  "waffle_per_kchar": 0.21
}
```
