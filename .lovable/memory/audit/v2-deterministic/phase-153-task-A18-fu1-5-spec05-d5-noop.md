# Phase 153 Task A18-fu1 #5 — spec/05 [D5 HIGH] no-op closure

**Date:** 2026-05-03
**Status:** CLOSED (no-op, cache-staleness)

## Finding (cache snapshot)
`05-split-db-architecture.json` — D5 HIGH "Unresolved External Dependency: Coding Guidelines" — AC-SD-01/02 reference `../02-coding-guidelines/.../97-acceptance-criteria.md` not in audit bundle.

## Resolution
Already absorbed by **AC-SD-24** (spec/05 §97 lines 256–261), authored in earlier A14 close. AC explicitly instructs the auditor:

> MUST treat the finding as a **harness bundling-cap artifact**, NOT a spec defect — the AC-CL-* registry is canonical at spec/02; inlining would violate Lesson #36 (link-don't-restate, dual-source drift).

Cache `total=82 GOOD` predates the AC-SD-24 narrative absorption being scored. Refresh requires `--force` (gateway-gated, A8 surface).

## Cross-references
- **Lesson #29** — module-kind / harness-misclassification pin pattern (spec/25 precedent).
- **Lesson #34** — cache snapshots are non-authoritative for CRITICAL/HIGH counts until LLM re-score lands; verify against §97 + closing memos first.
- **Lesson #36** — cross-module link-don't-restate (the contract this AC defends).
- **Lesson #38** — gateway availability check; force-refresh still 402-blocked at observation time.

## Lockstep ripple
None. No spec edits. Banners/changelogs unchanged.

## Outcome
spec/05 D5 HIGH finding queue: **0 actionable, 1 cache-stale (already absorbed)**.
Tree-wide: no movement on actionable backlog. Reinforces F-03 (cache refresh) as highest-leverage next action once gateway unblocks.
