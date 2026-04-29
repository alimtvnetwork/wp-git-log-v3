# Phase 153 P48-fu — Tracker: 24-ads truncation findings × 4

**Phase:** 153 (post-P48-4 grounding sweep)  
**Status:** ✅ CLOSED (harness false-positive per P47-fu1 Lesson #1)  
**P47-fu1 finding:** 24-ads top_blockers[0..3] (results[5])

## Findings

> [truncation] 00-overview.md content  
> [truncation] 97-acceptance-criteria.md content  
> [truncation] 98-changelog.md content  
> [truncation] 99-consistency-report.md content

## Resolution

These are **digest-cap artefacts**, not real truncations. Mechanical
authority is `linter-scripts/check-truncated-prose.py`, which reports
**OK (872 files clean)** at Phase 153 close — including all four spec/24
files cited.

P47-fu1 ran at `total_cap=14000, per_file_cap=4000`. spec/24 file sizes:
- `00-overview.md`: 657 lines (~30 KB) — vastly exceeds 4 KB per-file cap
- `97-acceptance-criteria.md`: 124 lines
- `98-changelog.md`: 83 lines
- `99-consistency-report.md`: 102 lines

Combined ~50 KB → cannot fit in 14 KB total cap → AI sees truncated
chunks → infers "truncation in source". This is exactly the failure mode
**P47-fu1 Lesson #1** flagged: "below digest size 8000+, AI hallucinates
truncation blockers". The fix (raise caps) doesn't help spec/24 because
the file is genuinely large and the auditor's window is bounded.

## Authoritative source

`check-truncated-prose.py` is the mechanical gate (deterministic regex
+ heuristic for trailing ellipsis / unclosed sentences / cut-off code
fences). Its verdict overrides AI digest inference per Lesson #11
(class-vs-instance: mechanical gate catches the narrow truncation class,
AI catches the broader missing-contract class — they are complementary).

## Action

None. No spec edit, no lockstep ripple. The AI auditor's truncation
category is **structurally unreliable for files >4 KB** under the current
P47-fu1 cap config. If audit-v7+ re-uses this category, it MUST be cross-
checked against `check-truncated-prose.py` before being raised as a finding.


---

**Lessons codified:** #37 → see [`mem://process/phase-153-lessons`](../../../memory/process/phase-153-lessons.md) for the canonical contributor-rule statements.
