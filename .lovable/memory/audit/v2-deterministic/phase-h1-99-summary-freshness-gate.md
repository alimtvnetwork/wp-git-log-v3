---
name: Phase H1 — §99 Summary freshness gate
description: Codifies Phase 136/139 stale-prose lesson into the 15th strict CI gate. Advisory-by-default (89/89 unstamped); becomes strict per-file as authors opt-in to `<!-- verified-phase: NNN -->` stamps under §99 ## Summary.
type: feature
---

# Phase H1: §99 Summary freshness gate (2026-04-28)

**Trigger:** User reply `next` after G-series closure. Picked initiative #4 (§99 freshness gate) from the post-G3 menu.

## What H1 added

| File | Purpose |
|---|---|
| `linter-scripts/check-99-summary-freshness.py` | The gate itself; advisory-then-strict |
| `spec/27-spec-toolchain/26-check-99-summary-freshness.md` | Slot 26 spec with AC-26-01..05 |
| `linter-scripts/test/test-check-99-summary-freshness.sh` | 11-assertion self-test, synthetic sandbox per Phase F3 |

## Stamp convention

```markdown
## Summary
<!-- verified-phase: 147 -->

…narrative claims about counts, versions, status flags…
```

Files **without** the stamp are advisory only. The gate fails strict mode only on files that opted in and have since gone stale (delta > `--max-age`, default 20 phases).

## AC-31-31 4-way lockstep (precedent: F2)

| Site | Change |
|---|---|
| `linter-scripts/audit-spec-vs-code-v2.py` | RUBRIC_VERSION v2.23 → **v2.24**; footer +row #15; section title +H1; EXECUTIVE-SUMMARY 14→15 gates |
| `linter-scripts/test/test-qa-baseline-footer.sh` | awk pattern +`/§99 Summary freshness gate/` |
| `.github/workflows/spec-health.yml` | +1 step (gate only — the self-test is NOT a separate workflow step, see below) |
| `linter-scripts/test/README.md` | +row 8 entry; "Last updated" → H1; totals 7→8 scripts |

## Workflow design decision

Only the gate is wired as a separate workflow step. The `.sh` self-test is **not** a separate step — it would have made workflow_gates = 16 while footer/declared = 15, breaking AC-31-28's gate-count parity. Precedent: Phase 91/94/95/102/103/112/113 self-tests ARE separate steps (each tied to a numbered footer row); H1's self-test is a meta-test of the §99-freshness gate (footer row #15) and is collapsed into that single row to keep the 4-way enumeration consistent.

## Trace-map delta

| Metric | Before | After | Δ |
|---|---|---|---|
| `ac_total` | 1299 | 1304 | +5 (AC-26-01..05) |
| `ac_traced` | 69 | 74 | +5 |
| `code_total` | 46 | 48 | +2 (gate + self-test) |
| `code_orphan` | 24 | 25 | +1 (self-test sandbox script — acceptable; will join Phase 107 ledger if persistent) |

## What did NOT change

- No existing §99 file touched. Stamps are opt-in for future authors.
- No §97 ACs added or renumbered.
- §31 unchanged (the 4-way enumeration update is normal lockstep, not a registry expansion).

## Verification

| Gate | Result |
|---|---|
| H1 self-test | 11/11 ✅ |
| QA-footer test | 11/11 at 15-gate ✅ |
| §27-inventory parity | 6/6 ✅ |
| README-inventory | 22/22 at 8 self-tests ✅ |
| Tree-health strict | 168/168 ✅ |
| Lockstep | 87/87 / 0 findings ✅ |
| Trace-map regression | ✅ at new baseline |
| Audit deterministic | mean 98.0/99.8 unchanged ✅ |

## Lessons

1. **Advisory-then-strict is the right pattern for invasive new gates.** Strict-on-day-one would have required pre-stamping 89 §99 files (out of scope; better suited to R1 or a future opt-in adoption phase).
2. **Self-tests are NOT always separate workflow steps.** When the test exclusively exercises a single numbered footer gate, collapse it into that gate's step to preserve AC-31-28 enumeration parity. Standalone self-test workflow steps belong only to gates that test broader contracts (the audit subsystem #5–#7, the meta-suite #9, #10, #12, #13).

## Reproducibility

```bash
python3 linter-scripts/check-99-summary-freshness.py        # → exit 0, 89 unstamped advisory
python3 linter-scripts/check-99-summary-freshness.py --max-age 10  # same — no opt-ins yet
bash    linter-scripts/test/test-check-99-summary-freshness.sh     # → 11/11
```
