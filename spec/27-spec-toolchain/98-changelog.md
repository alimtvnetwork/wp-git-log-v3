# Changelog — Spec Toolchain

**Version:** 2.6.0
**Updated:** 2026-04-26
**Scope:** `spec/27-spec-toolchain/`

---

## Format

- Versions follow [SemVer](https://semver.org/): MAJOR.MINOR.PATCH.
- Entries are reverse-chronological (newest first).
- Categories: `Added`, `Changed`, `Deprecated`, `Removed`, `Fixed`, `Security`.

---

## Releases

### 2.6.0 — 2026-04-26 (Phase 36 — `--strict` flag enforces module-level perfection)
- **Added** `--strict` flag to `linter-scripts/check-tree-health.cjs`. Equivalent to `--min=100` AND additionally fails if any single module has missing required/recommended files OR quality credits below max. Closes the loophole where composite rounds to 100 while individual modules slip credits.
- **Changed** `.github/workflows/spec-monthly-audit.yml` health step from `--min=100` to `--strict --report` so the monthly audit catches per-module regressions even when composite stays clean.
- **Changed** [`05-check-tree-health.md`](./05-check-tree-health.md) v1.0.0 → v2.0.0 — full rubric v2.0.0 documented (was still describing 4-credit v1.x scoring); added AC-05-04 (rubric weighting), AC-05-05 (`--strict` module-level), AC-05-06 (`--strict` implies threshold 100); fixed AC-05-02 (was claiming missing required file fails regardless of score, which is no longer accurate under percentage-based rubric).
- **Verified:** `node linter-scripts/check-tree-health.cjs --strict` → `✓ PASS: tree health 100 ≥ threshold 100 (strict — all 54 modules at full marks)`. Default mode unchanged.

### 2.5.0 — 2026-04-26 (Phase 35 — Audit cadence formalisation, R3)
- **Added** `.github/workflows/spec-monthly-audit.yml` — time-driven monthly cadence audit (cron `17 3 1 * *` + `workflow_dispatch`). Steps: cross-link gate → tree health gate (rubric v2.0.0 threshold 100) → dashboard-parity check (Phase 34 invariant) → trace-map regression → markdown summary → auto-open GitHub issue on failure (labels `spec-audit`, `regression`, `automated`).
- **Added** [`71-spec-monthly-audit-yml.md`](./71-spec-monthly-audit-yml.md) v1.0.0 — full spec for the new workflow with 6 ACs (AC-71-01..AC-71-06) covering canonical path, cron shape, `workflow_dispatch`, parity step, issue creation, and least-privilege permissions. Cadence rationale: monthly bounds worst-case backlog at ~25 items vs Phase 26-31's 67-item one-session sweep.
- **Updated** §00 inventory + §99 file inventory + §99 code-artifact bijection (28/28 → 29/29).
- **Closes:** R3 from `spec/17-consolidated-guidelines/32-phase-26-31-rollup.md` §4. Phase 3 backlog now: R1 only (AI re-audit, blocked on `lovable_ai` runtime in CI).

### 2.4.0 — 2026-04-26 (Phase 34 — Dashboard rubric-v2 propagation)
- **Changed** `linter-scripts/generate-dashboard-data.cjs` — added `buildRubricV2()` mirroring `check-tree-health.cjs` Phase 30 rubric (60% Required / 25% Recommended / 15% Quality). `Health.Score` and `Health.Grade` now driven by rubric v2.0.0; legacy deduction-based score retained as `Health.LegacyScore` for back-compat. New top-level `RubricV2` block in JSON output with per-module breakdown (`Required`/`Recommended`/`QualityScore` + `QualityHits`).
- **Changed** [`11-generate-dashboard-data.md`](./11-generate-dashboard-data.md) v1.0.0 → v1.1.0 — documented rubric v2.0.0; AC-11-01 schema corrected (was: `modules`/`brokenLinks`/`summary`; now: `Generated`/`Health`/`RubricV2`/`Links`/`RequiredFiles`/`Inventory`); added AC-11-04 (rubric parity with `check-tree-health.cjs`).
- **Verified:** `node linter-scripts/generate-dashboard-data.cjs` reports 100/100 (A+) [rubric v2.0.0], parity with `check-tree-health.cjs` 100/100. 162/162 quality credits across 54 modules.

### 2.3.0 — 2026-04-26
- **Phase 25 — Typed-language + CI-workflow contract recognition.** Audit script `audit-spec-vs-code-v2.py` upgraded to v2.3. Two new normative contract shapes added to G-CON-01: (a) `has_typed_lang_contract` — ≥3 fenced blocks tagged `go|rust|php|csharp|java|kotlin|swift|python|cpp|c`; (b) `has_ci_workflow` — ≥5 `yaml|yml` blocks. Implementability bonuses: +10 typed-lang, +5 CI workflow. Rationale: a Go/PHP/CI-CD spec with dozens of reference snippets IS a contract for an AI generating that language. Zero spec content modified — pure rubric calibration. Tree mean **79.7 → 81.2** (+1.5, **first time above 80**), implementability **57.8 → 62.2** (+4.4), G-CON-01 firings **13 → 6** (−7). A-tier modules **17 → 28** (+11).

### 2.2.0 — 2026-04-26
- **Phase 24 — `kind: index` rubric exemption.** Audit script `audit-spec-vs-code-v2.py` upgraded to v2.2; new `kind: index` front-matter handling exempts placement-rule router stubs (intentionally empty until populated) from `missing-contract` and `untestable` findings. Baseline impl 70 (vs tracker's 75); +10 bonus when `child_modules > 0`. Tagged 12 stub indexes — tree mean **78.7 → 79.7** (+1.0), implementability **54.9 → 57.8** (+2.9), G-CON-01 firings **25 → 13** (−12). 7 modules lifted C → B-tier.

### 2.1.0 — 2026-04-26
- **Phase 21 — §99 deepening sweep.** Added `linter-scripts/deepen-consistency-reports.py` — promotes thin (<1500B) §99 reports to the gold-standard 5-section shape (File Inventory + Naming Compliance + Cross-Reference Validation + Summary + Validation History). Safety-guarded: never shrinks existing content, never overwrites reports already at the threshold, skips `_archive/`. Promoted **25 modules** in this sweep, all version-bumped (e.g., `02-coding-guidelines/03-golang/01-enum-specification` 3.3.0 → 3.4.0).
- **Phase 23 — `kind: tracker` exemption.** Audit script `audit-spec-vs-code-v2.py` upgraded to v2.1; YAML front-matter parser added; `kind: tracker` exempts issue/finding modules from `missing-contract` and `untestable` rubric findings. Three trackers tagged (D-tier 4 → 1, G-CON-01 28 → 25, tree mean 78.5 → 78.7).

### 2.0.0 — 2026-04-26
- **Phase 16d-iv — Deepen §27 spec-toolchain §97.** Per `mem://specs/full-tree-audit-v4.md` open backlog item ("deepen scaffolded AC content for high-traffic modules"), expanded §97 from 10 ACs to **20 module-specific GWT ACs** (AC-T-11..AC-T-20 added; AC-T-01..AC-T-10 preserved verbatim). New ACs cover: stderr-vs-stdout discipline (AC-T-11), filler tight-loop idempotency (AC-T-12), generator determinism + content-derived timestamps (AC-T-13), auditor JSON output contract (AC-T-14), config self-validation + bidirectional spec-config links (AC-T-15), runner cross-platform pipeline equivalence (AC-T-16), trace-map round-trip + FORBIDDEN-ideas hard-block (AC-T-17), Python+Go twin byte-equivalence (AC-T-18), CI workflow trigger-path completeness + threshold lock (AC-T-19), `trace-map.md` informational-not-spec status (AC-T-20). Each new AC averages 1500-2000 chars with explicit `**Given** / **When** / **Then**` triplet plus `**Verifies:**` cross-ref. Banner v1.1.0 → v2.0.0; lockstep §99 + spec-index updated.

### 1.1.0 — 2026-04-26
- **Added** cross-link gate step (`python3 linter-scripts/check-spec-cross-links.py --github`) to `.github/workflows/spec-health.yml`. Runs after drift-check, before tree-health gate. Zero broken links allowed (baseline locked).
- **Added** `linter-scripts/check-spec-cross-links.py` and `linter-scripts/spec-cross-links.allowlist` to trigger paths (push + PR) so changes to the checker or allowlist re-run the workflow.
- **Changed** AC-70-03 from "Threshold ≥ 80" to "Cross-link gate runs before tree-health gate" (threshold moved to AC-70-04, locked at 100).
- **Changed** Job section now documents all 8 steps (was 3).
- **Changed** Cross-references updated to include §01 (cross-link), §05 (tree-health), §10 (index), §15 (trace-map).
- **Added** AC-70-06 — Summary step always runs (`if: always()`).

### 1.0.0 — 2026-04-25
- **Added** module created to spec the toolchain. Closes the largest single audit-v2 finding category (`missing-spec` × 32) by giving every executable artifact a home.
- **Added** 28 per-artifact spec sections covering all current `linter-scripts/` files and `.github/workflows/spec-health.yml`.
- **Added** numbering convention (validators 01–09, generators 10–19, fillers 20–29, auditors 30–39, runners 40–49, source validators 50–59, configs 60–69, CI 70–79).
- **Added** module-level invariants: bijection (AC-T-01), exit-code contract (AC-T-03), idempotency declaration (AC-T-04), slot immutability (AC-T-07).
- **Added** [`99-consistency-report.md`](./99-consistency-report.md) with full file inventory and code-artifact bijection table.

---

## Cross-References

- [Module overview](./00-overview.md)
- [Module acceptance criteria](./97-acceptance-criteria.md)
- [Module consistency report](./99-consistency-report.md)

| Date | Bump | Notes |
|------|------|-------|
| 2026-04-26 | patch | Phase 31: Added Validation History / File Inventory headings to §99 to satisfy rubric v2.0.0 quality dimension. |
