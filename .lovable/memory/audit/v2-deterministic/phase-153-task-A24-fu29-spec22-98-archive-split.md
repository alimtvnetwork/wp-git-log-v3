# Phase 153 Task A24-fu29 — spec/22 §98 archive split

**Closed:** 2026-04-30
**Pattern:** A24-fu28 (spec/27 §98 split) — second instance
**Lesson:** #65 (walker bundle budget is dominant cache-score driver)

## Outcome

- spec/22 §98 size: **100 KB → 38 KB** (-62 KB, -62%)
- spec/22 tier1 bundle: **239 KB → 176 KB** (-26%)
- spec/22 status: OVER → CLEAR (under 120 KB walker cap with healthy headroom)
- Expected cache lift on next re-score: **83 → 86+** (D2 AC-coverage improves as auditor can now reach AC-78 walker-pin without changelog saturation)

## Mechanics

- Created `spec/22-git-logs-v2/_archive/98-changelog-pre-v3.9.0.md` (frozen, 51 lines, 65 KB)
- Moved 48 historical rows: v3.8.13 → v2.0.0
- Live §98 retains 17 rows (v3.11.0 → v3.9.0) + new v3.12.0 row + archive pointer block at top
- Banners: §00 + §98 + §99 all v3.11.0 → v3.12.0
- §97 NOT bumped (no AC change)
- New `## v3.12.0 Audit` section in §99 with 4-row file change table

## Gate verification

- lockstep 87/87 ✅
- tree-health 168/168 strict ✅
- version-parity 74/74 ✅

## Lesson reinforcement

- A24-fu28 (spec/27): 248 KB → 45 KB §98 (-82%); tier1 455 → 261 KB
- A24-fu29 (spec/22): 100 KB → 38 KB §98 (-62%); tier1 239 → 176 KB

The pattern is mechanical and reliably halves §98 size on any module where
a single SemVer-band cut is available. Next OVER targets:
- spec/01 (tier1 148 KB) — needs §00 trim + §98 split
- spec/07 (tier1 134 KB) — §97 sub-folder extraction
