# Project-Wide Spec Health Dashboard

**Generated:** 2026-04-25  
**Total Files Scanned:** 643  
**Total Folders:** 80  
**Overall Health:** 97/100 (A+)

> Major audit pass 2026-04-25: filled 3 missing `99-consistency-report.md` files, fixed 30+ phantom-module/dead-link references in `00-overview.md`, `21-git-logs/00-overview.md`, `22-app-issues/02-consolidated-audit-findings/`, `22-git-logs-v2/00-overview.md`, and 3 cross-folder app refs. Added cross-repo allowlist mechanism to `linter-scripts/generate-dashboard-data.cjs` for sibling-repo (`gitmap-v3`, monorepo siblings) references that resolve outside this tree. **Score: 74 → 97 (+23). Broken links: 45 → 0.**

---

## Health Score Breakdown

| Metric | Value |
|--------|-------|
| Score | **97/100 (A+)** |
| Deduction | 0 broken links (0) — **cleared 2026-04-25** |
| Deduction | 0 missing consistency reports (0) — **cleared 2026-04-25** |
| Deduction | 1 missing overview (-3) — `21-git-logs/reference/` (legacy verbatim brief, intentional) |

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
