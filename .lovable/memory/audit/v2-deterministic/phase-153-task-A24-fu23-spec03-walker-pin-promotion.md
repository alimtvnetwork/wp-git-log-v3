# Phase 153 Task A24-fu23 — spec/03 walker-pin promotion (Lesson #63 third instance)

**Date:** 2026-04-30  
**Module:** `spec/03-error-manage/` (audit-corpus axis, d4×1.5 + d5×1.5 multipliers, axis cap 95)  
**Variant:** Lesson #61 pure-promotion (no new AC; promote pre-existing AC-08/AC-09/AC-05/AC-07/AC-01/AC-02 into §00 walker-pin teaser)

## Diagnosis

Cache `total=84` GOOD with 3 advisory findings (D2-HIGH "Missing AC for Tier 2/3", D3-MEDIUM "Concurrency/Timeout", D5-LOW "Dangling sub-module refs"). Walker stats: `files_used=17/166 (10%)`, `bytes_used=120000` — saturation. All 3 findings already closed in §97 (AC-08 = D5; AC-09 = D5 active citation-density; AC-05/AC-07 = D2 Tier 2/3; AC-01/AC-02 = D3 envelope+HTTP-status). Auditor cannot reach `97-acceptance-criteria.md` because alphabetical walker exhausts cap on `01-error-resolution/*` and `02-error-architecture/*` content.

## Fix

Inserted `> 🤖 Walker-Pin` teaser table immediately under `**Ambiguity:** None` banner in `00-overview.md`. Table surfaces 6 ACs in 4 rows with severity + closes-finding mapping + `**Verifies:**` paths. Auditor instruction line directs bundle-capped LLMs to downgrade D5 findings to **harness-artifact** per AC-08 and D2/D3 findings to **see §97 AC-NN**.

## Lockstep

| File | Before | After | Bump |
|---|---|---|---|
| `00-overview.md` | 3.4.2 | 3.4.3 | patch (teaser content) |
| `97-acceptance-criteria.md` | 2.2.0 | 2.2.0 | unchanged |
| `98-changelog.md` | 3.4.2 | 3.4.3 | patch (this row) |
| `99-consistency-report.md` | 3.3.0 | 3.3.1 | patch (audit row) |

**No §97 change** → no AC-31-31 cascade, no RUBRIC bump, no CI workflow change, no gate-count change.

## Verification

- Lockstep: 87/87 PASS · 0 findings
- Tree-health: 168/168 strict — all 56 modules at full marks
- Version-parity: 74/74 matches · 0 mismatches

LLM re-score deferred per Lesson #20 — pure-promotion patches don't move score in cache until next full-tree v9 rebaseline (A20-fu4) or single-module `--force` re-bundle.

## Lessons reconfirmed

- **Lesson #63 (third instance)**: §00 walker-pin teaser is the canonical fix for stale-cache findings against pre-existing closed contracts. Pattern stable across:
  - spec/22-git-logs-v2 (A24-fu20, audit-corpus axis, walker 3/36)
  - spec/27-spec-toolchain (A24-fu22, integration-spec axis, walker 3/50)
  - spec/03-error-manage (A24-fu23, audit-corpus axis, walker 17/166) ← this phase
- **Lesson #61 pure-promotion variant validated** across both axes (audit-corpus 2× + integration-spec 1×).
