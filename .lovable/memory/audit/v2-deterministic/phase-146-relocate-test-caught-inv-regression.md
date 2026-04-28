# Phase 146 — Relocate AC-62-04 Test, Caught Inventory Regression

**Date:** 2026-04-28
**Mode:** 🤖 Autonomous (regression repair)
**Trigger:** Forensic check on Phase 144's new file revealed it was placed in the wrong directory and silently broke Phase 112's §27 inventory parity gate.

## What went wrong
Phase 144 created `linter-scripts/test_check_spec_folder_refs.py` in the **production scripts directory**, not the canonical `linter-scripts/test/` directory. Phase 112's `test-overview-inventory-parity.sh` immediately flagged it as an untracked production orphan:

```
❌ every on-disk script is tracked in §27 overview OR Phase 107 orphan memo (INV-01)
    Untracked code files (drift since Phase 107):
      - linter-scripts/test_check_spec_folder_refs.py
```

Phases 144 and 145 both passed `check-lockstep.cjs` and `check-tree-health.cjs --strict`, but neither runs Phase 112's inventory triangle. The regression was invisible to the gates I checked.

## Action
- Moved `linter-scripts/test_check_spec_folder_refs.py` → `linter-scripts/test/test-check-spec-folder-refs.py` (matches `test-*.sh`/`test-*.py` directory convention and naming with hyphens).
- Fixed `HERE = ...parent.parent` so the test still locates `check-spec-folder-refs.py` one level up.
- Updated §27 §98 and §99 prose paths to the new location (single occurrence each).

## Verification (3 gates)
| Gate | Result |
|------|--------|
| `test-check-spec-folder-refs.py` | ✓ PASS (4/4) AC-62-04 regression locked |
| `test-overview-inventory-parity.sh` (Phase 112) | ✓ 6/6 — 34 on-disk = 31 specced + 3 known orphans (back to baseline) |
| `test-readme-inventory.sh` (Phase 102) | ✓ 20/20 — still scoped to `test-*.sh`, my `.py` test is out of scope by design |
| `check-lockstep.cjs` | ✓ PASS · 0 findings |
| `check-tree-health.cjs --strict` | ✓ PASS · 100/100 · 168/168 |

## Followups deliberately not done
- The new `.py` test is **outside Phase 102's `test-*.sh` scope** — invisible to the README parity gate. Three options exist (extend the gate to `.py`, add a manual README row, declare the file Python-test-only and exempt). All three involve §31 / AC-31-27 / Phase 102 changes that are too entangled for an autonomous pass. Logging as **Phase F3** (toolchain test-extension policy) for a future user decision.
- Likewise no §27 `NN-*.md` spec slot was assigned for this test. Tests don't currently get spec slots (the production scripts do); this matches existing `test-*.sh` precedent.

## Lesson learned (memory candidate)
**When creating any new file under `linter-scripts/`, always run `bash linter-scripts/test/test-overview-inventory-parity.sh` before declaring the phase complete.** `check-lockstep.cjs` and `check-tree-health.cjs` do NOT cover the inventory triangle. This is the same blind spot Phase 107 documented; Phase 146 hit it again.

## Status of the autonomous queue
Repaired and exhausted. Phase F3 added to the user-decision list.
