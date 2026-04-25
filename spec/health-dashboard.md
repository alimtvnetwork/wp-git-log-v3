# Project-Wide Spec Health Dashboard

**Generated:** 2026-04-25  
**Total Files Scanned:** 656  
**Total Folders:** 80  
**Modules Audited:** 52  
**Overall Health:** 81/100 (B) — measured by `linter-scripts/check-tree-health.cjs`

> **v3.7.3 (2026-04-25):** Phase 2a complete — authored 14 missing `99-consistency-report.md` files. **Required files now 104/104 (100%).** Recommended files (97-AC + 98-changelog): 26/104 (25%). CI threshold raised to **80** to lock today's gains. Score trajectory: 45 → 71 → **81 (+36 from F baseline)**. Earlier today (v3.7.2) added `check-tree-health.cjs` CI gate that measures honest score (replaced narrative estimates).

---

## Health Score Breakdown

| Metric | Value |
|--------|-------|
| Score | **81/100 (B)** — measured |
| Required files (00-overview + 99-consistency) | **104/104 (100%)** ✅ |
| Recommended files (97-AC + 98-changelog) | 26/104 (25%) |
| Stale `spec-index.md` | 0 (auto-regen v3.7.1) |
| CI gate threshold | **80** (raised v3.7.3) |

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
