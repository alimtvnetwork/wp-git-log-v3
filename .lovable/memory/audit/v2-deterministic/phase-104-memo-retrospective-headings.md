---
name: phase-104-memo-retrospective-headings
description: Phase 104 — added `check-memo-retrospective-headings.py` meta-linter that mechanises Phase 100's retired-cadence verdict + new AC-31-29; scans phase memos with `NNN ≥ 100` and FAILS on forward-looking H2/H3 headings (Next phases, Remaining Tasks, Future work, TODO, Roadmap, …); CI gate count 10 → 11; RUBRIC_VERSION v2.19 → v2.20
type: feature
---

# Phase 104 — Memo Retrospective-Heading Meta-Linter

**Date:** 2026-04-27
**Status:** ✅ Complete
**Trigger:** Discovered Phase 100 — the freshness-sweep cadence was retired with empirical evidence ("Phase 90+ memos resist drift"), but the *retired pattern itself* (forward-looking "Next phases" / "Remaining Tasks" sections inside memos) was enforced only by reviewer attention. A future contributor could silently reintroduce the staleness pattern that motivated retirement.

## Why this matters

Phase 100 inspected memos 96-99 and concluded the freshness-sweep cadence was unnecessary going forward — recent memos were already self-contained retrospectives. The verdict cited two failure modes of forward-looking memo sections:

1. **Drift**: a "Next phases" list inside Phase N's memo becomes stale the moment Phase N+1 lands. Phases 78-83 all shipped with `## Next phases (queued)` lists that were stale by Phase 84.
2. **Authority confusion**: pending work belongs in exactly ONE place — the chat reply's "Remaining Tasks" table. A second list inside a memo competes with that single source of truth.

Phase 100's verdict was retrospective ("retire the cadence"), but the rule it implied ("memos at or above Phase 100 MUST be retrospective") was never written down or mechanised. Phases 101, 102, 103 happened to comply — but the discipline lived entirely in author attention.

## What changed

### 1. New meta-linter — `linter-scripts/check-memo-retrospective-headings.py`

Module-level constants:
- `MEMO_DIR = Path(".lovable/memory/audit/v2-deterministic")`
- `CUTOFF_PHASE = 100` — single-line edit if cadence ever changes
- `FORBIDDEN_PATTERNS` — 8 case-insensitive regexes covering: `Next phases?`, `Next iterations?`, `Next Recommended …`, `Remaining (work|tasks?|backlog)`, `Future (work|iterations?|phases?)`, `TODO`, `Upcoming`, `Roadmap`
- `SUGGESTED_REPLACEMENTS` — 7 retrospective alternatives printed on failure

Algorithm:
1. Glob `phase-*.md` in `MEMO_DIR`, parse the leading `phase-NNN-` integer.
2. For each memo with `NNN ≥ CUTOFF_PHASE`, scan H2/H3 headings (`^(#{2,3})\s+(.+?)$`) and apply the forbidden-pattern table to the heading text.
3. On any match: print file + line + raw heading + matched pattern label. Exit 1.
4. On clean scan: print summary (`N memos scanned, 0 forbidden headings`), exit 0.

Exit codes: `0` clean, `1` violations, `2` structural error.

### 2. CI wiring — `spec-health.yml`

New step `Memo retrospective headings gate (Phase 104)` after the Phase 103 QA baseline footer self-test. The new step IS the 11th quality gate the audit footer enumerates.

### 3. Audit script v2.19 → v2.20

- `RUBRIC_VERSION` `"v2.19"` → `"v2.20"`.
- "QA tooling baseline" footer in `00-index.md` regenerated to enumerate **11 strict CI gates** (was 10); added gate #11 row pointing to `check-memo-retrospective-headings.py` (Phase 104).
- Section title "Phase 99, expanded Phases 102 + 103" → "Phase 99, expanded Phases 102 + 103 + 104".
- `EXECUTIVE-SUMMARY.md` cross-reference: 10 → 11 gates.

### 4. Phase 103 self-test extended

`linter-scripts/test/test-qa-baseline-footer.sh` awk pattern table extended with `/Memo retrospective headings/ {n++}`. The test now checks 4-way alignment at **11**: declared count ↔ footer rows ↔ workflow steps ↔ `RUBRIC_VERSION`. The Phase 103 test functions as the meta-gate that catches any future drift between the 11 sources of truth.

