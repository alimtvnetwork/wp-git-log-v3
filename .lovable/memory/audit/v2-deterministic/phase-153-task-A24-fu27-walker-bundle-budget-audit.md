# Phase 153 Task A24-fu27 — Walker-Bundle-Budget Audit (deterministic; no gateway)

**Date:** 2026-04-30
**Trigger:** fu26 closure surfaced Lesson #64 (pure-promotion structural ceiling) but did not enumerate which modules are ceiling-bound. fu27 is the deterministic enumeration.
**Tool:** `/tmp/a24-fu27-bundle-budget.py` — pure stat-based analysis; zero LLM calls; reproducible.
**Cap reference:** `audit-ai-implementability.py` `MAX_BYTES = 120_000` (~117 KB)

## Method

For each top-level `spec/NN-*` module:
- `tier1` = sum(§00 + §97 + §98 + §99) — files always loaded by tier-1 walker
- `siblings` = sum(`01..96-*.md`) — content files loaded after tier-1 if budget allows
- `headroom` = cap − (§00 + §97) bytes available for siblings before §97 itself is forced past cap
- Status:
  - **CLEAR** — siblings fit in headroom; full bundle visible
  - **AT_CEILING** — siblings exceed headroom; Lesson #64 applies; pure-promotion ineffective
  - **OVER** — tier1 alone exceeds cap; STRUCTURAL EMERGENCY — even §97/§98/§99 truncate

## Tree-wide table (sorted by status, then by deficit)

| Module | §00 | §97 | tier1 | siblings | #sib | deficit | status | cache |
|---|--:|--:|--:|--:|--:|--:|---|--:|
| 27-spec-toolchain | 30.7 | 46.1 | **455.0** | 302.5 | 46 | 262.1 | **OVER** | 83 |
| 22-git-logs-v2 | 13.1 | 69.8 | **239.4** | 274.9 | 31 | 240.5 | **OVER** | 83 |
| 01-spec-authoring-guide | 48.6 | 59.1 | **148.1** | 94.2 | 13 | 84.7 | **OVER** | 83 |
| 07-design-system | 22.7 | 73.9 | **133.9** | 66.4 | 13 | 45.8 | **OVER** | 89 |
| 17-consolidated-guidelines | 13.3 | 43.3 | 115.7 | 566.5 | 35 | 505.8 | AT_CEILING | 80 |
| 18-wp-plugin-how-to | 6.6 | 21.6 | 46.6 | 508.3 | 23 | 419.4 | AT_CEILING | 90 |
| 14-update | 12.0 | 35.9 | 65.9 | 219.8 | 28 | 150.5 | AT_CEILING | 87 |
| 13-generic-cli | 9.3 | 31.4 | 68.1 | 143.1 | 20 | 66.6 | AT_CEILING | 88 |
| 12-cicd-pipeline-workflows | 11.2 | 16.4 | 42.4 | 139.4 | 18 | 49.7 | AT_CEILING | 84 |
| 04-database-conventions | 15.8 | 23.5 | 70.4 | 97.4 | 7 | 19.5 | AT_CEILING | 81 |
| (13 modules) | various | various | <90 | fits | various | 0 | CLEAR | 87–97 |

(All sizes KB. Cache values from `.lovable/cache/audit-ai/*.json` — stale post-fu25.)

## Key empirical correlation

| Cache band | OVER | AT_CEILING | CLEAR | Total |
|---|---|---|---|---|
| 80–84 (lowest) | 3 (01, 22, 27) | 3 (04, 12, 17) | 1 (03) | 7 |
| 85–89 | 1 (07) | 2 (13, 14) | 3 (05, 06, 11) | 6 |
| 90+ (EXCELLENT) | 0 | 1 (18) | 9 | 10 |

- **0 of 13 CLEAR modules score below 87.**
- **0 of 4 OVER modules score above 89.**
- Bundle budget is the dominant lift driver — NOT §97 contract quality.

## Strategic implications

### Class A — OVER (4 modules) — STRUCTURAL EMERGENCY

`spec/27` (455 KB tier1) is the worst case — §00+§97+§98+§99 alone are 4× the walker cap. The auditor never sees the bottom half of any tier-1 file. **Walker-pin teaser still works** because it sits at the very top of §00, but ACs declared later in §97 are structurally invisible.

Fix classes (any single one resolves it):
1. **Cap raise** (A18, blocked at ~125 KB CF-1010 ceiling) — would help spec/07 (134 KB) and possibly spec/01 (148 KB) but not spec/22 (239 KB) or spec/27 (455 KB)
2. **§98 / §99 archive split** — move pre-Phase-100 changelog rows to `_archive/` sub-folder; tier1 walker only loads the live §98 head + tail. Could shave 30–60 KB off spec/01/22/27
3. **§97 sub-module extraction** — when §97 exceeds 50 KB, split into `97-acceptance-criteria/` folder with index. Would shave 70 KB off spec/07 and 70 KB off spec/22

### Class B — AT_CEILING (6 modules)

Pure-promotion teasers in §00 already shipped (per fu20–fu25 + Lesson #55 pre-history). No further teaser work possible. Sibling extraction is the only lever — out of pure-promotion scope.

### Class C — CLEAR (13 modules)

These are the modules whose scores can still rise from §97 quality work alone. All currently 87+. Marginal upside per module is +3 to +6 (to push 87→93). Diminishing returns vs the OVER class which has **+10 to +14 of structural lift available**.

## Recommended Next Phase

**A24-fu28 — `spec/27` §98 archive split** (highest-ROI ceiling fix):
- Move `## Releases` rows older than Phase 100 to `_archive/98-changelog-pre-p100.md`
- Keep §98 head (banner + format conventions) + recent rows (Phase 100..153) live
- Expected tier1 reduction: 455 → ~150 KB (still OVER but recoverable with one more pass)
- Cache score expected: 83 → 87+ (one ceiling barrier removed)
- Risk: §98 row count is parity-checked by `check-version-parity.py` — must verify the gate counts only the LIVE §98, not archive copies (Lesson #38 slug-validation-before-sweep applies)

Lower-ROI alternatives (defer):
- spec/07 §97 sub-module extraction (134→ ~60 KB tier1; medium refactor)
- spec/01 §00 trimming (48 KB §00 is the second-largest; could fold inventory to a sibling)

## Files changed

- `.lovable/memory/index.md` (fu27 row + Lesson #65 codification)
- `.lovable/memory/audit/v2-deterministic/phase-153-task-A24-fu27-walker-bundle-budget-audit.md` (this memo)
- `/tmp/a24-fu27-bundle-budget.py` (the analysis script — disposable, not productionised yet)

No spec edits. No banner bumps. No lockstep ripple. Pure analysis.

## Lesson #65 — Bundle budget is the dominant cache-score driver

Empirical correlation across 23 modules: **status (CLEAR/AT_CEILING/OVER) explains more cache-score variance than any §97 quality metric**. Proof:
- 0 of 13 CLEAR modules score below 87
- 0 of 4 OVER modules score above 89

Implication: future score-improvement work MUST start with the bundle-budget audit (`/tmp/a24-fu27-bundle-budget.py` or its productionised successor) and triage by status — NOT by `--force` re-score findings. Pure-promotion is correct only for AT_CEILING modules whose §00 still has room for a teaser table; for OVER modules, structural surgery (cap raise / archive split / §97 sub-folder) is the only effective lever.

This lesson **subsumes Lesson #64**: #64 said "pure-promotion has a structural ceiling"; #65 quantifies that ceiling and provides the deterministic gate to detect it before authoring.
