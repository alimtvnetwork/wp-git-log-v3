---
name: phase-102-readme-inventory-test
description: Phase 102 — added `test-readme-inventory.sh` self-test that mechanically enforces AC-31-27 (linter-scripts/test/README.md inventory ↔ filesystem parity); CI gate count rises 8 → 9; RUBRIC_VERSION v2.17 → v2.18
type: feature
---

# Phase 102 — README Inventory Parity Self-Test

**Date:** 2026-04-27
**Status:** ✅ Complete
**Trigger:** Discovered Phase 98 — AC-31-27 mandated the `linter-scripts/test/` inventory README stay aligned with filesystem contents, but enforcement was reviewer-attention only. A future contributor adding a 5th `test-foo.sh` could ship without updating the README and no gate would catch it.

## Why

Phase 98 established `linter-scripts/test/README.md` as the canonical
inventory + onboarding doc for the audit-script CLI contract self-tests
(Phases 91/94/95). AC-31-27 mandated the README's "Test inventory" table
list every `test-*.sh` present, with executable bits, structural
sections, and the "Adding a new self-test" template.

But AC-31-27's enforcement was entirely **social**: code review.
A contributor could:

- Add a 5th `test-foo.sh` and forget to add the README row.
- Remove an existing test and forget to remove the row.
- Forget to mark a new test executable.
- Delete one of the four required structural sections.

None of these would fail any CI gate. Phase 102 closes that loop.

## What changed

### 1. New self-test — `linter-scripts/test/test-readme-inventory.sh`

14 assertions, ~1 s runtime. Algorithm:

1. **Filesystem set:** `ls test-*.sh` in the directory, sorted.
2. **README set:** `grep -oE '\(\./test-[a-z0-9-]+\.sh\)' README.md` →
   extract filenames from inventory table markdown links → sort -u.
3. **Symmetric diff via `comm`:** assert MISSING_FROM_README is empty
   AND EXTRA_IN_README is empty. Print missing/extra lists on failure.
4. **Per-script:** assert each filesystem script is linked in README +
   has executable bit set.
5. **Structural sections:** assert README contains `## Test inventory`,
   `## Coverage triad`, `## Adding a new self-test`, and a
   `**Last updated:**` line.

### 2. Self-bootstrapping verification

The first run **failed by design**: the new script
`test-readme-inventory.sh` was on disk but not yet in the README's
inventory. The script reported:

```
❌ every test-*.sh on disk appears in README inventory
    Missing from README: test-readme-inventory.sh
❌ README links to ./test-readme-inventory.sh
Results: 12 passed, 2 failed
```

This is the desired behaviour — the gate caught its own missing
inventory row, forcing the README to be updated in the same PR. After
adding the 4th row, all 14 assertions passed.

### 3. CI wiring — `spec-health.yml`

New step `Self-test README inventory parity (Phase 102)` added after
the Phase 97 mermaid syntax gate. Comment block (12 lines) explains
the blind spot it covers and notes the self-bootstrapping behaviour.

### 4. README updated — `linter-scripts/test/README.md`

- 4th row in **Test inventory** table (linking `test-readme-inventory.sh`,
  Phase 102, AC-31-27, 14+ assertions, ~1 s).
- 4th row in **Coverage triad** table (blind spot: "Self-test
  added/removed without updating this README" — was reviewer-attention
  only; now mechanically enforced).
- Local-execution snippet expanded to all 4 scripts.
- See-also memo list extended with Phase 102.
- Totals updated: 4 scripts · 41+ assertions · ~22 s of CI time.
- "Last updated" header bumped to *Phase 102*.

### 5. `audit-spec-vs-code-v2.py` v2.17 → v2.18

Per AC-31-28: any change that alters the QA-tooling-baseline
enumeration MUST bump `RUBRIC_VERSION`, even if it's metadata-only.
Updates:

- `RUBRIC_VERSION` string `"v2.17"` → `"v2.18"`.
- "QA tooling baseline" footer in `00-index.md` regenerated to
  enumerate **9 strict CI gates** (was 8); added gate #9 row for
  `test-readme-inventory.sh` (Phase 102).
- Section title "Phase 99" → "Phase 99, expanded Phase 102".
- Annotation "Inventory + onboarding for the self-test triad (#5–#7)"
  → "self-test suite (#5–#7, #9)".
- `EXECUTIVE-SUMMARY.md` footer reference: 8 → 9 gates, "Phase 99" →
  "Phase 99, expanded Phase 102".

