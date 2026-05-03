---
phase: 153
task: A24-fu39
date: 2026-05-03
status: CLOSED
gates: lockstep 87/87 · tree-health 168/168 strict · version-parity 74/74 — all GREEN
---

# A24-fu39 — spec/22 §99 archive split

## Outcome

Mirror of A24-fu29 (§98 archive split) applied to §99. Walker headroom freed for spec/22.

- **NEW** `spec/22-git-logs-v2/_archive/99-consistency-report-pre-v3.12.0.md` — 19 historical audit blocks (v2.8.7 Audit → Phase P7b / v3.9.14 Audit), 308 lines / ~46 KB.
- §99 live size: ~58 KB → **15 KB (-74%)**.
- Combined with fu29 (§98 -63 KB), total spec/22 tier1 walker bundle reduction since fu20 baseline (239 KB) is now **~106 KB (-44%)**.

### Lockstep
- §00 v3.12.0 → **v3.13.0** (banner-only — archive pointer).
- §98 v3.12.0 → **v3.13.0** (new archive pointer + new row).
- §99 v3.12.0 → **v3.13.0** (split + new audit block).
- §97 NOT bumped (no AC change).
- No CI workflow change · no RUBRIC bump · no AC-31-31 cascade · no gate-count change · no DDL change · no schema bump · no new AC.

### Re-score expectation
Pre-A24-fu39 cache: total=90, files_used=3/37, bytes_used=120 KB (saturated). With ~46 KB freed in §99, walker should fit 6+/37 files in budget on next re-score (15-error-codes, 14-endpoint-examples, 18-schema.sql, 28-example-github-actions). Expected lift: 90 → 92+. Per Lessons #18/#45/#72, score may not move monotonically — value is structural (walker visibility) regardless of cache delta.

## Lesson reinforcement

- **Lesson #65 (archive-split pattern)** — third-instance application. fu28=spec/27 §98, fu29=spec/22 §98, fu39=spec/22 §99. Pattern is now stable across both file-classes (§98 changelog + §99 consistency report) and both modules.
- **Lesson #70 (walker-budget starvation)** — second mechanical close-out. Diagnosed `files_used: 3/37` at 120 KB cap as walker starvation, applied archive split (NOT contract editing) as the leverage move.

## Strict gates

All 3 strict gates GREEN: lockstep 87/87 · tree-health 168/168 strict · version-parity 74/74.
