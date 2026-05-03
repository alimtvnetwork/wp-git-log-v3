# Phase 153 Task A18-fu1 #3 — spec/11 self-lift triplet (concurrency + parallelism + downstream-pin)

**Date:** 2026-05-03  **Module:** `spec/11-powershell-integration/`  **Pre:** 86 GOOD  **Expected post:** ≥91 EXCELLENT (LLM re-score deferred — gateway 402)

## Findings closed (audit-v7 cache `11-powershell-integration.json`)
1. **D3 HIGH** — Concurrency Race Condition in Shared Store → **AC-11** `[high]` (advisory lock + sibling Get-Process + Lesson #36 cross-ref to spec/13 AC-22 & spec/27 AC-T-28 R3)
2. **D2 MEDIUM** — Missing AC for Multi-Site Parallelism → **AC-12** `[medium]` (lifts `parallel-work-sync-output.md` to normative per Lesson #19)
3. **D5 LOW** — Unresolved External Script References → **AC-13** `[low]` (downstream-pin per Lesson #29 + spec/03 AC-11 + spec/22 AC-78)

## Pre-flight (Lesson #45)
spec/11 §97 = 11.5 KB pre-edit (78.5 KB headroom); §00 = 25 KB; §01 = 9 KB. Tier-1 sum = 51 KB ≪ 75 KB threshold → safe to ship.

## Lockstep
- §97 v1.3.0 → v1.4.0 (count 10→13)
- §00 spec-version 2.27.1 → 2.27.2
- §98 v1.3.1 → v1.4.0
- §99 v3.5.1 → v3.6.0

## Strict gates
lockstep 87/87 · tree-health 100/100 strict (168/168) · version-parity 74/74 · §99 freshness 81 stamped + 6 exempt + 0 unstamped — **all GREEN**. Pre-existing folder-refs drift in memo phase-153-task-A20-fu7 (`spec/22-pre-fu29/`) is unrelated and out of scope.

## Lesson reinforcement
Lesson #40 triplet pattern (D3 HIGH + D2 MED + D5 LOW closed in single phase) holds on integration-axis modules; combined with Lesson #37 (Lesson #19 + Lesson #36 co-application on integration-axis), this is the canonical close-out shape for the GOOD→EXCELLENT band lift on modules with §97 headroom > 70 KB.
