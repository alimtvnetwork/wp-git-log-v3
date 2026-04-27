---
name: phase-94-explain-contract-test
description: Phase 94 — 14-assertion CI self-test locks AC-31-23 --explain contract from silent-break regressions
type: feature
---

# Phase 94 — `--explain` Contract Self-Test

**Date:** 2026-04-27
**Status:** ✅ Complete
**Trigger:** Phase 91's "Remaining Tasks" queue item #1
**Predecessor:** Phase 91 (same pattern, applied to `--min-weighted` / `--min-impl`)

## Why

Phase 90 shipped `--explain=<substring>` as the primary contributor-facing
debugging tool — the way an author or reviewer learns *why* a module got
the score it got. Phase 91 had already established the pattern that
contributor-facing CLI surfaces need contract self-tests because they are
**not exercised by the normal audit gate**. A refactor could:

- Remove the `Branch :` line from the stdout report
- Flip the no-match exit code from 1 to 0 (silent failure)
- Drop the multi-match disambiguation warning
- Accidentally make `--explain` write to `.lovable/memory/audit/v2-deterministic/`
  (breaking the no-side-effects guarantee)

…and CI would still pass at 98.0/99.8 because no production gate ever
calls `--explain`. The contract would be silently broken until a
contributor next tried to debug a score outlier.

## Solution

Added `linter-scripts/test/test-audit-explain-contract.sh` — a 14-assertion
self-test across 4 scenarios:

### Scenario 1: Single match (`--explain=01-spec-authoring-guide`)
Asserts 6 things:
- exit code = 0
- stdout contains `Branch` line
- stdout contains `Final score` line
- stdout contains `--- Per-dimension scores ---` table
- stdout contains `--- Implementability bonuses fired` block
- stdout contains `--- Key metrics ---` block

These map to AC-31-23 (a)–(f), the 6 required structural elements.

### Scenario 2: No match (`--explain=does-not-exist-XYZ-7f3a`)
Asserts 3 things:
- exit code = 1
- stderr contains `no module matched` hint
- stdout contains NO `Branch` or `Final score` line (no rubric trace leaked)

The leaked-trace check is subtle but important: a regression where
`--explain` runs the rubric *then* checks for a match would still print
the report on no-match, defeating the exit-1 contract.

### Scenario 3: Multi match (`--explain=03-issues`)
The corpus contains exactly 2 matching modules (`05-split-db-architecture/03-issues`
and `06-seedable-config-architecture/03-issues`). Asserts 4 things:
- exit code = 0 (multi-match does not fail)
- combined output matches `matched [0-9]+ modules` warning
- both candidate paths listed
- full report for first match still printed

### Scenario 4: No side effects (sha256 snapshot)
Asserts 1 thing:
- `sha256(ls -la .lovable/memory/audit/v2-deterministic/)` before all
  `--explain` invocations equals the same hash after.

This catches any regression that makes `--explain` write the normal audit
report files. The audit script normally writes 89 files to that directory;
`--explain` MUST short-circuit before any of them.

## Tooling note

The sandbox lacks `diff` and `cmp`, so the snapshot comparison uses
`sha256sum` (always present per POSIX-base image). This is actually
*better* than `diff` for the assertion: it's a single-bit equality check
with no false positives from line-ending or sort-order quirks.

## Wired into CI

New step in `.github/workflows/spec-health.yml` immediately after the
Phase 91 self-test:

```yaml
- name: Audit --explain contract self-test (Phase 94)
  run: bash linter-scripts/test/test-audit-explain-contract.sh
```

Runs in ~6s (3 audit invocations × ~2s each in deterministic mode).

## Spec lockstep

| File | Before | After | Change |
|------|--------|-------|--------|
| `spec/27-spec-toolchain/31-audit-spec-vs-code-v2.md` | v1.10.0 | **v1.11.0** | New AC-31-25, header `Source` line lists all 3 artefacts, Category appends `+ CLI contract self-tests ×2`, rubric changelog renumbers `v2.16-test` → `v2.16-test1` and adds `v2.16-test2` |
| `spec/27-spec-toolchain/98-changelog.md` | v2.17.0 | **v2.18.0** | New 2.18.0 release entry |
| `spec/27-spec-toolchain/99-consistency-report.md` | v2.14.0 | **v2.15.0** | New v2.15.0 update banner |

## Verification

```
$ bash linter-scripts/test/test-audit-explain-contract.sh
Results: 14 passed, 0 failed
✅ --explain contract intact.

$ bash linter-scripts/test/test-audit-cli-thresholds.sh
Results: 6 passed, 0 failed
✅ Audit CLI threshold contract intact.

$ node linter-scripts/check-tree-health.cjs --strict
✓ PASS: tree health 100 ≥ threshold 100

$ node linter-scripts/check-lockstep.cjs --strict
Findings: 0
✓ PASS: lockstep gate (strict)

$ AUDIT_DETERMINISTIC=1 python3 linter-scripts/audit-spec-vs-code-v2.py \
    --min-weighted=97 --min-impl=99
Mean weighted: 98.0/100  |  Mean implementability: 99.8/100
✓ PASS: thresholds met

$ python3 linter-scripts/check-spec-cross-links.py
OK All internal spec cross-references resolve.
```

All 6 gates green: cross-links + tree-health (strict) + lockstep (strict)
+ audit floor + Phase 91 self-test + Phase 94 self-test.

## Pattern crystallised

Phases 91 and 94 together establish a clear **contributor-facing CLI
contract test pattern**:

1. The CLI surface ships first (Phase 81 for thresholds; Phase 90 for `--explain`).
2. A few phases later, after the CLI has stabilised and edge cases are known,
   ship a self-test that asserts the contract independently of absolute scores.
3. Wire into `spec-health.yml` immediately after the related production gate.
4. Add a paired AC in `spec/27-spec-toolchain/31-audit-spec-vs-code-v2.md`.
5. Renumber the rubric changelog if multiple `-test` rows accrete.

This pattern should apply to any future contributor-facing CLI flag that
isn't exercised by the production audit gate.

## Files touched

- **NEW** `linter-scripts/test/test-audit-explain-contract.sh` (executable, sha256-based no-side-effects check)
- **EDIT** `.github/workflows/spec-health.yml` (+12 lines, new CI step)
- **EDIT** `spec/27-spec-toolchain/31-audit-spec-vs-code-v2.md` (+ AC-31-25, header bump, changelog rows)
- **EDIT** `spec/27-spec-toolchain/98-changelog.md` (+ 2.18.0 release entry)
- **EDIT** `spec/27-spec-toolchain/99-consistency-report.md` (+ v2.15.0 update banner)

## Why this matters

Phases 90 and 91 paired together gave us a **debugger** + a **contract
test on a related production gate**. Phase 94 closes the loop by adding a
contract test on the **debugger itself**. The pipeline now has:

- Production gates with their CLI contracts locked (Phase 91)
- Diagnostic tools with their structural contracts locked (Phase 94)
- All locks active even though scores sit comfortably above floors

The `--explain` tool is now safe to refactor: any change that breaks the
documented contract fails CI immediately, with a per-assertion ✅/❌
summary pinpointing exactly what broke.
