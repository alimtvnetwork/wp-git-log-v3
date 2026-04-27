# Phase 130 — Full-tree audit v5 publication (audit-v4 baseline correction)

**Date:** 2026-04-27
**Trigger:** `next` after Phase 129. Re-validating audit-v4 critical findings before deferring to user-blocked queue.

## Discovery
Audit-v4 (2026-04-25, 45/100 baseline) listed 4 critical findings + 3 user-blocked decisions. Mechanical re-validation shows **3 of 4 critical findings already resolved**, and 2 of 3 "blocked decisions" already actioned by prior phases:

| audit-v4 item | Status | Evidence |
|---|---|---|
| #1 Session-persistence regression | 🔓 Open (not re-observed in 12 phases) | Phases 117–129 files persist |
| #2 Root slot 22 collision | ✅ Resolved | `ls -d spec/2[0-9]-*` shows 22-git-logs-v2 alone, 25-app-issues exists |
| #3 32 broken links | ✅ Resolved (Phase 129) | Dashboard reports 0 broken / 3 waived |
| #4 Legacy `21-git-logs/` folder | ✅ Resolved | `test -d` → folder deleted entirely |
| Blocker: rename 22→25-app-issues | ✅ Done | `spec/25-app-issues/` exists |
| Blocker: archive 21-git-logs/ | ✅ Done | folder deleted |
| Blocker: broken-link strategy | ✅ Done | Phase 129 waiver semantics |

audit-v4's quantitative claims also stale:
- Claimed 13 modules missing §97 → actual 1 (only `_archive/`, by design)
- Claimed 15 modules missing §99 → actual 1 (same)
- Claimed 15 sub-folder collisions → actual 6 intentional name-duplicates (`diagrams/`, `images/`, etc. under different parents)
- Claimed dashboard at 80/100 → actual **100/100 (A+)** since Phase 129

## Action
Wrote `spec/17-consolidated-guidelines/33-full-tree-ai-audit-v5.md` (110 lines) that:
1. Reconciles each audit-v4 finding against current state with `bash` reproducibility commands
2. Documents the corrected quantitative baselines
3. **Deliberately publishes no numeric headline** — re-using audit-v4's rubric without the AI scorer would be a fake number. Defers to R1 (real-AI re-audit, blocked on Lovable Cloud).
4. Carries forward 3 open items: R1, R2 (session-persistence monitoring), B1 (App identity)

audit-v4 banner-superseded with link to v5.

## Slot collision incident (recovered)
Initially wrote v5 to slot 32 → collided with existing `32-phase-26-31-rollup.md`. Detected on next `ls`, immediately moved to slot 33. Updated supersession link in audit-v4. **Core memory rule "file slots are immutable once shipped" held.** Slot 32 retains the rollup; v5 lives at slot 33.

## Files touched
- `spec/17-consolidated-guidelines/33-full-tree-ai-audit-v5.md` — created (110 lines)
- `spec/17-consolidated-guidelines/31-full-tree-ai-audit-v4.md` — supersession banner added (line 3)
- `spec/17-consolidated-guidelines/98-changelog.md` — v2.4.0 → v2.5.0
- `spec/17-consolidated-guidelines/99-consistency-report.md` — v4.3.0 → v4.4.0; file count 33 → 34; row 34 added
- `.lovable/memory/audit/v2-deterministic/phase-130-audit-v5-baseline-correction.md` — this memo

## Verification
- Lockstep gate: 87/87 pass · 0 findings ✅
- Python cross-link gate: green ✅
- Dashboard: **100/100 (A+)** · 861 files · 2941 links (2929 ok / 12 external-allowed / 0 broken) ✅

## Memory impact
Need to update `mem://specs/full-tree-audit-v4.md` (or rename to v5) to reflect resolved findings — otherwise future sessions will keep reporting stale "45/100 baseline" and "3 user-blocked decisions" that don't exist anymore.
