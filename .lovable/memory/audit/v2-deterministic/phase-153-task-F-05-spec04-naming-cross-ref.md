# Phase 153 Task F-05 — spec/04 §01-naming-conventions storage-vs-naming axis cross-reference banner

**Status:** CLOSED 2026-05-03 (LLM re-score deferred per Lesson #20 — gateway 402 after F-04 full-tree budget burn)
**Module:** spec/04-database-conventions
**Lever:** F-04 baseline 89/100 GOOD (3 findings: HIGH-D5 walker artifact, MEDIUM-D3 forbidden by Lesson #36, LOW-D1 productive)

## Result

- **Productive scope:** LOW-D1 "Boolean Type Ambiguity in SQLite" — closed via single Normative cross-reference banner at top of `01-naming-conventions.md` § "Boolean Column Rules" pinning that section's `BOOLEAN` keyword as DDL pseudo-syntax for the **naming axis only** (production storage MUST follow §2.1 / AC-09 + §2.1.1 SQLite-INTEGER mandate / AC-17).
- **Non-remediated:**
  - HIGH-D5 "Truncated Relationship Diagram File" — **Lesson #74** LLM hallucination class (file is 15.8 KB on disk per AC-17; "136 KB cap" is bundle-budget byte-cap artifact). No stronger pin attempted.
  - MEDIUM-D3 "SQLite Concurrency Logic Externalized" — auditor recommendation explicitly violates **Lesson #36** (cross-reference, never restate). spec/13 AC-22 owns concurrency contract; spec/04 §02 §4.3 already cross-references per Phase 153 P3.
- **No mass `BOOLEAN→INTEGER` sed** across 50 SQL snippets — would corrupt the section's naming-axis pedagogy (the snippets demonstrate `Is`/`Has` prefix discipline, not engine storage).
- **Expected score lift:** 89 → 90+ (EXCELLENT band) — D1 finding clears with banner; D2/D3/D4/D5 dimensions unchanged. Re-score deferred per Lesson #20.

## Lockstep

| File | Before | After | Reason |
|---|---|---|---|
| `01-naming-conventions.md` | v3.5.0 | **v3.5.1** | Banner added (4-line normative cross-reference, no contract change) |
| `00-overview.md` | v3.8.0 | **v3.8.1** | Patch — child file got new content |
| `98-changelog.md` | v3.8.0 | **v3.8.1** | New row + banner |
| `99-consistency-report.md` | v3.9.1 | **v3.9.2** | New audit row |
| `97-acceptance-criteria.md` | v1.6.0 | **unchanged** | No new contract — banner is implementer-facing prose mirror |

**No CI / RUBRIC / AC-31-31 / gate-count change.** All 5 strict gates GREEN: lockstep 87/87 · tree-health 168/168 strict · version-parity 74/74 · freshness · folder-refs.

## Lessons reinforced

- **#36 (Nth instance — cross-section axis):** Banner explicitly tags dual-source drift class as forbidden; cites AC-09/AC-17 line anchors for canonical surface. Same pattern as P3 concurrency mirror, A24-fu4 spec/12 cross-ref, A24-fu45 spec/11 walker-pin teaser.
- **#74 (1st full-baseline reinforcement):** HIGH-D5 finding survived F-04 v8 baseline despite already being walker-pinned at AC-17. Confirms maximal-walker-pin + LLM-still-hallucinates → classify as auditor error, do NOT stack stronger pins (would push real contract past bundle cap).
- **#36 vs auditor recommendation conflict:** When LLM auditor's `fix` field recommends restating a cross-module contract inline (here MEDIUM-D3 "Inline the specific SQLite PRAGMA…from spec/13"), the recommendation MUST be rejected — the `fix` field is advisory; the spec contract (Lesson #36) is normative.

## Cross-references

- F-04 v8 baseline: `phase-153-task-F-04-v8-rebaseline.md`
- AC-09: spec/04 §97 (cross-language boolean storage convention)
- AC-17: spec/04 §97 (canonical INTEGER mandate + walker-cap finding pin)
- Lesson #36: `mem://process/phase-153-lessons` § C
- Lesson #74: index Memories list (Phase 153 Task A24-fu49)
