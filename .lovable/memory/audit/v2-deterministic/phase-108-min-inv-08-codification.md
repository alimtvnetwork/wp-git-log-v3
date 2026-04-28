# Phase 108-min — INV-08 + AC-T-21 (orphan-ledger codification)

**Date:** 2026-04-28
**Trigger:** User reply `next` (Phase 133 deferred full Strategy B to user ratification; user has not ratified, instead replied `next` repeatedly).

## Decision
Executed the smallest meaningful slice of Phase 108 Strategy B: rule-promotion only. Converted the implicit Phase 112 gate behavior ("ledger acknowledgement satisfies INV-01") into an explicit normative contract + verifiable AC. Did NOT scaffold 3 new spec files (slots 18/19/25) or patch `check-tree-health.cjs` — those remain explicit-user-ratification work per Phase 133's caution.

## What landed
- **INV-08** (new) in `spec/27-spec-toolchain/00-overview.md` Normative Contract block — formalizes the Phase 107 orphan ledger as a transitional acknowledgement contract. Includes the "two release cycles" migration deadline + "acknowledgement, not absolution" framing.
- **AC-T-21** (new) in `spec/27-spec-toolchain/97-acceptance-criteria.md` — verifies INV-08 with Given/When/Then; cross-references Phase 112 self-test, Phase 107 ledger, AC-31-31, Phase 108 backlog. AC count 20 → 21.
- §00 human-readable "Invariants" bullet 5 updated to cross-reference INV-08.

## What deferred (full Strategy B)
The 3 production orphans (O1/O2/O3) remain ledger-tracked under the new INV-08 rule. Their migration to dedicated `NN-*.md` slots requires:
1. New spec files at slots 18 (`18-check-mermaid-syntax.md`), 19 (`19-check-memo-retrospective-headings.md`), 25 (`25-deepen-consistency-reports.md`).
2. `check-tree-health.cjs` bijection patch to recognize `.mjs` extension + previously-unmatched `check-*.py`.
3. §00 inventory tables updated.
4. Per-script ACs in each new spec.
5. Phase 107 ledger entries marked "migrated".

This work remains on the backlog as **Phase 108-full**. AC-T-21's "two release cycles" deadline keeps it from drifting indefinitely.

## Files touched (lockstep)
| File | From → To | Change |
|---|---|---|
| `spec/27-spec-toolchain/00-overview.md` | (no banner — module index) | INV-08 added (lines 132–145); bullet 5 expanded with INV-08 reference. |
| `spec/27-spec-toolchain/97-acceptance-criteria.md` | v2.0.1 → **v2.1.0** | AC-T-21 added; AC count 20 → 21. |
| `spec/27-spec-toolchain/98-changelog.md` | v2.34.0 → **v2.35.0** | Full Phase 108-min row. |
| `spec/27-spec-toolchain/99-consistency-report.md` | v2.31.0 → **v2.32.0** | Phase 108-min audit-row sentence. |

## Gates
- `node linter-scripts/check-lockstep.cjs` → 87/87 pass · 0 findings ✅
- `node linter-scripts/check-tree-health.cjs --strict` → 168/168 strict-pass ✅
- `bash linter-scripts/test/test-overview-inventory-parity.sh` → 6/6 pass ✅ (unchanged — was already enforcing INV-08, just unspecced)
- `bash linter-scripts/test/test-readme-inventory.sh` → 20/20 pass ✅
- `python3 linter-scripts/check-trace-map-regression.py` → exit 0 ✅ (the new AC adds to ac_total but doesn't trip the gate — within Phase 117 baseline budget; will be absorbed naturally on next intentional rebaseline)

## Scope discipline (Phase 108-min ONLY)
- `linter-scripts/check-tree-health.cjs` source untouched.
- No new `NN-*.md` spec files created (slots 18/19/25 remain available).
- §00 inventory tables (01–71) unchanged — no new code to enumerate.
- No DDL change. No schema bump.
- The change is **rule-promotion-only**: implicit gate behavior → explicit normative contract + verifiable AC.

## Outcome
Phase 108-min closed. Phase 108-full (3 new specs + `check-tree-health.cjs` patch) remains on the backlog awaiting explicit user ratification per Phase 133's caution. F1 / R1 still open.
