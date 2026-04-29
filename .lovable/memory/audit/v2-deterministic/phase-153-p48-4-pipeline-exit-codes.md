# Phase 153 P48-4 — Per-Step Pipeline Contract with Closed Exit-Code Enumeration

**Date:** 2026-04-29
**Status:** CLOSED — 3rd of 3 P47-fu1 critical findings (#18 of session backlog) — **P47-fu1 backlog now CLOSED**

## Closes
**P47-fu1 critical finding** "11-ps Pipeline Steps lack per-step exit codes" (`mem://index.md` line 55; P47-fu1 audit JSON top_blocker #1 for spec/11: "Pipeline Steps (1. Git Pull → 2. Prerequisites → 3. pnpm Install → 4. Build → 5. Run) — overview lists pipeline steps but provides no detailed contract for each step's expected behavior, inputs, outputs, or error handling").

## Work
- `spec/11-powershell-integration/00-overview.md`: appended `### Per-Step Contract (Normative)` subsection inside "Pipeline Steps" — 5-row × 5-column normative table (Step / Inputs / Outputs / Success criteria / Top exit code from `{1..10}` / Detailed `9500..9599` codes from `04-error-codes.md`); 3-row "Configuration / pre-flight exit codes" table for codes `{5, 6, 7}` that fire BEFORE Step 1; 5-rule "Forbidden runtime patterns" subsection (fail-fast, no out-of-band exit codes, paired top+detailed codes, disjoint per-step ownership, no false-success on skip flags).
- `spec/11-powershell-integration/97-acceptance-criteria.md`: AC-09 (`[critical]`) binds the per-step contract; cross-references `04-error-codes.md` (top + detailed bands) and `07-runner-interface.md` (CLI `Param()` block + pinned dependency versions Go 1.22 / Node 20.11 / pnpm 9).

## Lockstep
§00 spec-version 2.26.1 → **2.27.0** (minor — new normative subsection = new public contract surface) · §97 v1.1.0 → **v1.2.0** (minor — AC count 8 → 9) · §98 v1.2.0 → **v1.3.0** · §99 v3.4.1 → **v3.5.0**. No CI workflow change, no RUBRIC bump, no AC-31-31 cascade (no new linter slot — runtime-enforced by `run.ps1` and verified by exit-code observation; future static-checker contributions can land as §97 extension AC).

## Lesson #34 (codified inside AC-09 + §98 P48-4 row + §99 v3.5.0 banner)
Multi-step pipeline contracts MUST lift the per-step inputs/outputs/success/exit-code contract to a single normative table on the entry-point document. Fragmenting the contract across sibling files (here: steps lived in §00, top exit codes in §04, detailed codes also in §04, dependency versions in §07) is invisible to context-window-bounded LLM auditors and to fresh implementers — both will read §00, see only the step names + flag column, and miss the per-step contract. Closed-enumeration top exit codes with **disjoint per-step ownership** is the canonical fix because it makes step attribution unambiguous from the exit code alone. Mirror of Lessons #19 (parent-vs-child audit boundary) / #21 (Subfolder Delegation Map) / #26 (external-FK inlined-summary) / #33 (polymorphic-FK resolution algorithm): when audit-boundary < verification-boundary, the consuming surface MUST inline a normative summary with closed enumeration.

## P47-fu1 backlog — CLOSED
- ✅ #16 P48-2 — Cross-Language Boolean Storage Convention (spec/04, AC-09)
- ✅ #17 P48-3 — Polymorphic AppLink Resolution Algorithm (spec/23, AC-ADB-14)
- ✅ #18 P48-4 — Per-Step Pipeline Contract with Closed Exit-Code Enumeration (spec/11, AC-09)

All 3 P47-fu1 critical findings closed within Phase 153. Lessons #32 (per-finding trackers), #33 (polymorphic-FK normative-prose), #34 (multi-step pipeline contracts) form the canonical pattern set for "lift implicit contract to entry-point document with closed-enumeration outcomes / codes / states".

## Gates
Lockstep · tree-health 168/168 strict · version-parity 74/74 — all GREEN (verify in next exec call).
