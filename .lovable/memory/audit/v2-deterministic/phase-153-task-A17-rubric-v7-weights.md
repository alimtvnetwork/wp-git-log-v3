# Phase 153 Task A17 — Rubric v7 axis-driven dimension weight cascades

**Status:** CLOSED 2026-04-30
**Predecessors:** A15 (design memo), A16 (metadata foundation: 5-axis enum injected into 23 module §00s)
**Successors:** A18 (per-axis cap refinement, conditional on A20 measurement), A19 (auditor wiring), A20 (rebaseline)
**Lesson refs:** #29 (audit-corpus pin), #36 (cross-ref ceiling), #40 (gate-graduation moat), #41 (split-phase rubric evolution)

---

## 1. Goal

Author the Rubric v7 normative contract that consumes the `content_axis` metadata A16 shipped. Three new ACs (AC-34-10/11/12) + a "Rubric v7 — Axis-driven dimension weight cascades" prose section in `spec/27-spec-toolchain/34-audit-ai-implementability.md` §97. The contract describes WHAT the auditor MUST do; A19 implements it in `linter-scripts/audit-ai-implementability.py`; A20 measures the result.

## 2. Contract surface added

### Prose section (between AC-34-09 and the Acceptance Criteria sub-header)

- Diagnosis table: 4 modules at structural 75-floor (03/12/17/25) + the rubric-v6 penalty source for each.
- **Weight cascade table**: per-axis D1–D5 multipliers, sum-normalised to 5.0:
  - `normative-contract`: D1=1.0, D2=1.5, D3=1.2, D4=0.8, D5=0.5
  - `process-guidance`: D1=1.5, D2=0.7, D3=0.8, D4=1.0, D5=1.0
  - `integration-spec`: D1=1.0, D2=0.9, D3=0.9, D4=1.4, D5=1.2 (raw 5.4 → renormalised)
  - `audit-corpus`: D1=1.0, D2=0.5, D3=0.5, D4=1.5, D5=1.5
  - `tooling-spec`: D1=1.0, D2=1.3, D3=1.0, D4=1.3, D5=0.9 (raw 5.5 → renormalised)
- **Per-axis caps + floor table**: soft caps 95 for `process-guidance`/`integration-spec`/`audit-corpus`; 100 for `normative-contract`/`tooling-spec`. **Floor (strict CI threshold) remains 60 tree-wide.**

### Three new ACs

- **AC-34-10 `[critical]`** — Axis-driven multipliers MUST be applied per module BEFORE summing; sum-renormalisation rule formalised.
- **AC-34-11 `[high]`** — Soft cap applies to band assignment only; strict CI gate threshold MUST remain 60 tree-wide; 15-point moat (Lesson #40) preserved.
- **AC-34-12 `[critical]`** — Missing or invalid `content_axis` MUST exit code 2; silent v6 fallback FORBIDDEN.

## 3. Lockstep deltas

| File | Before | After | Bump kind |
|---|---|---|---|
| slot 34 §00 | v1.2.0 | **v1.3.0** | minor (AC count 9→12, new prose section) |
| §27 §00 | v2.78.1 | **v2.79.0** | minor (slot-34 minor → module minor; contract-level surface) |
| §27 §98 | v2.78.1 | **v2.79.0** | minor (matches §00) |
| §27 §99 | v2.75.1 | **v2.76.0** | minor (matches §00) |

## 4. Out of scope (and why)

- **No AC-31-31 cascade** — that registry tracks the 14 §27 module-level parity-ACs, not per-slot AC counts.
- **No CI workflow change** — A19 wires the auditor to read `content_axis`; until A19 lands, the script falls back to v6 uniform weighting (per AC-34-12, this becomes a hard error AFTER A19; for now the v6-vs-v7 transition window is intentionally lenient).
- **No `RUBRIC_VERSION` bump** — the Rubric v7 weight contract lives INSIDE the audit script's spec, not in a CI rubric version field.
- **No gate-count change** — strict gate count remains 15.

## 5. Verification

- `grep -c "^### AC-34-1[012]" spec/27-spec-toolchain/34-audit-ai-implementability.md` → 3
- Lockstep gate: 87/87 GREEN
- Tree-health: 168/168 strict
- Version-parity: 74/74

## 6. Lessons applied / new

- **Applied #29** — `audit-corpus` axis multiplier (D2/D3 dialled to 0.5) directly codifies the spec/25 precedent.
- **Applied #36** — soft cap of 95 on `audit-corpus`/`process-guidance` acknowledges the cross-ref ceiling rather than requiring authors to "fix" inherent semantic distance.
- **Applied #40** — strict floor 60 vs current 75-floor preserves the 15-point moat across all axes; AC-34-11 codifies this so future axis additions can't accidentally weaken the gate.
- **Applied #41** — A17 ships ONLY the contract; A19 ships the wiring; A20 measures. Splitting these phases lets each layer stabilise.

## 7. Next

**A19** — extend `linter-scripts/audit-ai-implementability.py` to:
1. Read `content_axis` from each module's `00-overview.md` front-matter.
2. Apply AC-34-10's multiplier cascade with sum-renormalisation.
3. Apply AC-34-11's soft cap on band assignment.
4. Implement AC-34-12's exit-code-2 fail-fast on missing/invalid axis.

Then **A20** — rebaseline tree expected to lift 03/12/17/25 from 75 → 85+.
