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

---

## Retrospective (added in Phase 92)

Outcome map for the "Next phases (queued)" list above:

| # | Original queued task | Actual outcome |
|---|---|---|
| 1 | Phase 82 — evidenced-tracker contract bonus (cap 90) | ✅ **Shipped in Phase 82** but with **cap 95** (stronger than queued; rubric v2.13). |
| 2 | Phase 83 — audit `weighted_overall < 95` modules | ✅ **Shipped in Phase 83**: rubric v2.14 (TODO regex tightening + `todo_audit_exempt`) + AC injection on 30 modules; mean weighted 96.5 → **98.0**. |
| 3 | Phase 84 — cumulative schema-bonus cap | ❌ **REJECTED in Phase 86** after empirical test: mean impl 99.8 → 89.2; 76 multi-contract modules unfairly penalised. Source comment + memo `phase-86-schema-cap-rejected.md` preserve rejected design. **Do not re-propose without new corpus data.** |
| 4 | Phase 85 — `27-spec-toolchain/72-min-thresholds.md` | ❌ **Superseded by Phase 85's chosen approach**: documented the new flags in `spec/27-spec-toolchain/31-audit-spec-vs-code-v2.md` (script's own spec, AC-31-20) instead of creating a new `72-*` slot. Bijection is preserved without expanding the toolchain numbering. |
| 5 | B1 decision | 🚧 Still pending user input. |
| 6 | R1 real-AI re-audit | 🚧 Still blocked on Lovable Cloud. |

Floors quoted at the time (`weighted=95 / impl=98`) were tightened to **97 / 99** in Phase 84 after Phases 82–83 lifted the means. See `phase-84-ci-floor-tighten.md`.
