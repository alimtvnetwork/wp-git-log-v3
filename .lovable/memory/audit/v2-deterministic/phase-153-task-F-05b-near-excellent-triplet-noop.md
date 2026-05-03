# Phase 153 Task F-05b — Near-EXCELLENT triplet no-op (spec/01 + spec/05 + spec/17)

**Status:** CLOSED 2026-05-03 (no-op — all 9 findings classified as Lesson #74 LLM hallucinations against existing pins)
**Modules:** spec/01-spec-authoring-guide (89), spec/05-split-db-architecture (89), spec/17-consolidated-guidelines (88)
**Trigger:** F-04 v8 baseline flagged these three as "single normative AC away from EXCELLENT"; cache inspection contradicts the framing.

## Per-module finding triage

### spec/01-spec-authoring-guide (89/100 GOOD, axis=process-guidance)

| Severity | Dim | Finding | Auditor `fix` | Verdict |
|---|---|---|---|---|
| HIGH | D3 | Linter Script Implementation Gap | "Cite AC-SAG-30 and treat as harness-artifact" | **Hallucination** — AC-SAG-30 already exists (§97 line 334, shipped A24-fu21) and does exactly what auditor recommends |
| MEDIUM | D3 | Walker-Pin Saturation Risk | "Spec already includes Walker-Pin teaser in 00-overview.md" | **Self-contradicting** — auditor's own `fix` confirms the pin exists; flagged as gap regardless |
| LOW | D1 | Version/Phase Discrepancy | "Cite AC-SAG-31: inlined-schema versions follow per-contract authoring phase" | **Hallucination** — AC-SAG-31 already exists (§97 line 344, shipped A24-fu21) |

### spec/05-split-db-architecture (89/100 GOOD, axis=normative-contract)

| Severity | Dim | Finding | Auditor `fix` | Verdict |
|---|---|---|---|---|
| HIGH | D5 | Unresolved External Dependency: Coding Guidelines | "Inline PascalCase and FK naming rules into §97" | **Forbidden by Lesson #36** — those rules are owned by spec/02 + spec/04; restating them here creates dual-source drift |
| MEDIUM | D3 | Missing Concurrency Handling for Non-Go Languages | "Add Polyglot Implementation section…driver-specific connection strings for Python/TS/Rust" | **Forbidden by Lesson #36** — concurrency contract is owned by spec/13 AC-22; spec/05 already cross-references per Phase 153 P3 |
| LOW | D1 | Ambiguous 'ProjectSlug' Source | "Standardize on a single registry table name…and field name" | **Hallucination** — already standardized: §1-fundamentals line 142 declares `Project` table with `Slug TEXT UNIQUE NOT NULL` (line 148); `projectSlug` in Go signatures is standard Go camelCase param naming for the `Project.Slug` value |

### spec/17-consolidated-guidelines (88/100 GOOD, axis=process-guidance)

| Severity | Dim | Finding | Auditor `fix` | Verdict |
|---|---|---|---|---|
| HIGH | D5 | Broken Cross-References to Source Folders | "Ensure referenced source folders are…clearly marked as external/future" | **Self-confirming-no-op** — spec/17 IS the consolidated-guidelines index by definition; cross-references to source modules ARE the contract surface |
| MEDIUM | D3 | Truncated Content in Consolidated Guidelines | "Provide full content of all consolidated guideline files" | **Walker-window byte-cap artifact** (Lesson #74) — files are complete on disk; bundle truncation is the auditor's window limit |
| LOW | D1 | Version Parity Logic Complexity | "Include source code or detailed logic/schemas for sync scripts" | **Forbidden by Lesson #36** — sync scripts are owned by spec/27 slot registry |

## Pattern

**9/9 findings (100%) are non-actionable**: 4 are Lesson #74 LLM hallucinations against existing pins, 4 are Lesson #36 violations if remediated, 1 is a self-contradicting auditor recommendation. Zero productive levers across all three modules.

This confirms the F-04 v8 baseline framing of these modules as "near-EXCELLENT — one normative AC away" was **incorrect** for the same reason A24-fu49 found spec/22 stuck at 87: maximal walker-pins are already in place; LLM auditor saturates at 88-89 against the spec's own existing contract surface.

## No spec edits

- No §97 changes (would violate Lesson #36 if applied as auditor recommended).
- No §00/§98/§99 banner bumps (no content shipped).
- No new ACs.
- No CI / RUBRIC / AC-31-31 / gate-count changes.

All 5 strict gates remain GREEN: lockstep 87/87 · tree-health 168/168 strict · version-parity 74/74 · freshness 81+6+0 · folder-refs 0.

## Lessons reinforced

- **#74 (3rd full-baseline instance)**: when 3 of 23 modules saturate at 88-89 with all surfaced findings being hallucinations against existing pins, the saturation IS the architectural ceiling for those axis classes. Stacking more pins to "satisfy" the LLM would push real contract content past the bundle cap.
- **#36 (architectural-recommendation-rejection)**: when LLM auditor's `fix` field recommends inlining a cross-module contract, the recommendation is **always** rejected — `fix` is advisory; Lesson #36 is normative.
- **NEW Lesson #75 — Near-EXCELLENT band (88-89) + saturated walker = exhausted lever class.** The "one normative AC away from EXCELLENT" framing in F-04-style baselines is misleading when the surfaced findings are ALL non-actionable. A module's score-band reflects walker visibility + LLM saturation, NOT contract gap density. **Three-way diagnostic test** (codify before opening lift phase): for each cache finding, check (a) does the recommended AC already exist? (Lesson #30 — verify before opening); (b) does the `fix` violate Lesson #36? (cross-module restate); (c) does the `fix` violate Lesson #74? (re-pin a maximal walker-pin). If 100% of findings fail at least one check, classify as exhausted-lever and ship a no-op consolidation memo.

## Cross-references

- F-04 v8 baseline: `phase-153-task-F-04-v8-rebaseline.md`
- A24-fu49 spec/22 precedent: 1st full-baseline Lesson #74 instance
- F-05 spec/04 precedent: 2nd full-baseline instance (had 1 productive finding amid 2 hallucinations)
- F-05b (this memo): 3rd–5th instances — all 9 findings non-actionable
- AC-SAG-30, AC-SAG-31: `spec/01-spec-authoring-guide/97-acceptance-criteria.md` lines 334, 344 (already shipped)
