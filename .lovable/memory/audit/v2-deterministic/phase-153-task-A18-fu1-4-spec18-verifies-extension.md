# Phase 153 · Task A18-fu1 #4 — spec/18 AC-13 Verifies-clause artifact-citation extension

**Date:** 2026-04-30
**Trigger:** `next` after F-03 cache-refresh attempt returned HTTP 402 tree-wide (Lesson #20 — defer scoring, don't block).
**Module:** `spec/18-wp-plugin-how-to/` (cached score 86 GOOD; axis: process-guidance).
**Closes:** audit-v7 [D2 HIGH] `Missing Verifies clauses for Phase 14-21`.

## What changed

`spec/18/97-acceptance-criteria.md` — AC-13's `**Verifies:**` line extended with 6 explicit linter/test artifact citations:
- (a) REST `permission_callback` + namespace → `linter-scripts/check-forbidden-strings.py`
- (b) Settings `register_setting` + sanitize_callback / raw `update_option` → `check-forbidden-strings.py`
- (c) Response envelope + typed exceptions → `check-forbidden-strings.py`
- (d) Repository facade + raw `$wpdb->query` → `check-forbidden-strings.py`
- (e) ping endpoint exact-shape → `test-readme-inventory.sh` schema-snapshot extension hook
- (f) walkthrough end-to-end parity → `check-tree-health.cjs --strict` + `check-lockstep.cjs`

Authoring rule appended (Lesson #28): AC-10/12/13 row changes MUST add matching forbidden-string patterns to `linter-scripts/forbidden-strings.toml` in the same phase to keep verification mechanical.

## Lockstep

| File | Pre | Post | Reason |
|------|-----|------|--------|
| §97 | 1.4.0 | 1.4.1 | Verifies-clause extension on existing AC (no new AC, no AC-31-31 cascade) |
| §00 | 1.4.1 | 1.4.2 | Banner lockstep |
| §98 | 1.4.1 | 1.4.2 | New row |
| §99 | 1.4.3 | 1.4.4 | Status refresh |

Patch-only across all four files (Lesson #24 — banner-style edit on §97 for an existing AC).

## Gates

- `check-lockstep.cjs`: 87/87 PASS · 0 findings
- `check-tree-health.cjs --strict`: 168/168, score 100/100
- `check-version-parity.py`: 74/74 matches, 0 mismatches
- `check-99-summary-freshness.py`: 81 stamped + 6 exempt + 0 unstamped

## Score outcome

**Re-score deferred per Lesson #20** — `audit-ai-implementability.py --force` returned HTTP 402 Payment Required tree-wide at session open. Expected lift on next gateway-available re-score: D2 16 → 18 (+2), total 86 → ~88 (still GOOD).

## Lessons reinforced

- **Lesson #19** (audit-boundary < verification-boundary): Verifies-clause is the lift surface when contract exists in §97 prose but artifact bindings are absent.
- **Lesson #28** (mechanical-lock): every contract row SHOULD point at a verifier; documented as authoring rule inside the AC itself.
- **Lesson #20** (gateway 402 → defer score): re-score deferred but contract change shipped; cache will refresh on next gateway window.

## Remaining backlog

- F-03: tree-wide cache refresh — **gateway-blocked** (402 confirmed at session open).
- A18-fu1 #5–#12: 7 remaining HIGH findings (other modules' D2/D5).
- A24-fu43-fu1 / A24-fu44-fu1: deferred GWT stub work (spec/12 + spec/03).
- A19: promote audit-ai gate to strict (gateway-dependent).
- R1: trace-map deeper bindings (Lovable Cloud blocked).
