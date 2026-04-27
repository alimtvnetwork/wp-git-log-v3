# Phase 90 — `--explain` Rubric Trace Flag

**Date:** 2026-04-27
**Scope:** `linter-scripts/audit-spec-vs-code-v2.py` (v2.15 → v2.16), `spec/27-spec-toolchain/{31,98,99}.md`
**Status:** ✅ Complete

## Why
Score outliers (a module unexpectedly dropping a grade after a rubric tweak) previously required reading raw-results.json plus tracing rubric branches by hand. Phase 90 adds a `--explain=<substring>` CLI flag that prints the full rubric trace for one module in human-readable form: branch, every fired bonus with its delta + originating rubric version, every active gate (with before/after), per-dimension raw-vs-final scores + Δ + contribution, and key metrics.

## Change
- **Script v2.15 → v2.16**: new `explain_module()` function (~80 lines) above `main()`; `main()` short-circuits to it when `--explain=` is on `sys.argv`. Pure-add: no file writes, no AI calls, no behaviour change to existing modes.
- **§31 v1.8.0 → v1.9.0**: header `Source` line updated to `(script v2.16)`; Category appends `+ --explain debugger`; Usage block adds `--explain` invocation; CLI flags table re-titled and gains `Since` column distinguishing v2.12 vs v2.16; new **AC-31-23** specifies stdout structure + exit codes + multi-match handling + no-side-effects guarantee; rubric changelog table extended through v2.16 (incl. v2.15 row recording the rejected Phase 86 schema-bonus cap).
- **§98 v2.15.0 → v2.16.0**, **§99 v2.12.0 → v2.13.0** (lockstep partners; v2.12.0 blockquote preserved below the new v2.13.0 entry).

## Verification
- `--explain=27-spec-toolchain` → meta-toolchain branch, 4 bonuses (75+10+5+5+5 capped 100), 0 active gates ✓
- `--explain=22-git-logs-v2` → normal-contract branch, 6 contract bonuses + size bonus capped 100 ✓
- `--explain=does-not-exist-XYZ` → exit 1 with stderr hint ✓
- Tree-health (strict) 100/100, lockstep (strict) 0 findings, audit `--min-weighted=97 --min-impl=99` PASS at 98.0/99.8 — no regression
