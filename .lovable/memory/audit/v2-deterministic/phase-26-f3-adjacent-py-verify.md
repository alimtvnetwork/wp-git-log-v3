# Phase 26 — F3 "Adjacent .py tests" subsection verification (No-Questions Mode 10/40)

**Date:** 2026-04-28
**Trigger:** Phase 25 close-out rec (c) — confirm `test-check-spec-folder-refs.py`
(Phase 144 sanctioned exception per F3) is properly listed.

## Method

1. `grep` README for "Adjacent" subsection + sanctioned-exception narrative.
2. Count main-table rows vs filesystem `.sh` files.
3. Count adjacent-table rows vs filesystem `.py` files.
4. Run both relevant parity gates.

## Result

| Check | Expected | Actual | Status |
|-------|----------|--------|--------|
| F3 narrative present (L107-132) | Yes | Yes | ✅ |
| "Adjacent `.py` tests" H3 (L136) | Yes | Yes | ✅ |
| Adjacent table row for `test-check-spec-folder-refs.py` | 1 | 1 | ✅ |
| Main inventory `.sh` rows | 10 | 10 | ✅ |
| Filesystem `.py` tests in `test/` | 1 | 1 | ✅ |
| `test-readme-inventory.sh` | 26 pass | 26 pass | ✅ |
| `test-overview-inventory-parity.sh` | 6 pass | 6 pass | ✅ |

## Decision: No-op (verification confirmed clean)

The F3 codification landed correctly in Phase 146 (relocated from
`linter-scripts/` root to `linter-scripts/test/`) with proper README
documentation. No drift, no fixes.

## Files touched

None — read-only verification + this memo.

## Lesson

When Core memory codifies a sanctioned-exception class, periodically
verify the documenting README still segregates the exception from the
parity-gated inventory. This sweep confirms F3 has held since
Phase 146 — pattern works as designed.
