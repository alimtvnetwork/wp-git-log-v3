# 62 — spec-folder-refs.allowlist

**Version:** 1.2.0  
**Updated:** 2026-04-28 (Phase F1: classified 3 stale missing-folder targets — `08-docs-viewer-ui`, `09-code-block-system`, `21-app` — all as `[doc-only]`. 11 broken references resolved across 6 files. `check-spec-folder-refs.py` now reports 0 stale refs. `[doc-only]` count 21 → 24.)  
**Source:** [`linter-scripts/spec-folder-refs.allowlist`](../../linter-scripts/spec-folder-refs.allowlist)  
**Category:** Configuration (consumed by §02)

---

## Purpose

Two-section allowlist consumed by §02 [`check-spec-folder-refs.py`](./02-check-spec-folder-refs.md).

## Format

```ini
[external]
# Real folders that live in a sibling repository.
# References are valid; we just don't host them here.
spec/30-cross-repo-foo/
spec/31-cross-repo-bar/

[doc-only]
# Illustrative / historical names. They do NOT exist anywhere.
# They are prose only and MUST NEVER become live links.
spec/14-generic-update/
spec/15-self-update-app-update/
```

## Section semantics

| Section | Allowed in prose? | Allowed as a markdown link? |
|---------|:-----------------:|:---------------------------:|
| `[external]` | yes | yes (linked to sibling repo URL) |
| `[doc-only]` | yes | NO — doc-only entries MUST NEVER be linkified |

## Acceptance criteria

### AC-62-01 — Both section headers are required
- **Given** the file is missing `[external]` OR `[doc-only]`,
- **When** §02 loads it,
- **Then** §02 MUST exit `2` (structural error).

### AC-62-02 — Doc-only entries are not linkified
- **Given** an entry under `[doc-only]`,
- **When** §01 (link validator) sees a markdown link of the form `[x]` followed by `(spec/<entry>)`,
- **Then** §01 MUST report it as broken (this allowlist does NOT cover §01).

### AC-62-03 — External entries pass §02
- **Given** an entry under `[external]`,
- **When** the same path appears in prose,
- **Then** §02 MUST NOT report it stale.

### AC-62-04 — Inline comments on entry lines are forbidden (Phase 143)
- **Given** a non-blank, non-section-header line in this allowlist,
- **When** the line contains a `#` after the folder name (i.e. an inline trailing comment like `04-some-feature  # narrative example`),
- **Then** the entry IS BROKEN — `check-spec-folder-refs.py:128` (`buckets[current].add(line.strip())`) stores the entire string including the `#` and trailing comment, so the resulting bucket entry never matches the bare folder name. Documentation MUST go in a **separate `#`-prefixed comment line ABOVE** the entry. Phase 143 discovered this empirically: the first allowlist write attempt used inline trailers and the stale-refs count did not drop until the comments were moved above.
- **Verifies:** `linter-scripts/spec-folder-refs.allowlist` (Phase 143 v1.1.0 entries follow this rule); `linter-scripts/check-spec-folder-refs.py:128` (parser line that mandates the rule).

## Cross-references

- §02 [`02-check-spec-folder-refs.md`](./02-check-spec-folder-refs.md) — consumer.
- §01 [`01-check-spec-cross-links.md`](./01-check-spec-cross-links.md) — separate concern (link resolution).

## Changelog

### 1.1.0 — 2026-04-28 — Phase 143: bulk-classify 13 documentation/historical refs
- Added 13 entries under `[doc-only]` covering: 4 narrative-example folder names (`04-some-feature`, `30-cross-repo-foo`, `31-cross-repo-bar`, `99-nonexistent` — all used as deliberate fake paths inside §27 spec GWT bodies and audit-memo prose), 4 archived/historical folder names (`21-git-logs`, `21-git-logs-v1`, `22-app-issues`, `29-app-issues-cli` — historical references in `_archive/`, audit memos, and full-tree audit-v4 memo), and 3 deprecated/typo-example folder names used as deliberate negative examples in §27 linter specs (`12-cicd-pipelines` typo, `14-generic-update` and `15-self-update-app-update` deprecated pre-consolidation).
- Result: stale-refs count dropped from 29 → **11** (-62%). Remaining 11 refs across 6 unique missing targets (`08-docs-viewer-ui`, `09-code-block-system`, `21-app`, plus a few in `01-spec-authoring-guide`, `07-design-system`, `17-consolidated-guidelines`) require user intent classification — defer under Phase F.
- **Format note (caught during Phase 143 implementation):** the allowlist parser at `check-spec-folder-refs.py:128` does `buckets[current].add(line)` on the stripped line — inline `# comment` suffixes on entry lines are NOT stripped and corrupt the bucket. Phase 143 documents intent in **separate full-line comments above the entries**, not as inline trailers. AC-62-04 added to enforce this.

### 1.0.0 — 2026-04-25
- Initial version. Two-section format `[external]` + `[doc-only]`.
