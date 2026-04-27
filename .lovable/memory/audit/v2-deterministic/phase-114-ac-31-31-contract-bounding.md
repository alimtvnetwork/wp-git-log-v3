# Phase 114 — AC-31-31 contract-surface bounding sweep

**Date landed:** 2026-04-27
**Trigger AC:** AC-31-31 (Phase 109 — multi-file enumeration parity contract)
**Discovery method:** Second proactive sweep of `linter-scripts/`, `spec/27-spec-toolchain/`, and `.github/workflows/` for 3+ file enumerations not yet covered by the AC-31-31 registry — performed *after* Phase 113 had already done a `.lovable/memory/`-focused sweep and found one (WEIGHTS).
**Verifies:** AC-31-31 "Currently-NOT-qualifying enumerations" paragraph (extended)
**CI gate count:** 13 → **13** (unchanged)
**Script version:** v2.22 → **v2.22** (unchanged)
**RUBRIC_VERSION:** unchanged
**Net deliverable:** zero new parity tests; AC-31-31 contract surface formally bounded

---

## What landed

A documentation-only commit extending AC-31-31's "Currently-NOT-qualifying enumerations" paragraph at `spec/27-spec-toolchain/31-audit-spec-vs-code-v2.md` line 342 with **four new dismissals** and one new conceptual distinction.

### The four dismissals

| Candidate | Sites | Why dismissed |
|---|---|---|
| Audit-script exit-code table (0 / 1 / 2) | `audit-spec-vs-code-v2.py` source + §31 spec table = 2 | Direct lockstep zone (≤2 files) — already covered by `check-lockstep.cjs`. AC-31-31 fires only at 3+. |
| Per-script exit-code tables across `check-memo-retrospective-headings.py`, `check-memory-mirror-drift.py`, `check-root-readme.py`, `check-tree-health.cjs`, etc. | N scripts × 2 sites each | Each script's `0`/`1`/`2` encodes **different domain semantics** ("no drift" vs "no broken links" vs "no missing readme"). These are N independent 2-file enumerations sharing a *label*, not one N-file enumeration sharing a *value*. Inventing a cross-script "exit code 1 always means failure" parity test would be a category error. |
| Audit CLI flag set (`--min-weighted` / `--min-impl` / `--strict` / `--explain` / `--deterministic`) | script + spec + workflow + 4 self-tests = 7 | Each site cites only the **subset of flags it actually uses**: `test-audit-cli-thresholds.sh` cites only the 2 threshold flags, `test-audit-explain-contract.sh` cites only `--explain`, the spec cites the 3 user-facing flags. This is **API surface use** — the canonical list lives only in `argparse`. AC-31-31 requires that every site list **all N items** for the parity contract to be coherent. |
| CI threshold floors `--min-weighted=97 --min-impl=99` | `.github/workflows/spec-health.yml` + `linter-scripts/test/test-audit-cli-thresholds.sh` expected-floor cases = 2 | Direct lockstep zone — Phase 91's self-test already verifies the workflow's floors are coherent with the script's exit-code semantics. |

### The new conceptual distinction

The Phase 114 paragraph introduces an explicit distinction between two patterns that look superficially similar:

- **Enumeration restatement** — every site lists **all N items** (e.g. WEIGHTS dict — every restatement must enumerate all 7 dimensions; QA-baseline footer — every restatement must enumerate all 13 gates). AC-31-31 fires.
- **API surface use** — each site lists **only the items it touches** (e.g. CLI flag references — each test mentions only the flags it locks). AC-31-31 does NOT fire; the canonical list lives in a single source-of-truth (argparse, the function signature, etc.) and the single-source rule covers it.

This distinction was implicit in Phases 102/103/112/113 but never named. Phase 114 names it and uses it as the dismissal criterion for the CLI flag set.

## How discovery happened

Phase 113's "Remaining Tasks" queued a follow-up sweep to validate that the registry was complete, not just to find more rows. The sweep used `rg -l` across three roots (`linter-scripts/`, `spec/27-spec-toolchain/`, `.github/workflows/`) for candidate keywords: `exit code`, `EXIT_CODE`, `sys.exit`, `--min-`, `threshold`, `MIN_SCORE`, `severity`, `RISK_LEVELS`. Each multi-file hit was triaged against AC-31-31's three trigger conditions:

1. Same enumeration (not the same label encoding different values)?
2. Restated in 3+ files?
3. Every site lists all N items (not just the subset it touches)?

Only matches that satisfied all three would qualify. **No candidate satisfied all three.** This is the first time AC-31-31's trigger criteria have been applied as a *negative filter* rather than a positive one — and the criteria proved sharp enough to dismiss every false positive without ambiguity.

## Why bound the registry at all?

AC-31-31 was authored as an open-ended contract: any future 3+ file enumeration is bound by the protocol, indefinitely. Without a bounding statement, the contract has no observable closure point — a reviewer can never say "the AC-31-31 registry is complete for the current toolchain". This creates a slow drift toward over-application: future contributors might invent parity tests for the CLI flag set or the per-script exit-code tables, mistaking labels for values.

Phase 114's bounding statement creates an **explicit closed-set** for the *current* toolchain surface (4 rows) while preserving the *open-ended* obligation for future additions. The bounding language reads:

