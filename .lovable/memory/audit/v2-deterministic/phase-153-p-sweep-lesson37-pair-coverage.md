# Phase 153 P-sweep — Lesson #37 Complete-Pair Coverage Audit

**Date:** 2026-05-06
**Scope:** 7 candidate integration-axis modules (spec/03, 10, 11, 14, 16, 17, 18)
**Mode:** Survey-only — no spec edits, no lockstep ripple
**Lesson tested:** #37 (integration-axis modules need BOTH audit-boundary pin [Lesson #19] AND cross-module anchor map [Lesson #36])

## Detection heuristics

- **Audit-boundary pin** (Lesson #19): grep `audit-boundary | kind:.*(interface|tracker|index|post-mortem) | module-kind | walker.*(cap|tier)` in §97
- **Cross-module anchor map** (Lesson #36): grep `cross-module.*(citation|anchor|map) | externalized citation | restate-in.*forbidden` in §97
- **External-ref density**: `grep -rEoh "spec/NN-… | linter-scripts/…"` in module tree

## Findings

| Module | ACs | Pin | Map | Ext refs | Pair status | Priority |
|---|---:|---:|---:|---:|---|---|
| 03-error-manage | 12 | 3 | **0** | **43** | ⚠ MAP MISSING | 🔴 HIGH |
| 10-research | 10 | 3 | **0** | 15 | ⚠ MAP MISSING | 🟡 MED |
| 11-powershell-integration | 13 | 2 | **0** | 12 | ⚠ MAP MISSING (AC-11 partial — Lesson #37 precedent) | 🟢 LOW (AC-11 already anchors 4 scripts) |
| 14-update | 22 | 1 | **0** | 17 | ⚠ MAP MISSING | 🟡 MED |
| 16-generic-release | 21 | 1 | **0** | 11 | ⚠ MAP MISSING | 🟢 LOW |
| 17-consolidated-guidelines | 15 | 12 | **0** | **63** | ⚠ MAP MISSING | 🔴 HIGH |
| 18-wp-plugin-how-to | 16 | 8 | **0** | 19 | ⚠ MAP MISSING | 🟡 MED |

**Pair-complete modules tree-wide (precedent):** spec/22 (AC-78 + AC-79), spec/12 (AC-10 + AC-11). Pattern is exactly 2/56 — Lesson #37 is undersaturated.

## Disposition

Two true integration-axis hotspots emerge by external-ref density:

1. **spec/17-consolidated-guidelines** — 63 external refs, 12 pin signals but 0 anchor map. This is the highest-leverage gap (governance hub citing every other module).
2. **spec/03-error-manage** — 43 external refs, 0 anchor map. Error contracts cross-cite spec/04 (DB), spec/05 (data), spec/13 (CLI), spec/27 (linters).

Lower-density modules (spec/10, 14, 16, 18) **may not need the full anchor map**; their external refs are mostly intra-domain (e.g. spec/14-update → spec/27 release scripts). Author per-module judgment required — do NOT mass-apply the AC-78/AC-79 pattern blindly (Lesson #79 plateau-class warning).

## Ranked follow-up phases

| Phase | Module | Action | Expected lift |
|---|---|---|---|
| **P-sweep-1** | spec/17 | Author cross-module anchor map AC (~10–15 row table) | Audit-followability +meaningful (highest blast radius) |
| P-sweep-2 | spec/03 | Author cross-module anchor map AC (~6–8 rows) | Error-chain followability |
| P-sweep-3 | spec/14, 16, 18, 10 | Per-module review: anchor map vs Lesson #36 link-only | Targeted; may be NO-OPs |
| P-sweep-4 | spec/11 | Promote AC-11's 4-script anchor into full Cross-Module Citation Map | Completes pair (already partial) |

## Lesson confirmations

- **Lesson #37 reconfirmed**: integration-axis modules systematically lack the anchor-map half of the pair. The pin half is much more common (audit fixed first historically); the anchor map is the newer pattern (post-Phase-153-A24-fu4).
- **NEW candidate Lesson (deferred)**: external-ref count >40 is a strong signal that an anchor map is warranted; <20 may not justify it.

## No edits this phase

Pure survey. Counter unchanged. Lockstep/tree-health unaffected.
