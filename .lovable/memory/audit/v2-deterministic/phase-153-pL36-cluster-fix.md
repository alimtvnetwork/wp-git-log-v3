# Phase 153 pL36-cluster-fix — three-gate regression repair

**Closed:** 2026-04-29
**Type:** mechanical fix (cluster-terminal sweep regression catch)

## What

Cluster-terminal sweep (P40 runner) caught **3 silent gate regressions** that had accumulated over the prior 5+ phases without surfacing because no individual phase ran the runner. Verifies the P34/P38/P42/P43 cadence rule for the **5th time** empirically.

## Findings + fixes

### Finding 1 — cross-links: 10 broken `**Source:**` line-3 anchors in spec/17

Root cause: the v3.4.5 P-L36-fu sweep that authored these headers used legacy/aspirational sibling-folder slugs instead of canonical disk slugs.

**7 renames (real → real)**:
| File | Old | New |
|---|---|---|
| `01-spec-authoring.md` | `../01-spec-authoring/` | `../01-spec-authoring-guide/` |
| `11-research.md` | `../02-coding-guidelines/research/` | `../02-coding-guidelines/10-research/` |
| `12-root-research.md` | `../research/` | `../10-research/` |
| `14-app-issues.md` | `../21-app/issues/` | `../25-app-issues/` |
| `16-app-design-system-and-ui.md` | `../21-app/design-system/` | `../24-app-design-system-and-ui/` |
| `27-linter-authoring-guide.md` | `../27-linter-authoring-guide/` | `../27-spec-toolchain/` |
| `28-distribution-and-runner.md` | `../28-distribution-and-runner/` | `../15-distribution-and-runner/` |

**3 doc-only conversions (link → inline-code with rider)**:
- `08-docs-viewer-ui.md`, `09-code-block-system.md`, `13-app.md` — folders genuinely don't exist (Phase F1 doc-only classification). Live links converted to `` `../<slug>/` `` inline-code spans + explicit `*(documentation-only — folder not yet materialised; see Phase F1 classification)*` rider. Per the P44 inline-code-blanking-parity contract, the cross-link gate now correctly skips these.

### Finding 2 — folder-refs: 1 stale ref in last cycle's pL36-codify memo

`spec/24-design-system/` (does not exist) → `spec/24-app-design-system-and-ui/`. Same root cause as Finding 1 — author memory of slug shape diverged from disk truth. Fixed in `phase-153-pL36-codify.md` line 20 + `mem://process/phase-153-lessons` Section G line 117.

### Finding 3 — README inventory: 16 fs vs 15 README (slot 16 missing)

`linter-scripts/test/test-audit-ai-implementability.sh` was added in Phase 153 Task A4 but never inserted into `linter-scripts/test/README.md` test-inventory table. Added as row 16 (CLI-surface contract for slot 34 `audit-ai-implementability.py` in no-network mode; AC-34-01..06; 6 assertions; ~5 s). Bumped totals (14 → 16 scripts, ~36 → ~41 s CI), refreshed `**Last updated:**` line, added to Local-execution bash list.

## Lockstep applied

- **spec/17** §00/§98/§99: 3.4.5 → **3.4.6** + 3.4.5 → **3.4.6** + 4.6.5 → **4.6.6** (patch — header-metadata fix, no §97 contract change). h10 stamp 48 → 153 in §00.
- §98 release row 3.4.6 added with full rename table + Lesson #36 reinforcement.
- §99 v4.6.6 prose update with the same.
- `linter-scripts/test/README.md` `**Last updated:**` line refreshed (no banner version on README).

## Lessons

**NEW Lesson #38 — bulk-sweep tools MUST validate slug input against disk before running.** The v3.4.5 sweep produced 10 broken anchors out of 15 (67% failure rate) entirely because the input slug list was author-memory-derived rather than `ls spec/`-derived. Future sweep tools authoring navigation links across ≥N files SHOULD ingest the canonical sibling-folder list at run-time via `ls spec/ | grep -E "^[0-9]{2}-"`, not from memory.

**Lesson #36 reinforced** for sweep-authored content: even when the author understands the link-don't-restate rule, mechanical sweep authoring is the secondary failure mode — the rule says nothing about WHICH path to link to, and that's where 67% of sweep output failed.

**Cluster-cadence rule reconfirmed (5th empirical validation)**: the P40 runner (P34→P38→P42→P43→P49→pL36-cluster-fix) now has a 5/5 hit rate on catching multi-phase silent regressions. Cluster-cadence pattern is mature; new linter-script or spec-edit clusters MUST close with a `bash linter-scripts/test/cluster-terminal-sweep.sh` invocation.

## Verification

Post-fix: cluster-terminal sweep **9/9 GREEN**, lockstep 87/87, tree-health 168/168 strict, version-parity 74/74 stamped + 0 mismatches.
