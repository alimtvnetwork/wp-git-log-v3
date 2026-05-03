# Phase 153 Task A05-fu — spec/05 §00 walker-pin teaser refresh (pure-promotion)

**Date:** 2026-05-03
**Module:** `spec/05-split-db-architecture/`
**Status:** CLOSED
**Outcome:** Patch-only refresh; §97 untouched; LLM re-score deferred (gateway HTTP 402, Lesson #20).

## Findings addressed (audit-v7 cache `.lovable/cache/audit-ai/05-*.json`)

| # | Sev/Dim | Finding | Resolution |
|---|---------|---------|------------|
| 1 | HIGH/D5 | Inheritance/cross-module citation drift | Pre-closed by AC-SD-24 (existing) — re-anchored in §00 teaser |
| 2 | MEDIUM/D3 | Polyglot driver concurrency contract | **Pure-promotion cross-link** to canonical owner `spec/13-generic-cli/10-database.md` § "Concurrency & Locking (Normative)" per Lesson #36 (link-don't-restate). spec/05 already carries language-agnostic PRAGMA+retry in AC-SD-22. |
| 3 | LOW/D1 | `ProjectSlug` vs `Slug` ambiguity | Re-anchored as walker-cap artifact; canonical column already pinned in `01-fundamentals.md` Table: Database |

## Lesson #45 hit + recovery

Initially attempted to author **AC-SD-27** inlining the cross-ref contract. Pre-flight `wc -c §97 §00 §01 = 82.5 KB` exceeded the 75 KB headroom from the §99 v4.1.1 A23 precedent (post-A23 was 103 KB → −7 regression). **Reverted §97 edit BEFORE re-score.** Retained as teaser-only promotion in §00. Confirms Lesson #45 budget rule applies symmetrically to all `normative-contract` modules at saturation.

## Lockstep

- §97 untouched (v4.4.1 preserved)
- §00 v4.4.4 → **v4.4.5**
- §98 v4.4.4 → **v4.4.5** (new release row)
- §99 v4.1.4 → **v4.1.5** (banner only)

Patch-only — no new AC, no AC-31-31 cascade, no RUBRIC bump, no CI change, no gate-count change.

## Gates

- Lockstep 87/87 · 0 findings — GREEN
- Tree-health 168/168 strict — GREEN
- Version-parity 74/74 · 0 mismatches — GREEN

## Lessons reinforced

- **#36** (link-don't-restate): cross-module concurrency contract owned by spec/13 §10 — spec/05 links rather than duplicates.
- **#45** (saturation budget): pre-flight `wc -c` on tier-1 files BEFORE drafting any §97 add on `normative-contract` modules. Third reinforcement on spec/05 (after A22 +0 and A23 −7).
- **#63** (walker-pin teaser): refresh §00 teaser against current cache snapshot whenever findings shift, even when §97 cannot grow.
