# Phase 25 — Multi-Target Close-Out Sweep (No-Questions Mode 9/40)

**Date:** 2026-04-28
**Trigger:** Phase 24 close-out recs (a)+(b)+(c) batched together for
efficiency since each target was small.

## Targets scanned

1. `.github/PULL_REQUEST_TEMPLATE.md` (46 lines)
2. `.github/workflows/spec-monthly-audit.yml` (130 lines)
3. `spec/folder-structure-root.md` (33 lines)

## Findings

| Target | Suspected drift | Verdict | Notes |
|--------|----------------|---------|-------|
| PR template L17/L20 | "Run all four" / "100/100" | ✅ NOT DRIFT | Same intentional contributor-scoping as CONTRIBUTING.md (Phase 24): 4 quality-bar gates, not 15/17 workflow gates. |
| PR template L40 | "87-module corpus" | ✅ ACCURATE | Confirmed by `audit-spec-vs-code-v2.py` `[87/87]` actual output. |
| PR template L42 | "phase-86-schema-cap-rejected.md" | ✅ ACCURATE | Historical example reference, file still exists. |
| spec-monthly-audit L52/66/96/118 | "rubric v2.0.0" | ✅ NOT DRIFT | Refers to TREE-HEALTH rubric (`check-tree-health.cjs` line 131 prints "rubric v2.0.0") — distinct from audit script's `RUBRIC_VERSION = "v2.26"`. Tree-health rubric is genuinely still at v2.0.0. |
| folder-structure-root | inventory range "01-20 / 21+" | ✅ ACCURATE | Modules go up to 28; "21+" still correctly captures app-specific range. Pure 33-line redirect, no count claims. |

## Bonus discovery (filed as Ambiguity 03, self-resolved)

While verifying #2, found that `test-qa-baseline-footer.sh` reports
**17/17/17 parity** but the in-context Core memory summary appeared to
say "CI gate count 14 → 15". Investigation: on-disk
`.lovable/memory/index.md` line 13 already correctly states "CI gate
count **17**". The "15" figure is a context-summary truncation
artifact — the full memory file is current. No edit needed. Filed
note: `.lovable/question-and-ambiguity/03-core-memory-gate-count-stale.md`.

**Lesson codified:** When in-context Core summary disagrees with
verified source-of-truth, check on-disk `.lovable/memory/index.md`
BEFORE assuming drift. Auto-summary may lag the full file.

## Decision: No-op sweep

All 3 targets clean. The Phase 24 H10 narrative-claims rejection
verdict stands — no further drift surfaces in this class.

## Files touched

- `.lovable/question-and-ambiguity/03-core-memory-gate-count-stale.md` (filed + self-resolved)
- `.lovable/memory/audit/v2-deterministic/phase-25-multi-target-closeout.md` (this memo)

No spec or workflow edits.

## Stale-prose sweep cycle status

Phases 18 → 25 form a closed cohort:
- Phase 18: tree-health drift sweep (trace-map false positive, root-caused to baseline)
- Phase 19: stale-baseline advisory (Ambiguity 02, option 3)
- Phase 20: linter-scripts/test/README.md (5 narrative-count fixes)
- Phase 21: spec/27-spec-toolchain/00-overview.md (banner + slot-70)
- Phase 22: fleet banner-drift sweep (0 drift across 23 modules → H10 rejected)
- Phase 23: root spec/00-overview.md (clean, table-driven)
- Phase 24: CONTRIBUTING.md (3 fixes incl. high-severity version triple)
- Phase 25: PR template + monthly-audit.yml + folder-structure-root.md (clean)

**Cycle verdict:** Stale-prose drift exists but is rare, heterogeneous,
and not mechanically detectable by a single regex. Future sweeps should
be triggered by version bumps (audit script / RUBRIC) rather than
periodic cadence.
