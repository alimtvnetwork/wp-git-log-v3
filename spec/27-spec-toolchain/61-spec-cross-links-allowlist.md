# 61 — spec-cross-links.allowlist

**Version:** 1.1.0  
**Updated:** 2026-04-28 (Phase P39: bind P37 surface-survey lesson — AC-61-04 codifies that this file's `<relpath>:<line>:<target>` format is the SOLE line-keyed allowlist tree-wide and any future line-keyed waiver MUST inherit the P35 fuzzy-match contract.)  
**Source:** [`linter-scripts/spec-cross-links.allowlist`](../../linter-scripts/spec-cross-links.allowlist)  
**Category:** Configuration (consumed by §01)

---

## Purpose

Suppression list for known broken-link exceptions consumed by §01 [`check-spec-cross-links.py`](./01-check-spec-cross-links.md). Each entry is one path+anchor that the validator should ignore.

## Format

- One entry per line.
- Lines beginning with `#` are comments.
- Blank lines are ignored.
- Entries match the literal `target` string of a markdown link of the form `[x]` followed by `(target)` — no globbing.

```
# Example
spec/_archive/old-doc.md
spec/21-git-logs/00-overview.md#renamed-section
```

## Policy

Adding a new entry MUST be accompanied by a §99 audit row in the relevant module explaining the temporary suppression and the planned fix.

## Acceptance criteria

### AC-61-01 — Comment lines are ignored
- **Given** a line starting with `#`,
- **When** §01 loads the allowlist,
- **Then** that line MUST NOT match any link.

### AC-61-02 — Entries are exact-match
- **Given** an entry `spec/x.md`,
- **When** a link points to `./spec/x.md`,
- **Then** it MUST NOT be considered allowlisted (relative-vs-absolute mismatch); both forms must be normalised before comparison by §01.

### AC-61-03 — File is UTF-8
- **Given** the allowlist,
- **When** read,
- **Then** decoding as UTF-8 MUST succeed.

## Cross-references

- §01 [`01-check-spec-cross-links.md`](./01-check-spec-cross-links.md) — consumer.
