# Consistency Report — Spec Toolchain

**Version:** 1.0.0  
**Updated:** 2026-04-25

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
| 20-fill-missing-acceptance-criteria.md | ✅ | Filler |
| 21-fill-missing-changelogs.md | ✅ | Filler |
| 22-fill-missing-consistency-reports.md | ✅ | Filler |
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
| 70-spec-health-yml.md | ✅ | CI workflow |
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
| `linter-scripts/trace-map.toml` | [14](./14-generate-trace-map.md) | ✅ (data file consumed by §14) |
| `linter-scripts/fill-missing-acceptance-criteria.cjs` | [20](./20-fill-missing-acceptance-criteria.md) | ✅ |
| `linter-scripts/fill-missing-changelogs.cjs` | [21](./21-fill-missing-changelogs.md) | ✅ |
| `linter-scripts/fill-missing-consistency-reports.cjs` | [22](./22-fill-missing-consistency-reports.md) | ✅ |
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

**Bijection: 28/28 ✅** — every executable / configuration artifact has exactly one spec section.

**Subdirectory:** `linter-scripts/installer-templates/` is intentionally not specced here — it is a *content directory* not a *script*. If installer templates ever get their own conventions, add a sibling module (slot 28+) and reference from §00.

## Retired Slots

_None._

## Open Gaps

_None._
