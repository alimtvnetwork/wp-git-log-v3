# Phase F3 — `.sh`-only test-discovery policy codified

**Date:** 2026-04-28
**Trigger:** User reply `next` (autonomous queue exhausted; recommended close = `Phase F3: keep sh-only`).

## Decision
User implicitly accepted recommendation. The implicit Phase 102 design intent ("test-discovery via `ls test-*.sh`") is now an explicit written policy.

**Rule:** New self-tests SHOULD be `.sh`. The only sanctioned `.py` exception is exercising an internal function (not a CLI surface) of a hyphenated `.py` source file requiring `importlib.util.spec_from_file_location`. "Better assertion library / prettier diff output" is NOT sanctioned.

## Path chosen vs alternatives
- ❌ (a) **extend gate to `.py`** — forces `test-readme-inventory.sh` and Phase 102's contract to grow multi-runtime detection logic; non-trivial; every new runtime would require the same treatment.
- ❌ (b) **manual exemption row** — one-off, doesn't generalize to a future `.py` test.
- ✅ (c) **keep `.sh`-only with documented policy** — codifies implicit Phase 102 design intent, lists `.py` exceptions in a separate "Adjacent `.py` tests" subsection.

## Files touched (lockstep)
| File | Change |
|---|---|
| `linter-scripts/test/README.md` | New "Test-discovery policy (Phase F3)" section; new "Adjacent `.py` tests" subsection listing `test-check-spec-folder-refs.py` (Phase 144); `Last updated:` banner refresh. |
| `spec/27-spec-toolchain/98-changelog.md` | v2.33.0 → **v2.34.0** with full Phase F3 row. |
| `spec/27-spec-toolchain/99-consistency-report.md` | v2.30.0 → **v2.31.0** with Phase F3 audit-row sentence. |
| `mem://index.md` | Core test-file rule extended with Phase F3 addendum (one rule, two sentences — sanctioned-`.sh` + sanctioned-`.py`-exception). |

## Gates
- `node linter-scripts/check-lockstep.cjs` → 87/87 pass · 0 findings ✅
- `node linter-scripts/check-tree-health.cjs --strict` → 168/168 strict-pass ✅
- `bash linter-scripts/test/test-readme-inventory.sh` → unchanged pass (structural anchors preserved; `.sh`-only discovery unchanged) ✅
- `bash linter-scripts/test/test-overview-inventory-parity.sh` → unchanged pass (Phase 146's `.py` relocation already satisfies enumeration exclusion) ✅

## Scope discipline (Phase F3 ONLY)
- `test-readme-inventory.sh` source untouched (still `.sh`-only by design).
- `test-overview-inventory-parity.sh` source untouched (already excludes `linter-scripts/test/`).
- No AC IDs added/removed. No DDL change. No schema bump.
- The seven existing `.sh` tests in the main inventory table are unchanged.
- The `.py` test (`test-check-spec-folder-refs.py`, Phase 144) is now formally acknowledged in README's "Adjacent `.py` tests" subsection — the README listing IS the acknowledgement contract since the parity gate excludes the directory.

## Outcome
F3 closed. Phase F2 (wire `check-spec-folder-refs.py` + Phase 144 test into CI) remains blocked on Phase F1 user verdicts on the 6 missing folders. Phase 108 / R1 still open.
