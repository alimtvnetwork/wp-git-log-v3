# Phase 152 — Full-tree audit v6 baseline (post-P3-sweep)

**Date:** 2026-04-29
**Trigger:** `next` after Task #22 closure (P3 sweep CLOSED Phase 151). Natural milestone for new audit.

## Headline
audit-v5 (Phase 130) deliberately published no numeric baseline (deferred to R1, blocked on Lovable Cloud). Two years of incremental closure later, v6 publishes a **deterministic-only** numeric baseline derived purely from gate replay — no AI scorer involved. Headline: **P3 (Verifies-coverage) driver CLOSED tree-wide**; AI-confidence match 12/15 (80%); residual drift is 3 modules on P4 workflow-ref, cosmetic.

## Headline metrics

| Metric | v5 | v6 | Delta |
|---|---|---|---|
| Tree health (strict) | 100/100 (162/162) | **100/100 (168/168)** | +6 credits (rubric widening) |
| Lockstep | 0 / 87 modules | **0 / 87** | held |
| §99 stamp coverage | 0 | **81 stamped + 6 exempt + 0 unstamped** | full opt-in adoption |
| AI-confidence eligible/match | not measured | **15/12 (80%)** | new metric |
| P3 (Verifies-coverage) drifters | not measured | **0** ✅ | tree-wide CLOSED |
| P4 (workflow-ref) drifters | not measured | 3 (`07`,`14`,`28`) | residual cosmetic |
| §97 ACs tree-wide | ~1304 | **1373** | +69 |
| Strict CI gates | 14 | **15** | +1 (H1 + F2 net) |
| RUBRIC_VERSION | v2.22 | **v2.24** | +2 minors |

## Action
Wrote `spec/17-consolidated-guidelines/34-full-tree-ai-audit-v6.md` (130+ lines) that:
1. Cites every gate's actual command + output (reproducible)
2. Tabulates v5→v6 deltas with Phase pointers
3. Lists open items with owner + driver + notes
4. Lists deferred work (GAP-V2-05 #1–#8) explicitly out of scope
5. Forward-looking trigger conditions for v7

audit-v5 banner-superseded with link to v6.

## Lockstep
- §17 §00 banner 3.4.2 → **3.4.3** (patch — single-file additive, mirrors v4.4.0/Phase-130 precedent)
- §17 §00 inventory: row 33 updated with "Superseded by v6" tag, row 34 added for v6
- §17 §98 release row **3.4.3** added
- §17 §99 banner 4.6.2 → **4.6.3**, file count 34 → 35, row 35 added
- `33-full-tree-ai-audit-v5.md` line 1: title + supersession blockquote prepended

## Verification
```
node linter-scripts/check-tree-health.cjs --strict   # 100/100 (168/168) ✅
node linter-scripts/check-lockstep.cjs               # 0 findings, 87/87 ✅
python3 linter-scripts/check-99-summary-freshness.py --report-only  # 81 stamped, 6 exempt, 0 unstamped ✅
python3 linter-scripts/check-ai-confidence.py --report  # 12/15 match ✅ (3 P4 drifts only)
```

## Memory impact
- Update `mem://specs/full-tree-audit-v4.md` (the file that has tracked v4→v5 history) to add v6 row + new metrics. Future sessions should reference v6, not v5.
- Core line "audit-v4's 45/100 baseline is **superseded by audit-v5** (Phase 130)" should extend to "**superseded by audit-v6 (Phase 152)** with deterministic 100/100 + 12/15 AI-confidence headline."

## Why a patch and not a minor on §17
audit-v6 is a single-file additive publication that **reports** state — it does not **change** the contract surface (no AC changes, no rubric changes, no new gates, no new CI workflow steps). This matches the precedent set by v4.4.0 (Phase 130 publication of v5).

## Open items inherited from v5 (all deferred)
- **R1** real-AI re-audit — blocked on Lovable Cloud; would deepen ~25 trace-map orphans (run.sh/deprecated-v1/helpers/audit-internals)
- **R2** session-persistence regression monitor — not re-observed in 35 phases (117–151)
- **B1** Git Logs §07 App identity — CLOSED Phase 147 (locked decision 12)
- **GAP-V2-05** (Tasks #1–#8) — explicitly out of scope; each requires own focused phase
