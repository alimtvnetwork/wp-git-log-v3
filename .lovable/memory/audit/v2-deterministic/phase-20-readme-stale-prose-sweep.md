# Phase 20 — README stale-prose sweep (linter-scripts/test/)

**Date**: 2026-04-28
**Mode**: No-Questions Mode (task 4/40)

## Context

Per Phase 19 close-out recommendation (b): audit `linter-scripts/test/README.md`
for the F3 codification. F3 codification (Adjacent `.py` tests subsection)
was confirmed correctly landed — but the audit surfaced 5 stale-prose
narrative bugs from incremental growth (7 → 8 → 10 self-tests) that no
gate enforces.

Same Phase 136/139 stale-prose class: parity gate
(`test-readme-inventory.sh`) verifies the **inventory table**
filesystem-parity but is silent about narrative count claims elsewhere
in the README.

## Drift fixed

| Loc | Before | After |
|---|---|---|
| L55 | "All eight scripts are wired … as discrete steps" + 8 named steps | "All ten scripts are reachable … Seven run as discrete self-test steps … remaining three folded into broader-contract gates" — explicitly cites H1 workflow-step parity lesson, names H1/H5/H7 production gates as the homes for tests 8/9/10 |
| L66 | "The seven tests together form a complete blind-spot coverage matrix … gates 1–3 plus meta-suite gates 4–7" | "The ten tests together form … gates 1–3, meta-suite gates 4–7, and §99 lifecycle / archive-exclusion contracts (gates 8–10)" |
| L78–80 | Coverage-triad table missing rows for H1/H4/H7 | Added 3 rows describing each blind spot + why production gate misses it |
| L79+L80 | "If you add an eighth contract guarantee … add an eighth self-test" | "If you add an eleventh … add an eleventh" |
| L93 | "The seven scripts above are shell tests" | "The ten scripts above" |
| L142–144 | "These are CI-runnable … but not yet wired into spec-health.yml (that's Phase F2, blocked on Phase F1 user verdicts)" — F1+F2 are CLOSED per Core memory | "These are CI-runnable … not yet wired as discrete steps in spec-health.yml. Acknowledgement flows through test-overview-inventory-parity.sh (Phase 112) instead of the README parity gate, which remains `.sh`-only by design" |
| L168–178 | "Local execution" code block + "Run all seven sequentially" — missing 3 entries | Added 3 missing `bash …` lines + "Run all ten sequentially" |

## Verification

- `test-readme-inventory.sh`: **26 passed, 0 failed** (parity gate still intact)
- `test-overview-inventory-parity.sh`: **6 passed, 0 failed** (no §27 drift introduced)

## Why no AC-31-31 cascade / new gate

This is pure narrative-prose lockstep — no new gate added, no scoring
weight changed, no acceptance criteria added. The structural inventory
(table at L36–L51) was already correct and gated; only narrative
restatements drifted. Same lockstep class as Phase 139's §99 Summary
sweep.

## Lesson candidate

Stale-prose drift in README narrative ("The N tests together…", "Run all
N sequentially", "If you add an Nth …", named-step enumerations) is a
recurring class **not covered by inventory parity gates**. Possible
future H10 candidate: extend `test-readme-inventory.sh` to assert the
filesystem count appears verbatim in the prose narrative. Not implemented
this turn (1/3 H10: detector-friendly but only one historical incident,
low active surface).
