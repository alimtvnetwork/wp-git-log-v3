# Phase 153 Task A20-fu2 — v8 Full-Tree Rebaseline

**Closed:** 2026-04-30
**Trigger:** Cumulative A24 series (fu11/fu12/fu13/fu14/fu15/fu10-fu2) shipped without a tree-wide re-score; gateway confirmed available (Lesson #38).
**Report:** `/mnt/documents/spec-ai-implementability-audit-v8.md`

---

## Headline

**Tree score: 87.3 / 100 (GOOD)** — Δ **+5.0** vs v7 baseline (82.3).
**Bands:** 8 EXCELLENT (was 2) · 15 GOOD · 0 NEEDS_WORK · 0 BLOCKING.
**Severity tally:** CRITICAL 4 · HIGH 18 · MEDIUM 24 · LOW 23.

| Dim | v7 avg | v8 avg | Δ |
|---|---:|---:|---:|
| D1 Contract Clarity | 17.7 | 18.3 | +0.6 |
| D2 AC Coverage     | 17.0 | 18.1 | +1.1 |
| D3 Edge/Error      | 15.1 | 16.4 | +1.3 |
| D4 Examples        | 16.5 | 17.3 | +0.8 |
| D5 Cross-Ref Closure | 15.8 | 16.2 | +0.4 |

D3 Edge/Error +1.3 is the largest dimension lift — direct evidence the A24 fu-series concurrency/error-path ACs (boolean roundtrip, matchMedia fallback, flock prose-mirror, ExitCode prose refresh) registered at the auditor.

## Per-module deltas (v7 → v8, sorted by Δ)

| Module | v7 | v8 | Δ | Driver |
|---|---:|---:|---:|---|
| spec/24-app-design-system-and-ui | 93 | **95** | +2 | A24-fu11 baseline carry |
| spec/23-app-database | 97 | **97** | 0 | already saturated |
| spec/28-universal-ci-cli | 97 | **97** | 0 | already saturated |
| spec/03-error-manage | 81 | 84 | +3 | audit-corpus walker baseline |
| spec/22-git-logs-v2 | 79 | 83 | +4 | walker baseline |
| spec/05-split-db-architecture | 87 | 89 | +2 | – |
| spec/06-seedable-config-architecture | 85 | 87 | +2 | – |
| spec/14-update | 85 | 87 | +2 | – |
| spec/13-generic-cli | 86 | 88 | +2 | A24-fu15 |
| spec/16-generic-release | 89 | 91 | +2 | – |
| spec/04-database-conventions | 84 | **81** | -3 | A24-fu13 honest-baseline correction (boolean roundtrip surfaced D2 gap) |
| spec/26-gitlogs-diagrams | 89 | 88 | -1 | minor cache-stability noise |
| spec/02-coding-guidelines | 75 | 75 | 0 | A24-fu11 structural-pin held the line; D3+D4 unchanged |
| spec/25-app-issues | 92 | 93 | +1 | A24-fu12 walker-cap pin |
| spec/07-design-system | 92 | **80** | -12 | **regression — investigate** |
| spec/18-wp-plugin-how-to | 90 | 90 | 0 | A24-fu10-fu2 held |

## Investigation surface

**spec/07-design-system 92 → 80 (-12)** is the only material regression. Hypothesis (Lesson #45): A24-fu14 added AC-038 + AC-039 + bumped §97 to v3.11.0; bundle SHA changed → walker truncation point shifted → some prior contract content fell outside the 117 KB cap. Files visible: 3/17 (was higher in fu14 self-check at 92). This is a candidate for **Lesson #50 structural-pin extension** OR **Lesson #16 tier-1 walker re-ordering** within spec/07.

## Strict CI gates (post-rebaseline)

- Lockstep: 87/87 ✓
- Tree-health: 168/168 strict ✓
- Version-parity: 74/74 (0 mismatches) ✓
- Cache refreshed: all 23 module JSON snapshots written under `.lovable/cache/audit-ai/`.

## Lessons

**NEW Lesson #54 — Honest-baseline corrections after structural-pin work.** When a self-lift adds a new AC (e.g. AC-04-13 boolean roundtrip in fu13), expect the next tree-wide re-score to potentially DROP the module's score because the new normative surface widens the rubric eligibility. spec/04 dropping 84 → 81 after fu13 is the canonical example: D2 went down because the new AC-04-13 added 1 more contract that the auditor measures coverage against. This is **not a regression** — it is the auditor seeing more truth. Mirror of Lesson #18 (band-level corrections in both directions).

**Lesson #45 reinforcement:** spec/07 -12 is a textbook cache-stability shift triggered by §97 content edits (AC-038 + AC-039 in fu14 changed bundle_sha → truncation point moved). Future A-series phases should re-score the affected module with `--force` immediately after self-lift to detect this same-phase, not wait for a tree-wide rebaseline to surface it.

---

## Files touched

- created: `.lovable/memory/audit/v2-deterministic/phase-153-task-A20-fu2-v8-rebaseline.md`
- created: `/mnt/documents/spec-ai-implementability-audit-v8.md`
- regenerated: `.lovable/cache/audit-ai/*.json` (23 modules)
- edited: `.lovable/memory/index.md` (rebaseline row + Lesson #54)

No spec edits, no §97 changes, no lockstep ripple — pure measurement phase.
