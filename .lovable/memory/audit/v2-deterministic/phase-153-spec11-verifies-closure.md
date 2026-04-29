# Phase 153 — spec/11 Verifies-coverage Closure

**Date:** 2026-04-29  
**Driver:** Task #28 — close real P3 gap discovered in Phase 152 stamp-refresh attempt.

## Context

Phase 151 declared P3 (Verifies-coverage) CLOSED tree-wide based on the 11
modules the sweep targeted. Phase 152 (audit-v6 baseline) inherited that claim
without re-validating boilerplate-template modules, because
`linter-scripts/check-ai-confidence.py` only iterates module subdirectories
and treats template-style §97 files as low-priority noise.

Investigation during the Task #25 stamp-refresh found three modules whose §97
files still used the boilerplate `**Source:**` pattern instead of
`**Verifies:**`:

- `spec/00-overview.md` companion `spec/97-acceptance-criteria.md` — **already had 8/8 Verifies clauses** (false positive in the discovery pass).
- `spec/23-app-database/97-acceptance-criteria.md` — **already had 10/10 Verifies clauses** (false positive).
- `spec/11-powershell-integration/97-acceptance-criteria.md` — **0/8 Verifies clauses** (real gap).

Net: only `spec/11` needed work.

## Change

Added `**Verifies:**` lines to all 8 boilerplate ACs (AC-01..AC-08) in
`spec/11-powershell-integration/97-acceptance-criteria.md`. Each clause is
anchored to the §00 baseline, a sibling spec section, or the relevant linter
script — matching the authoring rule in
`mem://process/verifies-clause-authoring`.

## Lockstep bumps

| File | From | To |
|------|------|------|
| `spec/11/00-overview.md` (Spec Version) | 2.26.0 | 2.26.1 |
| `spec/11/97-acceptance-criteria.md` | 1.0.0 | 1.1.0 |
| `spec/11/98-changelog.md` | 1.1.0 | 1.2.0 |
| `spec/11/99-consistency-report.md` | 3.4.0 | 3.4.1 |

All four files now stamped `2026-04-29`.

## Validation

- `node linter-scripts/check-lockstep.cjs --strict` → **PASS** (0 findings, 87/87 files).
- `node linter-scripts/check-tree-health.cjs --strict` → **PASS** (168/168 strict, 100/100, all 56 modules full marks).

## Lessons

1. **Discovery passes that count `Verifies:` per file should be re-run on the actual file, not inferred from prior phase claims.** The original Task #28 framing (~26 ACs across 3 modules) was wrong; reality was 8 ACs in 1 module.
2. **Boilerplate-template §97 files (AC-01..AC-08 pattern) are an audit-v6 blind spot.** They don't appear in `check-ai-confidence.py`'s scoring because the rubric treats them as scaffolding. Future audits should add a "boilerplate Verifies-coverage" lint, OR Task #29's fix to the script.
3. **`**Source:**` and `**Verifies:**` are NOT interchangeable** — the Verifies-coverage gate looks for the literal `**Verifies:**` token. Boilerplate scaffolders should emit `**Verifies:**` going forward.

## Open follow-ups

- Task #29 still relevant: fix `check-ai-confidence.py` to also scan top-level `spec/NN-overview.md` AND boilerplate-template §97 files so future drift is caught automatically.
- Audit other boilerplate-template §97 files tree-wide for the same `**Source:**`-only pattern (precedent: `spec/03-error-manage/03-error-code-registry/97-acceptance-criteria.md` shown in context still uses `**Source:**`).


---

**Related lessons:** see [`mem://process/phase-153-lessons`](../../../memory/process/phase-153-lessons.md) for the consolidated Phase 153 contributor rules (#11–#37).
