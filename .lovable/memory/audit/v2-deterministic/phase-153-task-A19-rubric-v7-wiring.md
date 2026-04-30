# Phase 153 Task A19 — Rubric v7 wiring lands in `audit-ai-implementability.py`

**Status:** CLOSED 2026-04-30
**Predecessors:** A16 (metadata foundation), A17 (contract authored: AC-34-10/11/12)
**Successors:** A20 (LLM rebaseline with v7); A18 (per-axis cap refinement, conditional on A20 measurement)
**Lesson refs:** #15 (CI gateway-secret guard), #34 (cache staleness), #41 (split-phase rubric evolution), **#42 NEW** (importlib-in-bash for internal-fn self-tests)

---

## 1. Goal

Implement the AC-34-10/11/12 contract A17 authored, so the live audit script reads `content_axis` from each module's front-matter, applies axis-appropriate multipliers + soft caps, and fails-fast on missing/invalid axis instead of silently falling back to v6 uniform weighting.

## 2. Code surface added

### Constants (top of `linter-scripts/audit-ai-implementability.py`)

- `AXIS_VALUES`: 5-value enum `{normative-contract, process-guidance, integration-spec, audit-corpus, tooling-spec}`
- `AXIS_MULTIPLIERS_RAW`: per-axis D1–D5 weight rows (verbatim from §97 cascade table)
- `AXIS_CAPS`: per-axis soft cap (95 for `process-guidance`/`integration-spec`/`audit-corpus`; 100 for `normative-contract`/`tooling-spec`)

### Helpers

- `axis_multipliers(axis)` — returns AC-34-10 normalised row (raw rows summing to >5.0 are divided by `(raw_sum / 5.0)` so output sums to exactly 5.0).
- `read_content_axis(mod_dir)` — parses `00-overview.md` YAML front-matter; returns `(axis, error)`. Front-matter regex matches `^---\n...\n---\n`; axis line regex `^\s*content_axis:\s*([A-Za-z0-9_\-]+)\s*$`. Returns error string if file missing, no front-matter, no axis key, or axis ∉ AXIS_VALUES.
- `apply_rubric_v7(scores, axis)` — multiplies each `dN × multiplier`, sums, applies cap. Returns dict with `weighted_total` (pre-cap, float) + `total_v7` (post-cap, int) + `axis_multipliers` + `axis_cap`.

### Wiring changes

- `audit_module(...)` gains `axis` parameter. `bundle_sha` now hashes `f"axis={axis}\n{bundle}"` so v6 cache entries (no axis) are stale-by-construction. After parsing scores, calls `apply_rubric_v7(...)`, sets `parsed["total"] = total_v7` (band uses v7), `parsed["total_v6"]` retained for transparency, `parsed["rubric"] = "v7"` for cache-version pinning.
- `main()` runs an axis pre-flight pass over every selected module BEFORE entering the gateway loop. Aggregates ALL missing/invalid axis errors and exits 2 with a per-module summary list (mirrors `check-version-parity.py` aggregate-then-fail pattern; one error per affected module instead of fail-on-first).
- Report table gains `Axis` + `Total (v7)` + `Raw (v6)` columns + 🔒 cap-marker on capped totals; per-module stdout shows `axis=<value>` + `(cap NN)` when total hits the cap.

## 3. Self-test extended

`linter-scripts/test/test-audit-ai-implementability.sh`: 6 → **9 assertions**.

- **AC-34-10**: imports the script via `importlib.util.spec_from_file_location()` (handles hyphenated filename), iterates `AXIS_VALUES`, asserts each `axis_multipliers(axis)` sums to 5.0 ± 1e-6. Spot-checks `audit-corpus` with all-20s scoring caps to 95.
- **AC-34-11**: asserts every axis preserves the 60-floor — a module scoring 12s in every dimension (raw 60) MUST land ≥60 under any axis (BLOCKING threshold preserved tree-wide). Asserts every axis cap is ≥95.
- **AC-34-12**: creates temporary `spec/00-aai-axis-test-fixture/00-overview.md` with (a) no front-matter, then (b) invalid `content_axis: not-a-real-axis`. Both invocations MUST exit 2. Fixture removed in cleanup.

