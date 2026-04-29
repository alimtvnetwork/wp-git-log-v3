# Phase 153 P49 — AC-T-13 mechanical-lock graduation (P46-followup-3)

**Date:** 2026-04-29
**Status:** CLOSED — third and final P46-followup graduation

## Summary

Closed the last of the three retroactive parity-AC graduation candidates
surfaced by P46. AC-T-13 (generator determinism) cited three generators
in `**Verifies:**` — auditor + `generate-spec-index.cjs` +
`generate-dashboard-data.cjs` — but only the auditor was mechanically
locked by `test-audit-deterministic-stability.sh`. P49 extended the
self-test to cover all three.

## Mechanical work

- `linter-scripts/test/test-audit-deterministic-stability.sh` — added
  `run_twice_byte_identical()` helper that:
  1. Snapshots the live artifact (working-tree contract).
  2. Runs the generator twice via `node <script>`.
  3. Asserts sha256 + byte-count parity across both runs.
  4. Restores the original artifact (working tree byte-identical pre/post).
- Two new test sections invoke it for `generate-spec-index.cjs` →
  `spec/spec-index.md` and `generate-dashboard-data.cjs` →
  `spec/dashboard-data.json`.

## Verification

| Gate | Result |
|---|---|
| Self-test assertion count | 7 → **13** GREEN |
| Lockstep | 87/87 |
| Tree-health (strict) | 168/168 |
| Version-parity | 74/74 |

Confirmed both extended generators produce byte-identical output across
back-to-back runs — `new Date().toISOString().slice(0,10)` is stable
within a single day, so no script change was needed.

## AC + lockstep ripple

- §97 v2.8.0 → **v2.8.1** — extended AC-T-13 `**Verifies:**` with
  `**Mechanical lock (P49):**` line citing the self-test.
  No new AC, no AC-31-31 cascade.
- §00 v2.77.1 → **v2.77.2**, §98 v2.77.1 → **v2.77.2**, §99 v2.74.1 → **v2.74.2**.
- No CI workflow change (existing self-test step now exercises 6 more
  assertions automatically).

## Lesson #31 (codified inside §98 P49 row)

Self-tests that mutate working-tree artifacts MUST snapshot pre-existing
output and restore it after the test. Without the restore, the self-test
itself would corrupt git working state every CI run (e.g. inserting
today's date into `Generated:` lines on a tree where the prior generation
was on a different date). The `run_twice_byte_identical()` helper
formalizes the snapshot-restore contract for any future generator added
to AC-T-13's parity set.

## P46-followup completion

| Followup | Phase | AC | Status |
|---|---|---|---|
| #1 | P47 | AC-31-29 (memo-retro tri-source) | CLOSED |
| #2 | P48 | AC-T-11 (stderr/stdout convention) | CLOSED |
| **#3** | **P49** | **AC-T-13 (generator determinism)** | **CLOSED (this phase)** |

P46 → P47 → P48 → P49 retroactive graduation chain complete. All 14
parity-ACs in §27 are now either (a) already-locked at P46 survey time
or (b) mechanically locked by a self-test cited in `**Verifies:**`.

## References

- P46 closure: `mem://index.md` line 52 (parity-AC survey)
- P47/P48 precedents: prior `phase-153-task-*-graduation.md` memos
- AC-T-13: `spec/27-spec-toolchain/97-acceptance-criteria.md` line 74
- Self-test: `linter-scripts/test/test-audit-deterministic-stability.sh`
