# Phase 153 Task A20-fu4 — Full-Tree v9 Rebaseline

**Closed:** 2026-05-01  
**Trigger:** OVER-class sweep (fu28-fu31) closed; gateway live (Lesson #38).  
**Command:** `python3 linter-scripts/audit-ai-implementability.py --force --report /mnt/documents/spec-ai-implementability-audit-v9.md`

## Headline

**Tree mean: 88.04 → 90.52 / 100 (+2.48)** — first crossing into EXCELLENT band (≥90) since the v3 baseline (81.6) on 2026-04-29.

| Band | v8 | v9 | Δ |
|---|---:|---:|---:|
| EXCELLENT (≥90) | 9 | **15** | **+6** |
| GOOD (75-89) | 14 | 8 | -6 |
| NEEDS_WORK (60-74) | 0 | 0 | 0 |
| BLOCKING (<60) | 0 | 0 | 0 |

## Full v9 scoreboard

| Module | v8 | v9 | Δ | Band v9 |
|---|---:|---:|---:|---|
| 17-consolidated-guidelines | 80 | **94** | **+14** | EXCELLENT ⬆ |
| 04-database-conventions | 81 | **91** | **+10** | EXCELLENT ⬆ |
| 27-spec-toolchain | 83 | **93** | **+10** | EXCELLENT ⬆ |
| 07-design-system | 80 | 89 | +9 | GOOD |
| 22-git-logs-v2 | 83 | **90** | +7 | EXCELLENT ⬆ |
| 13-generic-cli | 88 | **92** | +4 | EXCELLENT ⬆ |
| 14-update | 87 | **90** | +3 | EXCELLENT ⬆ |
| 01-spec-authoring-guide | 83 | 85 | +2 | GOOD |
| 03-error-manage | 84 | 82 | -2 | GOOD |
| 02 / 05 / 06 / 10 / 11 / 12 / 15 / 16 / 18 / 23 / 24 / 25 / 26 / 28 | unchanged | unchanged | 0 | (mix) |

## OVER-class sweep validation

All 4 OVER modules from fu27 audit closed at LLM scoring level:

| Phase | Module | v8 | v9 | Δ |
|---|---|---:|---:|---:|
| fu28 | spec/27 | 83 | 93 | +10 |
| fu29 | spec/22 | 83 | 90 | +7 |
| fu30 | spec/01 | 83 | 85 | +2 (predicted "modest") |
| fu31 | spec/07 | 80 | 89 | +9 |

**Cumulative: +28 score points across 4 modules in 4 phases.** Lesson #65 (structural surgery > pure-promotion) fully validated empirically.

## Walker tier-1 fix validation (A6 / Lesson #16)

spec/17 +14 is the largest single-module delta of the entire A-series. With 39 leaf files, the alphabetical-then-tier-1 walker now reaches §97 reliably across all of them.

## Lockstep

- spec/27 §00 v2.83.0 → **v2.84.0**
- spec/27 §98 v2.83.0 → **v2.84.0** (new top row)
- spec/27 §99 v2.80.0 → **v2.81.0** (new top blockquote summary)
- §97 untouched

All 5 strict gates GREEN: lockstep 87/87 · tree-health 168/168 strict · version-parity 74/74 · §99 freshness 81+6+0.

## NEW Lesson #67 codified (§98 v2.84.0 row)

When a coordinated multi-phase sweep targets the same audit-physics root cause (here: walker bundle saturation across fu28→fu31), run a single full-tree `--force` rebaseline AFTER the sweep closes — not per-phase — to:
- (a) capture cumulative lift in one cache snapshot
- (b) avoid HTTP 402 budget churn from N partial rebaselines
- (c) surface band-threshold crossings at tree-mean granularity (here: 88→90+, the GOOD/EXCELLENT boundary)

Mirror of Lesson #38 (gateway-availability-check) at the **batch-vs-iterative axis**: gateway is cheap-per-module but expensive-per-rebaseline; batch the rebaseline to the natural sweep boundary.

## Reports

- `/mnt/documents/spec-ai-implementability-audit-v9.md` — full v9 markdown report
- `.lovable/cache/audit-ai/*.json` — fresh per-module cache (all 23 entries refreshed)
