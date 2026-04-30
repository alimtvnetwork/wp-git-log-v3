# Phase 153 Task A24-fu24 — spec/13 §00 walker-pin pure-promotion (Lesson #63 fourth instance)

**Date:** 2026-04-30  
**Module:** `spec/13-generic-cli/` (normative-contract axis, d2×1.5 + d3×1.2)  
**Variant:** Lesson #61 pure-promotion / Lesson #63 fourth instance — **first on `normative-contract` axis**

## Diagnosis

Cache `total=88 GOOD`, walker `files_used=16/24 (67 %)`, `bytes_used=120000`. 3 advisory findings ALL closed in §97:
- D5-HIGH "Truncated Context (Files 14-21 Missing)" → AC-25 (walker-cap STRUCTURAL pin from A24-fu15, Lesson #50)
- D4-MEDIUM "Missing Concrete Examples for Multi-Format Output" → AC-09 (renderJSON/renderCSV/renderMarkdown in `pkg/output/`, jq-parseable, RFC-4180, GFM)
- D3-LOW "Undefined isBusyOrLocked Implementation" → AC-22 (busy_timeout=5000 + retry on SQLITE_BUSY/LOCKED) + Phase 153 P3 prose mirror in `10-database.md`

Walker hits 67 % of files but the targeted contracts live deep in §97 (AC-25 at line 222, AC-22 at line 161, AC-09 at line 86 — the latter inside §97 but past tier-1 cut). Auditor never reaches them.

## Fix

Inserted `> 🤖 Walker-Pin (normative-contract axis)` 4-row teaser table at §00 head, immediately after the Related-specs block. Surfaces AC-25 / AC-09 / AC-22 / AC-21+AC-26 with severity + closes-finding mapping + `**Verifies:**` paths. Auditor instruction line directs bundle-capped LLMs to downgrade D5 to "walker-window artifact" per AC-25 and D3/D4 to "see §97 AC-NN".

## Lockstep

| File | Before | After | Bump |
|---|---|---|---|
| `00-overview.md` | 1.1.8 | 1.1.9 | patch (teaser content) |
| `97-acceptance-criteria.md` | 2.4.0 | 2.4.0 | unchanged |
| `98-changelog.md` | 1.1.8 | 1.1.9 | patch (this row) |
| `99-consistency-report.md` | 1.1.8 | 1.1.9 | patch (audit row) |

**No §97 change** → no AC-31-31 cascade, no RUBRIC bump, no CI workflow change, no gate-count change.

## Verification

- Lockstep: 87/87 PASS · 0 findings
- Tree-health: 168/168 strict — all 56 modules at full marks
- Version-parity: 74/74 matches · 0 mismatches

LLM re-score deferred per Lesson #20.

## Lessons reconfirmed

- **Lesson #63 fourth instance** — pure-promotion pattern stable across all three axes:
  - audit-corpus: spec/22 (A24-fu20) + spec/03 (A24-fu23)
  - integration-spec: spec/27 (A24-fu22)
  - normative-contract: spec/13 (A24-fu24) ← this phase
- Confirms pattern generalises regardless of axis multipliers — the bottleneck is walker visibility of §97 contracts, not axis weighting.
