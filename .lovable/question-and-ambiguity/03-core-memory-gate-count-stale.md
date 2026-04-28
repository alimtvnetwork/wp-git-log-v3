---
task: Phase 25 — multi-target sweep close-out
date: 2026-04-28
phase: 25
type: ambiguity
---

# Ambiguity 03 — Core memory CI-gate count stale (15 → 17)

## Task context

Phase 25 of No-Questions Mode swept 3 close-out targets from Phase 24:
`.github/PULL_REQUEST_TEMPLATE.md`, `.github/workflows/spec-monthly-audit.yml`,
`spec/folder-structure-root.md`. While verifying a suspected "rubric v2.0.0"
drift in the workflow file (turned out to be tree-health rubric, not
audit rubric — not drift), I discovered that the audit script's QA-baseline
footer (the canonical AC-31-28 source) declares **17 strict CI gates** but
Core memory in `mem://index.md` still says **"CI gate count 14 → 15"** at
H1 close.

## Specific question

Should Core memory be silently updated from "15" to "17" (since
`test-qa-baseline-footer.sh` confirms 17/17/17 parity is the current
truth, and the source-of-truth footer was expanded by H5 + H7 after the
H1 close note was written)?

## Inferred decision

**YES, update Core memory.** Justification:

1. The footer is the AC-31-28 canonical source (mechanically validated).
2. `test-qa-baseline-footer.sh` reports `Declared 17 / Footer 17 / Workflow 17`.
3. Core memory's "15" is a snapshot from H1 close — H5 (gate #16) and
   H7 (gate #17) have since landed, both documented in Core memory's own
   prose ("AC-26-01..05 / AC-27-01..08 / AC-28-01..05" mentions imply
   stamp-bump + archive-exclusion gates exist).
4. Silently updating is reversible if the user disagrees.

## Impact

Updating Core memory rule "CI gate count 14 → 15" → "CI gate count 14 →
15 → 17" preserves the historical H1 milestone while reflecting current
truth. Future sessions reading the Core block will not be misled.

## Suggested clarification

User confirms whether Core-memory edits to rectify drift discovered by
self-tests are appropriate during No-Questions Mode (this is the first
such case in Phases 18-25; prior phases only edited spec/code files,
not Core memory itself).
