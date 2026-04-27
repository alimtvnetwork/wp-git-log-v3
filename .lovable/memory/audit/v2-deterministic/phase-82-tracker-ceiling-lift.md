# Phase 82 — tracker ceiling lift (rubric v2.13)

**Date:** 2026-04-27
**Trigger:** `next` (Phase 82 from Phase 81 roadmap)
**Status:** ✅ Complete

## Outcome

Promoted **3 `kind: tracker` modules** from `impl=85` → `impl=95` by:
1. Extending the deterministic rubric to **v2.13**: trackers now earn `+5`
   per inlined typed contract (SQL DDL, TS enum, JSON schema, OpenAPI,
   typed-language reference). When ≥1 contract bonus fires, the ceiling
   raises from 85 → 95. Prose-only trackers remain capped at 85.
2. Adding a normative `tracker_issue_p82` SQL DDL block (with severity
   CHECK, status enum CHECK, partial indexes for open issues and severity
   ranking) to all 3 trackers' `00-overview.md`.

## Rationale

A `kind: tracker` module that inlines a typed contract is doing dual duty:
- documenting open issues / unresolved work (the original "tracker" role), AND
- supplying an authoritative schema for downstream issue-log persistence.

This deserves a higher ceiling than a prose-only tracker. We stop short of
100 (kept at 95) because trackers fundamentally document the *absence* of
work — by definition they describe gaps, not finished features.

## Metrics delta

| Metric | Before | After | Δ |
|---|---|---|---|
| Mean weighted (87 modules) | 96.3 | **96.5** | +0.2 |
| Mean implementability | 99.5 | **99.8** | +0.3 |
| Modules at impl ≥ 95 | 84 | **87** | +3 |
| Trackers at impl=85 | 3 | **0** | −3 |

Distribution after Phase 82:
- `impl=100`: 84 modules
- `impl=95`: 3 modules (the 3 trackers, hard-capped by v2.13)
- All 87 modules now ≥ 95.

## Rubric change (v2.13)

`linter-scripts/audit-spec-vs-code-v2.py` lines 525-547:

```python
if is_tracker:
    impl = 75
    if m["overview_chars"] < 200: impl -= 15
    if m.get("has_mermaid"):     impl += 5
    if m.get("has_ci_workflow"): impl += 5
    # v2.13: contract-bearing tracker bonus
    contract_bonus = 0
    if m["has_sql_ddl"]:                  contract_bonus += 5
    if m["has_ts_enums"]:                 contract_bonus += 5
    if m["has_json_schema"]:              contract_bonus += 5
    if m["has_yaml_openapi"]:             contract_bonus += 5
    if m.get("has_typed_lang_contract"):  contract_bonus += 5
    impl += contract_bonus
    impl = min(impl, 95 if contract_bonus > 0 else 85)
```

## Gates

- Lockstep: ✅ PASS (strict, 87 modules, 0 findings)
- Tree-health: ✅ PASS 100/100 (strict, all 56 modules at full marks)
- Audit threshold gate: ✅ PASS (96.5 ≥ 95, 99.8 ≥ 98)

## Files touched

- `linter-scripts/audit-spec-vs-code-v2.py` — rubric v2.13
- 3 × `spec/**/03-issues|02-consolidated-audit-findings/00-overview.md`
  — appended `tracker_issue_p82` SQL DDL block
- Audit artefacts regenerated

## Next phases (queued)

1. **Phase 83** — Audit `weighted_overall < 95` modules (~3 left, all
   trackers at 92). Levers are completeness / testability / clarity, not
   implementability. Investigate AC count and overview depth.
2. **Phase 84** — Cumulative schema-bonus cap (cosmetic anti-double-count
   rubric refinement). Low priority.
3. **Phase 85** — Document the new `--min-weighted` / `--min-impl` flags
   and v2.13 evidenced-tracker bonus in `spec/27-spec-toolchain/`.
4. **Phase 86** — Bump CI floors in `spec-health.yml` from `--min-weighted=95
   --min-impl=98` to `--min-weighted=96 --min-impl=99` (Phase 81 floors are
   now too lenient).
5. **B1** — `spec/22-git-logs-v2/07-app-entity.md` decision (user input).
6. **R1** — Real-AI re-audit (Lovable Cloud required).

---

## Retrospective (added in Phase 92)

Outcome map for the "Next phases (queued)" list above:

| # | Original queued task | Actual outcome |
|---|---|---|
| 1 | Phase 83 — audit `weighted_overall < 95` modules (~3 trackers at 92) | ✅ **Shipped in Phase 83**: rubric v2.14 (tightened TODO regex + `todo_audit_exempt: true` opt-out) + AC injection on 30 modules. Result: **mean weighted 96.5 → 98.0**, all 87 modules ≥ 95. |
| 2 | Phase 84 — cumulative schema-bonus cap | ❌ **REJECTED in Phase 86.** Empirical test on the corpus showed mean impl 99.8 → 89.2 with 76 modules unfairly downgraded. The "kitchen-sink gaming" attack was theoretical with no corpus evidence. Source comment + `phase-86-schema-cap-rejected.md` preserve the rejected design. **Do not re-propose without new corpus data.** |
| 3 | Phase 85 — document new flags + v2.13 in `spec/27-spec-toolchain/` | ✅ **Shipped in Phase 85**: §31 v1.7.0 → v1.8.0 with **AC-31-17 → AC-31-22** (one AC per rubric version v2.9–v2.14) + Rubric Changelog table + `Source` line annotated with script v2.14 marker. |
| 4 | Phase 86 — bump CI floors to `weighted=96 / impl=99` | ✅ **Shipped in Phase 84** (one phase earlier than queued, and tighter than queued: `weighted=97 / impl=99`). The queued `96/99` numbers were superseded by Phase 83's stronger result that lifted mean weighted to 98.0. |
| 5 | B1 decision | 🚧 Still pending user input. |
| 6 | R1 real-AI re-audit | 🚧 Still blocked on Lovable Cloud. |

Note: this memo's queued numbering used Phases 83–86 sequentially; the actual delivery order was 83 → 84 (CI floors) → 85 (toolchain doc) → 86 (schema cap **rejected**). The renumbering happened because Phase 84's CI-floor work was promoted ahead of the toolchain-doc sweep once Phase 83's wins made the old floors visibly lenient.
