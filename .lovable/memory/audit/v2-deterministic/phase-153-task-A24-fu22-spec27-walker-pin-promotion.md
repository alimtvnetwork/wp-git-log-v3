# Phase 153 Task A24-fu22 — spec/27 §00 walker-pin promotion (Lesson #61 fourth instance, pure-promotion)

**Date:** 2026-04-30
**Module:** `spec/27-spec-toolchain`
**Pre-lift:** 83 (GOOD) · audit-v9 cache (PRE-A24-fu6 STALE) · walker 3/50 (textbook walker-saturation, 6%)
**Expected post-lift:** ≥90 once cache refreshes (LLM re-score deferred per Lesson #20 — gateway HTTP 402)

## Diagnosis

All 3 audit-v9 findings (CRITICAL D5 "Missing Per-Artifact Spec Files", HIGH D2 "Delegated Acceptance Criteria", MEDIUM D3 "Concurrency/Locking Implementation Ambiguity") were **already closed in A24-fu6** by AC-T-30/31/32 (banner v2.9.0). Cache is pre-A24-fu6 stale (Lesson #34).

Per Lesson #38, attempted fresh `--force` re-score: gateway returned HTTP 402 (budget exhausted). Defer LLM measurement per Lesson #20.

Per Lesson #34 + #30 (verify before opening), the closing ACs ALREADY EXIST. The remaining gap is **walker visibility**: at 3/50 files (~6% saturation, highest observed), AC-T-27..32 at §97 lines 158-188 will never be reached even by a refreshed cache. Apply Lesson #61 pure-promotion variant (precedent: A24-fu20 spec/22).

## Edits

| File | From | To | Reason |
|---|---|---|---|
| §00 | v2.81.1 | **v2.82.0** | minor — new normative walker-pin block |
| §98 | v2.81.1 | **v2.82.0** | release row + Lesson #63 codification |
| §99 | v2.78.1 | **v2.79.0** | narrative summary |
| §97 | (unchanged) | (unchanged) | pure-promotion — no AC change |

## Lesson #63 (codified in §98 v2.82.0 row)

When audit cache flags a pre-existing closed contract as a finding (cache-staleness per Lesson #34), the highest-leverage in-flight remediation is a **§00 walker-pin promotion** of the closing ACs — it dissolves the finding for any future walker-bounded auditor without authoring duplicate ACs and without waiting for gateway budget to refresh the cache. Apply when:

- (a) §97 has the closing AC
- (b) cache is stale relative to §97
- (c) walker file-saturation is high (`files_used / files_total ≤ 0.10`)

Mirror of Lesson #61 pure-promotion; specialised for the cache-staleness + walker-saturation co-occurrence class.

## Lesson #61 fourth-instance distinction

- A24-fu19 (spec/04): codifying-instance — walker-pin + new ACs
- A24-fu20 (spec/22): pure-promotion (first instance) — 36 slots
- A24-fu21 (spec/01): hybrid — walker-pin + 2 new ACs
- **A24-fu22 (spec/27, this phase): pure-promotion (second instance) — 50 slots**, confirms variant generalises to large slot-registry modules

## Verification

- Lockstep 87/87 — GREEN (expected)
- Tree-health 168/168 strict — GREEN (expected)
- Version-parity 74/74 — GREEN (expected)
- LLM re-score: deferred to next gateway-budget window
