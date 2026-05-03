---
phase: 153
task: A18-probe
date: 2026-05-03
status: CLOSED — walker raise rejected; rollback to 120 KB; new backlog item surfaced
gates: lockstep 87/87 · tree-health 168/168 strict · version-parity 74/74 — all GREEN
---

# A18-probe — Walker MAX_BYTES 120 → 140 KB probe on 3 floor-bound modules

## Hypothesis

Per A20-fu7 closing memo: tree at 90.6 / 100 mean, 0 findings, but 3 modules
floor-locked at 84/85 (spec/12, spec/01, spec/27). User asked for `next` against
empty backlog → only unblocked, untried lever was a small walker raise under the
CF-1010 ceiling (~125 KB *response* limit; *request* payload has ~40 KB headroom).

Plan: bump `MAX_BYTES` 120_000 → 140_000, `--force` re-score the 3 floor-bound
modules, observe whether new findings surface.

## Result — REJECTED + ROLLED BACK

| Module | 120 KB (baseline) | 140 KB (probe) | Δ |
|---|--:|--:|--:|
| spec/01 | 85 | **90 (EXCELLENT)** | +5 |
| spec/12 | 84 | 83 | −1 |
| spec/27 | 85 | 76 | **−9** |

**Mean Δ:** −1.7. **Bands:** spec/01 promoted GOOD→EXCELLENT; spec/27 holds GOOD;
spec/12 noise. spec/27 lost 9 points because 3 fresh findings surfaced — most
notably a CRITICAL "Context Window Truncation" complaining the module is 136 KB,
exceeding the 120 KB cap the auditor *thinks* applies (the prompt template hard-codes
"120KB cap" language; raising MAX_BYTES alone doesn't update the prompt).

## Rollback

`MAX_BYTES` reverted to 120_000. All 3 modules `--force` re-scored to restore
baseline cache. Final state: 84 / 85 / 85 with 0 findings — identical to A20-fu7.

## Lessons

- **NEW Lesson #76 — Walker MAX_BYTES is coupled to the prompt template.** Raising
  the byte ceiling without updating the prompt's "120KB cap" language causes the
  LLM to fabricate a "context truncation" CRITICAL on any bundle that previously
  fit. Future A18-class probes MUST update both:
  1. `MAX_BYTES` constant (line 45)
  2. The auditor prompt block describing the cap (search `120KB` / `120_000` in
     the prompt-template section, ~lines 240–290)

- **Lesson #18 reaffirmed (honest-baseline correction).** spec/27's −9 is not
  regression; it is the auditor seeing more bundle and finding 3 real items
  (delegated AC blind spot, missing reference snippets, walker-artifact CRITICAL).
  After the prompt-template fix in a future A18-full, items #2 and #3 are
  actionable D2/D4 lift opportunities.

- **NEW Lesson #77 — A18 is a 2-edit phase, not a 1-edit phase.** The "small probe"
  framing in A20-fu7's closing memo was incomplete. A18 proper requires:
  (a) MAX_BYTES bump, (b) prompt-cap-language update, (c) re-score, (d) finding
  triage, (e) per-module D2/D4 lift work. Estimated 5–15 LLM calls + 1–3 §97 edits.

## Backlog hand-off

The probe **did** surface that A18 is achievable but needs the 2-edit treatment.
A18-full is now the highest-leverage unblocked item (no longer "blocked" — the
CF-1010 ceiling claim was wrong; 140 KB requests pass fine). New backlog state:

| # | Status | Task |
|---|---|---|
| **A18-full** | 🟢 actionable (was 🔒 blocked) | Walker raise 120→140 KB **+ prompt-cap-language sync** + finding triage on spec/27 |
| **R1** | 🔒 blocked | Trace-map deeper bindings (still needs Lovable Cloud) |
| **Steady-state monitor** | 🟢 default | Quarterly re-baseline |

## No spec edits

Pure tooling probe. No banner bumps, no §97/§98/§99 changes, no lockstep ripple.
Cache restored to A20-fu7 baseline.
