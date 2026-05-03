# Phase 153 Task S28-01 — AC-28-44 codifies `--parallel` failure isolation (audit-v6 MED/D3 close-out)

**Closed**: 2026-05-03
**Module**: `spec/28-universal-ci-cli/`
**Finding**: audit-v6 MEDIUM/D3 — "Ambiguous behavior for parallel runtime failures — §02 failure semantics define behavior for sequential phases, but for --parallel runtimes, it's unclear if a failure in 'ts' should abort an ongoing 'php' runtime or just prevent its next phase."

## Resolution
Added new normative subsection `### --parallel failure isolation (Normative)` to `02-architecture.md` `## Failure Semantics`:
- 4-row event table: single-runtime failure (no sibling impact) / `SIGINT` (130) / `SIGTERM` (143) / `--fail-fast` (cross-runtime cancel)
- Aggregated worst-exit-code rule: precedence `4 > 2 > 1 > 0`
- 4-pattern forbidden list: cross-runtime cancellation w/o `--fail-fast`, sibling-SIGTERM inheritance, exit-code-lowering on later success, cross-runtime ship-queue reordering

Bound as **AC-28-44** `[medium]` in §97 (count 43 → 44) with full GWT + cross-references to AC-28-22 (per-runtime PipelineName / sealed ship queues).

## Lockstep
- §97 v2.2.0 → **v2.3.0** (minor — new AC, count 43 → 44)
- §00 v2.2.0 → **v2.3.0** (banner sync per version-parity gate)
- §02 v1.0.0 → **v1.1.0** (new normative subsection)
- §98 [2.2.0] → **[2.3.0]** (new release row)
- §99 v2.2.0 → **v2.3.0**
- h10 stamp 153 (unchanged)

**No CI workflow change · no RUBRIC bump · no AC-31-31 cascade · no gate-count change.**

## Pattern lineage
- Lesson #19 (audit-boundary < verification-boundary): the answer existed implicitly in §02's "separate ship queues" prose, but the failure-cancellation contract was not lifted to a normative table — auditor couldn't infer it. AC-28-44 IS the lift.
- Lesson #22 (closed-enumeration tables replace open prose): 4-row event table + 4-pattern forbidden list make the contract auditable.
- Lesson #36 (link, never restate) — AC-28-44 cross-references AC-28-22 for ship-queue semantics rather than restating.

## Gates
- Lockstep 87/87 · Tree-health 168/168 strict · Version-parity 74/74 · Freshness 81+6/87 = all GREEN.

## Score expectation
LLM re-score deferred per Lesson #20. Expected lift 97 → 99 on next rescore (D3 was the only non-20 dimension; 2 remaining LOW findings — SSH signature example + push timeout — may or may not lift further).
