---
task: Phase 25 — multi-target sweep close-out (REVISED)
date: 2026-04-28
phase: 25
type: ambiguity-resolved-self
---

# Ambiguity 03 — Core memory CI-gate count stale (RESOLVED, no action needed)

## Status: SELF-RESOLVED 2026-04-28

Initial concern: the in-context `mem://index.md` summary shown to the
session said "CI gate count 14 → 15" while `test-qa-baseline-footer.sh`
reports 17/17/17 parity.

**Investigation result:** On-disk `.lovable/memory/index.md` already
correctly states "CI gate count **17**" (line 13). The "15" figure
appears only in the auto-summarized context block shown at the top of
each session — that summary is regenerated from the full file by the
harness and is not authored content. The on-disk source of truth is
already correct.

**No memory edit needed.** Filing this note as a record so that if a
future session sees the same context-summary artifact, they know the
underlying memory is already current (last bumped at H7 close).

## Lesson codified

When the in-context Core summary appears to disagree with verified
source-of-truth (self-tests, footer parity), check the on-disk
`.lovable/memory/index.md` BEFORE assuming a drift exists. The auto-
summary may be stale relative to the full file even when the file
itself is current.

## Suggested clarification

User confirms the auto-summary cadence — does it regenerate per
session, per phase, or only on memory-write events? Knowing this
helps future sessions calibrate trust in the Core summary block.


---
## Status

**Status:** Open
**Last-reviewed:** 2026-06-28 (hygiene-round-3 — footer added per new closure protocol)
**Blocked-on:** gateway-budget (HTTP 402 oscillation per Lesson #86) + user-decision on auto-summary cadence (file 03) / budget reset (file 05)
