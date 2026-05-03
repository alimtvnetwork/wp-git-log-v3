# Phase 153 Task A18-fu2 — AC-34-14 codifies 140 KB cap + dynamic truncation marker

**Date:** 2026-05-03  
**Status:** CLOSED  
**Driver:** Spec-vs-code drift — `linter-scripts/audit-ai-implementability.py:45` cited "AC-34-14" but the AC did not exist in `spec/27-spec-toolchain/34-audit-ai-implementability.md`.

## What changed

1. **slot 34 (`spec/27-spec-toolchain/34-audit-ai-implementability.md`)** v1.4.0 → **v1.5.0**: added **AC-34-14** `[critical]` codifying:
   - `MAX_BYTES = 140_000` (raised from 120 KB at A18-full).
   - Truncation marker MUST interpolate `{MAX_BYTES//1024}KB` dynamically (no hard-coded literals → closes Lesson #77 LLM-fabrication class).
   - Source-line comment at `audit-ai-implementability.py:45` MUST cite both AC-34-13 + AC-34-14.
   - Future raise above 140 KB requires fresh live-probe under canonical `User-Agent: lovable-spec-audit/1.0`.
   - Tier-1 priority (AC-34-09) preserved.
   - AC-34-13 marked superseded (retained as historical contract for the 120 KB intermediate step).
2. **§97** v2.9.0 → **v2.10.0**: Slot Delegation Map row for slot 34 + AC Family Prefix Index updated (`AC-34-09..14` / count `≥14`).
3. **§00** v2.87.0 → **v2.88.0** (banner only).
4. **§98** v2.87.0 → **v2.88.0** (this row).
5. **§99** v2.84.0 → **v2.85.0** (banner only + audit row).

## Why now

- A18-fu2 was selected over A24-fu45 (spec/05 self-lift) because the gateway is returning HTTP 402 again — single-module re-scores are blocked. Codified work that does not need the LLM is the right next move (Lesson #20 + Lesson #34).
- Closes a real spec-vs-code drift the line-45 comment created at A18-full.

## Validation

| Gate | Result |
|---|---|
| `check-lockstep.cjs` | 87/87 pass · 0 findings |
| `check-tree-health.cjs --strict` | 168/168 strict (all 56 modules full marks) |
| `check-version-parity.py --strict` | 74/74 matches · 0 mismatches |
| `check-99-summary-freshness.py --strict-position` | 81 stamped + 6 exempt + 0 unstamped |

All 4 strict gates GREEN.

## No-op surfaces

- No script change (`audit-ai-implementability.py:45`/213 already implement what AC-34-14 pins).
- No CI workflow change.
- No AC-31-31 cascade.
- No RUBRIC bump.
- No gate-count change.

## Lessons reinforced

- **Lesson #20** — gateway 402 → defer score, don't block phase. Pivoted to codification work.
- **Lesson #34** — cache cannot be authoritative until LLM gateway is reliably live; pin contract directly in spec.
- **Lesson #77** — hard-coded literals in prompt scaffolding get treated as contract claims by LLM auditors. Dynamic interpolation is the canonical fix.

## Files edited

- `spec/27-spec-toolchain/34-audit-ai-implementability.md` (banner + AC-34-14 added, AC-34-13 marked superseded)
- `spec/27-spec-toolchain/97-acceptance-criteria.md` (banner + Slot Delegation Map row + AC Family Prefix Index row)
- `spec/27-spec-toolchain/00-overview.md` (banner)
- `spec/27-spec-toolchain/98-changelog.md` (banner + new row)
- `spec/27-spec-toolchain/99-consistency-report.md` (banner + audit row)
- `.lovable/memory/audit/v2-deterministic/phase-153-task-A18-fu2-ac3414-codify.md` (this file)
- `.lovable/memory/index.md` (closure note)
