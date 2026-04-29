# Phase 153 Task #29e — AI Confidence underclaim banner promotions

**Date:** 2026-04-29
**Status:** CLOSED
**User reply:** `next`

## Problem

Phase 153 Task #29b's recursive walker surfaced 9 modules where the declared
`**AI Confidence:**` value was `High` but `check-ai-confidence.py` derived
`Production-Ready` (P1+P2+P3+P4 all pass). Pure stale-banner underclaims —
the modules already meet the highest tier; only the metadata lagged.

## Targets (9 modules promoted)

| Module |
|---|
| `02-coding-guidelines/07-csharp` |
| `02-coding-guidelines/08-file-folder-naming` |
| `02-coding-guidelines/11-security` |
| `03-error-manage/01-error-resolution/app-issues` |
| `03-error-manage/02-error-architecture/04-error-modal` |
| `03-error-manage/03-error-code-registry/07-schemas` |
| `03-error-manage/03-error-code-registry/08-linter-scripts` |
| `23-app-database` |
| `24-app-design-system-and-ui` |

## Fix

`/tmp/bulk_lockstep_29e.py` — single in-line `sed` for the AI Confidence line
(`High` → `Production-Ready`) per §00, then patch-bump §00/§98/§99 versions
+ Updated/Last Updated/Generated dates (heterogeneity-tolerant per Task #29c
lesson #4), append §98 changelog row, prepend §99 update note.

## Result

| Metric | Before | After |
|---|---|---|
| AI-confidence `matches` | 27/51 | **36/51** |
| `mismatches` | 24 | **15** |
| Underclaim drifts (declared=High / derived=Production-Ready) | 9 | **0** |
| Lockstep | 87/87 strict | 87/87 strict |
| Tree-health | 168/168 strict | 168/168 strict |

Remaining 15 drifts are **all P1 inventory gaps** (declared=High/PR /
derived=None) — real spec work (sibling .md files exist on disk but not
listed in §00 inventory tables). Tracked as Task #29d.

## Spec lockstep

Each promoted module: §00 patch-bump (banner Version + AI Confidence value),
§98 patch-bump + new changelog row, §99 patch-bump + new update note.
Two modules (`08-file-folder-naming`, `03-error-code-registry/07-schemas`)
have §00 files lacking a `**Version:**` banner — promotion still applied
to the AI Confidence value; §98/§99 captured the lockstep bump.

## Lessons codified

1. **Underclaim sweeps are 5-minute high-leverage wins** — pure metadata
   edits that immediately move the AI-confidence match-rate up. Sequence
   them BEFORE inventory sweeps (which are real spec work).
2. **`**Version:**` is not universal in §00 across the spec tree** — some
   modules (especially `02-coding-guidelines/08`, `03-error-code-registry/07`)
   carry only the AI Confidence + Updated banners. This is acceptable;
   bulk scripts MUST tolerate the absence and still apply the targeted
   banner edit.
3. **Banner-edit lockstep budget**: when changing §00's AI Confidence value
   only (no AC change), patch-bumps on §00/§98/§99 are correct (§97 stays
   unchanged). Distinguishes from Task #29c's pattern where §97 minor was
   appropriate (new content, even on legacy stubs).
