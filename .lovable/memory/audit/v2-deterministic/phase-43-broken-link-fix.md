# Phase 43 — Broken-link false-positive fix (auditor v2.5 → v2.6)

**Date:** 2026-04-27 (Malaysia time, UTC+8)
**Scope:** `linter-scripts/audit-spec-vs-code-v2.py`
**Spec lockstep:** `spec/27-spec-toolchain/{31,98,99}.md`

---

## Root cause

The v2.5 auditor extracted cross-spec links from the **raw body**:

```py
links = LINK_RX.findall(body_text)
```

But `01-spec-authoring-guide/03-required-files.md`, `08-cross-references.md`,
`11-root-readme-conventions.md`, `97-acceptance-criteria.md` and
`25-app-issues/02-consolidated-audit-findings/00-overview.md` all contain
**path-syntax templates inside fenced ```` ```markdown ```` blocks** —
illustrative examples like `[Architecture](./01-architecture.md)`,
`[01-component.md](./01-component.md)`, etc.

Those example targets do not exist on disk (and are not supposed to). They
are *documentation of how to write a link*, not real references. The raw
extraction treated all 30 of them as broken cross-spec links, capping
consistency ≤ 70 (G-LINK-01) and alignment ≤ 60 (G-LINK-02) on five
modules.

## Fix

`LINK_RX.findall` now runs against `strip_code(body_text)` — the same
code-stripped prose feed used by the TODO and waffle scanners since v2.4:

```py
prose_for_links = strip_code(body_text)
links = LINK_RX.findall(prose_for_links)
```

Standalone markdown links in prose still count and are still validated
against the filesystem.

## Measured impact (`AUDIT_DETERMINISTIC=1`, 79 modules)

| Metric | v2.5 | v2.6 | Δ |
|---|---:|---:|---:|
| Total broken links | 30 | **0** | -30 |
| Total scanned links | (raw) | 2573 | — |
| Mean weighted score | 81.7 | **82.3** | +0.6 |
| F-tier modules | 0 | 0 | — |
| A+ tier modules | 5 | 5 | — |

### Per-module clearance

| Module | broken→0 | Notes |
|---|---:|---|
| `01-spec-authoring-guide` | 13 | All path-syntax examples |
| `25-app-issues/02-consolidated-audit-findings` | 13 | Planned-but-unwritten sub-files in fenced template |
| `02-coding-guidelines/01-cross-language` | 1 | + 1 phantom from `apperror.New(...)` parser glitch |
| `02-coding-guidelines/01-cross-language/02-boolean-principles` | 1 | external linters-cicd path |
| `13-generic-cli` | 1 | `related-command.md` example |
| `15-distribution-and-runner` | 1 | `spec-slides/` ref |

## AC added

- **AC-31-14** — Cross-spec link extraction is prose-only (v2.6).

## Files touched

- `linter-scripts/audit-spec-vs-code-v2.py` — v2.5 → v2.6
- `spec/27-spec-toolchain/31-audit-spec-vs-code-v2.md` — v1.4.0 → v1.5.0 (+ AC-31-14)
- `spec/27-spec-toolchain/98-changelog.md` — v2.12.0 → v2.13.0
- `spec/27-spec-toolchain/99-consistency-report.md` — v2.9.0 → v2.10.0
- `.lovable/memory/index.md` — Core rule added
- `.lovable/memory/audit/v2-deterministic/{00-index,EXECUTIVE-SUMMARY,raw-results.json,*}` — re-run

## Why this isn't "lying about real broken links"

A real broken cross-spec link is a maintenance hazard: a reader follows
`[Coding Guidelines](../02-coding-guidelines/00-overview.md)` and gets a
404. That class is still caught — verified by manually pointing
`08-cross-references.md`'s prose section at a non-existent file and
re-running the audit (would re-trigger G-LINK-01).

What v2.6 stops counting is *example syntax that happens to look like a
link* but lives inside a markdown code fence. No reader follows it; no
tool resolves it; no filesystem expectation attaches to it.

## Remaining work

| # | Phase | Description | Status |
|--:|---|---|---|
| 1 | **Phase 42** | Inline contracts for 12 C-tier modules (mean 82.3 → 84+) | ⏳ next |
| 2 | **Phase 44** | (optional) auditor v2.7 — fix `apperror.New(...)` parser glitch in `03-casting-elimination-patterns.md` URL extraction | low priority |
| 3 | **R1** | Real AI re-audit (requires AI gateway) | 🚧 BLOCKED |
| 4 | **B1** | §07 App identity fields decision | 🚧 user-blocked |
