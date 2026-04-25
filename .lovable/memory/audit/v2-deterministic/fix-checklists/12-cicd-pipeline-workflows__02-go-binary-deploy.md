# Fix Checklist — `spec/12-cicd-pipeline-workflows/02-go-binary-deploy`

**Generated:** 2026-04-25  
**Current score:** 72/100 (C)  
**Implementability:** 50/100  
**Estimated effort:** ~10 min  
**Impact-weighted backlog:** 6 points

## Actions

| # | Pri | Category | Target file | Effort | Action |
|---:|:--:|---|---|---:|---|
| 1 | **P1** | broken-link | `spec/12-cicd-pipeline-workflows/02-go-binary-deploy/` | 5m | Run `python3 linter-scripts/check-spec-cross-links.py --root spec/12-cicd-pipeline-workflows/02-go-binary-deploy` then either (a) fix each path or (b) add the link to `linter-scripts/spec-cross-links.allowlist` with a justification comment. |
| 2 | **P3** | maintainability | `spec/12-cicd-pipeline-workflows/02-go-binary-deploy/98-changelog.md` | 5m | After applying the above fixes, bump version (≥ minor) and add a row to `98-changelog.md` summarising what changed. |

## Detail + Acceptance test for each action

### 1. [P1] broken-link — `spec/12-cicd-pipeline-workflows/02-go-binary-deploy/`

**Action:** Run `python3 linter-scripts/check-spec-cross-links.py --root spec/12-cicd-pipeline-workflows/02-go-binary-deploy` then either (a) fix each path or (b) add the link to `linter-scripts/spec-cross-links.allowlist` with a justification comment.

**Acceptance test:** Given `python3 linter-scripts/check-spec-cross-links.py --root spec/12-cicd-pipeline-workflows/02-go-binary-deploy`, When run, Then exit code MUST be 0.

**Effort estimate:** ~5 minutes

### 2. [P3] maintainability — `spec/12-cicd-pipeline-workflows/02-go-binary-deploy/98-changelog.md`

**Action:** After applying the above fixes, bump version (≥ minor) and add a row to `98-changelog.md` summarising what changed.

**Acceptance test:** Given `spec/12-cicd-pipeline-workflows/02-go-binary-deploy/98-changelog.md`, When read, Then top-most version row MUST be dated 2026-04-25 and reference the fixes above.

**Effort estimate:** ~5 minutes

## Source metrics (from deterministic audit)

```json
{
  "ac_chars": 2717,
  "ac_count": 5,
  "child_modules": 0,
  "code_blocks_by_lang": {
    "bash": 10,
    "plain": 5,
    "yaml": 14
  },
  "code_blocks_total": 29,
  "consistency_report": true,
  "gwt_block_count": 5,
  "has_json_schema": false,
  "has_mermaid": false,
  "has_sql_ddl": false,
  "has_ts_enums": false,
  "has_yaml_openapi": true,
  "links_broken": 1,
  "links_total": 43,
  "md_files": 7,
  "mmd_files": 0,
  "overview_chars": 2109,
  "todo_density": 0,
  "waffle_per_kchar": 0.02
}
```
