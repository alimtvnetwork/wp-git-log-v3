# Phase 143 — Phase F sub-batch: bulk-classify 13 stale folder refs + AC-62-04

**Date:** 2026-04-28
**Trigger:** User pushed `next` after Phase 142 explicitly recommended halt. Reinterpreted as "do whatever's autonomous-safe, including the cheap part of Phase F." Executed the dump-groups + bulk-classify-obvious sub-batch.

## Scope

Phase F's 26-ref backlog (later remeasured at 29) was triaged into 3 buckets:
1. **Obvious doc-only allowlist additions** (this Phase 143): 13 refs across 11 unique target folders, each verified as a deliberate documentation reference, not authoring drift.
2. **Linter parser bug discovered along the way**: AC-62-04 added to §62.
3. **Genuine user-intent territory**: 11 remaining stale refs across 6 unique missing folders — deferred under Phase F1.

## What landed

### Allowlist additions (13 entries)

| Target | Refs | Source | Classification |
|---|---|---|---|
| `04-some-feature` | 1 | `17-consolidated-guidelines/31-full-tree-ai-audit-v4.md` §3 narrative | doc-only example |
| `30-cross-repo-foo` | 1 | `27-spec-toolchain/62-spec-folder-refs-allowlist.md` §[external] example row | doc-only (spec demonstrating itself) |
| `31-cross-repo-bar` | 1 | same | doc-only (spec demonstrating itself) |
| `99-nonexistent` | 1 | `27-spec-toolchain/02-check-spec-folder-refs.md` AC GWT body | doc-only (linter spec uses fake path) |
| `21-git-logs` | 6 | `17-consolidated-guidelines/33-full-tree-ai-audit-v5.md` historical | doc-only (pre-renumbering) |
| `21-git-logs-v1` | 1 | `22-git-logs-v2/36-why-v1-archived.md` → `_archive/21-git-logs-v1/` | doc-only (archived) |
| `22-app-issues` | 1 | `.lovable/memory/specs/full-tree-audit-v4.md` historical | doc-only (now `25-app-issues`) |
| `29-app-issues-cli` | 1 | `phase-126-folder-17-enumeration-sweep.md` historical | doc-only |
| `12-cicd-pipelines` | 1 | `27-spec-toolchain/02-check-spec-folder-refs.md:49` AC GWT (typo example for `12-cicd-pipeline-workflows`) | doc-only (linter spec uses typo intentionally) |
| `14-generic-update` | 2 | `27-spec-toolchain/04-check-forbidden-spec-paths.md:14,46` (deprecated, merged into `14-update` 2026-04-17) | doc-only (deprecated example) |
| `15-self-update-app-update` | 2 | same | doc-only (deprecated example) |

Net: **29 → 11 stale refs** (-62%).

### AC-62-04 (new)

Codifies a parser bug discovered empirically during the bulk-add: `linter-scripts/check-spec-folder-refs.py:128` is `buckets[current].add(line)` (with `line = raw.strip()` upstream). Inline `# comment` suffixes on entry lines are NOT stripped, so trailers like `04-some-feature  # narrative example` are stored in the bucket as that whole string and never match the bare folder name.

The first allowlist write attempt used inline trailers and the stale count did not drop. Only after moving comments to separate `#`-prefixed lines ABOVE entries did the count drop from 29 → 11. AC-62-04 mandates this format so future contributors don't repeat the mistake.

This is more valuable than the allowlist itself: it converts a silent-failure UX trap into a documented rule.

## Lockstep

| File | Before | After |
|---|---|---|
| `linter-scripts/spec-folder-refs.allowlist` | (no version) | 13 entries added under `[doc-only]` |
| `spec/27-spec-toolchain/62-spec-folder-refs-allowlist.md` | v1.0.0 | **v1.1.0** (Changelog section + AC-62-04) |
| `spec/27-spec-toolchain/98-changelog.md` | v2.30.0 | **v2.31.0** |
| `spec/27-spec-toolchain/99-consistency-report.md` | v2.27.0 | **v2.28.0** |

## Verification

| Check | Result |
|---|---|
| `check-tree-health.cjs --strict` | ✓ 168/168 (56/56 modules at full marks) |
| `check-lockstep.cjs --strict` | ✓ 0 findings |
| `check-spec-folder-refs.py` stale-refs | 29 → **11** |
| Trace-map regression (separate, Phase 117 territory) | unchanged — still RED on drift |

## What's left for the user (Phase F1)

11 stale refs across 6 unique missing target folders, all in `01-spec-authoring-guide/04-ai-onboarding-prompt.md` + a few in `07-design-system/` and `17-consolidated-guidelines/`:

| Target | Refs | Likely intent | Needs user verdict |
|---|---|---|---|
| `08-docs-viewer-ui` | ~5 | Planned future module? Or stale rename? | Yes |
| `09-code-block-system` | ~2 | Possibly merged into `07-design-system/07-code-blocks.md` | Yes (suggest typo→fix) |
| `21-app` | ~4 | Sibling-repo (gitmap-v3 has `01-app`) or planned local? | Yes |

These cannot be classified autonomously — they reflect actual planning/naming ambiguity in the spec authoring guide. Phase F1 awaits user direction.

## Process lessons

1. **Allowlist parser sucks at inline comments** — codified as AC-62-04. Cost: one wasted write attempt + a re-read of the parser source. Cheap lesson, valuable rule.
2. **The dump-groups + bulk-classify pattern works** — going from 29 individual decisions to ~14 per-target decisions to ~3 verdict-classes (narrative-example / archived-historical / deprecated-typo-demo) collapsed the cost from "intractable user backlog" to "one autonomous batch + one user batch."
3. **The script being non-CI-gated is fine for this work** — Phase 143 reduces dormant-linter noise floor, not a CI fix. When Phase F1 finishes, wiring this gate into CI becomes a natural Phase 144 candidate (small, autonomous-safe once the count is at 0).
