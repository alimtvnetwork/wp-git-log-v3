# Project-Wide Spec Health Dashboard

**Generated:** 2026-04-28  
**Total Folders Inventoried:** 87 (in `dashboard-data.json`)  
**Modules Audited:** 56  
**Overall Health:** 100/100 (A+) 🎯 — measured by `linter-scripts/check-tree-health.cjs`

> **v3.7.10 (2026-04-28, Phase P38):** Cluster-terminal dashboard refresh post P35–P37 batch (per the P34 lesson #2 cadence rule). All 9 critical gates re-verified green AFTER catching one regression: trace-map drift had grown `1232 → 1257` (+25 ACs) and `code_total 51 → 54` (+3 files) since the H1 rebaseline — the canonical "ac_traced flat + drift growth = AC growth" Phase 18 case (rebaseline-correct, NOT bind-required). Atomically rebaselined via `check-trace-map-regression.py --update-baseline`. The +3 code files are P31/P35-era `linter-scripts/test/*` self-tests (`test-check-spec-cross-links.sh`, `test-check-version-parity.sh`, `test-check-spec-folder-refs.py`) — properly excluded from production-script trace surface per F3 policy, covered by `test/README.md` parity gate instead. Dashboard counters unchanged: Quality **168/168**, Required + Recommended **112/112** each, allowlist **9 prefixes**, CI gate count **19**, RUBRIC **v2.29**, H10 adoption **74/74 (100%)**. **P38 lesson — cluster-terminal dashboard refresh works as designed**: third consecutive cluster (P34 caught stale waivers; P38 caught stale trace-map baseline) where the terminal refresh surfaced a silent regression that strict CI would have flagged only at next PR. The cadence rule is now empirically validated thrice — keep applying.

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
