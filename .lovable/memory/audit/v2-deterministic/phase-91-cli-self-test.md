---
name: phase-91-cli-self-test
description: Phase 91 — CLI threshold contract self-test locks v2.12 exit-code semantics from silent-inversion regressions
type: feature
---

# Phase 91 — Audit CLI threshold contract self-test

**Date:** 2026-04-27
**Status:** ✅ Complete
**Trigger:** Phase 89's "Remaining Tasks" queue item #2

## Problem

The v2.12 (Phase 81) `--min-weighted=N` / `--min-impl=N` CLI flags are the
only thing standing between us and silent quality regressions in the audit
gate. They live in a 20-line tail block of `audit-spec-vs-code-v2.py`:

```python
for a in sys.argv[1:]:
    if a.startswith("--min-weighted="):
        ...
    elif a.startswith("--min-impl="):
        ...
sys.exit(1 if failed else 0)
```

A future refactor could trivially:
- flip `<` to `>` in the comparison
- invert `failed` boolean
- short-circuit out of the check
- accidentally swallow the exit code

…and **CI would still pass**, because all 87 modules currently sit at
**98.0 / 99.8** — comfortably above the production floor of **97 / 99**.
The contract would be silently broken. We'd only discover it during the
next genuine quality regression, when the gate fails to catch it.

## Solution

Added `linter-scripts/test/test-audit-cli-thresholds.sh` — a 6-case
shell self-test that:

1. **Doesn't depend on absolute scores.** Uses `--min-weighted=200`
   (impossible) and `--min-weighted=0` (trivial) so it works regardless
   of whether mean weighted is 60 or 100.
2. **Locks the comparison-operator contract.** If a refactor inverts the
   logic, the impossible-floor case stops failing and the test catches it.
3. **Locks the logical-OR semantics.** Case (f) confirms that a mixed
   floor (one breachable, one not) still fails the run.
4. **Pure side-effect-free.** No file writes, no AI calls
   (`AUDIT_DETERMINISTIC=1`).

Six cases:
| # | Args | Expected | Locks |
|---|------|----------|-------|
| a | `--min-weighted=200` | exit 1 | unsatisfiable weighted breaches |
| b | `--min-weighted=0` | exit 0 | satisfiable weighted passes |
| c | `--min-impl=200` | exit 1 | unsatisfiable impl breaches |
| d | `--min-impl=0` | exit 0 | satisfiable impl passes |
| e | `--min-weighted=0 --min-impl=0` | exit 0 | combined satisfiable |
| f | `--min-weighted=0 --min-impl=200` | exit 1 | logical-OR breach |

## Wired into CI

New step in `.github/workflows/spec-health.yml` immediately after the
existing audit gate:

```yaml
- name: Audit CLI threshold contract self-test (Phase 91)
  run: bash linter-scripts/test/test-audit-cli-thresholds.sh
```

Runs in ~3s (one full audit pass per case, but deterministic mode + no
AI calls keeps it fast).

## Spec lockstep

| File | Before | After | Change |
|------|--------|-------|--------|
| `spec/27-spec-toolchain/31-audit-spec-vs-code-v2.md` | v1.9.0 | **v1.10.0** | New AC-31-24, header `Source` line + Category updated, rubric changelog gains `v2.16-test` row |
| `spec/27-spec-toolchain/98-changelog.md` | v2.16.0 | **v2.17.0** | New 2.17.0 release entry |
| `spec/27-spec-toolchain/99-consistency-report.md` | v2.13.0 | **v2.14.0** | New v2.14.0 update banner |

## Verification

```
$ bash linter-scripts/test/test-audit-cli-thresholds.sh
Results: 6 passed, 0 failed
✅ Audit CLI threshold contract intact.

$ node linter-scripts/check-tree-health.cjs --strict --report
✓ PASS: tree health 100 ≥ threshold 100

$ node linter-scripts/check-lockstep.cjs --strict
Findings: 0
✓ PASS: lockstep gate (strict)

$ AUDIT_DETERMINISTIC=1 python3 linter-scripts/audit-spec-vs-code-v2.py \
    --min-weighted=97 --min-impl=99
Mean weighted: 98.0/100  |  Mean implementability: 99.8/100
✓ PASS: thresholds met
```

## Files changed

- **NEW** `linter-scripts/test/test-audit-cli-thresholds.sh` (executable)
- **EDIT** `.github/workflows/spec-health.yml` (+10 lines, new CI step)
- **EDIT** `spec/27-spec-toolchain/31-audit-spec-vs-code-v2.md` (+ AC-31-24, header bump, changelog row)
- **EDIT** `spec/27-spec-toolchain/98-changelog.md` (+ 2.17.0 release entry)
- **EDIT** `spec/27-spec-toolchain/99-consistency-report.md` (+ v2.14.0 update banner)

## Why this matters (the broader pattern)

This is a **contract test** in the property-based-testing tradition: it
doesn't assert specific values, it asserts that the relationship between
inputs and outputs holds. The relationship under test is:

> "Floor of N fails iff mean < N."

That property is the entire reason the flag exists. Without locking it
in CI, the flag's value erodes silently whenever scores rise above the
floor — and right now the slack is **+1.0 weighted, +0.8 impl**, plenty
of room for a refactor to introduce a bug nobody notices.

Phase 91 closes that gap permanently.
