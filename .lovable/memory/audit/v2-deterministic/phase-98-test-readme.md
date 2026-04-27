---
name: phase-98-test-readme
description: Phase 98 — added the canonical inventory + onboarding README for linter-scripts/test/, locked by AC-31-27 with a copy-pasteable template for adding a 4th self-test
type: feature
---

# Phase 98 — `linter-scripts/test/` Inventory README

**Date:** 2026-04-27
**Status:** ✅ Complete
**Trigger:** Phase 95's "Remaining Tasks" queue item #2

## Why

Phases 91, 94, and 95 each shipped a CLI contract self-test for
`audit-spec-vs-code-v2.py`, and each was wired into CI as a discrete
workflow step. But the directory holding them (`linter-scripts/test/`)
had **no README** — a contributor opening it cold could see three
shell scripts but had no way to know:

1. **What each one locks** without reading 50+ lines of preamble.
2. **Why they exist** as separate gates from the production audit.
3. **How they fit together** as a coverage triad (inversion / regression / non-determinism).
4. **How to add a fourth** following the established convention.

Phase 98 fills that gap with one canonical inventory document, locked
by a new AC so the inventory cannot drift from the directory's actual
contents.

## What changed

### New file

**`linter-scripts/test/README.md`** (~180 lines, 7 sections):

1. **Why this directory exists** — the production gates check
   *repo state*, not *linter behaviour*; this directory closes that
   blind spot.
2. **Test inventory** — table with one row per script: Phase introduced,
   what it locks, assertion count, runtime, locked AC ID with relative link.
3. **Coverage triad** — maps each blind spot (comparison-operator inversion,
   `--explain` regression, non-determinism) to the test that catches it
   and explains why the production gate cannot.
4. **Adjacent gates** — discoverability for the full-tree linters in
   `linter-scripts/` proper (mermaid syntax, tree-health, lockstep,
   cross-links, trace-map).
5. **Local execution** — single + bulk run snippets, with a note about
   exit-code semantics and side-effect-free guarantees.
6. **Adding a new self-test** — copy-pasteable bash template covering
   shebang, header comment block (Phase + locked contract + blind-spot
   rationale + spec link + memo link), `set -euo pipefail`, `assert`
   helper, summary block; followed by 5 follow-up steps for CI wiring,
   inventory + triad updates, lockstep AC, and the post-merge memo.
7. **See also** — cross-links to `spec/27-spec-toolchain/31-audit-spec-vs-code-v2.md`,
   the workflow YAML, the local runner, and all 5 phase memos
   (Phases 91, 94, 95, 97, 98).

### Spec lockstep

- **`spec/27-spec-toolchain/31-audit-spec-vs-code-v2.md` v1.12.0 → v1.13.0**:
  header `Source` line now references the README as the 5th artefact;
  Category appends `+ inventory README`. New **AC-31-27** mandates:
  - The README must exist at `linter-scripts/test/README.md`.
  - Its **Test inventory** + **Coverage triad** tables must reflect the
    actual contents of the directory on every PR that touches it.
  - It must link to test scripts via relative path, to locked ACs via
    relative path into `spec/`, and to phase memos under `.lovable/memory/`.
  - It must contain the copy-pasteable "Adding a new self-test" template
    with all 5 follow-up steps so a new contributor can follow the
    convention without reverse-engineering existing scripts.
  - The last-updated date must be bumped on every modification.
- **`spec/27-spec-toolchain/98-changelog.md` v2.19.0 → v2.20.0**: new 2.20.0 release entry.
- **`spec/27-spec-toolchain/99-consistency-report.md` v2.16.0 → v2.17.0**:
  new v2.17.0 update banner; v2.16.0 banner preserved below.

## Verification

- README inventory ↔ filesystem alignment: 3 self-tests listed in the table,
  3 `test-*.sh` files present in the directory ✓
- All 8 strict gates remain green:
  - Cross-links: ✓
  - Tree-health (strict): ✓ 100/100 across 56 modules
  - Lockstep (strict): ✓ 0 findings
  - Audit `--min-weighted=97 --min-impl=99`: ✓ 98.0 / 99.8 PASS
  - Phase 91 self-test: ✓ 6/6
  - Phase 94 self-test: ✓ 14/14
  - Phase 95 self-test: ✓ 7/7
  - Phase 97 mermaid syntax: ✓ 106/106

No score regression. Documentation + lockstep AC only.

## Why this matters

The Phase 91/94/95 triad was a **technical** safety net for the audit
script's CLI contracts. Phase 98 is the **discoverability** safety net
for that triad: without a README, a future contributor adding a fourth
contract guarantee to `audit-spec-vs-code-v2.py` (or any other linter)
might:

- Forget to add a paired self-test.
- Add a self-test in a different style, breaking the assertion-count /
  exit-code conventions the existing three share.
- Forget to wire it into CI.
- Forget the lockstep AC.
- Forget the post-merge memo.

The "Adding a new self-test" section enumerates all 5 of those steps
in order, so the bar to add a new contract test is now: copy the
template, fill in the assertions, follow the checklist. AC-31-27
ensures the template stays canonical: any drift between the README and
the actual directory contents fails the lockstep gate on the next PR.

This completes a **four-phase quality-tooling lift**:

| Phase | Layer | Asks |
|---|---|---|
| 91 | Comparison operators | "Do `--min-*` flags still gate correctly?" |
| 94 | Diagnostic output | "Does `--explain` still print the right structure?" |
| 95 | Determinism | "Are scores reproducible across runs?" |
| **98** | **Discoverability** | **"Can a new contributor add a 4th self-test without reverse-engineering?"** |

## Files touched

- **NEW** `linter-scripts/test/README.md` (~180 lines)
- **NEW** `.lovable/memory/audit/v2-deterministic/phase-98-test-readme.md` (this memo)
- **EDIT** `spec/27-spec-toolchain/31-audit-spec-vs-code-v2.md` (+ AC-31-27, header bump v1.12.0 → v1.13.0)
- **EDIT** `spec/27-spec-toolchain/98-changelog.md` (+ 2.20.0 entry, header bump)
- **EDIT** `spec/27-spec-toolchain/99-consistency-report.md` (+ v2.17.0 banner; v2.16.0 banner preserved)
