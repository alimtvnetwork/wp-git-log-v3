# Phase 153 Task #31 — Boilerplate §97 Verifies-coverage CLOSED tree-wide

**Date:** 2026-04-29
**Driver:** Bulk-backfill the 24 boilerplate-template §97 files discovered as audit-v6 blind spots in Phase 153.

## Context

Phase 153 spec/11 fix patched 1/24 modules. Phase 153 Task #29a patched the
scaffolder (`fill-missing-acceptance-criteria.cjs`) to prevent recurrence.
This phase backfills the remaining 24 modules.

(Note: Discovery count was originally "23 + spec/11"; final enumeration
confirmed 24 actually-affected modules, all boilerplate-pattern.)

## Change

**192 `**Verifies:**` clauses inserted across 24 §97 files** via
`/tmp/bulk_verifies.py`. Anchors mirror the Phase 153 spec/11 mapping:

| AC | Verifies anchor |
|----|-----------------|
| AC-01 | §00 Module overview baseline (H1 + Version + Updated banner) |
| AC-02 | §00 cross-reference inventory; `linter-scripts/check-spec-cross-links.py` |
| AC-03 | `spec/01-spec-authoring-guide/02-naming-conventions.md` §Filename pattern |
| AC-04 | §99 File Inventory rubric |
| AC-05 | `linter-scripts/check-tree-health.cjs` §required=2/2 contribution |
| AC-06 | `linter-scripts/audit-spec-vs-code-v2.py` rubric v2.13 (G-CON-01 contract gate) |
| AC-07 | `linter-scripts/check-spec-cross-links.py` §Phase 81 strict gate |
| AC-08 | `linter-scripts/check-lockstep.cjs` §strict date+phase parity |

## Lockstep

72 sibling files bumped via `/tmp/bulk_lockstep.py`:
- 24× §97: minor bump (1.0.0 → 1.1.0, or 2.0.0 → 2.1.0 for spec/13-generic-cli)
- 24× §98: patch bump + Phase 153 Task #31 row
- 24× §99: patch bump + Phase 153 Task #31 summary row

All updated to date 2026-04-29.

## Modules patched

```
spec/02-coding-guidelines/03-golang/01-enum-specification
spec/02-coding-guidelines/08-file-folder-naming
spec/02-coding-guidelines/10-research
spec/02-coding-guidelines/11-security/01-axios-version-control
spec/02-coding-guidelines/22-app-issues
spec/03-error-manage/01-error-resolution
spec/03-error-manage/02-error-architecture
spec/03-error-manage/02-error-architecture/04-error-modal/01-copy-formats
spec/03-error-manage/02-error-architecture/04-error-modal/02-react-components
spec/03-error-manage/02-error-architecture/04-error-modal/04-color-themes
spec/03-error-manage/02-error-architecture/06-apperror-package/01-apperror-reference
spec/03-error-manage/03-error-code-registry
spec/05-split-db-architecture/02-features
spec/05-split-db-architecture/03-issues
spec/06-seedable-config-architecture/02-features
spec/06-seedable-config-architecture/03-issues
spec/12-cicd-pipeline-workflows/01-browser-extension-deploy
spec/12-cicd-pipeline-workflows/02-go-binary-deploy
spec/12-cicd-pipeline-workflows/03-reusable-ci-guards
spec/13-generic-cli
spec/14-update/diagrams
spec/18-wp-plugin-how-to/02-enums-and-coding-style
spec/25-app-issues/01-phase-2-git-logs-audit
spec/25-app-issues/02-consolidated-audit-findings
```

## Validation

- Tree-wide grep: **0 boilerplate §97 files missing `**Verifies:**` clauses** (down from 24).
- `node linter-scripts/check-lockstep.cjs --strict` → **PASS** (87/87, 0 findings).
- `node linter-scripts/check-tree-health.cjs --strict` → **PASS** (168/168, 100/100).

## Lessons

1. **Mass-mechanical sweeps SHOULD use one driver script for content + a separate driver for lockstep.** Splitting `bulk_verifies.py` and `bulk_lockstep.py` made each easier to verify and re-run.
2. **The Verifies-anchor map is reusable scaffolder DNA.** The 8-AC anchor mapping should now live as a constant in
   `linter-scripts/fill-missing-acceptance-criteria.cjs` so any future
   boilerplate refresh inherits the same anchors. Currently the mapping is
   duplicated in three places: `/tmp/bulk_verifies.py`, the spec/11 fix,
   and the scaffolder template strings. Future task: factor into a shared
   constant (low priority; scaffolder + template are now in sync).
3. **Phase 153 Task #29a + #31 together close the boilerplate Verifies-drift loop:**
   #29a prevents NEW drift, #31 eliminates EXISTING drift. P3 sweep is now
   genuinely **CLOSED tree-wide** — Core memory's claim is true again.

## Open follow-ups

- **Task #29b** still relevant: fix `check-ai-confidence.py` to actively
  flag boilerplate-template §97 files missing `**Verifies:**` so any future
  regression (e.g. someone hand-authoring a §97 without using the scaffolder)
  is caught automatically.
- **Task #32** still open: `spec/07-design-system` §00 v3.4.0 vs §98 v1.7.0
  pre-existing version-parity drift.


---

**Related lessons:** see [`mem://process/phase-153-lessons`](../../../memory/process/phase-153-lessons.md) for the consolidated Phase 153 contributor rules (#11–#37).
