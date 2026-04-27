---
name: phase-103-qa-baseline-footer-test
description: Phase 103 — added `test-qa-baseline-footer.sh` (5th CLI self-test) that mechanically enforces AC-31-28 via 4-way enumeration consistency between RUBRIC_VERSION, 00-index.md, EXECUTIVE-SUMMARY.md, and spec-health.yml workflow steps; CI gate count 9 → 10
type: feature
---

# Phase 103 — QA Baseline Footer Self-Test

**Date:** 2026-04-27
**Status:** ✅ Complete
**Trigger:** Discovered Phase 99/102 — AC-31-28 mandated rubric-version + gate-enumeration emission but had no mechanical enforcement; the AC's own `Verifies` clause said "lockstep with workflow", which is reviewer-attention only.

## Why

Phase 99 introduced AC-31-28 + the `RUBRIC_VERSION` constant + the "QA
tooling baseline" footer that enumerates the strict CI gates surrounding
the audit score. Phase 102 added a 9th gate (and bumped the footer
accordingly). But neither phase mechanised the contract — it lived
entirely on reviewer attention:

- A contributor adds a 10th workflow step but forgets to bump the footer
  → production audit gate still passes, docs lie.
- A contributor bumps `RUBRIC_VERSION` in the script but forgets to
  re-run audit → header drifts from constant.
- A contributor changes `00-index.md` "9" to "10" but forgets to add
  the gate-row → declared count ≠ row count.

All three would silently pass CI. Phase 103 closes that loop with a
**4-way enumeration consistency test**.

## What changed

### 1. New self-test — `linter-scripts/test/test-qa-baseline-footer.sh`

11 assertions, ~3 s runtime. Algorithm:

1. **Re-run the audit** in deterministic mode (so we test the latest
   emission, not a stale cached one). Doubles as a smoke test.
2. **Source-of-truth extraction**: parse `RUBRIC_VERSION = "v…"` from
   the script.
3. **Header parity**: assert `**Rubric:** $RUBRIC_FROM_SCRIPT` appears
   verbatim in both `00-index.md` and `EXECUTIVE-SUMMARY.md`.
4. **No drift**: re-extract from each file independently; assert all
   three match (script ↔ 00-index ↔ EXECUTIVE-SUMMARY).
5. **Section presence**: assert `## QA tooling baseline` exists in
   `00-index.md`.
6. **Declared count**: extract integer N from "one of **N strict CI
   gates**".
7. **Row count**: parse the section with awk, count lines matching
   `^[0-9]+\. \*\*` until the next `## ` header. Assert `ROW_COUNT == N`.
8. **Workflow lockstep**: count `- name:` steps in `spec-health.yml`
   matching the 10 known gate-name patterns. Assert
   `WORKFLOW_GATES == N`.
9. **Onboarding link**: assert `linter-scripts/test/README.md`
   reference is preserved.
10. **Cross-summary**: assert `EXECUTIVE-SUMMARY.md` references the
    same N-gate baseline.

The 4-way alignment (script constant ↔ 00-index ↔ EXECUTIVE-SUMMARY ↔
workflow) means **any** of the four sources drifting fails the test.

### 2. CI wiring — `spec-health.yml`

New step `Self-test QA baseline footer (Phase 103)` after the Phase 102
README inventory parity step. The new step IS the 10th quality gate the
test counts.

### 3. Audit script v2.18 → v2.19

Per AC-31-28 (now extended): any change altering the QA-baseline
enumeration MUST bump `RUBRIC_VERSION`. Updates:

- `RUBRIC_VERSION` `"v2.18"` → `"v2.19"`.
- Footer regenerated with **10 strict CI gates** (was 9); added gate
  #10 row pointing to `test-qa-baseline-footer.sh` (Phase 103).
- Section title "Phase 99, expanded Phase 102" → "Phase 99, expanded
  Phases 102 + 103".
