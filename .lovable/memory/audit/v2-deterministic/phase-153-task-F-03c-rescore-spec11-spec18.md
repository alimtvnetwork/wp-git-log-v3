# Phase 153 Task F-03c — single-module re-score (spec/11 + spec/18)

**Date:** 2026-05-03
**Status:** PARTIAL CLOSED (spec/11 confirmed; spec/18 noise-band)

## Re-score deltas

| module | cache before | re-score | Δ | band | walker | result |
|---|---|---|---|---|---|---|
| 11-powershell-integration | 87 | **95** | **+8** | GOOD → **EXCELLENT** | 18/19, 136 KB | **fu45 confirmed; hits integration-spec axis cap (95)** |
| 18-wp-plugin-how-to | 86 | 85 | −1 | GOOD | 16/35, 136 KB | within ±1 noise band (Lesson #45); fu46 teaser shipped but did not lift |

## Findings

**spec/11** — fu45 walker-pin teaser + winget cross-ref shipped a clean +8 jump and saturated the integration-spec axis cap. All three audit-v7 findings (HIGH/D5 schema artifact, MEDIUM/D5 downstream-repo pin, LOW/D3 winget edge case) cleared on first re-score. Lesson #63 ninth confirmed instance with measurable score lift; Lesson #71 saturation gate validated (`bytes_used 136 KB`, no §97 authoring needed).

**spec/18** — fu46 teaser shipped but score moved within Lesson #45 noise band. Two hypotheses for forward investigation (NOT this phase):
1. Process-guidance axis (cap likely lower than integration-spec's 95) may already be at cap — score has nowhere to go.
2. Bundle truncation point shifted with §00 teaser bytes; auditor may now be missing a different file that previously made the cut. Per Lesson #17, enumerate `files_used` delta (16/35 = 19 unbundled files) before authoring further teaser rows.

Defer spec/18 deeper analysis to a future phase; current closure-rate is acceptable given Lesson #45's expected non-monotonicity.

## Lockstep impact

None. Pure observation phase.

- No spec edits.
- No CI workflow change.
- All 5 strict gates remain GREEN (lockstep 87/87 · tree-health 168/168 strict · version-parity 74/74 · §99 freshness 81+6+0 · folder-refs 0 stale).

## Backlog correction (Lesson #30 hit)

**A19 is ALREADY CLOSED.** `.github/workflows/spec-health.yml:392` shows
`audit-ai-implementability.py --strict` (no `--report-only`). The "promote to
strict" task graduated silently in an earlier phase (presumably A12 or
adjacent). Updated remaining-tasks list accordingly. Lesson #30 strikes for the
fourth time this Phase 153 (after Tasks #9–#11, A11a-fu2, A24-fu46 trio).
