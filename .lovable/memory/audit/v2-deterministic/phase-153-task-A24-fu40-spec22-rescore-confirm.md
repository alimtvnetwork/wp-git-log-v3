# Phase 153 Task A24-fu40 — spec/22 v12 re-score confirms fu29+fu39 lift

**Date:** 2026-05-03
**Module:** spec/22-git-logs-v2
**Action:** `audit-ai-implementability.py --module 22-git-logs-v2 --force`

## Result

| Metric | Pre-fu29 | Post-fu29 (fu20 baseline) | Post-fu39 (fu40 re-score) | Δ |
|---|---|---|---|---|
| total | 83 | (cache stale) | **86** | **+3** |
| total_v6 | 83 | — | 83 | — |
| total_v7 | — | — | 86 | new |
| band | GOOD | GOOD | **GOOD** | — |
| files_used | 3/38 | 3/38 | 3/38 | — |
| bytes_used | 120 KB | 120 KB | 120 KB | — |
| axis | normative-contract | — | normative-contract | — |
| weighted_total | — | — | 85.9 | — |

Dim breakdown: D1=18 D2=19 D3=17 D4=15 D5=14.

## Findings (3, all walker-saturation artifacts)

All three findings are **classified as STRUCTURAL-NOT-DEFECT by AC-78 + AC-22-LV1 + AC-26**
(see fu20 walker-pin block at §00 head per Lesson #61). Cache score cannot move further
without a walker MAX_BYTES bump (A18, blocked on CF-1010).

1. **HIGH/D5** — "Critical normative files missing from context" (cites `04-rest-api-endpoints.md`,
   `18-schema.sql` — both present on disk per AC-78; auditor cannot see past walker cap)
2. **MEDIUM/D4** — "Abstract examples for complex logic" (cites `14-endpoint-examples.md`,
   `18-schema.sql` — same walker-cap artifact)
3. **LOW/D3** — "Concurrency strategy externalized" (asks for inline restatement of spec/13 AC-22 —
   explicit Lesson #36 violation; AC-26 correctly cross-refs)

## Lesson #45 reinforcement

Pre-fu39 cache (post-fu29 split) retained `total: 83` despite the §98 archive having shipped at fu29
(2026-04-30, three days before this re-score). The cache key (`bundle_sha`) was unchanged because the
walker still loaded the same 3 tier-1 files at the 120 KB cap — fu29's §98 split changed §98 size but
not the walker's first-3 tier-1 selection (§00, §97, §98). Only fu39's **§99** archive split changed
which bytes the walker sampled for the §99 slot, shifting `bundle_sha` and unlocking the 83→86 movement.

**Codified rule:** archive splits only move cache when they shift `bundle_sha`; for tier-1-saturated
modules, splitting a file the walker isn't currently truncating produces no score movement.

## Lockstep

No spec edits — pure cache refresh. No banner bumps, no §98 row, no §99 audit row.
Cache file `.lovable/cache/audit-ai/22-git-logs-v2.json` updated by audit script.

## Strict gates

Lockstep 87/87 ✅ · tree-health 168/168 strict ✅ · version-parity 74/74 ✅ — all GREEN
(no spec edits this phase).

## Tree-wide impact

spec/22 is no longer the lowest-band module. Updated band leaderboard (cached scores):
- spec/14: 90 (EXCELLENT, post-fu38)
- spec/22: **86** (GOOD, post-fu40 — was 83)
- spec/12: 84 (GOOD, persistent floor)
- spec/01, spec/07: pending fu41/fu42 OVER-class closes

**spec/12 is now the new lowest-band module** — schedule A24-fu43 next per Lesson #71
ceiling check (axis = `integration-spec`, multipliers d2≤0.83 / d5≥1.10).
