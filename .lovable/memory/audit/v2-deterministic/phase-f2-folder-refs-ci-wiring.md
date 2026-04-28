# Phase F2 — Folder-reference gate wired into CI (14th strict gate)

**Date:** 2026-04-28
**Trigger:** User reply `next` (recommended in Phase F1 retrospective; F2 unblocked by F1 bringing the gate to 0 stale refs).

## What landed

Wired `linter-scripts/check-spec-folder-refs.py` into `.github/workflows/spec-health.yml` as a strict gate, inserted between the cross-link gate (#1) and the tree-health gate (#2). The script existed since authoring but was dormant (zero CI references) — Phase 141 surfaced it; Phase F1 brought it to 0 stale refs; Phase F2 wires it so future drift triggers a CI fail at PR time.

## AC-31-31 4-way enumeration lockstep

Per AC-31-31's discipline (registry row #1, Phase 103 footer test), CI gate-count changes touch 4 sites in lockstep. All 4 updated:

| # | Site | Change |
|---|---|---|
| 1 | `.github/workflows/spec-health.yml` | New step `Spec folder-reference gate (Phase F2)` |
| 2 | `linter-scripts/audit-spec-vs-code-v2.py` | `RUBRIC_VERSION` v2.22 → v2.23; footer +row #14; section title gains "+ F2"; EXEC-SUMMARY → "14 strict CI gates" |
| 3 | `linter-scripts/test/test-qa-baseline-footer.sh` | awk pattern +`Spec folder-reference gate` (now expects 14/14) |
| 4 | Regenerated `00-index.md` + `EXECUTIVE-SUMMARY.md` (deterministic mode) | Reflects 14-gate baseline + v2.23 |

## F2 is NOT an AC-31-31 row addition

The new gate enforces folder-ref allowlist consistency — a **single-source invariant** already locked by AC-62-01..04, not a 3+ file enumeration parity. AC-31-31 registry remains correctly bounded at 4 rows (per Phase 114 sweep). The 4-way footer-update cycle is the *direct lockstep* zone (registry row #1 — Phase 103 footer test) which already covers the gate-count change mechanically.

## What did NOT change

- `linter-scripts/check-spec-folder-refs.py` source untouched (CI-ready since authoring, just unwired).
- §02 spec untouched (gate behavior unchanged, only CI invocation is new).
- Allowlist data unchanged from Phase F1 baseline (24 doc-only + 25 external entries).
- AC-31-31 registry table unchanged (4 rows, correctly bounded).

## Files touched (lockstep)

| File | From → To |
|---|---|
| `.github/workflows/spec-health.yml` | +1 step (`Spec folder-reference gate`) |
| `linter-scripts/audit-spec-vs-code-v2.py` | RUBRIC v2.22 → **v2.23**; footer +row #14 |
| `linter-scripts/test/test-qa-baseline-footer.sh` | awk +1 pattern |
| `.lovable/memory/audit/v2-deterministic/00-index.md` | regenerated (v2.23, 14 gates) |
| `.lovable/memory/audit/v2-deterministic/EXECUTIVE-SUMMARY.md` | regenerated |
| `spec/27-spec-toolchain/31-audit-spec-vs-code-v2.md` | v1.22.0 → **v1.23.0** (Source +13th artefact, rubric changelog +v2.23 row) |
| `spec/27-spec-toolchain/98-changelog.md` | v2.37.0 → **v2.38.0** |
| `spec/27-spec-toolchain/99-consistency-report.md` | v2.34.0 → **v2.35.0** |

## Gates (verified at landing)

- `python3 linter-scripts/check-spec-folder-refs.py` → 0 stale refs ✅
- `bash linter-scripts/test/test-qa-baseline-footer.sh` → **11/11 at 14-gate alignment** ✅
- `bash linter-scripts/test/test-weights-parity.sh` → 8/8 ✅
- `bash linter-scripts/test/test-overview-inventory-parity.sh` → 6/6 ✅
- `bash linter-scripts/test/test-readme-inventory.sh` → 20/20 at 7 self-tests ✅
- `node linter-scripts/check-tree-health.cjs --strict` → 168/168 ✅
- `node linter-scripts/check-lockstep.cjs` → 87/87 / 0 findings ✅
- `AUDIT_DETERMINISTIC=1 python3 linter-scripts/audit-spec-vs-code-v2.py` → mean 98.0/99.8 (unchanged) ✅

CI gate count: **13 → 14**.

## Outcome

Phase F2 closed. Folder-reference drift is now mechanically prevented at PR time. The Phase 141 → F1 → F2 chain is fully resolved. Only R1 (real-AI re-audit, blocked on Lovable Cloud) and R2 (passive monitoring) remain.
