---
name: phase-97-mermaid-syntax-gate
description: Phase 97 — added a CI gate that parses every spec/**/*.mmd with the mermaid library under a jsdom shim; found and fixed 2 latent syntax errors; locked by AC-SAG-24
type: feature
---

# Phase 97 — Mermaid Diagram Syntax Gate

**Date:** 2026-04-27
**Status:** ✅ Complete
**Trigger:** Phase 95's "Remaining Tasks" queue item #1
**Companion to:** Phase 93 (which made `.mmd` the canonical lifecycle SoT but didn't validate it)

## Why

Phase 93 elevated `lifecycle-spec-authoring.mmd` to the canonical source of
truth for the §01 lifecycle (locked by AC-SAG-23) — but no gate ever opened
the file to check it parsed. A future contributor could introduce broken
mermaid syntax in any of the 106 `.mmd` files under `spec/**` and CI would
happily pass; the breakage would surface only when a human tried to render
the diagram.

Phase 97 closes that hole with a parser-only check: walk every `.mmd` file,
hand it to the official `mermaid` library, fail the gate on any parse error.
No rendering, no Chromium, no network — pure syntax validation.

## What changed

### New script

**`linter-scripts/check-mermaid-syntax.mjs`** (~95 lines, ESM):

- Walks `spec/**/*.mmd` recursively.
- Sets up a `jsdom` window + minimal `DOMPurify` shim before importing
  `mermaid` (mermaid v11 requires a browser-ish environment for its
  `parse()` even though no rendering happens).
- Calls `mermaid.parse(src)` on each file, collects `{file, error}` failures.
- Exits 0 on all-clean, 1 on parse errors (with file path + first error
  line, no library stack trace), 2 on infrastructure failure (lib missing).

### Two latent bugs fixed by the new gate

The very first run found **2 of 106** files broken:

| File | Problem | Fix |
|---|---|---|
| `spec/01-spec-authoring-guide/lifecycle-spec-authoring.mmd` | Bare `%%` line in the comment header — mermaid mis-tokenises an "empty" comment as a `%%{init}%%` directive opening, then chokes on the next non-comment line which it sees as `%%flowchart TD` (concatenated). | Changed the bare `%%` separator to `%% --` (semantically still a separator, parses cleanly). Also added a Phase 97 validation footer line. |
| `spec/12-cicd-pipeline-workflows/images/ci-pipeline-flow.mmd` | All node labels used the unquoted form `B[actions/checkout@v6]` — the `@` confuses the flowchart parser inside a `subgraph`. | Wrapped every label in double quotes: `B["actions/checkout@v6"]`. Did the same for all `subgraph` member nodes (~26 nodes total). Cosmetically identical when rendered. |

Both bugs were **invisible without the gate**: they had been present in the
tree for an unknown number of phases.

### CI wiring

`.github/workflows/spec-health.yml` gained:

1. A new `oven-sh/setup-bun@v2` step (Bun was previously not installed in CI).
2. A new `bun install --frozen-lockfile` step (mermaid + jsdom were already
   declared as devDependencies in `package.json`, just not installed in the
   CI job).
3. A new "Mermaid diagram syntax gate (Phase 97)" step running
   `node linter-scripts/check-mermaid-syntax.mjs`, placed after the
   Phase 95 determinism test and before the trace-map regression gate.
4. An updated "Verify required scripts exist" step that now also checks
   for `check-mermaid-syntax.mjs`.

`linter-scripts/run.sh` gained a parallel "Step 6 — Mermaid diagram syntax
gate" so contributors hit the same check locally before pushing
(satisfying AC-SAG-23's lockstep requirement: every CI gate must have a
local equivalent).

### Spec lockstep

- **`spec/01-spec-authoring-guide/97-acceptance-criteria.md` v4.2.0 → v4.3.0**:
  Added **AC-SAG-24** documenting the contract — required parser, required
  shim (jsdom + DOMPurify), the three common author mistakes that fail it
  (bare `%%`, unquoted `@`-bearing labels, missing directive), and the
  side-effect-free + deterministic guarantees.
- **`spec/01-spec-authoring-guide/98-changelog.md` v4.7.0 → v4.8.0**: new
  4.8.0 release entry.
- **`spec/01-spec-authoring-guide/99-consistency-report.md` v4.4.0 → v4.5.0**:
  new v4.5.0 update banner; v4.4.0 banner preserved below.

## Verification

### Local
- `node linter-scripts/check-mermaid-syntax.mjs` → **106/106 files parsed cleanly** ✓
- `node linter-scripts/check-tree-health.cjs --strict` → 100/100 (56 modules) ✓
- `node linter-scripts/check-lockstep.cjs --strict` → 0 findings ✓
- `AUDIT_DETERMINISTIC=1 python3 linter-scripts/audit-spec-vs-code-v2.py --min-weighted=97 --min-impl=99` → **PASS at 98.0/99.8** ✓
- All three CLI self-tests (Phases 91, 94, 95): 6/14/7 assertions all pass ✓

### Score impact
None. §01 already received `has_mermaid (+5)` from the existing diagram
under the audit rubric — Phase 97 only validates the syntax, doesn't add
or remove diagrams. §01 score remains 97/100 (A+) with impl=100.

## Why this matters

Phase 97 completes a **three-phase quality triad for the `.mmd` ecosystem**:

| Phase | Asks | Locks |
|---|---|---|
| 93 | "Is the lifecycle diagram faithful to the real pipeline?" | AC-SAG-23 (canonical SoT + lockstep) |
| 97 | "Does every `.mmd` file actually parse?" | AC-SAG-24 (parse-clean gate) |
| (future 100+) | "Does every `.mmd` file render to a valid SVG?" | (would need Chromium — out of scope) |

Phase 97 is the cheap-and-deep middle layer: parse-only validation catches
~95% of author mistakes without the cost of a headless browser. The
`bun install` infrastructure added here also unlocks future Node-based
linters (TypeScript schema validation, MDX checks, etc.).

The two pre-existing bugs found are an honest signal: **the spec tree had
been carrying broken mermaid for unknown duration**, exactly the kind of
silent rot that quality gates exist to prevent.

## Files touched

- **NEW** `linter-scripts/check-mermaid-syntax.mjs` (executable, ~95 lines)
- **NEW** `.lovable/memory/audit/v2-deterministic/phase-97-mermaid-syntax-gate.md` (this memo)
- **EDIT** `.github/workflows/spec-health.yml` (+ setup-bun, + bun install, + mermaid gate step, + verify-script line)
- **EDIT** `linter-scripts/run.sh` (+ Step 6 mermaid gate)
- **FIX** `spec/01-spec-authoring-guide/lifecycle-spec-authoring.mmd` (bare `%%` → `%% --` + validation footer line)
- **FIX** `spec/12-cicd-pipeline-workflows/images/ci-pipeline-flow.mmd` (all labels quoted)
- **EDIT** `spec/01-spec-authoring-guide/97-acceptance-criteria.md` (+ AC-SAG-24, header bump)
- **EDIT** `spec/01-spec-authoring-guide/98-changelog.md` (+ 4.8.0 entry)
- **EDIT** `spec/01-spec-authoring-guide/99-consistency-report.md` (+ v4.5.0 banner)
