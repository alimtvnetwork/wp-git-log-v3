# Phase 153 Task F-03c-fu1 — Re-score spec/05 + spec/12 + spec/27

**Date:** 2026-05-03
**Status:** CLOSED (1 confirmed lift, 2 noise-band null movements)

## Re-score deltas

| module | cache | re-score | Δ | files | bytes | result |
|---|---|---|---|---|---|---|
| 05-split-db-architecture | 82 | **89** | **+7** | 9/20 | 136 KB | **fu47 confirmed** (normative-contract axis, no cap hit yet) |
| 12-cicd-pipeline-workflows | 85 | 83 | −2 | 16/49 | 136 KB | noise band; integration-spec at floor under 120 KB cap |
| 27-spec-toolchain | 85 | 83 | −2 | 15/57 | 136 KB | noise band; matches A20-fu6 prediction (floor ≈ 85 under 120 KB cap) |

## Class signal — saturated-module null-movement is now reproducible

Three saturated modules now show null-or-negative movement after Lesson #71
promotion-class edits in consecutive re-score phases (F-03c spec/18, F-03c-fu1
spec/12 + spec/27). Strong evidence that **A12 walker-cap raise (120 KB → 250 KB)**
is the only lever that will move these modules past their current floor. Lesson
#71 edits are still correct — they document the pre-closures for future
walker-cap re-baselines — but the score-lift comes only when the walker can see
the additional bundled files.

**Forward implication**: do NOT continue promotion-class edits on saturated
modules expecting score lifts. The pattern is now: ship promotions ONCE
(documentation value), then queue the module for A12 re-baseline.

## Lockstep impact

None. Pure observation phase.

- No spec edits.
- No CI workflow change.
- All 5 strict gates remain GREEN (lockstep 87/87 · tree-health 168/168 strict · version-parity 74/74 · §99 freshness 81+6+0 · folder-refs 0 stale).

## NEW Lesson #73 — Saturated-module score-lift requires walker-cap headroom

Codify in `mem://process/phase-153-lessons` Section H under Lesson #72:
saturation gate (#71) permits promotion-class edits, but **score-lifts on
already-saturated modules are bounded by walker `MAX_BYTES`**. Empirically:

- Modules where `bytes_used < cap` AND axis-cap-not-hit → promotion lifts score (spec/05 +7, spec/11 +8).
- Modules where `bytes_used == cap` (saturated) → promotion ships but score stays in ±2 noise band (spec/12, spec/18, spec/27 — 3-of-3 reproducible).

The promotion edit is still mandatory for documentation value, but
contributors should NOT stack consecutive promotion-class phases on the same
saturated module expecting cumulative score lifts. Queue for A12 instead.

**Source:** F-03c (spec/18 noise) + F-03c-fu1 (spec/12 + spec/27 noise) +
A20-fu6 (spec/27 floor prediction) — three independent confirmations.
