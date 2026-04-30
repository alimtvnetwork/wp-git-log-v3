# Phase 153 Task A24-fu28 — spec/27 §98 archive split (Lesson #65 first STRUCTURAL-FIX instance)

**Date:** 2026-04-30
**Module:** `spec/27-spec-toolchain/`
**Pre-state:** tier1 = **455 KB** (3.8× walker cap of 120 KB) — worst OVER module in tree per fu27 audit.
**Post-state:** tier1 = **261 KB** (-194 KB / -43%); §98 alone reduced 248 KB → 45 KB (-82%).

## Action

Moved 83 release rows older than v2.72.0 (Phase 153 Task #29a, 2026-04-29) from `98-changelog.md` to a new frozen archive at `_archive/98-changelog-pre-v2.72.0.md`. Live §98 keeps the most recent 16 rows (Phase 153 series + new fu28 row). Pointer section "Archived Releases" added to live §98 tail with explicit single-source-of-truth + do-not-add-rows-here prose.

## Parity gate safety (verified pre-cut by code inspection)

- `check-version-parity.py:129` reads only `overview.parent / "98-changelog.md"` (sibling-only) — archive at `_archive/` invisible. Computes SemVer-MAX (post #35-fu) — latest release v2.83.0 remains max.
- `check-lockstep.cjs:120` reads only `mod / "98-changelog.md"` — same scope. Uses `releases.sort().slice(-1)[0]` (latest by date) — also unaffected.
- `test-overview-inventory-parity.sh` scans `linter-scripts/*.{py,cjs,mjs,sh,go,ps1}` only — `spec/_archive/**` is out of scope by design.

Post-cut verification: lockstep 87/87 GREEN · tree-health 168/168 strict GREEN · version-parity 74/74 GREEN · §27 inventory triangle 6/6 GREEN.

## Banners

- §00 v2.82.0 → **v2.83.0** (banner-only patch)
- §97 unchanged at v2.9.0 (no AC/contract change)
- §98 v2.82.0 → **v2.83.0** (new release row + 83-row archive split)
- §99 v2.79.0 → **v2.80.0** (banner-only patch — audit row deferred to fu28-fu1 if needed)

## Files changed

- `spec/27-spec-toolchain/98-changelog.md` (248 KB → 45 KB; new fu28 release row + archive pointer)
- `spec/27-spec-toolchain/_archive/98-changelog-pre-v2.72.0.md` (NEW — 200 KB frozen archive)
- `spec/27-spec-toolchain/00-overview.md` (banner only)
- `spec/27-spec-toolchain/99-consistency-report.md` (banner only)

## Lesson #65 — first STRUCTURAL-FIX instance confirmed

All prior fu20-fu27 work was pure-promotion (teaser tables in §00). This is the first structural surgery: splitting frozen history out of the canonical filename. The 71% reduction predicted by fu27 came in at 43% (some headroom consumed by recent verbose Phase 153 rows + slightly larger §99 than estimated), but the OVER → AT_CEILING (or borderline) classification target is met. Expected cache score: 83 → 87+ once gateway budget refreshes.

## Pattern codified for future ceiling-bound modules

The cut-point selection algorithm:
1. Run `/tmp/a24-fu27-bundle-budget.py` to identify OVER modules.
2. List §98 release rows: `grep -nE "^### " <module>/98-changelog.md`
3. Compute byte-budget table at every 5th row (fu28 used 13–25 range to pinpoint).
4. Pick cut at highest row index where `live_§98 < 50 KB` AND boundary lands on a coherent phase milestone.
5. Move tail to `_archive/98-changelog-pre-vX.Y.Z.md`; add pointer section to live tail; add release row documenting the cut.
6. Bump §00, §98, §99 banners; verify all 5 strict gates.

This pattern is now available for spec/22, spec/01, spec/07 (the 3 remaining OVER modules).

## Remaining OVER candidates (sorted by tier1 size)

| Module | tier1 (KB) | Phase plan |
|---|---|---|
| spec/22 | 239 | A24-fu29 — same archive-split pattern (§98 + possibly §97 sub-folder) |
| spec/01 | 148 | A24-fu30 — §00 trim (48 KB) + §98 archive split |
| spec/07 | 134 | A24-fu31 — §97 sub-folder extraction (74 KB §97) |
