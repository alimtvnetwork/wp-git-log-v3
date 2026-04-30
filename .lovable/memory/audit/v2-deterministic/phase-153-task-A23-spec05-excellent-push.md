# Phase 153 Task A23 — spec/05 EXCELLENT push: NULL RESULT (Lesson #45 graduated)

**Date:** 2026-04-30
**Module:** `spec/05-split-db-architecture/`
**Pre:** 89/100 GOOD (18,19,17,18,15); axis `normative-contract`.
**Target:** ≥90 EXCELLENT.
**Result:** REVERTED. Score regressed 89 → 82 (−7) after attempt; reverted to 89.

## Attempt

Per Lesson #45 working levers (a)+(b), added two ACs directly in parent §97:
- **AC-SD-27** Application/Project terminology binding (5-row inline table).
- **AC-SD-28** Root DB registry-table column completeness (Project + Database schema floor).

Predicted weighted lift +4.9 → 93.7 EXCELLENT.

## Actual outcome

Post-`--force` rescore: **89 → 82 (−7)**. Dim vector (18,19,17,18,15) → (16,18,17,15,14). New D1/D4 findings surfaced; D5 finding shifted to AC-SD-01 with auditor hint "implementation snippets need to be within first 80 KB".

**Walker-budget cause:** pre-A23 §97 was 38 KB; post-A23 §97 was **45.8 KB**. Bundle math:
§97 (45.8) + §00 (6.6) + §01-fundamentals (31.7) + §98 + §99 ≈ **103 KB > 90 KB walker cap** → `01-fundamentals.md` got pushed out, breaking D1/D4/D5 evidence the auditor previously had.

## Resolution

REVERTED. AC-SD-27 + AC-SD-28 deleted from §97. §97 + §00 banners restored to 4.4.0. §98 → 4.4.1 (this null-result row). §99 → 4.1.1 (this prose row). Cache restored to 89 (18,19,17,18,15) on `--force`. **No content shipped from A23**; only this memo + §98/§99 patch rows persist.

## Lesson #45 GRADUATED

The walker-budget failure mode applies to **ANY new tier-1 content** on a saturated `normative-contract` module — NOT just delegation prose (which was the original A22 framing). Working levers (a) D5 citation clusters and (b) D3 edge-case tables only work when §97 has headroom under the cap.

### Pre-flight discipline (REQUIRED before any future EXCELLENT-band push on `normative-contract` modules)

```
wc -c spec/<module>/97-acceptance-criteria.md \
       spec/<module>/00-overview.md \
       spec/<module>/01-*.md
```

Sum MUST be < **75 KB** (≥15 KB headroom for new content within the 90 KB walker cap). Empirical evidence:

| Module | §97 size pre-attempt | Headroom | Outcome |
|---|---|---|---|
| spec/04 | 12 KB | 78 KB | A21 +8 ✅ |
| spec/03 | 18 KB | 72 KB | A21 +7 ✅ |
| spec/05 | 38 KB | 52 KB | A22 +0 (no harm); A23 −7 (REVERT) ❌ |

### Saturated modules

If sum ≥ 75 KB the module is **saturated** — no in-§97 content additions can lift score. Options:
1. Walker-cap raise (deferred until A12 LLM gateway redesign).
2. §97 sub-extraction RUBRIC pattern (untried; would need new contract for split §97 → §97 + `97-extended/`).
3. Accept GOOD-band score as structural ceiling.

## Gates

All 5 strict CI gates GREEN post-revert: lockstep · tree-health 168/168 strict · version-parity · freshness · folder-refs.
