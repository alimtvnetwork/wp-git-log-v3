# Phase 153 Task R2-followup — AC-34-18 Tier-1B Mechanical Lock Extension

**Date:** 2026-05-06
**Status:** CLOSED (productive, gateway-independent)
**Predecessor:** A8-prep (AC-34-18 bounded tier-1B promotion)
**Trigger:** Gateway oscillated back to HTTP 402 (Lesson #86 reconfirmed, third oscillation this Phase 153 sweep) — fell back to gateway-independent R2-followup queued task

## Outcome

Extended `linter-scripts/test/test-audit-ai-tier1b-promotion.sh` from **6 → 21 assertions** covering all 6 FITS modules + OVERFLOW + zero-T1B sentinel.

### Pre-extension (A8-prep landing)

| Test | Module | Assertions | Path |
|---|---|---:|---|
| T1 | spec/05 | 3 | FITS |
| T2 | spec/02 | 2 | OVERFLOW-fallback |
| T3 | spec/22 | 1 | zero-T1B |
| **Total** | | **6** | **3/8 affected modules locked** |

### Post-extension (this phase)

| Test | Module | Assertions | Path | Nested T1B count |
|---|---|---:|---|---:|
| T1 | spec/05 | 3 | FITS | 8 |
| T2 | spec/02 | 2 | OVERFLOW-fallback | 116 |
| T3 | spec/22 | 1 | zero-T1B | 0 |
| **T4** | **spec/06** | **3** | **FITS (NEW)** | **8** |
| **T5** | **spec/10** | **3** | **FITS (NEW)** | **4** |
| **T6** | **spec/12** | **3** | **FITS (NEW)** | **12** |
| **T7** | **spec/18** | **3** | **FITS (NEW)** | **4** |
| **T8** | **spec/26** | **3** | **FITS (NEW)** | **4** |
| **Total** | | **21** | **8/8 affected modules locked** |

Self-test result: **21 pass, 0 fail**.

## Discovery — natural 12-slot ceiling (codified inside test header)

spec/12 has 12 nested T1B files but only 8 fit in the first-12 bundle entries alongside the 4 root T1 files. This is **correct contract behavior**, not regression:

```
position 1-4:   root T1 (4 files, priority-1 by slot)
position 5-12:  nested T1B (8 of 12, priority-1 by alpha within bucket)
position 13+:   remaining 4 nested T1B (T2, alpha order) + all other files
```

Per AC-34-18 the bounded-promotion gate fires on **byte budget** (≤140 KB), not slot count. spec/12's 12 nested T1B files fit byte-wise but the natural 12-entry visualization window only shows 8. Any future regression that drops root-T1 count below 4 OR breaks nested-T1B promotion ordering will trip the test.

## Mechanical-lock rule applied (mirror of P49 AC-T-13 graduation)

Per L21 parity-AC mechanical-lock rule: a contract-AC citing N affected modules MUST lock across ALL N. Pre-R2-followup AC-34-18 only locked 3/8 sentinels (spec/05, spec/02, spec/22). The other 5 FITS modules shared the same code path but had no test — a future walker edit could have silently regressed any of them without tripping the test.

## Files changed

- `linter-scripts/test/test-audit-ai-tier1b-promotion.sh` — header doc-block updated (T1-T8 enumeration); `fits_test()` helper added (~15 LoC); 5 new test invocations T4-T8.
- `spec/27-spec-toolchain/34-audit-ai-implementability.md` — banner v1.10.0 → v1.10.1 (patch — test extension only).
- `spec/27-spec-toolchain/00-overview.md` — banner v2.91.0 → v2.91.1.
- `spec/27-spec-toolchain/98-changelog.md` — new v2.91.1 row + banner.
- `spec/27-spec-toolchain/99-consistency-report.md` — new v2.87.1 update block + banner.

## Lockstep

Patch-only across §27 surfaces (no AC change, no code change):
- slot 34 §00 v1.10.0 → v1.10.1 (patch — self-test contract extension)
- §27 §00/§98 v2.91.0 → v2.91.1
- §27 §99 v2.87.0 → v2.87.1

**No CI workflow change** — existing `bash linter-scripts/test/test-audit-ai-tier1b-promotion.sh` step automatically exercises 15 additional assertions on next run.
**No RUBRIC bump.** **No AC-31-31 cascade.** **No gate-count change.** **No new AC.**

## Gates expected GREEN

- Lockstep 87/87
- Tree-health 168/168 strict
- Version-parity 74/74
- Freshness 81 stamped + 6 exempt + 0 unstamped
- Folder-refs 0 stale
- AC-34-18 self-test 21/21
- `test-overview-inventory-parity.sh` (no new file under linter-scripts/)

## Lessons reinforced

- **L21 parity-AC mechanical-lock rule** — when a contract-AC cites N affected modules, lock across ALL N (mirror of P49 AC-T-13 graduation).
- **Lesson #86 (gateway oscillation)** — third oscillation this Phase 153 sweep; gateway-independent backlog items (R2-followup-class) are the correct fallback when `--force` 402s.
- **Lesson #20 (defer score on 402)** — A8-cont1 deferred to next gateway-up window without blocking productive work this phase.
