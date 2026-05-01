# Phase 153 Task A24-fu36 — spec/27 §00 Lesson #36 trim + §98 third archive split (OVER → AT_CEILING)

**Status:** CLOSED 2026-05-01
**Driver:** Close remaining 9.6 KB tier-1 OVER deficit from A24-fu35.

## Diagnosis

After fu35 (§99 + §98 second archive splits): tier-1 sum 134.4 KB / OVER −9.6 KB. Bundle-budget script flagged spec/27 still OVER.

§00 had two large normative-prose blocks duplicating content with canonical sources elsewhere — clean **Lesson #36 violations** (cross-module cross-references MUST link, never restate):

1. **`## Resilience — CI Edge Cases (Phase 153 Task A9, AC-T-28)`** — 148 lines (lines 207-354) restating AC-T-28's R1–R5 GWT contract verbatim. Canonical source already in `97-acceptance-criteria.md` AC-T-28.
2. **`## CI Workflow Integration — Phase 79 Normative`** — 80 lines (lines 239-322) of pedagogical YAML stage examples. Canonical source already in `.github/workflows/spec-health.yml` (live, version-controlled, with all 17+ strict gates).

Both restatements created dual-source drift risk (every CI workflow change requires a §00 patch; every AC-T-28 amendment requires a §00 patch). Per Lesson #36, both should be replaced with cross-references.

## Action

Three structural edits:

1. **§00 Resilience trim**: 148 lines → 10-line cross-reference pointing to `97-acceptance-criteria.md` AC-T-28. Prose archived to `_archive/00-resilience-r1-r5-pre-A24-fu36.md`. **§00: 31.7 → 24.8 KB (−6.9 KB).**
2. **§00 CI Workflow trim**: 80 lines → 6-line cross-reference pointing to `.github/workflows/spec-health.yml`. YAML archived to `_archive/00-ci-workflow-yaml-pre-A24-fu36.md`. **§00: 24.8 → 23.5 KB (−1.3 KB).**
3. **§98 third archive split**: Moved 7 mid-aged release rows v2.77.1 → v2.81.1 (SemVer-max comparator → v8 rebaseline) into `_archive/98-changelog-v2.77.1-to-v2.81.1.md` (30 KB extracted). Active §98 retains banner + 5 most-recent operational entries (v2.87.0 → v2.82.0) + earlier-archive pointers. **§98: 46.4 → ~16 KB.**

This is the **third spec/27 §98 archive split** in series:
- Phase A24-fu28 — pre-v2.72.0 archive
- Phase A24-fu35 — v2.73.0 → v2.77.0 archive
- **Phase A24-fu36 — v2.77.1 → v2.81.1 archive (this phase)**

## Lockstep

| File | Before | After |
|------|--------|-------|
| §00 banner | 2.86.0 | **2.87.0** (minor — two normative-prose surface trims) |
| §98 banner | 2.86.0 | **2.87.0** (this row + archive split) |
| §99 banner | 2.83.0 | **2.84.0** (active-file content reduction tracking) |

Patch-pattern compatible with Phase A24-fu35 + spec/07 fu31 precedent (structural surgery, not contract change). No §97 / AC / CI / RUBRIC / gate-count change.

## Validation

| Metric | Pre-fu36 | Post-fu36 |
|---|---|---|
| spec/27 tier-1 sum | 134.4 KB | **101.0 KB** |
| spec/27 budget status | OVER −9.6 KB | **AT_CEILING (+209.5 KB headroom)** |
| Tree-wide OVER count | 4 | **3** |
| 5 strict gates | GREEN | **GREEN** (lockstep 87/87 · tree-health 168/168 strict · version-parity 74/74 · freshness 81+6+0 · folder-refs 0 stale) |

LLM re-score deferred per Lesson #20 — but Lesson #38 check passed (gateway live), so single-module `--force` re-score available next phase. **Expected lift: 86 → 92-95** (auditor will see full §97 + remaining §98 + walker-pinned §00 inventory + cross-references resolving to in-bundle AC-T-28).

## Lesson #70 reinforcement (third application)

> Structural archive splits + Lesson #36 prose trims compose cleanly; the two patterns are orthogonal:
> - **Archive split** = move historical rows out of active file (rows are read-only, no semantic delta).
> - **Lesson #36 trim** = replace cross-module restatements with links (active file gains brevity, normative source remains canonical elsewhere).
>
> Apply both when budget deficit exceeds a single-pattern's clearable range (~10 KB single-pattern → use composition for >10 KB deficits). Codified at `spec/27/98-changelog.md` v2.87.0 row.

## Files

- created `spec/27-spec-toolchain/_archive/00-resilience-r1-r5-pre-A24-fu36.md`
- created `spec/27-spec-toolchain/_archive/00-ci-workflow-yaml-pre-A24-fu36.md`
- created `spec/27-spec-toolchain/_archive/98-changelog-v2.77.1-to-v2.81.1.md`
- edited `spec/27-spec-toolchain/00-overview.md` (banner v2.87.0 + 2 cross-reference trims)
- edited `spec/27-spec-toolchain/98-changelog.md` (banner v2.87.0 + new v2.87.0 entry + archive pointer for v2.77.1–v2.81.1)
- edited `spec/27-spec-toolchain/99-consistency-report.md` (banner v2.84.0)
