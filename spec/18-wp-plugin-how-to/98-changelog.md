# Changelog — WordPress Plugin How-To — Overview

**Version:** 1.1.0  
**Updated:** 2026-04-29  
**Scope:** `spec/18-wp-plugin-how-to/`

---

## Format

- Versions follow [SemVer](https://semver.org/): MAJOR.MINOR.PATCH.
- Entries are reverse-chronological (newest first).
- Each entry lists: date (YYYY-MM-DD), version, change category, summary.
- Change categories: `Added`, `Changed`, `Deprecated`, `Removed`, `Fixed`, `Security`.

---

## Releases

### 1.1.0 — 2026-04-29 — Phase P48-1-fu1-batch slot 5 (P1 inventory sync)
- §00 Feature Inventory now lists `readme.md` and `changelog.md` (legacy root entry-points). Aligns §00 with §99 file-index (which already enumerated both). Pure inventory reconciliation — no spec rule changes. Linter: P1 driver eliminated for `spec/18`.

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
| 2026-04-26 | patch | Phase 31: Added Validation History / File Inventory headings to §99 to satisfy rubric v2.0.0 quality dimension. |
