# Phase 153 Task A24-fu — spec/10 EXCELLENT-band push (AC-10 v7-finding tri-closure)

**Date:** 2026-04-30  
**Scope:** spec/10-research §97 AC-10 added; lockstep §00/§98/§99 patch-bumped  
**Outcome:** AC-10 ships closing all three v7 audit findings; LLM re-score deferred per Lesson #20 (HTTP 402)  
**Projected:** 87 → 92+ (EXCELLENT band) per Lesson #44 audit-corpus axis multipliers

## Pre-flight (Lesson #45 graduated rule)

| Metric | Value | Cap | Status |
|---|---|---|---|
| Tier-1 bundle (`{00,97,98,99}-*.md`) | ~12 KB → ~15 KB | 75 KB saturation | ✅ massive headroom |
| Total tree bundle | ~36 KB → ~39 KB | 90 KB walker | ✅ massive headroom |

spec/10 was the highest-leverage candidate identified by A24-preflight: `audit-corpus` axis (D4×1.5 + D5×1.5 multipliers) with 63.3 KB headroom and 3 concrete cached findings spanning all three high-value dimensions.

## Findings closed

| # | v7 dim | Severity | Title | Closed by |
|---|---|---|---|---|
| 1 | D1 | LOW | Registry Table Type Mismatch | AC-10 §(a) — CHECK constraint on `AuthoredAt` (ISO-8601) + `Domain` GLOB pin |
| 2 | D3 | MEDIUM | Ambiguous 'On-Disk' Resolution Logic | AC-10 §(b) — 5-row normative table (base path / case sensitivity / symlinks / domain pattern / resolution order) |
| 3 | D5 | HIGH | Unresolved External Script Dependencies | AC-10 §(c) — 4-row script-binding table delegating each linter contract to its owning spec/27 §97 AC family |

Per **Lesson #44**, on `audit-corpus` axis the D5 closure alone yields ~+4 weighted points (16 → 20 base = +4 raw × 1.5 multiplier). D3 closure adds ~+3 weighted (17 → 20 = +3 × 1.5 — but D3 multiplier is 0.5 on this axis, so +1.5 weighted). D1 closure adds ~+2 raw × 1.0 = +2. Cumulative projection: 87 → 92+ (EXCELLENT band).

## Lesson #36 application (link-don't-restate)

The script-binding table in §(c) explicitly delegates to spec/27's §97 AC families:

- `check-spec-folder-refs.py` → AC-62-01..04
- `check-tree-health.cjs` → AC-T-01..09
- `check-spec-cross-links.py` → AC-CL-01..05
- `check-lockstep.cjs` → AC-LS-01..06

No script source code is inlined in spec/10; cross-references are link-only. This avoids the dual-source drift class that Lesson #36 codifies.

## Lockstep

| File | Before | After | Driver |
|---|---|---|---|
| `97-acceptance-criteria.md` | v1.2.0 | **v1.3.0** | New AC-10 (minor: new content) |
| `00-overview.md` banner | v3.3.4 | **v3.3.5** | Patch (catching §97 minor) |
| `98-changelog.md` | v3.3.4 | **v3.3.5** | New release row 3.3.5 (patch) |
| `99-consistency-report.md` | v1.3.2 | **v1.3.3** | New v1.3.3 row + Updated date (patch) |

## CI gates (all GREEN)

- Lockstep: **87/87** PASS strict
- Tree-health: **168/168** PASS strict (all 56 modules at full marks, score 100/100)
- Version-parity: **74/74** PASS strict (0 mismatches)

## Deferred

**LLM re-score:** the Cloudflare gateway is currently 402-budget-blocked (Lesson #20). The cached score will continue to read 87 until A8 unblocks. The projected 92+ is based on Lesson #44's axis-multiplier arithmetic and the precedent from spec/03 A21 (+7 with 2 findings closed on `audit-corpus` axis).

## Pattern reusability

This is the **canonical EXCELLENT-band push pattern** for `audit-corpus` modules with low tier-1 bundle size:

1. Pre-flight `wc -c` per Lesson #45 (sum < 75 KB tier-1; total < 90 KB)
2. Read v3/v4/v7 cache findings → enumerate per-dimension
3. Author single fat AC closing all findings with sub-sections per dimension
4. Apply Lesson #36 (link-don't-restate) for cross-module references
5. Lockstep §00/§98/§99 patch-bumps; §97 minor-bumps on new AC

Future A24-fu candidates (per A24-preflight): spec/14-update (75 KB headroom, 2 findings), spec/16-generic-release (~70 KB headroom).

## Remaining tasks

| # | Status | Task |
|---|---|---|
| **A24-fu (more)** | 🟢 ready | Apply this pattern to spec/14, spec/16, or other A24-preflight unsaturated GOOD candidates |
| **A18** | ⚪ conditional | D5 honor-list pattern auto-detection — no miscalibration evidence |
| **R1** | 🔒 blocked | Trace-map deeper bindings — needs `enable cloud` |
| **A8** | 🔒 blocked | LLM gateway re-score — needs Cloudflare budget |
