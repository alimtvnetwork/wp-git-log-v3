# Task Counter — No-Questions Mode (Prompt 01)

**Budget:** 40 tasks
**Activated:** 2026-04-28
**Counter:** 5 / 40
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
| 1 | 2026-04-28 | Phase 18 — tree-health drift sweep + trace-map rebaseline | 01-trace-map-plus-2-untraced-acs |
| 2 | 2026-04-28 | Phase 18-resolution — root-cause: stale baseline (not new ACs) | 02-stale-baseline-ci-guard |
| 3 | 2026-04-28 | Phase 19 — stale-baseline advisory (Ambiguity-02 option 3) | none (resolves 02) |
| 4 | 2026-04-28 | Phase 20 — README stale-prose sweep (linter-scripts/test/) | none |
| 5 | 2026-04-28 | Phase 21 — §00-overview stale-prose sweep (banner + slot-70) | none |

## Deactivation trigger

When counter reaches **40**, mark this file `Status: ✅ completed`, update
`.lovable/prompts/01-no-questions.md` frontmatter (`status: completed`,
`deactivated: <date>`), and update `.lovable/prompts.md` index row. Resume
normal question-asking behavior on task 41.
