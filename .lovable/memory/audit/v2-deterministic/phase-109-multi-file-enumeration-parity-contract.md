---
name: phase-109-multi-file-enumeration-parity-contract
description: Phase 109 — generalised the lessons from Phases 99 / 102 / 103 into AC-31-31 at §27 — the abstract "multi-file enumeration parity" pattern with 3-file trigger threshold, binary obligation (parity self-test OR exemption-comment in EACH restatement), seed inventory of 2 existing parity tests, explicitly-NOT-qualifying enumerations, and declarative-not-meta-linter-enforced rationale; pure contract generalisation, no code change, CI gate count unchanged at 11
type: feature
---

# Phase 109 — Multi-File Enumeration Parity Contract (generalises Phases 99 / 102 / 103)

**Date:** 2026-04-27
**Status:** ✅ Complete
**Trigger:** Last turn's "Remaining Tasks #2" — generalise the Phase 103 footer-parity self-test pattern into a meta-rule for any future numbered list spanning 3+ files.

## Why this matters

Three independent recent phases re-discovered the same lesson:

- **Phase 99** added a CI-gate-count footer to `00-index.md`; the count was hand-maintained and immediately drifted as Phases 102/103/104 added gates.
- **Phase 102** caught a missing `linter-scripts/test/README.md` inventory row on its own first run — the README's own table had drifted from the filesystem.
- **Phase 103** built `test-qa-baseline-footer.sh` to assert 4-way parity (script `RUBRIC_VERSION` ↔ `00-index.md` footer ↔ `EXECUTIVE-SUMMARY.md` ↔ workflow step list).

Each phase fixed its own incident but none codified the underlying invariant. Without a named pattern, the next gate-author — adding (say) a security-pin enumeration that crosses `package.json` + a contributor doc + a CI workflow — would re-discover the lesson by experiencing the same drift incident.

AC-31-31 names the pattern, sets a precise trigger (3+ files), and gives gate-authors a binary checklist (parity test OR exemption comments in every restatement).

## What changed

### 1. New AC-31-31 at §27

Inserted after AC-31-30 in `spec/27-spec-toolchain/31-audit-spec-vs-code-v2.md`. Specifies in `Given/When/Then/And/Verifies` form:

- **Trigger threshold**: an enumeration restated **verbatim or by count** in **3 or more files** across `linter-scripts/` + `spec/` + `.github/workflows/` + `.lovable/memory/audit/v2-deterministic/` + `package.json` + generated audit outputs. Threshold rationale: 2 = direct lockstep already covered by `check-lockstep.cjs`; 1 = no parity concern; every drift bug Phases 99–104 caught crossed exactly 3+ files.
- **Binary obligation** when the threshold fires: either author a `linter-scripts/test/test-<name>-parity.sh` self-test (wired into `spec-health.yml` + row in `linter-scripts/test/README.md`) OR document an exemption with `<!-- enumeration-parity-exempt: <reason> -->` HTML comments in EACH restatement.
- **Current registered parity-test inventory** as a markdown table:
  | Enumeration | Restated in (N files) | Parity test | Trigger phase | Locks AC |
  |---|---|---|---|---|
  | CI gates (count + names) | script + 2 audit outputs + workflow (= 4) | `test-qa-baseline-footer.sh` | Phase 103 | AC-31-28 |
  | `linter-scripts/test/` inventory | README + filesystem + workflow (= 3) | `test-readme-inventory.sh` | Phase 102 | AC-31-27 |
- **Currently-NOT-qualifying enumerations** (documented to bound the contract surface): the rubric changelog (single source of truth — restated nowhere else); per-module score table in `00-index.md` (single-source generator output, Phase 95 determinism covers stability); per-AC `Verifies` clauses (each AC owns its own clause). The grammar-library pin inventory at AC-31-30 spans only 2 sites today (`package.json` + the inventory table) and so falls under direct lockstep — *if* a third site is ever added (e.g. a contributor doc enumerating the pins), AC-31-31 fires and a parity test is required.
- **3-step protocol** for adding a new enumeration crossing the threshold: (a) author parity self-test or exemption comment in all N restatements, (b) add a row to the registry, (c) bump §27 §97/§98/§99 in lockstep.
- **Declarative-not-meta-linter-enforced rationale**: detecting "is this an enumeration that crosses 3+ files?" requires semantic understanding the toolchain doesn't have. Reviewer attention against the registry is the right enforcement layer; the rule's value is giving reviewers a named pattern to point at and gate-authors a checklist to follow.

### 2. Lockstep §31 / §98 / §99

- §31: v1.18.0 → **v1.19.0** (header `Source` gains "multi-file enumeration parity contract (Phase 109)" as 10th artefact; Category appends `+ multi-file enumeration parity contract`; AC-31-31 added).
- §98: v2.25.0 → **v2.26.0** with the Phase 109 release notes block.
- §99: v2.22.0 → **v2.23.0** with the v2.23.0 update narrative.

### 3. No code, no new self-test, no new CI step

The two existing parity self-tests (Phase 102 + Phase 103) already cover the entire current inventory. They are the empirical evidence that motivated the generalisation, and they now serve as the seed inventory of AC-31-31's registry. Future parity tests will extend the table; the table itself never needs a parity test (single source of truth).

CI gate count remains **11**; `RUBRIC_VERSION` remains **v2.20**.

## What this enables

- **Future gate-author checklist**: "Does my new enumeration cross 3 files? If yes → parity self-test (or exemption comments). If no → direct lockstep is sufficient."
- **Reviewer pattern-name**: drift incidents now have a named root cause to cite in PR reviews ("this violates AC-31-31 — please add a parity self-test or exemption comment").
- **Bounded scope**: explicitly enumerating the NOT-qualifying cases prevents future scope creep into single-source data (rubric changelog, score tables, per-AC `Verifies`).

## Why Phases 99 / 102 / 103's predictions were correct

All three phases independently arrived at "drift is inevitable when a value crosses 3+ files; mechanical parity is the only sustainable defence". AC-31-31 simply records that consensus as a named contract instead of leaving it implicit in three separate memos.

## Verification

- Cross-links: OK (no new internal links broken)
- Tree-health: 100/100 strict (no file additions/deletions outside spec text)
- Lockstep: 0 findings strict (§31 v1.19.0 ↔ §98 v2.26.0 ↔ §99 v2.23.0 all advanced together with matching dates)
- Audit `--min-weighted=97 --min-impl=99`: ✓ at 98.0/99.8 (no rubric/script change)
- Phase 91/94/95 self-tests: 6/14/7 ✅ (no script behaviour change)
- Phase 97 mermaid: 106/106 ✓
- Phase 102 self-test: 16/16 ✅ (no `linter-scripts/test/` filesystem change)
- Phase 103 self-test: 11/11 ✅ (no `RUBRIC_VERSION` / footer / workflow change)
- Phase 104 meta-linter: ✅ — **8 in-scope memos** (Phases 100–107 + 109), 0 forbidden headings (this memo passes its own rule)

## Files touched

- `spec/27-spec-toolchain/31-audit-spec-vs-code-v2.md` (header + AC-31-31)
- `spec/27-spec-toolchain/98-changelog.md` (header + Phase 109 release block)
- `spec/27-spec-toolchain/99-consistency-report.md` (header + v2.23.0 narrative)
- `.lovable/memory/audit/v2-deterministic/phase-109-multi-file-enumeration-parity-contract.md` (this memo)

## Score impact

None. No rubric change, no script change, no CI gate added. Pure declarative contract generalisation — closes the lessons of Phases 99 / 102 / 103 into a named, enforceable-by-review pattern.
