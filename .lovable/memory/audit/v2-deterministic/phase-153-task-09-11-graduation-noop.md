# Phase 153 Task #9–#11 — Graduate spec/27 self-tests (NO-OP closure)

**Date:** 2026-04-29
**Status:** CLOSED (no-op — already graduated in prior phases)

## Scope

Backlog items #9–#11 inherited the label "graduate spec/27 self-tests" from
pre-Phase-153 planning. This phase audited each candidate gate against
`.github/workflows/spec-health.yml` to determine remaining work.

## Findings

| Gate | CI invocation | Status |
|---|---|---|
| `check-version-parity.py` | `--strict` (line 136) | ✅ Already strict; 74/74 matches at zero mismatches (Phase 153 Task #35-fu2) |
| `check-99-summary-freshness.py` | `--strict-position` (line 122) | ✅ Already strict; 81 stamped, 6 exempt, 0 unstamped failures |
| `audit-ai-implementability.py` | `--report-only` (line 387) | ⏸ Advisory (= **A8**, requires gateway budget for 3-baseline validation) |

The three deterministic gates listed in the original "graduate" backlog have
already been promoted in prior phases (P15/H10/H1/P20/P48-1-fu1). The only
remaining advisory→strict promotion is **A8** for the LLM-driven audit, which
is gated on `LOVABLE_API_KEY` budget and out of scope for this no-op closure.

## Outcome

No spec edits, no script edits, no lockstep ripple. Task #9–#11 collapses to
**A8** as the sole remaining graduation surface.

## Lesson #30

Inherited backlog labels from prior planning rounds MUST be re-audited against
current CI state before allocating effort. Three "next-up" tasks dissolved on
inspection because the work shipped silently across Phases P15/H10/P20/P48-1-fu1.
Future "next" command should treat backlog inheritance as advisory — verify the
gate is not already strict before opening a graduation phase.

## References

- `.github/workflows/spec-health.yml` lines 122, 136, 387
- Phase 153 Task #35-fu2 memo (version-parity 74/74 zero-mismatch baseline)
- Phase H1 / P20 (per-file stamp pattern enabling the strict promotions)
