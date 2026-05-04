# Phase 153 Task L75 — Walker-cap finding triage before opening self-lift

**Date:** 2026-05-04
**Closure type:** Pure docs (mem:// only)
**Scorecard impact:** None — opens nothing; prevents wasted self-lift cycles.

## Problem

Surveyed the 4 lowest GOOD-band candidates after the L74 rebaseline-deferral:

| Module | Score | files_used / files_total | Top HIGH/D5 finding |
|---|---:|---|---|
| spec/18 | 85 | 16/35 | "Truncated Context Cap" — Phases 4-21 missing |
| spec/22 | 87 | 5/38 | "Missing Core Normative Files (04, 18, 34)" |
| spec/17 | 88 | 6/39 | "Broken Cross-References to Source Folders" — 08/09/13 (already closed by AC-15 rollup-pin) |
| spec/14 | 91 | — | EXCELLENT, no actionable findings |

All 3 GOOD candidates' dominant finding is **walker-cap truncation**, not contract gap. Adding ACs would not close them — the auditor never bundles the cited files. spec/17's finding is already auditor-misread (closed via AC-15 in Task A24-fu18 per cache `total_v7=88`, but the finding text persists because cache hasn't been refreshed post-edit).

Without a triage rule, the next session would optimistically open spec/18 or spec/22 self-lifts and produce zero score lift + 1 lockstep ripple + drained gateway budget.

## Action

Codified **Lesson #75** in `mem://process/phase-153-lessons` (immediately after Lesson #72):

1. **Triage check** — inspect cached `files_used / files_total` AND grep findings for `truncat|missing core|broken cross-ref|elided|context cap`.
2. **Decision rule** — if `files_used < 0.5 * files_total` OR HIGH/D5 cites truncation class → **walker-cap blocked**, defer to A18.
3. **Branch** — open a pure-docs lesson-codification phase OR shift to NEEDS_WORK-band module instead.

Mirror of Lesson #30 (verify-before-opening) on the *self-lift-target axis*.

Updated `## See also` reverse index with #75 row pointing here.

## Files changed

- `mem://process/phase-153-lessons` (lines 33–35: Lesson #75 body; line 212: reverse-index row)
- `.lovable/memory/audit/v2-deterministic/phase-153-task-L75-walker-cap-triage.md` (this memo)

## Validation

No spec touched · no lockstep ripple · no CI gate change.
