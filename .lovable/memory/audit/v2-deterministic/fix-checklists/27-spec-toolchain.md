# Fix Checklist — `spec/27-spec-toolchain`

**Generated:** 2026-04-25  
**Current score:** 74/100 (C)  
**Implementability:** 40/100  
**Estimated effort:** ~65 min  
**Impact-weighted backlog:** 12 points

## Actions

| # | Pri | Category | Target file | Effort | Action |
|---:|:--:|---|---|---:|---|
| 1 | **P0** | missing-contract | `spec/27-spec-toolchain/00-overview.md` | 30m | Inline a ```text``` fenced block containing the normative contract block (DDL / schema / enum / OpenAPI). Do not link to a sibling file — paste the contract directly. |
| 2 | **P3** | drift | `spec/27-spec-toolchain/*.md` | 30m | Resolve 3 TODO/TBD/FIXME marker(s). Either implement the missing detail or move the marker into a tracked AC. |
| 3 | **P3** | maintainability | `spec/27-spec-toolchain/98-changelog.md` | 5m | After applying the above fixes, bump version (≥ minor) and add a row to `98-changelog.md` summarising what changed. |

## Detail + Acceptance test for each action

### 1. [P0] missing-contract — `spec/27-spec-toolchain/00-overview.md`

**Action:** Inline a ```text``` fenced block containing the normative contract block (DDL / schema / enum / OpenAPI). Do not link to a sibling file — paste the contract directly.

**Acceptance test:** Given `spec/27-spec-toolchain/00-overview.md`, When grepped, Then it MUST contain at least one ```text fenced code block ≥10 non-blank lines.

**Effort estimate:** ~30 minutes

### 2. [P3] drift — `spec/27-spec-toolchain/*.md`

**Action:** Resolve 3 TODO/TBD/FIXME marker(s). Either implement the missing detail or move the marker into a tracked AC.

**Acceptance test:** Given the module body, When grep'd for `TODO|TBD|FIXME|XXX|HACK`, Then 0 matches.

**Effort estimate:** ~30 minutes

### 3. [P3] maintainability — `spec/27-spec-toolchain/98-changelog.md`

**Action:** After applying the above fixes, bump version (≥ minor) and add a row to `98-changelog.md` summarising what changed.

**Acceptance test:** Given `spec/27-spec-toolchain/98-changelog.md`, When read, Then top-most version row MUST be dated 2026-04-25 and reference the fixes above.

**Effort estimate:** ~5 minutes

## Source metrics (from deterministic audit)

```json
{
  "ac_chars": 3934,
  "ac_count": 10,
  "child_modules": 0,
  "code_blocks_by_lang": {
    "bash": 23,
    "ini": 1,
    "markdown": 1,
    "plain": 1,
    "powershell": 1,
    "toml": 1
  },
  "code_blocks_total": 28,
  "consistency_report": true,
  "gwt_block_count": 10,
  "has_json_schema": false,
  "has_mermaid": false,
  "has_sql_ddl": false,
  "has_ts_enums": false,
  "has_yaml_openapi": false,
  "links_broken": 0,
  "links_total": 107,
  "md_files": 32,
  "mmd_files": 0,
  "overview_chars": 8021,
  "todo_density": 3,
  "waffle_per_kchar": 0.16
}
```
