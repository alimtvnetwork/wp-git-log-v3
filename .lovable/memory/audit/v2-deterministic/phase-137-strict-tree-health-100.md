# Phase 137 — Tree-health strict 100/100 recovered (q=2/3 → 3/3)

**Date:** 2026-04-28
**Trigger:** `next` after Phase 136. Autonomous queue empty; ran `check-tree-health.cjs --strict` (not previously run during the `next` loops) and immediately surfaced one imperfect module.

## Discovery

Default-mode tree health prints `§99 quality credits: 167 / 168` and rounds to 100. Strict mode revealed the single missing credit:

```
✗ FAIL: --strict mode — 1 module(s) below full marks:
    22-git-logs-v2  →  quality 2/3
```

## Root cause

`scoreQuality()` in `linter-scripts/check-tree-health.cjs` awards 3 credits: `depth`, `history`, `inventory`. The inventory credit requires a heading matching:

```
^##+\s+(File Inventory|Module Inventory|Top-Level Modules|Document Inventory|Modules)
```

`spec/22-git-logs-v2/99-consistency-report.md:8` used `## Inventory` (no qualifier), so the regex missed it despite the section being the canonical and authoritative inventory block (Phase 135 just removed its stale duplicate).

## Fix

One-token rename: `## Inventory` → `## File Inventory` (line 8). Lockstep:

- §99 banner v3.9.4 → **v3.9.5**, date 2026-04-27 → 2026-04-28
- §98 new row: 3.8.10 / 2026-04-28 / Phase 137 entry

No contract change, no DDL change, no AC churn — pure linter-conformance hygiene.

## Verification

- `check-tree-health.cjs --strict`: **168/168 quality, 100/100 strict-pass, all 56 modules at full marks** (first time)
- `check-lockstep.cjs`: **87/87 pass · 0 findings**

## Significance

Tree is now provably perfect under the strictest available gate. The default `--min=75` threshold has held for many phases, but `--strict` (100 threshold + per-module full-marks check) was a latent regression detector that had never been run green. Recommend wiring `--strict` into the `spec-health.yml` workflow as a non-blocking advisory line so future credit-loss surfaces immediately without blocking PRs.

## Files touched

- `spec/22-git-logs-v2/99-consistency-report.md` — heading + banner
- `spec/22-git-logs-v2/98-changelog.md` — Phase 137 row
- `.lovable/memory/audit/v2-deterministic/phase-137-strict-tree-health-100.md` — this memo
