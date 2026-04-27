# Phase 86 — Schema-Bonus Cap Investigation (REJECTED)

**Date:** 2026-04-27
**Scope:** `linter-scripts/audit-spec-vs-code-v2.py` rubric `else` branch (normal contract modules)
**Status:** ✅ Complete (negative result documented in source + memo)

## Hypothesis
The rubric's additive contract bonuses (SQL +20, JSON +15, TS +10, OpenAPI +10,
typed-lang +10, CI +5 = up to +70) implied "more contract types = strictly more
credit" without diminishing returns. Theoretical concern: a kitchen-sink module
could game the rubric by sprinkling tiny contract snippets of every type.

## Experiment
Implemented diminishing-returns variant in v2.15:
```python
contract_bonuses = []  # collect bonuses for each true contract type
if m["has_sql_ddl"]:                 contract_bonuses.append(20)
# ... etc
contract_bonuses.sort(reverse=True)
contract_subtotal = 0
for i, bonus in enumerate(contract_bonuses):
    contract_subtotal += bonus if i == 0 else bonus // 2  # halve subsequent
impl += min(contract_subtotal, 50)  # hard-cap at 50
```

## Empirical Result on 87-Module Corpus

| Metric | Before (v2.14) | After (v2.15-experimental) | Δ |
|---|---:|---:|---:|
| Mean weighted | 98.0 | 94.1 | **−3.9** |
| Mean implementability | 99.8 | 89.2 | **−10.6** |
| Modules with `impl < 100` (normal contract) | ~10 | 76 | **+66** |
| CI gate `--min-weighted=97 --min-impl=99` | ✓ PASS | ✗ FAIL × 2 | regression |

Top losers (real, well-specified modules — not gaming):
- `22-git-logs-v2` impl 100 → 79 (legitimately has SQL+TS+JSON+YAML)
- `02-coding-guidelines` impl 100 → 82
- `04-database-conventions` impl 100 → 82
- `18-wp-plugin-how-to` impl 100 → 82
- `05-split-db-architecture` impl 100 → 84

## Decision: REJECTED, REVERTED

**Rationale:**
1. Multi-contract breadth is **genuine signal**, not gaming. A spec that ships
   SQL DDL + TS enums + JSON schema + OpenAPI for one feature encodes more
   invariants and is materially more implementable than a spec with one of those.
2. The existing `impl = max(0, min(100, impl))` cap already prevents pathological
   stacking (the rubric tops out at 100 regardless).
3. The "kitchen sink gaming" attack vector is theoretical — no module in the
   corpus exhibits it. Phase 86's experiment punished real quality to defend
   against an unobserved threat.

**Reverted to v2.3 additive model.** Source comment in `audit-spec-vs-code-v2.py`
preserves the rejected design + empirical impact so future contributors don't
re-propose it without seeing the data.

## Lesson
Rubric "purity" critiques must be validated empirically against the actual
corpus before being applied. Diminishing-returns sounds principled but only
helps when the corpus contains modules that earn bonuses they haven't
substantively justified — which our corpus doesn't.

## Verification
- Gates restored: tree-health (strict) 100/100, lockstep (strict) 0 findings,
  audit `--min-weighted=97 --min-impl=99` PASS at 98.0 / 99.8.
- Source-of-truth banner bumped to v2.15 with REJECTED-experiment changelog.
- No spec/AC changes required (rejected change never reached spec/27-spec-toolchain).

## Files touched
- `linter-scripts/audit-spec-vs-code-v2.py` — v2.15 banner + 8-line comment block in `else` branch documenting the rejected experiment.
