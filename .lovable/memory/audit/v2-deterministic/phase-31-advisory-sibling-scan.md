# Phase 31 — Advisory CI step sibling scan (NO-OP)

**Date:** 2026-04-28
**Mode:** No-Questions Mode task 15/40
**Trigger:** Phase 29 root-caused the spec-index drift gate's `|| true`-style
advisory pattern. Phase 30 strict-promoted it. This phase tests the hypothesis
"the rot pattern likely has siblings" by sweeping `spec-health.yml`.

## Method

1. Enumerated every `run:` step in `.github/workflows/spec-health.yml` (24 steps).
2. Grepped for advisory patterns: `|| true`, `|| exit 0`, `continue-on-error`,
   trailing `exit 0`, `|| echo`.
3. Inspected `Self-heal` step (line 57) — flagged in Phase 30 follow-up notes.

## Findings

**Advisory `|| true` hits — 5, all in the `Summary` step (lines 311, 314, 317, 320, 323).**

The `Summary` step has `if: always()` and writes to `$GITHUB_STEP_SUMMARY`
**after** all real gates have already run. The `|| true` here is **correct** —
it prevents a cosmetic rendering failure from masking the upstream gate result.
This is NOT the Phase 29 rot pattern (advisory on a real validation).

**Self-heal step (line 57)** — NOT advisory. The `[ -f X ] && node X` chain
returns the last command's exit status. If a script runs and exits non-zero,
the step fails. The only edge case is `[ -f ]` returning false (script
missing), which short-circuits `&&` to exit 1 — also failing the step
(defensible: these scripts should always exist; all 3 verified present).

The CI runner's mutations are also implicitly contained by the Phase 30
strict spec-index drift gate that runs immediately after (line 63): any
self-heal change to spec/ would trip that gate.

## Verdict

**NO-OP.** Phase 29's advisory-rot pattern has zero unaddressed siblings in
`spec-health.yml`. Hypothesis disproven empirically.

## Lesson codified

CI hygiene rule: `|| true` is acceptable ONLY in `if: always()` summary
aggregators that write to `$GITHUB_STEP_SUMMARY` AFTER all gates have run.
Any `|| true` (or equivalent `continue-on-error: true`) on a step that performs
a real validation is the Phase 29 rot pattern and MUST be either strict-promoted
or explicitly justified in a code comment with a phase reference.

No spec edits. No version bumps. No AC changes. No CI changes. Memo + counter only.

## Verification

- `grep -nE "(\|\| true|continue-on-error|exit 0\$)" .github/workflows/spec-health.yml`
  → 5 hits, all in cosmetic Summary step (verified by line-range inspection).
- All 24 real gate steps inspected: each either fails on error (default
  bash + step semantics) or is the cosmetic aggregator.
