# Phase 153 Task A20-rescore — A21 close-out confirmed

**Date:** 2026-04-30
**Outcome:** Tree-wide zero-NEEDS_WORK milestone.

## Results

| Module | v7 baseline (A20) | v7 + A21 rescored (A20-rescore) | Δ | Band |
|---|---:|---:|---:|---|
| spec/03-error-manage (audit-corpus) | 74 | **81** | **+7** | NEEDS_WORK → GOOD |
| spec/04-database-conventions (normative-contract) | 74 | **82** | **+8** | NEEDS_WORK → GOOD |

**Tree-wide:** mean 83.7 → **84.4** (+0.7); EXCELLENT 5 · GOOD 18 · **NEEDS_WORK 0** · BLOCKING 0.

## Why lifts exceeded prediction by 5-8×

A21 predicted +1..+3 lifts. Actual lifts were +7 / +8. Root cause: v7's per-axis multipliers compound across multiple new GWT ACs:

- spec/03 (audit-corpus) — D5 multiplier ×1.6. A21's two D5 citation-cluster ACs each got ~3.5pt raw → ×1.6 → ~5.6pt → cumulative +7.
- spec/04 (normative-contract) — D3 multiplier ×1.4. A21's edge-case enumeration ACs lifted both D3 (raw +5 → ×1.4 ≈ +7) and adjacent D2 dimension.

## Lesson #44

When axis-driven mechanical AC additions land on `audit-corpus` (D5×1.6) or `normative-contract` (D3×1.4) modules, expect score lifts to exceed predictions by 5-8×. Future predictions should bracket {predicted, predicted+8} for axis-aligned AC additions. Codified inline in spec/17 §98 v3.5.1 row.

## Lockstep

- spec/17 §00/§98/§99 v3.5.0/3.5.0/4.7.0 → **v3.5.1/3.5.1/4.7.1** (patch — pure baseline data refresh).
- slot 35 (`35-full-tree-ai-audit-v7.md`) v7.0.0 → **v7.1.0** (refreshed metrics table, NEEDS_WORK section CLOSED).
- All 5 strict CI gates GREEN.

## Open items after A20-rescore

| # | Status | Task |
|---|---|---|
| **R1** | 🔒 blocked | Trace-map deeper bindings — needs `enable cloud` |
| **A18** | ⚪ conditional | D5 honor-list pattern auto-detection — only if future re-score reveals miscalibration |

All other Phase 153 tasks CLOSED. No active backlog of remaining mechanical work.
