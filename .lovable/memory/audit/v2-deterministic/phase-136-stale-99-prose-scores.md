# Phase 136 — Stale §99 prose-score discovery (no spec edits)

**Date:** 2026-04-27
**Trigger:** `next` after Phase 135. Autonomous queue was empty; ran a forensic sweep instead of inventing a phantom phase.

## Discovery

`grep`-scanning every `99-consistency-report.md` for the pattern `(\d+)/100` revealed **20 modules whose §99 prose still cites sub-100 rubric-v1 scores** even though the dashboard's rubric-v2 generator reports the tree at 100/100 (A+):

| Score | Modules |
|-------|---------|
| **45/100 (F)** | `14-update/24-update-check-mechanism`, `17-consolidated-guidelines`, `18-wp-plugin-how-to` |
| **60/100 (D)** | `10-research` (correctly marked "placeholder folder, content pending") |
| **75/100** | `15-distribution-and-runner` |
| **85/100** | 13 modules under `02-coding-guidelines/`, `05-split-db-architecture/`, `06-seedable-config-architecture/`, `14-update/diagrams/`, `25-app-issues/` |
| **90–98/100** | `28-universal-ci-cli`, `12-cicd-pipeline-workflows/03-reusable-ci-guards`, `03-error-manage/02-error-architecture` |

## Root cause

These are **textual narrative scores** — historical headlines from audit-v3.5.0 / audit-v4 / earlier rubric-v1 passes — quoted inside §99 prose as part of audit-trail context. They are NOT live computed metrics. The live metric is in `check-tree-health.cjs` (rubric-v2), which reports the tree at 100/100.

Concrete proof inside `spec/17-consolidated-guidelines/99-consistency-report.md`:
- Top of file (v3.5.0 update banner): cites historical **"45/100 (F)"**
- Bottom of file: states current **"Health Score: 100/100 (A+)"**

Both are correct in their own context but a casual reader scanning §99 will believe the module is failing.

## Why no spec edits in Phase 136

- Fixing the prose in 20 modules is a **20-file lockstep** with banner bumps and §98 entries — too broad for an autonomous "next" cycle without user scope confirmation.
- Three reasonable strategies, none obviously dominant:
  - **A — Annotate**: prepend each historical score with `[rubric-v1, superseded]` (minimal, preserves audit trail, ~20 files).
  - **B — Strip**: delete historical score lines entirely (cleanest, but destroys audit narrative; conflicts with "audit-trail rule" cited in `30-threat-model` exemption).
  - **C — Tier-cap**: only fix the four lowest (`45`/`45`/`45`/`60`) where the contrast with the tree-wide 100/100 is most misleading; leave 75/85 modules alone (cheapest, accepts residual confusion).
- Recommended: **A** (preserves audit history, single regex sweep possible, low risk).

## Backlog impact

New backlog item queued: **Phase S — stale §99 prose-score sweep** (3 strategies above, A recommended).

## Files touched

- `.lovable/memory/audit/v2-deterministic/phase-136-stale-99-prose-scores.md` — this memo only.

No spec-tree edits, no banner bumps, no §98 entries. Pure discovery + queue.
