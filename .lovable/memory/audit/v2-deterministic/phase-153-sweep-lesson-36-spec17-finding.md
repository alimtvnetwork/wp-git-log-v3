# Phase 153 Sweep — Lesson #36 spot-check on `spec/17` consolidated guides

**Status:** ADVISORY (no phase opened) 2026-04-29
**Outcome:** 1 soft Lesson #36 surface identified in `spec/17-consolidated-guidelines/`; **deferred** as judgement-required, not autonomously actionable.

## Sweep methodology

Ran 5 normative-signal greps tree-wide for phrases that should be single-source-owned per Phase 153 Lessons #19/#21/#33/#36:

| Signal | Owner | Files mentioning | Verdict |
|--------|-------|------------------|---------|
| `BEGIN IMMEDIATE` | spec/13 §97 AC-22 + spec/05 §97 AC-SD-22 + spec/22 (separate API) | 16 | ✅ Owner-scoped; no drift |
| `journal_mode=WAL` | spec/05 §97 AC-SD-12 (existence) + spec/13 §97 AC-22 (operational policy) | 9 | ⚠️ See finding |
| `busy_timeout=5000` | spec/13 §97 AC-22 + spec/05 §97 AC-SD-22 | 9 | ⚠️ See finding |
| `RESOLVED_DIRECT/TRANSITIVE` | spec/23 AC-ADB-14 | 4 (all in spec/23) | ✅ Single-module; no drift |

## Soft Lesson #36 finding

`spec/17-consolidated-guidelines/18-database-conventions.md` and `spec/17-consolidated-guidelines/05-split-db-architecture.md` restate PRAGMA values (`journal_mode=WAL`, `busy_timeout=5000`, `foreign_keys=ON`) in summary tables and code examples **without next-to-table cross-references** to the owning ACs (AC-22 in spec/13 §97, AC-SD-12/AC-SD-22 in spec/05 §97).

Concrete locations:
- `spec/17/18-database-conventions.md` lines 452-454 (PRAGMA summary table) and line 874 (operational checklist row 14) — no `AC-22` / `AC-SD-12` mention anywhere in the file.
- `spec/17/05-split-db-architecture.md` lines 205-210 (Go code example) and line 720 (PRAGMA summary row) — only a top-of-file `**Source:** spec/05-split-db-architecture/` block; no per-table AC pointer.

If AC-22 ever changes (e.g., busy_timeout 5000 → 10000, or retry policy 3×100ms → 5×200ms), these consolidated tables would silently drift until the next manual sync. That is the exact hazard Lesson #36 names.

## Why deferred (not opened as a phase)

Three orthogonal reasons:

1. **`spec/17` is `kind: consolidated-guide`**. Its declared purpose is restatement-for-readability; a blanket Lesson #36 application would gut that purpose. The right Lesson #36 application is **per-table cross-references**, not "delete the table". That requires a per-AC editorial pass, not a mechanical sweep.

2. **Risk gradient is low**. The PRAGMA values have been stable since Phase 152 (no edits to AC-22's PRAGMA constants in the past 30+ phases). Real drift requires (a) an AC change and (b) a manual-sync omission — historically near-zero.

3. **Mirror of Lesson #20 (defer-don't-block)**: a judgement call worth surfacing rather than acting autonomously. The user may reasonably prefer (a) accept the soft restatement as the cost of consolidated-guide UX, or (b) add per-table `**Source:**` pointers without restructuring, or (c) replace tables with auto-generated includes. Each path has different tradeoffs that warrant explicit choice.

## Forward rule (not yet codified — awaits user decision)

Possible **Lesson #40 candidate**: `kind: consolidated-guide` modules MAY restate normative values, but every restating section MUST carry a `**Source:** AC-XX (file:line)` next-to-table marker. Drift is then a mechanical regression: a sweep can grep for normative signal without an adjacent `**Source:**` marker.

This would be ~30 minutes of editorial work across spec/17's 25+ leaf files, a new sweep script in `linter-scripts/`, and an AC + lockstep ripple. **Not opening unless the user explicitly elects this scope.**

## Files changed

- `.lovable/memory/audit/v2-deterministic/phase-153-sweep-lesson-36-spec17-finding.md` (this file)

No spec edits. No script edits. No CI changes. No lockstep ripple.

## Validation context

This sweep ran on a clean tree:
- Lockstep: 87/87 GREEN
- Tree-health: 168/168 strict GREEN
- Version-parity: 74/74 / 0 mismatches GREEN
- §99 freshness: 81 stamped + 6 exempt + 0 unstamped GREEN
- Folder-refs: 0 stale GREEN

The Lesson #36 finding is **not** a CI gate failure — it's a manual-review risk surface that no current gate catches.
