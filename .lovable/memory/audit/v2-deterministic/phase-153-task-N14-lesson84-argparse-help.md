# Phase 153 Task N14 — Codify Lesson #84 (--module slug-vs-path UX hint)

**Date:** 2026-05-05
**Counter:** 15/40 → 16/40
**Outcome:** 1-line argparse `help=` clarification on `--module` flag

## Why now (re-evaluating R1-attempt deferral)
R1-attempt deferred Lesson #84 under "Lesson #36 single-incident threshold".
Re-reading Lesson #36: it governs **cross-module restatement** (dual-source
drift class). Argparse `help=` text is **local CLI hint**, not cross-module
restatement — Lesson #36 doesn't apply.

True applicable lesson: **#15** (UX-friendliness for operators) + **#30**
(verify-before-open) — and the verify already happened (R1-attempt hit the
trap firsthand).

## Edit
`linter-scripts/audit-ai-implementability.py` line 667:
- BEFORE: `help="Only audit this module slug (e.g. 04-database-conventions)"`
- AFTER:  `help="Only audit this module SLUG, not path (e.g. 04-database-conventions; NOT spec/04). Lesson #84."`

## No lockstep ripple
- Script's argparse `help=` is operator UX, NOT normative §97 contract
- Slot 34 §97 ACs (AC-34-09, AC-34-10..12, AC-34-15..17) govern walker
  behavior, scoring, chunking — not CLI hint text
- No version bump (script is at v1.3.0; argparse `help=` changes are
  not SemVer-relevant per the same convention used for `verifies-clause`
  refresh phases like A11a-fu1)
- No §98 row (no contract change)
- No §99 update (no inventory change)

## NEW Lesson #85 (meta)
When deferring a small fix under a lesson threshold, FIRST verify the lesson
actually applies to the fix's class (cross-module vs local; contract vs UX;
RUBRIC-cascade vs hint-text). Lesson #36's "single-incident threshold"
applies to dual-source drift surfaces (cross-module restatement), NOT to
local UX refinements. Mis-citing a lesson to defer trivial work is itself
a documentation drift class.

## Memos referenced
- `phase-153-task-R1-attempt-402-deferred.md` (Lesson #84 origin)
- Lesson #36 in `mem://process/phase-153-lessons` (cross-module restatement)
- Lesson #15 in same memo (operator UX)
