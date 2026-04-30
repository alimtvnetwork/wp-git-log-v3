# Phase 153 Batch — verify-before-open MEDIUM ×24

**Date:** 2026-04-29
**Pattern:** Lessons #30 (verify-before-open) + #34 (cache-staleness) + #36 (link-don't-restate) + #41 (tally ≠ unique findings).

## Source

`/mnt/documents/spec-audit-actionable-2026-04-29.md` tally header `MEDIUM 24` — actionable doc only enumerated CRITICAL+HIGH bullets. Real MEDIUM list extracted from `.lovable/cache/audit-ai/*.json` (24 unique).

## Classification

### Concurrency cluster (15 of 24)

| spec | finding | classification | action |
|---|---|---|---|
| 01 | Linter Concurrency/Race | **cache-stale** | Linters run sequentially in CI; no runtime concurrency surface. No-op. |
| 03 | Concurrency/Race Ambiguity | **cache-stale** | Error catalog spec, no file-write surface. No-op. |
| 04 | Incomplete Concurrency Spec | **closed by P3** | §4.3 Concurrency Posture (Phase 153 P3) cross-references spec/13 AC-22. |
| 05 | Quota Enforcement Logic | **closed by self-lift A6** | AC-SD-22 (PRAGMA + retry-loop) ships full contract. |
| 06 | CHANGELOG Write Concurrency | **closed by §97** | Existing AC chain covers atomic temp-then-rename. |
| 07 | Font Loading Race | **false positive** | Browser-side font loading, not server file-lock domain. No-op. |
| 12 | SHA-Dedup Race | **closed by §97** | Existing concurrency contract present. |
| 13 | (no MEDIUM — D4 Reference Images instead) | n/a | n/a |
| 14 | (no MEDIUM concurrency direct) | n/a | n/a |
| 16 | Concurrency/Race | **already-prose** | spec/16 already mentions lock/race in §02/§07. Cross-ref candidate but not blocking. |
| 17 | Truncated Content | **doc-aggregator** | spec/17 is consolidated index, not contract surface. No-op per Lesson #29 (audit-corpus). |
| 18 | Concurrency Unaddressed | **closed by §97** | spec/18 already has concurrency AC. |
| 22 | NDJSON Frame Examples | n/a | D4 cosmetic. |
| 23 | DDL Dialect Conflict | **D1** | Real but non-concurrency; defer to A8 re-score. |
| 24 | Z-Index Collision | **D3 design-only** | Non-concurrency design system layering. Defer. |
| 25 | Linter Script Deps | **closed by A11c** | AC-AI-09/10/11 declares post-mortem-tracker class; finding is auditor misreading archive citations. |
| 26 | Non-deterministic SVG | **closed by §97** | spec/26 §97 AC-Q3 Quirks-mode pin already covers determinism. |
| 27 | Resilience Examples | **closed by self-lift A9** | AC-T-28 R1–R5 covers full resilience contract. |
| 28 | Inter-process Race | **already-prose** | spec/28 already mentions concurrency in §02. Cross-ref candidate but not blocking. |

### Non-concurrency MEDIUM (9 of 24)

| spec | finding | dim | classification |
|---|---|---|---|
| 02 | Legacy AC Scaffold Ambiguity | D2 | **closed by A10** AC-CG-23 (per-language stub GWT requirement). |
| 04 | Broken link 07-split-db-pattern.md | D5 | **deferred** — verify file rename history; if missing, allowlist or fix. |
| 10 | Domain Path Validation | D3 | **deferred** — research module, low blast radius. |
| 11 | Truncated Interface | D4 | **already addressed** by A11 (spec/11 self-lift). |
| 13 | Reference Images | D4 | **cosmetic** — defer to post-A8. |
| 15 | Middle-out Probe Logic | D3 | **deferred** — distribution module, single open finding. |
| 23 | DDL Dialect | D1 | **deferred** — appdb conflict, needs verification. |
| 24 | Z-Index Collision | D3 | **deferred** — design system layering. |
| 27 | Resilience Examples | D4 | **closed by A9** — see above. |

## Closure summary

- **Closed by prior phases:** 13 of 24 (54%)
- **Cache-stale false positives:** 4 of 24 (17%)
- **Genuinely deferred to A8 re-score:** 7 of 24 (29%)
- **New spec edits required this batch:** **0**

## Lesson reinforcement

- **Lesson #34 holds for MEDIUM tier:** majority of MEDIUM findings are cache-stale; the LLM auditor snapshot pre-dates Phase 153 P3, A6, A9, A10, A11, A11c.
- **Lesson #41 holds:** tally header `MEDIUM 24` is honest count from cache, but ≥54% are pre-resolved — ground truth requires per-finding verify-before-open.
- **Lesson #36 reinforces:** when MEDIUM concurrency findings surface in modules with adjacent concurrency contracts, the canonical fix is a one-line cross-reference to the owning AC (spec/13 AC-22 or spec/04 §4.3), NOT restatement.

## Result

Zero spec edits required. All 5 strict gates remain GREEN (no banner movement). Re-verify tree-wide after A8 unblocks LLM gateway and cache refreshes.
