---
name: phase-111-documentation-cadence-retirement-pattern
description: Phase 111 — generalised Phase 100's empirical retired-cadence verdict + Phase 104's mechanical enforcement gate into AC-SAG-26 at §01 — the abstract "documentation-cadence retirement" pattern with 3+1 trigger threshold, retirement protocol (memo + canonical replacement SoT + CUTOFF marker + mechanical gate + §27 AC + lockstep), seed registry of 1 retirement (forward-looking phase-memo H2/H3 sections, Phase 100/104), and declarative-with-mechanical-companions rationale; pure declarative contract generalisation, no code change, CI gate count unchanged at 11
type: feature
---

# Phase 111 — Documentation-Cadence Retirement Pattern (generalises Phase 100 + Phase 104)

**Date:** 2026-04-27
**Status:** ✅ Complete
**Trigger:** Last turn's "Remaining Tasks #3" — generalise Phase 100's "retired-cadence" verdict + Phase 104's mechanical gate into an abstract pattern at §01 (mirroring how Phase 105 generalised Phase 101 into AC-31-30 and how Phase 109 generalised Phases 99/102/103 into AC-31-31).

## Why this matters

Phase 100 made a specific empirical observation: the "Next phases (queued)" / "Remaining Tasks" cadence in phase memos was structurally fragile — every memo Phases 78–83 / 92–93 / 96 carried such a section, and every one was stale by the time the next phase landed. Phase 100 retired the cadence; Phase 104 mechanised the retirement with `linter-scripts/check-memo-retrospective-headings.py` (CUTOFF_PHASE = 100 + forbidden H2/H3 patterns).

But the Phase 100/104 sequence was authored as a one-off response to a concrete pain point. The underlying *pattern* — "when does a documentation cadence become more drift-producing than informational, and what's the protocol for retiring it?" — was never extracted. A future contributor encountering an analogous fragile cadence (say, recurring "Open questions" sections in `99-consistency-report.md` files that get answered in subsequent files but never deleted from the originals) would have to reverse-engineer the protocol from the Phase 100 + Phase 104 memos and might skip the mechanical-gate step that prevents silent regression.

AC-SAG-26 names the pattern, sets a precise trigger (3+ historical instances + 1 of 3 drift conditions), and gives the next contributor a binary checklist (memo + canonical replacement SoT + CUTOFF + mechanical gate + §27 AC + lockstep + registry row).

## What changed

### 1. New AC-SAG-26 at §01

Inserted after AC-SAG-25 in `spec/01-spec-authoring-guide/97-acceptance-criteria.md`. Specifies in `Given/When/Then/And/Verifies` form:

- **Trigger threshold**: 3 historical instances of the cadence + AT LEAST 1 of 3 concrete drift conditions:
  - (a) ≥ 2 instances where cadence content was stale within 1 phase of being written;
  - (b) ≥ 1 instance where two adjacent artefacts contradicted each other on the same cadence-controlled item;
  - (c) the information has migrated to a single canonical SoT (tracker, registry, AC `Verifies` clause, generated artefact) that the cadence now duplicates.

  Threshold rationale: 1–2 instances = isolated authorship lapse; 3+ with no drift = working cadence; 3+ with drift = structurally fragile.

- **Retirement protocol** — a retirement memo that MUST contain: cadence definition; empirical evidence; canonical replacement SoT; CUTOFF marker (phase number, date, version, sequence number); explicit out-of-scope statement for pre-cutoff artefacts.

- **Mechanical enforcement gate** — same PR (or immediately following) MUST author a `linter-scripts/check-<retired-cadence>.{py,cjs,mjs,sh}` gate that: scans new artefacts post-cutoff; FAILS on the retired structural signature; carries `CUTOFF_PHASE` (or equivalent) as a module-level constant; is wired into `spec-health.yml`; is specced under §27 per INV-01; has a corresponding §27 `Verifies` AC.

- **Current registered retirements** as a markdown table:
  | Retired cadence | Class | Memo | Gate | CUTOFF | Phase |
  |---|---|---|---|---|---|
  | Forward-looking H2/H3 sections (`Next phases`, `Remaining Tasks`, `Future work`, `Roadmap`, `TODO`, `Upcoming`) | phase memos under `.lovable/memory/audit/v2-deterministic/` | `phase-100-memo-freshness-sweep-96-99.md` | `linter-scripts/check-memo-retrospective-headings.py` (locks AC-31-29) | `CUTOFF_PHASE = 100` | Phase 100 (verdict) + Phase 104 (gate) |

- **Canonical replacement SoT** for the registered retirement: the **chat-reply "Remaining Tasks" table** maintained per turn by the AI agent, with code-locked items captured in their respective AC `Verifies` clauses. Single-writer surface that cannot drift relative to itself (unlike per-memo cadence sections, which by construction can disagree across N memos).

