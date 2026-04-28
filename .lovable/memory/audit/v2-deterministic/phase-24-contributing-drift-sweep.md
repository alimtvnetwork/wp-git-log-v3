# Phase 24 — CONTRIBUTING.md Drift Sweep (No-Questions Mode 8/40)

**Date:** 2026-04-28
**Trigger:** Phase 23 close-out rec (a) — graduate or close out the
"narrative-claims advisory" H10 candidate by hitting a third sweep target.

## Targets scanned

1. `README.md` — **N/A**, 3-line Lovable placeholder ("TODO: Document your
   project here"). Out of spec scope.
2. `CONTRIBUTING.md` — primary target.
3. `readme.txt` — 2-line file, no narrative.

## Findings (CONTRIBUTING.md, 126 lines)

| Line | Stale claim | Reality | Severity |
|------|-------------|---------|----------|
| 9 | "current as of Phase 86" | Phase 147 canonical / v2-det Phase 23 | medium |
| 52 | "v1.8.0, covering script v2.15" | v1.23.0, script v2.17, RUBRIC v2.26 | **high** |
| 117 | "phase-81 through phase-86 for examples" | range OK for rubric-tuning era; missed recent 18-23 stale-prose phases | low |

**Verified non-drift** (initial false-positives):
- L5/L23 "the four CI gates" — intentional scoping. Doc covers the 4
  contributor-facing quality-bar gates from the L11-17 table; workflow
  has 15 total but most are non-contributor concerns. Not drift.
- L102 "87-module corpus" — confirmed by `audit-spec-vs-code-v2.py`
  output (`[87/87]`). The "56 modules" figure in core memory refers to
  tree-health module count (different definition). Not drift.
- L13 "100/100 across all 56+ modules" — current state, accurate.

## Fixes applied

- L9 banner phase reference updated.
- L52 version triple corrected (3 fields, all stale by ~15 patch releases
  + 1 minor on rubric).
- L117 phase-range note expanded to include recent v2-det 18-23.

## Decision: H10 candidate **GRADUATED to 3/3**

Phase 20 (README counts) + Phase 21 (§00-overview banner) + Phase 24
(CONTRIBUTING version refs) = 3 distinct narrative-claims-drift instances
across 4 sweep phases (Phase 22 fleet sweep was 0/23 for the §00 banner
sub-class). Pattern is real but **diffuse**:

- Each instance lives in a different document class (test README, spec
  overview, contributor doc).
- No single mechanical detector covers all three (the version-triple in
  CONTRIBUTING is freeform prose, not a machine-checkable header).

**Recommendation: do NOT promote to a CI gate.** Instead, codify a
**lightweight memory rule**: when bumping `audit-spec-vs-code-v2.py`
script version, RUBRIC_VERSION, or §31 spec version, grep `CONTRIBUTING.md`
for the old triple. This is cheaper than a custom advisory and covers
the highest-severity drift (L52 was the only "high" finding).

## Files touched

- `CONTRIBUTING.md` — 3 line replacements (L9, L52, L117).

No spec lockstep cascade (CONTRIBUTING is not §spec scope).

## Lesson

The "narrative-claims advisory" H10 ladder (1/3 → 2/3 → 3/3) ended up
**rejected** anyway because the three instances were too heterogeneous
to share one detector. Lesson: H10 graduation requires not just N
instances but a **shared mechanical signature**. Future candidates
should pre-test "would one regex catch all 3?" before counting toward 3/3.
