---
name: phase-105-grammar-library-pin-pattern
description: Phase 105 — generalised AC-SAG-25 (mermaid + jsdom exact-pin rule, Phase 101) into AC-31-30 at §27 — the abstract "grammar-defining-library pinning pattern" for any future linter-scripts gate built on a parser/schema/AST library; pure declarative contract generalisation, no code change, CI gate count unchanged at 11
type: feature
---

# Phase 105 — Grammar-Defining-Library Pin Pattern (generalises AC-SAG-25)

**Date:** 2026-04-27
**Status:** ✅ Complete
**Trigger:** Discovered Phase 101 — AC-SAG-25 was authored as a one-off response to a concrete pain point (`mermaid` 11 → 12 silent grammar drift), but the underlying principle ("any library that defines the grammar of a CI gate's inputs MUST be exact-pinned") generalises cleanly to any future gate. Without an explicit general rule at §27, a future contributor adding a TypeScript-AST or JSON-Schema gate could re-introduce caret ranges and rediscover the same pain.

## Why this matters

AC-SAG-25 lives at §01-spec-authoring-guide because its concrete instance (`mermaid` + `jsdom`) powers a spec-authoring gate that authors interact with daily. But the reasoning behind it has nothing to do with the spec-authoring guide specifically — it is a **toolchain-wide invariant**:

> "Any library used by a `linter-scripts/` gate to parse, schema-validate, or AST-walk a spec artefact defines the grammar that gate enforces. Caret/tilde ranges on such a library turn the gate from a quality signal into a flaky one."

This invariant belongs at §27 (the spec-toolchain home) so any future gate-author finds it without having to reverse-engineer it from a §01 mermaid-specific AC. The §01 instance and the §27 abstract pattern complement each other: §01 stays prescriptive about the mermaid+jsdom case (where the rule was first felt), §27 owns the abstract pattern + the inventory of currently-pinned libraries + the addition protocol for new ones.

## What changed

### 1. New AC-31-30 at §27

Inserted after AC-31-29 in `spec/27-spec-toolchain/31-audit-spec-vs-code-v2.md`. Specifies in `Given/When/Then/And/Verifies` form:

- **Trigger condition** for inclusion: a script under `linter-scripts/` (production OR self-test) `import`s/`require`s the library AND uses it to inspect spec content. Libraries used only by `src/` (the app preview) or as transitive deps of unrelated tooling do NOT qualify.
- **Current pinned inventory** as a markdown table:
  | Library | Pin | Used by | Trigger phase | AC |
  |---|---|---|---|---|
  | `mermaid` | `11.14.0` | `check-mermaid-syntax.mjs` | Phase 97 + 101 | AC-SAG-24, AC-SAG-25 |
  | `jsdom` | `20.0.3` | `check-mermaid-syntax.mjs` | Phase 97 + 101 | AC-SAG-24, AC-SAG-25 |
- **Explicitly-NOT-qualifying examples**: `typescript`, `tailwindcss`, `react`, `vite`, `@vitejs/*` (used by `src/` only — no `linter-scripts/` script imports them); `ajv` is not yet a dependency. Documenting non-qualifiers prevents future scope creep.
- **Major-vs-minor/patch bump rules** inherited from AC-SAG-25.
- **Four-step protocol** for adding a new gate built on a previously-unpinned library:
  1. Tighten the library's `package.json` entry to an exact pin in the same PR.
  2. Add a row to AC-31-30's inventory table.
  3. Author a per-library instance AC at the appropriate spec section, modelled on AC-SAG-25.
  4. Bump §27 §98/§99 in lockstep.
- **Declarative-not-CI-enforced rationale**: silent grammar drift is intrinsically a pre-merge phenomenon (an unpinned `^` range corrupts `bun.lock` *before* CI sees the PR), so a runtime gate would only detect drift after the damage is done. Reviewer attention against the inventory + per-library instance ACs is the right enforcement layer.

### 2. AC-SAG-25 cross-reference

`spec/01-spec-authoring-guide/97-acceptance-criteria.md` AC-SAG-25 `Verifies` clause extended to point to AC-31-30 as the abstract general form. AC-SAG-25 stays unchanged in its mermaid+jsdom-specific contract; the cross-reference makes the relationship discoverable from either direction.

### 3. §31 header bump

§31 v1.17.0 → v1.18.0:
- `Source` line gains `package.json` grammar-defining-library pin block as the 9th artefact (Phase 105).
- Category appends `+ grammar-library pin contract`.

### 4. Lockstep cascade

