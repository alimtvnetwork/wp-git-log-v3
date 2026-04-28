# Phase 27 — Root §97 (AC-ROOT-01..08) Freshness Verification (No-Questions Mode 11/40)

**Date:** 2026-04-28
**Trigger:** Phase 26 close-out rec (c) — confirm AC-ROOT-01..08 still match
the post-Phase-23-verified inventory (26 rows = 23 active + 2 locked + 1 archived).

## Method

Per-AC verification against current `spec/00-overview.md` and filesystem:

| AC | Verified target | Result |
|----|-----------------|--------|
| AC-ROOT-01 | Module Inventory bijection (26 rows) | ✅ 26 inventory rows confirmed |
| AC-ROOT-02 | Slots 08/09 immutable, "Locked vacant" wording | ✅ Both rows present |
| AC-ROOT-03 | SpecTreeIndex JSON-Schema in §"Normative Contract" | ✅ Line 72 + `$schema` line 81 |
| AC-ROOT-04 | Slug + Path regex patterns | ✅ Present in Normative Contract |
| AC-ROOT-05 | Status enum `["active","locked-vacant","deprecated","slot-collision"]` | ✅ Exact match line 100 |
| AC-ROOT-06 | 4 Supporting Files exist + listed L65-68 | ✅ All 4 files on disk + listed |
| AC-ROOT-07 | `kind: index` frontmatter + AC-31-15 ref | ✅ Both present |
| AC-ROOT-08 | Lockstep across §00/§97/§98/§99 | ✅ `check-lockstep.cjs --strict` 0 findings |

## Banner alignment (root module)

| File | Version | Updated |
|------|---------|---------|
| §00 overview | 3.5.0 | 2026-04-27 |
| §97 ACs | 1.0.0 | 2026-04-27 |
| §98 changelog | 3.5.0 | 2026-04-27 |
| §99 consistency | 4.1.0 | (banner only, lockstep date-relation passes) |

All dates aligned at 2026-04-27. Lockstep gate confirms 0 findings.

## Decision: No-op (verification confirmed clean)

All 8 root ACs accurately describe current spec reality. No drift.

## Files touched

None — read-only verification + this memo.

## Lesson

Root-module ACs (`spec/97-acceptance-criteria.md`) are a stable surface
because they describe **structural invariants** (inventory bijection,
locked-vacant slots, schema enforcement) rather than version-specific
counts. This contrasts with module-level §97 files where ACs often
embed numeric thresholds that require maintenance. **Pattern**:
write structural-invariant ACs at the root level, defer numeric-bound
ACs to module-level §97 where they live closer to the source they
quantify.
