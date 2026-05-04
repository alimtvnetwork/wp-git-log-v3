# Phase 153 Task L74 — Gateway probe before rebaseline (refines Lesson #38)

**Date:** 2026-05-04
**Closure type:** Pure docs (mem:// only)
**Scorecard impact:** None — rebaseline deferred per Lesson #20

## Problem

Attempted v13 mini-rebaseline of 3 recently-touched modules (spec/26, spec/05, spec/23) to absorb S26 stdlib-fallback + A05-fu pure-promotion + S23-02 deltas. Pre-flight per Lesson #38: `test -n "$LOVABLE_API_KEY"` → OK. All 3 `--force` re-scores returned **HTTP 402 Payment Required** despite secret being present.

Lesson #38 is **necessary but not sufficient** — it conflates secret-presence with gateway-capacity. The two are orthogonal: Cloudflare credit pool can deplete while the API key remains valid.

## Action

Codified **Lesson #74** in `mem://process/phase-153-lessons` (immediately after Lesson #20):

1. State the refinement: env-var check ≠ capacity check.
2. Mandate a **single cheap probe** (one small module `--force`) before scheduling multi-module rebaseline.
3. Branch: probe OK → green-light A-series; probe 402 → defer per Lesson #20, ship pure-docs/mechanical work.
4. Updated `## See also` reverse index with #74 row pointing here.

## Files changed

- `mem://process/phase-153-lessons` (lines 26–28: Lesson #74 body; line 207: reverse-index row)
- `.lovable/memory/audit/v2-deterministic/phase-153-task-L74-gateway-probe-before-rebaseline.md` (this memo)

## Validation

No spec touched · no lockstep ripple · no CI gate change. v13 rebaseline deferred.
