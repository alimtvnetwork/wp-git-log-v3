# Phase 153 Task #29a — Scaffolder Patched, Boilerplate Verifies-Drift Bounded

**Date:** 2026-04-29
**Driver:** Root-cause fix for the audit-v6 boilerplate blind spot discovered in Phase 153 (24 §97 files missing `**Verifies:**` clauses because the scaffolder never emitted them).

## Change

Patched `linter-scripts/fill-missing-acceptance-criteria.cjs` `buildAC()` template:
1. Every boilerplate AC (AC-01..AC-05) now emits BOTH a `**Source:**` line AND a `**Verifies:**` line.
2. **Latent bug fixed**: previous template referenced an undefined `upToSpec` identifier (would throw `ReferenceError` on first new module). Replaced with depth-aware derivation: `const upToSpec = '../'.repeat(rel.split('/').length);`.

## Spec Lockstep

| File | From | To | Change |
|------|------|------|--------|
| `spec/27-spec-toolchain/20-fill-missing-acceptance-criteria.md` | 1.0.0 | 1.1.0 | Added AC-20-04 (Verifies emission contract) + AC-20-05 (depth-awareness contract); also added `**Verifies:**` to AC-20-01..03 |
| `spec/27-spec-toolchain/00-overview.md` | 2.71.0 | 2.72.0 | Lockstep banner |
| `spec/27-spec-toolchain/98-changelog.md` | 2.71.0 | 2.72.0 | Phase 153 Task #29a row |
| `spec/27-spec-toolchain/99-consistency-report.md` | 2.68.0 | 2.69.0 | Phase 153 Task #29a summary row |

## Validation

- `node linter-scripts/fill-missing-acceptance-criteria.cjs` → idempotent, 0 created, 86 skipped, exit 0 (AC-20-01 holds).
- `node linter-scripts/check-lockstep.cjs --strict` → **PASS** (87/87, 0 findings).
- `node linter-scripts/check-tree-health.cjs --strict` → **PASS** (168/168, 100/100).
- `python3 linter-scripts/check-version-parity.py` → **PRE-EXISTING FAILURE** on `spec/07-design-system` (§00=3.4.0 vs §98=1.7.0, phase-32 stamp). NOT introduced by this phase. Logged as Task #32.

## Lessons

1. **Root-cause fixes to scaffolders are higher-leverage than per-instance sweeps.** The 23 remaining boilerplate-template §97 files with missing `**Verifies:**` clauses (Task #31) are now strictly bounded — no NEW modules can recreate the gap. Per-instance backfill remains a discrete, finite task.
2. **Always check whether a scaffolder upstream is the originator of a mass-coverage gap before embarking on a per-instance sweep.** Phase 153 patched 1 file (spec/11) before realizing the scaffolder was the source. Future audit-v6 blind spots discovered via mass-coverage gaps SHOULD trace to upstream generators first.
3. **Latent bugs in scaffolders only surface on first new module.** The undefined `upToSpec` would have thrown `ReferenceError` the next time a new module folder was added. Fixed proactively.

## Open follow-ups

- **Task #31** still required: backfill `**Verifies:**` clauses in the 23 remaining boilerplate-template §97 files. Mechanical — same Python pattern as Phase 153 spec/11 fix, batch-applicable.
- **Task #29b** still relevant: fix `check-ai-confidence.py` to (a) include top-level `spec/NN-overview.md` files; (b) flag boilerplate-template §97 files missing `**Verifies:**` so this class of drift is caught automatically going forward.
- **Task #32 (NEW)**: investigate `spec/07-design-system` §00 v3.4.0 / §98 v1.7.0 version drift — pre-existing, surfaced today by `check-version-parity.py`. Phase-32 stamp suggests this has been failing for a long time; needs root-cause investigation (likely §00 was bumped without corresponding §98 entry).
