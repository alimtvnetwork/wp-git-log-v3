# Phase 153 Task A24-fu26 — spec/04 walker-pin verify-before-open (NO-OP)

**Date:** 2026-04-30
**Module:** `spec/04-database-conventions/`
**Pre-state cache:** 81 GOOD (second-lowest in tree)
**Outcome:** **NO-OP** — verify-before-open per Lesson #30; teaser already shipped pre-session

## Plan (entering phase)

Apply Lesson #63 seventh instance: insert compact AC-index walker-pin teaser
into spec/04 §00 to lift cache floor 81 → ~85.

## Pre-flight inspection

`code--view spec/04-database-conventions/00-overview.md lines 1-30` revealed an
**already-present 4-row walker-pin teaser** at lines 17-25:

| AC | Severity | Subject | Canonical surface |
|----|----------|---------|-------------------|
| AC-13 | medium | SQLite single-writer (cross-link, not restate) | spec/13 §97 AC-22 |
| AC-14 | high | Golden Rules 1–4 (singular, PascalCase, PK, FK) | This file + §97 AC-14 |
| AC-15 | medium | Smallest-type rule for bounded lookups (SMALLINT) | This file + §97 AC-15 |
| AC-16 | low | View prefix `Vw` (never `View` suffix) | §01 + Canonical DDL + §97 AC-16 |

Plus a "Forbidden remediation patterns" footnote pointing to §97 AC-13.

The teaser is attributed to Lesson #55 (the original narrative-walker-pin
codification) and pre-dates this session. Likely shipped in fu18 alongside
spec/17's first walker-pin (both were Lesson #55 candidates).

## Why the cache score still reads 81

Walker reports `files_used: 9 / 11` (82 % visible) — high coverage by file
count but combined sibling-content `00..07-*.md` totals **115 KB** out of the
**120 KB cap**, leaving only ~5 KB of headroom for `97-acceptance-criteria.md`
(24 KB). The auditor sees the §00 teaser but truncates §97 mid-AC, losing
the GWT bodies that anchor AC-13/14/15/16.

Pure-promotion has no remaining lever here — the teaser IS the §00 surface
the pattern can occupy. Adding more rows or a second teaser would not lift
the score; it would only consume more of the 120 KB cap and risk pushing
§97 visibility lower.

## Lesson #64 — Pure-promotion has a structural ceiling

When module sibling-content (`01..NN-*.md`) totals exceed the walker cap minus
§97 size, no §00 teaser placement can rescue the cache score — the contract
genuinely cannot reach the auditor in a single bundle.

spec/04 is the canonical example:
- 7 sibling files × ~13 KB avg = **92 KB** consumed before §97 starts
- §00 itself adds **16 KB** → 108 KB total
- §97 (24 KB) cannot fit in remaining 12 KB

Genuine fix classes for these structural-ceiling modules:

| Class | Approach | Status |
|---|---|---|
| **(a) Cap raise** | Raise walker `MAX_BYTES` from 120 KB → 250 KB (A18) | 🔒 blocked at ~125 KB Cloudflare-1010 ceiling |
| **(b) Sibling extraction** | Move chunky sibling files into sub-module folders so they audit separately | High-cost refactor; out of pure-promotion scope |
| **(c) Contract migration** | Lift critical ACs from §97 into §00 directly (one-tier folding) | Structural redesign; violates §97-WINS contract |

None are "pure-promotion" work. Each requires its own multi-phase plan.

## Halt point — bottom-of-tree pure-promotion sweep complete

After fu26's no-op closure, every cache-floor module ≤ 84 has either received
a walker-pin teaser this session or already had one pre-shipped:

| Module | Cache | Walker-pin source |
|---|---|---|
| spec/17 | 80 | fu25 (this session) |
| spec/04 | 81 | fu18 (pre-session, Lesson #55) |
| spec/01 | 83 | fu21 (this session) |
| spec/22 | 83 | fu20 (this session) |
| spec/27 | 83 | fu22 (this session) |
| spec/03 | 84 | fu23 (this session) |
| spec/12 | 84 | fu4 (this session) |
| spec/13 | 88 | fu24 (this session) |

## Verifies

- **Lesson #30** (verify-before-open): inspection prevented duplicate teaser authoring
- **Lesson #63** (compact-AC-index pattern): confirmed axis-coverage at 5; spec/04 = 6th case where pattern already shipped
- **Lesson #64** (NEW): pure-promotion structural ceiling rule

## Files changed

- `.lovable/memory/index.md` (fu26 no-op row + Lesson #64 codification)
- `.lovable/memory/audit/v2-deterministic/phase-153-task-A24-fu26-spec04-noop-verify-before-open.md` (this memo)

No spec edits. No banner bumps. No lockstep ripple. Three strict gates remain GREEN from fu25 closure.
