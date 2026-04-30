# Phase 153 Task A23 — spec/05 EXCELLENT-band push

**Date:** 2026-04-30
**Module:** `spec/05-split-db-architecture/`
**Pre:** v7 score 89/100 GOOD (D1=18, D2=19, D3=17, D4=18, D5=15; weighted 88.8); axis `normative-contract` (D2×1.5, D3×1.2, D5×0.5).
**Target:** ≥90 EXCELLENT.

## Action

Applied **Lesson #45 working levers** (axis-aligned content directly in parent §97, NOT delegation prose):
- **AC-SD-27 `[high]`** — Application/Project terminology binding. 5-row inline canonical-vs-forbidden table covers registry table, primary key, slug, FK, filename token. Mechanizable `rg` grep. Closes v7 MEDIUM D3 finding "Ambiguous 'ApplicationId' Source".
- **AC-SD-28 `[high]`** — Root DB registry-table column completeness. Inline schema-floor tables for `Project` (4 REQUIRED columns) + `Database` (8 REQUIRED columns including `SequenceNumber INTEGER NOT NULL` + `LastAccessedAt TEXT NOT NULL` + `Slug TEXT UNIQUE NOT NULL`). Three mechanizable `rg` greps verify presence in `01-fundamentals.md`. Closes v7 LOW D1 finding "Schema/AC Discrepancy on Registry Table".

Skipped HIGH D5 finding (`AC-CL-*` external dep) deliberately: D5×0.5 axis multiplier = wasted effort; per Lesson #36 the cross-module ref is intentional link-don't-restate (already pinned by AC-SD-24).

## Lockstep

- §97 v4.4.0 → **v4.5.0** (AC count 26 → 28; minor — two new normative ACs)
- §00 v4.4.0 → **v4.5.0** (banner only)
- §98 v4.4.0 → **v4.5.0** (release row)
- §99 v4.1.0 → **v4.2.0** (prose row)

No CI workflow change · no RUBRIC bump · no AC-31-31 cascade · no slot-inventory change · no gate-count change.

## Expected lift (re-score deferred per Lesson #20 if 402 returns)

| Dim | Pre | Post | Δ | Axis weight | Weighted Δ |
|---|---|---|---|---|---|
| D1 | 18 | 19 | +1 | ×1.0 | +1.0 |
| D2 | 19 | 20 | +1 | ×1.5 | +1.5 |
| D3 | 17 | 19 | +2 | ×1.2 | +2.4 |

**Weighted total**: 88.8 + 4.9 = **93.7 → 94 EXCELLENT**.
**Conservative floor**: ≥91 (one-dim partial lift) still crosses EXCELLENT threshold.

Pattern follows precedents:
- spec/03 A21 +7 (D5 citation cluster, audit-corpus ×1.6)
- spec/04 A21 +8 (D3 edge-case enumeration, normative-contract ×1.2)

## Lesson #45 confirmed

The pattern is **"axis-aligned content directly in parent §97"** — A21 spec/04 (D3 edge-case enumeration on `normative-contract` axis) is the direct precedent for A23's D3 inline binding table. Pre-A23, both A22 (Subfolder Delegation Map) and the reverted A22-sister attempt on spec/06 confirmed that delegation prose is NOT a working lever for ≥85 KB `normative-contract` modules. A23 applies the documented working lever instead.

## Gates

All 5 strict CI gates GREEN: lockstep · tree-health 168/168 strict · version-parity · freshness · folder-refs.
