# Phase 153 Task #35-fu2 — Version-Parity Gate Close-Out (15 → 0 FAILs)

**Date:** 2026-04-29
**Status:** CLOSED
**Predecessor:** Phase 153 Task #35-fu (SemVer-max comparator fix)

---

## Summary

Closed the version-parity gate from 15 stamped FAILs → **0 FAILs (74/74 matches)** by mechanically backfilling 14 missing §98 rows for §00-ahead drifters and unifying the one inverse case (spec/15: §00 BEHIND §98) per the Task #32 SemVer-track unification pattern.

## Mechanical actions

### 14 §00-ahead drifters (script-driven)
Drove `/tmp/backfill_98_rows.py` — for each (§00 path, §00 version) pair, appended a dated changelog row with rationale "Phase 153 Task #35-fu2 — §98 backfill (parity gate close-out)" and refreshed the §00 h10 stamp to phase 153.

| # | Module | §00 → §98 lift |
|---|---|---|
| 1 | spec/23-app-database | 4.0.2 → 4.0.3 |
| 2 | spec/25-app-issues/01-phase-2-git-logs-audit | 1.4.0 → 1.4.1 |
| 3 | spec/18-wp-plugin-how-to/02-enums-and-coding-style | 1.1.1 → 1.1.2 |
| 4 | spec/03-error-manage/03-error-code-registry | 3.2.2 → 3.2.3 |
| 5 | spec/03-error-manage/03-error-code-registry/08-linter-scripts | 1.3.0 → 1.3.1 |
| 6 | spec/03-error-manage/02-error-architecture/04-error-modal | 3.3.0 → 3.3.1 |
| 7 | spec/03-error-manage/02-error-architecture/07-logging-and-diagnostics | 3.3.0 → 3.3.1 |
| 8 | spec/02-coding-guidelines/01-cross-language | 4.1.0 → 4.1.1 |
| 9 | spec/02-coding-guidelines/03-golang | 4.1.0 → 4.1.1 |
| 10 | spec/02-coding-guidelines/04-php | 4.2.0 → 4.2.1 |
| 11 | spec/02-coding-guidelines/07-csharp | 4.1.0 → 4.1.2 |
| 12 | spec/02-coding-guidelines/11-security | 2.3.0 → 2.3.1 |
| 13 | spec/02-coding-guidelines/03-golang/01-enum-specification | 3.3.2 → 3.3.3 |
| 14 | spec/02-coding-guidelines/01-cross-language/16-static-analysis | 4.1.0 → 4.1.1 |

### 1 inverse case (Task #32 unification pattern)
**spec/15-distribution-and-runner**: §00=1.1.0 was BEHIND §98 SemVer-max=2.0.0. Phase 16d-i had shipped a v2.0.0 §97 depth pass (5→20 ACs) without bumping §00; Phase 55 then shipped a v1.1.0 lever bump. Resolution: §00 1.1.0 → **2.1.0** (consolidates Phase 16d-i depth + Phase 55 lever); §98 banner 2.1.0 → 2.1.1; §99 banner 2.1.0 → 2.1.1; new §98 row + §99 audit row document the SemVer-track unification per Lesson #25.

## Gates (all GREEN)

| Gate | Before | After |
|---|---|---|
| `check-version-parity.py` | 15 stamped FAILs (out of 74) | **0 FAILs · 74/74 matches** |
| `check-lockstep.cjs` | 87/87 | 87/87 (after spec/15 §98 Updated refresh) |
| `check-tree-health.cjs --strict` | 168/168 | 168/168 |

## Lessons confirmed

- **#25 (Task #32 precedent)** — spec/15's BEHIND case proves dual-track SemVer drift accumulates silently: a v2.0.0 §97 depth pass and a v1.1.0 §00 lever shipped in adjacent phases without ever reconciling. The Task #32 unification pattern (renumber §00 to encompass both tracks, document in §99) generalises cleanly to the inverse drift direction.
- **#28** — The 14 mechanical backfills validate Lesson #28's claim that the bulk of "drift" was a comparator artifact: once `latest_release()` returned SemVer-max (Task #35-fu), the remaining drifts were genuine but trivially mechanical (mostly `+0.0.1` patches that had been authored as §00 banner bumps but never as §98 rows).
- **Cost of per-case mechanical work**: 14 modules × 1 §98 append + 1 stamp refresh = ~30 seconds via script driver. Worth scripting even at this volume.

## Files changed

- 14 × `98-changelog.md` (new row per §00 banner version)
- 14 × `00-overview.md` (h10 stamp 22/26/30/32 → 153)
- spec/15-distribution-and-runner/{00-overview,98-changelog,99-consistency-report}.md (banner unification + §98/§99 audit rows)

## Next-up after this

Version-parity gate is now at steady-state. Future drifts will be single-case mechanical close-outs (no class-wide sweep needed). The remaining backlog reverts to A11c (spec/25 crypto fix), spec/13 lift verification, and the deferred LLM re-scores.
