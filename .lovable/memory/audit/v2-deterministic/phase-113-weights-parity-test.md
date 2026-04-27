# Phase 113 — WEIGHTS dimension-table parity self-test

**Date landed:** 2026-04-27
**Trigger AC:** AC-31-31 (Phase 109 — multi-file enumeration parity contract)
**Discovery method:** Memory-tree sweep for previously-unregistered 3+ file enumerations (the autonomous task queued for Phase 113)
**Verifies:** AC-31-02 (`implementability == 35 AND total == 100`, extended cross-file), AC-31-31 row #4
**CI gate count:** 12 → **13**

---

## What landed

A new parity self-test, `linter-scripts/test/test-weights-parity.sh`, mechanically enforces the 3-way parity triangle for the audit's 7-dimension scoring weights:

| Site | Role | Source of truth |
|---|---|---|
| `linter-scripts/audit-spec-vs-code-v2.py` | Source of truth | `WEIGHTS` dict at module top + `assert sum(WEIGHTS.values()) == 100` |
| `linter-scripts/generate-gate-report.py` | Duplicated for offline analysis | `WEIGHTS` dict (one-liner format) |
| `spec/27-spec-toolchain/31-audit-spec-vs-code-v2.md` | Documented spec | `## Weights` markdown table |

The test asserts **8 invariants**:

