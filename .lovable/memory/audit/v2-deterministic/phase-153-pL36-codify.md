# Phase 153 pL36-codify — Lesson #36 forward-looking codification

**Closed:** 2026-04-29
**Type:** docs-only

## What

Appended **Section G — Cross-Module Roll-ups (Lesson #36)** to `mem://process/phase-153-lessons` consolidating the "link, never restate" rule that drove the spec/04 §4.3 P3 closure (concurrency-prose mirror) and the spec/17 §05 + §18 pL36 anchors phase + tree-wide pL36 sweep.

## Changes

1. `mem://process/phase-153-lessons`
   - Frontmatter: `name` updated `(#11–#28)` → `(#11–#37)`; `description` extended to mention Lesson #36 explicitly + audit-corpus #29.
   - New **## G. Cross-Module Roll-ups (Lesson #36)** section: full rule + 4-step canonical-anchor pattern + when/when-not-to-apply + cost/value + Mirror-of-Lesson-#25 cross-reference.
   - **Cross-references** block: added line for spec/04 §4.3 + spec/17 §05/§18 anchors codifying #36.
   - **Reverse index**: split the old `#36` row into `#36 (canonical-anchor)` (links to today's pL36 memos) + `#36 (backlog-enum, prior numbering)` (preserves the prior in-place row for historical traceability).

## What this closes

The pL36-spec17 sweep (last cycle) caught a real `synchronous=NORMAL` divergence in spec/17 mirrors of AC-22; without Section G in the lessons memo, the rule lived only in narrative across multiple per-task memos. Section G makes the rule discoverable as a first-class contributor process surface — future `spec/24-app-design-system-and-ui` or README-style index authors will find it before they paste a contract into a roll-up.

## Mirror chain

- Lesson #25 = SemVer dual-track unification (cross-section axis)
- Lesson #36 = cross-module roll-up link rule (cross-module axis)
- Both eliminate silent dual-source drift classes that strict gates cannot detect.

## Lockstep

None. Pure docs work on a memo file (no spec edit, no §98/§99 ripple, no banner bump, no AC, no CI workflow change).

## Verification

Read-back of the memo file confirmed Section G renders correctly, reverse-index reads cleanly, frontmatter is valid YAML.