`RUBRIC_VERSION` remains a static string — Phase 95 determinism
self-test re-validates byte-identical output (new sha256 stable across
two runs after the one-time rollover).

### 6. Spec lockstep — §27

- **§31 v1.14.0 → v1.15.0**: header `Source` line gains the new
  self-test as the 6th artefact; Category bumped from `×3 incl.
  determinism` to `×4 incl. determinism + README parity`; **AC-31-27**
  `Verifies` line gains the Phase 102 self-test as the
  mechanical-enforcement artefact; **AC-31-28** updated to enumerate
  9 strict CI gates (was 8) including the new Phase 102 row; rubric
  changelog table extended through **v2.18** (Phase 102 row).
- **§98 v2.21.0 → v2.22.0**: new 2.22.0 release entry.
- **§99 v2.18.0 → v2.19.0**: new v2.19.0 update banner; v2.18.0 banner
  preserved.

## Verification

All 9 strict gates green:

- **Cross-links:** ✓
- **Tree-health (strict):** ✓ 100/100 across 56 modules
- **Lockstep (strict):** ✓ 0 findings
- **Audit (`--min-weighted=97 --min-impl=99`):** ✓ 98.0 / 99.8 PASS
- **Phase 91 self-test:** ✓ 6/6
- **Phase 94 self-test:** ✓ 14/14
- **Phase 95 self-test:** ✓ 7/7 (new sha256 stable across 2 runs after
  the one-time rollover from the v2.17 → v2.18 RUBRIC_VERSION + footer
  text change)
- **Phase 97 mermaid:** ✓ 106/106
- **Phase 102 self-test:** ✓ 14/14 (4 fs-files / 4 README entries /
  parity intact)

CI gate count rises **8 → 9**. No scoring change — new safety net +
output-clarity only.

## Why this matters

Phase 102 closes the **last reviewer-attention-only contract** in the
audit-subsystem QA stack. Every AC-31-* now has either a paired CLI
self-test, a paired full-tree linter, or an algorithmic enforcement
inside the audit script itself.

Three patterns crystallise from Phases 91 + 94 + 95 + 102:

| Pattern | Examples | Test shape |
|---|---|---|
| **Behaviour contract** (input → output) | Phases 91, 94 | Drive the script with crafted args; assert exit code + stdout structure |
| **Determinism contract** (run twice → identical) | Phase 95 | sha256 byte-identity across N runs |
| **Inventory contract** (README ↔ filesystem) | **Phase 102** | Symmetric set diff between two enumerations |

Future contract self-tests should pick the matching shape and reuse
the `assert` helper + summary block conventions from any of the four
existing scripts.

The self-bootstrapping property is also notable: the script's first
run failed because IT was the missing inventory entry. This is a
**stronger correctness signal** than a script that passes on first
run — it proves the gate is wired to the real check, not to a
tautology.

## Files touched

- **NEW** `linter-scripts/test/test-readme-inventory.sh` (14 assertions, executable)
- **EDIT** `linter-scripts/test/README.md` (+ 4th inventory row + 4th coverage-triad row + local-exec line + see-also memo + totals + header date bump to Phase 102)
- **EDIT** `linter-scripts/audit-spec-vs-code-v2.py` (RUBRIC_VERSION v2.17 → v2.18; QA-baseline footer 8 → 9 gates; section title "Phase 99" → "Phase 99, expanded Phase 102"; EXECUTIVE-SUMMARY.md reference updated)
- **EDIT** `.github/workflows/spec-health.yml` (+ new step `Self-test README inventory parity (Phase 102)` after the Phase 97 step)
- **EDIT** `spec/27-spec-toolchain/31-audit-spec-vs-code-v2.md` (header v1.14.0 → v1.15.0; Source +6th artefact; Category bump; AC-31-27 `Verifies` extended; AC-31-28 enumeration 8 → 9 gates; rubric changelog +v2.18 row)
- **EDIT** `spec/27-spec-toolchain/98-changelog.md` (+ 2.22.0 entry, header bump)
- **EDIT** `spec/27-spec-toolchain/99-consistency-report.md` (+ v2.19.0 banner; v2.18.0 banner preserved)
- **REGEN** `.lovable/memory/audit/v2-deterministic/00-index.md` + `EXECUTIVE-SUMMARY.md` + `raw-results.json` (new sha256 stable, post-rollover)
- **NEW** `.lovable/memory/audit/v2-deterministic/phase-102-readme-inventory-test.md` (this memo)
