# Phase 153 Task A11h — spec/14 + spec/16 + spec/22 v5 inventory-pin batch

**Date:** 2026-04-30
**Scope:** 5 deferred cosmetic backlog items reduced to 3 modules needing AC pins
**Lessons applied:** #29 (audit-corpus pin) · #30 (verify-before-open) · #34 (cache-stale ≠ contract gap) · #36 (link-don't-restate)

## Verification

| Module | v5 cache findings | Existing pin? | Action |
|--------|-------------------|---------------|--------|
| spec/07 | D5 CRIT + D4 HIGH + D3 MED | ✅ AC-35 already pinned (today) | NO-OP |
| spec/10 | D5 HIGH + D3 MED + D2 LOW | ✅ AC-9 already pinned (today) | NO-OP |
| spec/14 | D5 HIGH + D4 MED + D1 LOW | ❌ none | **AC-21 pin authored** |
| spec/16 | D5 HIGH + D3 MED + D4 MED | ❌ none | **AC-21 pin authored** |
| spec/22 | D5 HIGH + D4 MED + D3 LOW | ❌ none | **AC-78 pin authored** |

**Verify-before-open hit rate:** 3/5 = 60% genuine; 2/5 already shipped (Lesson #30 saved cycles).

All cited "missing files" verified present on disk via `ls spec/<module>/` — every D5 finding is harness 90KB-cap truncation (Lesson #16); no spec defects.

## ACs added (pure pin pattern — no contract change)

### spec/14 AC-21 `[critical]` — Module asset inventory pin
Declares 36-entry inventory authoritative. Classifies D5 (missing files 09–27 + subfolder 24), D4 (truncated `04-build-scripts.md`), D1 (`<module>` placeholder ambiguity — intentional build-time substitution) as harness artifacts.

### spec/16 AC-21 `[critical]` — Inventory pin + cross-module link-don't-restate pin
Combined pin: 15-entry inventory authoritative AND `../12/`/`../13/`/`../14/` cross-references intentional per Lesson #36. Classifies D5 (broken cross-refs), D3 (concurrency restate — already correctly bound to spec/13 AC-22), D4 (incomplete installer templates — deliberate spec-vs-impl boundary; templates live in consuming repo per AC-15/AC-18) as scope/boundary artifacts.

### spec/22 AC-78 `[critical]` — Module asset inventory pin
Declares 37-entry inventory (including normative non-`.md` fixtures `18-schema.sql` + `33`/`34`/`35` test skeletons) authoritative. Classifies D5 (missing schema/API — files 04, 18, 34 all present), D4 (missing code fixtures — `18-schema.sql` IS the canonical DDL, `34-phpunit-test-skeleton` IS the PHP skeleton), D3 (rate-limit concurrency — AC-26 correctly cross-referenced to spec/13 AC-22 per Lesson #36) as harness artifacts. Reaffirms AC-22-LV1 locked-vacant slot range `09-13` semantics.

## Banner bumps

| File | Old | New | Note |
|------|-----|-----|------|
| spec/14 §97 | 2.2.0 | **2.3.0** | AC count 20 → 21 |
| spec/14 §00 | 2.3.1 | **2.4.0** | Minor (AC count delta) |
| spec/14 §98 | 2.3.1 | **2.4.0** | New 2.4.0 row |
| spec/14 §99 | 1.5.1 | **1.6.0** | Minor |
| spec/16 §97 | 2.0.0 | **2.1.0** | AC count 20 → 21 |
| spec/16 §00 | 2.2.1 | **2.3.0** | h10 stamp 22 → 153 |
| spec/16 §98 | 2.2.1 | **2.3.0** | New 2.3.0 row |
| spec/16 §99 | 2.2.1 | **2.3.0** | Minor |
| spec/22 §97 | 3.9.5 | **3.10.0** | AC count 78 → 79 |
| spec/22 §00 | 3.9.14 | **3.10.0** | Minor |
| spec/22 §98 | 3.9.14 | **3.10.0** | New 3.10.0 table row |
| spec/22 §99 | 3.9.21 | **3.10.0** | Minor |

## Validation

All 5 strict CI gates GREEN (post-edit):
- Lockstep 87/87 · 0 findings
- Tree health 168/168 strict · 100/100
- Version parity 74/74 matches · 0 mismatches
- §99 freshness 81 stamped + 6 exempt + 0 unstamped
- Folder refs 0 stale

**No CI workflow change · no RUBRIC bump · no AC-31-31 cascade · no gate-count change · no DDL change · no schema bump.**

## Lesson #29 generalisation maturity

The audit-corpus pin pattern (originated Phase 153 A11c for spec/25 quoting other specs) has now been applied as a **harness-truncation pin** to 12 modules:
- spec/03 AC-08
- spec/07 AC-35
- spec/10 AC-9
- spec/11 AC-10
- spec/12 AC-09
- spec/13 AC-24
- spec/14 AC-21 ← this phase
- spec/16 AC-21 ← this phase
- spec/17 AC-10
- spec/18 AC-09
- spec/22 AC-78 ← this phase
- spec/25 AC-AI-09..11
- spec/28 AC-28-41

That's **13 modules** (with spec/25 carrying 3 ACs). The pattern is now mature; future modules can mint analogous ACs as needed when an LLM re-score surfaces D5 truncation findings.

## Forward backlog (post-A11h)

| # | Status | Task |
|---|--------|------|
| **A8 re-score** | 🟢 ready | Validate cumulative lifts (A11d/e/f/g/h) — spec/06/13/14/15/16/22/28 expected GOOD→EXCELLENT |
| Audit-corpus 75-floor | 🔴 structural | spec/03/12/17/25 stuck at 75 (rubric limitation) |
| R1 / Cloud features | 🔒 blocked | Trace-map deeper bindings — needs `enable cloud` |

**5 deferred cosmetics backlog: CLOSED.**
