# Phase 153 P48-fu — Tracker: 04-db "Full specification for database naming" missing

**Phase:** 153 (post-P48-4 grounding sweep)  
**Status:** ✅ CLOSED (auditor missed delegation per Lesson #29 / harness false-positive)  
**P47-fu1 finding:** 04-db top_blocker #1 (results[2].top_blockers[0])

## Finding

> "[missing-contract] Full specification for database naming"

## Resolution

Database naming is **delegated** to `spec/01-spec-authoring-guide/02-naming-conventions.md`,
which is the normative source for ALL naming conventions (table/column/file/
folder/identifier). Verified at:

- `spec/04-database-conventions/97-acceptance-criteria.md` line 36:
  `**Source:** spec/01-spec-authoring-guide/02-naming-conventions.md`

This is the canonical Subfolder-Delegation pattern (Lesson #21) applied
across-modules: a child module SHOULD `**Source:**`-delegate to a sibling
when the contract lives elsewhere. The auditor saw the AC stub and flagged
it as "missing"; in fact the contract is fully specified at the delegation
target.

## Lesson #29 / #21 mirror

When a finding is "missing-contract" on an AC that has a `**Source:**`
pointer, the auditor failed to follow the delegation chain. This is a
known harness limitation — not a spec gap.

## Action

None. No spec edit, no lockstep ripple. If this class of false-positive
recurs in audit-v7+, consider extending the AI auditor to follow `**Source:**`
links one hop (analog to walker's tier-1 fix from Lesson #16).


---

**Related lessons:** see [`mem://process/phase-153-lessons`](../../../memory/process/phase-153-lessons.md) for the consolidated Phase 153 contributor rules (#11–#37).
