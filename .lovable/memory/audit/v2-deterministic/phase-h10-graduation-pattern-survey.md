# Phase H10 — Graduation-Pattern Survey (read-only audit)

**Date:** 2026-04-28
**Trigger:** Backlog item #4 (audit other one-off lessons for promotion to standing gates, applying the H6→H7 / H8→H9 graduation template).
**Type:** Audit-only — no §98/§99 banner bumps (per H6 lesson #2).

## Goal
Apply the H6→H7 / H8→H9 graduation template ("when a one-off fix uncovers a mechanically-detectable pattern with an active regression surface, promote the lesson to a standing CI gate within the next session") to every codified-but-not-yet-mechanized lesson in `mem://index.md` Core + recent phase memos.

## Filter
A lesson promotes IFF:
1. **Mechanically detectable** — can be expressed as a deterministic check on file content / git state / linter output.
2. **Active regression surface** — there exists a live place in the codebase where a future contributor could re-introduce the failure mode.
3. **Low false-positive risk** — the rule discriminates real failures from legitimate exceptions (e.g. H9's adjacency-only rule vs whole-file rule).

A lesson does NOT promote if it's:
- Already structurally enforced by an existing gate (regression surface eliminated).
- Procedural-only (depends on phase intent / context, no mechanical signal).
- Operates on intentionally-historical artifacts (memos, _archive/) where drift is the design.

## Candidates surveyed

| # | Lesson | Source | Verdict | Reasoning |
|---|---|---|---|---|
| C1 | Stale-prose §99 sweep filter (fenced code / blockquotes / Validation History excluded) | Memory line 20 (Phase 136/139) | **No-op — already enforced** | Slot-26 freshness gate scans only INSIDE tracked-heading bodies. By construction this excludes Validation History audit-log subsections, fenced code blocks below tracked headings, and blockquoted documentation. The procedural rule "ignore X when manually sweeping" has no active regression surface — no other linter scans §99 narrative. |
| C2 | Allowlist comment-position (comments MUST be on separate full lines) | Memory line 21 (Phase 143, AC-62-04) | **No-op — already enforced** | `check-spec-folder-refs.py` lines 120-127 mechanically strip inline trailing `# comment` since Phase 143. The parser is now defensive — even if a contributor places `entry # comment` on the same line, the comment is stripped before bucket insertion. The codified rule remains a doc-the-preferred-form recommendation, but the failure mode it described cannot regress. |
| C3 | F1 discovery-count freshness ("re-run linter at resolution time") | Memory line 21 (Phase 141 said "26", F1 found 11) | **Defer — low-value lint** | Mechanizable as `check-memo-discovery-counts.py` scanning memos for `N {missing,broken,stale} <noun>` patterns adjacent to script names. Rejected because memos are intentionally historical snapshots — flagging "stale counts" in a closed phase memo would be high-noise (every memo eventually becomes stale by design). Better expressed as procedural: "re-run linter at resolution time before declaring closure", which is already in memory Core. |
| C4 | H6 lesson — audit-only phases skip §98/§99 bumps | Memory line 16 | **Procedural-only — non-mechanical** | Depends on phase intent (is this an audit or a change?). No git-detectable signal can distinguish "intentional audit" from "forgot to bump". Best codified as memory rule + lockstep gate's existing "skip (no §98/§99)" tally (which already passes audit-only phases). |
| C5 | H6 lesson — archive references first-class when archive IS audit subject | Memory line 16 | **Procedural-only — non-mechanical** | Same — depends on phase intent. The cross-link gate already accepts references into `_archive/` when they resolve; no false-positive surface to harden against. |
| C6 | Session-persistence regression (verify file presence at session start) | Memory line 19 | **Defer — no current surface** | Last observed Phases 117–143 (none re-observed). Self-imposed pre-flight pattern; no mechanical "session start" hook in CI. Would require runtime instrumentation. |

## Net result

**0 candidates promoted.** The graduation pattern's filter (active regression surface AND mechanical detectability AND low false-positive risk) eliminates all 6 candidates:
- 2 already enforced (C1, C2) — surface eliminated.
- 1 low-value mechanizable (C3) — operates on historical artifacts.
- 2 procedural-only (C4, C5) — no mechanical signal.
- 1 dormant (C6) — no current regression surface.

## Lesson codified — graduation backlog is clean
After H7 (runtime archive-exclusion gate) + H9 (stamp-position structural enforcement), the slot-26 / spec-validator graduation backlog is **demonstrably empty**. Future "one-off lesson → standing gate" candidates should be evaluated against the H10 filter above before opening a graduation phase. The filter's three criteria (mechanically detectable + active regression surface + low false-positive risk) are now the canonical gating test.

## Lesson codified — surface-elimination is a valid resolution
Two of six candidates (C1, C2) resolved by the realization that their regression surface had already been eliminated by an unrelated gate (slot-26 H1 in C1's case, AC-62-04's parser hardening in C2's case). Surface elimination is preferable to lint addition — it removes the failure mode rather than detecting it. Future graduation surveys should check for surface elimination FIRST before designing a new lint.

## No code changes
- 0 new files
- 0 modified scripts
- 0 spec edits
- 0 §98/§99 bumps
- 0 AC-31-31 cascade
- All gates remain green at H9-close baseline (87 scanned / 81 stamped / 6 exempt / 0 unstamped / 0 stale / 0 misplaced; lockstep 87/87 / 0; tree-health 168/168 strict; 17/17/17 footer parity).

## Memory updates
Add to memory Core: H10 graduation-survey filter (3 criteria) + surface-elimination preferred over lint addition. Mark backlog item #4 RESOLVED with verdict NO-OP.