### 5. Spec lockstep — §27

- §31 v1.16.0 → v1.17.0: `Source` line gains 8th artefact; Category gains `+ memo retrospective-heading meta-linter`; AC-31-28 enumeration 10 → 11; **AC-31-29 added** (formalises the Phase 100 retired-cadence verdict — `Given/When/Then/And/Verifies` with explicit cutoff semantics, forbidden-pattern list, and suggested replacements).
- Rubric changelog extended through **v2.20** with Phase 104 row.
- §98 v2.23.0 → v2.24.0; §99 v2.20.0 → v2.21.0.

## Verification

| Gate | Result |
|------|--------|
| Cross-links | ✅ all resolve |
| Tree-health (strict) | ✅ 100/100 — all 56 modules at full marks |
| Lockstep (strict) | ✅ 0 findings (87 modules pass) |
| Audit `--min-weighted=97 --min-impl=99` | ✅ 98.0 / 99.8 |
| Phase 91 CLI threshold self-test | ✅ 6/6 |
| Phase 94 `--explain` self-test | ✅ 14/14 |
| Phase 95 determinism self-test | ✅ 7/7 — sha256 `95a19ceb…` stable |
| Phase 97 mermaid syntax | ✅ 106/106 |
| Phase 102 README inventory parity | ✅ 16/16 |
| Phase 103 QA baseline footer self-test | ✅ 11/11 — script v2.20 / 11 declared / 11 footer rows / 11 workflow steps |
| **Phase 104 memo retrospective headings** | ✅ 4 in-scope memos / 0 forbidden headings |

**Detection smoke test** — temporarily lowered `CUTOFF_PHASE` to 78 and re-ran: linter correctly flagged 9 historical forward-looking H2 headings in phases 78, 79, 80, 81, 82, 83, 92, 93, 96 (all `## Next phases (queued)` or `## Next iteration`). Restored cutoff to 100; 0 in-scope violations.

## Score impact

None. No rubric change shipped — pure CI safety net + output clarity.
- Audit mean: 98.0/99.8 unchanged.
- New `RUBRIC_VERSION` is metadata-only.
- One-time sha256 rollover (Phase 99 → 102 → 103 → 104) absorbed by Phase 95 determinism test.

## Files touched

- `linter-scripts/check-memo-retrospective-headings.py` (created, 121 lines, executable)
- `linter-scripts/audit-spec-vs-code-v2.py` (RUBRIC_VERSION v2.19→v2.20; 11-gate footer; 11-gate cross-ref)
- `linter-scripts/test/test-qa-baseline-footer.sh` (awk pattern table extended)
- `.github/workflows/spec-health.yml` (new gate step after Phase 103)
- `spec/27-spec-toolchain/31-audit-spec-vs-code-v2.md` v1.16.0 → v1.17.0 (header `Source`/Category; AC-31-28 11-gate; **AC-31-29** added; rubric changelog v2.20 row)
- `spec/27-spec-toolchain/98-changelog.md` v2.23.0 → v2.24.0
- `spec/27-spec-toolchain/99-consistency-report.md` v2.20.0 → v2.21.0
- `.lovable/memory/audit/v2-deterministic/{00-index.md, EXECUTIVE-SUMMARY.md, raw-results.json}` (regenerated by audit run)

## What this enables

A new meta-pattern: **"verdict from a freshness-sweep memo is a candidate AC".** Phase 100's verdict ("the cadence is retired") was a behavioural prescription for future authors. Phase 104 demonstrates that such verdicts can — and should — graduate into mechanically enforced ACs the moment they touch a stable artefact (here, the memo directory). Future verdicts of the same shape ("X discipline is sufficient; we don't need Y safeguard") should be evaluated for the same lift.

## Why Phase 100's prediction was correct

Phase 100 predicted the retired cadence would "compound naturally" without sweeps as long as authors held to the retrospective convention. Phases 101, 102, 103 each shipped retrospective-only memos with zero exceptions — Phase 104's linter run confirms 0 forbidden headings across all 4 in-scope memos. The Phase 100 verdict was correct on the merits; Phase 104 turns it from custom into contract.
