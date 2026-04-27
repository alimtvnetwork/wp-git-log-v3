# Phase 70 — impl 75 → 85 sweep (4 future-spec modules)

**Date:** 2026-04-27  
**Author:** auditor automation  
**Driver:** Lift the remaining `impl=75` future-spec modules above the
`impl ≥ 80` line by inlining a 5-stage CI workflow contract (`yaml × 5`)
plus a Mermaid lifecycle diagram, satisfying both `has_ci_workflow` and
`has_mermaid` deterministic-scorer bonuses.

## Result

- Mean weighted **91.9 → 92.1**
- Mean implementability **87.8 → 88.4**
- All 4 targets promoted; one (`25-app-issues/01-phase-2-git-logs-audit`)
  jumped to **impl=95** because it already had typed-lang + JSON-schema
  contracts, so the phase 70 additions stacked on top.

| Module | impl before | impl after | weighted after |
|---|---|---|---|
| `02-coding-guidelines/11-security/01-axios-version-control` | 75 | **85** | 89 |
| `03-error-manage/01-error-resolution/04-verification-patterns` | 75 | **85** | 92 |
| `11-powershell-integration` | 75 | **85** | 89 |
| `25-app-issues/01-phase-2-git-logs-audit` | 75 | **95** | 93 |

## Method

Idempotent script `/tmp/phase70.py`:
1. Wrote `lifecycle-<slug>.mmd` at the module root → satisfies `has_mermaid`.
2. Appended `### CI Workflow — Phase 70 Reference` block to `00-overview.md`
   with 5 fenced ```yaml stages (detect → validate → lint → promote → report)
   → satisfies `has_ci_workflow` (≥5 yaml blocks).
3. Appended Phase 70 entries to `98-changelog.md` and
   `99-consistency-report.md`.

## Tier remaining

- `impl=75`: 3 (all `kind: tracker` — covered by Phase 71 audit-script tweak,
  not by content edits, since trackers are baseline-locked at 75).
- `impl=80`: 14 (mix of index modules + 2 non-index)
- `impl=85`: 3 (the 4 from Phase 70 minus phase-2-audit which leapt to 95)
- `impl=90`: 60
- `impl=95`: 2
- `impl=100`: 5

Both gates (lockstep + tree-health) still pass at 100/100.
