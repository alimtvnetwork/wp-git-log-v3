# Question & Ambiguity Log

Centralized log of points where the AI made an inference rather than asking
the user, per Prompt 01 (No-Questions Mode).

## Files

- `task-counter.md` — running count of tasks completed under No-Questions Mode
- `01-*.md`, `02-*.md`, … — individual ambiguity notes (sequential numbering)

## Note contract

Each `NN-<brief-title>.md` file MUST contain:

1. **Task context** — feature / spec section the ambiguity relates to
2. **Specific question** — the exact point of uncertainty
3. **Inferred decision** — what assumption was made to proceed
4. **Impact** — how the decision affects the implementation
5. **Suggested clarification** — what the user should confirm

Format: markdown, ≤200 words, ISO timestamp at the top.

## Closure protocol (Normative — Phase 153 / Lesson #32)

Every tracker file MUST end with a `## Status` footer in one of two shapes.
File numbers are immutable — once allocated, never reuse a slot (mirror of
the spec file-slot immutability rule). Closed items stay on disk for audit
trail; the footer is what tells the next session not to re-surface them.

### Open

```markdown
## Status

**Status:** Open
**Last-reviewed:** YYYY-MM-DD (phase NNN)
**Blocked-on:** <gateway-budget | user-decision | upstream-fix | …>
```

### Resolved

```markdown
## Status

**Status:** Resolved
**Resolved-in-phase:** <phase ID — e.g. P49, A18-impl-3, A11c, hygiene-round-2>
**Resolved-on:** YYYY-MM-DD
**Resolution:** <one-sentence pointer to the AC / memo / spec section that closed it>
**Do not re-surface:** yes
```

### Rules

1. `**Resolved-in-phase:**` is the **mandatory** pointer — it lets the next
   `next` command (per Core memory rule) skip items already closed without
   re-reading the body. Phase IDs follow the established convention
   (`P<n>`, `A<n>[-<suffix>]`, `H<n>`, `hygiene-round-<n>`, etc.).
2. If a closed item lacks a phase pointer (legacy archival), the closing
   contributor MUST add `**Resolved-in-phase:** pre-Phase-153 (legacy archival)`
   — do NOT invent a phase number.
3. Closed items MUST NOT be deleted; the audit trail is the deliverable.
   To "remove" a misfiled entry, change Status to
   `Withdrawn (misfiled — see <correct slot>)` and keep the file.
4. The `Do not re-surface` flag is the discoverability hook for future
   sessions — grep `^**Status:** Resolved` to enumerate closed items;
   grep `^**Status:** Open` to enumerate the actual backlog.

## Review cadence

User reviews the entire folder at the end of the 40-task budget (or earlier
on demand). The closure protocol replaces the previous "leave them in place"
guidance — auditors now have a machine-greppable surface for open vs closed
without reading each body.
