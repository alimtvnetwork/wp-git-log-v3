---
name: Phase G2 — AC-binding sweep batch 2
description: Second of three batches re-binding Phase 117's 14 absorbed toolchain scripts. G2 binds 24 ACs across 6 generators (gwt + fix-checklist + gate-report + fill-missing trio); ac_traced 30→54, code_orphan 35→29.
type: feature
---

# Phase G2: AC-binding sweep batch 2 (2026-04-28)

**Trigger:** User reply `next` after G1 closure.

## What G2 did

Appended `# ===== Phase G2 =====` block to `linter-scripts/trace-map.toml` with **24 new `[[trace]]` entries** across 6 scripts:

| AC range | Script | Count |
|---|---|---|
| AC-13-01..03 | `generate-gwt-acceptance.py` | 3 |
| AC-15-01..06 | `generate-fix-checklist.py` | 6 |
| AC-16-01..06 | `generate-gate-report.py` | 6 |
| AC-20-01..03 | `fill-missing-acceptance-criteria.cjs` | 3 |
| AC-21-01..03 | `fill-missing-changelogs.cjs` | 3 |
| AC-22-01..03 | `fill-missing-consistency-reports.cjs` | 3 |
| **Total** | **6 scripts** | **24** |

## Trace-map delta

| Metric | Before | After | Δ |
|---|---|---|---|
| `ac_total` | 1299 | 1299 | 0 |
| `ac_traced` | 30 | **54** | **+24** |
| `ac_drifted` | 1269 | 1245 | -24 |
| `code_total` | 46 | 46 | 0 |
| `code_referenced` | 11 | **17** | **+6** |
| `code_orphan` | 35 | **29** | **-6** |
| `trace_entries` | 30 | 54 | +24 |

All 6 newly-bound scripts decrement orphan count (each had zero prior bindings).

## Implementation gotcha (caught by gate, fixed before rebaseline)

Initial G2 entries assumed `fill-missing-acceptance-criteria`, `fill-missing-changelogs`, `fill-missing-consistency-reports` were `.py`. Actual extensions: `.cjs`. `check-trace-map-regression.py` fired immediately with 9 missing-file errors. Fixed via in-place `sed -i 's|.py|.cjs|g'` for the three affected paths. Rebaseline only ran AFTER the fix.

**Lesson codified for G3:** always `ls linter-scripts/<name>.*` before authoring `[[trace]]` entries. The trace-map regression gate is a strong belt-and-braces check — but pre-flight `ls` makes the diff one-shot instead of needing a fix-up commit.

## What G2 deliberately did NOT do

- No script source touched (pure data update).
- No spec module content changed.
- No new ACs (existing AC IDs only).
- No §31 / AC-31-31 / rubric / footer changes.
- No CI step added.

## Verification

```bash
python3 linter-scripts/check-trace-map-regression.py        # → ✅ no regression
node    linter-scripts/check-lockstep.cjs                   # → 87/87 pass / 0 findings
node    linter-scripts/check-tree-health.cjs --strict       # → 168/168
python3 linter-scripts/check-spec-folder-refs.py            # → 0 stale
bash    linter-scripts/test/test-overview-inventory-parity.sh # → 6/6
```

All passed.

## Remaining G3 backlog

| Module | Bindable ACs | Script extension verified? |
|---|---|---|
| `07-check-readme-canonicals` | 2 | TBD (must `ls` first) |
| `08-check-readme-install-section` | 3 | TBD |
| `09-check-memory-mirror-drift` | 2 | TBD |
| `12-suggest-spec-cross-link-fixes` | 3 | TBD |
| `23-scaffold-spec-module` | 5 | TBD |
| **G3 total** | **15** | |

Projected post-G3 state: `ac_traced` ~69, `code_orphan` ~24. R1 (real-AI re-audit, blocked on Lovable Cloud) remains the deeper rebinding tool for any non-1:1 cases G3 can't cover.

## Lockstep

- §98 v2.39.0 → **v2.40.0**
- §99 v2.36.0 → **v2.37.0**
- Memory index updated with G2-closed marker + the new "ls first" rule.
