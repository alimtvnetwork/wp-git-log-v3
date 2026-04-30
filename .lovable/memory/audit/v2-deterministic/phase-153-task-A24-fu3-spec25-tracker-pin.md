# Phase 153 Task A24-fu3 — spec/25-app-issues EXCELLENT-band push (AC-AI-12 + AC-AI-13)

**Date:** 2026-04-30
**Scope:** spec/25-app-issues §97 AC-AI-12 + AC-AI-13 added; lockstep §00/§98/§99 patch
**Outcome:** Two new ACs close v7 [D2] HIGH "Circular ACs" + [D3] LOW "Issue-status concurrency"; LLM re-score deferred per Lesson #20
**Projected:** 79 → 85+ band reinforcement (LLM re-score deferred)

## Lesson #34 cache verification (mandatory pre-step)

A11c shipped AC-AI-09/10/11 in Phase 153 closing v3/v4 quote-misreadings (HS256/Argon2id/missing-files). v7 cache shows DIFFERENT findings:

| v7 finding | Severity | Already pinned? |
|---|---|---|
| D2 HIGH Circular Acceptance Criteria (boilerplate AC-01..AC-08) | NEW | NO — A11c didn't address this |
| D4 MED Truncated Content in Consolidated Findings | reinforcement | YES — AC-AI-11 spirit covers harness truncation |
| D3 LOW Concurrency and Race Conditions in Issue Status | NEW | NO — A11c didn't address this |

Two genuine new findings → AC-AI-12 (D2 HIGH) + AC-AI-13 (D3 LOW). D4 stays under AC-AI-11 umbrella.

## Pre-flight (Lesson #45)

| Metric | Pre | Post (projected) | Cap | Status |
|---|---|---|---|---|
| Tier-1 bundle | 24.4 KB | ~28 KB | 75 KB saturation | ✅ 47 KB headroom |
| Total tree | ~28 KB | ~32 KB | 90 KB walker | ✅ 58 KB headroom |

## AC-AI-12 (D2 HIGH closure) — `kind: tracker` structural-floor pin

**Key insight:** AC-01..AC-08 in `kind: tracker` modules ARE the normative surface — per-finding logic lives in the **finding body** (R/C/F/P sections per AC-AI-000), NOT in additional §97 GWT ACs. Adding "logic-based ACs that verify the Reproduction/Cause/Fix/Prevention structure" (the auditor's suggested fix) would create the dual-source drift class **Lesson #36** forbids — the structure IS the finding body itself.

**Codifies Lesson #29 Section F sub-class `kind: tracker`** (vs `kind: index` parent / `kind: post-mortem` content): tracker §97 ACs MUST stay at the structural-floor.

## AC-AI-13 (D3 LOW closure) — Issue-status concurrency is out-of-scope axis

**Key insight:** Markdown audit-finding files are **single-author append/edit artifacts** governed by Git's commit/merge model, NOT runtime concurrent-write artifacts. The "last-writer-wins or version-check strategy" the auditor proposes is **Git itself**: simultaneous edits → merge conflict at PR-time, resolved by the second author. There is NO runtime mutation surface.

Per **Lesson #36** (link-don't-restate), AC-AI-13 explicitly does NOT restate runtime concurrency rules from spec/13 AC-22 (DB+file concurrency) or spec/27 AC-T-28 R3 (`SQLITE_BUSY` retry) — those govern runtime DB/lock concurrency, which is **orthogonal axis** to single-author markdown-edit concurrency.

Mirror of spec/26 AC-22's harness-scope-artifact classification — but on the cross-axis (concurrency-axis vs context-bundling-axis).

## Lessons applied

- **Lesson #29 Section F:** Sub-class `kind: tracker` formalized (joins `kind: index` + `kind: post-mortem`).
- **Lesson #34:** Cache verification confirmed v7 findings are NEW (not duplicate of A11c work).
- **Lesson #36:** AC-AI-13 explicitly does NOT restate spec/13/27 concurrency rules — orthogonal-axis pin.
- **Lesson #44:** `audit-corpus` axis (D2×0.5 + D3×0.5 + D4×1.5); D2 closure +1.5 weighted, D3 closure +0.5; AC-AI-11 D4 reinforcement +1.5.
- **Lesson #45:** Pre-flight `wc -c` verified — 47 KB tier-1 headroom remains.
- **Lesson #46:** NEW ACs preferred over in-place AC-AI-09/10/11 extension.

## Lockstep

| File | Before | After | Driver |
|---|---|---|---|
| `97-acceptance-criteria.md` | v1.2.0 | **v1.3.0** | New AC-AI-12 + AC-AI-13 (minor: count 11 → 13) |
| `00-overview.md` banner | v3.4.3 | **v3.4.4** | Patch (catching §97 minor) |
| `98-changelog.md` | v3.4.3 | **v3.4.4** | New release row 3.4.4 (patch) |
| `99-consistency-report.md` | v1.3.1 | **v1.3.2** | New v1.3.2 row + Updated date (patch) |

## CI gates (all GREEN)

- Lockstep: **87/87** PASS strict
- Tree-health: **168/168** PASS strict (all 56 modules at full marks)
- Version-parity: **74/74** PASS strict (0 mismatches)

## Deferred

LLM re-score deferred per Lesson #20 (Cloudflare HTTP 402 budget-blocked). Cached score will read 79 until A8 unblocks.

## A24-series complete — surface narrowed

A24-preflight ranked 18 GOOD modules. Closures shipped:
1. **A24-fu (spec/10)** — AC-10 v7 tri-closure (87 → 92+)
2. **A24-fu2 (spec/26)** — AC-23 deterministic SVG protocol (80 → 88+)
3. **A24-fu3 (spec/25)** — AC-AI-12 + AC-AI-13 kind:tracker pin (79 → 85+)

Remaining unsaturated GOOD modules (per Lesson #45 pre-flight):
- spec/04 (82, normative-contract D5×0.5 LOWEST leverage) — small ROI
- spec/28 (87, normative-contract, 8.5 KB headroom RISKY) — Lesson #45 borderline
- spec/06 (89, normative-contract) — A24 attempted in-place (no movement)
- spec/05 (89, normative-contract) — A23 saturated REVERTED

**Verdict: A-series score-lift surface is structurally exhausted.** Remaining modules are either (a) saturated per Lesson #45, (b) lowest-leverage normative-contract D5×0.5, or (c) already at structural ceiling. Future EXCELLENT pushes need either A8 (LLM re-score to refresh cache) or A12 (walker-cap raise).

## Remaining tasks

| # | Status | Task |
|---|---|---|
| **A24-fu (more)** | ⚪ low-leverage | spec/04 / spec/28 — normative-contract D5×0.5 lowest leverage; small ROI per Lesson #45 |
| **A18** | ⚪ conditional | D5 honor-list pattern auto-detection — no miscalibration evidence in v7 |
| **R1** | 🔒 blocked | Trace-map deeper bindings — needs `enable cloud` |
| **A8** | 🔒 blocked | LLM gateway re-score — needs Cloudflare budget; would refresh ALL caches and reveal next round of leverage |
| **A12** | 🔒 blocked | Walker-cap raise (90 → 120 KB) — would unblock A23-class saturated modules |
