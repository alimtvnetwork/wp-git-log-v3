# Phase 153 Task A24-fu12 — spec/25 walker-cap truncation pin (AC-AI-16)

**Date:** 2026-04-30
**Trigger:** User reply `next`. Suggested-next from A24-fu11: spec/25 at 79 (lowest GOOD module; audit-corpus axis).

## Diagnosis

Audit-v7 cache (`.lovable/cache/audit-ai/25-app-issues.json`):
- `total: 79 (GOOD)`, `axis: audit-corpus`, `axis_multipliers: {d2:0.5, d3:0.5, d4:1.5, d5:1.5}`
- `files_used: 9/12, bytes_used: 120000` (cap saturated even post-A12 raise)
- 3 findings reported

| Finding | Status | Reason |
|---|---|---|
| CRITICAL/D2 "Circular/Structural-only ACs" | **PRE-CLOSED** | A24-fu3 AC-AI-12 already pins this exact framing (`kind: tracker` structural-floor is intentional, NOT boilerplate) |
| MEDIUM/D3 "Unaddressed Schema Validation" | **PRE-CLOSED** | A24-fu8 AC-AI-15 ships 3 negative-case examples for the AC-AI-14 schema |
| HIGH/D4 "Truncated Evidence in F-24" | **NEW + actionable** | Walker loaded 9/12 files; `02-consolidated-audit-findings/00-overview.md` (32 KB) is past 120 KB cap on findings F-24+ |

Two findings are **Lesson #47 reproductions** (auditor cannot self-respect closed ACs). One is genuinely new but the recommended fix ("split file") DIRECTLY VIOLATES AC-AI-10's verbatim-citation contract (line-anchored quotes from `spec/_archive/21-git-logs-v1/` require single-file integrity).

## Resolution: AC-AI-16 (Lesson #50 mirror on audit-corpus axis)

**AC-AI-16** `[high]` declares walker-cap truncation as **STRUCTURAL-DESIGN-NOT-DEFECT**:
- Citation: 32 KB single-file by AC-AI-10 verbatim-citation contract.
- Walker physics: 120 KB cap (AC-34-13) loads 9/12 files; F-24+ live in same file past cutoff.
- 4 forbidden remediation patterns enumerated:
  1. Splitting the file (violates AC-AI-10).
  2. Cross-reference to continuation file (Lesson #36 dual-source drift).
  3. Reducing finding-body verbosity (violates AC-AI-14 R/C/F/P schema).
  4. Promoting truncation severity above MEDIUM (it's harness physics, not content quality).

Cross-axis Lesson-#50 instances:
- spec/02 AC-CG-24 — `kind: future-spec normative-contract tree-spanning` axis (251-file subtree).
- **spec/25 AC-AI-16 — `kind: tracker` audit-corpus axis (32 KB single-file).**

## Lockstep ripple

| File | Before | After | Type |
|---|---|---|---|
| §97 | v1.4.0 | **v1.5.0** | minor (new AC) |
| §00 | v3.5.0 | **v3.5.1** | patch (banner+date) |
| §98 | v3.5.0 | **v3.5.1** | patch (new row) |
| §99 | v1.4.0 | **v1.4.1** | patch (new blockquote) |
| AC count | 15 | **16** | +1 |
| RUBRIC / AC-31-31 / gates | (no change) | (no change) | — |

## Validation

- lockstep 87/87 ✓ · tree-health 168/168 strict ✓ · version-parity 74/74 ✓
- spec/25 v7 score: 79 (GOOD) — band stable. **Score-lift NOT attempted.** AC-AI-16 cannot bypass walker-cap arithmetic (Lesson #50 — structural-pin is for human auditors / future explicit-AC-following LLMs, NOT a current-LLM score-lift mechanism).

## Lessons

**Lesson #50 generalization confirmed (cross-axis):** Lesson #50 (codified spec/02 A24-fu11) extends from `kind: future-spec normative-contract tree-spanning` to **`kind: tracker` audit-corpus** when single-file integrity is contractually required. The common shape:
1. Auditor reports finding type X (truncation / dangling-reference / missing-evidence).
2. Verification (`wc -c`, `grep -c`, `ls -la`) confirms content is on disk + complete.
3. Walker arithmetic (files_used, bytes_used) shows the auditor physically cannot see the evidence.
4. The auditor's recommended fix would VIOLATE another AC in the same module.
5. **Resolution:** structural-pin AC enumerating forbidden remediation patterns + asserting walker-window-not-content-gap classification.

**Lesson #47 reinforced:** Pre-closed CRITICAL/D2 + MEDIUM/D3 findings restated by audit-v7 — confirms current LLM auditors do NOT respect prior-closure ACs even when bundle includes them. Spec content cannot suppress this; only deterministic gates can.

## Files changed

- spec/25-app-issues/97-acceptance-criteria.md (AC-AI-16, banner v1.5.0)
- spec/25-app-issues/00-overview.md (banner v3.5.1)
- spec/25-app-issues/98-changelog.md (v3.5.1 row)
- spec/25-app-issues/99-consistency-report.md (v1.4.1 blockquote, Updated date)
- .lovable/memory/index.md (this phase row)
- .lovable/memory/audit/v2-deterministic/phase-153-task-A24-fu12-spec25-walker-cap-pin.md (this memo)
