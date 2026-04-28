# Phase 22 — §00-overview Banner Drift Sweep (No-Questions Mode 6/40)

**Date:** 2026-04-28
**Trigger:** Phase 21 found §27 overview banner ~39 patches behind §98. Recommended next was a fleet sweep.

## Method

`/tmp/banner_drift.py` — for each `spec/*/00-overview.md` with a sibling
`98-changelog.md`, extract the first `vMAJOR.MINOR.PATCH` from the first 2KB
of the overview (banner zone) and compare to the first version in the
changelog (latest release).

## Result

**Modules scanned:** 23 (those carrying both `00-overview.md` + `98-changelog.md`)
**Drift detected:** **0**

§27 (caught in Phase 21) is the only historical case across the fleet.

## Decision: H10 candidate REJECTED

Per H10 filter:
- ✅ Mechanically detectable (script above is 20 LOC)
- ❌ **No active regression surface** — fleet-wide sweep confirms zero drift
- ⚠ Low FP risk, but cost of new gate > benefit when zero violations exist

**Conclusion:** Do not promote "Version-field parity" to a CI gate.
Phase 21 was an isolated stale-prose case (the only module with a Phase
117–143 retrofit history that didn't bump its banner alongside §98 patch
releases). The existing lockstep date-relation gate plus periodic
spot-checks are sufficient.

## Lesson

Before authoring a new lint, run a one-shot fleet sweep to confirm the
violation class actually has population > 1. Single-incident bugs belong
in memory, not in CI.

## Files touched

None — read-only sweep. Recording sweep + decision in memory only.
