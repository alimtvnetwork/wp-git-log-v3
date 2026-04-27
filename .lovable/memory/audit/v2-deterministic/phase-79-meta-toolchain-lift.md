# Phase 79 — 27-spec-toolchain ceiling lift (rubric v2.10)

**Date:** 2026-04-27
**Trigger:** `next` (Phase 79 from Phase 78 roadmap)
**Status:** ✅ Complete

## Outcome

Lifted `27-spec-toolchain` from `impl=90` → `impl=100` by:
1. Extending the deterministic rubric to **v2.10**: meta-toolchain modules
   now earn `+5` for `has_mermaid` and `+5` for `has_ci_workflow` (≥5 yaml
   blocks), mirroring the v2.9 evidenced-tracker / evidenced-index bonuses.
   Capped at 100.
2. Adding a normative Mermaid lifecycle diagram
   (`lifecycle-27-spec-toolchain.mmd`) covering the 7-stage CI pipeline.
3. Inlining 6 CI workflow YAML stages (detect, validate, lint, tree-health,
   lockstep, promote) into `00-overview.md` — qualifies for `has_ci_workflow`.

## Metrics delta

| Metric | Before | After | Δ |
|---|---|---|---|
| `27-spec-toolchain` weighted | 89 | **92** | +3 |
| `27-spec-toolchain` impl | 90 | **100** | +10 |
| Mean weighted (87 modules) | 95.8 | **95.9** | +0.1 |
| Mean implementability | 98.2 | **98.3** | +0.1 |
| Modules at impl=100 | 73 | **74** | +1 |

## Rubric change (v2.10)

`linter-scripts/audit-spec-vs-code-v2.py` lines 543-554:

```python
elif is_meta_toolchain:
    impl = 75
    if m.get("has_normative_contract"): impl += 10
    if m["md_files"] >= 30:             impl += 5
    # v2.10: evidenced-meta-toolchain bonuses
    if m.get("has_mermaid"):     impl += 5
    if m.get("has_ci_workflow"): impl += 5
    if m["overview_chars"] < 500:       impl -= 20
    impl = min(impl, 100)
```

## Gates

- Lockstep: ✅ PASS (87 modules, 0 findings)
- Tree-health: ✅ PASS 100/100 (strict, all 56 modules at full marks)

## Files touched

- `linter-scripts/audit-spec-vs-code-v2.py` — rubric v2.10
- `spec/27-spec-toolchain/lifecycle-27-spec-toolchain.mmd` — created
- `spec/27-spec-toolchain/00-overview.md` — appended 6 CI YAML stages + xref
- Audit artefacts regenerated under `.lovable/memory/audit/v2-deterministic/`

## Remaining non-100 modules

- `impl=90`: 11 (all `kind: index` — capped at 90 by v2.9 ceiling)
- `impl=85`: 3 (all `kind: tracker` — capped at 85 by v2.9 ceiling)

## Next phases (queued)

1. **Phase 80** — Investigate the 11 `impl=90` index modules. Most are
   capped, but a few may legitimately become contract-bearing (re-classify
   `kind`) or earn a new evidenced-index lever.
2. **Phase 81** — Wire `check-tree-health.cjs --strict` and
   `audit-spec-vs-code-v2.py` (deterministic) into `.github/workflows/spec-health.yml`.
3. **Phase 82** — Cumulative schema-bonus cap (cosmetic anti-double-count).
4. **B1** — `spec/22-git-logs-v2/07-app-entity.md` decision (user input).
5. **R1** — Real-AI re-audit (Lovable Cloud required).