| File | From | To |
|------|------|----|
| `spec/27-spec-toolchain/31-audit-spec-vs-code-v2.md` | v1.17.0 | **v1.18.0** |
| `spec/27-spec-toolchain/98-changelog.md` | v2.24.0 | **v2.25.0** |
| `spec/27-spec-toolchain/99-consistency-report.md` | v2.21.0 | **v2.22.0** |
| `spec/01-spec-authoring-guide/97-acceptance-criteria.md` | v4.4.0 | **v4.5.0** |
| `spec/01-spec-authoring-guide/98-changelog.md` | v4.9.0 | **v4.10.0** |
| `spec/01-spec-authoring-guide/99-consistency-report.md` | v4.6.0 | **v4.7.0** |

### 5. No code change

Phase 105 is a pure declarative contract generalisation. Zero lines of script changed. Zero new CI step. Zero new self-test. **CI gate count remains 11**; **`RUBRIC_VERSION` remains v2.20**.

## Verification

| Gate | Result |
|------|--------|
| Cross-links | ✅ all resolve |
| Tree-health (strict) | ✅ 100/100 — all 56 modules at full marks |
| Lockstep (strict) | ✅ 0 findings (87 modules pass) |
| Audit `--min-weighted=97 --min-impl=99` | ✅ 98.0 / 99.8 |
| Phase 91 CLI threshold self-test | ✅ 6/6 |
| Phase 94 `--explain` self-test | ✅ 14/14 |
| Phase 95 determinism self-test | ✅ 7/7 — sha256 unchanged from Phase 104 (script untouched) |
| Phase 97 mermaid syntax | ✅ 106/106 |
| Phase 102 README inventory parity | ✅ 16/16 |
| Phase 103 QA baseline footer self-test | ✅ 11/11 — alignment intact |
| Phase 104 memo retrospective headings | ✅ 5 in-scope memos / 0 forbidden headings (this memo included) |

`package.json` inventory check: `mermaid` `11.14.0` exact ✓; `jsdom` `20.0.3` exact ✓; no `^` or `~` on either; `bun.lock` resolved versions match.

## Score impact

None. No rubric change shipped — pure spec lockstep + AC addition.
- Audit mean: 98.0 / 99.8 unchanged.
- §27 holds at 97/100 A+ with impl=100.
- §01 holds at 97/100 A+ with impl=100.
- `RUBRIC_VERSION` = v2.20 unchanged (script untouched).
- Phase 95 determinism sha256 unchanged from Phase 104 (script untouched).

## Files touched

- `spec/27-spec-toolchain/31-audit-spec-vs-code-v2.md` (header `Source`/`Category`; AC-31-30 added with full inventory table)
- `spec/27-spec-toolchain/98-changelog.md` v2.24.0 → v2.25.0
- `spec/27-spec-toolchain/99-consistency-report.md` v2.21.0 → v2.22.0
- `spec/01-spec-authoring-guide/97-acceptance-criteria.md` (AC-SAG-25 `Verifies` cross-reference; v4.4.0 → v4.5.0)
- `spec/01-spec-authoring-guide/98-changelog.md` v4.9.0 → v4.10.0
- `spec/01-spec-authoring-guide/99-consistency-report.md` v4.6.0 → v4.7.0

No script files touched. No `linter-scripts/` change. No `.github/workflows/` change. No `package.json` change (the existing pins from Phase 101 already satisfy the new general rule).

## What this enables

- **Predictable gate-authoring**: any future contributor adding a new `linter-scripts/` gate built on a parser/schema/AST library has a single canonical reference (AC-31-30) explaining what they MUST do with the underlying library's `package.json` entry, plus a four-step protocol that keeps inventory + ACs + lockstep in sync.
- **Inventory as living document**: the markdown table inside AC-31-30 is itself a `Verifies` artefact — keeping it in lockstep with `package.json` is now part of the spec contract, not just an implicit convention.
- **Scope-creep prevention**: explicitly enumerating *non*-qualifying libraries (typescript, react, vite, …) makes it harder for a well-meaning future contributor to over-pin the dependency tree.
- **Pattern reuse beyond pins**: Phase 105 demonstrates the general lift "concrete AC at module-of-first-use → abstract pattern at toolchain-home + back-link" — a template applicable to any future cross-cutting toolchain invariant first felt as a one-off.

## Why Phase 101's prediction was correct

Phase 101 chose to scope AC-SAG-25 narrowly to the mermaid+jsdom pair rather than pre-emptively generalise. That was correct on the merits at the time — pre-emptive generalisation without a second instance risks over-fitting the abstract rule to the first concrete case. Phase 105 lifts the rule now that we have evidence (a) the underlying invariant is real and (b) future gate-additions will encounter it. The `Verifies` cross-reference from AC-SAG-25 to AC-31-30 preserves the original contract intact; no rewrite, only an explicit pointer.
