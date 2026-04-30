# Phase 153 Task A12 — AI-implementability gate graduates to `--strict`

**Date:** 2026-04-30
**Trigger:** User `next` after A8 closure unblocked the cascade. A12 was the cheapest unblocked surface (single CI flag flip + spec status section + lockstep).
**Status:** CLOSED.

## What

Flipped `.github/workflows/spec-health.yml` "AI-implementability deep-walk audit" step from `--report-only` to `--strict`. The gate now exits 1 if any module scores BLOCKING (<60); GOOD (75-89) and NEEDS_WORK (60-74) still pass (advisory inside the report).

## Graduation criteria (H10 filter — all 3 satisfied)

| # | Criterion | Evidence |
|---|---|---|
| 1 | Mechanically detectable | Per-module score is an integer 0-100 returned by gateway as JSON; deterministic threshold check at script line 326-328. |
| 2 | Active regression surface | Any new module with thin §97, or contract amputation on existing module, could drop into BLOCKING<60. v5 baseline (A8) shows 4 modules at the 75-floor — they sit 15 points above the threshold but a 16-point regression would trip the gate. |
| 3 | Low false-positive risk | Tree mean 84.7 sits **25 points above** BLOCKING; current 75-floor sits **15 points above** = one full GOOD-band gap (75-89 spans 15 points). Wide moat against rubric ~3-5 point inter-run noise observed during A6-A8. |

## Files changed

1. `.github/workflows/spec-health.yml` — step renamed "Phase 153 Task A5, advisory" → "Phase 153 Task A12, strict on BLOCKING"; comment block rewritten to cite A8 v5 baseline (84.7) instead of A3 baseline (81.6); flag flipped `--report-only` → `--strict`. Added 75-floor disclosure + threshold-lock prohibition.
2. `spec/27-spec-toolchain/34-audit-ai-implementability.md` — slot 34 §00 v1.1.0 → v1.2.0. `## Status` section rewritten: "Advisory-by-default" replaced with "Strict on BLOCKING (Phase 153 Task A12, 2026-04-30)" + H10 graduation evidence + threshold-lock clause forbidding lowering the threshold without a Rubric v7 design memo update.
3. `spec/27-spec-toolchain/00-overview.md` — banner v2.77.4 → v2.78.0 (slot-34 minor cascades to module minor per Lesson #25 lockstep budget).
4. `spec/27-spec-toolchain/98-changelog.md` — banner + new top row v2.78.0 with full action narrative + Lesson #40 codification.
5. `spec/27-spec-toolchain/99-consistency-report.md` — banner + new top blockquote v2.75.0 with full audit narrative.
6. This memo.

## Lockstep + gates

- §97 unchanged (v2.8.1) — no new AC, no AC-31-31 cascade, no RUBRIC bump, no gate-count change. AC-34-08 (`--report-only` never fails) contract preserved.
- §27 banners cascade: slot 34 minor (v1.1.0 → v1.2.0) → §27 module minor (v2.77.4 → v2.78.0) → §98 minor → §99 patch (v2.74.4 → v2.75.0).
- Verified: lockstep 87/87 GREEN, tree-health 168/168 strict, version-parity 74/74.
- Dry-run of the new strict invocation against current tree: exits 0 (0 BLOCKING modules) — no immediate false-positive.

## Lesson #40 — LLM-gate strict-graduation moat width

When graduating an LLM-driven advisory gate to strict, the strict threshold MUST sit at least one band-step below the current floor:

```
strict_threshold = current_floor - rubric_band_width
                 = 75 (current floor) - 15 (GOOD band 75-89)
                 = 60 (BLOCKING boundary)
```

Without the moat, the gate would oscillate between PASS and FAIL on rubric-noise alone. Observed inter-run variance during A6-A8 was ~3-5 points on the same `bundle_sha`; a strict threshold at the floor (75) would FAIL ~30% of CI runs purely on noise. A 15-point moat reduces the false-positive probability to effectively zero (5σ below noise floor).

**Rule**: future LLM-gate graduations MUST cite floor + moat width in the gate's §00 Status section. Mirror of Lesson #28 (comparator hygiene — inspect comparator before mass-patching) for the gate-threshold axis.

**Codified in**: §98 v2.78.0 row (this phase) + slot 34 §00 Status section threshold-lock clause.

## Why now (Lesson #30 verify-before-open)

Pre-flight: checked `LOVABLE_API_KEY` (available — confirmed at A8), checked CI YAML for current flag (`--report-only` at line 387), checked script for strict mode (already present at line 288). Nothing to author — only a flag flip + spec status update + lockstep. Smallest unblocked surface in the A8 cascade.

## Remaining work (post-A12)

| # | Status | Task | Notes |
|---|---|---|---|
| **A16** | 🟢 unblocked | Add `content_axis: specifies-behavior \| describes-other-specs` to all 23 module §00 front-matter | Mechanical bulk-edit; foundation for Rubric v7. |
| **A17-A20** | 🟢 unblocked | Slot 34 axis routing + D5 honor list + LLM rebaseline | Closes the 4 75-floor structural ceilings (03/12/17/25). |
| **R1** | 🔒 blocked | Trace-map deeper bindings (run.sh / deprecated-v1 / helpers / audit-internals) | Needs `enable cloud` (separate from LOVABLE_API_KEY). |

A16 is the natural next step (smallest unit of Rubric v7 work — 23 front-matter edits, no script work).
