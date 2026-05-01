# Phase 153 Task A24-fu32 — Productionise Bundle-Budget Audit (Slot 35)

**Closed:** 2026-05-01

## Action

Productionised ephemeral `/tmp/a24-fu27-bundle-budget.py` (used in fu27 to surface the OVER class + drive fu28-fu31 sweep) as permanent §27 slot 35:

- `linter-scripts/audit-bundle-budget.py` — deterministic walker bundle budget audit
- `spec/27-spec-toolchain/35-audit-bundle-budget.md` v1.0.0 — 5 ACs (AC-35-01 cap source-of-truth; AC-35-02 deterministic classification; AC-35-03 strict mode fails on OVER; AC-35-04 default advisory; AC-35-05 self-test parity)
- `linter-scripts/test/test-audit-bundle-budget.sh` — 10 assertions (T1-T9), all PASS

## Anti-drift contract

`MAX_BYTES` read from `linter-scripts/audit-ai-implementability.py:45` at runtime per Lesson #36 (link, never restate). Verifies AC-34-13 SemVer-locked single source of truth.

## Current baseline

- 4 OVER (spec/01, 07, 22, 27 — post-fu31 deficits 5.5–148 KB; v9 scores 85-93)
- 6 AT_CEILING (spec/04, 12, 13, 14, 17, 18)
- 13 CLEAR

Default advisory; `--strict` exits 1 on OVER. CI wiring deferred to graduation phase when OVER count = 0.

## Lockstep

- §27 §00 v2.84.0 → **v2.85.0** (slot 35 row added)
- §27 §98 v2.84.0 → **v2.85.0** (new top row)
- §27 §99 v2.81.0 → **v2.82.0**

## Validation

- Self-test 10/10 PASS · inventory parity 6/6 PASS (41/41 tracked)
- Lockstep 87/87 · tree-health 168/168 strict · version-parity 74/74 · §99 freshness 81+6+0 — all GREEN

## Lesson #68 (codified at §98 v2.85.0 row)

Ephemeral `/tmp/*.py` audit scripts that drove a multi-phase sweep MUST be productionised under `linter-scripts/` immediately after the sweep closes — temporary tools become institutional debt the moment their lesson ships. Mirror of Lesson #31 at the **temporary-script-vs-production-gate axis**.
