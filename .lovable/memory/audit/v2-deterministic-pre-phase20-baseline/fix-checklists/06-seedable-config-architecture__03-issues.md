# Fix Checklist — `spec/06-seedable-config-architecture/03-issues`

**Generated:** 2026-04-25  
**Current score:** 54/100 (D)  
**Implementability:** 10/100  
**Estimated effort:** ~40 min  
**Impact-weighted backlog:** 16 points

## Actions

| # | Pri | Category | Target file | Effort | Action |
|---:|:--:|---|---|---:|---|
| 1 | **P0** | missing-contract | `spec/06-seedable-config-architecture/03-issues/00-overview.md` | 30m | Inline a ```toml``` fenced block containing the TOML configuration block. Do not link to a sibling file — paste the contract directly. |
| 2 | **P1** | broken-link | `spec/06-seedable-config-architecture/03-issues/` | 5m | Run `python3 linter-scripts/check-spec-cross-links.py --root spec/06-seedable-config-architecture/03-issues` then either (a) fix each path or (b) add the link to `linter-scripts/spec-cross-links.allowlist` with a justification comment. |
| 3 | **P3** | maintainability | `spec/06-seedable-config-architecture/03-issues/98-changelog.md` | 5m | After applying the above fixes, bump version (≥ minor) and add a row to `98-changelog.md` summarising what changed. |

## Detail + Acceptance test for each action

### 1. [P0] missing-contract — `spec/06-seedable-config-architecture/03-issues/00-overview.md`

**Action:** Inline a ```toml``` fenced block containing the TOML configuration block. Do not link to a sibling file — paste the contract directly.

**Acceptance test:** Given `spec/06-seedable-config-architecture/03-issues/00-overview.md`, When grepped, Then it MUST contain at least one ```toml fenced code block ≥10 non-blank lines.

**Effort estimate:** ~30 minutes

### 2. [P1] broken-link — `spec/06-seedable-config-architecture/03-issues/`

**Action:** Run `python3 linter-scripts/check-spec-cross-links.py --root spec/06-seedable-config-architecture/03-issues` then either (a) fix each path or (b) add the link to `linter-scripts/spec-cross-links.allowlist` with a justification comment.

**Acceptance test:** Given `python3 linter-scripts/check-spec-cross-links.py --root spec/06-seedable-config-architecture/03-issues`, When run, Then exit code MUST be 0.

**Effort estimate:** ~5 minutes

### 3. [P3] maintainability — `spec/06-seedable-config-architecture/03-issues/98-changelog.md`

**Action:** After applying the above fixes, bump version (≥ minor) and add a row to `98-changelog.md` summarising what changed.

**Acceptance test:** Given `spec/06-seedable-config-architecture/03-issues/98-changelog.md`, When read, Then top-most version row MUST be dated 2026-04-25 and reference the fixes above.

**Effort estimate:** ~5 minutes

## Source metrics (from deterministic audit)

```json
{
  "ac_chars": 2652,
  "ac_count": 5,
  "child_modules": 0,
  "code_blocks_by_lang": {
    "bash": 1
  },
  "code_blocks_total": 1,
  "consistency_report": true,
  "gwt_block_count": 5,
  "has_json_schema": false,
  "has_mermaid": false,
  "has_sql_ddl": false,
  "has_ts_enums": false,
  "has_yaml_openapi": false,
  "links_broken": 1,
  "links_total": 6,
  "md_files": 4,
  "mmd_files": 0,
  "overview_chars": 237,
  "todo_density": 0,
  "waffle_per_kchar": 0.0
}
```
