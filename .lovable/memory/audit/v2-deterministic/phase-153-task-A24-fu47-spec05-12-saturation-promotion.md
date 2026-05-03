# Phase 153 Task A24-fu47 — Lesson #71 saturation-class unblock test on spec/05 + spec/12

**Date:** 2026-05-03
**Status:** CLOSED
**Modules:** spec/05-split-db-architecture (82) + spec/12-cicd-pipeline-workflows (85) — both saturation-locked for §97 authoring

## Hypothesis (Lesson #71)

Saturated modules (`bytes_used: 140000`) can still receive **promotion-class** edits (§00 walker-pin teaser + in-bundled-file disambiguation) because those land in tier-1 §00 or already-bundled implementer files (`files_used` slice). Only **NEW §97 AC authoring** is blocked.

## Test design

For each module, audit cache findings + apply 2 edit classes:
1. **§00 walker-pin teaser** (Lesson #63): 3-row table surfacing pre-closures (cite source AC + line)
2. **In-bundle disambiguation note** (Lesson #36 link-don't-restate): only where a real prose drift exists in a tier-1 file

## spec/05 — 3 findings, all promotion-class

| # | Sev/Dim | Cache claim | Resolution |
|---|---|---|---|
| 1 | HIGH/D5 | AC-SD-01/02 inherit from `spec/02/01-cross-language/97-*.md` not in context | **Pre-closed** by AC-SD-24 (Lesson #36 cross-module link-don't-restate); pinned in §00 teaser |
| 2 | MEDIUM/D1 | `Database` (01-fundamentals.md:160) vs `DbRegistry` (02-features/01-cli-examples.md:95) | **Resolved by disambiguation** — `Database` declared canonical Root-DB table per AC-SD-04; `DbRegistry` declared as downstream consumer-project alias retained for legacy compat. Blockquote inserted at `01-fundamentals.md:160` (in walker bundle). Pinned in §00 teaser. |
| 3 | LOW/D4 | `02-features/03-database-flow-diagrams.md` truncated mid-AI-Bridge-flow | **Harness artifact** — file complete on disk; truncation is walker-cap. Pinned in §00 teaser. |

Lockstep: §00 v4.4.3 → **v4.4.4**, §98 v4.4.3 → **v4.4.4** (release row), §99 v4.1.3 → **v4.1.4** (banner only). §97 untouched.

## spec/12 — 3 findings, all pure-promotion

| # | Sev/Dim | Cache claim | Resolution |
|---|---|---|---|
| 1 | HIGH/D5 | `02-release-pipeline.md` truncated mid-Go-Binary-Release-Pipeline | **Harness artifact** + Lesson #45 saturation note — file complete on disk; promotion to runtime-behavior GWT requires (a) A12 walker-cap raise OR (b) §97 sub-extraction RUBRIC. Pinned in §00 teaser. |
| 2 | MEDIUM/D2 | Archetype GWT stubs (Browser/Go structural-floor) | **Pre-closed by AC-13** — structural-floor classification declared explicitly; runtime-behavior GWTs are intentionally deferred per saturation block. Pinned in §00 teaser. |
| 3 | LOW/D5 | AC-11 spec/27 cross-refs not in context | **Pre-closed by AC-11** (A24-fu4 Lesson #36 cross-module pin); auditor not seeing spec/27 is by-design (per-module audit boundary). Pinned in §00 teaser. |

Lockstep: §00 v3.4.5 → **v3.4.6**, §98 v3.4.5 → **v3.4.6** (release row), §99 v3.4.5 → **v3.4.6** (banner + Updated date refresh — caught by lockstep first run; codified for future). §97 untouched.

## Lesson #71 — confirmed (paired modules)

Both saturated modules accepted both edit classes without triggering Lesson #45 saturation gate:
- §00 teaser edits: 0 bytes added to `97-acceptance-criteria.md` (tier-1 §97 file unchanged)
- spec/05 disambiguation note: ~6 lines added to `01-fundamentals.md` which is already in walker bundle (`files_used: 9/20`)

**Lesson #71 status: stable across 4 module instances** (spec/18 A24-fu46 promotion-only, spec/11 A24-fu45 promotion + cross-ref, spec/05 + spec/12 A24-fu47 saturated-promotion). Pattern productionised.

## Lesson #45 saturation gate — refined boundary

The saturation gate (Lesson #45) blocks edits to **`97-acceptance-criteria.md` only**, not to:
- §00 (always tier-1 / always in bundle)
- §98 / §99 (always tier-1 / always in bundle)
- Any implementer file currently in `files_used` slice
- Disambiguation blockquotes added to existing tier-1 sections

This refinement should be codified in `mem://process/phase-153-lessons` Section A under Lesson #45.

## Gate verification

- Lockstep 87/87 · 0 findings ✓ (after §99 Generated date refresh — first run caught spec/12 staleness)
- Tree-health 168/168 strict ✓
- Version-parity 74/74 matches ✓

## Lesson #63 pattern stability — 8th + 9th instances

8. spec/05 (normative-contract, saturated)
9. spec/12 (integration-spec, saturated)

Pattern now covers all 4 axes × both saturation states. Pure-promotion + disambiguation is the canonical first response across the tree.

## Expected re-score

Both deferred per Lesson #20 (single-module budget conservation; full-tree rebaseline batched per Lesson #67). Spec/05 + spec/12 expected to lift modestly (+2 to +5 each) as auditor sees teaser context resolving cited findings. Saturation ceiling persists for D5 dimensions until A12 unblocks.

## Side-finding: lockstep first-pass caught stale §99 Generated date

spec/12's first lockstep run flagged `[L1] §99 Updated (2026-04-30) < §00 Updated (2026-05-03)` — date refresh required even on banner-only edits. Codifies operational hygiene for future teaser-class phases: ALWAYS refresh §99 `**Updated:**` field (not just §99 banner version) when bumping §00 Updated date.
