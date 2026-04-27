---
name: phase-99-rubric-version-qa-baseline
description: Phase 99 — bumped audit script v2.16 → v2.17 (zero rubric change); added RUBRIC_VERSION constant + Rubric header in summary outputs + 8-gate QA baseline footer in 00-index.md; locked by AC-31-28
type: feature
---

# Phase 99 — `RUBRIC_VERSION` + QA Tooling Baseline Footer

**Date:** 2026-04-27
**Status:** ✅ Complete
**Trigger:** Phase 98's "Remaining Tasks" queue item #1
**Predecessors:** Phase 90 (script v2.16 + `--explain`), Phase 91/94/95 (self-test triad), Phase 97 (mermaid gate), Phase 98 (test README)

## Why

The audit's summary outputs (`00-index.md`, `EXECUTIVE-SUMMARY.md`) showed
the score (98.0/99.8) but said nothing about (a) which rubric version
produced it, or (b) what other gates surround it in CI. A reader of the
output could reasonably conclude the audit IS the quality bar — when
in fact it's one signal among 8 strict gates by Phase 98.

Phase 99 closes the discoverability gap on the output side, mirroring
what Phase 98 did on the test-directory side.

## What changed

### Script `linter-scripts/audit-spec-vs-code-v2.py` v2.16 → v2.17

- **NEW** module-level constant `RUBRIC_VERSION = "v2.17"` (static string —
  preserves Phase 95 determinism).
- **NEW** `**Rubric:** v2.17` header line in both `00-index.md` and
  `EXECUTIVE-SUMMARY.md`.
- **NEW** "QA tooling baseline (Phase 99)" section appended to
  `00-index.md` enumerating the 8 strict CI gates with script paths and
  Phase numbers, plus a link to `linter-scripts/test/README.md` (Phase 98)
  as the canonical inventory for the self-test triad.
- **EXEC summary** also points readers to the new footer.
- **Zero rubric change** — explicitly marked "no scoring change" in the
  rubric changelog row.

### Determinism

Static `RUBRIC_VERSION` string + static "Phase 99" footer text means
Phase 95's byte-identity guarantee holds. The sha256 hash of
`raw-results.json` shifted exactly once on rollout (`fdba5f87…` →
`e22906c4…`) and re-stabilised — verified by re-running the determinism
self-test post-change (7/7 ✅).

### Spec lockstep

- **§31 v1.13.0 → v1.14.0**: header `Source` line bumped to `(script v2.17)`;
  Category appends `+ QA-baseline footer`. New **AC-31-28** mandates the
  `**Rubric:**` header in both outputs, the 8-gate enumeration in
  `00-index.md`, the static-string `RUBRIC_VERSION` requirement
  (preserves determinism), the Phase 98 README link, and the
  bump-on-every-change rule (rubric OR metadata). Rubric changelog table
  extended through v2.17.
- **§98 v2.20.0 → v2.21.0**: new 2.21.0 release entry.
- **§99 v2.17.0 → v2.18.0**: new v2.18.0 update banner.

## Verification

All 8 strict gates green:
- Cross-links: ✓
- Tree-health (strict): ✓ 100/100 across 56 modules
- Lockstep (strict): ✓ 0 findings
- Audit `--min-weighted=97 --min-impl=99`: ✓ 98.0 / 99.8 PASS
- Phase 91 self-test: ✓ 6/6
- Phase 94 self-test: ✓ 14/14
- Phase 95 self-test: ✓ 7/7 (new sha256 `e22906c4…` stable across runs)
- Phase 97 mermaid syntax: ✓ 106/106

No score regression. Output-clarity only.

## Why this matters

A reader of `00-index.md` arriving via a CI link or a contributor onboarding
PR can now see in one place:

1. The score (98.0/99.8 — "is it good?")
2. The rubric (v2.17 — "what produced this score?")
3. The 8 surrounding gates (— "what else am I trusting?")
4. A pointer to the inventory README (— "where's the next layer of detail?")

Combined with Phase 97 (mermaid gate) and Phase 98 (test README), this
completes a **discoverability triad** for the spec-toolchain quality
infrastructure: every quality signal is reachable in ≤2 clicks from the
audit output.

## Files touched

- **EDIT** `linter-scripts/audit-spec-vs-code-v2.py` (docstring v2.16 → v2.17 + new `RUBRIC_VERSION` constant + `**Rubric:**` lines + "QA tooling baseline" section in `00-index.md`)
- **EDIT** `spec/27-spec-toolchain/31-audit-spec-vs-code-v2.md` (+ AC-31-28, header bump v1.13.0 → v1.14.0, rubric changelog v2.17 row)
- **EDIT** `spec/27-spec-toolchain/98-changelog.md` (+ 2.21.0 entry)
- **EDIT** `spec/27-spec-toolchain/99-consistency-report.md` (+ v2.18.0 banner)
- **REGEN** `.lovable/memory/audit/v2-deterministic/00-index.md` + `EXECUTIVE-SUMMARY.md` + `raw-results.json` (new sha256 `e22906c4…`)
- **NEW** `.lovable/memory/audit/v2-deterministic/phase-99-rubric-version-qa-baseline.md` (this memo)
