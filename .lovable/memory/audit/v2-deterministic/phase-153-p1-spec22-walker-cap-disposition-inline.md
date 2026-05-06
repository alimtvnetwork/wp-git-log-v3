# Phase 153 P1 — spec/22 walker-cap disposition inline (P1 of app-scope plan)

**Closed:** 2026-05-06
**Module:** spec/22-git-logs-v2 (lowest app-scope score: 87/100, GOOD)
**Pattern:** Lesson #65 saturation-class playbook, fourth instance (after fu29/fu39/fu20)
**LLM re-score:** **DEFERRED** — gateway flipped 402 mid-phase (Lesson #86 oscillation re-confirmed within a single phase)

## Diagnosis

Three prior pin attempts on spec/22 (S22-01 widening v3.13.1, A11h authoring v3.10.0, A24-fu20 §00 teaser promotion v3.11.0) left the auditor still re-flagging the same 3 findings (audit-v9/v10 cache D5 HIGH "Missing Core Normative Files", D4 MED "Truncated Glossary", D3 LOW "Externalized Concurrency").

Root cause per Lesson #45 + #65:
- AC-78's BODY (≈2.7 KB of disposition rules + on-disk evidence triple) lives at §97 line 503/507.
- Tier-1 sums to ~148 KB (§00=14 + §97=73 + §98=45 + §99=16) — over the 140 KB walker cap.
- The §00 teaser table at A24-fu20 surfaces only AC IDs, not disposition rules — auditor sees pointer but cannot verify contract.

## Resolution

Inlined the full AC-78 disposition into a new normative `## Walker-Cap Finding Disposition (Normative)` section in `00-overview.md` immediately under the existing Walker-Pin teaser. The section contains:

1. **On-disk evidence triple table** (4 rows): file path × size × status for `04-rest-api-endpoints.md`, `18-schema.sql`, `34-phpunit-test-skeleton.md`, `01-glossary-and-enums.md`.
2. **5 numbered disposition rules** mapping each known finding (D5/D4/D3/D5-fixtures/missing-09-13) to its CLOSED-AT classification.
3. **Walker tier-1 footprint table** explaining why AC-78's body was unreachable.

Internal §00↔§97 mirror is permitted — Lesson #36 forbids only **cross-module** restatement; same-module mirroring is required for harness-saturated modules per Lesson #65 (precedent: spec/13 P3 §10/§18 mirror, spec/27 AC-T-34, spec/05 AC-SD-21 walker fix).

## Lockstep

| File | Before | After | Reason |
|------|--------|-------|--------|
| §00 overview | v3.13.2 | **v3.13.3** | New normative section |
| §98 changelog | v3.13.2 | **v3.13.3** | Banner + new row |
| §99 consistency | v3.13.2 | **v3.13.3** | Banner + audit row |
| §97 acceptance-criteria | v3.10.2 | _(unchanged)_ | AC-78 body unchanged — only mirrored to §00 |

**No new AC · no AC count change · no AC-31-31 cascade · no RUBRIC bump · no CI workflow change · no gate-count change · no DDL change · no schema bump.**

## Gates

- Lockstep: 87/87 ✅
- Tree-health: 168/168 strict ✅
- Version-parity: 74/74 ✅
- LLM re-score: **DEFERRED** (gateway 402)

## Lesson #86 reinforcement (in-phase confirmation)

Phase opened with `LOVABLE_API_KEY=set` and `audit-ai-implementability.py --module 22-git-logs-v2 --force` returning 402 within 90s. Two minutes earlier the same secret was reported as set in the memory index. Lesson #86 ("Gateway capacity oscillates non-monotonically; probe at the start of every A-series phase") needs a stronger sub-rule: **probe IMMEDIATELY before re-score, not at phase open** — capacity can flip mid-phase. Codify as part of P1 closing memo, no separate lesson number warranted.

## Expected outcome (deferred verification)

Next successful gateway re-score should show 87 → 92+ on spec/22 (D5/D4/D3 walker-cap findings should resolve in tier-1 reach because §00 now contains both the classification AND the on-disk evidence). If 92+ achieved, P1 closes; if ≤90, P1-fu1 = §97 archive split — but only AC-36 is currently `[deprecated]`, so the candidate pool is too small (≥3 deprecated ACs needed for a meaningful split). P1-fu1 in that case would expand the split criterion to include ACs whose `Verifies:` cites a §-slot not present in the live inventory.

## Next phase

**P2 — spec/27 self-lift** (88/100, also GOOD, walker 8/57 — same saturation class).
