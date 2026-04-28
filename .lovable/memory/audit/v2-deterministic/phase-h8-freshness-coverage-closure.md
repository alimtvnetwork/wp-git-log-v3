# Phase H8 — §99 Freshness Coverage Closure

**Date:** 2026-04-28
**Trigger:** Backlog item #4 (stamp-bump retroactive opt-in) — final disposition of the 12 unstamped §99 files.

## Outcome
- **Coverage**: 75 stamped + 12 unstamped → **81 stamped + 6 exempt + 0 unstamped** (full 87/87 declared posture).
- **No new gate**, no AC-31-31 cascade, no RUBRIC bump. Slot-26 contract unchanged; behavior extension is internal to AC-26-02 ("tally").

## Validator extension
`check-99-summary-freshness.py`:
- New `EXEMPT_RE = <!--\s*freshness-exempt:\s*([a-z0-9_\-]+)\s*-->`.
- Recognized **anywhere in file** (whole-file property, not heading-scoped).
- Exempt files counted separately, skipped before `find_summary_stamp`, never increment unstamped advisory.
- Output line shape changed: `§99 files scanned: N; stamped: N; exempt: N; unstamped: N`.

## Convention codified
**`audit-log-only`** is the canonical first reason. Qualifies when §99 has neither `## Summary` nor any tracked inventory-rubric heading — only date-anchored audit-log subsections.

The 6 files exempted:
- `spec/02-coding-guidelines/10-research/01-research-index/99`
- `spec/02-coding-guidelines/21-app/01-app-coding-rules/99`
- `spec/02-coding-guidelines/22-app-issues/01-app-issue-templates/99`
- `spec/02-coding-guidelines/23-app-database/01-app-database-conventions/99`
- `spec/02-coding-guidelines/24-app-design-system-and-ui/01-app-ui-conventions/99`
- `spec/14-update/diagrams/01-diagram-conventions/99`

## Lesson 1 — stamp-position precedent
5 files (`05/02-features`, `06/02-features`, `12/01-browser-extension-deploy`, `12/02-go-binary-deploy`, `18/02-enums-and-coding-style`) carried the stamp on the blank line BEFORE `## Summary` rather than under it. The heading-body scanner correctly rejected them.

**Rule**: stamps MUST live inside a tracked-heading body, not adjacent. The H1 contract said this in wording but never enforced position. Position fix is a one-time sweep; the gate's existing scope (heading-body only) is the standing enforcement.

## Lesson 2 — blockquote-buried stamps don't count
`spec/27-spec-toolchain/99` had stamp tokens only inside Validation History blockquotes (lines 22-26), not under any tracked heading. Added a fresh `<!-- verified-phase: 147 -->` directly under `## File Inventory`.

**Rule**: changelog/Validation-History narrative mentioning the stamp value does not satisfy the gate. Each §99 must carry a real machine-readable stamp under its tracked heading body OR an exempt marker.

## Self-test
`test-check-99-summary-freshness.sh`: 12 → **20 assertions**.
- T1 counts-line shape now requires the `exempt:` field.
- New T11 (3 sub-asserts): exempt-marker honored — sandbox file with no tracked heading + exempt marker counted as exempt, not unstamped, exits 0.

## Verification
- Freshness ✅ (87 scanned, 81 stamped, 6 exempt, 0 unstamped, 0 stale)
- Self-test 20/20 ✅
- Lockstep 87/87 / 0 findings ✅
- Tree-health 168/168 strict ✅
- §27-inventory 6/6 ✅

## Bumps
- §98 v2.46.0 → v2.46.1 (patch — convention extension + coverage closure)
- §99 v2.43.0 → v2.43.1
