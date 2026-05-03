# Phase 153 · Task A18-fu1 #5 — spec/04 walker-cap structural-pin + SQLite INTEGER mandate

**Date:** 2026-05-03
**Module:** `spec/04-database-conventions/` (cached score 89 GOOD; axis: normative-contract).
**Closes:** audit-v7 cache findings HIGH D5 + MEDIUM D3 + LOW D1.

## Findings & resolution

| Sev | Dim | Finding | Resolution |
|-----|-----|---------|------------|
| HIGH | D5 | "Truncated Relationship Diagram File (136KB cap)" | **Walker-cap artifact** — file is 15.8 KB on disk (`wc -c`); structural-pinned in AC-17 per Lesson #47 |
| MEDIUM | D3 | "SQLite Concurrency Logic Externalized" | Already canonically closed at AC-13 + §02 §4.3 cross-ref to spec/13 AC-22 (Lesson #36); structural-pinned in AC-17 |
| LOW | D1 | "Boolean Type Ambiguity in SQLite" | **Tightened §02-schema-design §2.1.1**: `INTEGER` is now MANDATORY; `BOOLEAN` keyword FORBIDDEN (alias-trap rationale: declared `BOOLEAN` has `NUMERIC` storage affinity, false signal for ORMs/drivers) |

## Lockstep

| File | Pre | Post |
|------|-----|------|
| §97 | 1.5.0 | 1.6.0 (AC count 16 → 17, new AC-17) |
| §00 | 3.7.0 | 3.8.0 |
| §02-schema-design | 3.4.1 | 3.4.2 |
| §98 | 3.7.0 | 3.8.0 (new row) |
| §99 | 3.9.0 | 3.9.1 (added missing `Updated:` field for lockstep) |

## Gates

- lockstep 87/87 · tree-health 168/168 strict · version-parity 74/74 · §99 freshness 81+6+0 — ALL GREEN.
- LLM re-score deferred per Lesson #20 (gateway 402 active at session open).

## Lessons reinforced

- **#34** cache-staleness: verify `wc -c` before allocating a file-split phase.
- **#47** auditor self-blindness: byte-cap artifact pattern identified again.
- **#51** structural-pin AC pattern: 4th instance (spec/02 AC-CG-24 + spec/25 AC-AI-16 + spec/04 AC-13 + spec/04 AC-17).
