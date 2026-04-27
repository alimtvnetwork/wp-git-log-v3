---
name: phase-101-pin-mermaid-jsdom
description: Phase 101 — pinned `mermaid` (11.14.0) and `jsdom` (20.0.3) to exact versions in package.json to stabilise the Phase 97 syntax gate's parser grammar; added AC-SAG-25 forbidding caret ranges for these two packages
type: feature
---

# Phase 101 — Pin `mermaid` + `jsdom` Versions

**Date:** 2026-04-27
**Status:** ✅ Complete
**Trigger:** Discovered Phase 97 — the mermaid syntax gate's parse-clean guarantee is only as stable as the mermaid grammar; an unpinned caret range can silently upgrade the grammar mid-PR and turn the gate flaky.

## Why

Phase 97 added `linter-scripts/check-mermaid-syntax.mjs` and locked it
behind AC-SAG-24 (every `spec/**/*.mmd` file parses cleanly). The gate's
correctness depends on a specific parser grammar — but `package.json`
declared `"mermaid": "^11.14.0"` and `"jsdom": "^20.0.3"`, both with
caret ranges. If `bun install` later resolves to (e.g.) `mermaid@11.20.0`
on one developer's machine and `mermaid@11.14.0` on CI, a grammar tweak
between those two minor versions could:

- Pass on the bumper's machine but fail on CI (or vice versa).
- Pass for weeks then suddenly fail when CI's lockfile is regenerated.
- Make AC-SAG-24 indistinguishable from a flaky test.

Pinning makes any grammar change an **explicit, reviewable bump** that
has to land in a PR, not a silent transitive resolution.

## What changed

### 1. `package.json` — exact pins

| Line | Before | After |
|---|---|---|
| 52 (deps) | `"mermaid": "^11.14.0"` | `"mermaid": "11.14.0"` |
| 82 (devDeps) | `"jsdom": "^20.0.3"` | `"jsdom": "20.0.3"` |

`mermaid` is in `dependencies` (used both by the app and by the
syntax-gate script). `jsdom` is in `devDependencies` (used only by the
script). `dompurify` (the third package the script touches) is
transitively pinned through mermaid's own `package.json` and does NOT
need a direct pin in this repo.

### 2. `bun.lock` — regenerated

`bun install` re-resolved both packages to the exact pinned versions
(`mermaid@11.14.0`, `jsdom@20.0.3`). 650 packages installed in 996ms.
The lockfile diff is the canonical record of the pin.

### 3. AC-SAG-25 — new acceptance criterion

Added to `spec/01-spec-authoring-guide/97-acceptance-criteria.md`
(§97 v4.3.0 → v4.4.0). Codifies four guarantees:

- (a) Both packages MUST use exact pins; caret (`^`) and tilde (`~`)
  ranges are FORBIDDEN.
- (b) Major-version bumps (`11.x.y` → `12.0.0`, `20.x.y` → `21.0.0`)
  MUST: (1) record the local `bun linter-scripts/check-mermaid-syntax.mjs`
  output in `98-changelog.md`, AND (2) re-run the full gate triad
  locally before merge:
  - `bun linter-scripts/check-mermaid-syntax.mjs`
  - `bash linter-scripts/test/test-audit-deterministic-stability.sh`
  - `bash linter-scripts/run.sh`
- (c) Minor/patch bumps MAY rely on CI alone, since pinning ensures CI
  sees the same version as the bumper's local environment.
- (d) `dompurify` is transitively pinned and does NOT require a direct pin.

### 4. Documentation lockstep

- **§97 v4.3.0 → v4.4.0:** AC-SAG-25 added; header bumped.
- **§98 v4.8.0 → v4.9.0:** new 4.9.0 release entry.
- **§99 v4.5.0 → v4.6.0:** new v4.6.0 update banner.

No spec-tree `.md` content changes outside §01.

## Verification

All 8 strict gates green:

- **Cross-links:** ✓
- **Tree-health (strict):** ✓ 100/100 across 56 modules
- **Lockstep (strict):** ✓ 0 findings
- **Audit (`--min-weighted=97 --min-impl=99`):** ✓ 98.0 / 99.8 PASS
- **Phase 91 self-test (`test-audit-cli-thresholds.sh`):** ✓ 6/6
- **Phase 94 self-test (`test-audit-explain-contract.sh`):** ✓ 14/14
- **Phase 95 self-test (`test-audit-deterministic-stability.sh`):** ✓ 7/7
- **Phase 97 mermaid syntax gate:** ✓ 106/106 files parsed cleanly

`bun.lock` diff confirms `mermaid@11.14.0` and `jsdom@20.0.3` resolve
exactly without the caret. The mermaid gate produces identical output
against the pinned versions, proving the pin is functionally
transparent (it tightens future behaviour without changing current
behaviour).

## Why this matters

Phase 101 closes a class of latent failures — **silent grammar drift** —
by converting an implicit "whatever caret resolves to today" into an
explicit "whatever the lockfile says, which is what the bumper and CI
both saw." The pattern generalises:

> Any quality gate whose correctness depends on a specific library
> grammar (parser, schema validator, linter) MUST pin that library
> exactly. Caret ranges are appropriate for libraries used as
> implementation details, not for libraries used as the spec of a
> quality gate.

This is the **infrastructure-side counterpart** to Phase 95's
determinism work: Phase 95 made the audit script's *output* stable
across runs; Phase 101 makes the syntax gate's *grammar* stable across
installs. Together they remove two of the three classic flakiness
sources (input non-determinism, dependency drift); the third
(environment differences) is already mitigated by the `bun
install --frozen-lockfile` step in CI.

A future quality gate added on top of another grammar-defining library
(e.g. a TypeScript-AST linter, a JSON-schema validator) should adopt
this same pattern by default — adding it to AC-SAG-25 as a follow-up
clause if a second example lands.

## Files touched

- **EDIT** `package.json` (2 caret ranges removed: mermaid + jsdom)
- **REGEN** `bun.lock` (resolved versions match pins exactly)
- **EDIT** `spec/01-spec-authoring-guide/97-acceptance-criteria.md` (+ AC-SAG-25, header bump v4.3.0 → v4.4.0)
- **EDIT** `spec/01-spec-authoring-guide/98-changelog.md` (+ 4.9.0 entry, header bump)
- **EDIT** `spec/01-spec-authoring-guide/99-consistency-report.md` (+ v4.6.0 banner; v4.5.0 banner preserved)
- **NEW** `.lovable/memory/audit/v2-deterministic/phase-101-pin-mermaid-jsdom.md` (this memo)
