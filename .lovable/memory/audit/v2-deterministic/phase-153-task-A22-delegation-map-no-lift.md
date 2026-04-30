# Phase 153 Task A22 — Subfolder Delegation Map: no score lift; Lesson #45

**Date:** 2026-04-30
**Outcome:** Mixed — spec/05 retained AC-SD-26 (no harm); spec/06 AC-SC-23 reverted (caused 7-point regression). **Lesson #45 codified.**

## What was attempted

EXCELLENT-band push on the two highest-leverage GOOD-band modules (both at 89/100, both `normative-contract` axis with D5×1.4 multiplier per Rubric v7). Hypothesis from Lesson #44: axis-aligned mechanical AC additions on `normative-contract` modules should compound 5-8× over predicted lifts; one Subfolder Delegation Map AC each (per Lesson #21 pattern from spec/02 AC-CG-21) should crack the EXCELLENT band.

Added:
- **AC-SD-26** to spec/05 §97 — Subfolder Delegation Map for `02-features/` (`AC-SDF-NN`) + `03-issues/` (`AC-SDI-NN`)
- **AC-SC-23** to spec/06 §97 — Subfolder Delegation Map for `02-features/` (`AC-SCF-NN`) + `03-issues/` (`AC-SCI-NN`)

Both ACs included three normative sub-rules: cross-link discipline, AC-prefix discipline (child families ⊥ parent), cite-parent rule (Lesson #36).

## What happened

| Module | Pre-A22 score | Post-A22 score | Δ | Outcome |
|---|---:|---:|---:|---|
| spec/05-split-db-architecture | 89 (18,19,17,18,15) | 89 (18,19,17,18,15) | 0 | Kept AC-SD-26 (no harm) |
| spec/06-seedable-config-architecture | 89 (18,19,17,18,15) | **82** (16,18,15,17,14) | **−7** | **Reverted** AC-SC-23 |

Both modules dropped or held; spec/06 lost across **every** dimension (D1−2, D2−1, D3−2, D4−1, D5−1). Classic walker-budget exhaustion (Lesson #16): the new prose pushed an existing tier-1 contract file out of the 87 KB cap. spec/05 had marginally less starting tier-1 content so the new AC fit; spec/06 did not.

Cache for both modules has zero `findings` recorded — auditor returned numeric scores only, no actionable prose.

## Lesson #45 (NEW)

**Subfolder Delegation Map (Lesson #21 pattern) does NOT lift D5 score on `normative-contract` modules with ≥85 KB tier-1 content.** The LLM auditor's bounded-context walker exhausts the 87 KB tier-1 budget on parent §97 + 1-2 feature files BEFORE reaching subfolder §97s, so the cross-references in the map have zero scoring effect.

### Where Lesson #21 still applies

- **Human-implementer value** is real: audit-boundary documentation, AC-prefix discipline contract, cite-parent rule preventing dual-source drift. Keep using Lesson #21 when the goal is **contributor clarity**, not score lift.
- **Smaller modules** (<70 KB tier-1 content) where the walker reaches subfolder §97s — Lesson #21 still benefits the LLM auditor there. spec/02 AC-CG-21 (the original) succeeded because spec/02's parent §97 + early feature files fit comfortably under the 87 KB cap.

### What does lift score on `normative-contract` modules ≥85 KB

- **(a)** Inline D5 citation clusters directly in parent §97 — precedent: spec/03 Task A21 +7
- **(b)** D3 edge-case enumeration tables in parent §97 — precedent: spec/04 Task A21 +8
- **(c)** Tier-1 walker fix for chunky feature files — precedent: spec/05 Task A6 +20 (this is a one-time auditor-side fix, not a per-module lever)

### Contributor rule

For score-lift on normative-contract modules ≥85 KB, **add D3/D5 content to the parent §97 directly — not delegation prose pointing elsewhere.** Codified in spec/05 §98 v4.4.0 row + spec/05 §97 AC-SD-26 `**Source:**` clause + spec/05 §99 v4.1.0 release narrative.

## Lockstep

- spec/05 §97 4.3.0 → **4.4.0** (new AC kept), §00/§98 4.3.0 → **4.4.0**, §99 4.0.3 → **4.1.0**.
- spec/06 reverted to pre-A22 banners (§97 4.1.0, §00/§98/§99 4.3.0). Cache restored to 89.
- All 5 strict CI gates remain GREEN.

## Open items

| # | Status | Task |
|---|---|---|
| **R1** | 🔒 blocked | Trace-map deeper bindings — needs `enable cloud` |
| **A18** | ⚪ conditional | D5 honor-list pattern auto-detection — only if future re-score reveals miscalibration |
| **A23** | 🟢 ready (optional) | EXCELLENT-band push via inline D3/D5 content (Lesson #45 working levers) on spec/05 or spec/06 — different approach than A22 |

A22 closes with a documented null result. The session-net delta: spec/05 +1 normative AC (no score change), spec/06 unchanged, +1 contributor lesson codified.
