# Phase 153 Tasks N7 + N9 — Gateway probe + audit self-test CI wiring

**Closed:** 2026-05-05
**Status:** ✅ CLOSED (N9 productive · N7 budget-blocked at gateway level)

## N7 — Opportunistic gateway re-score on sub-90 stale-cache module

Per Lesson #38, ran `test -n "$LOVABLE_API_KEY"` first → **key=set**. Attempted fresh `--force --chunked` re-score on `04-database-conventions` (89/100, chunked_path=None — closest sub-90 to threshold).

**Result:** `HTTP Error 402: Payment Required` from the gateway. Lesson #82 advisory fired correctly post-attempt; cache stayed at 89/100.

**Lesson #38 refinement:** the secret being set is necessary but not sufficient — the gateway can be budget-capped at 402 even with a valid key. R1/R2/A8 remain blocked at the gateway-budget level (not the secret level). Future probes can be a single `--force` invocation; if 402 fires, defer immediately without retrying.

## N9 — Wire 3 deterministic audit self-tests into `spec-health.yml`

Verified via grep that none of `test-audit-ai-implementability.sh`, `test-audit-bundle-budget.sh`, `test-audit-chunked-cache-advisory.sh` were wired into CI (Lesson #30 verify-before-open). Added a new "Audit-AI self-test triplet (deterministic — no gateway)" workflow step immediately after `test-archive-exclusion-runtime.sh` (line 364). Per H1 workflow-step-parity rule these are broader-contract tests (not single-footer-gate locks), so they share one dedicated step that runs all three sequentially.

**Why bundled into one step (not three separate steps):**
- All three exercise the same script family (`audit-*-implementability.py` + `audit-bundle-budget.py`)
- All three are deterministic (no `LOVABLE_API_KEY` guard needed)
- Combined runtime ~8s — splitting into three steps would add ~3× the YAML noise + GitHub Actions overhead per step
- Failure attribution is preserved by `set -e` + bash exit semantics: the failing step's name appears in the CI log

**Assertions newly locked in CI:**
- 16 (CLI surface contract — AC-34-01..06 + AC-34-16 per-chunk SHA inventory)
- 10 (walker-budget analyzer — Lesson #65/#73 diagnostics)
- 5 (Lesson #82 chunked-cache advisory — N6/N8 mechanical lock)
- **Total: 31 assertions, ~8s combined**

## Files

- Edited `.github/workflows/spec-health.yml` (added audit-AI self-test triplet step at line 364, 22 lines)
- No spec edits, no script edits, no lockstep ripple (CI-only change)

## Verification

- All 3 self-tests pass locally: 16/16 + 10/10 + 5/5 = 31/31
- Tree-health strict: 168/168 GREEN
- Lockstep: 87/87 · 0 findings GREEN
- No new gates in `check-99-summary-freshness.py` registry needed (workflow-step parity preserved per H1 — the triplet is a broader-contract step, not a numbered footer gate)

## Out of scope

- N3 (false-coverage inverse audit) — separate task
- A8 / R1 / R2 — gateway-budget-blocked (N7 confirmed today)
- Splitting the triplet into 3 separate workflow steps if any one becomes long-running (~10s+) — current bundling is correct at ~8s combined
