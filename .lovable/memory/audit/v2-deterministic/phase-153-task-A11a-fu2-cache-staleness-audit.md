# Phase 153 Task A11a-fu2 — CRITICAL-cache staleness audit (no-op closure)

**Date:** 2026-04-29
**Trigger:** User reply `next` after A11a-fu1 stale-prose refresh closed spec/13 D1 file-grep finding.
**Outcome:** No-op. All 4 cache CRITICALs are already contract-closed in prior phases; cache cannot refresh until A8/A12 (LLM gateway) unblocks.

## Cache enumeration (deterministic)

Ran `python3` over `.lovable/cache/audit-ai/*.json` filtering `severity == 'CRITICAL'`. 4 hits:

| Module | Dim | Title | Closure | Cache stale because |
|---|---|---|---|---|
| `13-generic-cli` | D1 | Conflicting Exit Code Contracts | **A11a (AC-21 §97-WINS)** + **A11a-fu1 (prose refresh)** | Cache predates A11a; v3 baseline. |
| `25-app-issues` | D3 | Cryptographic Contradiction (HS256 vs Argon2id) | **A11c (AC-AI-09/10/11; Lesson #29)** | Quoted-evidence misclassification, not contract gap. |
| `17-consolidated-guidelines` | D2 | ACs lack `**Verifies:**` clauses in 00-overview | **Tasks #29a–#29e + #31 + #34** (P3 sweep CLOSED tree-wide) | 51/51 AI-confidence matches as of #29d. |
| `11-powershell-integration` | D5 | Missing Core Schema and Template Files | **A2 / A4 (walker fix)** | Schemas/templates live outside `spec/` by design; A4 walker no longer mis-flags. |

## Why no-op

- Each finding has a §97 contract pin OR a walker fix already shipped.
- Re-scoring requires LLM gateway (A8/A12) which is gateway-budget-gated → out of scope this session.
- Per **Lesson #30** (verify-before-open): refusing to "open" graduation phases on findings that are already closed. Three audit "next-up" items dissolved on inspection in Tasks #9–#11 by the same rule.
- Per **Lesson #32** (one-finding-per-file trackers): this memo IS that tracker for the 4-CRITICAL bundle, so future `next` invocations can grep and skip.

## Closure index (for future grep)

```
spec/13-generic-cli D1 → AC-21 + A11a-fu1 prose refresh (§00/§98/§99 v1.1.3)
spec/25-app-issues  D3 → AC-AI-10 (quoted-evidence rule, Lesson #29)
spec/17-consolidated-guidelines D2 → Phase 153 Tasks #29a-e + #31 + #34
spec/11-powershell-integration D5 → A2/A4 walker fix (out-of-tree assets)
```

## Lesson #34 — Cache-vs-contract drift class

Audit caches (`.lovable/cache/audit-ai/*.json`) are LLM-derived snapshots; they go stale the moment the spec is patched. Until the LLM gateway is unblocked, **the cache MUST NOT be the authoritative source of CRITICAL counts** — it overcounts by surfacing findings that contract patches have already closed.

**Rule:** when reporting CRITICAL count, ALWAYS cross-reference the cache against:
1. The closing memo (`.lovable/memory/audit/v2-deterministic/phase-153-task-*.md`)
2. The §97 AC index (`grep -l "AC-AI-\|AC-21\|AC-CG-23" spec/*/97-acceptance-criteria.md`)
3. The §98 changelog rows of the affected module

If any of those show a closure post-dating the cache file's `bundle_sha`, the cache row is stale — do NOT open a new phase on it. Mirror of Lesson #30 (verify-before-open), Lesson #32 (anchor-at-source).

## No spec edits, no lockstep ripple

This is a docs-only memo. Tree health 168/168 strict, lockstep 87/87, version-parity 74/74 unchanged.

**Real-CRITICAL count this session: 0.** All 4 cache CRITICALs are closed at the contract layer awaiting cache refresh.