- **Declarative-with-mechanical-companions** rationale: the AC defines the retirement *protocol*; reviewer attention against the registry catches new retirements that bypass it. A meta-meta-linter for "what cadences should be retired?" would require longitudinal repository analysis the toolchain doesn't perform.

### 2. Lockstep §97 / §98 / §99 at §01

- §97: v4.5.0 → **v4.6.0** (AC-SAG-26 added; header `Updated:` annotation extended with Phase 111 summary)
- §98: v4.10.0 → **v4.11.0** (Phase 111 release block prepended)
- §99: v4.7.0 → **v4.8.0** (Phase 111 v4.8.0 update narrative prepended)

### 3. No code, no new gate, no §27 changes

The mechanical gate that enforces the seed retirement (Phase 104's `check-memo-retrospective-headings.py`) is unchanged — Phase 111 simply codifies the *pattern* it instantiates. The seed registry has 1 row; future retirements extend the table.

CI gate count remains **11**; `RUBRIC_VERSION` remains **v2.20**. §27 needs no edit because the AC lives at §01 (the spec-authoring guide is the home of meta-spec authoring patterns); the §27-side enforcement is already covered by AC-31-29 and is cross-referenced from AC-SAG-26's `Verifies` clause.

## What this enables

- **Future contributor checklist** when encountering a fragile cadence: "Are there 3+ historical instances? Has it drifted? If yes → retirement memo + canonical replacement SoT + CUTOFF + mechanical gate + §27 spec + AC + lockstep + registry row."
- **Reviewer pattern-name**: drift-prone cadences now have a named root cause to cite ("this looks like it qualifies for AC-SAG-26 retirement — please open a retirement memo").
- **Bounded scope**: the explicit trigger threshold (3+1) prevents premature retirement of working cadences and prevents over-engineering single isolated incidents.
- **Composability with Phase 109 (AC-31-31)**: the registered-retirements table is itself an enumeration that may eventually cross 3 files (e.g. retirement memos + the registry table + a contributor doc summarising retired cadences). At that point AC-31-31 fires and a parity self-test for the registry is required. The two ACs compose: AC-SAG-26 defines *when* a cadence retires; AC-31-31 defines *how* the registry stays consistent if it grows beyond 3 sites.

## Why Phase 100's prediction was correct

Phase 100's verdict was that the forward-looking memo cadence was structurally fragile (drift evidence in Phases 78–83 / 92–93 / 96). Phase 104's mechanical gate has now run successfully across Phases 101–109 with zero false-positives and zero false-negatives — every in-scope memo (8 + this one = 9) is retrospective-only. The gate's success validates Phase 100's verdict; AC-SAG-26 now records the underlying pattern so the success can be replicated, not just observed.

## Verification

- Cross-links: OK (the new `Verifies` clause links to existing AC-31-29 and the existing Phase 100 memo + Phase 104 script — all targets exist)
- Tree-health: 100/100 strict (no file additions/deletions outside spec text)
- Lockstep: 0 findings strict (§97 v4.6.0 ↔ §98 v4.11.0 ↔ §99 v4.8.0 all advanced together with matching dates)
- Audit `--min-weighted=97 --min-impl=99`: ✓ at 98.0/99.8 (no rubric/script change)
- Phase 91/94/95 self-tests: 6/14/7 ✅ (no script behaviour change)
- Phase 97 mermaid: 106/106 ✓
- Phase 102 self-test: 16/16 ✅ (no `linter-scripts/test/` filesystem change)
- Phase 103 self-test: 11/11 ✅ (no `RUBRIC_VERSION` / footer / workflow change)
- Phase 104 meta-linter: ✅ — **9 in-scope memos** (Phases 100–107 + 109 + 111), 0 forbidden headings (this memo passes its own rule — uses `What this enables`, `Why Phase 100's prediction was correct`, `Verification`, `Files touched`, `Score impact` per the AC-31-29 suggested-replacements list)

## Files touched

- `spec/01-spec-authoring-guide/97-acceptance-criteria.md` (header `Updated:` + AC-SAG-26)
- `spec/01-spec-authoring-guide/98-changelog.md` (header + 4.11.0 release block)
- `spec/01-spec-authoring-guide/99-consistency-report.md` (header + v4.8.0 narrative)
- `.lovable/memory/audit/v2-deterministic/phase-111-documentation-cadence-retirement-pattern.md` (this memo)

## Score impact

None. No rubric change, no script change, no CI gate added, no §27 edit. Pure declarative contract generalisation — closes the lessons of Phase 100 + Phase 104 into a named, enforceable-by-review pattern that future contributors can apply to other fragile cadences without re-discovering the protocol from scratch.
