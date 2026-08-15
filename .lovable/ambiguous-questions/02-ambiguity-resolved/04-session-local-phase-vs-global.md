# Ambiguity 04 — Session-local phase counter vs global integer phase

**Surfaced:** Phase 30 (No-Questions Mode 14/40), 2026-04-28  
**Severity:** Medium (caused two gate failures during cascade; resolved)  
**Status:** Self-resolved with codified rule

## Observation

The "No-Questions Mode" session-local counter has been numbering its work
"Phase 18..30" since the session began. The project-wide integer phase
counter, however, sits at **Phase 147** (B1 close), with H1..H9 / F1..F3 / B1
as letter-prefixed branches off that integer line.

`linter-scripts/check-99-summary-freshness.py::detect_current_phase()`
computes `max(phase number)` from `.lovable/memory/index.md` + `spec/27-spec-toolchain/98-changelog.md`. That returns **147**, not 30.

## Failure mode observed

When stamping `spec/27-spec-toolchain/99-consistency-report.md` with
`<!-- verified-phase: 30 -->` during Phase 30's cascade:

1. **Freshness gate (slot 26)**: `delta = 147 - 30 = 117 > max-age 20` → exit 1.
2. **Stamp-bump gate (slot 27)**: stamp `30` ≠ current `147` → would exit 1 if material edits.

## Resolution applied (Phase 30)

Stamped with `147` (current global head) instead of `30`. Both gates pass.
Session-local "Phase 30" naming preserved in narrative prose only —
`max()` ignores it because `30 < 147`.

## Forward rules (candidate for Core memory)

When operating in a session-local naming convention (e.g. "Phase 18..40"
under a No-Questions audit pass) while the project-wide integer counter is
ahead:

1. **Never** stamp a §99 with the session-local number. Use the global
   `detect_current_phase()` value (currently 147).
2. Session-local names belong in: changelog narrative, memo filenames,
   roadmap entries, ambiguity notes — anywhere that's prose metadata only.
3. Alternative: continue the integer sequence (this session's "Phase 30"
   → "Phase 148"). Cleaner long-term but breaks session-counter mapping
   and would force renaming all 12 prior session phases (18..29).
4. Option (1) is preferred — zero rename cost, zero validator friction,
   session-local semantics preserved.

## Codification

Added to Phase 30 memo as Lesson #4. Candidate for Core-memory promotion
if a third instance occurs (e.g. another session-local counter session).

## Resolution: SELF-RESOLVED · do NOT escalate to user


---
## Status

**Status:** Resolved
**Resolved-in-phase:** pre-Phase-153 (legacy archival — exact phase not recorded at closure time; this footer added by hygiene-round-3 to normalize the closure protocol per README convention)
**Resolved-on:** unknown (legacy)
**Resolution:** see body — original "RESOLVED" / "SELF-RESOLVED" note retained verbatim above this footer.
**Do not re-surface:** yes
