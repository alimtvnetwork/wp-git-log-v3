# Phase 32 — Memory Core staleness sweep & refresh

**Date:** 2026-04-28
**Mode:** No-Questions Mode task 16/40
**Trigger:** Phase 31 close-out rec (a). Core memory headline numbers
("CI gate count 17, RUBRIC v2.26") drifted from live state after Phases
28→29→30→31 advanced gate count to 18, RUBRIC to v2.27, and codified two
new lessons (Ambiguity-04, CI hygiene rule).

## Method

1. Snapshotted live metrics: `check-tree-health.cjs --strict`,
   `check-lockstep.cjs --strict`, `test-qa-baseline-footer.sh`.
2. Diffed against Core memory line 13 ("Tree health 168/168...CI gate count").
3. Identified 4 stale claims + 2 missing lesson promotions.

## Stale → Live deltas

| Claim | Stale | Live | Source phase |
|-------|-------|------|--------------|
| CI gate count | 17 | **18** | Phase 30 |
| RUBRIC_VERSION | v2.26 | **v2.27** | Phase 30 |
| Footer parity | 17/17/17 implicit | **18/18/18** | Phase 30 |
| Phase 19 stale parenthetical | "CI gate count unchanged at 15... production CI gates remain 15" | removed (obsolete since H7 promoted to 17 then Phase 30 to 18) | Phase 19 → H7 → 30 |

## Lessons promoted to Core (line 13 trailer)

1. **Phases 28–31 close-out summary** — one-line summary of each phase
   with disposition (28: dashboard freshness; 29: spec-index regen +
   advisory-rot root-cause; 30: strict-promotion + AC-31-31 cascade; 31:
   sibling scan NO-OP).
2. **Ambiguity-04 codified** — session-local phase counters MUST NOT be
   used as `<!-- verified-phase: NNN -->` stamp values; stamp with global
   `detect_current_phase()` value (currently 147), not session-local.
3. **CI hygiene rule (Phase 31 lesson)** — `|| true` /
   `continue-on-error: true` on a `run:` step is acceptable ONLY in
   `if: always()` summary aggregators writing to `$GITHUB_STEP_SUMMARY`
   AFTER all real gates have run. On any real-validation step it IS the
   Phase 29 advisory-rot pattern and MUST be strict-promoted or carry an
   explicit phase-ref justification comment.

## Verification

- `check-lockstep.cjs --strict` → 87/87 pass, 0 findings ✅
- `check-tree-health.cjs --strict` → 168/168, all 56 modules full marks ✅
- `test-qa-baseline-footer.sh` → 11/11 (declared 18, footer 18, workflow 18) ✅

## Files changed

- `.lovable/memory/index.md` (Core line 13 — single-line replacement)
- `.lovable/memory/audit/v2-deterministic/phase-32-core-memory-refresh.md` (this memo)
- `.lovable/question-and-ambiguity/task-counter.md` (15/40 → 16/40)
- `.lovable/prompts.md` (counter column 15/40 → 16/40)
- `.lovable/memory/specs/phased-roadmap.md` (Phase 32 entry append)

**Zero spec edits, zero CI changes, zero version bumps.** Pure memory
reconciliation phase per H6 lesson #2 (audit-only / memory-only phases
skip §98/§99 banner bumps).

## Lesson codified (for future phases)

**Memory Core freshness sweep cadence**: after any phase that bumps a
project-wide invariant (RUBRIC_VERSION, CI gate count, tree-health
score, lockstep count, audit means), the next-but-one phase SHOULD be
a Core sweep to reconcile the headline numbers. Two-phase lag is
deliberate: lets the new state settle and lets any sibling cascade
phases (e.g. Phase 31's NO-OP scan) finish before reconciling.
