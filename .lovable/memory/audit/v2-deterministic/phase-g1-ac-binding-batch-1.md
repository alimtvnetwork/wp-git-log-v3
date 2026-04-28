---
name: Phase G1 — AC-binding sweep batch 1
description: First of three batches re-binding Phase 117's 14 absorbed toolchain scripts. G1 binds 6 ACs (AC-62-02/03/04 + AC-T-22/23/24); ac_traced 24→30, code_orphan 39→35.
type: feature
---

# Phase G1: AC-binding sweep batch 1 (2026-04-28)

**Trigger:** User reply `next` after autonomous-queue exhaustion; chose initiative #1 (AC-binding sweep) from the post-F3 menu. G1 is the smallest sub-batch.

## What G1 did

Appended a new `# ===== Phase G1 =====` block to `linter-scripts/trace-map.toml` with **6 new `[[trace]]` entries**:

| AC | Script | Bucket |
|---|---|---|
| `27-spec-toolchain/62-spec-folder-refs-allowlist.md#AC-62-02` | `check-spec-cross-links.py` + `spec-folder-refs.allowlist` | A (allowlist parser & consumers) |
| `27-spec-toolchain/62-spec-folder-refs-allowlist.md#AC-62-03` | `check-spec-folder-refs.py` + `spec-folder-refs.allowlist` | A |
| `27-spec-toolchain/62-spec-folder-refs-allowlist.md#AC-62-04` | `check-spec-folder-refs.py` + `spec-folder-refs.allowlist` | A |
| `27-spec-toolchain/97-acceptance-criteria.md#AC-T-22` | `check-mermaid-syntax.mjs` | B (Phase 108-full migrations) |
| `27-spec-toolchain/97-acceptance-criteria.md#AC-T-23` | `check-memo-retrospective-headings.py` | B |
| `27-spec-toolchain/97-acceptance-criteria.md#AC-T-24` | `deepen-consistency-reports.py` | B |

## Trace-map delta

| Metric | Before | After | Δ |
|---|---|---|---|
| `ac_total` | 1299 | 1299 | 0 |
| `ac_traced` | 24 | **30** | **+6** |
| `ac_drifted` | 1275 | 1269 | -6 |
| `code_total` | 46 | 46 | 0 |
| `code_referenced` | 7 | **11** | **+4** |
| `code_orphan` | 39 | **35** | **-4** |
| `trace_entries` | 24 | 30 | +6 |
| `missing_ac` | 0 | 0 | 0 |
| `missing_file` | 0 | 0 | 0 |

`code_orphan` drops by 4, not 6, because AC-62-02/03 share two scripts (`check-spec-cross-links.py` and `check-spec-folder-refs.py`) — only the previously-unreferenced ones decrement the orphan count.

Baseline file: `.lovable/memory/audit/trace-map-baseline.json` updated via `--update-baseline`.

## Why G1 first (smallest meaningful slice)

1. **Closes Phase 108-full loop end-to-end.** AC-T-22/23/24 were authored Phase 108-full but lived only in §97 prose — `trace-map.toml` had zero edges to them. G1 makes them mechanically discoverable from the trace-map.
2. **Validates the rebaseline ritual** for a multi-script binding sweep before the larger G2 (~15 entries across 5 scripts: fill-missing trio + generate-gate-report + generate-gwt-acceptance) and G3 (~17 entries across 6 scripts) batches.
3. **Bucket A (allowlist ACs)** is naturally adjacent to F1/F2's recent allowlist work — least context-switching cost.

## What G1 deliberately did NOT do

- **No script source touched.** Pure data update to `trace-map.toml`.
- **No spec module content changed.** AC text in §62 and §97 is the source of truth; G1 only references existing IDs.
- **No new ACs.** §97 / §62 AC counts unchanged.
- **No §31 / AC-31-31 / rubric implications.** Trace-map is not part of the 4-way enumeration enforced by AC-31-31.
- **No CI step added.** `check-trace-map-regression.py` is already CI-wired.

## Verification

```bash
python3 linter-scripts/check-trace-map-regression.py        # → ✅ no regression (after rebaseline)
node    linter-scripts/check-lockstep.cjs                   # → 87/87 pass / 0 findings
node    linter-scripts/check-tree-health.cjs --strict       # → 168/168 (all 56 modules full marks)
python3 linter-scripts/check-spec-folder-refs.py            # → 0 stale references
bash    linter-scripts/test/test-overview-inventory-parity.sh # → 6/6
```

All passed.

## Remaining G2/G3 backlog (pre-allocated)

| Batch | Modules | Bindable ACs | Trace entries |
|---|---|---|---|
| G2 | `13-generate-gwt-acceptance` (3), `15-generate-fix-checklist` (6), `16-generate-gate-report` (6), `20-fill-missing-acceptance-criteria` (3), `21-fill-missing-changelogs` (3), `22-fill-missing-consistency-reports` (3) | 24 | ~24 |
| G3 | `07-check-readme-canonicals` (2), `08-check-readme-install-section` (3), `09-check-memory-mirror-drift` (2), `12-suggest-spec-cross-link-fixes` (3), `23-scaffold-spec-module` (5) | 15 | ~15 |
| **Sum (G2+G3)** | 11 modules | 39 | ~39 |

Combined with G1's +6 → projected final state after G3: `ac_traced` 30 → ~69, `code_orphan` 35 → ~24. R1 (real-AI re-audit, blocked on Lovable Cloud) remains the deeper rebinding tool — G1/G2/G3 only address the obvious 1:1 script↔AC bindings.

## Lockstep

- §98 v2.38.0 → **v2.39.0**
- §99 v2.35.0 → **v2.36.0**
- Memory index updated with G1-closed marker.

## Reproducibility

```bash
# Inspect what G1 added
sed -n '/Phase G1/,$p' linter-scripts/trace-map.toml
```
