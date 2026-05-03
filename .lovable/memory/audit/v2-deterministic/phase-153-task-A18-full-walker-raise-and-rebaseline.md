---
phase: 153
task: A18-full
date: 2026-05-03
status: CLOSED — walker raised 120→140 KB + dynamic truncation marker; v13 honest rebaseline
gates: lockstep 87/87 — GREEN
---

# A18-full — Walker raise + prompt-coupling fix + v13 rebaseline

## What shipped

1. **`MAX_BYTES` 120_000 → 140_000** (line 45) — under CF gateway limit; A18-probe
   confirmed 140 KB requests return HTTP 200.
2. **Lesson #77 fix — dynamic truncation marker** (line 213): hard-coded
   `"...TRUNCATED at 120KB context cap..."` → f-string `f"...TRUNCATED at
   {MAX_BYTES//1024}KB context cap..."`. Without this, raising MAX_BYTES makes
   the auditor see a stale "120KB" literal in its own bundle and fabricate
   "Context Window Truncation" CRITICALs (precisely what A18-probe observed).
3. **Tree-wide `--force` rebaseline** (~25 LLM calls, all 200 OK).

## Result — v13 baseline

| Metric | v12 (A20-fu7) | **v13 (A18-full)** | Δ |
|---|--:|--:|--:|
| Tree mean | 90.6 | **89.9** | −0.7 |
| EXCELLENT | 15 | 12 | −3 |
| GOOD | 8 | 11 | +3 |
| NEEDS_WORK | 0 | 0 | — |
| BLOCKING | 0 | 0 | — |
| CRITICAL findings | 0 | **0** | — |
| HIGH findings | 0 | **14** | +14 |
| MEDIUM findings | 0 | **23** | +23 |

**Per-axis movers:**
- Lifters: spec/16 91→**98** (+7), spec/07 86→**95** (+9, axis-cap), spec/13 92→93,
  spec/11 87→**86** noise.
- Honest corrections (D5-truncation visibility on cap-bound modules):
  spec/12 84→76, spec/03 94→81, spec/05 89→82, spec/27 85→**88** (+3 net),
  spec/01 85→**89** (+4 net).
- Held: spec/23/24/28 = 97/95/97 EXCELLENT cap.

## Diagnosis: why mean went DOWN

This is **Lesson #18 in action** (honest-baseline correction; A7 precedent).
The 90.6 baseline inflation came from the dynamic-marker bug: at 120 KB the
auditor was suppressing D5 truncation findings on cap-bound modules because
the literal "120KB cap" in the truncation marker matched its mental model of
"acceptable context budget" — so D5 scored full marks even when 80%+ of files
were missing. With the dynamic marker fix:

- Cap-bound modules (18/23 modules > 120 KB total) now get **honest D5**
  scores reflecting actual coverage (`files_used / files_total`).
- 14 HIGH + 23 MEDIUM findings surfaced are **real per-module work backlog** —
  most are "context truncation cuts off file X" (mechanical fix: shrink
  module by archiving sub-files) or "AC-NN references file Y not in bundle"
  (mechanical fix: inline summary or anchor to canonical slot per Lesson #36).

## v13 is the new ground truth

Per Lesson #18: **do NOT roll back the prompt-coupling fix** to restore the
inflated 90.6. The 89.9 baseline is more accurate. EXCELLENT count fell from
15 to 12 because three modules (spec/03, spec/05, spec/11 etc.) were never
*really* EXCELLENT — they were just unmeasurable. Future lift work should
target the 14 HIGH findings first (largest single moves available).

## Backlog implications

- **A18-fu1 (NEW, actionable)**: Triage 14 HIGH findings → most are
  D5-truncation on cap-bound modules. Two mechanical fixes per module:
  (a) archive non-normative sub-files to bring under cap; (b) inline summary
  of cut-off content into §00 or §97. Estimate: 5-10 sessions, +5-8 tree mean
  achievable (back to 95+).
- **A18-fu2 (codify)**: Add AC-34-14 to slot 34 spec — pin the dynamic-cap
  marker contract (Lesson #77), so future MAX_BYTES bumps don't re-introduce
  stale-literal regression. **Defer until A18-fu1 reduces backlog**, since
  AC-34-14 only protects against future raises.

## Lockstep impact

- `linter-scripts/audit-ai-implementability.py`: 2-line change (constant + marker).
  Per Phase 153 Task #29a/A12 precedent, slot 34 §00 (`spec/27/.../34-*.md`)
  needs a banner bump for the constant change once AC-34-14 is authored
  (deferred to A18-fu2).
- Cache files: 23 rewritten with new scores — these are LLM-derived snapshots,
  not source-of-truth, no banner bumps required (Lesson #34).
- Lockstep 87/87 — GREEN.

**No spec edits in this phase.** Pure tooling + cache refresh. The 89.9
baseline is the honest starting point for A18-fu1 backlog work.

## Lessons reinforced

- **Lesson #77 confirmed + actually fixed** (A18-probe identified the wrong
  location; the real coupling was the truncation-marker f-string, not the
  RUBRIC prompt).
- **Lesson #78 confirmed**: A18 was indeed a 2-edit phase (constant + marker).
  Per-module D2/D4 lift work now becomes A18-fu1.
- **Lesson #18 reaffirmed**: do not chase pre-correction high-water marks.

## Report artifact

`/mnt/documents/spec-ai-implementability-audit-v13.md` — full per-module table.