- Annotation now references self-test suite (#5–#7, #9, #10).
- `EXECUTIVE-SUMMARY.md` cross-reference: 9 → 10 gates.

### 4. README updated — `linter-scripts/test/README.md`

- 5th row in **Test inventory** (linking `test-qa-baseline-footer.sh`,
  Phase 103, AC-31-28, 11+ assertions, ~3 s).
- 5th row in **Coverage triad** matrix (blind spot: "QA-baseline
  footer drifting from RUBRIC_VERSION / workflow / declared count" —
  was reviewer-attention only).
- Local-execution snippet expanded to 5 scripts.
- See-also memo list extended with Phase 103.
- Totals: 5 scripts · 52+ assertions · ~25 s of CI time.
- "Last updated" header → Phase 103.

### 5. Spec lockstep — §27

- **§31 v1.15.0 → v1.16.0**: header `Source` gains the new self-test
  as the 7th artefact; Category bumped from `×4 incl. determinism +
  README parity` to `×5 incl. determinism + README parity + footer
  parity`; **AC-31-28** updated to enumerate **10 strict CI gates**
  (was 9), to cite the Phase 103 self-test as the
  mechanical-enforcement artefact (replacing weak "lockstep with
  workflow" language with strong "MUST be detected by the Phase 103
  self-test"), and to extend the `RUBRIC_VERSION` bump rule to cover
  gate-count enumeration changes; rubric changelog table extended
  through **v2.19** with Phase 103 row.
- **§98 v2.22.0 → v2.23.0**: new 2.23.0 release entry.
- **§99 v2.19.0 → v2.20.0**: new v2.20.0 update banner.

## Verification

All 10 strict gates green:

- **Cross-links:** ✓
- **Tree-health (strict):** ✓ 100/100 across 56 modules
- **Lockstep (strict):** ✓ 0 findings
- **Audit (`--min-weighted=97 --min-impl=99`):** ✓ 98.0 / 99.8 PASS
- **Phase 91 self-test:** ✓ 6/6
- **Phase 94 self-test:** ✓ 14/14
- **Phase 95 self-test:** ✓ 7/7 (new sha256 stable across 2 runs after
  the v2.18 → v2.19 RUBRIC_VERSION + footer rollover)
- **Phase 97 mermaid:** ✓ 106/106
- **Phase 102 self-test:** ✓ 14/14 (5 fs-files / 5 README entries)
- **Phase 103 self-test:** ✓ 11/11 (script v2.19 / 10 declared gates /
  10 footer rows / 10 workflow steps — 4-way alignment intact)

CI gate count rises **9 → 10**. No scoring change.

## Why this matters

Phase 103 closes the audit-subsystem QA stack at a **fixed point**:
every AC-31-* now has either a paired CLI self-test, a paired full-tree
linter, or an algorithmic enforcement inside the audit script itself.
The 4-way enumeration consistency pattern is the most-general form —
any future "must mirror across N sources" contract can adopt the same
shape (extract from each source independently, assert all-equal).

The five self-test patterns now crystallised:

| # | Pattern | Examples | Test shape |
|---|---|---|---|
| 1 | **Behaviour contract** | Phases 91, 94 | Crafted args → assert exit code + stdout structure |
| 2 | **Determinism contract** | Phase 95 | sha256 byte-identity across N runs |
| 3 | **Inventory contract** | Phase 102 | Symmetric set diff between two enumerations |
| 4 | **Multi-source consistency contract** | **Phase 103** | Extract value from N sources independently, assert all-equal |

Every future contract self-test should fit one of these four shapes
(or extend the table with a 5th).

The Phase 103 test also has a useful **side-property**: because it
re-runs the audit before asserting, it catches any silent regression in
the summary-output emitter (e.g. a future refactor that drops the
`**Rubric:**` line entirely would fail here, even though the production
audit gate would still pass with valid `raw-results.json`).

## Files touched

- **NEW** `linter-scripts/test/test-qa-baseline-footer.sh` (11 assertions, executable)
- **EDIT** `linter-scripts/test/README.md` (+ 5th inventory row + 5th coverage-triad row + local-exec line + see-also memo + totals + header date bump to Phase 103)
- **EDIT** `linter-scripts/audit-spec-vs-code-v2.py` (RUBRIC_VERSION v2.18 → v2.19; QA-baseline footer 9 → 10 gates with new gate #10 row; section title + annotation updated; EXECUTIVE-SUMMARY.md reference updated)
- **EDIT** `.github/workflows/spec-health.yml` (+ new step `Self-test QA baseline footer (Phase 103)` after the Phase 102 step)
- **EDIT** `spec/27-spec-toolchain/31-audit-spec-vs-code-v2.md` (header v1.15.0 → v1.16.0; Source +7th artefact; Category bump; AC-31-28 enumeration 9 → 10 gates + cites Phase 103 self-test as enforcement; rubric changelog +v2.19 row)
- **EDIT** `spec/27-spec-toolchain/98-changelog.md` (+ 2.23.0 entry, header bump)
- **EDIT** `spec/27-spec-toolchain/99-consistency-report.md` (+ v2.20.0 banner; v2.19.0 banner preserved)
- **REGEN** `.lovable/memory/audit/v2-deterministic/00-index.md` + `EXECUTIVE-SUMMARY.md` + `raw-results.json` (new sha256 stable, post-rollover)
- **NEW** `.lovable/memory/audit/v2-deterministic/phase-103-qa-baseline-footer-test.md` (this memo)
