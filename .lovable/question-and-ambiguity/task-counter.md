# Task Counter — No-Questions Mode (Prompt 01)

**Budget:** 40 tasks
**Activated:** 2026-04-28
**Counter:** 0 / 40
**Status:** 🟢 active

## Counting rule

Increment by 1 per **completed user-facing task** (one user message → one
unit of work delivered). Setup/admin sub-steps inside a single task do NOT
each count separately.

This setup task (creating prompt-01 + index + folder scaffolding) is task
**counter setup**, not task #1. Task #1 begins on the user's next message.

## Log

| # | Date | Brief | Ambiguity notes filed |
|---|------|-------|----------------------|
| 0 | 2026-04-28 | Setup: prompt-01 + index + folder scaffolding | none |

## Deactivation trigger

When counter reaches **40**, mark this file `Status: ✅ completed`, update
`.lovable/prompts/01-no-questions.md` frontmatter (`status: completed`,
`deactivated: <date>`), and update `.lovable/prompts.md` index row. Resume
normal question-asking behavior on task 41.
