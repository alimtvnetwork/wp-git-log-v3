# Phase 153 Task A24-fu10 — spec/18-wp-plugin-how-to self-lift (80 → 86, +6)

**Closed:** 2026-04-30
**Module:** spec/18-wp-plugin-how-to (axis: process-guidance)
**Score movement:** 80 → **86** (+6, GOOD band — strong move within band)
**Dimensions:** d1 18→18 (=), d2 12→15 (+3), d3 15→17 (+2), d4 19→19 (=), d5 14→16 (+2)

## v7 findings closed (3/3 actionable)

| Severity | Dim | Title | Resolution |
|----------|-----|-------|------------|
| HIGH | D2 | Missing Verifies clauses for Phase 01-06 | **AC-10** — 6-row architectural-invariant binding table in §97 (Lesson #19 lift) |
| LOW | D3 | Concurrency and Race Conditions Unaddressed | **AC-11** — 5-row concurrency contract for FileLogger + self-update; cross-refs spec/13 AC-22 (Lesson #36) |
| MEDIUM | D5 | Unresolved External Refs in Consistency Report | §99 §4 mechanical cleanup — removed P0 #2/#3 + P1 #4 already-RESOLVED rows (Lesson #34) |

## New findings surfaced (next phase: A24-fu10-fu1)

- HIGH D2 "Missing Verifies clauses for Phase 07-21" — AC-10 deliberately scoped to phases 01-06 (architectural foundation); phases 07-21 are pattern-implementation files. A24-fu10-fu1 should extend AC-10 with a second 15-row table, OR split into AC-12 (Patterns 07-13) + AC-13 (Integration 14-21).
- MEDIUM D5 "Unresolved internal sub-file references" — different from the closed external-ref class; needs investigation.
- LOW D1 "Filename casing mismatch" — the existing `readme.md:84 → CHANGELOG.md` warning (already in §99 §2.1, retained as P0 row #1).

## Authored ACs

- **AC-10 [high]**: Phase-file architectural invariants binding (Phases 01–06) — 6-row table covering bootstrap idempotency, enum info-object pattern (cross-ref spec/02 Go enum spec), trait composition, FileLogger facade, Response envelope, Validator chain.
- **AC-11 [high]**: Concurrency contract for FileLogger + self-update — 5-row table covering `flock(LOCK_EX)` mandatory, `LOCK_NB` forbidden silent-drop class, atomic-rename rotation, `.zip.partial` staging with `.lock` sentinel, deferred reload via `register_shutdown_function`, sha256-verified atomic rollback. Cross-refs spec/13 AC-22 per Lesson #36.

## Lockstep

- §97 v1.2.0 → **v1.3.0** (minor — 2 new ACs, AC count 9 → 11)
- §00 v1.2.1 → **v1.3.0** (minor — synced to §98 per Lesson #25)
- §98 v1.2.1 → **v1.3.0** (minor — release row added)
- §99 v1.4.0 → **v1.4.1** (patch — §4 actionable-only cleanup)

## Gates (all GREEN)

- Lockstep 87/87 · 0 findings
- Tree-health 168/168 strict
- Version-parity 74/74 matches (initial drift caught + fixed mid-phase)

## Lessons reinforced

- **Lesson #19** (audit-boundary lift): AC-10 is the canonical pattern at the phase-file granularity (mirror of spec/02 AC-CG-21 Subfolder Delegation Map).
- **Lesson #25** (sync §00 to §98 minor): version-parity gate caught initial §00 v1.2.2 patch-only bump while §98 was minor v1.3.0 — required mid-phase correction. Future authors: when §97 minor ships, §00 + §98 SHOULD also bump minor, not patch.
- **Lesson #34** (cache-staleness mechanical cleanup): §99 §4 had stale rows for items already RESOLVED in §2.2/§2.3 — pure mechanical cleanup, no contract change, closes auditor finding.
- **Lesson #36** (link-don't-restate): AC-11 references spec/13 AC-22 for canonical concurrency posture; spec/18 only pins WordPress-specific surfaces (FileLogger, self-update, rollback).
- **Lesson #38** (gateway availability check): single `--force` re-score completed in <30s; gateway reliably available.

## Tree-state context

- Tree avg ≈ 85.70 → 85.96 (single-module +6 / 23 modules ≈ +0.26)
- Module remains GOOD band; A24-fu10-fu1 extending AC-10 to phases 07-21 likely lifts to ≥90 (EXCELLENT).
