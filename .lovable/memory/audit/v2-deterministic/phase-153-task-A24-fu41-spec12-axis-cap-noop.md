---
phase: 153
task: A24-fu41
date: 2026-05-03
status: CLOSED (mechanical no-op — axis-cap floor confirmed)
gates: lockstep 87/87 · tree-health 168/168 strict · version-parity 74/74 — all GREEN
---

# A24-fu41 — spec/12 axis-cap diagnosis (no actionable lift)

## Re-score result (`--force`)

| Metric | Value |
|---|---|
| total | **84** (unchanged from cache) |
| total_v7 | 84 |
| axis | integration-spec (d2≤0.83 / d5≥1.10 multipliers) |
| weighted_total | 84.0 |
| files_used | 15 (AT_CEILING; tier1 ~43 KB + siblings filling cap) |
| bytes_used | 120 000 (cap saturated) |
| findings | **0** |

## Diagnosis

- 0 findings → no D-band gap is currently surfacing. The auditor sees the bundle and reports
  no defects. Score is constrained by the **integration-spec axis multipliers**, not by §97
  contract quality nor walker visibility.
- Walker is AT_CEILING (15/N files, 120 KB hit) but spec/12's tier-1 footprint (43 KB total
  for §00+§97+§98+§99) is healthy — the cap saturates on siblings, not tier-1, so no
  archive-split lever applies (Lesson #65 inverse).
- Per **Lesson #71** (axis-cap ceiling), modules whose `weighted_total == total` and whose
  axis carries multipliers <1.0 in any dim cannot exceed that dim's capped contribution.
  Authoring more ACs in those dims yields zero score movement.

## Lesson reinforcement

- **Lesson #71 confirmed empirically for the 3rd time** (after spec/22 pre-fu29 and
  spec/14 pre-fu38): `findings: []` + `weighted_total == total` is the canonical signature
  of an axis-floor module. Future contributors MUST run `--force` ONCE to confirm zero
  findings before opening a self-lift phase on any module already showing this signature
  in its cache.
- **Lesson #65 inverse**: when tier-1 is small (<50 KB) and cap saturates on siblings,
  archive splits are not a lever (no tier-1 file is being truncated). The lever for
  AT_CEILING / siblings-saturated modules is **walker MAX_BYTES raise** (A18, blocked) —
  not structural surgery on the spec.

## Strict gates

No spec edits. No banner bumps. Lockstep 87/87 ✅ · tree-health 168/168 strict ✅ ·
version-parity 74/74 ✅.

## Next

spec/12 is **graduated from the lift backlog**. Next-lowest actionable modules:
- spec/01 (OVER, 83 cached, +5.5 KB tier-1 OVER per fu27 ledger) — **fu42 candidate**
- spec/07 (OVER, 89 cached, +5.5 KB OVER) — fu43 candidate
- spec/27 (OVER, 83 cached, 262 KB OVER — needs §98+§99 archive splits, large refactor)