> "Phase 114 bounds the **current** surface, not future additions. Future contributors who introduce a new 3+ file enumeration are still bound by the AC-31-31 protocol regardless of this Phase-114 bounding language."

This is an instance of the **AC-SAG-26 documentation-cadence retirement pattern** applied to a *registry* rather than a recurring section: the registry is declared "provisionally complete" with a clear re-trigger condition (any new 3+ file enumeration). The retirement is not deletion — it is a **closure declaration** that frees reviewers from the cognitive load of "is there one more I'm missing?" while keeping the rule active.

## Why this matters more than just adding a row

Phases 102/103/112/113 each *closed* a drift surface. Phase 114 *bounds* the contract that defines what counts as a drift surface. Without the bounding pass, every future PR review would re-ask the same question ("are there other 3+ file enumerations we've missed?"), and the answer would always be "maybe — keep looking". With the bounding pass, the answer becomes "no — here are the four candidates we already evaluated and the criteria we used; if you find a *new* candidate that satisfies all three triggers, add it; otherwise the registry is complete".

This is the difference between a **rule** (always applies, always re-checked) and a **completed inventory** (declared closed at a point in time, with a clear re-trigger). AC-31-31 stays a rule; the registry becomes a completed inventory.

## CI gate cascade (intentionally absent)

Unlike Phases 102/103/112/113, Phase 114 does **not** trigger the standard cascade:

| Cascade step | Phase 112/113 | Phase 114 |
|---|---|---|
| New self-test | ✅ | ❌ (none authored) |
| `RUBRIC_VERSION` bump | ✅ | ❌ (no behavioural change) |
| `00-index.md` footer regenerated | ✅ | ❌ (gate count unchanged) |
| `EXECUTIVE-SUMMARY.md` cross-reference | ✅ | ❌ |
| `test-qa-baseline-footer.sh` extended | ✅ | ❌ |
| `linter-scripts/test/README.md` extended | ✅ | ❌ |
| `spec/27-spec-toolchain/31-...md` registry row | ✅ | ❌ (registry unchanged; only the dismissal paragraph extended) |
| `spec/27-spec-toolchain/98-changelog.md` row | ✅ | ✅ (v2.28.0 → v2.29.0) |
| `spec/27-spec-toolchain/99-consistency-report.md` block | ✅ | ✅ (v2.25.0 → v2.26.0) |
| §31 minor version bump | ✅ | ✅ (v1.21.0 → v1.22.0 — prose-only change) |

The asymmetry is intentional and is itself a **claim about Phase 114**: documentation-only bounding passes belong to a smaller cascade. This is the first phase in the v2 deterministic series to demonstrate that asymmetry empirically.

## Verification

All 13 strict CI gates remain green (no behavioural change to any script, workflow, or test):

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
| 9 | README inventory parity (Phase 102) | ✅ 20/20 |
| 10 | QA baseline footer (Phase 103) | ✅ 11/11 at 13-gate alignment |
| 11 | Memo retrospective headings (Phase 104) | ✅ in-scope memos / 0 forbidden headings (this memo included) |
| 12 | §27 inventory parity triangle (Phase 112) | ✅ 6/6 |
| 13 | WEIGHTS dimension-table parity (Phase 113) | ✅ 8/8 |

§27 holds at **97/100 A+** with impl=100. No score regression.

## Outcome and design property

The four parity tests in the AC-31-31 registry (Phases 102, 103, 112, 113) plus the four formally-dismissed Phase-114 candidates plus the four pre-Phase-114 dismissals (rubric changelog, per-module score table, per-AC `Verifies` clauses, grammar-library pin inventory) together form a **fully-triaged set of 12 enumeration candidates** for the current toolchain. The set is now closed; future additions either trigger the protocol (parity test required) or extend the dismissal paragraph (justification required). There is no third option.

This is the design property Phase 114 establishes: **AC-31-31's contract surface is enumerable, and Phase 114 enumerated it.** Future maintenance becomes "add to the registry or add to the dismissal list" — never "wonder if we're missing something".

## Files changed

- `spec/27-spec-toolchain/31-audit-spec-vs-code-v2.md` — version v1.21.0 → v1.22.0; AC-31-31 "Currently-NOT-qualifying enumerations" paragraph extended with the four Phase-114 dismissals + the enumeration-restatement vs API-surface-use distinction.
- `spec/27-spec-toolchain/98-changelog.md` — version v2.28.0 → v2.29.0; v2.29.0 entry added under "Changed" category.
- `spec/27-spec-toolchain/99-consistency-report.md` — version v2.25.0 → v2.26.0; v2.26.0 update block prepended.
- `.lovable/memory/audit/v2-deterministic/phase-114-ac-31-31-contract-bounding.md` — this memo.

## Followups

None required for AC-31-31 itself — registry is provisionally complete. Open queue items (carried from Phase 113's Remaining Tasks):

- **Phase 108** — orphan-classifier strategy (decision blocked)
- **B1** — `spec/22-git-logs-v2/07-app-entity.md` `App` identity columns (decision blocked)
- **R1** — Real-AI re-audit of 87 modules (Lovable Cloud blocked)
