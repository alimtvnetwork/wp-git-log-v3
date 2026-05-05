# Phase 153 Task A18-impl-3 — Chunked re-scoring promoted to default

**Date:** 2026-05-05 · **Status:** CLOSED · **Gates:** 4/4 GREEN

## Summary
Closed the AC-34-15(e) "promotion to default" milestone by flipping `--chunked` CLI default `False` → `True` and wiring `audit_module()` to route multi-chunk modules through the gateway-per-chunk path with `merge_chunk_scores()` weighted-merge per AC-34-15(d).

## Changes
- `linter-scripts/audit-ai-implementability.py` (~50 LoC):
  - `audit_module()` gained `use_chunked: bool = True` param
  - Multi-chunk branch: per-chunk `call_gateway()` + `parse_score()` + `merge_chunk_scores()`
  - `bundle_sha`: folds `chunked={1,0}` tag ONLY for multi-chunk modules (FULL-tier path bit-identical)
  - CLI: `--chunked` (default=True, no-op) + new `--no-chunked` (rollback)
  - `parsed["chunked_path"]` flag on every result
- `spec/27-spec-toolchain/34-audit-ai-implementability.md`: AC-34-17 `[high]` (banner v1.7.0 → v1.8.0)
- §27 lockstep: §97 v2.14.0 → v2.15.0; §00/§98/§99 v*.*.1 → v*.*.2

## Parity invariant (AC-34-15(b)) — verified
spec/16-generic-release (FULL-tier, ≤MAX_BYTES):
- `--chunked` (default): bundle_sha = `e16de187513b288e`
- `--no-chunked`:        bundle_sha = `e16de187513b288e` ✓ byte-identical

## Live re-score deltas
| Module | Before | After | Δ | Note |
|---|---:|---:|---:|---|
| spec/27-spec-toolchain | 83 | **88** | **+5** | Contract surface previously truncated now visible (8 files × 30 chunks vs single 140KB slice) |
| spec/12-cicd-pipeline-workflows | 87 | 84 | −3 | Honest baseline per Lesson #18 (chunked path scores ALL 49 files) |

Net mean impact for the two targeted modules: +1.0 pts.

## Self-test
All **16 assertions PASS** unchanged (parity verified by existing test #12 + cross-flag bundle_sha equality on spec/16).

## Deferred
Tree-wide rebaseline of remaining 14 multi-chunk modules → backlog **#10** (gateway-cost-bounded, ~480 chunk calls).

## Lesson #79 (codified inside §98 row)
Multi-phase rollouts (opt-in helpers → splitter → default-on flip) work — A18-impl-1/2/3 each shipped self-test green with parity invariant verifiable at every step. Default-on flips MUST preserve byte-identical hashes for unchanged-behavior paths so existing caches stay valid; flag-state-folded hashes apply ONLY to paths whose behaviour actually changes. Rollback flag (`--no-chunked`) mandatory on default-on flips.
