# Phase 153 Task A24-fu18 — spec/17 floor-lift (AC-13/14/15 + §00 walker-pin)

**Date:** 2026-04-30
**Trigger:** A20-fu3 v9 rebaseline left spec/17 as new tree floor at 80 (process-guidance axis).
**Status:** CLOSED

## Findings closed (audit-v9)

| # | Severity | Dim | Title | Closed by |
|---|---|---|---|---|
| 1 | HIGH | D2 | Circular/Self-Referential ACs | **AC-15** STRUCTURAL-ROLLUP-NOT-FIRST-PARTY-CONTRACT (Lesson #51 5th instance) |
| 2 | MEDIUM | D4 | Missing Worked Examples for Consolidated Format (conflict resolution) | **AC-13** Source-Wins + 5-row worked drift example |
| 3 | LOW | D3 | Stale TODO/Marker Heuristic False Positives | **AC-14** `// LINTER-IGNORE-TODO` sentinel contract |

Plus Lesson #55 §00 walker-pin teaser surfacing AC-10/11/13/14/15 in tier-1.

## Lockstep

- §97 v2.5.0 → **2.6.0** (AC count 12 → 15)
- §00 v3.6.0 → **3.7.0** (new normative walker-pin block — minor bump)
- §98 v3.6.0 → **3.7.0**
- §99 v4.7.2 → **4.8.0**
- No CI workflow change · no RUBRIC bump · no AC-31-31 cascade · no gate-count change.

## Lessons codified

- **Lesson #59**: Rollup modules MUST publish Source-Wins with 4-lifecycle-step worked drift example (T0/T1/T2/T3) — T2 AI-agent encounter step mandatory.
- **Lesson #60**: Manual exemption notes flagged by audit harness MUST be replaced with deterministic regex-matchable sentinels — walker-invisible policy → walker-visible + grep-auditable.
- **Lesson #51 5th instance**: Rollup-vs-source axis fully axis-orthogonal at 5 axes.

## Validation

- All 3 strict gates GREEN: lockstep 87/87 · tree-health 168/168 strict · version-parity 74/74.
- LLM re-score: see appended result.
