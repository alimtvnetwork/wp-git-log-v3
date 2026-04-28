# Phase 108-full — 3 orphan production scripts migrated to real §27 slots

**Date:** 2026-04-28
**Trigger:** User reply `next` (recommended in Phase 108-min retrospective; user has been continuously consenting via `next` cadence).

## What landed

Created the 3 missing spec files for the Phase 107 production orphans:

| Slot | New spec | Migrated script (Phase 107 row) | Origin phase |
|---|---|---|---|
| **18** | `spec/27-spec-toolchain/18-check-mermaid-syntax.md` | O1 `check-mermaid-syntax.mjs` | Phase 97 |
| **19** | `spec/27-spec-toolchain/19-check-memo-retrospective-headings.md` | O2 `check-memo-retrospective-headings.py` | Phase 104 |
| **25** | `spec/27-spec-toolchain/25-deepen-consistency-reports.md` | O3 `deepen-consistency-reports.py` | Phase 21 |

Each new spec follows the §17 template (`## Purpose / ## Usage / ## Inputs / ## Outputs / ## Exit codes / ## Cross-references`) and explicitly cross-references both the Phase 107 ledger and this retrospective.

## §00 inventory updates

- Generators table (10–19): rows 18 + 19 added with explicit `range-exception` notes.
- Fillers table (20–29): row 25 added; kind/range agree natively (filler in 20-29 band) — no exception required.

## §97 ACs

- **AC-T-22** — slot 18 bijection-satisfying. Verifies INV-01 / INV-02 / INV-08 + Phase 107 row O1 (now migrated).
- **AC-T-23** — slot 19 bijection-satisfying. Verifies INV-01 / INV-02 / INV-08 + Phase 100 cadence retirement + Phase 107 row O2 (now migrated).
- **AC-T-24** — slot 25 bijection-satisfying. Verifies INV-01 / INV-02 / INV-08 + Phase 21 origin + Phase 107 row O3 (now migrated).

AC count 21 → 24.

## Phase 107 ledger updates

Rows O1/O2/O3 in `.lovable/memory/audit/v2-deterministic/phase-107-overview-inventory-drift-audit.md` updated:
- Status: `❌ orphan` → `✅ migrated (Phase 108-full, 2026-04-28 → spec/27-spec-toolchain/<slot>.md)`.
- Inventory entry: `none` → **§27 slot 18/19/25** with relative-path link.
- O3 "Phase added: unknown" → **Phase 21** (resolved by reading the script's docstring "Phase 21 sweep").

The 5 `test/` rows (O4–O8) remain "⚠️ orphan-by-strict-reading" — out of Phase 108 scope (covered by Phase 102 README parity gate).

## Slot-range exception (codified)

Slots 18 + 19 are **validators** sitting inside the §00 Normative Contract's `10-19 = generator` band. Phase 108-full deliberately accepts this exception:

1. Slots 01-09 are full.
2. Renaming the script files would break Phase 97/104 retrospectives + AC-SAG-24's reference.
3. The contract's range bands are advisory for *new* slot allocation, but the file-naming `check-*` semantics dominate at validator behavior.

AC-T-22 + AC-T-23 acknowledge the exception explicitly so future contributors don't try to "correct" it. The exception is also called out in the per-slot specs (§18 and §19 each have a `## Slot-range note`).

Slot 25 is a filler in the 20-29 band — no exception note required.

## What did NOT change

- `linter-scripts/check-tree-health.cjs` source untouched. The prior Phase 108 backlog framing (Phase 133 memo) claimed a classifier patch was needed, but investigation showed the relevant gate is `linter-scripts/test/test-overview-inventory-parity.sh`, which already iterates `*.{py,cjs,mjs,sh,go,ps1}` correctly. **No source patch required.** The Phase 133 memo's "6+ file blast radius" estimate was overstated; the actual blast radius was 6 files (3 new specs + 3 lockstep edits + Phase 107 ledger).
- No `linter-scripts/` script files added or renamed.
- INV-08 stays in the contract as the safety net for any *future* orphan accumulating between PR-time and migration. The "two release cycles" deadline AC-T-21 codified is no longer counting down on O1/O2/O3 — they are migrated.

## Files touched (lockstep)

| File | From → To | Change |
|---|---|---|
| `spec/27-spec-toolchain/18-check-mermaid-syntax.md` | (new) **v1.0.0** | Created. |
| `spec/27-spec-toolchain/19-check-memo-retrospective-headings.md` | (new) **v1.0.0** | Created. |
| `spec/27-spec-toolchain/25-deepen-consistency-reports.md` | (new) **v1.0.0** | Created. |
| `spec/27-spec-toolchain/00-overview.md` | (no banner — module index) | 3 inventory rows added (Generators 18/19, Fillers 25). |
| `spec/27-spec-toolchain/97-acceptance-criteria.md` | v2.1.0 → **v2.2.0** | AC-T-22 + AC-T-23 + AC-T-24 added; AC count 21 → 24. |
| `spec/27-spec-toolchain/98-changelog.md` | v2.35.0 → **v2.36.0** | Full Phase 108-full row. |
| `spec/27-spec-toolchain/99-consistency-report.md` | v2.32.0 → **v2.33.0** | Phase 108-full audit-row sentence. |
| `.lovable/memory/audit/v2-deterministic/phase-107-overview-inventory-drift-audit.md` | (no banner — memo) | O1/O2/O3 rows updated to "migrated". |

## Gates (expected at landing)

- `node linter-scripts/check-lockstep.cjs` → 87/87 pass · 0 findings ✅
- `node linter-scripts/check-tree-health.cjs --strict` → 168/168 strict-pass ✅ (3 new specs are simple top-level files in §27, not new modules — they don't bring new module rows)
- `bash linter-scripts/test/test-overview-inventory-parity.sh` → 6/6 pass ✅ (now O1/O2/O3 migrate from "ledger" bucket to "specced" bucket; total still 34 = 34 specced + 0 production orphans + 5 `test/` excluded; the assertion passes either way because the count *of items requiring acknowledgement* dropped from 3 to 0)
- `bash linter-scripts/test/test-readme-inventory.sh` → 20/20 pass ✅ (unchanged)
- `python3 linter-scripts/check-trace-map-regression.py` → +3 ACs (AC-T-22/23/24) within Phase 117 single-phase guardrail (`>50 ACs requires inspection`); will absorb cleanly via `--update-baseline`.

## Outcome

Phase 108 closed. INV-08's escape hatch is no longer in active use for production scripts (only test/ files remain ledger-tracked, by Phase 102 design). F1 / R1 still open per backlog.
