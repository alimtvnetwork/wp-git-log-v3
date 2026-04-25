# Project-Wide Spec Health Dashboard

**Generated:** 2026-04-25  
**Total Files Scanned:** 645  
**Total Folders:** 80  
**Overall Health:** 78/100 (B) — Phase 1 Triage executed

> **Honest baseline restored 2026-04-25 (v3.7.0).** Prior 100/100 was folder-scoped (folder 17 only). Full-tree v4 audit revealed 45/100 (F). Phase 1 Triage executed today: slot 22 collision resolved (`22-app-issues/` → `25-app-issues/`), legacy `21-git-logs/` archived to `_archive/21-git-logs-v1/`. **Score trajectory: 45 → 78 (+33).** Phase 2 (content fill: 13 missing AC files, 15 missing consistency reports) pending.

---

## Health Score Breakdown

| Metric | Value |
|--------|-------|
| Score | **78/100 (B)** |
| Deduction | -13 missing acceptance-criteria files |
| Deduction | -7 missing consistency reports (partial penalty) |
| Deduction | -2 stale `spec-index.md` |

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
