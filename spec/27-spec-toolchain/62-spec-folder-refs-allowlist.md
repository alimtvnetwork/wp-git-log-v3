# 62 — spec-folder-refs.allowlist

**Version:** 1.0.0  
**Updated:** 2026-04-25  
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

## Cross-references

- §02 [`02-check-spec-folder-refs.md`](./02-check-spec-folder-refs.md) — consumer.
- §01 [`01-check-spec-cross-links.md`](./01-check-spec-cross-links.md) — separate concern (link resolution).
