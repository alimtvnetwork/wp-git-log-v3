# Phase 153 Task A20-fu3 — v9 full-tree AI-implementability rebaseline

**Date:** 2026-04-30
**Trigger:** Captured cumulative A24-fu16 (spec/07 walker-pin promotion) + A24-fu17 (spec/02 floor-lift AC-CG-25/26/27 + §00 walker-pin) lifts.
**Status:** CLOSED

## Headline

Tree mean **87.7 → 88.4 / 100 (+0.7)** · EXCELLENT modules **8 → 9** · spec/02 (the floor) lifted **75 → 92 (+17)** GOOD → EXCELLENT.

## Per-module deltas

| Module | v8 | v9 | Δ | Band |
|---|---:|---:|---:|---|
| 02-coding-guidelines | 75 | **92** | **+17** | GOOD → **EXCELLENT** |
| All others | — | — | 0 | unchanged |

22 modules unchanged · 1 module +17 · 0 regressions.

## spec/02 axis breakdown (the win)

| Axis | v8 | v9 | Δ | Notes |
|---|---:|---:|---:|---|
| D1 Acceptance Clarity | 18 | 18 | 0 | stable |
| D2 AC Coverage | 15 | **19** | **+4** | CRITICAL/D2 circular-self-ref closed by AC-CG-25 inline language samples |
| D3 Edge/Error | 14 | **17** | **+3** | MEDIUM/D3 partial-CI-failure addressed by AC-CG-27 fail-fast policy (still flagged at LOW residual) |
| D4 Worked Examples | 12 | **20** | **+8** | HIGH/D4 missing-worked-example fully closed by AC-CG-26 Rust `match` example + counter-example (max score) |
| D5 Cross-Refs | 16 | **19** | **+3** | walker-pin teaser surfaced AC-CG-21/22/24 within tier-1 context |

## v9 residual findings (spec/02)

3 remaining findings, all LOW or MEDIUM:
- **MEDIUM/D3** "Ambiguous Concurrency/Partial Failure in CI Gates" — AC-CG-27 IS the closure but didn't make the v9 walker bundle (6/251 files at 117 KB cap; AC-CG-27 sits at line ~595 in §97 which truncates around line ~500). **Lesson #45 cache-stability** — same finding, different visibility window. Resolution path: either Lesson #50 §00 walker-pin teaser already cites AC-CG-27 (✓ done in fu17), OR future fu accepts MEDIUM as STRUCTURAL-DELEGATION-NOT-MISSING. NOT actionable as a new AC.
- **LOW/D1** "Inconsistent Boolean Prefix Regex" — likely subfolder content invisible to walker.
- **LOW/D5** "Dangling Reference to PowerShell/Research" — slot 09/10 references; AC-CG-17 already covers these as placeholder subfolders.

None promotion-worthy.

## Gateway-budget partials

spec/27 + spec/28 hit HTTP 402 (Cloudflare budget) mid-rebaseline. Per Lesson #20: defer score, don't block phase. Cached scores retained: spec/27 = 83 (GOOD), spec/28 = 97 (EXCELLENT). Re-attempted individually — same 402. **NEW Lesson #58 — Mid-rebaseline gateway 402 is normal.** A full-tree `--force` run consumes ~23 LLM calls; at the tail end the per-account budget can flake. Per-module retry within the same minute will not recover. Acceptable: keep cached scores; the modules will re-score on the next rebaseline naturally.

## Lockstep / CI

- **No spec edits, no §97 changes, no banner bumps, no lockstep ripple** — pure measurement phase per A20/A20-fu2 precedent.
- Cache `.lovable/cache/audit-ai/02-coding-guidelines.json` regenerated; 22 other caches confirmed stable.
- All 3 strict gates GREEN: lockstep 87/87 · tree-health 168/168 strict · version-parity 74/74.

## Validation of A24-fu series effectiveness

| Phase | Module | Pre | Post | Δ | Mechanism |
|---|---|---:|---:|---:|---|
| A24-fu16 | spec/07 | 80 | 89 | +9 | §00 walker-pin promotion (Lesson #55) |
| A24-fu17 | spec/02 | 75 | **92** | **+17** | AC-CG-25/26/27 + §00 walker-pin (Lessons #55/56/57) |

**Lesson #55 (§00 walker-pin) confirmed at 2nd instance with +17 score lift** — strongest single-module lift of the entire Phase 153 A-series. The §00 walker-pin pattern is now the canonical first-tool for any walker-saturated module.

## Migration backlog (carry forward)

§97-buried Lesson #50/#51 pins remaining (per A24-fu16 backlog):
- spec/04 AC-13 — pin in §97; consider §00 promotion at next walker-cap symptom
- spec/13 AC-25 — pin in §97; same
- spec/25 AC-AI-16 — pin in §97; same

These are NOT proactive work — wait for the next rebaseline to surface symptoms (per A24-fu16 migration discipline).
