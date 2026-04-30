# Phase 153 Task A20 — Rubric v7 LLM Rebaseline + Slot 35 Snapshot

**Date:** 2026-04-30
**Closes:** A20 (LLM rebaseline with v7; supersede `34-full-tree-ai-audit-v6.md` → `35-full-tree-ai-audit-v7.md`)
**Status:** ✅ CLOSED

## What shipped

1. **Forced refresh** of all 23 audit caches under Rubric v7 (`audit-ai-implementability.py --strict --force`).
2. **Snapshot** as `spec/17-consolidated-guidelines/35-full-tree-ai-audit-v7.md` (v7.0.0).
3. **Banner-superseded** `34-…-v6.md` (added `**Superseded by:**` line; v6 deterministic 168/168 result preserved as historical baseline per Lesson #18).
4. **Lockstep**: spec/17 §00 3.4.7 → 3.5.0; §98 3.4.7 → 3.5.0 (new release row); §99 4.6.7 → 4.7.0 (release narrative + Validation History row + Summary stamp 152 → 153); §00 inventory file count 35 → 36.
5. **Side-fix**: 4 stale-folder refs surfaced from A19 self-test fixture (`spec/00-aai-axis-test-fixture/`) — added to `[doc-only]` allowlist with full Why prose.

## Headline numbers

| Metric | v6 LLM | v7 | Δ |
|---|---:|---:|---:|
| Tree mean | 82.3 | **83.7** | **+1.4** |
| EXCELLENT (≥90) | 4 | **5** | +1 |
| NEEDS_WORK (60-74) | 1 | **2** | +1 |
| BLOCKING (<60) | 0 | **0** | held |

**Top movers (+):** spec/10 +12, spec/01 +7, spec/02 +6, spec/11 +6, spec/26 +5, spec/23 +4, spec/25 +4, spec/15 +3, spec/17 +3.

**Honest-baseline corrections (-):** spec/14 -10, spec/07 -9 (Rubric v7 D2/D3 weighting caught over-credit on narrative/process content).

## Per-axis observation

- **`audit-corpus`** (4 modules): boost worked — spec/10 +12, spec/26 +5, spec/25 +4. spec/03 stuck at 74 (needs single D5 citation cluster).
- **`process-guidance`** (4 modules): mixed — spec/01 +7, spec/17 +3, spec/18 ±0, spec/07 -9 (over-credited on D2 in v6).
- **`integration-spec`** (2 modules): spec/11 +6, spec/12 ±0.
- **`tooling-spec`** (1): spec/27 +1 (smallest delta — already had reasonable D2/D3 coverage).
- **`normative-contract`** (12): mostly held or improved; spec/14 -10 honest correction (narrative D5 over-credit).

## All 5 strict gates GREEN

```
lockstep            : 87/87  · 0 findings
tree-health (strict): 168/168 · 100/100
version-parity      : 74/74 matches · 0 mismatches
freshness           : 81 stamped + 6 exempt + 0 unstamped
folder-refs         : 0 stale (4 A19 fixture refs allowlisted [doc-only])
```

## NEEDS_WORK close-out targets (optional A21)

- **spec/03-error-manage** (74, audit-corpus) — 1pt below GOOD; needs one D5 citation cluster lift.
- **spec/04-database-conventions** (74, normative-contract) — 1pt below GOOD; D3 edge-case enumeration thin.

Both mechanically closeable in a single follow-up phase.

## Lesson #42 codified

> When a new LLM-baseline supersedes the prior baseline (v6 → v7), the prior file MUST get a `**Superseded by:**` line in its banner block AND its data MUST be preserved (not deleted) — historical baselines remain comparable evidence for future rubric changes (mirror of Lesson #18: honest-baseline preservation).

Codified inline in spec/17 §98 v3.5.0 release row + slot 35's `## Supersedes` block + §99 v4.7.0 release narrative.

## Files changed

- `spec/17-consolidated-guidelines/35-full-tree-ai-audit-v7.md` (NEW)
- `spec/17-consolidated-guidelines/34-full-tree-ai-audit-v6.md` (banner: `**Superseded by:**` line)
- `spec/17-consolidated-guidelines/00-overview.md` (banners + inventory row 35)
- `spec/17-consolidated-guidelines/98-changelog.md` (banner + release row 3.5.0)
- `spec/17-consolidated-guidelines/99-consistency-report.md` (banner + release narrative + Summary stamp + Validation History row)
- `linter-scripts/spec-folder-refs.allowlist` (4 A19 fixture refs → `[doc-only]`)
- `.lovable/cache/audit-ai/*.json` (23 files refreshed)
- `.lovable/memory/audit/v2-deterministic/audit-ai-implementability-latest.md` (auto-rewritten by auditor)
