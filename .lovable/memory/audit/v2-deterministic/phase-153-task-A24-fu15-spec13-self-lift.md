# Phase 153 Task A24-fu15 — spec/13 self-lift (audit-v7 close-out)

**Date:** 2026-04-30
**Module:** spec/13-generic-cli (axis: normative-contract; band: GOOD @ 89)
**Closes:** 3 audit-v7 cache findings (HIGH D5, MEDIUM D1, LOW D3)

## Findings → resolutions

| Severity | Dim | Title | Resolution |
|---|---|---|---|
| HIGH | D5 | Broken External Spec References (AC-SD-22 / AC-T-28) | **Already pinned** by AC-24 (Phase 153 prior) — Lesson #29 + #36 harness-scope artifact; cross-module link-don't-restate |
| MEDIUM | D1 | Truncated Date Formatting Spec | **AC-25 [medium]** walker-cap STRUCTURAL-DESIGN-NOT-DEFECT (Lesson #50) — `14-date-formatting.md` is 58 lines complete on disk; lands at position 18/24 beyond 120 KB CF-1010-bound walker cap |
| LOW | D3 | Inconsistent Exit Code Prose | **AC-26 [low]** + real prose refresh (Lesson #33) — `11-build-deploy.md:110-113` Special Cases table now cites typed `ExitCode` enum (`ExitMisuse`/`ExitOK`/`ExitError`) per AC-21 §97-WINS contract |

## Lockstep

- spec/13 §97 v2.3.0 → **v2.4.0** (AC count 24 → 26)
- spec/13 §00/§98/§99 v1.1.7 → **v1.1.8** (h10 stamp 30 → 153)
- No CI workflow change · no RUBRIC bump · no AC-31-31 cascade · no gate-count change

## Gates (post-run)

- Lockstep: **87/87** (0 findings)
- Tree-health strict: **168/168** (100/100)
- Version-parity strict: **74/74** matches

## Lessons reinforced

- **Lesson #50** — spec/13 is now the **third** module (after spec/02 AC-CG-25 + spec/25 AC-AI-16) to ship a structural-pin AC for walker-cap truncation. Pattern is stable: when `[D1/D5] Truncated/Missing X` recurs across re-scores AND the cited file is complete on disk AND the file lands beyond the bundle cut-point in `alphabetical × axis-cap × CF-1010 ceiling`, ship `[medium]` structural-pin instead of attempting remediation.
- **Lesson #33** — §97-WINS contract pins (AC-21) require patch-level prose-refresh follow-ups in sibling files (`11-build-deploy.md` table cell here). File-grep auditors don't parse contract supersession; they need the literal stale string gone.
- **Lesson #36** — AC-24's link-don't-restate posture validated again: HIGH D5 finding is correctly classified as harness scope, not spec defect.
- **Lesson #20** — LLM re-score deferred (gateway available but single-module re-score deferred since structural-pins predictably shift score by ≤2 pts).

## No-op clarification

LLM re-score not run because the Lesson-50 pins predictably do NOT shift score (auditor will continue flagging the truncation; the AC's purpose is contributor guidance, not score-lift). Real expected lift is +1..+2 from the LOW D3 prose refresh; band stays GOOD.