1. `audit-spec-vs-code-v2.py` exposes a non-empty WEIGHTS dict.
2. `generate-gate-report.py` exposes a non-empty WEIGHTS dict.
3. §31 `## Weights` table parses to a non-empty mapping.
4. audit-script WEIGHTS == gate-report WEIGHTS (same keys, same values).
5. audit-script WEIGHTS == §31 `## Weights` table (same keys, same values).
6. `implementability` weight == 35 (AC-31-02 invariant).
7. weights sum to 100 (AC-31-02 invariant).
8. exactly 7 dimensions (matches §00-overview's "7 dimensions" prose).

All 8 pass at landing:

```
audit-script:    {"alignment": 15, "clarity": 10, "completeness": 20, "consistency": 10, "implementability": 35, "maintainability": 3, "testability": 7}
gate-report:     {"alignment": 15, "clarity": 10, "completeness": 20, "consistency": 10, "implementability": 35, "maintainability": 3, "testability": 7}
§31 spec table:  {"alignment": 15, "clarity": 10, "completeness": 20, "consistency": 10, "implementability": 35, "maintainability": 3, "testability": 7}
implementability: 35  |  total: 100  |  dimensions: 7
Results: 8 passed, 0 failed
```

## How discovery happened (the proactive use of AC-31-31)

Phases 102, 103, and 112 all *reacted* to existing drift:

- **Phase 102** was authored after AC-31-27 was unenforceable for several phases (the README inventory had silently fallen out of sync with disk).
- **Phase 103** was authored after Phase 99's QA-baseline footer drift was discovered four phases late.
- **Phase 112** was authored after Phase 107 catalogued 8 silent orphans that had accumulated in `linter-scripts/`.

**Phase 113 is the first parity test authored *before* any drift occurred**, by deliberately sweeping the codebase for the AC-31-31 trigger pattern. The sweep was the autonomous task in the previous response's Remaining Tasks queue ("Phase 113 — Sweep `.lovable/memory/` for any other 3+ file enumerations Phase 109's contract surfaces but didn't yet register").

### Sweep methodology

The sweep used `grep -rln` across `spec/`, `linter-scripts/`, and `.lovable/memory/audit/v2-deterministic/` to enumerate files matching candidate patterns: `7 dimensions`, `WEIGHTS`, `implementability.*completeness.*alignment`, `8 exit codes`. For each candidate, it counted unique source files and applied the AC-31-31 threshold: ≥3 files → parity test required; 2 files → direct lockstep already covered by `check-lockstep.cjs`.

The triage produced:

| Candidate enumeration | Sites found | AC-31-31 verdict |
|---|---|---|
| `WEIGHTS` dict | `audit-spec-vs-code-v2.py`, `generate-gate-report.py`, §31 `## Weights` table (= 3) | **Triggers — parity test required** |
| `RUBRIC_WEIGHTS` (Required/Recommended/Quality) | `generate-dashboard-data.cjs` only (= 1) | Does not qualify (single-source) |
| `7 dimensions` prose | §00-overview, PHASE-22B-DELTA-REPORT.md (= 2) | Does not qualify (2 files = direct lockstep zone) |
| `8 exit codes` reference | §02 + §14 + §27 spec files (= multi-module, but each refers to a *different* exit-code table) | Does not qualify (different enumerations sharing a label) |

The WEIGHTS triangle was the only true match.

## Why this matters more than Phase 112

Phase 112 closed an *inventory-tracking* drift surface — bad, but the worst case was a future orphan accumulating again. **Phase 113 closes a *scoring-correctness* drift surface** — the worst case is a contributor "rebalancing" weights in `generate-gate-report.py` for a one-off analysis (a plausible pattern: "I want to see what scores look like with 50/50 implementability/completeness"), forgetting to revert, and silently producing wrong gate-cap attribution for months.

AC-31-02 caught the *internal* invariant (`sum == 100`) but not the *cross-file* invariant (audit-script's WEIGHTS == gate-report's WEIGHTS). The new test promotes AC-31-02 from a single-file runtime assertion to a 3-file CI gate — exactly the AC-31-31 generalisation pattern.

## CI gate cascade (third application)

Adding the new step triggered the same cascade Phases 112 + 102 + 103 had triggered:

- `audit-spec-vs-code-v2.py` `RUBRIC_VERSION` v2.21 → **v2.22**.
- `00-index.md` "QA tooling baseline" footer regenerated to enumerate **13 gates** (was 12), with new row #13 referencing the Phase 113 self-test and AC-31-02 + AC-31-31 row #4.
- `EXECUTIVE-SUMMARY.md` cross-reference updated to "13 strict CI gates".
- `linter-scripts/test/test-qa-baseline-footer.sh` (Phase 103) extended with `/Self-test WEIGHTS dimension-table parity/`. After regeneration: **11/11 assertions pass at 13-gate alignment** across all 4 sites.
- `linter-scripts/test/README.md` extended with row #7 in inventory + coverage-triad tables; "Last updated" → Phase 113; totals updated; "Adding a new self-test" callout extended.

The cascade has now run cleanly **three times in a row** (Phases 112 → 113 → ...), which is itself the empirical validation that AC-31-31 is the right abstraction. The first application (Phase 112) required discovering the cascade's full shape; the second (Phase 113) executed it as a checklist.

## Verification

All 13 strict CI gates green:

| # | Gate | Result |
|---|---|---|
| 1 | Cross-links | ✅ |
| 2 | Tree-health | ✅ 100/100 |
| 3 | Lockstep | ✅ 0 findings |
| 4 | Audit thresholds | ✅ 98.0/99.8 |
| 5 | CLI threshold self-test (Phase 91) | ✅ 6/6 |
| 6 | `--explain` self-test (Phase 94) | ✅ 14/14 |
| 7 | Determinism self-test (Phase 95) | ✅ 7/7 |
| 8 | Mermaid syntax (Phase 97) | ✅ 106/106 |
| 9 | README inventory parity (Phase 102) | ✅ **20/20** (was 18, now 20 after 7th row) |
| 10 | QA baseline footer (Phase 103) | ✅ 11/11 at **13-gate alignment** (v2.22 / 13 / 13 / 13) |
| 11 | Memo retrospective headings (Phase 104) | ✅ in-scope memos / 0 forbidden headings (this memo included) |
| 12 | §27 inventory parity triangle (Phase 112) | ✅ 6/6 (orthogonal to Phase 113; unaffected) |
| 13 | **WEIGHTS dimension-table parity (Phase 113)** | ✅ **8/8** (audit == gate-report == spec; impl=35; total=100; count=7) |

§27 holds at **97/100 A+** with impl=100. No score regression.

## Outcome and design property

The shared design property of all 4 parity tests in the AC-31-31 registry (Phases 102, 103, 112, 113) is now clear: **the source-of-truth file uses an existing markdown or Python idiom, and the test extracts via that idiom — never inventing a custom delimiter**.

- Phase 102: markdown link syntax `[file](./file)` carries the inventory.
- Phase 103: regex on `**Rubric:** v<X>.<Y>` carries the version.
- Phase 112: backticked code paths `` `linter-scripts/...` `` carry the inventory.
- Phase 113: Python `WEIGHTS = { ... }` literal + markdown `## Weights` table heading carry the weights.

This pattern is now a documented design property of AC-31-31's parity-test family. Future authors adding row #5 should follow it: pick an extraction idiom that already exists in the source-of-truth file, never define a new delimiter the spec authors must remember to use.
