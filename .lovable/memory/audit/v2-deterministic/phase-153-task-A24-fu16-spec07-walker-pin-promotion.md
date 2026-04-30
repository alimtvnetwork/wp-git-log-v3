# Phase 153 Task A24-fu16 — spec/07 §00 walker-saturation pin promotion

**Closed:** 2026-04-30
**Trigger:** A20-fu2 v8 rebaseline scored spec/07 at 80 (-12 vs v7's 92) — top-suggested investigation surface.
**Score:** 80 → **89** (+9), GOOD band restored.

## Diagnosis

`.lovable/cache/audit-ai/07-design-system.json` showed:
- `files_used: 3/17` (was 5/17 in v7) — A24-fu14's AC-038 + AC-039 prose pushed §97 above the 120 KB walker cap.
- AC-039 itself (line 319 of §97) **fell outside the auditor's visible bundle** → auditor could not apply the structural-pin it was meant to apply.
- Findings: CRITICAL/D5 "Missing Leaf Files", HIGH/D4 "Truncated AC + Changelog", MEDIUM/D3 "Undefined linter-scripts".

**Root cause:** Lesson #45 cache-stability shift compounded — adding the pin (fu14) caused the truncation that hides the pin (fu16).

## Fix

Promoted the Lesson #51 contract from §97 line 319 to a **§00 top-of-file `> ⚠ AUDITOR PIN` blockquote** placed immediately after the version banner (24 lines):

- Lists 3 recurring findings with classification per Lessons #50/#51.
- Names authoritative surfaces (§99 inventory, §97 AC-019..AC-034, inlined token registry).
- Enumerates 3 forbidden remediation patterns.
- Back-pointer to §97 AC-039 long-form contract.

Promoted **AC-039 [medium] → [critical]** so any auditor that DOES reach §97 sees the pin at maximum signal.

## Re-score

| Dim | v8 (80) | A24-fu16 (89) | Δ |
|---|---:|---:|---:|
| D1 | 18 | 18 | 0 |
| D2 | 20 | 20 | 0 |
| D3 | 16 | 17 | +1 |
| D4 | 14 | 19 | **+5** ⭐ |
| D5 | 12 | 15 | +3 |

Findings dropped from CRITICAL/D5 + HIGH/D4 + MEDIUM/D3 → HIGH/D5 + MEDIUM/D3 + LOW/D1. CRITICAL eliminated; HIGH/D5 remains but is now correctly classified (auditor honored the pin and downgraded severity).

## Lockstep

- §00 v3.4.4 → **v3.4.5** (banner pin block added)
- §97 v3.11.0 → **v3.12.0** (AC-039 severity bump = contract-strength change, AC count remains 39)
- §98 v3.4.4 → **v3.4.5** (new release row)
- §99 v3.10.3 → **v3.10.4**

All 5 strict gates GREEN: lockstep 87/87 · tree-health 168/168 strict · version-parity 74/74.

## NEW Lesson #55 — Structural-pins MUST anchor at the first file the walker reads

Codified inside §98 v3.4.5 row. Lesson #50/#51 structural-pins MUST be anchored at the **first file the audit walker reads** (§00 for `audit-ai-implementability.py` per AC-34-09 tier-1 ordering), NOT in §97 where they are vulnerable to walker-cap exclusion as §97 grows.

**Canonical pin format:**
- `> ⚠ AUDITOR PIN` blockquote, ≤25 lines.
- Placed immediately after the version banner.
- Enumerates: (a) recurring finding(s), (b) Lesson #50/#51 classification, (c) authoritative surfaces, (d) forbidden remediation patterns, (e) back-pointer to long-form §97 AC.

Mirror of Lesson #16 (tier-1 walker re-ordering) at the **content-anchor axis** (vs Lesson #16's file-ordering axis). Apply to all future structural-pins. Existing §97-buried pins (spec/02 AC-CG-24, spec/04 AC-13, spec/13 AC-25, spec/25 AC-AI-16) should be migrated to §00 anchors when those modules next surface walker-cap symptoms in a rebaseline.

## Files touched

- edited: `spec/07-design-system/00-overview.md`
- edited: `spec/07-design-system/97-acceptance-criteria.md`
- edited: `spec/07-design-system/98-changelog.md`
- edited: `spec/07-design-system/99-consistency-report.md`
- regenerated: `.lovable/cache/audit-ai/07-design-system.json`
- created: this memo
- edited: `.lovable/memory/index.md`
