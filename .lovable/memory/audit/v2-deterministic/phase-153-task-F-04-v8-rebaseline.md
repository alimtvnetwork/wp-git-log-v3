# Phase 153 Task F-04 — v8 full-tree re-baseline

**Status:** CLOSED 2026-05-03
**Trigger:** F-03c-fu1 confirmed 5+ single-module lifts since v7; gateway live (`LOVABLE_API_KEY` set); Lesson #18 honest-baseline rule applied.

## Result

- **Tree average: 91.5/100** (v7 ~91.1 → +0.4)
- **Bands: 15 EXCELLENT · 8 GOOD · 0 NEEDS_WORK · 0 BLOCKING**
- **Modules audited: 23**

## Per-module scores (v8)

| Module | Score | Band | Axis | Files |
|---|---|---|---|---|
| 01-spec-authoring-guide | 89 | GOOD | process-guidance | 4/18 |
| 02-coding-guidelines | 92 | EXCELLENT | normative-contract | 9/251 |
| 03-error-manage | 91 | EXCELLENT | normative-contract | 17/166 |
| 04-database-conventions | 89 | GOOD | normative-contract | 9/11 |
| 05-split-db-architecture | 89 | GOOD | normative-contract | 9/20 |
| 06-seedable-config | 95 | EXCELLENT | normative-contract | 9/21 |
| 07-design-system | 95 | EXCELLENT (cap) | process-guidance | 7/18 |
| 10-research | 93 | EXCELLENT | audit-corpus | 8/8 |
| 11-powershell-integration | 95 | EXCELLENT (cap) | integration-spec | 18/19 |
| 12-cicd-pipeline-workflows | 83 | GOOD | integration-spec | 16/49 |
| 13-generic-cli | 93 | EXCELLENT | normative-contract | 18/24 |
| 14-update | 91 | EXCELLENT | normative-contract | 13/54 |
| 15-distribution-and-runner | 93 | EXCELLENT | normative-contract | 8/8 |
| 16-generic-release | 98 | EXCELLENT | normative-contract | 13/13 |
| 17-consolidated-guidelines | 88 | GOOD | process-guidance | 6/39 |
| 18-wp-plugin-how-to | 85 | GOOD | process-guidance | 16/35 |
| 22-git-logs-v2 | 87 | GOOD | normative-contract | 5/38 |
| 23-app-database | 97 | EXCELLENT | normative-contract | 4/4 |
| 24-app-design-system | 95 | EXCELLENT | normative-contract | 4/4 |
| 25-app-issues | 93 | EXCELLENT | audit-corpus | 11/12 |
| 26-gitlogs-diagrams | 94 | EXCELLENT | normative-contract | 9/9 |
| 27-spec-toolchain | 83 | GOOD | tooling-spec | 15/57 |
| 28-universal-ci-cli | 97 | EXCELLENT | normative-contract | 15/15 |

## Honest-baseline observations

- Top performers: 16 (98), 23/28 (97), 06/07/11/24 (95).
- Lowest GOOD: 12 + 27 (83), 18 (85), 22 (87) — saturated at 136 KB walker cap (Lesson #73). Further lifts blocked until walker-cap raise lands or content is restructured.
- spec/12 + spec/27 stuck at 83 across 3 phases — confirms saturation as the active ceiling, not contract gaps.
- Zero NEEDS_WORK / BLOCKING — first v-baseline with empty bottom bands.

## Next-leverage targets

1. **spec/12** + **spec/27** at 83 → only progress lever is content restructuring (extract long examples to subfolders; tier-1 walker now sees `{00,97,98,99}` first, but bundle still truncates examples).
2. **spec/22** at 87 → process-guidance promotion candidate (axis swap?) or content trim.
3. **spec/01/04/05/17** at 88-89 → near-EXCELLENT, single normative AC away.

## Lockstep & gates

- §97/§00/§98/§99 untouched (report-only re-score; cache + report regenerated).
- All 5 strict gates GREEN: lockstep 87/87 · tree-health 168/168 strict · version-parity 74/74 · freshness 81+6+0 · folder-refs 0 stale.

## Cross-references

- Supersedes: v7 baseline (Phase 153 Task A7).
- Codifies: Lesson #18 (honest-baseline) re-application.
- Cited by: index Memories list + audit-ai-implementability-latest.md.
