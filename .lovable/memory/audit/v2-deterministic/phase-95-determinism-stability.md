---
name: phase-95-determinism-stability
description: Phase 95 — 7-assertion CI self-test runs the audit twice and asserts byte-identical raw-results.json, locking the AUDIT_DETERMINISTIC=1 guarantee from non-determinism regressions invisible to single-run gates
type: feature
---

# Phase 95 — Determinism / JSON-Stability Self-Test

**Date:** 2026-04-27
**Status:** ✅ Complete
**Trigger:** Phase 91's "Remaining Tasks" queue item #2

## Why

Determinism is the **cornerstone** of the entire CI quality bar. Phase 81's
`--min-weighted=97 --min-impl=99` floors only make sense if scores are
reproducible across runs. If a contributor adds a `time.time()` call, an
unsorted `set()` iteration, or a hash-seeded random sample to any
per-module field, scores would jitter on every CI run — and the production
audit gate **could not catch it by construction** because it runs the
audit only **once** per CI build. There's no second run to compare against.

This is the same blind-spot pattern as Phases 91 and 94:

- Phase 91 caught silent inversions of `--min-weighted` / `--min-impl`
  comparison operators (production gate runs above floor, can't see the bug)
- Phase 94 caught silent breakage of `--explain` (production gate never
  invokes it)
- Phase 95 catches silent non-determinism (production gate runs once, has
  nothing to compare)

## Solution

Added `linter-scripts/test/test-audit-deterministic-stability.sh` — a
7-assertion self-test that:

1. Runs the audit twice with `AUDIT_DETERMINISTIC=1`, identical args, identical CWD.
2. Snapshots `raw-results.json` after each run.
3. Asserts:
   - **Both runs exit 0** — pipeline didn't error out.
   - **Both runs wrote `raw-results.json`** — file is the deterministic artefact under test.
   - **`sha256(run1) == sha256(run2)`** — the byte-identity guarantee. Single bit difference → fail.
   - **Byte sizes match** — secondary truncation-catcher independent of hash.
   - **Both JSON files parse and contain ≥80 modules** — catches catastrophic corruption.
   - **Module counts match between runs** — catches non-deterministic skipping.
   - **Modules sorted by name** — catches removal of the `sorted(results, key=lambda r: r["module"])` line.

## Tooling note

The CI sandbox lacks `diff`, so the test uses `sha256sum` for byte-equality
(more robust than `diff` anyway: no false positives from line-ending or
encoding quirks). On byte-identity failure, the test falls back to a
pure-bash `paste`+`awk` line-diff that prints up to 20 differing line
pairs. No external binary dependencies.

## Wired into CI

New step in `.github/workflows/spec-health.yml` immediately after the
Phase 94 self-test:

```yaml
- name: Audit determinism / JSON-stability self-test (Phase 95)
  run: bash linter-scripts/test/test-audit-deterministic-stability.sh
```

Runs in ~10s (two full audit passes × ~5s each in deterministic mode).

**CI gate count: 6 → 7.**

## Spec lockstep

| File | Before | After | Change |
|------|--------|-------|--------|
| `spec/27-spec-toolchain/31-audit-spec-vs-code-v2.md` | v1.11.0 | **v1.12.0** | New AC-31-26, header `Source` line lists all 4 artefacts, Category appends `incl. determinism`, rubric changelog gains `v2.16-test3` row |
| `spec/27-spec-toolchain/98-changelog.md` | v2.18.0 | **v2.19.0** | New 2.19.0 release entry |
| `spec/27-spec-toolchain/99-consistency-report.md` | v2.15.0 | **v2.16.0** | New v2.16.0 update banner |

## Verification

```
$ bash linter-scripts/test/test-audit-deterministic-stability.sh
Run 1: AUDIT_DETERMINISTIC=1 python3 audit-spec-vs-code-v2.py
  ✅ Run 1 exited 0
  ✅ Run 1 wrote raw-results.json
Run 2: same invocation
  ✅ Run 2 exited 0
  ✅ raw-results.json sha256 identical across both runs
      sha256: fdba5f8753dc633413f87e003a2f20d41800dc3a2577f18fc4ba0530db792c6d
  ✅ raw-results.json byte size identical (160675 bytes)
  ✅ Both runs contain 87 valid module entries
  ✅ Modules sorted by name (deterministic ordering)
Results: 7 passed, 0 failed
✅ Determinism contract intact (raw-results.json is byte-identical).
```

All 7 CI gates green:
- cross-links: ✓
- tree-health (strict): 100/100
- lockstep (strict): 0 findings
- audit floor: 98.0 / 99.8 ✓
- Phase 91 self-test: 6/6 ✓
- Phase 94 self-test: 14/14 ✓
- Phase 95 self-test: 7/7 ✓ ← NEW

## Pattern completion

Phases 91 + 94 + 95 together form a **complete blind-spot coverage triad**
for the audit subsystem:

| Blind spot | Why production gate misses it | Self-test |
|---|---|---|
| Comparison-operator inversion | Production scores sit above floor; bug invisible | Phase 91 (6 cases) |
| Diagnostic tool silently broken | Production gate never invokes `--explain` | Phase 94 (14 assertions) |
| Non-determinism introduced | Production gate runs only once | Phase 95 (7 assertions) |

Future patches to `audit-spec-vs-code-v2.py` are now safe to refactor:
any change that breaks any documented contract fails CI immediately, with
per-assertion ✅/❌ output pinpointing exactly what broke.

## Files touched

- **NEW** `linter-scripts/test/test-audit-deterministic-stability.sh` (executable, 7 assertions, sha256-based)
- **EDIT** `.github/workflows/spec-health.yml` (+13 lines, new CI step)
- **EDIT** `spec/27-spec-toolchain/31-audit-spec-vs-code-v2.md` (+ AC-31-26, header bump, rubric changelog row)
- **EDIT** `spec/27-spec-toolchain/98-changelog.md` (+ 2.19.0 release entry)
- **EDIT** `spec/27-spec-toolchain/99-consistency-report.md` (+ v2.16.0 update banner; v2.14/v2.15 banners preserved)
