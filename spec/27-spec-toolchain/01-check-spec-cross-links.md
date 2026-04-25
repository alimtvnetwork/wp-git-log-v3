# 01 — check-spec-cross-links.py

**Version:** 1.0.0  
**Updated:** 2026-04-25  
**Source:** [`linter-scripts/check-spec-cross-links.py`](../../linter-scripts/check-spec-cross-links.py)  
**Category:** Validator (read-only)

---

## Purpose

Verify every internal markdown link inside `spec/` resolves to an existing file, and when an anchor is present, to an existing heading inside that target file. This is the primary defence against broken cross-references after a file rename or split.

## Usage

```bash
python3 linter-scripts/check-spec-cross-links.py
python3 linter-scripts/check-spec-cross-links.py --root spec
python3 linter-scripts/check-spec-cross-links.py --json
```

## CLI flags

| Flag | Default | Purpose |
|------|---------|---------|
| `--root <dir>` | `spec` | Tree to scan |
| `--json` | off | Machine-readable failure report on stdout |

## Inputs

- Every `*.md` file under `--root`.
- [`linter-scripts/spec-cross-links.allowlist`](../../linter-scripts/spec-cross-links.allowlist) — broken-link exceptions (see §61).

## Outputs

Human report on stderr; JSON on stdout when `--json`.

## Exit codes

| Code | Meaning |
|------|---------|
| 0 | All links resolve |
| 1 | One or more broken links / missing target sections |
| 2 | Invocation error (bad `--root`, etc.) |

## Acceptance criteria

### AC-01-01 — Broken file link is detected
- **Given** a markdown file in `spec/` containing a link of the form `[x]` followed by `(./missing.md)`,
- **When** the script runs,
- **Then** it MUST exit `1` and report the file path + line + target.

### AC-01-02 — Broken anchor is detected
- **Given** a link of the form `[x]` followed by `(./real.md#non-existent-heading)`,
- **When** the script runs and `real.md` exists but has no matching heading,
- **Then** it MUST exit `1` and categorise the failure as `missing-section`.

### AC-01-03 — Allowlist suppresses known exceptions
- **Given** an entry in `spec-cross-links.allowlist`,
- **When** the script encounters that exact link,
- **Then** the link MUST NOT be reported as broken.

### AC-01-04 — `--json` mode emits parseable JSON
- **Given** `--json`,
- **When** failures exist,
- **Then** stdout MUST be a single JSON document with one object per failure containing `file`, `line`, `target`, `category`.

## Cross-references

- §12 [`12-suggest-spec-cross-link-fixes.md`](./12-suggest-spec-cross-link-fixes.md) — companion fixer.
- §61 [`61-spec-cross-links-allowlist.md`](./61-spec-cross-links-allowlist.md) — allowlist format.
- §70 [`70-spec-health-yml.md`](./70-spec-health-yml.md) — CI wiring.
