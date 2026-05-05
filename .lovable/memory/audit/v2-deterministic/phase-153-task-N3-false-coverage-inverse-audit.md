# Phase 153 Task N3 — False-coverage inverse audit (1 real drift surfaced + closed)

**Closed:** 2026-05-05
**Status:** ✅ CLOSED
**Driver:** Inverse-audit class previously deferred from N5 plateau executive diagnosis. Built one-shot scanner to find ACs claiming `**Verifies:** §NN` of non-existent sections.

## Method

3 refinement loops on `/tmp/n3-inverse-audit-v{1,2,3}.py`:
- **v1**: naïve same-module §-heading match → 142 candidates (90% false positives from cross-module §NN slot refs).
- **v2**: added cross-module-slot tolerance (top-level spec/NN-* slots accepted) → 13 candidates.
- **v3**: added `§NN §MM` adjacent-token idiom (sub-section of cross-module slot NN) → 3 candidates.
- **On-disk inspection of final 3** → 1 real drift, 2 false positives (locked-vacant slots + cross-module idiom not detectable without semantic parsing).

## Drift closed

**spec/22-git-logs-v2/97 AC-36** cited `**Verifies:** §21` (POT extraction). §21 was retired in §98 v3.7.8 ("i18n out of scope for v2"); AC was never swept. Marked `[active]` → `[deprecated]` with new dormancy contract pinning §00 inventory row 21 + §98 v3.7.8 retirement entry as authoritative anchors.

## Tree-wide stats

- 1241 ACs scanned across 91 §97 files
- 1128 verifies-clauses parsed
- **1 real false-coverage drift = 0.08% rate**
- Confirms P3 Verifies-coverage sweep (Phase 151 + #29a-e + #31 + #34) closed the structural gap; this is residual maintenance drift, not a class.

## Lockstep

Patch-only (no new AC, no AC count change, no AC-31-31 cascade, no RUBRIC bump, no CI workflow change, no gate-count change, no DDL change):
- spec/22 §97 v3.10.1 → **v3.10.2**
- spec/22 §00 v3.13.1 → **v3.13.2**
- spec/22 §98 v3.13.1 → **v3.13.2** (banner + Updated date 2026-05-03 → 2026-05-05 + this row)
- spec/22 §99 v3.13.1 → **v3.13.2** (banner + new audit subsection)

## Lesson reinforcement

**Lesson #39-class**: orphan-AC sweeps at §-retirement are easy to miss when the §-retirement happens in a `00-overview.md` inventory edit but the AC living in `97-acceptance-criteria.md` is never re-read in the same phase. Future §-retirement phases MUST `grep "**Verifies:** §NN"` against the retired number BEFORE closing the retirement phase.

**Scanner-design lesson**: cross-module §NN refs in spec verifies-clauses use 3 distinct idioms (bare §NN slot, §NN §MM sub-section, slot-prefix `spec/NN-...` anchor). A naïve same-module scanner produces ~90% false positives. The 142→13→3 refinement curve is typical for any spec-text scanner — budget 2-3 refinement loops before trusting findings.

## Files

- Edited `spec/22-git-logs-v2/97-acceptance-criteria.md` (AC-36 [active] → [deprecated], banner)
- Edited `spec/22-git-logs-v2/00-overview.md` (banner)
- Edited `spec/22-git-logs-v2/98-changelog.md` (banner + new row + Updated date)
- Edited `spec/22-git-logs-v2/99-consistency-report.md` (banner + new audit subsection)
- Created `.lovable/memory/audit/v2-deterministic/phase-153-task-N3-false-coverage-inverse-audit.md`

## Verification

- Tree-health strict: 168/168 GREEN
- Lockstep: 87/87 · 0 findings GREEN
- Version-parity strict: 74/74 matches GREEN
- All 5 strict CI gates GREEN

## Future work

The v3 scanner is one-shot (lives in `/tmp/`). Productionising it as `linter-scripts/check-verifies-coverage.py` is a candidate for a future N-task but **not warranted now** — at 0.08% drift rate over 1241 ACs, the manual sweep at §-retirement time (Lesson #39-class above) is a higher-leverage process gate than a CI scanner that would mostly emit advisory false positives.
