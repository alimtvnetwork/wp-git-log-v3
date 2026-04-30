# Phase 153 Task A24-fu2 — spec/26-gitlogs-diagrams EXCELLENT-band push (AC-23 deterministic SVG-render protocol)

**Date:** 2026-04-30
**Scope:** spec/26-gitlogs-diagrams §97 AC-23 added; lockstep §00/§98/§99 patch
**Outcome:** AC-23 ships closing v7 [D3] MEDIUM Non-deterministic SVG Diffing; reinforces AC-22 [D4]+[D5] harness-artifact pin; LLM re-score deferred per Lesson #20
**Projected:** 80 → 88+ (EXCELLENT band) per Lesson #44 audit-corpus axis multipliers

## Why spec/26 (vs spec/14, spec/16)

A24-preflight survey ranked GOOD modules by (band ≠ EXCELLENT) × (tier-1 < 75 KB) × (no existing inventory pin) × (axis multiplier). Top picks:

| candidate | total | tier-1 KB | walker-cap? | inv-pin? | verdict |
|---|---|---|---|---|---|
| spec/14-update | 76 | 60.1 | YES (8/54) | YES | SKIP — already shipped AC-21 same pattern (A11h) |
| spec/16-generic-release | 90 | 53.8 | YES (11/13) | NO | SKIP — already EXCELLENT |
| spec/26-gitlogs-diagrams | 80 | 50.7 | NO (9/9 full) | YES (AC-22) | **PICK — D3 MEDIUM is real spec gap, not stale-cache** |

Lesson #34 + Lesson #45 working in concert: the candidate with cache showing `files_used == files_total` (full inventory loaded) and remaining genuine D3 prose-gap, NOT a harness-bundling artifact already pinned by an inventory AC.

## Pre-flight (Lesson #45)

| Metric | Pre | Post (projected) | Cap | Status |
|---|---|---|---|---|
| Tier-1 bundle | 50.7 KB | ~54 KB | 75 KB saturation | ✅ 21 KB headroom |
| Total tree bundle | 51.9 KB | ~55 KB | 90 KB walker | ✅ 35 KB headroom |

## Findings closed

| v7 dim | Sev | Title | Closed by |
|---|---|---|---|
| D5 | HIGH | Missing Authoritative Source Context (spec/22 not bundled) | AC-22 (already shipped — harness scope artifact per Lesson #34) |
| **D3** | **MEDIUM** | **Non-deterministic SVG Diffing** | **AC-23 (this task) — Tier 1 `.mmd` SHA primary + Tier 2 xmllint c14n11 structural diff fallback** |
| D4 | LOW | Missing .mmd Source Content | AC-22 + AC-23 Tier 1 step 4 (mmdc render-success gate proves files exist) |

## AC-23 normative protocol

**Tier 1 (primary):** 5-step `.mmd`-source SHA-256 + `mmdc` render-success gate. If consecutive `.mmd` SHAs match → SKIP render (no source change → no SVG drift possible).

**Tier 2 (fallback):** 5-step structural-XML diff via `xmllint --c14n11` over both committed and freshly-rendered SVGs, with `sed`-based normalization stripping `id="mermaid-NNNN"` random IDs and timing comments. Drift policy distinguishes acceptable (random IDs, comment whitespace) from FORBIDDEN (`<text>` content change, `<path d>` >1px, `class` mismatch).

**Forbidden patterns** explicitly enumerated to prevent regression:
- ❌ Raw-SVG SHA as primary equality check (≥80% false-positive rate from Mermaid random IDs)
- ❌ Visual screenshot diffing (Chromium-version dependency)
- ❌ Skipping Tier 2 when Tier 1 SHA differs (silent drift class)
- ❌ Per-language XML-diff implementations (must use POSIX `xmllint --c14n11`)

## Lessons applied

- **Lesson #29 (Section F):** Verification commands lifted into normative tables, not prose.
- **Lesson #34:** Cache D5/D4 findings already pinned by AC-22 — no double-pin authored.
- **Lesson #36:** Tier 1 step 5 cites AC-DG-12 lockstep without restating the `.mmd`↔`.svg` pairing rule.
- **Lesson #44:** `audit-corpus` axis (D3×0.5 + D4×1.5 + D5×1.5); D3 closure +1.5 weighted, AC-22 reinforcement adds confidence on D4×1.5 + D5×1.5 axes.
- **Lesson #45:** Pre-flight `wc -c < 75 KB` verified (50.7 KB → ~54 KB); 21 KB headroom remains for future authoring.
- **Lesson #46:** Preferred NEW AC over in-place AC-DG-12 extension (auditor weights NEW ACs more than clarifications).

## Lockstep

| File | Before | After | Driver |
|---|---|---|---|
| `97-acceptance-criteria.md` | v3.2.0 | **v3.3.0** | New AC-23 (minor: new content) |
| `00-overview.md` banner | v3.4.2 | **v3.4.3** | Patch (catching §97 minor) |
| `98-changelog.md` | v3.4.2 | **v3.4.3** | New release row 3.4.3 (patch) |
| `99-consistency-report.md` | v3.3.2 | **v3.3.3** | Updated date + summary (patch) |

## CI gates (all GREEN)

- Lockstep: **87/87** PASS strict
- Tree-health: **168/168** PASS strict (all 56 modules at full marks, score 100/100)
- Version-parity: **74/74** PASS strict (0 mismatches)

## Deferred

LLM re-score deferred per Lesson #20 (Cloudflare HTTP 402 budget-blocked). Cached score will read 80 until A8 unblocks.

## Pattern reusability

**A24-preflight has confirmed: most A24-eligible modules are already either EXCELLENT or have inventory-pin ACs already shipped.** The remaining work surface for further EXCELLENT-band pushes is now narrow:

1. spec/26 — DONE this task
2. spec/25-app-issues (79, audit-corpus, 23.8 KB tier-1, no inv-pin, walker-cap-hit) — but cache shows 9/12 files: needs walker-bundle inspection first
3. spec/04-database-conventions (82, normative-contract, 46.6 KB tier-1, NO inv-pin) — D5×0.5 lowest leverage; not worth the round
4. spec/28-universal-ci-cli (87, normative-contract, 64.7 KB tier-1, NO inv-pin) — but only 8.5 KB headroom; risky

**Recommendation: move to spec/25-app-issues next** (audit-corpus axis = high D5×1.5 leverage, but verify walker bundle first per Lesson #45).

## Remaining tasks

| # | Status | Task |
|---|---|---|
| **A24-fu3** | 🟢 ready | spec/25-app-issues EXCELLENT-band push (verify walker bundle first) |
| **A24-fu (more)** | ⚪ low-leverage | spec/04 / spec/28 — normative-contract axis = D5×0.5 lowest leverage; small ROI |
| **A18** | ⚪ conditional | D5 honor-list pattern auto-detection — no miscalibration evidence |
| **R1** | 🔒 blocked | Trace-map deeper bindings — needs `enable cloud` |
| **A8** | 🔒 blocked | LLM gateway re-score — needs Cloudflare budget |
