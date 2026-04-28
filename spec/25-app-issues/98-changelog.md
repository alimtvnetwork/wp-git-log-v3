# Changelog — App Issues

**Version:** 3.4.1  
**Updated:** 2026-04-28  
**Scope:** `spec/25-app-issues/`

---

### 1.2.0 — 2026-04-28 (Phase P11: nested-subdir audit)

- **Audited** the two nested child trackers (`01-phase-2-git-logs-audit/`, `02-consolidated-audit-findings/`) per the queued P11 task ("audit nested subdirs; spec or roll up"). **Disposition: SPEC IS COMPLETE — no roll-up required.**
- **Findings:** Both child trackers carry full §00/§97/§98/§99 governance; both are tagged `kind: tracker` (exempt from missing-contract / untestable rubric findings); both audit `spec/_archive/21-git-logs-v1/`; tree-health 168/168 strict-pass already covers them (all 56 modules at full marks per `mem://index.md` Core). The 02-consolidated tracker's "Correction notice" already declared supersession of 01 ("This document supersedes the Phase-2 audit … wherever they disagree").
- **Improvement landed (symmetry fix):** The supersession was previously declared only inside 02's "How to Use" section. Phase P11 surfaces it on **all three** routing endpoints: (1) parent `00-overview.md` Contents table now has a Status column with `superseded` / `active (start here)` labels + a Reading-order callout; (2) child `01-phase-2-git-logs-audit/00-overview.md` banner top-of-file gains explicit `Status: SUPERSEDED by ../02-consolidated-audit-findings/00-overview.md` with a quoted Phase P11 supersession-notice paragraph (replaces the misleading `Status: Open` that pre-dated the consolidation); (3) the existing 02 Correction notice is preserved unchanged. Future readers landing on any of the three entry points will be routed to 02 within one click.
- **Bumped** parent `00-overview.md` v3.3.0 → **v3.4.0**; child `01-phase-2-git-logs-audit/00-overview.md` v1.3.0 → **v1.4.0**; this changelog v1.1.0 → **v1.2.0**; parent §99 v1.0.0 → **v1.1.0**; child-01 §99 v1.3.0 → **v1.4.0**; child-01 §98 v1.2.0 → **v1.3.0**.
- **Scope discipline (Phase P11 ONLY):** No rewrite of audit findings (24 in 02, 25 in 01); no AC additions; no DDL/schema/enum/error-code change; no audit re-scoring. Pure routing + status-banner symmetry fix. The 02 tracker's substantive content is unchanged.
- **Verified:** `node linter-scripts/check-lockstep.cjs` ✅ 87/87; `node linter-scripts/check-tree-health.cjs --strict` ✅ 168/168.

---

## Format

- Versions follow [SemVer](https://semver.org/): MAJOR.MINOR.PATCH.
- Entries are reverse-chronological (newest first).
- Each entry lists: date (YYYY-MM-DD), version, change category, summary.
- Change categories: `Added`, `Changed`, `Deprecated`, `Removed`, `Fixed`, `Security`.

---

## Releases

### 3.4.1 — 2026-04-28 — Phase P30 batch reconciliation
- §98 header bumped to align with §00 banner; H10 stamp dropped on §00; date sweep `2026-04-27`→`2026-04-28`. Pure metadata patch — no module-rule change.

### 3.4.0 — 2026-04-28 — Phase P30 (P30 batch — dual-stream alignment)
- Reconciles §98 release stream with §00 banner stream (`3.4.0`). Prior §98 ladder ended at `2.0.0` (after promoting any post-footer prose) but §00 banner already tracked `3.4.0` from independent module-version stream (P25 subcase). Per P25 precedent, single alignment row added at §00 banner version; lockstep gate now satisfied.

### 2.0.0 — 2026-04-28 — promoted by Phase P30 (P30 batch — P28-style hybrid)
- Reconstructed from post-footer prose: `## 2026-04-27 — Phase 74 (evidenced index/tracker bonus)`. **Minor bump**: additive content (typed contracts, OpenAPI surface, Mermaid diagram, frontmatter, etc.) — no behavior change.

### 1.1.0 — 2026-04-26
- **Phase 24 — `kind: index` exemption.** Added YAML front-matter `kind: index` to `00-overview.md` to mark this module as a placement-rule router (intentionally empty / index-only). Audit script v2.2 honours the exemption, removing `missing-contract` and `untestable` rubric findings. Result: module lifted from C-tier to B-tier in the v2-deterministic audit.

### 1.0.0 — 2026-04-25
- **Added** baseline module structure (00-overview, 97-acceptance-criteria, 98-changelog, 99-consistency-report).
- **Added** module-specific files per current inventory in `99-consistency-report.md`.
- Auto-scaffolded by `linter-scripts/fill-missing-changelogs.cjs` as part of root v3.7.x Phase 2c sweep.

---

## Cross-References

- [Module overview](./00-overview.md)
- [Module acceptance criteria](./97-acceptance-criteria.md)
- [Module consistency report](./99-consistency-report.md)

| Date | Bump | Notes |
|------|------|-------|
| 2026-04-26 | minor | Phase 27c: Added `kind: future-spec` frontmatter + Drift Acknowledgment. Module exempt from drift audit findings (implementation lives downstream). |
