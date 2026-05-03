# Phase 153 Task A24-fu49 — spec/22 audit no-op (LLM hallucinates against explicit pin)

**Date:** 2026-05-03
**Status:** CLOSED (no-op; 3 findings classified as harness errors)

## Re-score result

| metric | value |
|---|---|
| total | 87 / 100 (GOOD) |
| weighted | 87.4 |
| files used | 5/38 |
| bytes | 140 KB (saturated) |
| dimensions | d1=18, d2=20, d3=17, d4=15, d5=14 |

## Surfaced findings (all auditor errors)

**Issue #0 [HIGH/D5] "Missing Core Normative Files (04, 18, 34)"** —
direct contradiction of **AC-78** at `spec/22/00-overview.md:27`, which
explicitly pins these files as present on disk and classifies any such
finding as "harness bundling-cap artifact, NOT spec defect". Auditor
ignored the pin.

**Issue #1 [MEDIUM/D4] "Truncated Glossary and Enums"** — cites
"01-glossary-and-enums.md truncated at 136 KB cap". File 01 is only
**14 KB** — the truncation is at the BUNDLE boundary (00+01+97 =
~99 KB + part of file 02), not the file. Auditor conflated bundle
boundary with file boundary.

**Issue #2 [LOW/D3] "Externalized Concurrency Strategy"** — suggests
inlining spec/13 AC-22 into spec/22 AC-26. This would directly
**violate Lesson #36** (link-don't-restate) and is also explicitly
forbidden by `00-overview.md:33` ("Inlining a concurrency strategy
subsection into spec/22 (Lesson #36 violation)"). Auditor recommended
a known-forbidden pattern.

## Diagnosis

spec/22 already carries the **most aggressive walker-pin in the tree**
(AC-78 + AC-22-LV1 + AC-26 cross-ref, lines 21-34 of §00, well within
tier-1 walker bundle). The pin works mechanically (the contract IS
correct), but the LLM auditor is hallucinating findings that contradict
the pin's explicit text. This is a known limitation of LLM-derived
audits per Lesson #45 (cache non-monotonicity) + Lesson #34 (cache MUST
NOT be authoritative source of CRITICAL counts).

**Real CRITICAL count for spec/22: 0.** Score 87 is at honest floor for
a saturated normative-contract module with 38 files but only 5 bundle
slots — further lifts require either (a) §97 size reduction (would
force AC-78 below the cut, regressive) or (b) walker MAX_BYTES raise
beyond the Cloudflare 25 KB POST limit (blocked at architectural cap).

## Lockstep impact

None. Pure observation phase.

- No spec edits.
- No CI workflow change.
- All 5 strict gates remain GREEN (lockstep 87/87 · tree-health 168/168 strict · version-parity 74/74 · §99 freshness 81+6+0 · folder-refs 0 stale).

## Lesson #30 6th strike — A12 already shipped TWICE

This phase's draft proposal cited "A12 walker-cap raise (120 KB → 250
KB)" as the next lever. Investigation found:
- **A12 ALREADY CLOSED** as AC-34-13 (90 KB → 120 KB).
- **A18-full ALSO CLOSED** as AC-34-14 (120 KB → 140 KB, the Cloudflare
  ceiling).

Current `MAX_BYTES = 140_000` is at architectural maximum. Further raise
requires changing the gateway transport (out of scope), so prior memos'
"A12 will lift saturated modules" expectation is **architecturally
foreclosed**, not deferred. Lesson #73 needs amendment to reflect that
A12/A18 are exhausted levers, not pending work.

## NEW Lesson #74 — LLM auditor can hallucinate against explicit pins

Codify in `mem://process/phase-153-lessons` Section H:

When a module carries an explicit walker-pin (Lesson #63) at the §00
head AND the LLM auditor STILL surfaces findings that contradict the
pin's text (e.g., "missing files X/Y/Z" when the pin states they are
present), the correct response is **classify as auditor error, do NOT
add a stronger pin**. The pin is already maximal — adding more pin
prose risks pushing real contract content past the bundle cap.

Detection rule: a finding contradicts the pin when its `why` field
restates a fact that the pin's prose has already explicitly negated.

Precedent: spec/22 fu49 (3-of-3 findings contradicted AC-78 / AC-22-LV1
/ Lesson #36 explicit-forbid clause).
