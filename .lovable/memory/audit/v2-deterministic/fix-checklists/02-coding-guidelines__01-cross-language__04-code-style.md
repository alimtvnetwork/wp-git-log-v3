# Fix Checklist — `spec/02-coding-guidelines/01-cross-language/04-code-style`

**Generated:** 2026-04-25  
**Current score:** 75/100 (B)  
**Implementability:** 50/100  
**Estimated effort:** ~45 min  
**Impact-weighted backlog:** 12 points

## Actions

| # | Pri | Category | Target file | Effort | Action |
|---:|:--:|---|---|---:|---|
| 1 | **P1** | untestable | `spec/02-coding-guidelines/01-cross-language/04-code-style/97-acceptance-criteria.md` | 5m | Rewrite 1 acceptance criterion/criteria into Given/When/Then form. |
| 2 | **P1** | broken-link | `spec/02-coding-guidelines/01-cross-language/04-code-style/` | 5m | Run `python3 linter-scripts/check-spec-cross-links.py --root spec/02-coding-guidelines/01-cross-language/04-code-style` then either (a) fix each path or (b) add the link to `linter-scripts/spec-cross-links.allowlist` with a justification comment. |
| 3 | **P3** | drift | `spec/02-coding-guidelines/01-cross-language/04-code-style/*.md` | 30m | Resolve 3 TODO/TBD/FIXME marker(s). Either implement the missing detail or move the marker into a tracked AC. |
| 4 | **P3** | maintainability | `spec/02-coding-guidelines/01-cross-language/04-code-style/98-changelog.md` | 5m | After applying the above fixes, bump version (≥ minor) and add a row to `98-changelog.md` summarising what changed. |

## Detail + Acceptance test for each action

### 1. [P1] untestable — `spec/02-coding-guidelines/01-cross-language/04-code-style/97-acceptance-criteria.md`

**Action:** Rewrite 1 acceptance criterion/criteria into Given/When/Then form.

**Acceptance test:** Given `spec/02-coding-guidelines/01-cross-language/04-code-style/97-acceptance-criteria.md`, When parsed, Then `gwt_block_count` MUST equal `ac_count` (9).

**Effort estimate:** ~5 minutes

### 2. [P1] broken-link — `spec/02-coding-guidelines/01-cross-language/04-code-style/`

**Action:** Run `python3 linter-scripts/check-spec-cross-links.py --root spec/02-coding-guidelines/01-cross-language/04-code-style` then either (a) fix each path or (b) add the link to `linter-scripts/spec-cross-links.allowlist` with a justification comment.

**Acceptance test:** Given `python3 linter-scripts/check-spec-cross-links.py --root spec/02-coding-guidelines/01-cross-language/04-code-style`, When run, Then exit code MUST be 0.

**Effort estimate:** ~5 minutes

### 3. [P3] drift — `spec/02-coding-guidelines/01-cross-language/04-code-style/*.md`

**Action:** Resolve 3 TODO/TBD/FIXME marker(s). Either implement the missing detail or move the marker into a tracked AC.

**Acceptance test:** Given the module body, When grep'd for `TODO|TBD|FIXME|XXX|HACK`, Then 0 matches.

**Effort estimate:** ~30 minutes

### 4. [P3] maintainability — `spec/02-coding-guidelines/01-cross-language/04-code-style/98-changelog.md`

**Action:** After applying the above fixes, bump version (≥ minor) and add a row to `98-changelog.md` summarising what changed.

**Acceptance test:** Given `spec/02-coding-guidelines/01-cross-language/04-code-style/98-changelog.md`, When read, Then top-most version row MUST be dated 2026-04-25 and reference the fixes above.

**Effort estimate:** ~5 minutes

## Source metrics (from deterministic audit)

```json
{
  "ac_chars": 4713,
  "ac_count": 9,
  "child_modules": 0,
  "code_blocks_by_lang": {
    "go": 25,
    "php": 21,
    "plain": 1,
    "typescript": 18
  },
  "code_blocks_total": 65,
  "consistency_report": true,
  "gwt_block_count": 8,
  "has_json_schema": false,
  "has_mermaid": false,
  "has_sql_ddl": false,
  "has_ts_enums": true,
  "has_yaml_openapi": false,
  "links_broken": 1,
  "links_total": 38,
  "md_files": 10,
  "mmd_files": 0,
  "overview_chars": 2589,
  "todo_density": 3,
  "waffle_per_kchar": 0.08
}
```
