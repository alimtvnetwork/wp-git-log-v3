# Phase 46 — Root Spec D-Tier Cleanup

**Date:** 2026-04-27
**Trigger:** Phase 45 completed with 1 remaining D-tier module: the root `spec/.` (weighted 59, implementability 30).

## Diagnosis

Three independent issues collided at the root:

1. **Frontmatter mismatch.** `spec/00-overview.md` declared `kind: future-spec` — a custom value the deterministic auditor did not recognise, defaulting it to the contract-required rubric branch (baseline 30). The body banner separately said `**Kind:** index`, creating a dual-source ambiguity.
2. **Missing acceptance criteria.** No `spec/97-acceptance-criteria.md` existed. `ac_count=0` triggered the `G-AC-01` testability cap and dropped completeness by 25 points.
3. **Auditor blind spot.** The auditor's `CHILDREN` map only populated when paths contained `/`, so the root `.` always saw `child_modules=0` even though 25+ top-level folders are obviously its children. Index-router rubric awards `+10 impl` and `+10 completeness` when `child_modules > 0` — the root never got either.

## Fix

### A. Auditor v2.8 → v2.9

Patched `linter-scripts/audit-spec-vs-code-v2.py`:
- `CHILDREN["."]` now collects every top-level spec folder (modules whose `MOD_REL` has no `/`).
- Root spec module itself is excluded from the children list to avoid self-recursion.

### B. Root `00-overview.md` v3.4.0 → v3.5.0

- Frontmatter `kind: future-spec` → **`kind: index`** (canonical).
- Removed the conflicting body line `**Kind:** index` (frontmatter is now sole source of truth).
- Banner `Updated: 2026-04-26` → `2026-04-27`.

### C. New `spec/97-acceptance-criteria.md` v1.0.0

8 GWT acceptance criteria:
- **AC-ROOT-01** Module-inventory bijection vs filesystem
- **AC-ROOT-02** Locked-vacant slot immutability (08, 09)
- **AC-ROOT-03** SpecTreeIndex JSON-Schema validity
- **AC-ROOT-04** Slug + path pattern enforcement
- **AC-ROOT-05** Status enum closed set
- **AC-ROOT-06** Supporting-files presence
- **AC-ROOT-07** Frontmatter `kind: index` requirement (cross-refs AC-31-15)
- **AC-ROOT-08** Lockstep enforcement (cross-refs §27/24)

### D. Lockstep

| File | Before | After |
|---|---|---|
| `spec/00-overview.md` | v3.4.0 (`kind: future-spec`) | **v3.5.0** (`kind: index`) |
| `spec/97-acceptance-criteria.md` | _missing_ | **v1.0.0** (8 ACs) |
| `spec/98-changelog.md` | v3.4.1 | **v3.5.0** |
| `spec/99-consistency-report.md` | v4.0.1 | **v4.1.0** |

## Measured Impact

```
Root implementability:  30 → 80   ✅ (+50)
Root weighted:          59 → 92   ✅ (D → A, +33)
D-tier modules:          1 → 0    ✅
Mean weighted:        84.3 → 84.7 ✅ (target ≥84 maintained)
Mean implementability: 67.8 → 68.5
```

Tier distribution after Phase 46: 0 F, 0 D, 0 C, 44 B, 30 A, 5 A+ → 35 A-tier modules.

## Verification

- Tree health: **100/100 strict** (54/54 modules full marks).
- Lockstep gate: **79/79 pass**, 0 findings.
- Deterministic re-run: **byte-identical** (`md5: 2d62b3...92e6`).

## Remaining Backlog

| # | Description | Status |
|---|---|---|
| **R1** | Real-AI re-audit (requires `lovable_ai` gateway) | 🚧 BLOCKED |
| **B1** | §07 App identity fields decision | 🚧 user-blocked |

The deterministic audit cycle is complete: every module is now B-tier or above, mean weighted 84.7, no D/F outliers, no C-tier bottlenecks, no broken-link false positives, no contract-gate false positives.

---

*Phase 46 — root D-tier cleared, mean climbed 84.3 → 84.7, no sub-B modules remain.*
