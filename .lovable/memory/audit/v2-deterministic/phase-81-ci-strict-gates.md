# Phase 81 — wire strict gates into CI

**Date:** 2026-04-27
**Trigger:** `next` (Phase 81 from Phase 80 roadmap)
**Status:** ✅ Complete

## Outcome

Wired three quality gates into `.github/workflows/spec-health.yml` that have
held green across Phases 77-80, locking in the current quality bar:

1. **Tree-health (strict)** — `check-tree-health.cjs --strict` (was `--min=100`).
   Strict mode requires every module at full marks (no partial credit).
2. **Lockstep (strict)** — `check-lockstep.cjs --strict`. Newly added; enforces
   §98/§99 date alignment per module.
3. **AI-implementability audit v2 (deterministic, threshold-gated)** —
   `audit-spec-vs-code-v2.py --min-weighted=95 --min-impl=98`. Newly added;
   floors set ~1 point below current means (96.3 / 99.5) to absorb noise
   while catching genuine regressions.

## Rubric / tooling change (v2.12)

`linter-scripts/audit-spec-vs-code-v2.py` now accepts `--min-weighted=N`
and `--min-impl=N` CLI flags. When the computed mean falls below either
threshold the script exits 1, suitable for CI gating. Both flags are
optional; absent flags preserve legacy behaviour (always exit 0 on success).

## Workflow changes

`.github/workflows/spec-health.yml`:
- Added `actions/setup-python@v5` (Python 3.11) for the audit step.
- Broadened `paths:` filter to `linter-scripts/**` (was 4 specific files).
- Added the 3 new gates between cross-link and trace-map gates.
- Expanded Summary block to include lockstep + audit output.
- Step count: 8 → 12.

## Local verification

```text
✓ tree-health: 100/100 (strict — all 56 modules)
✓ lockstep:    87 modules, 0 findings (strict)
✓ audit:       weighted 96.3 ≥ 95, impl 99.5 ≥ 98 — PASS
```

## Files touched

- `linter-scripts/audit-spec-vs-code-v2.py` — added `--min-weighted` /
  `--min-impl` CLI flags + non-zero exit gating.
- `.github/workflows/spec-health.yml` — added 3 gates, Python setup,
  broadened path filters, expanded summary.

## Next phases (queued)

1. **Phase 82** — Investigate the 3 `impl=85` trackers — possibly add an
   evidenced-tracker contract bonus (lift cap to 90).
2. **Phase 83** — Audit `weighted_overall < 95` modules (testability /
   completeness levers, not implementability).
3. **Phase 84** — Cumulative schema-bonus cap (cosmetic anti-double-count).
4. **Phase 85** — Add `27-spec-toolchain/72-min-thresholds.md` documenting
   the new audit flags as part of the toolchain bijection.
5. **B1** — `spec/22-git-logs-v2/07-app-entity.md` decision (user input).
6. **R1** — Real-AI re-audit (Lovable Cloud required).
