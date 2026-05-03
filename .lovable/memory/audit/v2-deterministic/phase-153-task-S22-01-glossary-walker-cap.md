# Phase 153 Task S22-01 — spec/22 D4 MED `Truncated Glossary` walker-cap close-out

**Date:** 2026-05-03  
**Module:** `spec/22-git-logs-v2/`  
**Status:** CLOSED (Lesson #39 / #74 verify-on-disk artifact, no content gap)

## Audit finding (cache: `.lovable/cache/audit-ai/22-git-logs-v2.json`)

```
[D4 MEDIUM] Truncated Glossary and Enums
why: 01-glossary-and-enums.md is truncated at the 136KB cap, losing the
     TypeScript Mirror and Enum Catalog required for implementation.
fix: Move the TypeScript Mirror and Enum Catalog to the top of file 01
     or provide them in a separate file.
```

## Lesson #39 evidence triple (on-disk verification)

```
$ wc -c spec/22-git-logs-v2/01-glossary-and-enums.md
14346  (= 14 KB, well under any reasonable single-file cap)

$ wc -l spec/22-git-logs-v2/01-glossary-and-enums.md
313

$ tail -20 spec/22-git-logs-v2/01-glossary-and-enums.md
[shows complete `## TypeScript Mirror` block including all enum exports
 and the closing `### Drift-detection contract` table]
```

The file is **complete on disk**. The auditor's "truncated at 136 KB" verb refers to **bundle position**, not file position — the multi-file walker exhausted its 140 KB byte budget on tier-2 sibling files BEFORE appending `01-*.md`. This is a walker-mechanics finding, not a content gap.

Cache snapshot also confirms:
- `files_used: 5 / files_total: 38`
- `bytes_used: 140000` (exactly the walker cap)

## Cross-finding diagnosis

The same cache reports two related findings — already covered by **AC-78**:
- D5 HIGH `Missing Core Normative Files (04, 18, 34)` — all 3 present (406, 465, 311 lines respectively).
- D3 LOW `Externalized Concurrency Strategy` — correctly cross-referenced to spec/13 AC-22 per Lesson #36 (link-don't-restate).

## Resolution

**Extended AC-78** (no new AC, no AC count change, no AC-31-31 cascade) Given/Then to explicitly catalog the D4 truncation finding alongside the existing D5/D4/D3 walker-cap artifact catalog. Mirror of Lesson #39 codification pattern (precedent: spec/27 AC-T-34, Phase 153 Task S27-01).

## Spec lockstep

| File | Before | After | Reason |
|------|--------|-------|--------|
| §97 acceptance-criteria | v3.10.0 | **v3.10.1** | Verifies-clause widening of AC-78 (no new AC) |
| §00 overview | v3.13.0 | **v3.13.1** | Banner-only (lockstep) |
| §98 changelog | v3.13.0 | **v3.13.1** | Banner + new row |
| §99 consistency | v3.13.0 | **v3.13.1** | Banner-only (lockstep) |

**No CI workflow change · no RUBRIC bump · no gate-count change · no DDL change · no schema bump · no new AC.**

## Gate verification

- Lockstep: 87/87 ✅
- Tree-health: 168/168 strict ✅
- Version-parity: 74/74 ✅
- Freshness: 81 stamped + 6 exempt ✅

## Lesson reinforcement

**Lesson #39 (verify-on-disk before action) MUST be applied to ALL `[D4] Truncated *` findings on modules where `files_used < files_total` AND `bytes_used == 140000` (walker cap).** The auditor's truncation finding is a true-positive at the **bundle layer** but a false-positive at the **content layer**; the spec contract MUST declare the disambiguation in §97 (AC-78 pattern) so future re-scores produce noise-resistant output.

The proper fix for the walker-cap class as a whole is **R2 — walker re-tiering** (out of scope for self-lift; tracked as backlog item). Until R2 ships, AC-78-style harness-pin ACs are the canonical local fix.
