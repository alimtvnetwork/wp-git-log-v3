# Phase 153 Task A22-fu1 — spec/05 AC-SD-27 ATTEMPTED → REVERTED (3rd Lesson #45 reinforcement)

**Date:** 2026-05-03
**Trigger:** F-03b cache showed spec/05 = 82 (lowest module). v5 surfaced 3 findings; #2 (D1 MEDIUM "Schema Discrepancy: Registry Table Naming `Database` vs `DbRegistry`") looked like a productive Lesson #29 multi-tier pin candidate (mirror of AC-AI-09/10/11 + AC-SC-23).

## What happened

1. Verified all 3 findings on disk: #1 D5 (cross-module AC-CL-* link) is already absorbed by AC-SD-24; #3 D4 (truncation of `03-database-flow-diagrams.md`) is harness-artifact (file is 357 lines, 8 sections, complete on disk); #2 D1 is a real auditor false-positive — `Database` (root tier) and `DbRegistry` (per-app tier) coexist intentionally per AC-SD-03.
2. Drafted **AC-SD-27** `[medium]` (~10 lines) following Lesson #29 multi-tier pin pattern; inserted between AC-SD-25 and AC-SD-26.
3. **Pre-flight saturation gate fired** post-insert: `wc -c §97 + §00 + §01-fundamentals` = **79,970 bytes** (4663 over the 75,000-byte threshold codified in §98 v4.4.1 from A23).
4. REVERTED AC-SD-27 from §97 BEFORE running re-score. §97 restored to v4.4.1 size (39,208 bytes, AC count 26 unchanged).

## Why this matters

A23 (2026-04-30) attempted a similar lift with AC-SD-27 + AC-SD-28 and regressed **89 → 82 (−7)** because the post-edit bundle (§97 + §00 + §01-fundamentals + §98 + §99 ≈ 103 KB) blew past the 90 KB walker cap, evicting `01-fundamentals.md` and breaking the D1/D4/D5 evidence the auditor previously had. A22-fu1 is the 3rd attempt in 4 days to lift this module via in-§97 content and the 3rd null-result/revert.

## Lesson #45 graduated

From "advisory" to **"MANDATORY pre-flight gate"** for spec/05 specifically:

> BEFORE drafting any AC for spec/05, contributors MUST run:
> ```
> wc -c spec/05-split-db-architecture/{97-acceptance-criteria.md,00-overview.md,01-fundamentals.md} | tail -1
> ```
> and assert the sum is < 75,000. If ≥ 75,000, the module is **saturation-locked** and no in-§97 content can lift the score.

spec/05's score floor of **82** is a structural ceiling until one of:
- (a) walker cap raised above 90 KB (deferred — A12 LLM gateway redesign)
- (b) §97 sub-extraction RUBRIC AC authored (untried)
- (c) §01-fundamentals.md trimmed (high-risk — would change normative DDL surface)

**Future `next` for spec/05 lift MUST first check the saturation gate, then either pursue (b)/(c) or skip the module.**

## Lockstep

| File | Before | After |
|---|---|---|
| §97 | v4.4.1 (39,208 b) | v4.4.1 (39,208 b) — no content change |
| §00 | v4.4.1 | **v4.4.3** (patch — null-result documentation; Updated 2026-05-03) |
| §98 | v4.4.2 | **v4.4.3** + new release row + Updated 2026-05-03 |
| §99 | v4.1.1 | **v4.1.3** (patch + Generated 2026-05-03) |

## Gates

- Lockstep 87/87 strict — GREEN
- Tree-health 168/168 strict — GREEN
- Version-parity 74/74 — GREEN
- §99 freshness 81+6+0 — GREEN

## Recommendation

For next `next` cycle, **skip spec/05 entirely** until A12 unblocks walker-cap raise or someone authors the §97-sub-extraction RUBRIC AC. Move to spec/12 / spec/27 / spec/18 / spec/11 — all have headroom under saturation threshold.
