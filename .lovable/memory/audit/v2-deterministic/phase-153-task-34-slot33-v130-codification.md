# Phase 153 Task #34 — Slot 33 v1.3.0 Codification (AC-33-10/11/12)

**Date:** 2026-04-29  
**Trigger:** User reply `next` after Task #29d closure left linter at v1.3.0 behaviour but slot-33 spec banner still claimed v1.2.0.

## What changed

Codified Task #29d's three regex widenings as new slot-33 ACs:

- **AC-33-10** — Multi-section inventory scan: `gate_p1()` MUST iterate ALL `## …Inventory` headings via `inv_heading_re.finditer()` (not `re.search()` first-match). Precedent: `spec/00` carries both `## Full Document Inventory` AND `## Document Inventory`.
- **AC-33-11** — Heading-name tolerance: `inv_heading_re` matches `(Inventory|Index|Modules|Files|Contents)` case-insensitively. Five canonical forms across the tree by design.
- **AC-33-12** — Bare-filename inventory entries: `INVENTORY_BARE_RE` counts `| 01-foo.md |` table cells and `- 01-foo.md` list items alongside markdown links. Three legitimate authoring forms coexist.

## Lockstep

- `33-check-ai-confidence.md` banner v1.2.0 → **v1.3.0**
- §27 §97 v2.6.0 → **v2.7.0** (3 new ACs, slot-internal — module count unchanged at 26)
- §27 §00 v2.73.0 → **v2.74.0**
- §27 §98 v2.73.0 → **v2.74.0** (new release row 2.74.0)
- §27 §99 v2.70.0 → **v2.71.0** (new banner; v2.70.0 banner preserved)

## Validation

- `check-lockstep.cjs --strict`: 87/87 pass · 0 findings
- `check-tree-health.cjs --strict`: 168/168 (100/100, threshold 100)
- `check-ai-confidence.py`: scanned=87, eligible=51, **matches=51**, mismatches=0

## Lesson codified (in §98 v2.74.0)

When a sweep deploys multiple regex/walker widenings to close ONE drift class, codify them as a *grouped* AC release on the linter's spec slot — not one phase per regex. Reader benefit (single Why prose chain) outweighs finer version-bump granularity; lockstep cost is identical (one §97 minor + one §00/§98/§99 patch). Three separate phases would fragment the rationale without changing CI surface.

## State

- Linter binary unchanged (already shipping v1.3.0 behaviour since Task #29d).
- This phase is documentation-only (spec catches up to deployed behaviour).
- AI-confidence drift class: **CLOSED** tree-wide (51/51).
