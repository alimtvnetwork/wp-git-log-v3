---
phase: 153
task: A24-fu37
date: 2026-04-30
status: CLOSED (no-op with diagnosis)
gates: lockstep 87/87 · tree-health 168/168 strict · version-parity 74/74 — all GREEN
---

# A24-fu37 — spec/17 v10 −7 inspection (no-op close)

## Diagnosis (Lesson #70 first)

| Metric | Value |
|---|---|
| Score | 92 (EXCELLENT) |
| Band | EXCELLENT |
| `files_used` | **5/39** (walker-budget starved) |
| `bytes_used` | 117.2 KB (at 120 KB cap) |
| Dimensions | d1=18, d2=17, d3=19, d4=18, d5=20 |
| Axis | `process-guidance` (cap=95, d2 mult=0.7) |

Tier-1 footprint: §00=13.3K + §97=43.2K + §98=32.9K + §99=27.3K = **116.7 KB**. Walker fits all 4 tier-1 files + 1 of the alphabetically-first secondary files (§01), then truncates mid-§01.

## Findings (all 3 are walker-budget / by-design)

1. **MEDIUM D2 — Truncated AC in §01.** Auditor self-diagnoses `due to the 120KB cap`. Pure walker-budget artifact (Lesson #70).
2. **LOW D1 — Aspirational folder refs (08-docs-viewer-ui, 09-code-block-system).** Already classified `[doc-only]` in F1; codified at AC-62-04. False-positive.
3. **LOW D4 — Worked example references external spec/03.** By-design cross-module example; per Lesson #36 (link, never restate) — cannot inline.

## Why this closes as no-op

- **Score is at the axis-cap-adjusted ceiling.** Axis `process-guidance` caps at 95, d2 multiplier 0.7 means D2 lifts cost more to extract less. Realistic ceiling: 93-94.
- **No contract gap.** All 3 findings are either walker visibility (1) or by-design (2, 3).
- **Low leverage.** §98 archive split (matching spec/27 fu28/35/36 pattern) would extract ~12 KB, freeing the cap to fit ~12 KB of §01 — auditor would see more ACs but D2 mult 0.7 caps the lift to roughly +1 to +2 score points.
- **Honest-baseline correction (Lesson #18).** The v9 → v10 −7 drop (99 → 92) is real measurement improvement, not regression. v9 was the over-stated baseline.

## Outcome

**Status: stable EXCELLENT at 92, accept as ceiling.** Defer §98 archive split until either (a) Lesson #70 sweep batches all `bytes_used ≥ 115 KB` modules together, or (b) A18 walker MAX_BYTES lift unblocks (then no archive surgery needed).

## Lesson reinforcement

- **Lesson #70 reapplied:** diagnose `files_used / files_total` ratio FIRST. spec/17's 5/39 ratio + 117.2 KB / 120 KB cap = walker-starvation, not contract gap.
- **NEW Lesson #71 — Axis-cap-adjusted ceiling defines no-op closure threshold.** When score = axis_cap − Δ where Δ ≤ 3 AND all surfaced findings are walker-budget or by-design, close as no-op. Allocating phase work to extract ≤2 score points violates leverage hygiene. Codify the diagnosis in a memo so future `next` doesn't re-open the same module.

## Lockstep

No spec edits. No banner changes. All 5 strict gates GREEN.
