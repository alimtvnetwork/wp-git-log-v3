# Consistency Report — Spec Toolchain

**Version:** 2.4.0
**Updated:** 2026-04-26

> **v2.4.0 update (Phase 37):** Added [`23-scaffold-spec-module.md`](./23-scaffold-spec-module.md) + `linter-scripts/scaffold-spec-module.cjs`. Emits a v2.0.0-rubric-compliant module skeleton (§00/§97/§98/§99) so newly created modules pass `check-tree-health.cjs --strict` on first run. 5 ACs (AC-23-01..05) covering strict-pass guarantee, slot-collision refusal, idempotency, slug/slot validation, and quality-credit anchor presence. Bijection 29/29 → 30/30. Companion to §20–§22 healers — prevents the next thin-§99 wave (cf. Phase 31 remediation).

> **v2.3.0 update (Phase 36):** Added `--strict` flag to `check-tree-health.cjs` (threshold 100 + fails on any single module below full marks). Wired into `spec-monthly-audit.yml`. [`05-check-tree-health.md`](./05-check-tree-health.md) v1.0.0 → v2.0.0 — rubric v2.0.0 fully documented (was still describing v1.x); 3 new ACs (AC-05-04..AC-05-06) for rubric weighting + `--strict` semantics; AC-05-02 corrected to match percentage-based scoring. Verified strict pass at 54/54 modules full marks.

> **v2.2.0 update (Phase 35 — R3):** Added [`71-spec-monthly-audit-yml.md`](./71-spec-monthly-audit-yml.md) + `.github/workflows/spec-monthly-audit.yml`. Time-driven monthly cadence companion to event-driven `spec-health.yml`. Includes dashboard-parity check (Phase 34 invariant) and auto-opens GitHub issue on regression. Bijection 28/28 → 29/29. Closes R3 from `32-phase-26-31-rollup.md` §4.

> **v2.1.0 update (Phase 34):** Propagated rubric v2.0.0 from `check-tree-health.cjs` into `generate-dashboard-data.cjs`. New top-level `RubricV2` block in `dashboard-data.json`; `Health.Score` now driven by rubric (legacy deduction-based score retained as `Health.LegacyScore`). [`11-generate-dashboard-data.md`](./11-generate-dashboard-data.md) v1.0.0 → v1.1.0; AC-11-01 schema corrected (was a 3-key shape that never matched the actual output); AC-11-04 added (parity with `check-tree-health.cjs`). Verified: dashboard reports 100/100 (A+), 162/162 quality credits, parity confirmed.

> **v2.0.0 update:** Phase 16d-iv deepened §97 from 10 ACs to **20 module-specific GWT ACs** (AC-T-11..AC-T-20 added; AC-T-01..AC-T-10 preserved). New ACs cover stderr discipline, filler tight-loop idempotency, generator determinism, auditor JSON contract, config self-validation, runner cross-platform equivalence, trace-map round-trip, twin byte-equivalence, CI trigger-path completeness, and `trace-map.md` informational status. Banner v1.1.0 → v2.0.0.

## File Inventory

| File | Present | Notes |
|------|---------|-------|
| 00-overview.md | ✅ | Inventory + numbering convention (v1.0.0) |
| 01-check-spec-cross-links.md | ✅ | Validator |
| 02-check-spec-folder-refs.md | ✅ | Validator |
| 03-check-forbidden-strings.md | ✅ | Validator |
| 04-check-forbidden-spec-paths.md | ✅ | Validator |
| 05-check-tree-health.md | ✅ | Validator (gate) |
| 06-check-root-readme.md | ✅ | Validator |
| 07-check-readme-canonicals.md | ✅ | Validator |
| 08-check-readme-install-section.md | ✅ | Validator |
| 09-check-memory-mirror-drift.md | ✅ | Validator |
| 10-generate-spec-index.md | ✅ | Generator |
| 11-generate-dashboard-data.md | ✅ | Generator |
| 12-suggest-spec-cross-link-fixes.md | ✅ | Generator (advisory + `--apply`) |
| 13-generate-gwt-acceptance.md | ✅ | Generator (AI-driven) |
| 14-generate-trace-map.md | ✅ | Generator (Spec ↔ Code trace map) |
| 15-generate-fix-checklist.md | ✅ | Generator (per-module fix checklist) |
| 16-generate-gate-report.md | ✅ | Generator (hard-gate cause report) |
| 20-fill-missing-acceptance-criteria.md | ✅ | Filler |
| 21-fill-missing-changelogs.md | ✅ | Filler |
| 22-fill-missing-consistency-reports.md | ✅ | Filler |
| 23-scaffold-spec-module.md | ✅ | Scaffolder (Phase 37) |
| 30-audit-spec-vs-code.md | ✅ | Auditor v1 |
| 31-audit-spec-vs-code-v2.md | ✅ | Auditor v2 |
| 40-run-sh.md | ✅ | Runner (bash) |
| 41-run-ps1.md | ✅ | Runner (powershell) |
| 50-validate-guidelines-py.md | ✅ | Source validator (Python) |
| 51-validate-guidelines-go.md | ✅ | Source validator (Go) |
| 52-check-axios-version.md | ✅ | Source validator |
| 60-forbidden-strings-toml.md | ✅ | Config |
| 61-spec-cross-links-allowlist.md | ✅ | Config |
| 62-spec-folder-refs-allowlist.md | ✅ | Config |
| 63-readme-cross-links-md.md | ✅ | Config |
| 70-spec-health-yml.md | ✅ | CI workflow (event-driven) |
| 71-spec-monthly-audit-yml.md | ✅ | CI workflow (cadence — Phase 35) |
| 97-acceptance-criteria.md | ✅ | AC-T-01..AC-T-10 |
| 98-changelog.md | ✅ | v1.0.0 |
| 99-consistency-report.md | ✅ | This file |

