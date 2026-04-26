# AI-Implementability Audit v2 — Executive Summary

**Date:** 2026-04-25  
**Verdict:** Mean **81.2/100** weighted, **62.2/100** implementability across 79 modules.

## TL;DR

- A mediocre AI could implement **~62.2%** of features from the spec alone.
- 0 F-tier modules; 1 D-tier; 28 A-tier.
- Top blocker categories: `drift` (12), `broken-link` (8), `missing-contract` (6)

## To raise the mean to 80+:
1. Inline contracts (DDL/enums/JSON-schemas) into the highest blast-radius modules first — see table above.
2. Replace waffle words (`should`, `may`, `optionally`) with normative MUST/MUST NOT.
3. Resolve all broken cross-spec links (auto-detected per module).
4. For every D/F module, run `linter-scripts/generate-gwt-acceptance.py` to regenerate ACs.
5. Add `Status: Planned/In-Progress/Implemented` banners so alignment scores reflect intent.

See [00-index.md](./00-index.md) for the full per-module ranking.