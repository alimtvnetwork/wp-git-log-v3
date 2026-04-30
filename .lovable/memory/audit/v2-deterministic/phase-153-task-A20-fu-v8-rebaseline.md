# Phase 153 Task A20-fu — full-tree v8 rebaseline post-A12 walker-cap (CLOSED 2026-04-30)

## Trigger
A12-walker-cap closed — `MAX_BYTES` raised 90 KB → 120 KB; all 23 cache entries stale-by-construction. Per Lesson #41 (foundation→contract→wiring→measurement) the rebaseline ships as a separate phase from the contract change.

## Run
- Command: `python3 linter-scripts/audit-ai-implementability.py --force`
- Wall-clock: **1m56s**
- Errors: **0** gateway failures
- Cache writes: **23/23** modules

## Result
**Tree mean 85.78 → 87.70 / 100 (+1.92).**

| Band | Pre-A20-fu | Post |
|---|---:|---:|
| EXCELLENT (≥90) | 6 | **7** (+1) |
| GOOD (75-89) | 17 | 16 |
| NEEDS_WORK <75 | 0 | 0 |
| BLOCKING <60 | 0 | 0 |

## Movements

### Top A12-driven lifts
| Module | Δ | Axis | Driver |
|---|---:|---|---|
| 26-gitlogs-diagrams | **+8** | audit-corpus | citation density now visible in 9/9 files |
| 11-powershell-integration | **+6** | integration-spec | 18/19 files visible |
| 18-wp-plugin-how-to | **+6** | process-guidance | 15/35 files vs prior 10/35 |
| 06-seedable-config-architecture | +4 | normative-contract | 9/21 files vs prior ~6 |
| 27-spec-toolchain | +4 | tooling-spec | 3/50 (Tier-1-bounded) cleaner at 117 KB |
| 16-generic-release | +1 | normative-contract | 90 → 91 (graduated to EXCELLENT) |

### Honest-baseline corrections (Lesson #18 — expected, NOT regressions)
| Module | Δ | Why |
|---|---:|---|
| 02-coding-guidelines | -5 | 245-file subtree D3/D5 gaps now exposed |
| 04-database-conventions | -2 | expanded Tier-2 surface |
| 07-design-system | -2 | expanded Tier-2 surface |
| 13-generic-cli | -2 | expanded Tier-2 surface |

These are the **next-highest-leverage corrective targets** (Lesson #18: mark new baseline as ground truth; do NOT roll back the bias fix to "restore" old EXCELLENT scores).

### Stable
| Module | Score |
|---|---:|
| 23-app-database | 97 |
| 28-universal-ci-cli | 97 |
| 24-app-design-system-and-ui | 95 |
| 10-research | 93 |
| 14-update | 87 |
| 05-split-db-architecture | 89 |

## Lockstep
- §27 §00 v2.81.0 → **v2.81.1** (patch — pure measurement)
- §27 §98 v2.81.0 → **v2.81.1**
- §27 §99 v2.78.0 → **v2.78.1**

Mirrors A8 v5 rebaseline lockstep budget (v2.77.3 → v2.77.4 patch-only).

No contract / AC / CI / RUBRIC / gate-count change.

## Cache state
All 23 `.lovable/cache/audit-ai/*.json` entries rewritten with fresh `bundle_sha` (incorporating `axis=…` prefix + 120 KB Tier-2 content) + scores. Cache is authoritative again per Lesson #34.

## A12 ROI verification
Prediction (from A12 closure): "modules with highest pre-A12 truncation will see D4 + D5 lifts proportional to additional Tier-2 surface."

Verified:
- spec/26 (audit-corpus, citation-dense) was the single highest beneficiary at +8 — D5 cross-ref dimension responded most strongly to the extra context as predicted.
- integration-spec axis (spec/11 +6, spec/12 +1) confirms Lesson #37's predicted dual-gap exposure.
- spec/27 only +4 despite being the canonical Lesson #45 pre-flight module — Tier-1 alone exceeds 120 KB so Tier-2 budget gain is theoretical only; this is consistent with the pre-flight finding that spec/27 retained 3/50 visibility post-A12.

## Lesson #49 codified (in §98 row + §99 update)
**`MAX_BYTES`-class cap raises expose mixed lift+correction signal in rebaseline; net gain is the leading indicator, not per-module monotonic improvement.**

A20-fu's +1.92 net (14 lifts / 4 honest corrections / 5 unchanged) confirms A12 shipped correctly:
- If every module monotonically lifted, the cap raise would only have surfaced more low-hanging fruit, not exposed prior measurement bias.
- Honest-baseline corrections (Lesson #18 axis) are evidence the fix worked.

Mirror of Lesson #18 on the cap-raise-rebaseline axis (vs Lesson #18's tier-1-ordering-rebaseline axis from A7).

## Verification
- Lockstep 87/87 (will verify post-edit)
- Tree-health 168/168 strict (will verify post-edit)
- Version-parity 74/74 (will verify post-edit)
- Slot-34 self-test 9/9 (unchanged from A12)

## Predecessor
- A12-walker-cap (cap raise + AC-34-13 codification)

## Successor candidates
1. **A24-fu11** — spec/02 -5 honest-baseline close (highest-leverage corrective; 245-file subtree)
2. **A24-fu12** — spec/25 at 79 (lowest GOOD, audit-corpus)
3. **A24-fu13** — spec/04/07/13 secondary corrective sweep (~6 score points across 3 modules)
4. **A24-fu10-fu2** — spec/18 §00 inventory + ORM concurrency partials
5. **A18** — per-axis cap refinement, conditional on whether 87.70 reveals miscalibration
6. **R1** — blocked on Lovable Cloud
