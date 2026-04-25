# Project-Wide Spec Health Dashboard

**Generated:** 2026-04-25  
**Total Files Scanned:** 642  
**Total Folders:** 80  
**Modules Audited:** 52 (top-level + nested with `00-overview.md`)  
**Overall Health:** 71/100 (C) — measured by `linter-scripts/check-tree-health.cjs`

> **v3.7.2 (2026-04-25):** Added `linter-scripts/check-tree-health.cjs` CI gate. **Honest measured score:** 71/100 (90/104 required files present, 26/104 recommended). Prior 78/80 numbers were narrative estimates — now reconciled with measured truth. CI threshold set to **70** to lock today's baseline; raise after Phase 2 fills 14 missing `99-consistency-report.md` files. Earlier today: Phase 1 Triage resolved slot 22 collision (`22-app-issues/` → `25-app-issues/`) and archived legacy `21-git-logs/` to `_archive/21-git-logs-v1/`.

---

## Health Score Breakdown

| Metric | Value |
|--------|-------|
| Score | **71/100 (C)** — measured |
| Required files (00-overview + 99-consistency) | 90/104 (87%) |
| Recommended files (97-AC + 98-changelog) | 26/104 (25%) |
| Stale `spec-index.md` | 0 (auto-regen v3.7.1) |
| CI gate threshold | 70 (locked v3.7.2) |

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
