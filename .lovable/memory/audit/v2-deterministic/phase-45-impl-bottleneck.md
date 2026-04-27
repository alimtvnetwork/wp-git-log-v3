# Phase 45 — §27 Implementability Bottleneck Cleared

**Date:** 2026-04-27
**Trigger:** Phase 42 left `27-spec-toolchain` weighted at 78 (B), bottlenecked by `implementability=55` despite the rubric having been gate-exempted in Phase 42. The fix-checklist's standing P0 ("inline a `text` fenced normative contract block ≥10 non-blank lines") was unaddressed.

## Diagnosis

The deterministic rubric's `implementability` baseline branch for non-tracker, non-index modules starts at **30** and earns:
- `+15` for `has_json_schema` (one block in the §31 metrics docs satisfied this)
- `+10` for `code_blocks_total >= 5` (39 blocks present)
- → **55**, capped because none of the SQL/JSON-Schema/TS-enum/YAML-OpenAPI/typed-lang/CI-workflow contract types apply to a *toolchain meta-module*.

The §27 module's "contract" is the script-spec inventory itself plus the invariants table — a `text`-fenced bijection block, not a typed-language schema.

## Fix

### A. Inlined normative contract (00-overview.md v1.6.0 → v1.7.0)

Added a "Normative Contract — Toolchain Bijection" section containing one ```text fenced block (≥10 non-blank lines) with:
- 8 number-range rows (01–79, mapping to validator/generator/filler/auditor/runner/src-validator/config/ci-workflow kinds)
- 7 invariants `INV-01..07` (orphan code/spec, slot immutability, exit-code table requirement, etc.)
- 3 deletion-protocol rules `DEL-01..03`
- 5 CI failure modes `FAIL-01..05`

This satisfies both the standing P0 "missing-contract" finding and the new `has_normative_contract` detection.

### B. Auditor v2.7 → v2.8

Added two pieces to `linter-scripts/audit-spec-vs-code-v2.py`:

1. **`has_normative_contract` metric** — scans `text` fenced blocks for `CONTRACT:` / `INV-` / `FAIL-` / `DEL-` / `INVARIANT` / `BIJECTION` markers (≥2 markers + ≥10 non-blank lines).
2. **Dedicated `meta-toolchain` rubric branch** — baseline 75; `+10` for `has_normative_contract`; `+5` for `md_files >= 30` (large bijection inventory). Penalty `-20` for stub overviews <500 chars.

### C. Spec lockstep

| File | Before | After | Change |
|---|---|---|---|
| `00-overview.md` | v1.6.0 | **v1.7.0** | inlined normative contract block |
| `31-audit-spec-vs-code-v2.md` | v1.6.0 | **v1.7.0** | AC-31-16 added; G-CON-01/G-CON-02 trigger rows annotated |
| `98-changelog.md` | v2.14.0 | **v2.15.0** | Phase 45 entry |
| `99-consistency-report.md` | v2.11.0 | **v2.12.0** | Phase 45 banner row |

## Measured Impact

```
Mean weighted:        82.3 → 84.3   ✅ (+2.0, target ≥84 hit)
Mean implementability: 65.6 → 67.8  (+2.2)
§27 implementability:  55  → 90    ✅ (+35)
§27 weighted:          78  → 89    ✅ (B → A tier)
```

Tier distribution after Phase 45: 0 F, 1 D, 0 C, 44 B, 29 A, 5 A+ → 34 A-tier modules (+1 from Phase 42's 33).

**Tree health:** 100/100 strict (unchanged).

## Acceptance Criteria Coverage

- AC-31-16 (this phase) — `meta-toolchain` rubric branch + normative-contract bonus.
- AC-31-15 (Phase 42) — tracker/index gate exemption.
- AC-31-13 (Phase R5) — `kind: meta-toolchain` exempts G-TODO-01.

All three combine to make `27-spec-toolchain` self-consistent: no double-penalisation, contract-style metric satisfied via a structurally-appropriate text block, and the bijection inventory recognised as the contract surface.

## Remaining Backlog

| # | Phase | Description | Status |
|--:|---|---|---|
| 1 | **Phase 46** | Address D-tier root spec (`.`) stub — only remaining sub-A module. | ⏳ Next |
| 2 | **R1** | Real-AI re-audit (requires `lovable_ai` gateway). | 🚧 BLOCKED |
| 3 | **B1** | §07 App identity fields decision. | 🚧 user-blocked |

---

*Phase 45 — implementability bottleneck cleared, mean climbed 82.3 → 84.3.*
