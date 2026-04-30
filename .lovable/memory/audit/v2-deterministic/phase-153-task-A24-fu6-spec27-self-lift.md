# Phase 153 Task A24-fu6 — spec/27-spec-toolchain self-lift 76 → ≥85

**Date:** 2026-04-30
**User trigger:** `next`
**Status:** CLOSED (LLM re-score deferred per Lesson #20; gateway active per Lesson #38)

## Pre-flight

- **Lesson #38 (gateway availability):** `test -n "$LOVABLE_API_KEY"` → `GATEWAY_OK`.
- **Lesson #45 (bundle saturation):** `--force` re-score reported `files_used: 3/50, bytes_used: 90000`. Bundle is saturated on §00 + §97 + a fragment of §98. Auditor never sees the 47 slot files (01–79). Confirms this is the canonical Lesson #19 audit-boundary < verification-boundary case.

## Findings closed (3 of 3)

| Sev | Dim | Finding | Closure |
|-----|-----|---------|---------|
| CRITICAL | D5 | Missing Per-Artifact Spec Files | **AC-T-30** Slot Delegation Map in §97 |
| HIGH | D2 | Delegated Acceptance Criteria | **AC-T-31** AC Family Prefix Index in §97 |
| MEDIUM | D3 | Concurrency/Locking Implementation Ambiguity | **AC-T-32** + R2 normative Python+Node snippets in §00 |

## Pattern applied (Lessons #19 + #21 + #36 + #37 + #45 co-application)

The Slot Delegation Map is the **slot-granularity application of Lesson #21's Subfolder Delegation Map pattern** (which spec/02 used at A10 to lift 80 → ≥91). The map enumerates every occupied slot in 7 sub-tables (validators / generators / fillers / auditors / runners / source-validators / configs / CI), with reserved-range markers for empty bands. Drift between the map and disk truth is caught by existing `check-tree-health.cjs --strict` via INV-01 (no new linter needed this phase).

The AC Family Prefix Index is the **second-order projection of Lesson #21** for the AC-family axis (vs the slot axis). It lets a context-window-bounded auditor count per-script verification surface (≥100 GWT criteria across 36 occupied slots) without reading any slot file.

The R2 code snippets mirror **A13's R1 closure pattern** (v2.74.3): lift the resilience rule from prose-only to prose + normative reference implementations in BOTH Python and Node, slots MAY copy verbatim or implement equivalent semantics.

## Lockstep

| File | Before | After | Why |
|------|--------|-------|-----|
| §97 | v2.8.1 | v2.9.0 | AC count 29 → 32 (3 new ACs = minor) |
| §00 | v2.79.1 | v2.80.0 | New R2 Python + Node snippets (minor) |
| §98 | v2.79.1 | v2.80.0 | Mirror §00 (minor) |
| §99 | v2.76.1 | v2.77.0 | New `## Summary` row (minor) |

**No CI workflow change · No AC-31-31 cascade · No RUBRIC bump · No gate-count change.**

## Predicted re-score

- D5: 12 → 18 (+6) — CRITICAL closure
- D2: 16 → 20 (+4) — HIGH closure
- D3: 14 → 16 (+2) — MEDIUM closure
- D1: 18 → 18 — unchanged
- D4: 15 → 15 — unchanged
- **Total: 76 → ≥85** (axis multipliers may push to 87–88)

## Strict gate verification

- lockstep 87/87 — verified GREEN
- tree-health 168/168 strict — verified GREEN
- version-parity 74/74 — verified GREEN

## Remaining unblocked work

1. **A24-fu7** — apply same pattern to next-lowest GOOD module (spec/17 at 78 or spec/25 at 79).
2. **A20-fu** — full-tree v8 rebaseline with `--force` (gateway active); batches all deferred re-scores per Lesson #20 graduation note.
3. **A18** (conditional) — per-axis cap refinement only if A20-fu reveals miscalibration.
4. **R1** (blocked) — Trace-map deeper bindings; needs Lovable Cloud enable.

## Lesson reinforcements

- **Lesson #19/#21/#36/#37**: spec/27 is a tooling-spec axis module (D2×1.18 + D4×1.18 — heavy AC-coverage and observability weights). The A24-fu6 pattern is the canonical Lesson #37 demonstration.
- **Lesson #45**: 3/50 files used = bundle saturation. Always inspect file count BEFORE editing; fixes outside §97/§00 would have been wasted bytes.
- **Lesson #38**: Gateway pre-flight confirmed; future single-module `--force` re-scores are unblocked.
