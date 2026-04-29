# Phase 153 Task A6 — spec/05 lift 69 → 89 + AC-34-09 walker tier-1 fix

**Date:** 2026-04-29  
**Closed by:** spec/05 §97 ACs + slot-34 walker fix + AC-34-09.

## Result

- **spec/05-split-db-architecture: 69 → 89 (+20)**, NEEDS_WORK → GOOD (one tick below EXCELLENT).
- D1 14→18 (+4), D2 12→19 (+7), D3 10→17 (+7), D4 18→20 (+2), D5 unchanged (cross-module AC-CL-* reference, known walker boundary inherited from Task A2).
- spec/05 is no longer the lone NEEDS_WORK module; tree-wide v3 baseline (81.6) is now bottlenecked by the next-lowest module(s).
- Walker fix benefits **every** module > ~70 KB of feature/issue prose — likely to lift the tree-wide score by several points on next full re-baseline.

## Two-loop story

**Loop 1**: Added 3 GWT ACs to spec/05 §97 (AC-SD-21 SQL identifier quoting + Go struct tags; AC-SD-22 busy_timeout + retry-loop on SQLITE_BUSY; AC-SD-23 TTL/expiry contract with ExpiresAt + sweeper). Re-score returned 69 — **zero movement**. `bundle_sha` changed but `total` didn't.

**Loop 2 — diagnosis**: Enumerated file sizes under spec/05. Discovered alphabetical sort exhausted the 90 KB cap on `02-features/0[1-3]-*.md` (50+22+10+31 KB) before reaching `97-acceptance-criteria.md`. The auditor was scoring examples *without ever seeing the binding contract*.

**Loop 2 — fix**: Patched `load_module_bundle()` to tier `{00,97,98,99}-*.md` FIRST in canonical order, then everything else alphabetically. Re-score: 89.

## Lockstep

- `spec/05-split-db-architecture/97-acceptance-criteria.md` v4.0.0 → **v4.1.0** (3 new ACs: AC-SD-21/22/23).
- `spec/05/00-overview.md` banner v4.0.0 → **v4.0.1** (Updated 2026-04-03 → 2026-04-29; h10 stamp 22 → 153).
- `spec/05/98-changelog.md` v4.0.0 → **v4.1.0**.
- `spec/05/99-consistency-report.md` v4.0.0 → **v4.0.1** (new v4.0.1 update block; freshness stamp 146 → 153).
- `spec/27/34-audit-ai-implementability.md` v1.0.1 → **v1.1.0** (added AC-34-09).
- §27 §00 v2.75.1 → **v2.76.0**, §98 v2.75.1 → **v2.76.0**, §99 v2.72.1 → **v2.73.0**.
- `linter-scripts/audit-ai-implementability.py` walker patched (no version-track on linter; tracked via slot-34 §00 banner per Task #32 lesson).
- **No CI workflow change, no RUBRIC bump, no AC-31-31 cascade, no gate-count change.**

## Validation

- spec/05 force re-score: 69 → 89 ✅
- `node linter-scripts/check-lockstep.cjs --strict` → 87/87 PASS, 0 findings.
- `node linter-scripts/check-tree-health.cjs --strict` → 168/168 PASS, score 100/100.

## Lesson #16 (codified at slot-34 §98 v2.76.0 + AC-34-09)

LLM auditors with bounded context windows MUST tier contract files (`{00,97,98,99}-*.md`) BEFORE example/feature files. Alphabetical-only ordering creates a silent contract-dropout class — contract edits become invisible to the auditor and contributors waste cycles wondering why scores don't move.

**Diagnostic heuristic**: if `bundle_sha` changes but `total` doesn't move under a §97 edit, the §97 was almost certainly truncated — enumerate file sizes BEFORE the next edit attempt. Add `ls -laS spec/<module>/` to the contributor checklist before any audit-driven §97 expansion.

## Lesson #17 (process)

Re-running an audit after a content-only edit MUST be the first verification step — not the last. Two-loop discovery here would have been one-loop if the score hadn't been trusted at face value before the file-bundle composition was checked.

## Next

- A7 (or A3-followup): tree-wide rebaseline run with the new walker — likely lifts overall 81.6 by several points on every chunky module.
- A6-followup: address the only remaining HIGH on spec/05 (cross-module AC-CL-* reference) by either inlining a 1-paragraph AC-CL summary in §05 §97 (mirrors the spec/04 A2 fix pattern) or extending the walker to slurp parent-module §97s when explicitly inherited.


---

**Lessons codified:** #16, #17 → see [`mem://process/phase-153-lessons`](../../../memory/process/phase-153-lessons.md) for the canonical contributor-rule statements.
