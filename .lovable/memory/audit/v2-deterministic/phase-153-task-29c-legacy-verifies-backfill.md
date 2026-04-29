# Phase 153 Task #29c — nested-tier `**Verifies:**` backfill on legacy AC stubs

**Date:** 2026-04-29
**Status:** CLOSED
**User reply:** `next`

## Problem

Phase 153 Task #29b widened `check-ai-confidence.py` to recursive walking,
surfacing 27 advisory drifts under nested sub-modules. ~15 were predicted to be
P3 `**Verifies:**` gaps (Task #31's blind spot). Actual count after inspection:
**4 modules, 18 missing clauses** — all in `*-LEGACY*` deprecation-marker ACs.

## Targets

| Module | Missing | Pattern |
|---|---|---|
| `02-coding-guidelines/01-cross-language/16-static-analysis` | 7 | `AC-SA-LEGACY-001..007` |
| `02-coding-guidelines/02-typescript` | 2 | `AC-TS-LEGACY` (×2) |
| `02-coding-guidelines/06-ai-optimization` | 2 | `AC-AI-LEGACY` (×2) |
| `02-coding-guidelines/07-csharp` | 7 | `AC-CS-LEGACY-01..07` |

## Fix

Two `/tmp/` scripts (per Phase 153 split-driver rule):

1. `bulk_verifies_29c.py` — parses `> Replaced by: AC-XX` blockquote where
   present and back-points the `**Verifies:**` to the modern replacement;
   else cites "the modern numeric-ID ACs in this same §97".
2. `bulk_lockstep_29c.py` — version bumps (§97 minor, §00/§98/§99 patch),
   inserts §98 row + §99 update note where slots exist.

Manual touch-up needed for two non-canonical banner formats:
- §99 in spec/02 sub-modules use `**Generated:**` (not `**Updated:**`).
- §98 in `02-typescript` uses `**Last Updated:**`.
- 16-static-analysis §98 has no `**Version:**` banner — added v4.1.0 row inline.

## Result

| Metric | Before | After |
|---|---|---|
| P3 Verifies drifts (tree-wide) | 4 modules / 18 clauses | **0** |
| AI-confidence `matches` | 24/51 | **27/51** |
| Lockstep | 87/87 | 87/87 (strict) |
| Tree-health | 168/168 strict | 168/168 strict |
| AI-confidence self-test | 5/5 | 5/5 |

3 modules promoted: `02-typescript`, `06-ai-optimization`, `07-csharp` →
declared tier now matches derived. `16-static-analysis` still drifts at P1
(separate inventory gap; tracked as future work).

## Spec lockstep

- `02-coding-guidelines/01-cross-language/16-static-analysis`:
  §97 v4.0.0 → **v4.1.0**, §00 v4.1.0 → **v4.1.1**, §98 row added at v4.1.0,
  §99 v4.0.0 → **v4.0.1**.
- `02-coding-guidelines/02-typescript`:
  §97 v4.0.0 → **v4.1.0**, §00 v4.1.0 → **v4.1.1**, §98 v4.1.0 → **v4.1.1**,
  §99 v4.1.0 → **v4.1.1**.
- `02-coding-guidelines/06-ai-optimization`:
  §97 v4.0.0 → **v4.1.0**, §00 v4.0.0 → **v4.0.1**, §98 v4.0.0 → **v4.0.1**,
  §99 v4.0.0 → **v4.0.1**.
- `02-coding-guidelines/07-csharp`:
  §97 v4.0.0 → **v4.1.0**, §00 v4.1.0 → **v4.1.1**, §98 v4.0.0 → **v4.0.1**,
  §99 v4.0.0 → **v4.0.1**.

## Lessons codified

1. **Banner-format heterogeneity** — spec/02 sub-modules use non-canonical
   banners (`**Generated:**`, `**Last Updated:**`, missing `**Version:**`).
   Future bulk-lockstep scripts MUST tolerate at least these three variants
   OR the script should fail loudly per-module when the regex misses, not
   skip silently. This Phase added a recovery sed-pass; a permanent fix
   would harden `bulk_lockstep_*.py` patterns or normalize the banners.
2. **"~15 expected P3 drifts" was a high-end estimate** — actual P3 count
   was 4 modules / 18 clauses. The remaining 23 advisory drifts split into
   ~10 P1 inventory gaps (real spec work, not mechanical) and ~5 P4
   underclaim drifts (banners stale-down — pure good news, fixable by
   editing the §00 `**AI Confidence:**` value).
3. **Legacy-stub Verifies pattern** is reusable: cite the replacement AC
   from the `> Replaced by:` blockquote, or fall back to a section-level
   pointer. Both satisfy P3 mechanically without inventing new contracts.
