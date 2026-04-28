# Phase 21 — §00-overview stale-prose sweep (banner + slot-70 description)

**Date**: 2026-04-28
**Mode**: No-Questions Mode (task 5/40)

## Context

Phase 20 close-out recommendation (b): audit other top-level READMEs
for the narrative-count drift class. `linter-scripts/README.md` does
not exist; survey of `spec/27-spec-toolchain/00-overview.md` surfaced
two real findings.

## Drift fixed

| Loc | Before | After |
|---|---|---|
| L9-10 | `Version: 1.7.0 / Updated: 2026-04-27` (~39 patch releases behind §98) | `Version: 2.46.3 / Updated: 2026-04-28` (synced to §98 after this phase's bump) |
| L116 (slot-70 row) | `Wires §05 + §10 into GitHub Actions (event-driven)` — frozen at the original 2-script wiring era | `Per-PR/push spec-health pipeline: 17 production gates + 7 discrete self-test steps (cross-link, folder-refs, §99 freshness/stamp-bump, tree-health strict, lockstep, audit-v2 deterministic, mermaid syntax, archive-exclusion runtime, memo retro headings, etc.)` |

## Why lockstep didn't catch the banner drift

`check-lockstep.cjs` enforces three rules between §00, §98, §99:
- L1: `§98 latest release date >= §00 Updated date` — was satisfied
  (`2026-04-28 >= 2026-04-27`)
- L2: `§98 banner Updated >= max(§00, all release-line dates)` — satisfied
- L3: same date-relation forms

**No rule checks Version-string parity.** A §00 banner can stay at v1.7.0
indefinitely while §98 advances to v2.46.x as long as Updated dates stay
ordered correctly. This is the same Phase 20 stale-prose class — gates
verify structural relations, not narrative version claims.

## Lockstep edits

- §00 banner: v1.7.0 → v2.46.3 + Updated 2026-04-27 → 2026-04-28
- §00 slot-70 description rewritten
- §98 banner: v2.46.2 → v2.46.3 + new release-line `2.46.3 — 2026-04-28 — Phase 21`
- §99 banner: v2.43.2 → v2.43.3 + new Validation History blockquote

## Why no AC-31-31 cascade / new gate

Pure narrative lockstep — no new gate added, no scoring weight changed,
no AC added. Inventory table (L42–L117) was structurally correct and
gated; only narrative descriptions and version-string claims drifted.
Same lockstep class as Phase 20 (README) and Phase 139 (§99 Summary).

## Verification

- `check-lockstep.cjs`: 87 modules / 0 findings ✅
- `check-tree-health.cjs --strict`: 168/168 ✅
- `test-overview-inventory-parity.sh`: 6/6 ✅
- `check-99-summary-freshness.py --strict-position`: 87 scanned, 81 stamped,
  6 exempt, 0 unstamped, 0 stale, 0 misplaced ✅

## Lesson candidate

**Version-field drift between §00 and §98 banners is not lockstep-gated**
when the date relation is technically satisfied. Possible H10 candidate:
extend `check-lockstep.cjs` with an advisory "Version-field parity"
check (§00 Version SHOULD == §98 Version when both are present). Currently
1/3 H10 (single historical incident, low active surface) — does not
warrant gate addition this turn, but worth recording for the second
recurrence.

This is the second narrative-stale class found via Phase-19→20→21 chain;
the README narrative-count class (Phase 20) and version-field class
(Phase 21) are both candidates for a future "narrative-claims advisory"
sister to the Phase 19 stale-baseline advisory.
