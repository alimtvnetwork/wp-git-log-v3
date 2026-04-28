# Project-Wide Spec Health Dashboard

**Generated:** 2026-04-28  
**Total Folders Inventoried:** 87 (in `dashboard-data.json`)  
**Modules Audited:** 56  
**Overall Health:** 100/100 (A+) 🎯 — measured by `linter-scripts/check-tree-health.cjs`

> **v3.7.11 (2026-04-28, Phase P43):** Cluster-terminal dashboard refresh post P39–P42 batch (per the P34 lesson #2 cadence rule, fourth empirical validation). All 9 critical gates re-verified green via the new P40 runner `bash linter-scripts/test/cluster-terminal-sweep.sh` — first cluster to use the runner as the SOLE verification surface (previously 9 individual `python3` / `bash` invocations). Runner output: 9 passed, 0 failed; trace-map clean (no rebaseline needed this cluster — P42 was a NO-OP audit, P41 was documentation-only, P40's runner self-validated on first run, P39's +1 AC was rebaselined inline). Dashboard counters unchanged: Quality **168/168**, Required + Recommended **112/112** each, allowlist **9 prefixes**, CI gate count **19**, RUBRIC **v2.29**, H10 adoption **74/74 (100%)**. **P43 lesson — runner adoption ≥ stale-snapshot risk reduction**: the P40 cluster-terminal runner replaces 9 manual gate invocations with 1 deterministic command, eliminating the "forgot to run gate N" failure mode that motivated the P34 cadence rule originally. The runner itself is now the cadence rule's mechanical enforcement; the rule graduates from procedure-only (mem-index Core) to procedure + canonical mechanism. Future cluster-terminals SHOULD invoke the runner as a single tool call.

---

## Health Score Breakdown

| Metric | Value |
|--------|-------|
| Score | **100/100 (A+)** 🎯 — measured |
| Rubric Version | **2.0.0** (tree-health) |
| Required files (00-overview + 99-consistency) | **112/112 (100%)** ✅ |
| Recommended files (97-AC + 98-changelog) | **112/112 (100%)** ✅ |
| Quality (depth + history + inventory) | **168/168 (100%)** ✅ |
| Modules tracked | **56** |
| Stale `spec-index.md` | 0 (auto-regen + Phase 30 strict gate) |
| CI gate threshold | **100** (locked v3.7.7, strict-pass since Phase H1) |
| **CI strict gate count** | **19** (last bumps: Phase 30 spec-index drift; Phase P31 H10 strict-flip) |
| **Audit RUBRIC_VERSION** | **v2.29** (last bump: Phase P31) |
| **H10 version-parity adoption** | **74/74 stamped (100%)**, 0 mismatches, 0 stamped_failed — strict under CI |

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

**Total currently allowlisted:** 9 prefixes (matches `EXTERNAL_REPO_PREFIXES` in `linter-scripts/generate-dashboard-data.cjs`). To add new prefixes, edit that array. Per-link waivers (with `<relpath>:<line>:<target>` keys) live in `linter-scripts/spec-cross-links.allowlist`; **line numbers MUST be refreshed whenever surrounding lines are inserted/removed** (precedent: P34 fixed two off-by-+1 stale waivers introduced by the P22/P32 H10-stamp comment insertions).

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
| 2026-04-27 | **100 (A+)** | Strict-pass baseline; 56 modules, 112/112 required+recommended, quality 167/168 (Phase H1 closeout) |
| 2026-04-28 | **100 (A+)** | Phase 28 — refreshed dashboard prose vs `dashboard-data.json` (was 52→56 modules, 104→112 files, allowlist 12→9 corrected) |
| 2026-04-28 | **100 (A+)** | Phase P34 — quality 167→168/168 (H8/H9 closure), CI gate count 17→19 (P30 spec-index strict + P31 H10 strict-flip), RUBRIC v2.29, H10 adoption 74/74 (100%); fixed 2 stale cross-link allowlist line numbers (P22/P32 stamp-insertion drift) |
