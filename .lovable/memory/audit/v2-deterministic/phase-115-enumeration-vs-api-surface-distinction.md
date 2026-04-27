# Phase 115 — Promote enumeration-vs-API-surface distinction to §01 meta-spec

**Date:** 2026-04-27
**Type:** Declarative contract promotion (no code change)
**Trigger:** Phase 114 dismissal sweep introduced the enumeration-restatement vs API-surface-use distinction inline in §31's "Currently-NOT-qualifying enumerations" prose. The distinction is more general than §27 — it applies whenever any spec module considers a parity test.

---

## What changed

Added **AC-SAG-27** at `spec/01-spec-authoring-guide/97-acceptance-criteria.md`:

- **Two definitions** — enumeration-restatement vs API-surface-use, with worked examples.
- **Four diagnostic questions** Q1–Q4:
  - Q1: Same set across all N sites?
  - Q2: Same domain semantics for each member?
  - Q3: No single canonical source-of-truth the others derive from?
  - Q4: Silent drift risk (≥1 PR cycle)?
- **Routing rule**: all four `YES` → AC-31-31 parity test; any `NO` → §31 dismissal record, no parity test (would be a category error).
- **Worked Phase-114 dismissal record** as a 5-row table preserved as canonical training set:
  - Audit CLI flag set — fails Q1
  - Per-script exit-code tables — fails Q2
  - Audit-script exit-code table — fails Q3 (and only 2 sites)
  - CI threshold floors — only 2 sites
  - Gate-cap thresholds — single-site magic numbers, fails Q1

## Why §01 (not just §27)

§01 is the meta-spec that governs how every spec author thinks about cross-file contracts. AC-31-31 is the §27-side mechanism for one specific pattern (multi-file enumerations within the toolchain), but the underlying triage discipline applies whenever any module considers a parity test. Without AC-SAG-27, future contributors authoring parity tests in `spec/22-git-logs-v2/` (e.g. multi-file enum tables) or `spec/04-database-conventions/` (e.g. multi-file SQL DDL enumerations) would re-derive triage from scratch — and might author category-error parity tests that lock API surfaces and break the next legitimate API extension.

## Lockstep cascade

| File | Before | After |
|---|---|---|
| `spec/01-spec-authoring-guide/97-acceptance-criteria.md` | v4.6.0 | v4.7.0 |
| `spec/01-spec-authoring-guide/98-changelog.md` | v4.11.0 | v4.12.0 |
| `spec/01-spec-authoring-guide/99-consistency-report.md` | v4.8.0 | v4.9.0 |

## What did NOT change

- No code change. No new linter script, no new test, no new CI gate.
- `RUBRIC_VERSION` unchanged at v2.22.
- CI gate count unchanged at 13.
- The AC-31-31 registry at §31 remains at 4 rows.
- §31's "Currently-NOT-qualifying enumerations" paragraph is unchanged content-wise — AC-SAG-27 now mirrors it from the meta-spec side.

## Declarative-with-runtime-companion structure

The four diagnostic questions are a triage protocol applied by the contributor at authoring time. There is no automated meta-meta-linter scanning for "are there candidate patterns that should be triaged?" — such detection would require longitudinal repo analysis the toolchain doesn't perform. The runtime companion is the worked-dismissal table at AC-SAG-27 + §31's "Currently-NOT-qualifying enumerations" paragraph, both maintained in lockstep and reviewable as a single artefact during audit.

## Verification

All 13 strict CI gates expected to remain green (no code change). §01 score holds at 97/100 A+ with impl=100; §27 score holds at 97/100 A+ with impl=100.
