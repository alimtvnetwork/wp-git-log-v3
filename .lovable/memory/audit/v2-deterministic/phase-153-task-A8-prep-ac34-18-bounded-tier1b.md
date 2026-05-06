# Phase 153 Task A8-prep / R2 — AC-34-18 bounded tier-1B promotion

**Closed:** 2026-05-06
**Class:** Productive walker upgrade (slot 34) — first productive R2-class work since plateau
**User-facing decision:** "Ship now (recommended)" for AC-34-18; AC-34-18-style sub-module recursion deferred indefinitely (user picked "Other" → best-judgment → defer per Lesson #79)

## Summary

Lifted nested `{00,97,98,99}-*.md` contract files to tier-1 priority in
`load_module_bundle()` — but only when combined T1+T1B ≤ MAX_BYTES (140 KB).
Bimodal contract: 6 of 10 affected modules get full clean lift (FITS path);
4 fall back to current behavior (OVERFLOW-fallback path) — zero regression.

## Why

Pre-A8-prep walker priority discipline (AC-34-09) only saw root contract
files. Nested contract files under sub-modules (e.g.
`spec/05/02-features/00-overview.md`) fell back to alphabetical T2/T3
ordering and risked silent truncation under the 140 KB cap. The post-Task-#29b
recursive walker found 51 eligible modules with 27 advisory drifts under
nested sub-modules — this patch closes the nested-contract-invisibility class
for the 6 modules where it can be done safely.

## Probe (Lesson #17 — dry-run BEFORE implementation)

Tree-wide nested-contract probe at MAX_BYTES=140_000:

| Module | root T1 (KB) | nested T1B (KB) | combined | Verdict |
|---|---:|---:|---:|---|
| spec/05 | 75 | 23 | 98 | **FITS** — clean lift |
| spec/06 | 62 | 24 | 86 | **FITS** — clean lift |
| spec/10 | 26 | 17 | 43 | **FITS** — clean lift |
| spec/12 | 57 | 81 | 138 | **FITS** — clean lift (just under cap) |
| spec/18 | 58 | 22 | 80 | **FITS** — clean lift |
| spec/26 | 77 | 11 | 88 | **FITS** — clean lift |
| spec/02 | 99 | **685** | 783 | OVERFLOW-fallback (no regression) |
| spec/03 | 62 | **433** | 495 | OVERFLOW-fallback (no regression) |
| spec/14 | 74 | 78 | 152 | OVERFLOW-fallback (no regression) |
| spec/25 | 58 | 95 | 153 | OVERFLOW-fallback (no regression) |

**Bimodal distribution → bounded promotion + graceful fallback** (NEW Lesson #91).
Blind mass promotion would have bloated spec/02 from 98 KB to 783 KB and
caused catastrophic truncation.

## Implementation

`linter-scripts/audit-ai-implementability.py` `load_module_bundle()`:

```python
# Tier-1B: nested {00,97,98,99}-*.md collected, sorted depth-shallowest-first,
# promoted only when _bytes(tier1) + _bytes(tier1b) <= MAX_BYTES.
tier1b: list[Path] = [
    f for f in files
    if f.name in tier1_names and len(f.relative_to(mod_dir).parts) > 1
]
tier1b.sort(key=lambda p: (len(p.relative_to(mod_dir).parts), str(p)))
if tier1b:
    if _bytes(tier1) + _bytes(tier1b) <= MAX_BYTES:
        for f in tier1b:
            files.remove(f)
        tier1 = tier1 + tier1b
```

## Self-test

`linter-scripts/test/test-audit-ai-tier1b-promotion.sh` (NEW, 6 assertions, ~80 LoC):

- T1 (FITS): spec/05 → 4 root T1 + 8 nested T1B in first 12 entries
- T2 (OVERFLOW-fallback): spec/02 → 4 root T1 + ≤3 nested T1B in first 12 entries (no mass promotion)
- T3 (no T1B): spec/22 → bundle unchanged

**Result: 6/6 PASS.**

## Live re-score (deferred per Lesson #20)

Gateway returned HTTP 402 on `--force` call against spec/05 despite
`LOVABLE_API_KEY` set — Lesson #86 oscillation pattern reconfirmed.
Score-validation deferred to A8 full-tree rebaseline once gateway budget
restores. Expected lift on FITS modules: +2-5 score points (D2 AC Coverage
dimension primarily, mirroring AC-34-09's spec/05 +20 lift pattern but
bounded by ceiling effects since 5/6 FITS modules already score ≥87/100).

## Lockstep

| File | Before | After | Reason |
|---|---|---|---|
| slot 34 §00 banner | v1.9.0 | **v1.10.0** | minor — new AC-34-18 |
| spec/27 §00 | v2.90.3 | **v2.91.0** | minor — child slot got new AC |
| spec/27 §98 | v2.90.3 | **v2.91.0** | minor — new changelog row |
| spec/27 §99 | v2.86.3 | **v2.87.0** | minor — new audit row |

## Strict gates (all GREEN)

- check-lockstep: 87/87, 0 findings
- check-tree-health --strict: 100/100, 56/56 modules
- check-version-parity --strict: 74/74 matches, 0 mismatches
- check-99-summary-freshness --strict-position: 81 stamped + 6 exempt + 0 unstamped
- test-audit-ai-tier1b-promotion: 6/6 PASS (NEW)
- test-overview-inventory-parity: 6/6 PASS (Phase F3 hard rule satisfied)

## NEW Lesson #91

**Walker tier-1 promotion patches MUST run a Lesson #17 dry-run probe BEFORE
implementation.** The bimodal FITS vs OVERFLOW byte-size distribution is the
design driver for "bounded promotion + graceful fallback" vs "blind mass
promotion". Without the probe, this patch could have bloated spec/02 from 98
KB to 783 KB (8× MAX_BYTES) and caused catastrophic content truncation at the
cap. Probe-then-design is the correct sequence; design-then-probe risks
shipping a regression on the OVERFLOW class.

Codified inside spec/27 §98 v2.91.0 row.

## Non-goals (deferred)

**AC-34-18-style sub-module recursion** for the 4 OVERFLOW giants (spec/02,
03, 14, 25) — treating each nested `<NN>-<name>/` containing `00-overview.md`
as an independent audit pseudo-module with depth-weighted score aggregation
— is the proper design for those modules. Deferred indefinitely per Lesson
#79 (saturation triage):

- spec/02 already at 90/EXC via current flat walker
- spec/03 already at 87/GOOD via current flat walker
- spec/14 + spec/25 not in saturation class at last cache snapshot

Sub-module recursion would be a 2-phase design+impl effort with diminishing
returns. Park as backlog item; revisit only if a future v5+ baseline shows
spec/02 or spec/03 dropping into NEEDS_WORK band.

## Cross-references

- AC-34-09 (root tier-1 priority, Phase 153 Task A6) — AC-34-18 extends
- AC-34-13/14 (MAX_BYTES = 140_000)
- AC-34-15/16/17 (chunked re-scoring path — orthogonal, both now apply)
- Lesson #16 (LLM auditors with bounded context windows MUST tier contract files)
- Lesson #17 (dry-run probe before walker edits)
- Lesson #20 (HTTP 402 → defer score, don't block phase)
- Lesson #38 (gateway availability check at phase start)
- Lesson #79 (saturation triage — diminishing-returns work)
- Lesson #86 (gateway oscillation re-probe rule)
- Lesson #91 (NEW — codified in spec/27 §98 v2.91.0 row)
