# Phase 153 Task L73-clarify-scope — Sweep-scope guidance for Lesson #73

**Date:** 2026-05-04
**Closure type:** Pure docs (mem:// only)
**Scorecard impact:** None (no spec edits)

## Problem

L73-preflight (prior phase) ran the Lesson #73 grep across `spec/{13,14,15,16}/97` and produced zero qualifying citations — every hit was either prose, a GWT example, or a §00-declared hard prerequisite. The lesson body did not warn future contributors of this noise floor, so the same no-op sweep is likely to be re-run.

## Action

Appended **Sweep-scope guidance** subsection to Lesson #73 (`mem://process/phase-153-lessons` lines 158–162):

1. Defines the noise classes (prose mentions, fenced GWT examples, §00 prerequisites).
2. Pins the rewrite-trigger to the narrow "normative tool dependency" clause class.
3. Cites L73-preflight as the precedent no-op sweep.

## Lesson-of-the-lesson

When a Lesson #36-class sub-pattern (link-don't-restate variants) graduates from one precedent (AC-24 spec/26) to a tree-wide rule, the codification MUST include the **scope-narrowing clause** alongside the application steps — otherwise the first sweep eagerly applies the rule to every grep hit and produces a no-op phase. Mirror of Lesson #30 (verify-before-opening) for the *codification-completeness axis*.

## Files changed

- `mem://process/phase-153-lessons` (lines 158–162: scope guidance + cost/value extension)
- `.lovable/memory/audit/v2-deterministic/phase-153-task-L73-clarify-scope.md` (this memo)

## Validation

No spec touched · no lockstep ripple · no CI gate change.
