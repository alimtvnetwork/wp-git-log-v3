# Phase 153 Task A18-impl-1 — Chunked Re-scoring (Shipped)

**Closed:** 2026-05-04
**Status:** SHIPPED — design-doc A18 → production code

## What shipped
- `linter-scripts/audit-ai-implementability.py`: `pack_chunks()`, `merge_chunk_scores()`, `--chunked`, `--chunk-stats`. Tier weights T1=1.0 / T2=0.85 / T3=0.6, MAX_BYTES=140_000. T1 (00/97/98/99) duplicated across every chunk for context anchoring.
- Parity guarantee: modules ≤140KB produce byte-identical results vs legacy `load_module_bundle`.
- `linter-scripts/test/test-audit-ai-implementability.sh`: 9 → 14 assertions (chunk parity, T1 anchor presence, weighted-merge math).
- `spec/27-spec-toolchain/34-audit-ai-implementability.md`: v1.5.x → **v1.6.0**, AC-34-15 codified.
- Lockstep banners: §27 §00/§98 + §99 patch-bumped.

## Gates
Self-test 14/14 · inventory parity 6/6 · lockstep 87/87 · version-parity 74/74 · tree-health 168/168 strict — all GREEN.

## Lesson
Walker-cap mitigation requires both (a) chunk-level packing AND (b) T1 anchor duplication; without (b) chunks lose contract context and D2 scores drop systematically. T1 anchor cost is bounded (≤~25KB across spec/) so duplication is cheap.

## Next
**A18-impl-2** — intra-file splitting for single files >140KB (rare: only ~3 files tree-wide), plus cache-schema migration to record per-chunk SHAs so partial re-scores invalidate correctly.
