# Phase 89 — Front-Matter Keys Documented in Spec Authoring Guide

**Date:** 2026-04-27
**Scope:** `spec/01-spec-authoring-guide/{00-overview,97-acceptance-criteria,98-changelog,99-consistency-report}.md`
**Status:** ✅ Complete

## Why
The deterministic auditor reads two front-matter keys from each module's
`00-overview.md` to choose its rubric branch and TODO-penalty behaviour:
- `kind:` → 5-value enum (`active-spec` / `future-spec` / `tracker` / `index` /
  `meta-toolchain`) selecting which rubric branch evaluates the module
- `todo_audit_exempt: true` → opt-out of the TODO-density penalty (v2.14)

These keys were documented inline in the audit script source and in
`CONTRIBUTING.md` (Phase 87), but the **spec authoring guide itself** —
the canonical "how to write a spec" reference — only mentioned `kind:` in
passing (INV-AUTH-02 + an OpenAPI enum). Module authors had no normative
table explaining what each kind value does to scoring.

## Changes

### `00-overview.md` (v3.5.0 → v3.6.0)
- New "Front-matter keys reference (Phase 89)" section after INV-AUTH block
- Full `kind:` table: 5 enum values × (auditor branch, impl baseline + bonus structure, when to use)
- `todo_audit_exempt: true` subsection: behaviour, when-to-use, reviewer-gate warning, v2.14 regex tightening note
- `drift_acknowledged:` subsection: paired requirement with `kind: future-spec`

### `97-acceptance-criteria.md` (v4.0.0 → v4.1.0)
- **AC-SAG-21** — `kind:` front-matter selects the auditor rubric branch. Given/When/Then ties each enum value to its concrete rubric formula (impl baselines, bonus deltas, caps for prose-only vs ≥1 typed contract). Cross-references AC-31-15..AC-31-21.
- **AC-SAG-22** — `todo_audit_exempt: true` opt-out is reviewer-gated. Given/When/Then forces `metrics.todo_count = 0`; constrains opt-in to auditor-self-reference modules; cites the v2.14 regex tightening so most modules don't need it. Cross-references AC-31-22.

### Lockstep
- §98 changelog: new `## 4.6.0 — 2026-04-27` row documenting Phase 89 + version bump (4.5.0 → 4.6.0)
- §99 consistency report: new `> v4.3.0 update (Phase 89)` blockquote at top + version bump (4.2.0 → 4.3.0)

## Verification
- **Tree health (strict):** ✓ 100/100
- **Lockstep (strict):** ✓ 0 findings (all 4 file dates align on 2026-04-27)
- **Audit (`--min-weighted=97 --min-impl=99`):** ✓ 98.0 / 99.8 PASS — no regression

Note: §01's auditor-visible content grew (~50 lines) but its `weighted_overall`
held steady because the new content is dense, on-topic, AC-backed, and adds no
TODO/waffle markers (front-matter examples are inline-code so `strip_code()`
removes them before scanning).

## Effect
Module authors now have a single normative reference table for front-matter
keys. Reviewers can cite AC-SAG-22 when rejecting `todo_audit_exempt: true`
on non-auditor-self-reference modules. Trace-map bijection improved: every
runtime-significant front-matter key now has a paired AC in the meta-spec.
