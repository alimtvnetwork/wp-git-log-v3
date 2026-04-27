---
name: phase-93-lifecycle-mmd-canonical
description: Phase 93 — rewrote lifecycle-spec-authoring.mmd from 10-node skeleton into 32-node faithful pipeline diagram; added AC-SAG-23 making it canonical SoT
type: feature
---

# Phase 93 — Lifecycle Diagram as Canonical Source of Truth

**Date:** 2026-04-27
**Status:** ✅ Complete
**Trigger:** Phase 91's "Remaining Tasks" queue item #2

## Discovery

The queued task assumed `lifecycle-spec-authoring.mmd` **didn't exist** and
needed creating to fire the `has_mermaid` bonus. Investigation found the
file already existed (10 nodes, no styling), and §01 was already getting
the `+5 has_mermaid` bonus at impl=100. The real gap was **content
fidelity**: the 10-node skeleton showed a generic "Linters Pass?" decision
that bore no resemblance to the actual Phases 81–91 pipeline (lockstep
enforcement, `kind:` branching, `--strict` gates, `--min-weighted` floors,
Phase 91 self-test, `--explain` debugger).

A future contributor reading the diagram would learn nothing about how the
quality gates actually work.

## What changed

### `lifecycle-spec-authoring.mmd`: 10 → 32 nodes, 6 styled classes

Now encodes:

1. **5-way `kind:` branching** at the entry node (`active-spec` /
   `future-spec` / `tracker` / `index` / `meta-toolchain`). Each path leads
   to a kind-appropriate scaffolding step before converging on the
   common authoring node.
2. **Lockstep step**: explicit `Sync §98 + §99 same date` node between
   authoring and the linter pipeline.
3. **6-step local linter pipeline** (matches `linter-scripts/run.sh`):
   `generate-spec-index.cjs` → `check-spec-cross-links.py --github` →
   `check-tree-health.cjs --strict` → `check-lockstep.cjs --strict` →
   `audit-spec-vs-code-v2.py` (with `AUDIT_DETERMINISTIC=1`) →
   `check-trace-map-regression.py`.
4. **6-gate CI sequence** (matches `.github/workflows/spec-health.yml`),
   including the **Phase 91 CLI self-test** as its own node.
5. **Failure-recovery loop** routing through `--explain=<module>` (Phase 90)
   back to the author phase — captures the Phase 87 contributor-DX
   intention.
6. **Post-merge phase memo** node writing to
   `.lovable/memory/audit/v2-deterministic/`.

Six `classDef`s (phase / author / local / ci / decision / done) give visual
distinction so a reader can scan the dark/green/purple/amber colour bands
and instantly see which steps are author-side, local, CI, or terminal.

### `00-overview.md` — inline excerpt rewrite

Replaced the 10-node inline mermaid (which duplicated the broken `.mmd`)
with a 13-node high-level summary that:
- Explicitly delegates to the `.mmd` file as canonical SoT.
- Shows the 5 `kind:` values, the lockstep step, both 6-step pipelines
  (local + CI), the failure loop, and the merge → memo terminal.
- Names Phase 84 floors (`--min-weighted=97 --min-impl=99`) and the
  Phase 91 self-test wiring directly in the explanatory paragraph.

### `97-acceptance-criteria.md` — new AC-SAG-23

Locks the `.mmd` file as canonical and mandates lockstep:
- (a) all 5 `kind:` values branched from entry
- (b) the 6 named local linter scripts present
- (c) the 6 named CI gates present (incl. Phase 91 self-test)
- (d) the `--explain=<module>` failure-recovery loop
- (e) the post-merge phase-memo step

Plus: any add/remove of a linter script or CI gate MUST update the `.mmd`
in the same PR (lockstep with `linter-scripts/run.sh` and
`.github/workflows/spec-health.yml`). On contradiction with the inline
excerpt, the `.mmd` file wins.

## Spec lockstep

| File | Before | After | Change |
|------|--------|-------|--------|
| `spec/01-spec-authoring-guide/lifecycle-spec-authoring.mmd` | 10 nodes | **32 nodes, 6 classes** | Full rewrite |
| `spec/01-spec-authoring-guide/00-overview.md` | v3.6.0 | **v3.7.0** | Inline excerpt rewrite + delegate to `.mmd` |
| `spec/01-spec-authoring-guide/97-acceptance-criteria.md` | v4.1.0 | **v4.2.0** | New AC-SAG-23 |
| `spec/01-spec-authoring-guide/98-changelog.md` | v4.6.0 | **v4.7.0** | New 4.7.0 release entry |
| `spec/01-spec-authoring-guide/99-consistency-report.md` | v4.3.0 | **v4.4.0** | New v4.4.0 update banner |

## Verification

```
$ AUDIT_DETERMINISTIC=1 python3 linter-scripts/audit-spec-vs-code-v2.py \
    --explain=01-spec-authoring-guide
Branch       : normal-contract  (kind=future-spec)
Final score  : 97/100 (A+)  |  impl=100
  +  5  has_mermaid     ← still firing, now justified by a real diagram
  + 10  code_blocks_total=91>=5

$ node linter-scripts/check-tree-health.cjs --strict
✓ PASS: tree health 100 ≥ threshold 100

$ node linter-scripts/check-lockstep.cjs --strict
Findings: 0

$ AUDIT_DETERMINISTIC=1 python3 linter-scripts/audit-spec-vs-code-v2.py \
    --min-weighted=97 --min-impl=99
Mean weighted: 98.0/100  |  Mean implementability: 99.8/100
✓ PASS: thresholds met

$ bash linter-scripts/test/test-audit-cli-thresholds.sh
Results: 6 passed, 0 failed

$ python3 linter-scripts/check-spec-cross-links.py
OK All internal spec cross-references resolve.
```

§01 holds at **97/100 A+** (no regression). Mean weighted **98.0**, mean
impl **99.8** — unchanged. All five gates green.

## Why this matters

The diagram is now a **runnable mental model** of the project's quality
machinery. When a contributor (human or AI) needs to understand how a
change flows from edit to merge, they can read 32 nodes and see:

- Which front-matter key affects which auditor branch (`kind:`)
- Which 6 commands run locally (the same 6 that run in CI)
- Which gate caught their failure (named, not just "Linters Pass?")
- Which flag debugs a score outlier (`--explain=<module>`)
- Where the post-merge memo lives

This is the diagram-form of the `CONTRIBUTING.md` Phase 87 shipped, locked
in by AC-SAG-23 so it can't drift away from the real pipeline.

## Files touched

- `spec/01-spec-authoring-guide/lifecycle-spec-authoring.mmd` — full rewrite
- `spec/01-spec-authoring-guide/00-overview.md` — inline excerpt rewrite + version bump
- `spec/01-spec-authoring-guide/97-acceptance-criteria.md` — AC-SAG-23 added
- `spec/01-spec-authoring-guide/98-changelog.md` — 4.7.0 release entry
- `spec/01-spec-authoring-guide/99-consistency-report.md` — v4.4.0 banner

## Next iteration

When Phase 96 (memo freshness sweep for 90–95) runs, it should record this
phase's "discovery shift" — the queued task's premise was wrong (`.mmd`
already existed) but the underlying intent (faithful lifecycle SoT) was
delivered with broader scope (AC + lockstep + inline excerpt rewrite).