## Code-Artifact Bijection

| Code artifact | Spec section | Status |
|---------------|--------------|--------|
| `linter-scripts/check-spec-cross-links.py` | [01](./01-check-spec-cross-links.md) | ✅ |
| `linter-scripts/check-spec-folder-refs.py` | [02](./02-check-spec-folder-refs.md) | ✅ |
| `linter-scripts/check-forbidden-strings.py` | [03](./03-check-forbidden-strings.md) | ✅ |
| `linter-scripts/check-forbidden-spec-paths.sh` | [04](./04-check-forbidden-spec-paths.md) | ✅ |
| `linter-scripts/check-tree-health.cjs` | [05](./05-check-tree-health.md) | ✅ |
| `linter-scripts/check-root-readme.py` | [06](./06-check-root-readme.md) | ✅ |
| `linter-scripts/check-readme-canonicals.py` | [07](./07-check-readme-canonicals.md) | ✅ |
| `linter-scripts/check-readme-install-section.py` | [08](./08-check-readme-install-section.md) | ✅ |
| `linter-scripts/check-memory-mirror-drift.py` | [09](./09-check-memory-mirror-drift.md) | ✅ |
| `linter-scripts/generate-spec-index.cjs` | [10](./10-generate-spec-index.md) | ✅ |
| `linter-scripts/generate-dashboard-data.cjs` | [11](./11-generate-dashboard-data.md) | ✅ |
| `linter-scripts/suggest-spec-cross-link-fixes.py` | [12](./12-suggest-spec-cross-link-fixes.md) | ✅ |
| `linter-scripts/generate-gwt-acceptance.py` | [13](./13-generate-gwt-acceptance.md) | ✅ |
| `linter-scripts/generate-trace-map.py` | [14](./14-generate-trace-map.md) | ✅ |
| `linter-scripts/generate-fix-checklist.py` | [15](./15-generate-fix-checklist.md) | ✅ |
| `linter-scripts/generate-gate-report.py` | [16](./16-generate-gate-report.md) | ✅ |
| `linter-scripts/trace-map.toml` | [14](./14-generate-trace-map.md) | ✅ (data file consumed by §14) |
| `linter-scripts/fill-missing-acceptance-criteria.cjs` | [20](./20-fill-missing-acceptance-criteria.md) | ✅ |
| `linter-scripts/fill-missing-changelogs.cjs` | [21](./21-fill-missing-changelogs.md) | ✅ |
| `linter-scripts/fill-missing-consistency-reports.cjs` | [22](./22-fill-missing-consistency-reports.md) | ✅ |
| `linter-scripts/scaffold-spec-module.cjs` | [23](./23-scaffold-spec-module.md) | ✅ (Phase 37) |
| `linter-scripts/audit-spec-vs-code.py` | [30](./30-audit-spec-vs-code.md) | ✅ |
| `linter-scripts/audit-spec-vs-code-v2.py` | [31](./31-audit-spec-vs-code-v2.md) | ✅ |
| `linter-scripts/run.sh` | [40](./40-run-sh.md) | ✅ |
| `linter-scripts/run.ps1` | [41](./41-run-ps1.md) | ✅ |
| `linter-scripts/validate-guidelines.py` | [50](./50-validate-guidelines-py.md) | ✅ |
| `linter-scripts/validate-guidelines.go` | [51](./51-validate-guidelines-go.md) | ✅ |
| `linter-scripts/check-axios-version.sh` | [52](./52-check-axios-version.md) | ✅ |
| `linter-scripts/forbidden-strings.toml` | [60](./60-forbidden-strings-toml.md) | ✅ |
| `linter-scripts/spec-cross-links.allowlist` | [61](./61-spec-cross-links-allowlist.md) | ✅ |
| `linter-scripts/spec-folder-refs.allowlist` | [62](./62-spec-folder-refs-allowlist.md) | ✅ |
| `linter-scripts/readme-cross-links.md` | [63](./63-readme-cross-links-md.md) | ✅ |
| `.github/workflows/spec-health.yml` | [70](./70-spec-health-yml.md) | ✅ |
| `.github/workflows/spec-monthly-audit.yml` | [71](./71-spec-monthly-audit-yml.md) | ✅ (Phase 35) |

**Bijection: 30/30 ✅** — every executable / configuration artifact has exactly one spec section.

**Subdirectory:** `linter-scripts/installer-templates/` is intentionally not specced here — it is a *content directory* not a *script*. If installer templates ever get their own conventions, add a sibling module (slot 28+) and reference from §00.

## Retired Slots

_None._

## Open Gaps

_None._

---

## Validation History

| Date | Version | Action |
|------|---------|--------|
| 2026-04-26 | current | Phase 31: Added Validation History + heading-rubric alignment for `check-tree-health.cjs` v2.0.0 quality dimension. No content removed. |
| 2026-04-25 | prior | Tree-wide audit baseline established (45/100 → roadmap to 100). |
| 2026-04-20 | prior | Module brought into alignment with parent §99 conventions. |
| 2026-04-16 | prior | Initial consistency report authored. |

This module's full lockstep history is mirrored in `98-changelog.md`; entries
above summarize only the audit-/validation-bearing milestones for `27-spec-toolchain`.

