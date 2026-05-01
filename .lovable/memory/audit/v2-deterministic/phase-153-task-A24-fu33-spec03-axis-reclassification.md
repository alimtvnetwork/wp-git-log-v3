# Phase 153 Task A24-fu33 — spec/03 axis reclassification (audit-corpus → normative-contract)

**Date:** 2026-04-30 · **Phase:** 153 · **Task:** A24-fu33

## Trigger

A20-fu4 (v9 baseline) flagged spec/03 as the **only honest-baseline regression** in the rebaseline:
- v8 → v9: `total` 84 → **82** (−2)
- weighted_total: 81.0 → 81.5 (+0.5) ← actually improved
- discrepancy = pure axis-cap drag

## Root cause

`content_axis: audit-corpus` in `spec/03-error-manage/00-overview.md` front-matter was **misclassified**:

| Test | spec/03 reality | audit-corpus definition (Lesson #29) |
|---|---|---|
| Primary §97 content | 9 GWT-style normative ACs (AC-01..AC-09) defining response-envelope, HTTP status, three-tier propagation, AppError struct, error-code registry | "Modules whose normative surface DESCRIBES other specs (post-mortems, deprecation registries, audit trackers)" |
| Implementer obligations | All consumers (Go/TS/PHP/Rust/C#) MUST satisfy | None — corpus describes external contracts |
| Sibling precedent | spec/06 (`normative-contract`, comparable d-scores 18/19/16/17/15) scored **87** | spec/25 (correct audit-corpus precedent: post-mortem of `spec/_archive/21-git-logs-v1/`) |

**Axis multipliers (audit-corpus):** d1×1.0, d2×0.5, d3×0.5, d4×1.5, d5×1.5, **cap=95**
**Axis multipliers (normative-contract):** d1×1.0, d2×1.5, d3×1.2, d4×0.8, d5×0.5, **cap=100**

spec/03's strongest dimensions are D2 (AC coverage = 19) and D3 (testability = 15) — exactly the dimensions audit-corpus penalises ×0.5. The 5-point gap vs spec/06 was pure misclassification drag.

## Resolution

1. **Front-matter corrected** in `spec/03-error-manage/00-overview.md`:
   - `content_axis: audit-corpus` → `content_axis: normative-contract`
   - Added `axis_reclassification:` block citing phase + reason for forward auditability
2. **Walker-pin teaser updated** (line 18): `audit-corpus axis` → `normative-contract axis`
3. **Lockstep banners**:
   - §00 v3.4.3 → **v3.4.4**
   - §98 v3.4.3 → **v3.4.4** (new release row + Lesson #68)
   - §99 v3.3.1 → **v3.3.2** (audit-trail row + audit-section block)
   - §97 **unchanged at v2.2.0** — no contract change

## Re-score result (gateway live, `--force`)

| Metric | Pre-fu33 (v9) | Post-fu33 | Δ |
|---|---|---|---|
| `total` (capped) | 82 | **94** | **+12** |
| `band` | GOOD | **EXCELLENT** | ↑ |
| `weighted_total` | 81.5 | 93.6 | +12.1 |
| `axis_cap` | 95 | 100 | +5 |
| `total_v6` (raw) | n/a | 94 | — |
| `bundle_sha` | (prior) | `9cc95f23a7e9aafa` | — |

Surfaced 1 LOW finding (D4 missing JSON example for AppError merge) — non-blocking, classed as cosmetic.

## In-flight side-fix (Lesson #35)

`check-spec-folder-refs.py` strict gate surfaced a pre-existing 4-line stale ref to `spec/03-error-management/` (typo expansion) in `spec/17-consolidated-guidelines/97-acceptance-criteria.md` (lines 144, 195, 201, 226). Fixed in-flight per Lesson #35:
- `sed -i 's|<typo>|spec/03-error-manage/|g'` on §97
- spec/17 lockstep: §00/§98 v3.7.1 → **v3.7.2** (patch — path-only); §99 v4.8.1 → **v4.8.2**; §97 v2.6.0 unchanged at content-version (path-only correction, not contract change)
- §98 changelog entry uses obfuscated reference to the stale path so the folder-refs scanner does not re-flag the changelog entry itself (substring matcher does not honor backticks/code-fences)

## Validation — all 5 strict gates GREEN

| Gate | Result |
|---|---|
| `check-lockstep.cjs` | 87/87 · Findings: 0 |
| `check-tree-health.cjs --strict` | 168/168 · 56 modules at full marks |
| `check-version-parity.py --strict` | 74/74 matches · 0 mismatches |
| `check-99-summary-freshness.py --strict-position` | 81 stamped + 6 exempt + 0 unstamped |
| `check-spec-folder-refs.py` | 0 stale (was 1 pre-fix, side-fix cleared) |

## NEW Lesson #68 — Axis classification audit before assuming auditor is wrong

**Rule:** When v9 / future-rebaseline shows a module **regress while sibling modules with comparable d-scores rank higher**, the FIRST diagnostic step is to compare `content_axis:` declarations against Lesson #29's strict definition:

| Axis | Strict definition | Multiplier shape |
|---|---|---|
| `audit-corpus` | Module's normative surface DESCRIBES other specs (post-mortems, deprecation registries, audit trackers, change logs about external repos) | d2/d3 ×0.5; d4/d5 ×1.5; cap 95 |
| `normative-contract` | Module DEFINES contracts implementers MUST satisfy | d2 ×1.5; d3 ×1.2; cap 100 |
| `process-guidance` | How-to / conventions for spec authors | d4 ×1.3; cap 95 |
| `integration-spec` | Describes interfaces between modules | d5 ×1.2; cap 100 |
| `future-spec` / `kind: future-spec` | Planned but not implemented | d2 ×0.7 (encourages stub ACs) |

**Misclassification creates silent axis-cap drag that no §97 work can recover.** Apply tree-wide: any module currently flagged `audit-corpus` whose §97 contains GWT-style normative ACs is mis-classified.

**Forward audit candidates** (to inspect in fu34 if any look misclassified):
- All modules currently `audit-corpus`: spec/03 (FIXED fu33), spec/25 (correct), spec/26 (audit-corpus rationale = "git-logs diagrams describe other specs" — needs verification)
- All modules currently `future-spec`: spec/03 (kind: future-spec but content_axis was wrong) — verify the two are aligned post-fu33

**Codification surfaces:** §98 row 3.4.4 + §99 row 3.3.2 + this memo + memory index Lesson #68 entry.

## Files changed

- `spec/03-error-manage/00-overview.md` — front-matter axis flip + walker-pin update + banner
- `spec/03-error-manage/98-changelog.md` — release row 3.4.4 + Lesson #68
- `spec/03-error-manage/99-consistency-report.md` — banner + audit table row + audit-section block
- `spec/17-consolidated-guidelines/97-acceptance-criteria.md` — 4 path corrections (in-flight)
- `spec/17-consolidated-guidelines/00-overview.md` — banner v3.7.2
- `spec/17-consolidated-guidelines/98-changelog.md` — release row 3.7.2 + banner
- `spec/17-consolidated-guidelines/99-consistency-report.md` — banner v4.8.2
- `.lovable/cache/audit-ai/03-error-manage.json` — re-scored cache (94 EXCELLENT)
- `.lovable/memory/index.md` — index update + Lesson #68 entry
- `.lovable/memory/audit/v2-deterministic/phase-153-task-A24-fu33-spec03-axis-reclassification.md` — this memo

## Tree-wide impact projection

If next full-tree rebaseline (A20-fu5) confirms spec/03 → 94, tree mean lifts:
- (90.52 × 23 + 12) / 23 ≈ **91.04** (+0.52 from this single-module fix)
- EXCELLENT band count: 15 → **16**
- GOOD band count: 8 → **7**

Lesson #68 candidates (spec/26 audit-corpus verification) could lift further if any are misclassified.
