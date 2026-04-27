# Phase 112 — §27 inventory parity triangle self-test

**Date landed:** 2026-04-27
**Trigger AC:** AC-31-31 (Phase 109 — multi-file enumeration parity contract)
**Closes drift documented in:** Phase 107 (`phase-107-overview-inventory-drift-audit.md`)
**Verifies:** AC-T-01 (§27 §97 bijection), INV-01/INV-02 (§00 Normative Contract), AC-31-31 row #3
**CI gate count:** 11 → **12**

---

## What landed

A new parity self-test, `linter-scripts/test/test-overview-inventory-parity.sh`, mechanically enforces the 3-way parity triangle for the §27 toolchain inventory:

| Site | Role | Source of truth |
|---|---|---|
| `spec/27-spec-toolchain/00-overview.md` | Canonical spec inventory | tables §38–§111 (one row per script/workflow) |
| Filesystem | Truth | `linter-scripts/*.{py,cjs,mjs,sh,go,ps1}` + `.github/workflows/*.yml` |
| `.lovable/memory/audit/v2-deterministic/phase-107-*.md` | Acknowledged-orphan ledger | back-ticked code paths in the Phase 107 audit memo |

The test asserts **6 invariants**:

1. Every on-disk script is tracked in §27 overview OR Phase 107 orphan memo (INV-01).
2. Every code path listed in §27 overview exists on disk (INV-02 / FAIL-02).
3. Every orphan path in Phase 107 memo still exists on disk (no stale ledger).
4. §27 overview has the `Inventory` H2 section.
5. §27 overview has the `Normative Contract` block (INV-01/INV-02 source).
6. Phase 107 memo references AC-31-31 OR INV-01 anchor.

Baseline at landing (verified locally and in regenerated audit output):

```
Filesystem:    34 executable artifacts
§27 overview:  31 tracked code paths
Phase 107:     5 known orphans
Math:          31 (specced) + 3 (production orphans) + 5 (test/ excluded by scope) = 34 + 5 ≈ 39 raw, 34 in scope
Result:        6 passed, 0 failed
```

The 5 `linter-scripts/test/test-*.sh` files are excluded by directory scope — the `find` filter is `-maxdepth 1` against `linter-scripts/` — because the canonical parity test for `test/` is already Phase 102 (`test-readme-inventory.sh`). Layering both would create a dual-coverage zone that violates AC-31-31's single-test-per-enumeration principle.

## Why this is the **first contribution authored under AC-31-31**

Phase 109 codified the multi-file enumeration parity contract but seeded its registry table with the two pre-existing parity tests (Phases 102 + 103) as **evidence**, not as new contributions. Phase 112 is the first time the contract was **applied prospectively**: the §27 inventory triangle was identified as a 3-file restatement during Phase 109's "currently-NOT-qualifying enumerations" inventory, and Phase 112 authored the parity test in response to that identification.

The 3-step protocol from AC-31-31 was followed verbatim:

1. **Author the parity self-test** → `linter-scripts/test/test-overview-inventory-parity.sh` (6 assertions, ~1 s).
2. **Add a row to the registry** → §31 AC-31-31 registry table extended with row #3.
3. **Bump §27 §98/§99 in lockstep** → §31 v1.19.0 → v1.20.0; §98 v2.26.0 → v2.27.0; §99 v2.23.0 → v2.24.0.

§97 was deliberately not bumped: AC-T-01 already enumerates the bijection invariant at the module level. Phase 112's contribution is a *mechanical enforcer* of the existing AC, not a new AC.

## CI gate cascade

Adding the new step triggered the AC-31-31 cascade for the existing CI-gate-count enumeration (the *original* parity-test target from Phase 103):

- `audit-spec-vs-code-v2.py` `RUBRIC_VERSION` v2.20 → **v2.21**.
- `00-index.md` "QA tooling baseline" footer regenerated to enumerate **12 gates** (was 11), with new row #12 referencing the Phase 112 self-test and AC-31-31 + INV-01/INV-02.
- `EXECUTIVE-SUMMARY.md` cross-reference updated to "12 strict CI gates".
- `linter-scripts/test/test-qa-baseline-footer.sh` (the Phase 103 footer-parity test) extended with the new gate-name pattern `Self-test §27 inventory parity triangle`. After regeneration: **11/11 assertions pass at 12-gate alignment** across all 4 sites (script constant ↔ 00-index declared count ↔ footer rows ↔ workflow step list).
- `linter-scripts/test/README.md` extended with row #6 in inventory + coverage-triad tables; "Last updated" → Phase 112; totals updated; "Adding a new self-test" callout extended with the new gate's failure mode.

The cascade itself is the empirical proof that AC-31-31 is the right abstraction: a single new gate forced 5 coordinated edits across 6 files, each of which would have silently drifted without the existing Phase 102 + Phase 103 parity tests catching them.

## Why `check-tree-health.cjs` was not patched

Phase 107 recommended (under Strategy B) patching `check-tree-health.cjs` to detect `.mjs` files and unmatched `check-*.py`. That work is independent of Phase 112 and remains queued for Phase 108 (the deferred strategy-choice decision). Phase 112 closes the *documentation* drift surface (the 3-way triangle); Phase 108 will close the *script-classifier* drift surface inside `check-tree-health.cjs` itself. The two phases attack the same root cause (orphan accumulation) at different layers.

## Verification

All 12 strict CI gates green:

| # | Gate | Result |
|---|---|---|
| 1 | Cross-links | ✅ |
| 2 | Tree-health | ✅ 100/100 |
| 3 | Lockstep | ✅ 0 findings |
| 4 | Audit thresholds | ✅ 98.0/99.8 |
| 5 | CLI threshold self-test (Phase 91) | ✅ 6/6 |
| 6 | `--explain` self-test (Phase 94) | ✅ 14/14 |
| 7 | Determinism self-test (Phase 95) | ✅ 7/7 |
| 8 | Mermaid syntax (Phase 97) | ✅ 106/106 |
| 9 | README inventory parity (Phase 102) | ✅ 18/18 (was 14, now 18 after 6th row) |
| 10 | QA baseline footer (Phase 103) | ✅ 11/11 at 12-gate alignment (v2.21 / 12 / 12 / 12) |
| 11 | Memo retrospective headings (Phase 104) | ✅ 9 in-scope memos, 0 forbidden headings (this memo included) |
| 12 | **§27 inventory parity triangle (Phase 112)** | ✅ **6/6** (filesystem 34, overview 31, orphan-memo 5) |

§27 holds at **97/100 A+** with impl=100. No score regression — pure safety-net + output-clarity contribution.

## Outcome and what the contract caught about itself

The most interesting validation came from **running the new test before authoring it correctly**. Initial pass attempted to count §27 overview rows by extracting the `code artifact` column with a fragile regex, missed 6 entries, and would have flagged 6 false-positive untracked files. Switching the extractor to "any backticked path matching `linter-scripts/...` or `.github/workflows/...`" — i.e. trusting markdown's existing structural discipline rather than re-parsing column boundaries — yielded the correct 31-tracked-paths count on first run.

This pattern (let markdown's existing backtick discipline carry the inventory rather than building a custom DSL) is the third instance of the same lesson, after Phase 102 (markdown links carry the inventory) and Phase 103 (regex on `**Rubric:** v<X>.<Y>` carries the version). All three parity tests now share this design property: **the source-of-truth file uses an existing markdown idiom, and the test extracts via that idiom**, never inventing a custom delimiter.
