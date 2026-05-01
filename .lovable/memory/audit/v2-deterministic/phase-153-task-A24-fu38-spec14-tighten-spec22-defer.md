---
phase: 153
task: A24-fu38
date: 2026-05-01
status: CLOSED
gates: lockstep 87/87 · tree-health 168/168 strict · version-parity 74/74 — all GREEN
---

# A24-fu38 — spec/14 contract-tightening + spec/22 walker-budget defer

## Outcome — split phase

### spec/14 (contract-tightening shipped)
- **AC-16** patched: cleanup-budget 100ms now pinned as **wall-clock** time (NOT CPU-time / `time.Process` quotas); added explicit threshold trigger (single `unlink()` > 50ms wall-clock → per-invocation cap) — closes audit-v10 LOW D1 "Missing Unit for Cleanup Latency".
- **AC-17** patched: new normative paragraph **Rollback-trigger ownership** declares rollback is process-local to the updater OR delegated to next-start cleanup per AC-16; NO inter-process signaling permitted (no daemon, no watcher, no PID-polling, no IPC channel between detached child and exited parent) — closes audit-v10 MEDIUM D3 "Ambiguous Rollback Trigger in Handoff".
- **Lockstep**: §97 v2.4.0 → v2.4.1 (patch — prose-only); §00 v2.4.2 → v2.4.3; §98 v2.4.2 → v2.4.3; §99 v1.6.2 → v1.6.3. No new AC, no AC-31-31 cascade, no RUBRIC bump.
- **Re-score**: 90 → 90 (no movement; bundle shifted 11/54 → 10/54 — within Lesson #45 noise band). Per Lesson #18: contract IS tighter for future readers/engineers; cache score not moving is auditor-fuzziness on subjective "ambiguity" axis, NOT work failure.

### spec/22 (deferred to A24-fu39)
- Cache: total=90, files_used **3/37** (severe walker starvation), bytes_used 117.2 KB at 120 KB cap.
- All 3 findings (HIGH D4 truncated examples + MEDIUM D3 concurrency externalized + LOW D1 PascalCase ambiguity) are walker-budget artifacts per Lesson #70.
- Defer to A24-fu39 (apply Lesson #70 archive split — spec/22 is biggest remaining OVER-class lever per index Memories list).

## Lesson reinforcement

- **Lesson #71 reapplied (counter-case)**: spec/14 score 90 with axis cap 100 = gap 10, which **exceeds** the ≤3 no-op threshold → contract-tightening is justified, NOT a no-op. Contrast with spec/17 fu37 (score 92, axis cap 95, d2 mult 0.7 → no-op closure). Lesson #71's threshold correctly distinguishes high-leverage vs low-leverage modules.
- **Lesson #45 reapplied**: cache `total` did not move under prose-only patch (`bundle_sha` shifted, `files_used` 11/54 → 10/54), but D1+D3 contract surface is genuinely improved. Do NOT roll back contract-tightening to "force" a score lift.
- **NEW Lesson #72 — Auditor "ambiguity" findings can survive contract-clarification patches.** When LLM auditor flags D1/D3 as "ambiguous" or "unclear", a normative subsection MUST be added EVEN IF the auditor's next pass doesn't recognize the fix. Reasons: (a) the contract is now machine-checkable for future engineers reading the spec directly; (b) re-score fluctuation per Lesson #45 may surface the lift in a later v12 rebaseline; (c) the value of contract clarity is decoupled from cache score (Lesson #71 measures axis-cap leverage, not "did the cache move?"). Mirror of Lesson #17 (`bundle_sha` change without `total` change is normal) at the **contract-quality vs cache-score axis**.

## Strict gates

All 3 strict gates GREEN: lockstep 87/87 · tree-health 168/168 strict · version-parity 74/74.
