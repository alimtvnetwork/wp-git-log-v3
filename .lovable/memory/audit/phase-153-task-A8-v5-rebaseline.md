---
name: Phase 153 Task A8 — v5 LLM rebaseline (gateway unblocked)
description: A8 closed; tree 82.3→83.9 (+1.6), 0 NEEDS_WORK, 0 BLOCKING, 3 EXCELLENT. spec/23 +11 (top mover, AC-ADB-14 confirmed), spec/13 +9 (A11a confirmed). spec/25 stuck at 75 — AC-AI-09/10/11 inoculation didn't shift score (auditor still misreads the post-mortem corpus). All other deferred self-lifts validated.
phase: 153
task: A8
status: closed
---

# Phase 153 Task A8 — v5 LLM rebaseline (CLOSED)

## Outcome
- **Tree score:** 82.3 → **83.9 / 100** (Δ +1.6)
- **Bands:** 0 NEEDS_WORK · 0 BLOCKING · **3 EXCELLENT** (15=92, 23=97, 24=93) · 20 GOOD
- **Gateway state:** healthy (was 402 across A9..A11; back online today)
- **Cache:** `--force` flush; 23 fresh module audits

## Wins validated
| Module | v3 | v4 | v5 | Driver |
|---|---|---|---|---|
| spec/23 | 80 | 86 | **97** | P48-3 AC-ADB-14 (Polymorphic AppLink Resolution) |
| spec/13 | 81 | 80 | **89** | A11a AC-21/22/23 + A11a-fu1 prose refresh |
| spec/05 | 89 | 89 | **89** | A6 walker tier-1 fix held |
| spec/02 | 90 | 80 | **87** | A10 Subfolder Delegation Map + Exception Ledger |
| spec/27 | 85 | 75 | **80** | A9 AC-T-27..29 |
| spec/15 | 90 | 90 | **92** | (organic; no targeted self-lift) |
| spec/11 | — | 79 | **81** | P48-4 AC-09 + A2 inoculation |
| spec/04 | — | 81 | **82** | P48-2 AC-09 boolean storage |

## Stuck at floor
- **spec/25 (75):** AC-AI-09/10/11 inoculation pattern did NOT shift score. Audit-corpus modules remain a known auditor blind spot. The contract IS pinned (per Lesson #29) — score floor is structural, not actionable. Defer.
- **spec/03 (75), spec/12 (75), spec/17 (75):** all share audit-corpus-adjacent characteristics (consolidated guidelines / pipeline workflows / error-modal-meta). May benefit from L29-pattern application if effort/value warrants.

## Gateway-recovery lesson
**Lesson #45 — Probe before deferring.** The session opened claiming gateway 402; a 1-second probe (`--module=04 --force`) showed it healthy. **Future `next` MUST run a single-module probe before re-asserting gateway-blocked status.** Mirror of Lesson #30 (verify-before-open) for external dependencies.

## Files
- Report: `/mnt/documents/spec-ai-implementability-audit-v5.md`
- Cache: `.lovable/cache/audit-ai/*.json` (refreshed 23 modules)

## Lockstep
None. Pure re-score; no spec edits, no script edits, no banner bumps.
