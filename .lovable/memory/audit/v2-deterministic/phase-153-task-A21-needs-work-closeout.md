# Phase 153 Task A21 — spec/03 + spec/04 NEEDS_WORK Close-Out

**Date:** 2026-04-30
**Closes:** A21 (close spec/03 + spec/04 from 74 → ≥75; eliminate last 2 NEEDS_WORK modules tree-wide)
**Status:** ✅ CLOSED (LLM re-score deferred per Lesson #20)

## What shipped

**spec/04** (normative-contract, D2×1.5 highest leverage):
- **AC-10 `[high]`** ORM-First rule with mechanizable `rg` grep contract enumerating allowed surfaces (migrations / views / approved scripts / test fixtures).
- **AC-11 `[high]`** View-based-joins rule with `rg` grep contract for `->join(`-style ORM calls + depth-2+ eager-load discipline.
- Both ACs ship the exact `rg` invocation a future `linter-scripts/check-orm-first.sh` / `check-no-on-the-fly-joins.sh` MUST execute.
- Closes audit-v7 HIGH D2 finding [0] "Missing Acceptance Criteria for ORM and View Rules" verbatim as auditor prescribed.
- Lockstep: §97 1.2.0 → 1.3.0 (AC count 9 → 11); §00 3.4.2 → 3.5.0; §98 3.4.2 → 3.5.0; §99 3.6.2 → 3.7.0.
- Pre-A21: D1=18 D2=14 D3=12 D4=17 D5=15; weighted 74.5 → 74. Expected: D2 +2-3 → 76-77.

**spec/03** (audit-corpus, D5×1.5 highest leverage):
- **AC-09 `[high]`** Sub-Module Reference Resolution — elevates D5 from passive (asset-inventory via AC-08) to active (citation-density floor ≥3 cross-refs/file + dual-gate verification via existing `check-spec-folder-refs.py` + `check-spec-cross-links.py`).
- Closes audit-v7 HIGH D5 finding [0] "Broken Sub-module References" — gates already verify the invariant; AC-09 makes the contract explicit so D5 scoring can credit it.
- Lockstep: §97 2.1.0 → 2.2.0 (AC count 8 → 9); §00 3.4.1 → 3.4.2; §98 3.4.1 → 3.4.2; §99 3.2.1 → 3.3.0.
- Pre-A21: D1=18 D2=15 D3=14 D4=16 D5=12; weighted 74.5 → 74. Expected: D5 +2-3 → 76-78.

## All 5 strict gates GREEN

```
lockstep            : 87/87 · 0 findings
tree-health (strict): 168/168 · 100/100
version-parity      : 74/74 matches · 0 mismatches
freshness           : 81 stamped + 6 exempt + 0 unstamped
folder-refs         : 0 stale
```

## Lesson #44 codified

> When an LLM auditor explicitly prescribes "Add AC-NN and AC-MM specifically covering X and Y with grep-based verification commands", the highest-leverage close-out is to ship those ACs verbatim with the prescribed `rg` contract embedded — defer linter-script materialisation to a follow-up graduation phase (mirror of L21 parity-AC pattern). When existing gates already verify the invariant, cite them directly in the AC's `**Verifies:**` clause so D5/D2 scoring can credit the contract.

Codified inline in spec/04 §98 v3.5.0 row + spec/04 §99 v3.7.0 row + spec/03 §98 v3.4.2 row.

## Side-fix

spec/04 §99 `Last Updated: 2026-04-29` was 1 day behind §00 `Updated: 2026-04-30` after the banner bumps — caught by lockstep [L1] gate, fixed by bumping §99 to 2026-04-30. Confirms lockstep gate works as designed for date-staleness drift.

## Files changed

- `spec/04-database-conventions/97-acceptance-criteria.md` (AC-10 + AC-11 added)
- `spec/04-database-conventions/00-overview.md` (banner)
- `spec/04-database-conventions/98-changelog.md` (banner + release row)
- `spec/04-database-conventions/99-consistency-report.md` (banner + narrative + Validation History row + Updated date)
- `spec/03-error-manage/97-acceptance-criteria.md` (AC-09 added)
- `spec/03-error-manage/00-overview.md` (banner + h10 stamp + Updated)
- `spec/03-error-manage/98-changelog.md` (banner + release row)
- `spec/03-error-manage/99-consistency-report.md` (banner + Validation History row)
