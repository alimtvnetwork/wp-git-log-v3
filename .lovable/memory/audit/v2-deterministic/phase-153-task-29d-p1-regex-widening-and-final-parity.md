# Phase 153 Task #29d — P1 inventory regex widened + final AI-confidence parity

**Date:** 2026-04-29
**Status:** CLOSED
**User reply:** `next`

## Result headline

**AI-confidence parity: 51/51 matches (100%) tree-wide.** All P1/P2/P3/P4 gates pass on every nested + top-level module carrying an `**AI Confidence:**` banner.

## Trajectory this phase

| Step | matches/eligible |
|---|---|
| Task #29e baseline | 36/51 |
| P1 regex widened (bare-filename + Index/Modules headings + multi-section scan) | 49/51 |
| 2 LEGACY-stub Verifies + 1 inventory entry added | 50/51 |
| 1 final underclaim promotion | **51/51** |

## Fixes applied to `check-ai-confidence.py`

1. **`INVENTORY_BARE_RE`** new regex: matches bare-filename references inside table rows (`|`-prefixed) or list items.
2. **Multi-section scan**: §00 may have BOTH a "Full Document Inventory" (subfolder paths) AND a "Document Inventory" (bare filenames) — both now scanned.
3. **Heading-name tolerance**: also accepts `Index`, `Modules`, `Files`, `Contents` (case-insensitive). Mirrors the §99 inventory-heading list in Core memory.
4. **Coupling preserved**: link-form (`](./file.md)`) still scanned tree-wide; bare-form restricted to inventory-titled sections (avoids false matches in prose).

## Spec edits (15 modules)

- **P3 LEGACY backfill** (§97 minor + §00/§98/§99 patch): 02/01-cross-language (+2), 02/03-golang (+2), 02/04-php (+7), 02/05-rust (+6) → 17 clauses.
- **Underclaim promotion** (§00/§98/§99 patch): 02/03-golang/01-enum-spec, 18-wp-plugin/02-enums, plus 9 from earlier in the phase.
- **Inventory entry** added: `06-lint-rule-catalog.md` to `03-error-manage/03-error-code-registry/00-overview.md`.
- **Banner format fix**: `25-app-issues/01-phase-2-git-logs-audit/00-overview.md` split joined `**Version:** ... **Updated:**` line.

## Validation

- `check-lockstep.cjs --strict`: 87/87, 0 findings.
- `check-tree-health.cjs --strict`: 168/168.
- `check-ai-confidence.py`: 51/51 matches.
- `test-check-ai-confidence.sh`: 5/5 PASS.

## Lessons codified

1. **Inventory-format heterogeneity is the rule, not the exception.** §00 inventories use 5+ heading names (`Inventory`, `Index`, `Modules`, `Files`, `Contents`) and 3 link forms (markdown link, bare-filename in table, bare-filename in list). Linters checking inventory completeness MUST tolerate all variants OR explicitly normalise the spec tree (much larger sweep).
2. **Multi-section §00 is a real pattern.** spec/02/01-cross-language has TWO inventories (legacy "Full Document Inventory" with subfolder paths + canonical "Document Inventory" with bare files). Single-section regex scans miss the canonical one. Use `finditer`, not `search`.
3. **Cascading P1 fixes unlock downstream P3/P4 visibility.** Each P1 widening surfaced 2-7 new actionable findings (mostly underclaims, some real Verifies gaps). Sequence: P1 regex → underclaim sweep → P3 backfill → final P1 sweep. Iterate until matches plateaus.
4. **AC-33-07 precedent reapplied successfully.** Both P48-1-fu1-batch-3 (numeric-prefix → any-prefix) and Phase 153 Task #29d (link-form → link-OR-bare) followed the same pattern: ≥30% findings cluster on a mechanical pattern → inspect the regex BEFORE mass-patching the tree.
