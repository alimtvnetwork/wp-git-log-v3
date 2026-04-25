# AI-Implementability Audit v2 — Executive Summary

**Date:** 2026-04-25  
**Verdict:** Mean **72.8/100** weighted, **52.2/100** implementability across 78 modules.

## TL;DR

- A mediocre AI could implement **~52.2%** of features from the spec alone.
- 0 F-tier modules; 6 D-tier; 5 A-tier.
- Top blocker categories: `broken-link` (41), `missing-contract` (33), `untestable` (23)

## To raise the mean to 80+:
1. Inline contracts (DDL/enums/JSON-schemas) into the highest blast-radius modules first — see table above.
2. Replace waffle words (`should`, `may`, `optionally`) with normative MUST/MUST NOT.
3. Resolve all broken cross-spec links (auto-detected per module).
4. For every D/F module, run `linter-scripts/generate-gwt-acceptance.py` to regenerate ACs.
5. Add `Status: Planned/In-Progress/Implemented` banners so alignment scores reflect intent.

See [00-index.md](./00-index.md) for the full per-module ranking.