# Phase 153 P3 — spec/17 self-lift NO-OP (work already shipped)

**Date:** 2026-05-05 · **Status:** CLOSED (no-op) · **Mode:** No-Questions (3/40)

## Summary

P2 forensics flagged spec/17 as the highest-leverage offline target
(D5=15/20, mult=1.0, headroom +5.0). On inspection, **all 3 cached
issues are already closed** in §97 by Phase 153 Task A24-fu18:

| Cache issue | Closed by | Status |
|---|---|---|
| HIGH/D5 "Broken Cross-References to Source Folders" | AC-10 + AC-11 (Subfolder Delegation Map with `[STUB]` markers) | ✅ shipped |
| MEDIUM/D3 "Truncated Content in Consolidated Guidelines" | AC-15 (rollup-not-first-party-contract pin) | ✅ shipped |
| LOW/D1 "Version Parity Logic Complexity" | AC-13 (Source-Wins conflict-resolution) | ✅ shipped |

§97 currently at v2.6.0 with 15 ACs (AC-10..AC-15 added in A24-fu18).
§00 carries the walker-pin teaser (Lesson #55 sixth instance).

## Why the cache hasn't moved

`bundle_sha=8961f4fd8326e2a3` · `files_used=6/39 @ 140000 bytes` · `chunked_path=None`

Walker is **FULL-tier saturated** at 140 KB on the §00 overview alone —
the 6 visible files exhaust the cap before reaching `97-acceptance-criteria.md`
where AC-10..15 live. Lesson #16 (tier `{00,97,98,99}-*.md` first) does NOT
help here because the §00 file alone is too large.

**The proper fix is the chunked walker (A18-impl-3).** spec/17 needs to be
re-scored with `--chunked` so the auditor can see all 39 files and the
A24-fu18 pins.

## Probe (per Lesson #38 — gateway-check-first)

```
$ test -n "$LOVABLE_API_KEY"  # KEY SET
$ python3 linter-scripts/audit-ai-implementability.py \
    --module 17-consolidated-guidelines --force --chunked
17-consolidated-guidelines  ERROR: HTTP Error 402: Payment Required
```

Gateway is hard-402. Defer per Lesson #20.

## Decision

- **No spec edits.** All contract work for spec/17 is already shipped.
- spec/17 re-score → backlog (gateway-gated alongside P1/P5).
- Next offline target: **P4 — spec/18-wp-plugin-how-to** (D5+5, but
  needs to be inspected for already-shipped work the same way).

## Lesson #81 (codified for §98)

**Before opening any "self-lift" phase from a stale cache, grep §97 for
ACs added since the cache's audit phase.** spec/17's cache predated
A24-fu18 by an unknown number of weeks; the 3 issues had been closed
for a full session but the cache still flagged them. Future "next
offline target" picks MUST include a **§97-grep-against-cache-issues**
step before allocating spec-edit effort. Mirror of Lesson #30
(verify-before-open) for the cache-vs-§97 axis.
