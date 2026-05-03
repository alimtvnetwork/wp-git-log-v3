# Phase 153 Task S24-fu — spec/24 close 1 MED + 2 LOW audit-v7 findings

**Date:** 2026-04-30
**Module:** spec/24-app-design-system-and-ui
**Pre-score:** 95 EXCELLENT (d3=18, d5=15)
**Expected re-score:** ≥97 EXCELLENT

## Findings closed

| dim | sev | title | resolution |
|---|---|---|---|
| D5 | MED | External Dependency §07 Missing | AC-ADS-11 — inline §07 primitive registry minimum-set + Cross-Refs link (Lesson #36) |
| D3 | LOW | Sidebar State Concurrency | AC-ADS-12 — unified `isCollapsed = (viewport<md) || userToggle`; forbids racing state slots |
| D5 | LOW | Missing linter-scripts | AC-ADS-13 — every script ref MUST resolve to canonical §27 slot; exit codes documented there only (Lesson #36) |

## Banners

- §97 v3.0.0 → v3.1.0 (AC count 10 → 13)
- §00 v4.1.2 → v4.1.3, h10 stamp 27 → 153
- §98 v4.1.2 → v4.1.3 (+row)
- §99 v2.2.1 → v2.2.2

## Lessons applied

- **#36 link-don't-restate** — both AC-ADS-11 (§07 cross-ref) and AC-ADS-13 (linter-scripts → §27) anchor at canonical authority instead of duplicating contract surface.

No CI workflow / RUBRIC change. Pure self-lift.
