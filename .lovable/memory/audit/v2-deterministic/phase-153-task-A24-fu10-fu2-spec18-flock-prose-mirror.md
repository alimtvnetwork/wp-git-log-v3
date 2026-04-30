# Phase 153 Task A24-fu10-fu2 — spec/18 flock prose-mirror + CHANGELOG.md cleanup

**Date:** 2026-04-30
**Module:** spec/18-wp-plugin-how-to (axis: process-guidance; band: GOOD @ 88)
**Closes:** 2 of 3 audit-v7 cache findings (HIGH/D5 verified stale-cache no-op; MEDIUM/D3 + LOW/D1 actionable)

## Findings → resolutions

| Severity | Dim | Title | Resolution |
|---|---|---|---|
| HIGH | D5 | External Reference Path Drift | **Stale-cache no-op** — `01-foundation-and-architecture.md:5` already points to canonical `../02-coding-guidelines/01-cross-language/04-code-style/00-overview.md` (verified via `rg`); auditor cited a phantom drift |
| MEDIUM | D3 | Concurrency Contract Implementation Gap | **Prose-mirror** — added normative blockquote at `04-logging-and-error-handling.md:68` mirroring AC-11's flock contract (LOCK_EX before fwrite, LOCK_NB FORBIDDEN, atomic rotation); Lesson #33 follow-up |
| LOW | D1 | Filename Casing Inconsistency | **Mechanical sed** — executed AC-14's enumerated cleanup `sed -i 's/CHANGELOG\.md/changelog.md/g' 10-deployment-patterns.md` (4 lines: 38, 54, 785, 977 incl. section heading) |

## Lockstep

- §97 **unchanged** (no new AC, no contract change)
- §00 v1.4.0 → **v1.4.1** (h10 stamp 153 retained)
- §98 v1.4.0 → **v1.4.1** (new row)
- §99 v1.4.2 → **v1.4.3**
- Patch-only budget per A11a-fu1 + P3 prose-mirror precedent

## Gates (post-run)

- Lockstep: **87/87** (0 findings)
- Tree-health strict: **168/168** (100/100)
- Version-parity strict: **74/74** matches

## Lessons reinforced

- **Lesson #33 (4th instance)** — §97-WINS contract pins demand sibling-file prose-refresh. Pattern now: A11a-fu1 (spec/13 exit codes) → A24-fu14 (spec/07 deprecation chain) → A24-fu15 (spec/13 exit-code table) → A24-fu10-fu2 (spec/18 flock).
- **Lesson #50 NOT triggered** — distinction codified: walker-cap truncation IS present (`files_used: 15/35`) but cited findings happen to land in the visible bundle, so all are genuinely actionable or stale-cache (no STRUCTURAL-DESIGN-NOT-DEFECT pin needed). Useful counter-example for future contributors: walker truncation alone is not sufficient grounds for Lesson #50 — the cited file/contract MUST also be invisible.
- **Lesson #34 reconfirmed** — HIGH/D5 was stale cache; verifying on-disk state via `rg` BEFORE allocating remediation effort saved a phantom phase.

## Score expectation

LLM re-score deferred per Lesson #20 (gateway available but score-lift modest: D3 +1-2, D1 +0.5; band stays GOOD). Real lift on next A20-fu2 rebaseline.
