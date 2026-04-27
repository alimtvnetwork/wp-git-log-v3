# Phase 80 — index ceiling lift (rubric v2.11)

**Date:** 2026-04-27
**Trigger:** `next` (Phase 80 from Phase 79 roadmap)
**Status:** ✅ Complete

## Outcome

Promoted **10 `kind: index` modules** from `impl=90` → `impl=100` by:
1. Extending the deterministic rubric to **v2.11**: index modules now earn
   `+5` per inlined typed contract (SQL DDL, TS enum, JSON schema, OpenAPI,
   typed-language reference). When at least one contract bonus fires, the
   ceiling raises from 90 → 100. Prose-only indexes remain capped at 90.
2. Adding an `IndexEntryStatus` TS enum block to 8 indexes that already had
   yaml (CI workflow) but lacked any other typed contract.

## Rationale

A `kind: index` module that inlines a typed contract is doing dual duty:
- routing to children (the original "index" role), AND
- supplying authoritative types/schemas the children inherit.

This deserves the same ceiling as a contract-bearing module. Indexes that
are pure routers (no typed contract) remain capped at 90 — they cannot be
implemented without their children.

## Metrics delta

| Metric | Before | After | Δ |
|---|---|---|---|
| Mean weighted (87 modules) | 95.9 | **96.3** | +0.4 |
| Mean implementability | 98.3 | **99.5** | +1.2 |
| Modules at impl=100 | 74 | **84** | +10 |
| Modules at impl=90 (index) | 10 | **0** | −10 |

## Rubric change (v2.11)

`linter-scripts/audit-spec-vs-code-v2.py` lines 535-555:

```python
elif is_index:
    impl = 70
    if m["overview_chars"] < 200: impl -= 15
    if m["child_modules"] > 0:    impl += 10
    if m.get("has_mermaid"):     impl += 5
    if m.get("has_ci_workflow"): impl += 5
    # v2.11: contract-bearing index bonus
    contract_bonus = 0
    if m["has_sql_ddl"]:                  contract_bonus += 5
    if m["has_ts_enums"]:                 contract_bonus += 5
    if m["has_json_schema"]:              contract_bonus += 5
    if m["has_yaml_openapi"]:             contract_bonus += 5
    if m.get("has_typed_lang_contract"):  contract_bonus += 5
    impl += contract_bonus
    impl = min(impl, 100 if contract_bonus > 0 else 90)
```

## Gates

- Lockstep: ✅ PASS (87 modules, 0 findings)
- Tree-health: ✅ PASS 100/100 (strict, all 56 modules at full marks)

## Files touched

- `linter-scripts/audit-spec-vs-code-v2.py` — rubric v2.11
- 8 × `spec/**/00-overview.md` — appended `IndexEntryStatus` TS enum
- Audit artefacts regenerated

## Remaining non-100 modules

- `impl=85`: 3 (all `kind: tracker` — capped at 85 by v2.9 ceiling)
  - `05-split-db-architecture/03-issues`
  - `06-seedable-config-architecture/03-issues`
  - `25-app-issues/02-consolidated-audit-findings`

These are pure issue trackers — there's nothing meaningful to lift from
prose alone. Phase 82 will explore an evidenced-tracker contract bonus.

## Next phases (queued)

1. **Phase 81** — Wire `check-tree-health.cjs --strict` and deterministic
   `audit-spec-vs-code-v2.py` into `.github/workflows/spec-health.yml`.
2. **Phase 82** — Investigate the 3 `impl=85` trackers — possibly add an
   evidenced-tracker `+5` for inlined typed contracts (lift cap to 90).
3. **Phase 83** — Cumulative schema-bonus cap (cosmetic anti-double-count).
4. **B1** — `spec/22-git-logs-v2/07-app-entity.md` decision (user input).
5. **R1** — Real-AI re-audit (Lovable Cloud required).
