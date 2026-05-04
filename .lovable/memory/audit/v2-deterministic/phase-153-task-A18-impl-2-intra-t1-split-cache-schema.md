# Phase 153 Task A18-impl-2 — Intra-T1 Split + Per-Chunk SHA Cache Schema (Shipped)

**Closed:** 2026-05-04
**Status:** SHIPPED — closes A18-impl-1's known-limitation footnote (single-T1-overflow).

## What shipped

1. **Intra-T1 splitter** (`linter-scripts/audit-ai-implementability.py:316-372`):
   replaces the pathological "T1 alone exceeds cap → truncate to first MAX_BYTES"
   branch with a real split. Anchor pair `00+97` is always emitted together as
   the first chunk; each remaining T1 file (`98`, `99`) gets its own chunk,
   anchor-prefixed when budget allows, solo-truncated as last resort. All
   emitted chunks carry `tier == "T1"`. Closes Lesson #65 spec/27-class
   data-loss path (T1=455 KB previously truncated to first 140 KB).

2. **Per-chunk SHA cache schema** (`audit_module()`): every cache JSON now
   carries `chunks: [{tier, bundle_sha_chunk(16-hex), files(rel-to-ROOT),
   bytes_used}]` for every scored module (single-chunk FULL-tier modules
   emit a single-element array). Composite `bundle_sha` retained unchanged
   so AC-34-15 ≤MAX_BYTES cache parity is preserved. `--no-network --json`
   mode also emits `chunks` for external tooling.

3. **AC-34-16** appended to `spec/27-spec-toolchain/34-audit-ai-implementability.md`
   (slot 34 §00 v1.6.0 → v1.7.0). Banners: §97 v2.13.0 → v2.14.0;
   §00/§98 v2.90.0 → v2.90.1; §99 v2.86.0 → v2.86.1.

4. **Self-test extended 14 → 16 assertions**:
   - #14 synthetic 4×50KB T1-overflow → ≥2 anchor-prefixed all-`tier=="T1"` chunks.
   - #15 `--no-network --json` emits `chunks` array with required keys + 16-hex SHA.

## Gates (all GREEN)

- self-test: **16/16**
- lockstep: **87/87**
- tree-health: **168/168 strict**
- version-parity: **74/74**
- freshness: 81 stamped + 6 exempt + 0 unstamped
- inventory-parity: **6/6**

## Lesson #79 (codified in §98 row)

When extending a cache schema mid-flight, use **additive-only field migration**.
Never rename or remove existing keys. The composite `bundle_sha` is the
cache-validity key; per-chunk SHAs are additive metadata for finer-grained
invalidation. This pattern lets the new schema co-exist with stale caches
indefinitely (no flag day, no migration script) — the `from_cache` fast-path
still hits because `bundle_sha` is computed identically pre/post-A18-impl-2.

## Side-fix during implementation

Test #14 first attempt used `tempfile.mkdtemp()` outside ROOT — `_render()`
calls `f.relative_to(ROOT)` which raised `ValueError: not in subpath`. Fix:
fixture lives at `m.SPEC / "00-aai-t1-overflow-fixture"` (deleted in `finally`).
Mirror of Lesson #38 slug-validation — when authoring synthetic fixtures
for spec-walking tools, the fixture MUST live inside the walked tree.

## Next surface — A18-impl-3 (gateway-dependent)

`--chunked` flag promotion to default + cache-prune tool that consumes the
new per-chunk SHAs to invalidate only stale chunks. Requires:
1. Gateway un-402 for full-tree `--force` rebaseline parity test.
2. Confirmation that no external consumer of `.lovable/cache/audit-ai/*.json`
   breaks on the new `chunks` field (additive, so unlikely — but verify
   `generate-dashboard-data.cjs` and `generate-spec-index.cjs` don't strict-key
   the cache JSON).

## Files changed

- `linter-scripts/audit-ai-implementability.py` (~60 LoC: intra-T1 split + cache_inventory build)
- `linter-scripts/test/test-audit-ai-implementability.sh` (+2 assertions)
- `spec/27-spec-toolchain/34-audit-ai-implementability.md` (AC-34-16 + banner)
- `spec/27-spec-toolchain/{00-overview,97-acceptance-criteria,98-changelog,99-consistency-report}.md` (banners + §98 row + §99 row)
- `.lovable/memory/audit/v2-deterministic/phase-153-task-A18-impl-2-intra-t1-split-cache-schema.md` (this memo)
