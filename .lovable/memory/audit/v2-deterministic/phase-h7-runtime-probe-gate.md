# Phase H7 — Runtime Archive-Exclusion Gate

**Date:** 2026-04-28
**Status:** CLOSED
**Predecessor:** Phase H6 (runtime probe verified 3 critical linters; H7 codifies as standing CI gate)
**Successor:** none queued

## What

New self-test `linter-scripts/test/test-archive-exclusion-runtime.sh` (slot 28) codifies the H6 lesson "runtime > source verification" as the **17th strict CI gate**. importlib-loads 3 critical spec-traversing linters and asserts each enumerator returns 0 archive-leaked results.

## Probes (floor ≥ 3)

| Linter | Enumerator | Total | Leaked |
|---|---|---|---|
| `check-99-summary-freshness.py` | `find_99_files()` | 87 | **0** |
| `audit-spec-vs-code-v2.py` | `ALL_MODULES` | 87 | **0** |
| `generate-trace-map.py` | `collect_ac_ids()` | 1315 | **0** |

## AC-31-31 cascade

| Surface | Before | After |
|---|---|---|
| `RUBRIC_VERSION` | `v2.25` | **`v2.26`** |
| QA-baseline footer count | 16 | **17** |
| Footer entries | 16 rows | **17** (added #17 Runtime archive-exclusion gate) |
| `EXECUTIVE-SUMMARY.md` back-ref | 16 | **17** |
| `test-qa-baseline-footer.sh` awk | 16 patterns | **17** (added `/Runtime archive-exclusion gate/`) |
| Workflow quality-gate steps | 16 | **17** |

Parity verified: **17 / 17 / 17** (footer rows / workflow steps / declared count).

## Verification (full gate suite)

- H7 self-test: **10/10 ✅**
- QA-baseline-footer self-test: 11/11 ✅
- README inventory parity: 26/26 ✅ (9→10 entries)
- §27 inventory parity triangle: 6/6 ✅
- Lockstep: 87/87 / 0 findings ✅
- Tree-health strict: 168/168 ✅
- Audit thresholds: mean 98.0 / 99.8 ✅
- Trace-map: ✅ at new baseline `{ac_total:1320, ac_traced:90, code_total:51, code_orphan:26}` (+5 ACs / +1 code, both within budget)
- Freshness: 75 stamped / 0 stale ✅

## Lessons codified

1. **Runtime probes graduate from one-off audits to standing gates** when the underlying convention (here: H3 `_archive` exclusion) is widely used. The H6→H7 path is the template: one-off audit → memo lesson → standing self-test → CI gate.
2. **F3 exception sanctioned**: the file IS a `.sh` self-test but occupies §27 slot 28 as a validator because that IS its functional CI role. The slot-doc explicitly forbids "correcting" this with a thin `.py` wrapper (would be ceremony without value). Recorded in slot-28 doc's "Self-test note" section.
3. **Probe-count floor (≥ 3)** prevents silent coverage regression: a contributor who removes a probe block now fails AC-28-04. New spec-traversing linters MUST add a probe (instructions in slot-28 doc).

## Files touched

- `linter-scripts/test/test-archive-exclusion-runtime.sh` (NEW)
- `linter-scripts/test/test-qa-baseline-footer.sh` (awk +1)
- `linter-scripts/test/README.md` (inventory entry #10, totals)
- `linter-scripts/audit-spec-vs-code-v2.py` (RUBRIC v2.26, footer #17, EXECUTIVE-SUMMARY back-ref)
- `linter-scripts/trace-map.toml` (AC-28-01..05 bindings)
- `.github/workflows/spec-health.yml` (gate step #17)
- `spec/27-spec-toolchain/28-check-archive-exclusion-runtime.md` (NEW slot doc)
- `spec/27-spec-toolchain/00-overview.md` (slot 28 inventory row)
- `spec/27-spec-toolchain/98-changelog.md` (v2.45.0 → v2.46.0)
- `spec/27-spec-toolchain/99-consistency-report.md` (v2.42.0 → v2.43.0)
- `.lovable/memory/audit/trace-map.json` + `trace-map-baseline.json` (rebaseline)
- `.lovable/memory/audit/v2-deterministic/phase-h7-runtime-probe-gate.md` (this memo)
- `.lovable/memory/index.md` (gate count 16→17, RUBRIC v2.25→v2.26, H7 closure block)
