# Phase 153 P2 — Dimension forensics for 8 sub-90 modules

**Date:** 2026-05-05 · **Status:** CLOSED (offline analysis, no spec edits)
**Mode:** No-Questions (Prompt 02, task 2/40)

## Mean-stuck root cause

Mean = **91.8 / 100 (N=23)** unchanged across A24-fu4 → A18-impl-3 → P1.
P1 confirmed gateway 402 prevents fresh re-scores; therefore mean cannot
move via re-scoring alone. **But** dimension forensics shows the cap is
NOT a structural ceiling — it's an **un-funded D3/D5 contract gap** in
the 8 sub-90 modules.

## Forensic table

| Module | Tot | wTot | Cap | D1 | D2 | D3 | D4 | D5 | Status | Best lever (headroom) |
|---|---:|---:|---:|---:|---:|---:|---:|---:|---|---|
| 01-spec-authoring-guide | 89 | 89.3 | 95 | 18 | 19 | **15** | 17 | 20 | LIFTABLE | D3 (×0.8 → 4.0) |
| 04-database-conventions | 89 | 88.8 | 100 | 18 | 19 | 17 | 18 | 15 | LIFTABLE | D3 (×1.2 → 3.6) |
| 05-split-db-architecture | 89 | 88.8 | 100 | 18 | 19 | 17 | 18 | 15 | LIFTABLE | D3 (×1.2 → 3.6) |
| 12-cicd-pipeline-workflows | 84 | 84.0 | 95 | 18 | 17 | 16 | 17 | 16 | LIFTABLE | D5 (×1.11 → 4.4) |
| 17-consolidated-guidelines | 88 | 87.9 | 95 | 18 | 19 | 17 | 19 | **15** | LIFTABLE | **D5 (×1.0 → 5.0)** |
| 18-wp-plugin-how-to | 85 | 84.9 | 95 | 18 | 19 | 17 | 16 | **15** | LIFTABLE | **D5 (×1.0 → 5.0)** |
| 22-git-logs-v2 | 87 | 87.4 | 100 | 18 | 20 | 17 | **15** | 14 | LIFTABLE | D4 (×0.8 → 4.0) |
| 27-spec-toolchain | 88 | 87.9 | 100 | 18 | 18 | 17 | 17 | 18 | LIFTABLE | D4 (×1.18 → 3.5) |

## Findings

1. **Zero modules are CAPPED.** All 8 sub-90 modules have `total < axis_cap`.
   The "stuck mean" is NOT a rubric ceiling — it's measurable contract gaps.
2. **D3 (Edge/Error)** is the dominant lever for content-spec modules (01, 04, 05).
3. **D5 (Cross-Module Coherence)** is the dominant lever for index/integration
   modules (12, 17, 18) — Lesson #36/#37 (link-don't-restate + Subfolder
   Delegation Map) directly addresses this.
4. **D4 (Robustness)** is the dominant lever for spec/22 + spec/27 — needs
   atomic-write / locking / retry contract surface.

## Sequenced lift plan (offline-authorable, gateway-deferred verification)

| Phase | Module | Current | Target | Lever | Pattern |
|---|---|---:|---:|---|---|
| **P3** | 17-consolidated-guidelines | 88 | ≥93 | D5+5 | Subfolder Delegation Map (Lesson #21/#37) |
| **P4** | 18-wp-plugin-how-to | 85 | ≥90 | D5+5 | Cross-module link-don't-restate (Lesson #36) |
| **P5** | 12-cicd-pipeline-workflows | 84 | ≥89 | D5+4, D2+3 | A24-fu4 pattern (already applied — needs re-score) |
| **P6** | 22-git-logs-v2 | 87 | ≥92 | D4+5 | Atomic-write + locking AC (Lesson #19 mirror) |
| **P7** | 04 + 05 + 27 | 88-89 | ≥92 | D3/D4 | Edge-case + concurrency ACs (Lesson #36 cross-ref) |
| **P8** | 01 | 89 | ≥93 | D3+4 | Edge-case ACs for spec-authoring failure modes |

**All P3–P8 are AUTHORABLE OFFLINE.** Re-score verification is
gateway-gated (defer per Lesson #20) — the contract work itself stands.

## Lesson #80 (codified for §98)

**"Mean-stuck" diagnosis MUST distinguish capped (rubric ceiling) from
liftable (contract gap).** P1 incorrectly assumed cache staleness; the
real signal lives in `total < axis_cap` (LIFTABLE) vs `total >= axis_cap`
(CAPPED). Future "mean-stuck" investigations should run the LIFTABLE/CAPPED
classification FIRST, before any re-score attempt — a 30-line offline
script provides the diagnosis without consuming any gateway budget.
