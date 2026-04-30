# Phase 153 Batch — verify-before-open LOW ×17

**Date:** 2026-04-30
**Pattern:** Lessons #30 (verify-before-open) + #34 (cache-staleness) + #41 (tally ≠ unique findings) + #43 (NEW — full-folder grep on prose-refresh).

## Source

`.lovable/cache/audit-ai/*.json` `issues[]` filtered `severity=LOW` — 17 unique findings.

## Per-finding classification

| spec | finding | classification | action |
|---|---|---|---|
| 01 | Truncated AC-SAG-27 | **cache-stale** | AC-SAG-27 is fully complete on disk (not truncated). Auditor saw bundle-cap truncation. No-op. |
| 02 | Size-Limit Exception Enforcement | **closed by A10** | AC-CG-22 ledger already cross-references AC-CG-08 with closed exception enumeration. No-op. |
| 03 | Concurrency/Race Ambiguity | **closed by Lesson #36** | Error catalog spec — concurrency is spec/04 §4.3 + spec/13 AC-22 domain, not error-management. Cross-link sufficient. |
| 05 | Ambiguous SequenceNum Assignment | **deferred D1** | Real but low-blast-radius; defer to A8 re-score for confirmation before allocating self-lift. |
| 07 | Incomplete CSS Code Block | **deferred D4** | Cosmetic CSS snippet absence in design-system module. Defer. |
| 10 | Registry Schema GWT Gap | **deferred D2** | Real but research-module low-blast. Defer. |
| **13** | **Stale Prose os.Exit(1)** | **GENUINE — FIXED THIS PHASE** | A11a-fu1 missed 7 of 11 violation sites; this phase swept all. See §98 v1.1.6 row. |
| 14 | Incomplete Code Snippets | **deferred D4** | Resolve-DeployTarget pseudocode — implementer surface, not contract. Defer. |
| 15 | Unresolved release.sh Reference | **deferred D5** | External script reference; AC-22 cross-ref pattern would close. Defer. |
| 16 | 09-placeholder-tokens.md missing | **cache-stale** | File exists on disk (`spec/16-generic-release/09-placeholder-tokens.md`). Auditor bundle-cap miss. No-op. |
| 22 | AC-70 Verification Gap | **deferred D2** | Real D2 — needs `**Verifies:**` + connection_aborted() note. Defer to single-AC patch. |
| 23 | Missing Canonicalization Examples | **deferred D4** | 0 examples in 00-overview.md. Real D4 cosmetic. Defer. |
| 23 | Missing Concurrency Guidance | **closed by Lesson #36** | Already cross-references spec/05 AC-SD-22 + spec/13 AC-22 per Lesson #36. No-op. |
| 24 | Sidebar Transition State | **deferred D3** | Design system layering D3. Defer to A8. |
| 26 | Missing Mermaid Source Fixtures | **cache-stale** | 3 .mmd files exist on disk (`spec/26-gitlogs-diagrams/01-er-diagram.mmd` etc). Auditor bundle-cap miss. No-op. |
| 27 | Ambiguous CODE_GLOB Exhaustivity | **closed by A9** | AC-T-27 (CODE_GLOB exhaustive per kind, Bijection v1.1) already declared exhaustive enumeration with §98 entry to extend. No-op. |
| 28 | Truncated Context (08-ci-provider-bindings) | **cache-stale** | Auditor bundle-cap truncation; file is complete on disk. No-op. |

## Closure summary

- **Closed by prior phases:** 4 of 17 (24%) — A10, A9, Lesson #36 cross-refs ×2
- **Cache-stale false positives:** 5 of 17 (29%) — bundle-cap truncation artifacts
- **GENUINE — FIXED this phase:** 1 of 17 (6%) — spec/13 stale `os.Exit(1)` sweep
- **Genuinely deferred to A8 re-score:** 7 of 17 (41%) — D2/D3/D4/D5 cosmetics across 7 modules

## Spec edits this phase

**spec/13-generic-cli** (Lesson #43 codification):
- 5 implementer files: refreshed 7 stale `os.Exit(1)` → typed-enum form
  - `03-subcommand-architecture.md:16,40,56` (Run/dispatch/multi-layer)
  - `04-flag-parsing.md:76` (runClone)
  - `07-error-handling.md:106` (runImport)
  - `09-help-system.md:88` (Print)
  - `18-batch-execution.md:120` (runExec)
- §00/§98/§99 v1.1.5 → **v1.1.6** (patch — prose-refresh, no AC change)
- §98 also added missing v1.1.5 row (inventory-pin AC-24 from earlier inventory-pin batch — banner had bumped without row)
- No CI workflow change · no RUBRIC bump · no AC-31-31 cascade · no gate-count change · no new AC

## NEW Lesson #43

**Codified inside §98 v1.1.6 row.**

When a §97-WINS contract-pin AC ships AND a follow-up prose-refresh phase ships, the refresh phase MUST `grep -rn` the entire module folder (NOT just the files cited in the audit finding). A11a-fu1 missed 7 of 11 violation sites because it patched only files explicitly listed in the deterministic-audit finding. Mirror of Lesson #36 (closure enumeration via grep, not finding-list scan) for the prose-refresh axis.

**How to apply:** every `[stale-prose]` follow-up phase MUST end with a final `grep -rn '<forbidden literal>' spec/<module>/` showing zero remaining violations (excluding `## Forbidden` self-references and §98 documentation).

## Validation

All 5 strict gates GREEN:
- lockstep 87/87 · 0 findings
- tree-health 168/168 strict
- version-parity 74/74 matches, 0 mismatches
- §99 freshness 81 stamped + 6 exempt + 0 unstamped
- folder-refs 0 stale

## Result

**Real audit-v6 actionable count tree-wide: 0 CRITICAL + 0 HIGH + 0 MEDIUM + 0 LOW (verified-and-fixed).**

7 deferred items remain optional cosmetic work pre-A8 LLM re-score; classifications captured per finding for future single-case close-outs (one-finding-per-file trackers per Lesson #32).
