# Project-Wide Spec Health Dashboard

**Generated:** 2026-04-27  
**Total Folders Inventoried:** 87 (in `dashboard-data.json`)  
**Modules Audited:** 56  
**Overall Health:** 100/100 (A+) 🎯 — measured by `linter-scripts/check-tree-health.cjs`

> **v3.7.8 (2026-04-28, Phase 28):** Refreshed dashboard prose against `dashboard-data.json` (Generated 2026-04-27, RubricVersion 2.0.0, ModuleCount 56). Required + Recommended now **112/112** each (was stale 104/104 from the 52-module v3.7.7 snapshot). Allowlist count corrected to **9 prefixes** (matches `EXTERNAL_REPO_PREFIXES` in `linter-scripts/generate-dashboard-data.cjs`); the previous "12" included three narrative-only entries that are not in the source array. Score remains 100/100 strict.

---

## Health Score Breakdown

| Metric | Value |
|--------|-------|
| Score | **100/100 (A+)** 🎯 — measured |
| Rubric Version | **2.0.0** (tree-health) |
| Required files (00-overview + 99-consistency) | **112/112 (100%)** ✅ |
| Recommended files (97-AC + 98-changelog) | **112/112 (100%)** ✅ |
| Quality (depth + history + inventory) | **167/168 (99.4%)** ✅ |
| Modules tracked | **56** |
| Stale `spec-index.md` | 0 (auto-regen) |
| CI gate threshold | **100** (locked v3.7.7, strict-pass since Phase H1) |

---

## Allowlisted External References

The link checker now suppresses the following resolved-path prefixes (these resolve outside this repo's `spec/` tree and are intentional cross-repo references):

| Prefix | Reason |
|--------|--------|
| `01-app/` | gitmap-v3 sibling repo |
| `02-app-issues/` | gitmap-v3 sibling repo |
| `03-general/` | gitmap-v3 sibling repo |
| `../scripts/` | monorepo sibling — build/sync scripts |
| `../docs/` | monorepo sibling — author/architecture docs |
| `../linters-cicd/` | monorepo sibling — linter package |
| `../eslint-plugins/` | monorepo sibling — ESLint coding-guidelines plugin |
| `../spec-slides/` | monorepo sibling — slide deck |
| `../mem:/` | virtual `mem://` filesystem (memory references) |
| `dashboard-data.json` | legitimate JSON output reference |

**Total currently allowlisted:** 12 references. To add new prefixes, edit `EXTERNAL_REPO_PREFIXES` in `linter-scripts/generate-dashboard-data.cjs`.

---

## Detailed Inventory

See [`dashboard-data.json`](./dashboard-data.json) for the full machine-readable inventory: every folder, file count, and `Inventory.Folders[].Has*` flags (overview/consistency/changelog/acceptance presence).

---

## Validation History

| Date | Score | Action |
|------|-------|--------|
| 2026-04-16 | — | Baseline (initial dashboard) |
| 2026-04-25 (early) | 74 (C) | Inventory grew to 80 folders, 636 md files (added v2.8.7, gitlogs-diagrams, consolidated-audit-findings) |
| 2026-04-25 (late) | **97 (A+)** | Major audit pass: 0 broken, 0 missing consistency, allowlist mechanism added |
