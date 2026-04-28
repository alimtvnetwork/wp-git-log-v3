# Phase 140 — Restored missing AC-T-10 (silent §97 hole found by trace-map check)

**Date:** 2026-04-28
**Trigger:** Phase 139 sweep / autonomous queue empty → ran full linter suite → `check-trace-map-regression.py` flagged `missing_ac: 27-spec-toolchain/97-acceptance-criteria.md#AC-T-10`.

## Finding

`spec/27-spec-toolchain/97-acceptance-criteria.md` v2.0.0 header claimed *"AC-T-01..AC-T-10 preserved verbatim"* — but the file body jumps from AC-T-09 directly to AC-T-11. AC-T-10 had been silently dropped. Meanwhile:
- `linter-scripts/trace-map.toml:115-118` mapped AC-T-10 → `linter-scripts/check-spec-cross-links.py`.
- `.github/workflows/spec-health.yml:71` runs that script as the live *"Spec cross-link gate"* step.

So the contract was being enforced in CI without a spec AC backing it — a perfect inversion of the AC-31-31 surface.

## Action

Pure documentation repair, single AC restored:

```
### AC-T-10 — Spec cross-link gate (zero broken links)
- Given every relative link inside spec/**/*.md,
- When python3 linter-scripts/check-spec-cross-links.py --github runs as a CI gate,
- Then exit 0 with "OK All internal spec cross-references resolve"; ANY broken target fails the build.
- Verifies: linter-scripts/check-spec-cross-links.py (binds via trace-map.toml:115-118).
```

Lockstep:
- §97 v2.0.0 → **v2.0.1** (header claim now true; AC count 19 → 20).
- §98 v2.29.0 → **v2.30.0** (Phase 140 entry added at top of Releases).
- §99 v2.26.0 → **v2.27.0** (Phase 140 explainer block added above v2.26.0).

## Verification

| Check | Before | After |
|---|---|---|
| `check-tree-health.cjs --strict` | 168/168 | 168/168 ✓ |
| `check-lockstep.cjs --strict` | 0 findings | 0 findings ✓ |
| trace-map `missing_ac` | 1 (AC-T-10) | **0** ✓ |
| trace-map `ac_traced` | 23 | **24** (baseline restored) ✓ |
| trace-map `ac_total` | 1293 | 1294 (the new AC) |
| trace-map regression overall exit | 1 (red) | 1 (still red — drift unresolved) |

The trace-map check still fails because of the **drift growth** (471 → 1270 ACs without trace entries) and **orphan code growth** (25 → 38 unspecced files). Both are Phase 117's user-blocked territory (mechanizing the AC-31-31 backlog) — they record a real spec-vs-trace gap that wasn't authored on this turn and shouldn't be silently rebaselined without user consent.

## Scope discipline

Phase 140 fixed **only** the `missing_ac` error class (1 AC, surgical, undeniably correct). The bigger drift/orphan numbers are tabled for Phase 117's decision. Resisting the urge to rebaseline `trace-map-baseline.json` autonomously: that would mask 798 ACs of legitimate spec growth that nobody traced. The right move is to surface the gap, not silence it.

## Process lesson

The `linter-scripts/run.sh` orchestrator (which would have caught this earlier) fails on missing Go in this sandbox before reaching the Python checks. Running individual checkers in parallel via `code--exec` was the workaround. Possible follow-up: add a `--skip-go` mode to `run.sh` so post-Step-2 checks still execute when Go isn't installed locally. Tabling — that's tooling polish, not spec health.

## Other findings dismissed this turn

- `check-readme-canonicals.py` reports `ERROR: readme not found at readme.md` — the script is from a different project context (expects badges + CDN domain in lowercase `readme.md`). Repo has only `README.md` (Lovable stub) and `readme.txt` (timestamps). Script is **not wired into any CI workflow** (verified: zero refs in `.github/workflows/`). Not actionable; would require either adopting the canonical readme convention (out of scope) or retiring the script. Tabled.
