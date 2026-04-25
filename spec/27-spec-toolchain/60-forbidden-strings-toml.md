# 60 — forbidden-strings.toml

**Version:** 1.0.0  
**Updated:** 2026-04-25  
**Source:** [`linter-scripts/forbidden-strings.toml`](../../linter-scripts/forbidden-strings.toml)  
**Category:** Configuration (consumed by §03)

---

## Purpose

TOML config defining forbidden patterns and per-pattern allowlists for §03 [`check-forbidden-strings.py`](./03-check-forbidden-strings.md).

## Schema

```toml
[scan]
include = ["spec/**/*.md", "readme.md"]   # glob list
exclude = ["spec/_archive/**"]            # glob list

[[patterns]]
id          = "no-jwt-in-git-logs-v2"
description = "JWT/RS256/JWKS were dropped from Git Logs v2"
regex       = "\\b(JWT|RS256|JWKS)\\b"
allowlist   = [
  "spec/21-git-logs/**",                  # v1 legacy is allowed
  "spec/_archive/**",
]
```

## Acceptance criteria

### AC-60-01 — File parses as valid TOML
- **Given** the file,
- **When** loaded with `tomllib`,
- **Then** parsing MUST succeed.

### AC-60-02 — Each `[[patterns]]` has required keys
- **Given** any `[[patterns]]` entry,
- **When** read,
- **Then** it MUST have `id`, `description`, `regex` (allowlist optional, defaults to `[]`).

### AC-60-03 — `id` is unique
- **Given** the full config,
- **When** all pattern `id` values are collected,
- **Then** there MUST be no duplicates.

### AC-60-04 — `regex` compiles
- **Given** any pattern,
- **When** the regex is compiled with `re.compile`,
- **Then** compilation MUST succeed.

## Cross-references

- §03 [`03-check-forbidden-strings.md`](./03-check-forbidden-strings.md) — consumer.
