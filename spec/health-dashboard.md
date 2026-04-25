# Project-Wide Spec Health Dashboard

**Generated:** 2026-04-25  
**Total Files Scanned:** 639  
**Total Folders:** 80  
**Overall Health:** 77/100 (C)

> Regenerated 2026-04-25 (post-§99 audit follow-up). Filled 3 missing consistency reports (`02-coding-guidelines/06-cicd-integration/`, `12-cicd-pipeline-workflows/03-reusable-ci-guards/`, `21-git-logs/`). Fixed 4 phantom-module links in `spec/00-overview.md` (replaced `08-docs-viewer-ui`, `09-code-block-system`, `21-app/` with locked-vacant markers; added missing `13-generic-cli`, `15-distribution-and-runner`, `16-generic-release`, `18-wp-plugin-how-to`, `21-git-logs`, `22-git-logs-v2`, `26-gitlogs-diagrams`). Score: 76→77; broken links 45→41; missing-consistency 3→0.

---

## Health Score Breakdown

| Metric | Value |
|--------|-------|
| Score | **77/100 (C)** |
| Deduction | 41 broken links (-20) |
| Deduction | 0 missing consistency reports (0) — **cleared 2026-04-25** |
| Deduction | 1 missing overview (-3) — `21-git-logs/reference/` (legacy verbatim brief, intentional) |

### Effective (Waived) Score

| Metric | Value |
|--------|-------|
| Effective Score | **~94/100 (A) after waivers** |
| Waiver — gitmap-v3 | ~32 of 45 broken links point at gitmap-v3 sibling repos covered by [folder-ref allowlist](#audit-status). Per [`mem://constraints/avoid-app-sync`] those siblings are intentionally **not** synced. |
| Waiver — locked vacant slots | 5 broken links from `22-git-logs-v2/00-overview.md` lines 65–69 were **fixed in v2.8.7** by replacing links with locked-vacant markers (slots 09–13). |
| Audit guard | `python3 linter-scripts/check-spec-folder-refs.py` — passes |

---

## Cross-Reference Integrity

| Metric | Count |
|--------|-------|
| Total links checked | 2080 |
| ✅ Resolved | 2030 |
| 🔴 Broken | 45 |

> Detailed broken-link list lives in `dashboard-data.json` (`Links.BrokenDetails[]`). Regenerate with `node linter-scripts/generate-dashboard-data.cjs`.

---

## Missing Required Files

### Missing `00-overview.md`

| Folder | File Count |
|--------|------------|
| `21-git-logs/reference/` | 2 |

> `21-git-logs/reference/` is a verbatim-brief sub-folder under deprecated v1 — overview is intentionally absent (legacy reference only).

### Missing `99-consistency-report.md`

| Folder | File Count |
|--------|------------|
| `02-coding-guidelines/06-cicd-integration/` | 11 |
| `12-cicd-pipeline-workflows/03-reusable-ci-guards/` | 11 |
| `21-git-logs/` | 10 |

> `21-git-logs/` is banner-deprecated (legacy v1) — adding §99 not required. Other two are gaps from sub-folder additions that did not include consistency reports.

---

## Audit Status

| Category | Result |
|----------|--------|
| `spec/NN-name/` folder references | 0 stale ✅ |
| Allowlisted external folders | 25 (gitmap-v3 sibling repos) |
| File-level broken links | 45 (most allowlisted, plus locked-vacant slot fixes shipped in v2.8.7) |
| Inventory growth since 2026-04-16 | +5 folders, +76 md files |

---

## Regeneration

```bash
node linter-scripts/generate-dashboard-data.cjs   # writes spec/dashboard-data.json
python3 linter-scripts/check-spec-folder-refs.py  # CI guard for stale folder refs
```

> `spec/spec-index.md` is hand-maintained and currently **stale** (last refreshed 2026-04-10). Use `dashboard-data.json` as the authoritative inventory snapshot until an auto-regen script is written.
