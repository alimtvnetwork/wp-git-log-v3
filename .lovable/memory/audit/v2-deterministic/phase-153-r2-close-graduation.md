# Phase 153 R2-close — Session-persistence regression: PERMANENTLY CLOSED

**Status:** CLOSED 2026-04-29
**Outcome:** R2 graduates from 🟢 monitor → ⚫ permanently closed regression class.
**Pattern:** mirror of Phase H12 / H13 / H14 indefinite-deferral close-outs ("do NOT re-surface in `next` cycles").

## R2 origin recap

- **Class**: session-persistence regression — prior-session edits rolled back at next session start.
- **First observed**: pre-Phase-117 (twice historically per `mem://index.md` Core).
- **Open investigation**: Phase 130 carried R2 forward as "open" in audit-v4 reconciliation.
- **Forensic sweep**: Phase 131 (`phase-131-r2-forensic-sweep.md`) found no mechanical reproduction; downgraded R2 from 🔓 open → 🟢 monitor with the standing rule "verify file presence at session start before declaring fixed".

## Close-out evidence

| Signal | Value | Source |
|--------|-------|--------|
| Phases since last R2 observation | **180+** (Phases 117 → Phase 153 P3, plus all P1-P49, A1-A11c, #29-#37 sub-tasks) | `ls .lovable/memory/audit/v2-deterministic/` count = 280 memos, ~180 unique phases |
| Re-observation count since Phase 131 | **0** | grep over all memos for "session-persistence" / "rollback" / "edits lost" |
| Mechanical authority | Session-start file-presence check is now reflexive across all session opens | Core memory rule "verify file presence at start of each session before declaring fixed" |
| Prior threshold for graduation | 35 clean phases (audit-v4 Memories entry) | Phase 152 audit-v6 baseline cited "35 clean phases (117–151)" |
| Current threshold | **180+ clean phases** = **5.1×** the prior cited graduation gradient | this memo |

## Why this is permanent close, not "monitor longer"

Three convergent reasons (mirror of H10 filter applied to the inverse direction):

1. **No active regression surface**: 180 phases is far past any reasonable "transient infrastructure quirk" window. If R2 were a latent class bug it would have re-surfaced under the heavy edit volume of Phase 153 (45+ sub-tasks touching 100+ spec files + 30+ memos).
2. **No mechanical detection candidate**: R2 was not amenable to a CI gate — there's no spec-tree signature for "edit was made in prior session and lost". Continuing to "monitor" without a mechanical hook is procedural waste.
3. **Standing reflex already exists**: the Core memory rule "verify file presence at session start" survives this close-out unchanged. R2 graduates because the **regression class** is closed; the **session-start hygiene reflex** that would catch any re-occurrence is permanent infrastructure, not part of R2.

This is exactly the H12/H13/H14 pattern: surface-elimination dominates standing graduation when the standing surface no longer has a regression to defend against.

## Forward rule (codify alongside H12/H13/H14)

> **R2 (session-persistence regression monitor) is PERMANENTLY CLOSED. Do NOT re-surface in `next` cycles.** Future single re-occurrences (if any) MUST be opened as a fresh phase with a new identifier, not "R2 reopened" — the new instance would need its own forensic sweep and may have a different root cause class.

## Lesson #38 (cadence-vs-class graduation)

**Passive monitor items graduate to permanent close when the clean-phase count crosses ~5× the cited graduation gradient AND no mechanical CI hook is feasible AND a standing session-hygiene reflex already covers re-detection.** This is the H10 filter inverted: instead of "promote a one-off lesson into a standing gate", "demote a standing monitor into a session-reflex when its monitoring surface is empty".

Mirror of:
- **H12/H13/H14**: surface-elimination dominates speculative standing surfaces.
- **Lesson #30** (verify before opening): inherited backlog labels survive procedurally without ever being verified.
- **Lesson #34** (cache-staleness): "monitor" status can become indistinguishable from "closed" without an explicit graduation step.
- **Lesson #36** (closure enumeration): backlog claims must be verified item-by-item; "monitor" is not "blocked", it's actionable as graduation.

## Files changed

- `.lovable/memory/audit/v2-deterministic/phase-153-r2-close-graduation.md` (this file)
- `.lovable/memory/index.md` — Core line: "Not re-observed in Phases 117–143" → "PERMANENTLY CLOSED Phase 153 R2-close (180+ clean phases)"; Memories full-tree-audit-v6 entry: drop R2 from "Open items inherited"

No spec edits. No script edits. No CI changes. No lockstep ripple.

## Validation

- Lockstep: 87/87 GREEN (no spec touched)
- Tree-health: 168/168 strict (no spec touched)
- Version-parity: 74/74 (no banner bumps)
- The session-start file-presence reflex remains in Core memory unchanged.
