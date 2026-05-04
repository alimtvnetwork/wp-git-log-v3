# Phase 153 Task A18-design — Chunked Re-Scoring Design Memo

**Status:** DESIGN-ONLY (pure docs, no spec/script edits)
**Date:** 2026-05-04
**Scope:** Sketch the architecture for raising effective walker coverage
beyond the `MAX_BYTES = 140_000` Cloudflare-safe ceiling without violating
the per-request byte cap.
**Trigger:** Gateway is 402-blocked again (LOVABLE_API_KEY present but
budget exhausted), and remaining D5 HIGH findings (`spec/{04,05,06,01}` and
the L75-blocked cluster `spec/{17,18,22,25,27}`) are all walker-cap derived.
A durable fix requires multi-pass scoring; this memo specifies it before
implementation so future sessions can ship without rediscovery.

## 1. Problem statement

A18-full raised `MAX_BYTES` from 120 KB → 140 KB and codified the dynamic
truncation marker (AC-34-14, Lesson #77). Live probes confirm 140 KB is the
hard Cloudflare-1010 ceiling. Modules that exceed 140 KB of normative
content (`spec/{04,12,17,18,22,25,27}` cluster) saturate the walker and
produce LLM-fabricated D5 findings against truncated content rather than
genuine spec gaps. AC-34-12 + AC-34-14 already pin this as a harness
artifact, but the underlying coverage gap remains a real ceiling on
audit fidelity.

## 2. Design: tiered chunked re-scoring with weighted merge

### 2.1 Tier definitions

Per-module file ordering already follows tier-1 priority (Lesson #16,
A6 walker fix): `{00,97,98,99}-*.md` always loaded first. Extend to:

| Tier | Globs | Weight | Rationale |
|------|-------|------:|-----------|
| **T1 — contract** | `{00,97,98,99}-*.md` | 1.00 | Normative AC surface; always in every chunk |
| **T2 — algorithms** | `0[1-9]-*.md`, `1[0-9]-*.md` | 0.85 | Implementation prose; high D2/D3 signal |
| **T3 — examples** | `2[0-9]-*.md`, `3[0-9]-*.md` | 0.60 | Worked examples, references; D5 signal |

Score is `weighted_mean(tier_score × tier_weight)` per dimension, then the
existing AC-34-10 axis multipliers + AC-34-11 soft cap apply unchanged.

### 2.2 Chunk packing algorithm

```
def pack_chunks(files, max_bytes=140_000):
    t1 = [f for f in files if is_tier1(f)]      # always-included
    t1_size = sum(sz(f) for f in t1)
    budget = max_bytes - t1_size - OVERHEAD     # room for one tier-N file set
    chunks = []
    for tier in (T2_FILES, T3_FILES):
        cur, cur_sz = [], 0
        for f in tier:
            if cur_sz + sz(f) > budget:
                chunks.append((tier_label, t1 + cur))
                cur, cur_sz = [], 0
            cur.append(f); cur_sz += sz(f)
        if cur: chunks.append((tier_label, t1 + cur))
    return chunks  # each ≤140 KB, T1 always present
```

T1 is duplicated across every chunk (fixed cost, ~30 KB typical) so the
LLM always re-anchors against the contract surface. This eliminates the
"AC-truncation false-CRITICAL" class (Lesson #11) at the cost of N×T1
gateway calls per module instead of 1.

### 2.3 Score merge

Per chunk i, the LLM returns a per-dimension score `s_{i,d}`. Merge:

```
final_d = sum(weight(tier_i) × s_{i,d}) / sum(weight(tier_i))
```

Findings are deduplicated by `(severity, dimension, first_120_chars)` —
T2/T3 chunks routinely surface the same finding from different evidence,
which is fine; the union is the audit output.

## 3. Cost model

| Module size | Current calls | Chunked calls | Multiplier |
|------------:|--------------:|--------------:|-----------:|
| ≤140 KB | 1 | 1 (no change) | 1.0× |
| 140–280 KB | 1 (truncated) | 2 | 2.0× |
| 280–420 KB | 1 (truncated) | 3 | 3.0× |
| ≥420 KB | 1 (truncated) | 4 | 4.0× |

Tree-wide: of 23 modules, ~7 are >140 KB (the L75-blocked cluster).
Estimated incremental cost for a full rebaseline: **+10 to +14 LLM calls
per `--force` rebaseline**, well within the gateway budget when un-402'd.

## 4. Cache key impact

`bundle_sha` becomes `bundle_sha_chunk_i` per-chunk. Cache file format
extends to:

```json
{
  "module": "spec/17",
  "chunks": [
    {"tier": "T1+T2_a", "bundle_sha": "...", "scores": {...}, "findings": [...]},
    {"tier": "T1+T2_b", "bundle_sha": "...", "scores": {...}, "findings": [...]},
    {"tier": "T1+T3_a", "bundle_sha": "...", "scores": {...}, "findings": [...]}
  ],
  "merged": {"d1": ..., "d2": ..., "total": ..., "band": "EXCELLENT"}
}
```

Chunk-level cache hits remain valid across re-runs; only chunks whose
constituent file SHAs changed need re-scoring. Big efficiency win on
incremental phases where only one T2 file moved.

## 5. Lockstep impact when shipped

- `linter-scripts/audit-ai-implementability.py`: ~80-line patch
  (chunk-packer + merge function + cache schema migration).
- Slot 34 §00 banner bump: minor (new behaviour).
- New AC: **AC-34-15** — Chunked re-scoring contract (T1 always present;
  weighted-merge formula; dedup key; cache schema).
- New self-test: assert that for a synthetic 280 KB module, two chunks are
  emitted and both contain T1 file SHAs.
- RUBRIC bump: v2.24 → v2.25 (new gate behaviour, not new gate).
- §27 §97 + §98 + §99 patch bumps; spec-health.yml unchanged (gate
  identity preserved).

## 6. Migration path

1. **A18-design (this memo)**: pure docs — DONE.
2. **A18-impl-1**: implement chunk-packer behind a `--chunked` flag
   (default off); parity-test against current single-pass output on
   ≤140 KB modules (must be byte-identical).
3. **A18-impl-2**: enable `--chunked` by default; ship AC-34-15;
   refresh cache for >140 KB cluster only.
4. **A18-rebaseline**: `--force` full-tree rebaseline once gateway is
   un-402'd; expect L75-blocked D5 findings to either close (genuine
   harness artifact) or escalate to actionable (genuine spec gap).

Steps 2–4 are gateway-dependent. Step 1 is pure-code and can ship in
the next gateway-blocked session.

## 7. Lessons referenced

- L11 — walker MUST walk full sub-tree
- L16 — tier-1 contract files first (now generalized to T1/T2/T3)
- L18 — honest baseline corrections expected on bias fixes
- L34 — cache is LLM-derived; not authoritative
- L75 — walker-cap D5 findings are harness artifacts, not spec gaps
- L77 — dynamic markers prevent stale-literal regressions
- L78 — A-series phases are often 2-edit when scoped right

## 8. Out-of-scope (deferred)

- LLM provider switch (sticking with Cloudflare gateway for budget reasons).
- Streaming responses (gateway doesn't support per-token streaming yet).
- Multi-model consensus (single-model audit is the current contract;
  changing it requires AC-34-09 module-kind pin re-evaluation).

---

**No spec edits, no script edits, no lockstep ripple.** This memo
unblocks the next gateway-available session to ship A18-impl-1
without rediscovery.
