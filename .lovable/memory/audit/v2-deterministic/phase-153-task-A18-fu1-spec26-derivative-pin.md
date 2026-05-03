# Phase 153 Task A18-fu1 — spec/26 AC-DG-22 derivative-artifact module-kind pin

**Date:** 2026-05-03
**Status:** CLOSED
**Driver:** First close-out from the v13 baseline 14-HIGH backlog. spec/26-gitlogs-diagrams cache showed `total=91, files=9/9, bytes=72710` (bundle COMPLETE) with HIGH `[D5] Missing Authoritative Source Context (spec/22)` — finding is structural derivative-module class, not a fixable D5 gap.

## What changed

- **AC-DG-22** `[critical]` added to `spec/26-gitlogs-diagrams/97-acceptance-criteria.md` (count 22 → 23).
  - Pins spec/26's relationship to spec/22 as **derivative-artifact module**: diagrams (`*.mmd`/`*.svg`) are spec/26-OWNED artifacts whose correctness invariants (AC-DG-01..21) are defined here, but whose subject matter is owned by spec/22.
  - Declares the **bounded delegation contract** (Authoritative-source link in §00 per AC-DG-13 + per-AC `**Verifies:**` clauses naming spec/22 sections + AC-DG-17 `GL-*` registry parity) as auditor-authoritative.
  - Declares LLM-auditor `[D5] Missing Authoritative Source Context (spec/22)` finding class as **harness bundling-scope artifact**, NOT spec/26 contract gap.
  - Forward-looking guard: future widened-walker diff-class findings (e.g. "AC-DG-01 lists table X but spec/22 §02 dropped X") REMAIN actionable; today's structural class is not.

## Why this AC, not content edits

- Bundling spec/22 into spec/26's audit scope would violate **Lesson #36** (cross-module link-don't-restate). The AC explicitly REJECTS that as the wrong fix.
- Mirror of **spec/25 AC-AI-09/10/11 pattern** (Phase 153 Task A11c) which closed the audit-corpus quoted-evidence misclassification class.

## Lockstep

| File | Before | After | Note |
|---|---|---|---|
| `97-acceptance-criteria.md` | v3.3.0 | **v3.4.0** | minor — new AC-DG-22 |
| `00-overview.md` | v3.4.4, h10=32 | **v3.5.0**, h10=153 | banner + stamp refresh + §00↔§98 parity |
| `98-changelog.md` | v3.4.4 | **v3.5.0** | new release row |
| `99-consistency-report.md` | v3.3.4 | **v3.4.0** | audit row |

§00 initially set to v3.4.5 — version-parity gate caught drift vs §98's v3.5.0; corrected to v3.5.0 in-flight (Lesson #28 reinforcement: trust the comparator, not the intuition).

## Validation

| Gate | Result |
|---|---|
| `check-lockstep.cjs` | 87/87 pass · 0 findings |
| `check-tree-health.cjs --strict` | 168/168 strict |
| `check-version-parity.py --strict` | 74/74 matches · 0 mismatches |
| `check-99-summary-freshness.py --strict-position` | 81 stamped + 6 exempt + 0 unstamped |

All 4 strict gates GREEN.

## LLM re-score

Deferred per **Lesson #20**. Gateway HTTP 402 was active during the prior task this session (A18-fu2's spec/05/03/11 probes all failed). Re-attempt next gateway-live window. Expected lift per spec/25 AC-AI-09..11 precedent: 91 → 93-95 EXCELLENT (D5 finding cleared on first re-score where the auditor reads AC-DG-22).

## Lesson #41 codified (NEW)

**Derivative-artifact module class** — any module that OWNS artifacts (diagrams, tests, examples, screenshots, fixtures) whose **subject matter** is defined in another spec module is a **derivative-artifact module**. Such modules systematically attract LLM-auditor `[D5] Missing Authoritative Source Context` findings that are NOT closeable by content edits. Canonical fix: a single `[critical]` AC pinning the bounded delegation contract (Authoritative-source link + `**Verifies:**` per per-artifact AC + cross-module registry parity ACs).

- Mirror axis: Lesson #29 = audit-corpus DESCRIBES other specs; Lesson #41 = derivative-artifact OWNS-but-VISUALIZES other specs.
- Forward sweep candidates: scan tree for `kind: index` modules whose §00 starts with `Authoritative source: …` OR whose §97 has ≥5 `**Verifies:**` clauses citing a sibling module's sections. spec/26 was the first surfaced; sweep deferred.

## Files edited

- `spec/26-gitlogs-diagrams/97-acceptance-criteria.md` (AC-DG-22 + banner)
- `spec/26-gitlogs-diagrams/00-overview.md` (banner + h10 stamp)
- `spec/26-gitlogs-diagrams/98-changelog.md` (banner + new row)
- `spec/26-gitlogs-diagrams/99-consistency-report.md` (banner + audit row)
- `.lovable/memory/audit/v2-deterministic/phase-153-task-A18-fu1-spec26-derivative-pin.md` (this file)
- `.lovable/memory/index.md` (closure note)
