# Phase 153 P-L36-fu — spec/17 Source-header tree-wide sweep

**Date:** 2026-04-29
**Status:** CLOSED
**Driver:** Lesson #36 nuance (roll-up surfaces) codified in P-L36 last cycle

## Survey

35 files in spec/17 surveyed for `**Source:**` or `**Source Module:**` headers:

| State | Count | Files |
|---|---|---|
| Pre-existing Source header | 14 | 02, 03, 05, 06, 15, 17, 18, 20, 21, 22, 23, 33, 34 + 04 §00 |
| Standalone (exempt) | 6 | 25, 26, 29, 30, 31, 32 (audits/rollups — they ARE the source) |
| **TRUE GAP — patched this phase** | **15** | 01, 04, 07, 08, 09, 10, 11, 12, 13, 14, 16, 19, 24, 27, 28 |

## Patches

Two batch scripts:

1. `/tmp/add_source.py` — inserts `**Source:** [`../<folder>/`](../<folder>/)` line under the title, above `**Version:**`. Special cases: 19-gap-analysis + 24-folder-mapping use `(self — ...)` syntax (no upstream source).
2. `/tmp/bump_patched.py` — patch-bumps `**Version:**` line by 0.0.1, refreshes `**Updated:** 2026-04-29`. Manual sed for 2 blockquote-version files (slots 27, 28).

Per-file bumps:
- 01 v3.3.0→3.3.1; 04 v3.2.0→3.2.1; 07 v3.2.0→3.2.1; 08 v3.2.0→3.2.1; 09 v3.2.0→3.2.1
- 10 v3.2.0→3.2.1; 11 v3.3.0→3.3.1; 12 v3.3.0→3.3.1; 13 v3.3.0→3.3.1; 14 v3.3.1→3.3.2
- 16 v3.3.0→3.3.1; 19 v13.0.0→13.0.1; 24 v1.0.0→1.0.1; 27 v1.0.0→1.0.1; 28 v1.1.0→1.1.1

Source folder mapping (the "TRUE GAP" 15):

| Slot | Source folder |
|---|---|
| 01 | `../01-spec-authoring/` |
| 04 | `../04-database-conventions/` (enums live here) |
| 07 | `../07-design-system/` |
| 08 | `../08-docs-viewer-ui/` |
| 09 | `../09-code-block-system/` |
| 10 | `../11-powershell-integration/` (folder 11) |
| 11 | `../02-coding-guidelines/research/` |
| 12 | `../research/` (root-level) |
| 13 | `../21-app/` |
| 14 | `../21-app/issues/` |
| 16 | `../21-app/design-system/` |
| 19 | `(self — periodic gap analysis report)` |
| 24 | `(self — mapping document)` |
| 27 | `../27-linter-authoring-guide/` |
| 28 | `../28-distribution-and-runner/` |

Module-level lockstep: spec/17 §00 v3.4.4 → v3.4.5; §98 row 3.4.5 added; §99 v4.6.4 → v4.6.5.

## Lesson #36 nuance reconfirmed

The `**Source:**` link is **the** drift-detection mechanism for roll-up surfaces. Without it, a future contributor editing a source spec has no grep path to find the spec/17 mirror — the silent-divergence class returns. The roll-up CONTENT is allowed to restate (that's its purpose); only the LINK is mandatory.

## Forward-looking codification

Any **new file** added to spec/17 MUST include a `**Source:**` line in the banner block. Standalone audits/rollups MAY use `**Source:** (self — <reason>)` to opt out explicitly. This rule is now the spec/17 authoring convention; codify in process memo (`mem://process/phase-153-lessons` Section F or new Section G) on next opportunity.

## Verification

- Lockstep 87/87 GREEN
- Tree-health 168/168 strict GREEN
- Version-parity 74/74 GREEN
- All 17 file banner bumps + 1 module banner bump landed cleanly

## Status post-close

This was the natural follow-up to P-L36's roll-up Lesson #36 codification. After this phase, **all 35 spec/17 files conform to the Source-header convention** (29 with explicit Source link, 6 standalone-exempt). Future spec/17 additions are governed by the new convention.
