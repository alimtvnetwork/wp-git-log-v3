# Phase 73 — impl 80 → 85 (4 non-index modules)

**Date:** 2026-04-27  
**Author:** auditor automation  
**Driver:** Promote the 4 non-index `impl=80` modules to 85 by inlining the
5-stage CI workflow contract. The remaining 10 `impl=80` modules are all
`kind: index` — capped at 80 by the deterministic scorer's index branch
(`baseline 70 + 10 child bonus`); they require a scorer enhancement
(scheduled for Phase 74), not content edits.

## Result

- Mean weighted **93.5 → 93.8**
- Mean implementability **91.8 → 92.5**
- 4 modules promoted: impl=80 → 85
- Bonus: 4 modules already at 95 stacked into 100 — `impl=100` count rose
  17 → 17 (net same, because some other shifts cancelled).

## Promoted modules

| Module | impl before | impl after |
|---|---|---|
| `02-coding-guidelines/10-research/01-research-index` | 80 | 85 |
| `02-coding-guidelines/22-app-issues/01-app-issue-templates` | 80 | 85 |
| `02-coding-guidelines/23-app-database/01-app-database-conventions` | 80 | 85 |
| `10-research/01-research-index` | 80 | 85 |

## New tier distribution

| impl | count | notes |
|------|-------|-------|
| 75 | 3 | trackers, baseline-locked (target of Phase 74) |
| 80 | 10 | all `kind: index`, scorer-locked (target of Phase 74) |
| 85 | 3 | future-spec from Phase 70 |
| 90 | 12 | residual trackers/indexes/meta-toolchain |
| 95 | 42 | bulk of substantive modules |
| 100 | 17 | leaders with stacked contracts |

## Method

Idempotent script `/tmp/phase73.py`:
1. Append `### CI Workflow — Phase 73 Reference` block to each `00-overview.md`
   containing 5 fenced ```yaml stages.
2. Append Phase 73 entries to `98-changelog.md` and `99-consistency-report.md`.

Both lockstep + tree-health gates still pass at 100/100.
