---
name: phase-96-memo-freshness-sweep-90-95
description: Phase 96 — third memo freshness sweep, applying the Phase 88/92 pattern to Phases 90–95 (only Phase 93 carried a forward-looking pointer requiring a retrospective)
type: feature
---

# Phase 96 — Memo Freshness Sweep (Phases 90–95)

**Date:** 2026-04-27
**Status:** ✅ Complete
**Trigger:** Phase 92's "Next iteration" pointer + Phase 95's "Remaining Tasks" queue item #2
**Predecessors:** Phase 88 (Phases 78–80), Phase 92 (Phases 81–89)

## Why

The Phase 88/92 pattern: every phase memo that ends with a forward-looking
section ("Next phases (queued)", "Next iteration", "Remaining tasks") risks
becoming a misleading historical artefact once those items either ship,
get rejected, or are reordered. Adding a `## Retrospective` footer
preserves the historical roadmap *and* records the actual outcome, so a
future contributor reading the memo in isolation isn't tempted to
re-propose a rejected idea or re-do work already shipped under a
different name.

Phase 96 sweeps Phases 90–95.

## Sweep result

Per-memo audit:

| Memo | Has forward-looking section? | Stale claims? | Footer added |
|------|:-:|---|:-:|
| `phase-90-explain-flag.md` | ❌ | Memo is terminal — pure shipping record (Why / Change / Verification) | — |
| `phase-91-cli-self-test.md` | ❌ | Memo is terminal — ends with "Why this matters (broader pattern)", no queued items | — |
| `phase-92-memo-freshness-sweep-81-89.md` | ⚠️ Has "Next iteration" pointing at Phase 96 | The pointer **was the trigger for this very phase** — discharged by Phase 96's existence | — (self-discharging) |
| `phase-93-lifecycle-mmd-canonical.md` | ✅ Has "Next iteration" requesting a discovery-shift retrospective | The queued framing ("flesh out skeleton") understated the actual scope (full rewrite + AC-SAG-23 + inline excerpt). Discovery shift recorded in footer. | ✅ |
| `phase-94-explain-contract-test.md` | ❌ | Memo is terminal — ends with "Pattern crystallised" + "Why this matters" | — |
| `phase-95-determinism-stability.md` | ❌ | Memo is terminal — ends with "Pattern completion" triad table | — |

**1 footer appended; 4 memos confirmed already-fresh; 1 self-discharging pointer.**

This is a notable shift from the Phases 81–89 sweep (which appended 3
footers across 9 memos): the Phase 90+ memos adopted a **terminal-by-default
style** with retrospective patterns ("Why this matters", "Pattern
crystallised") instead of speculative roadmaps. The freshness debt is
therefore much lower per-memo.

## Footer pattern

The Phase 93 footer follows the Phase 88 template exactly:

- `## Retrospective (added in Phase 96)` heading after a `---` separator
- Outcome-map table: `Original queued framing | Actual outcome`
- Lesson paragraph capturing the meta-pattern (here: when a "fill in / flesh
  out" task arrives, diff the artefact against the real system before
  treating it as cosmetic — drift usually warrants rewrite + lockstep AC)

## Why the Phase 90+ memos resist drift

Two writing-style changes since Phase 89 cut the freshness-debt rate:

1. **Terminal sections replaced roadmap sections.** Where Phases 78–89
   ended with "Next phases (queued)" tables, Phases 90–95 end with
   "Why this matters" or "Pattern crystallised" sections. The former lists
   ages; the latter doesn't.
2. **Forward-looking content moved to the chat reply.** The "Remaining
   Tasks" priority table (B1, R1, queued phases) is now produced fresh in
   each chat turn rather than baked into memos, so it reflects the live
   queue instead of a snapshot.

Recommendation: keep this style going forward. The freshness sweep
phases (88, 92, 96) become cheaper as a result and may eventually be
unnecessary.

## Verification

- **Tree health (strict):** ✓ 100/100 across 56 modules
- **Lockstep (strict):** ✓ 0 findings
- **Audit (`--min-weighted=97 --min-impl=99`):** ✓ 98.0 / 99.8 PASS
- **CLI self-tests (Phases 91 / 94 / 95):** all green
- **Cross-links:** ✓

No spec-side changes — this sweep operates entirely under
`.lovable/memory/`, outside `spec/`.

## Files touched

- `.lovable/memory/audit/v2-deterministic/phase-93-lifecycle-mmd-canonical.md` — appended retrospective footer (discovery-shift record)
- `.lovable/memory/audit/v2-deterministic/phase-96-memo-freshness-sweep-90-95.md` — this memo (NEW)

## Next iteration

Phase 100 (or whenever the queue head reaches a similar age) should sweep
Phases 96–99 with the same template. If Phases 96+ continue the
terminal-by-default writing style, the next sweep may find **zero** memos
needing footers — at which point the freshness sweep cadence can be
relaxed or the practice retired with a closing memo.
