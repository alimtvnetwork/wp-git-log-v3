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

## Review cadence

User reviews the entire folder at the end of the 40-task budget (or earlier
on demand). Notes are not auto-resolved — leave them in place for the audit
trail; mark resolutions inline with a `## Resolution` section once decided.
