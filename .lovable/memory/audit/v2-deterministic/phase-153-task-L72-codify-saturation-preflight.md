---
phase: 153
task: L72-codify
date: 2026-05-04
status: CLOSED — Lesson #72 graduated to indexed process rule
gates: docs-only · no spec edits · no lockstep ripple
---

# L72-codify — Pre-flight saturation check on normative-contract §97 adds

## Trigger

Two precedents (>1 = graduation criterion per Lesson #32 anchor-at-source):

1. **Phase 153 Task A23** — large §97 add on a saturated normative-contract module produced **−7 score regression** because new ACs pushed earlier ones out of the walker's tier-1 bundle.
2. **Phase 153 Task A05-fu** — projected §97 size **82.5 KB** detected by pre-flight `wc -c`; edit reverted, switched to pure-promotion via Lesson #36 canonical-anchor cross-reference. Score held (no regression risk taken).

## Rule (codified inline in `mem://process/phase-153-lessons` Section A)

Before adding any AC to a `normative-contract`-axis §97:

1. Run `wc -c spec/<NN>/97-acceptance-criteria.md`.
2. Project post-edit size = current + new AC body (header + GWT + Verifies + Why ≈ 1.5–3 KB).
3. **Reject if projected > 75 KB.**
4. Choose alternative path:
   - (a) Cross-reference to canonical owner (Lesson #36).
   - (b) Split content to sibling `NN-feature.md` with small §97 stub.
   - (c) Collapse stale legacy AC (Lesson #23) to free budget.

## Why 75 KB

Empirical: walker `MAX_BYTES=120 KB` minus tier-1 reserve for `{00, 98, 99}` (~30–45 KB combined on healthy modules) leaves ~75 KB headroom for §97. Above this, §97 either truncates or crowds out §99 inventory, both of which degrade the audit score asymmetrically (D2 AC-coverage drop > D1 contract-presence gain from new AC).

## Scope

Applies to `normative-contract` axis only (the largest module class — 14 of 23 modules). Other axes have different walker budgets:
- `tooling-spec` (slot 27, 34): tier-1 already saturated at 262 KB; A18 walker raise is the only fix.
- `process-guidance` / `audit-corpus` / `integration-spec`: lower §97 sizes typical; saturation rare.

## Files changed

- `mem://process/phase-153-lessons` — added Lesson #72 in Section A; reverse-index row appended.

## Cross-references

- Lesson #45 — saturation budget concept (parent).
- Lesson #36 — canonical-anchor alternative (preferred fallback).
- Lesson #23 — stale-AC collapse (alternative fallback).
- Lesson #75 — zero-finding tree implies steady-state (when §97 saturation is the binding constraint).

## Lockstep

None — pure docs work in `mem://`. No `spec/` files touched.
