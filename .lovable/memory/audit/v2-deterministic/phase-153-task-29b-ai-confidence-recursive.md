# Phase 153 Task #29b — `check-ai-confidence.py` widened to recursive

**Date:** 2026-04-29
**Status:** CLOSED
**User reply:** `next`

## Problem

Task #31 closed the boilerplate `**Verifies:**` gap on 24 top-level §97 files,
but `check-ai-confidence.py` v1.x walked only `SPEC_ROOT.iterdir()` — it saw
**15 of 56** banner-carrying overviews. Nested sub-modules (depth ≥ 3) were
invisible. The audit-v6 boilerplate blind spot was therefore only "closed at
the top layer" and could not regression-detect at deeper layers.

## Fix

Two coupled patches in `linter-scripts/check-ai-confidence.py`:

1. **`list_modules()`** — `iterdir()` → `rglob("00-overview.md")`.
   Filters `_archive` and `_`-prefix parents.
2. **`gate_p4()`** — leaf-name substring check widened to also accept
   `spec/**` glob in `spec-health.yml` OR module spec-relative path.
   Without this, every nested module would have flipped from
   `derived=Production-Ready` to `derived=High` purely as a workflow-detection
   artifact.
3. **Output** — `module` field now slash-joined relative to `spec/` so nested
   modules are uniquely addressable. Wrapped in try/except `ValueError` so the
   self-test's tmp-dir fixtures still work.

## Result

| Metric | Before | After |
|---|---|---|
| `scanned` | 23 (top-level dirs) | 87 (recursive overviews) |
| `eligible` (banner present) | 15 | 51 |
| `matches` | 15 | 24 |
| `mismatches` | 0 | **27** |
| `stamped` / `stamped_failed` | 0 / 0 | 0 / 0 |
| Default exit | 0 | 0 (advisory) |

The 27 newly-surfaced drifts split:

- **~15 P3 `**Verifies:**` gaps** under nested sub-modules (Task #31 blind
  spot exactly as predicted).
- **~10 P1 inventory gaps** in nested overviews.
- **~5 declared=High / derived=Production-Ready** *underclaim* drifts —
  banners stale in the conservative direction. Pure good news.

## Spec lockstep

- `spec/27-spec-toolchain/33-check-ai-confidence.md`: v1.1.0 → **v1.2.0**
  (AC-33-08 recursive walker; AC-33-09 workflow-glob coverage; P4
  detection-rule prose broadened).
- `spec/27-spec-toolchain/00-overview.md`: v2.72.0 → **v2.73.0**
- `spec/27-spec-toolchain/98-changelog.md`: v2.72.0 → **v2.73.0** (row added)
- `spec/27-spec-toolchain/99-consistency-report.md`: v2.69.0 → **v2.70.0**
  (update note added)

## Validation

- `bash linter-scripts/test/test-check-ai-confidence.sh` → 5/5 PASS
- `python3 linter-scripts/check-ai-confidence.py` → exit 0 (advisory)
- `node linter-scripts/check-lockstep.cjs` → 87/87 · 0 findings
- `node linter-scripts/check-tree-health.cjs --strict` → 168/168
- `bash linter-scripts/test/test-overview-inventory-parity.sh` → 6/6

**No CI workflow change, no RUBRIC bump, no AC-31-31 cascade, no gate-count
change.** The 27 new findings are advisory-only; CI stays GREEN. Per-file
opt-in stamps (`<!-- ai-confidence-verified-phase: NNN -->`) promote to
strict one module at a time.

## Lesson codified

Walkers that gate on a single directory level silently mask 70%+ of the
eligible population in a recursive spec tree. New `<module>/00-overview.md`-
targeting linters MUST default to `rglob` with explicit opt-out filters
(`_archive`, `_`-prefix). Pair every walker-widening change with a
coupled-rule audit (here: P4's leaf-name substring test) to avoid converting
one blind spot into a different false-positive class.
