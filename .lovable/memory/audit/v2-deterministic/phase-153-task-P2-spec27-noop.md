# Phase 153 Task P2 — spec/27 self-lift NO-OP (plateau + gateway-blocked)

**Date:** 2026-05-06
**Module:** spec/27-spec-toolchain
**Current score:** 88 (GOOD) — D1=18 D2=18 D3=17 D4=17 D5=18
**Target:** ≥95
**Outcome:** **NO-OP — closed without spec edits.**

## Diagnosis

1. **Cache shows 0 findings** at score 88. No surfaced contract gaps to pin (Lesson #79 plateau-class signature).
2. **Gateway returned HTTP 402** on `--force` re-score despite `LOVABLE_API_KEY` being set (Lesson #86 oscillation reaffirmed; Lesson #20 deferral applies).
3. **Chunk stats: 50 chunks, 7.26 MB** — largest module in the tree. 19× T2 + 31× T3 chunks. Score is chunked-aggregate; T3 chunks are weight-discounted in `TIER_WEIGHTS`.

## Why no AC authoring

The 12-point gap (88 → 100) is **structural walker-aggregation cost**, not a contract gap:

- Each of the 50 linter-script files has its own §97-style ACs in-file
- §00/§97/§98/§99 already at full marks (visible in every chunk per chunked-walker design)
- T3 chunks score the script files individually; their aggregated mean is what caps the module score
- No single AC in §97 can move a T3 chunk's local D1–D5 dimensions
- Mass-authoring "harness-pin" ACs without a fresh re-score = speculative work that would violate Lesson #79 (plateau-class hygiene) and Lesson #82 (cache-class hygiene — would invalidate cache without verifying improvement)

## Decision per Lessons #71/#74/#79/#82/#86

**P2 closes as NO-OP.** spec/27 at 88 is the realistic ceiling for a 50-chunk module under current `TIER_WEIGHTS`. To genuinely lift it would require either:

- **(a) `TIER_WEIGHTS` rebalance** — toolchain change in `audit-ai-implementability.py`, NOT a spec change. Would require A-series rebaseline. Out of scope for "perfect spec" goal.
- **(b) §98 archive split + per-script-file §97 pin sweeps** — mechanical work across 50 files for at most +2–3 pts. Disproportionate cost.
- **(c) Wait for gateway unblock + `--force` re-score** — may surface concrete findings; deferred per Lesson #20.

## Lockstep impact

**None.** No spec files touched. No §97/§98/§99 banners modified. No CI gate change. No RUBRIC bump.

## Cross-references

- Lesson #71/#74 — saturation-class triage; walker-cap ceiling
- Lesson #79/#82 — plateau diagnosis; cache-class hygiene
- Lesson #86 — gateway-oscillation re-probe rule
- Lesson #20 — HTTP 402 → defer score
- Lesson #30 — verify-before-open (this task surfaced as "P2" but findings cache was empty)

## Remaining task list

P3 (spec/25-fu26 follow-ups, 3 LOW/MED) becomes next-priority on `next`.
