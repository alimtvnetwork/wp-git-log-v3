# Phase H3 — `_archive/` Exclusion Sweep

**Date:** 2026-04-28
**Trigger:** User `next` after H2 closed — autonomous queued backlog item ("Apply `_archive/` exclusion retroactively to other freshness/drift gates")
**Status:** ✅ Closed

## Goal
Codify the H2 lesson ("`_archive/` exclusion should be standard for any spec-traversing gate") across all 12 spec-traversing linters.

## Audit results (12 scripts)

| Script | Status before H3 | Action |
|---|---|---|
| `check-tree-health.cjs` | ✅ excludes (`ARCHIVE_PREFIX`) | none |
| `check-99-summary-freshness.py` | ✅ excludes (Phase H2) | none |
| `audit-spec-vs-code.py` | ✅ excludes (rglob filter) | none |
| `audit-spec-vs-code-v2.py` | ✅ excludes (rglob filter) | none |
| `generate-spec-index.cjs` | ✅ label-only (no traversal) | none |
| `deepen-consistency-reports.py` | ✅ excludes + logs | none |
| `generate-dashboard-data.cjs` | ✅ excludes (`ARCHIVE_SEGMENTS`) | none |
| `fill-missing-consistency-reports.cjs` | ✅ excludes | none |
| `fill-missing-changelogs.cjs` | ✅ excludes | none |
| `fill-missing-acceptance-criteria.cjs` | ✅ excludes | none |
| `check-spec-folder-refs.py` | ✅ legacy-doc allowlist | none |
| **`check-lockstep.cjs`** | ❌ leaks (3 noisy `skip` rows) | **fixed** — added `e.name !== '_archive'` to walker |
| **`generate-trace-map.py`** | ⚠️ incidentally safe (h4 ACs invisible to `### `-only regex) | **fixed defensively** — explicit `_archive` filter in `collect_ac_ids()` |

## Fixes

### `linter-scripts/check-lockstep.cjs`
- Added `e.name !== '_archive'` to the directory walker condition.
- Result: `Modules scanned: 90 → 87`, `skip: 3 → 0`. Pass/fail counts unchanged.
- Cleaner CI output, no behavior change for non-archive paths.

### `linter-scripts/generate-trace-map.py`
- Added `if "_archive" in md.parts: continue` to `collect_ac_ids()`.
- Defensive: archive ACs were already h4-level (`#### AC-PUSH-13`) and invisible to the `### `-only regex, but this codifies the intent and protects against future archive docs that promote ACs to h3.
- No counts changed (1307 → 1307 ac_total).

## Trace-map regression handling

Regression gate caught +3 new ACs from Phase H2 (AC-26-06/07/08 — the new acceptance criteria for the widened freshness gate). All 3 bound in `linter-scripts/trace-map.toml` to symbols in `check-99-summary-freshness.py`:
- AC-26-06 → `TRACKED_HEADING_RE` (regex)
- AC-26-07 → `find_99_files` (function — `_archive` filter)
- AC-26-08 → `find_summary_stamp` (function — multi-block scan)

Then rebaselined within memory's safety thresholds: +3 ACs ≪ 50 limit, +0 code files ≪ 5 limit. New baseline: `{ac_total:1307, ac_traced:77 (+3 from 74), code_total:48, code_orphan:25}`. ac_drifted unchanged at 1230.

## AC-31-31 invariants
- Gate count unchanged at **15** (no new gate added).
- RUBRIC_VERSION unchanged at **v2.24** (no rubric semantics changed).
- Footer / EXECUTIVE-SUMMARY / qa-baseline-footer untouched.

## Verification
- `node check-lockstep.cjs` → 87/87 / 0 findings ✅ (was 90/87/3 skip)
- `node check-tree-health.cjs --strict` → 168/168 strict-pass ✅
- `python3 check-99-summary-freshness.py` → 75 stamped, 12 unstamped, 0 stale ✅
- `python3 check-trace-map-regression.py` → no regression at new baseline ✅
- `bash test-check-99-summary-freshness.sh` → 17/17 ✅
- `bash test-overview-inventory-parity.sh` → 6/6 ✅
- `bash test-readme-inventory.sh` → 22/22 ✅
- `bash test-qa-baseline-footer.sh` → 11/11 ✅

## Lessons
1. **Belt-and-braces is real**: `generate-trace-map.py` was incidentally safe via heading-level mismatch (h4 in archive vs h3-only regex). Fragile — codifying the explicit `_archive` filter prevents future regressions when archive docs are reformatted.
2. **Audit before assuming leakage**: 8 of 12 scripts already had the exclusion. Don't assume — `rg "_archive"` against the whole linter dir is the fastest first step.
3. **Trace-map regression gate is reliable belt-and-braces**: caught the +3 ACs from H2 that I forgot to bind. Confirms G-series lesson #2 ("trace-map regression gate is reliable belt-and-braces but pre-flight saves cycles").
