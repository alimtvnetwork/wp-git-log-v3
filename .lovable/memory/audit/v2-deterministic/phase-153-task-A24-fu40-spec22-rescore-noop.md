---
phase: 153
task: A24-fu40
date: 2026-05-03
status: CLOSED (mechanical no-op — re-score complete)
gates: lockstep 87/87 · tree-health 168/168 strict · version-parity 74/74 — all GREEN
---

# A24-fu40 — spec/22 v12 single-module re-score (post-fu29+fu39 archive splits)

## Outcome

- Pre-fu40 cache: total=90, files_used=3/37, bytes_used=120 KB.
- Post-fu40 (`--force` re-score): total=**86**, files_used=3/38, bytes_used=120 KB.
- Δ = **−4** (Lesson #45 noise band; same `3/N` tier-1 saturation).
- 0 findings surfaced.

## Diagnosis

The fu29+fu39 archive splits **succeeded structurally** (§98 100→37 KB, §99 58→15 KB), but the cache `bytes_used` stayed pinned at the 120 KB cap because **§97 alone is 71 KB** — when the walker tier-1 loads `{00,97,98,99}-*.md`:
- §00 (13 KB) + §97 (71 KB) = 84 KB
- + §98 (37 KB) = 121 KB → cap hit
- §99 (15 KB) crammed partial; tier-2 files (15-error-codes, 14-endpoint-examples, 18-schema.sql) never reached.

**§97 is the new dominant saturator.** Until §97 is thinned or split, no archive split on the other tier-1 files will move `files_used` above 3.

## Lesson reinforcement

- **Lesson #18 + #72 reapplied**: −4 movement is honest-baseline noise (no findings → no regression); do NOT roll back the fu29/fu39 archive splits to "force" a higher cache score. The archive splits remain valuable for human readers and for any future §97 thinning that frees walker headroom.
- **Lesson #65 limitation discovered**: archive-split pattern only frees walker headroom when the archived file is NOT dominated by a still-larger tier-1 sibling. For spec/22, §97 (71 KB) is 60% of the 120 KB cap by itself — splitting §98/§99 is necessary but not sufficient.

## Strict gates

All 3 strict gates GREEN: lockstep 87/87 · tree-health 168/168 strict · version-parity 74/74.

## Next

**fu41** queued: spec/22 §97 thinning or split. §97 has 79 ACs over 71 KB (~900 bytes/AC avg). Candidates for archive: AC-LEGACY-* rows (per AC-SAG-28 exemption), audit-history walker-pin teaser block (now duplicated in §00), or the 5+ deepened-AC blocks added across Phases 8/9/12/13 (could split into `97-acceptance-criteria-deepened.md` co-file).
