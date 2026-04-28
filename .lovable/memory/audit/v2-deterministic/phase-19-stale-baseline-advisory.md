# Phase 19 — Stale-baseline advisory (Ambiguity-02 option 3)

**Date**: 2026-04-28  
**Mode**: No-Questions Mode (task 3/40)  
**Resolves**: Ambiguity note `02-stale-baseline-ci-guard.md`

## Context

Phase 18-resolution traced the recurring "+N untraced ACs" drift to
manual baseline edits / two-step workflows where `trace-map-baseline.json`
was written against a stale `trace-map.json` on disk. The CLI
`--update-baseline` flag is structurally safe (always re-runs the
generator first), but hand-edits bypass it.

H10 score for the failure mode: 3/3. Ambiguity 02 listed three options:
1. New CI gate (high ceremony, AC-31-31 cascade)
2. Document-only (current default)
3. Linter advisory warning (middle ground)

## Decision

Implemented **option 3** — extended `check-trace-map-regression.py`
with a non-fatal advisory that detects mismatched `ac_total` / `code_total`
between current trace-map and baseline. These totals are tree-shape
invariants; mismatch ⇒ baseline was written against a different tree.

## Implementation

`linter-scripts/check-trace-map-regression.py` — added 14 LOC after the
orphan-grew check:

- Compares `current.ac_total` vs `baseline.ac_total`
- Compares `current.code_total` vs `baseline.code_total`
- On mismatch, emits `::warning::stale-baseline drift: …` to stderr
  (GitHub Actions warning annotation format)
- **No exit code change** — exit 0 preserved when only the advisory fires

## Verification

- Clean run (in-sync baseline): no warning, exit 0 ✓
- Synthetic drift (ac_total -3 in baseline): warning fires, exit 0 ✓
- Baseline restored after test ✓

## Why no AC-31-31 cascade

Per Ambiguity-02 inferred decision: this is an **advisory inside an
existing gate**, not a new gate. CI gate count remains 15. RUBRIC version
unchanged. EXECUTIVE-SUMMARY back-ref not required (no new validator
slot, no new acceptance criteria).

## Lessons

- "Advisory inside an existing gate" is a viable lower-ceremony pattern
  for H10 3/3 findings when the failure mode is detector-friendly but
  not worth a 16th gate.
- `ac_total` / `code_total` parity between current and baseline is a
  cheap tree-shape invariant — useful diagnostic regardless of drift
  direction.
