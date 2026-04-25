# Master Fix Checklist — All Modules

**Generated:** 2026-04-25  
**Modules analysed:** 1  
**Total actions:** 3  
**Estimated total effort:** ~65 min (1.1 hours)

## Priority distribution

| Priority | Count | Meaning |
|:--:|---:|---|
| **P0** | 1 | Blocker — module fails AI-implementability without this |
| **P1** | 0 | High — significantly raises score |
| **P2** | 0 | Medium — clarity / hygiene |
| **P3** | 2 | Low — polish |

## Findings by category

| Category | Count |
|---|---:|
| missing-contract | 1 |
| drift | 1 |
| maintainability | 1 |

## Per-module checklists (sorted by lowest implementability first)

| Module | Score | Impl | P0 | P1 | P2 | P3 | Effort | Checklist |
|---|---:|---:|---:|---:|---:|---:|---:|---|
| `27-spec-toolchain` | 74 | 40 | 1 | 0 | 0 | 2 | 65m | [open](./27-spec-toolchain.md) |

## How to use this checklist

1. Open the lowest-implementability module first (top of table).
2. Work each action in priority order P0 → P3.
3. After each fix, the **Acceptance test** column tells you exactly how to verify the fix is done.
4. Re-run `AUDIT_DETERMINISTIC=1 python3 linter-scripts/audit-spec-vs-code-v2.py` to refresh the score.
5. Re-run `python3 linter-scripts/generate-fix-checklist.py` to regenerate this list.
