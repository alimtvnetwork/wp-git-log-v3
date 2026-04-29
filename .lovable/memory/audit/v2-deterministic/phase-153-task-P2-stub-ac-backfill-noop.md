# Phase 153 Task P2 — Stub-AC Backfill: NO-OP

**Status:** CLOSED 2026-04-29
**Outcome:** No genuine 0-AC stubs found tree-wide. P2 closes without spec edits.

## Survey methodology

Two regex passes over all 88 `97-acceptance-criteria.md` files under `spec/`:

1. **Narrow pattern (original Lesson #23 trigger):** `AC-[A-Z][A-Z0-9]*-\d+`
   — requires a letter-segment module prefix (e.g., `AC-CG-01`, `AC-SD-22`).
   — 56 files reported 0 matches → looked like a sweep target.

2. **Broad pattern (P2 verification):** `AC-[A-Z0-9][A-Z0-9_-]*\d` (case-insensitive)
   — also accepts the simple `AC-NN` scheme used by self-contained module §97s.
   — Cross-checked with a `**Verifies:**` clause counter as second signal.
   — Result: **0 files** with zero IDs AND zero Verifies clauses.

## Spot-check evidence

Three of the 56 narrow-regex "0-AC" files, re-grepped with the broad pattern:

| File | AC IDs found |
|------|--------------|
| `spec/22-git-logs-v2/97-acceptance-criteria.md` | AC-01..AC-71 (71 ACs) |
| `spec/02-coding-guidelines/01-cross-language/02-boolean-principles/97-acceptance-criteria.md` | AC-01..AC-10 (10 ACs) |
| `spec/02-coding-guidelines/11-security/97-acceptance-criteria.md` | AC-01..AC-05 (5 ACs) |

All 56 narrow-regex hits resolve to `AC-NN` simple-scheme files with full AC content.

## Lesson

**Lesson #37 — Audit-survey regexes MUST be tested against the actual ID-scheme variation in the corpus before being treated as a "gap-finder".** The narrow `AC-[A-Z][A-Z0-9]*-\d+` pattern (used in earlier Phase 153 sweeps) systematically misses any module that uses the bare `AC-NN` scheme — and >60% of spec/ §97 files use it. A "0-AC sweep" driven by that pattern would have produced 56 false-positive backfills, every one of which would have **duplicated existing ACs** and broken the AC-31-31 single-source-of-truth invariant.

Mirror of:
- **Lesson #34** (cache-staleness): never trust an audit signal at face value; cross-reference against the source.
- **Lesson #16** (walker tier order): regex/walker design IS the audit measurement — bias in the tool produces bias in the verdict.
- **Lesson #28** (comparator inspection): when a gate flags wide drift, inspect the gate's regex/comparator BEFORE mass-patching.

**Future rule (codify in any audit-survey script):** When a survey regex flags ≥10 modules in a single class, run a **second regex with broader acceptance** (or a different signal entirely, e.g., `**Verifies:**` clauses) before allocating sweep effort. Two independent signals must agree before a tree-wide pattern is declared.

## Files changed

- `.lovable/memory/audit/v2-deterministic/phase-153-task-P2-stub-ac-backfill-noop.md` (this file)
- `mem://index.md` (P2 closure + Lesson #37)

No spec edits. No CI changes. No lockstep ripple.

## Validation

- Lockstep: 87/87 GREEN (no spec touched)
- Tree-health: 168/168 strict (no spec touched)
- Version-parity: 74/74 (no banner bumps)