All 9 PASS.

## 4. Lockstep deltas

| File | Before | After | Bump kind |
|---|---|---|---|
| slot 34 §00 | v1.3.0 | **v1.3.1** | patch (implementation; contract unchanged from A17) |
| §27 §00 | v2.79.0 | **v2.79.1** | patch |
| §27 §98 | v2.79.0 | **v2.79.1** | patch (new changelog row + Lesson #42) |
| §27 §99 | v2.76.0 | **v2.76.1** | patch |

## 5. Out of scope (and why)

- **No CI workflow change** — existing `--strict` step in `spec-health.yml` (Task A12) automatically picks up Rubric v7 on next run with `LOVABLE_API_KEY` available. Community-PR fork-skip behaviour preserved (Lesson #15).
- **No AC count change** — A19 ships the implementation of the 3 ACs A17 authored; AC index unchanged.
- **No AC-31-31 cascade** — slot-34 ACs are not §27 module-level parity ACs.
- **No RUBRIC_VERSION bump, no gate-count change.**

## 6. Cache invalidation

All 23 existing `.lovable/cache/audit-ai/<module>.json` entries are stale-by-construction (their `bundle_sha` was computed without the `axis=…` prefix and they lack the `rubric: "v7"` pin). On next CI run with `LOVABLE_API_KEY`, every module re-scores under v7. **This is intentional and correct** per Lesson #34 (audit caches are LLM-derived snapshots; spec patches invalidate them by design). A20 will measure the v7 baseline; cache refresh happens as the gateway-budget allows.

## 7. Verification

- Self-test: 9/9 PASS
- Live `--no-network` over all 23 modules: exit 0, all axes valid (proves A16 metadata foundation is intact)
- Lockstep: 87/87 · Tree-health: 168/168 strict · Version-parity: 74/74 · Inventory parity: 6/6 — all GREEN

## 8. Lessons applied / new

- **Applied #15** — CI gateway-secret guard preserved; no workflow changes needed since Task A12's step already conditionally skips when secret absent.
- **Applied #34** — cache invalidation tree-wide is intentional + audited in §98 row; cross-referenced to expected A20 refresh window.
- **Applied #41** — A19 is purely the wiring layer; no contract changes leaked back into A17's surface, no cap refinements pre-empted A20's measurement.
- **NEW Lesson #42 — importlib-in-bash for internal-function self-tests.** When testing a Python script's internal pure functions (not the CLI surface) inside its `.sh` self-test, use `importlib.util.spec_from_file_location()` inside a `python3 -c` heredoc embedded in the bash test. This preserves the bash-test convention (Phase F3 addendum: new self-tests SHOULD be `.sh`) without introducing a separate sibling `.py` file. The Phase F3 addendum sanctioned `.py` siblings for internal-function testing; Lesson #42 demonstrates the bash-embedded alternative is viable and preferable when the test is short (3–8 lines of Python).

## 9. Next

**A20** — full-tree LLM rebaseline with Rubric v7. Expected lift on 03/12/17/25 from 75 → 85+ (audit-corpus + process-guidance multipliers dial down D2/D3 penalties that were Rubric v6's structural ceiling). Plan: run `audit-ai-implementability.py --strict --force`, snapshot the v7 baseline as `spec/17-consolidated-guidelines/35-full-tree-ai-audit-v7.md`, banner-supersede `34-…-v6.md`, update `mem://specs/full-tree-audit-v4`.

If A20 reveals any axis-cap miscalibration (e.g. `audit-corpus` modules unexpectedly capped at 95 when they should reach 100), open **A18** to refine `AXIS_CAPS`.
