# Phase 84 — CI Floor Tighten (Lock In Phase 82–83 Wins)

**Date:** 2026-04-27
**Scope:** `.github/workflows/spec-health.yml` audit gate thresholds
**Status:** ✅ Complete

## Why
Phase 81 set CI floors at `--min-weighted=95 --min-impl=98` based on Phase 80
results (mean weighted 96.3, mean impl 99.5). After:
- Phase 82 — tracker ceiling lift (impl 85→95 on 3 modules, mean impl → 99.8)
- Phase 83 — rubric v2.14 + AC injection on 30 modules (mean weighted → 98.0)

…the old floors were lenient enough to allow ~3 points of silent backslide on
weighted score before failing CI. Phase 84 retightens to ~1 pt under current means.

## Change
| Threshold        | Before | After | Current mean | Headroom |
|------------------|-------:|------:|-------------:|---------:|
| `--min-weighted` |     95 |    97 |         98.0 |    1.0   |
| `--min-impl`     |     98 |    99 |         99.8 |    0.8   |

Single edit: `.github/workflows/spec-health.yml` lines 72–79 (audit gate step).

## Verification
- `audit-spec-vs-code-v2.py --min-weighted=97 --min-impl=99` → `✓ PASS: thresholds met`
- Workflow YAML parses clean (js-yaml)
- All previous gates (tree-health --strict, lockstep --strict, trace-map regression) untouched

## Effect
Any future PR that drops mean weighted below 97 or mean impl below 99 will fail CI,
preventing silent regression of the Phase 82–83 quality wins.
