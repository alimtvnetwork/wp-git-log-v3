---
name: Phase G3 — AC-binding sweep batch 3 (FINAL)
description: Final batch re-binding Phase 117's 14 absorbed toolchain scripts. G3 binds 15 ACs across 5 scripts; ac_traced 54→69, code_orphan 29→24. Cumulative G1+G2+G3: +45 ACs (+188%), -15 orphans.
type: feature
---

# Phase G3: AC-binding sweep batch 3 — FINAL (2026-04-28)

**Trigger:** User reply `next` after G2 closure.

## What G3 did

Appended `# ===== Phase G3 =====` block to `linter-scripts/trace-map.toml` with **15 new `[[trace]]` entries** across 5 scripts:

| AC range | Script | Count | Ext |
|---|---|---|---|
| AC-07-01..02 | `check-readme-canonicals.py` | 2 | .py |
| AC-08-01..03 | `check-readme-install-section.py` | 3 | .py |
| AC-09-01..02 | `check-memory-mirror-drift.py` | 2 | .py |
| AC-12-01..03 | `suggest-spec-cross-link-fixes.py` | 3 | .py |
| AC-23-01..05 | `scaffold-spec-module.cjs` | 5 | **.cjs** (caught by pre-flight `ls`) |
| **Total** | **5 scripts** | **15** | |

Pre-flight `ls linter-scripts/<name>.*` (G2 lesson) caught `scaffold-spec-module.cjs` before authoring — zero fix-up cycle this batch.

## Trace-map delta (G3 alone)

| Metric | Before | After | Δ |
|---|---|---|---|
| `ac_total` | 1299 | 1299 | 0 |
| `ac_traced` | 54 | **69** | **+15** |
| `ac_drifted` | 1245 | 1230 | -15 |
| `code_total` | 46 | 46 | 0 |
| `code_referenced` | 17 | **22** | **+5** |
| `code_orphan` | 29 | **24** | **-5** |
| `trace_entries` | 54 | 69 | +15 |

## Cumulative G1+G2+G3

| Metric | Pre-G1 | Post-G3 | Δ | % |
|---|---|---|---|---|
| `ac_traced` | 24 | **69** | **+45** | **+188%** |
| `code_orphan` | 39 | **24** | **-15** | **-38%** |
| `code_referenced` | 7 | **22** | **+15** | **+214%** |
| `trace_entries` | 24 | 69 | +45 | +188% |

All 14 Phase 117–absorbed scripts now mechanically bound in `trace-map.toml`:
- G1 (4): check-mermaid-syntax, check-memo-retrospective-headings, deepen-consistency-reports, check-spec-cross-links (partial via AC-62-02)
- G2 (6): generate-gwt-acceptance, generate-fix-checklist, generate-gate-report, fill-missing-acceptance-criteria, fill-missing-changelogs, fill-missing-consistency-reports
- G3 (5): check-readme-canonicals, check-readme-install-section, check-memory-mirror-drift, suggest-spec-cross-link-fixes, scaffold-spec-module
- Plus check-spec-folder-refs (was already partly bound; G1 added AC-62-03/04)

## What G3 deliberately did NOT do

- No script source touched (pure trace-map data).
- No spec module content changed.
- No new ACs.
- No §31 / AC-31-31 / rubric / footer / CI changes.

## What remains AC-unbound (24 orphan code files)

These are NOT Phase 117–absorbed scripts. They are:
- `linter-scripts/run.sh`, `linter-scripts/run.ps1` — orchestrators (covered by AC-T-08 workflow binding indirectly)
- `linter-scripts/audit-spec-vs-code.py` (v1, deprecated; v2 is bound)
- Validators called only via `run.sh` and not yet specced under their own §27 slot
- Helper/library files (`*-helper.py`, internal `_lib.py` etc.)
- `.lovable/memory/audit/*.cjs` analysis scripts (out of §27 scope)

R1 (real-AI re-audit, blocked on Lovable Cloud) is the proper tool — it would do deeper semantic binding (e.g., recognising that `run.sh` aggregates §27 gates and binding it to the umbrella AC-T-08 workflow assertion at function granularity, rather than the script-level binding we already have).

## Verification

```bash
python3 linter-scripts/check-trace-map-regression.py        # → ✅ no regression
node    linter-scripts/check-lockstep.cjs                   # → 87/87 pass / 0 findings
node    linter-scripts/check-tree-health.cjs --strict       # → 168/168
python3 linter-scripts/check-spec-folder-refs.py            # → 0 stale
bash    linter-scripts/test/test-overview-inventory-parity.sh # → 6/6
```

All passed.

## Lockstep

- §98 v2.40.0 → **v2.41.0**
- §99 v2.37.0 → **v2.38.0**
- Memory index updated with G3-closed marker + cumulative G-series totals.

## G-series closure

Phase G is **DONE**. Phase 117's containment annotation can be cross-referenced as resolved for the orphan-rebinding portion (the 800-AC drift portion remains legitimate spec growth, no action needed). The next layer of trace-map improvement requires R1.
