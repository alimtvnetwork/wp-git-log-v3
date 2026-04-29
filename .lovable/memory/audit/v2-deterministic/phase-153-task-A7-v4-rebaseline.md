# Phase 153 Task A7 — v4 tree-wide rebaseline (tier-1 walker)

**Date:** 2026-04-29  
**Closed by:** A6 walker fix → re-ran on full tree.

## Result

- **Overall: 81.6 → 82.3 / 100 (GOOD)** (+0.7)
- 23 modules scored. Severity tally: CRITICAL 4 · HIGH 26 · MEDIUM 22 · LOW 17 (was: 5 · 23 · 26 · 15).
- Bands: **EXCELLENT 2** (spec/24=93, spec/15=90 — was 4); **GOOD 21** (was 18); **NEEDS_WORK 0** (was 1); **BLOCKING 0**.
- Dimension averages: D1 17.8→**17.9**, D2 15.9→**17.0** (+1.1), D3 15.1→**15.2**, D4 17.0→**16.7**, D5 15.7→**15.4**.

## Key signal: D2 AC Coverage +1.1 tree-wide

The single largest dimensional shift is D2 (AC coverage), exactly as predicted in A6 — the tier-1 walker now ensures every §97 makes it into the LLM context window before alphabetical example/feature siblings exhaust the 90 KB cap. This is direct evidence the walker fix is doing its job.

## Honest-baseline corrections

Two modules lost EXCELLENT band:
- **spec/02-coding-guidelines: 90 → 80** (−10) — bundle now includes §97 over chunky `01-cross-language/16-static-analysis/*` siblings; previously masked D3 gaps in the cross-language matrix surface.
- **spec/28-universal-ci-cli: 90 → 86** (−4) — bundle composition shifted; previously masked D5 cross-ref gaps surface.

These are NOT regressions — they're the **honest scores** the auditor would have produced all along had the contract surface been bundled. A6 corrected a measurement bias; what looked like EXCELLENT was partial-credit.

## Top movers (positive)

- spec/05-split-db-architecture **69 → 89** (+20, A6's targeted lift; held under the new walker)
- spec/06-seedable-config-architecture 78 → 86 (+8)
- spec/07-design-system 75 → 84 (+9)
- spec/14-update 80 → 86 (+6)
- spec/22-git-logs-v2 83 → 89 (+6)
- spec/16-generic-release 80 → 89 (+9)

## Top movers (negative — i.e. honest corrections)

- spec/02-coding-guidelines 90 → 80 (−10, see above)
- spec/13-generic-cli 82 → 75 (−7)
- spec/27-spec-toolchain 85 → 75 (−10) — bundle now sees §97/§98 over chunky validator sub-specs; surfaces D5 closure gaps that were previously truncated out.

## Artifacts

- `/mnt/documents/spec-ai-implementability-audit-v4.md` (full report)
- `.lovable/cache/audit-ai/*.json` (23 entries, all refreshed)

## Lesson #18 (codify)

When fixing a measurement bias in an LLM-driven audit, expect **band-level corrections in both directions** in the next baseline. Mark the v4 baseline as the new ground truth — do not "restore" the v3 EXCELLENT modules by rolling back the walker fix. Future score-lift work should target the (now visible) D3/D5 gaps in spec/02, spec/13, spec/27.

## Next

- A8 (deferred): graduation. Wait for 3 consecutive baselines to hold ≥ GOOD before flipping `--report-only` → `--strict`.
- A6-followup-2: now that spec/02, spec/13, spec/27 surface honest gaps (each was a hidden EXCELLENT or near-EXCELLENT pre-A6), they're the next-highest-leverage targets for content-driven lifts (mirror the spec/05 A6 playbook).


---

**Lessons codified:** #18 → see [`mem://process/phase-153-lessons`](../../../memory/process/phase-153-lessons.md) for the canonical contributor-rule statements.
