# Changelog — Gitlogs Diagrams

**Version:** 1.1.0  
**Updated:** 2026-04-25  
**Scope:** `spec/26-gitlogs-diagrams/`

---

## Format

- Versions follow [SemVer](https://semver.org/): MAJOR.MINOR.PATCH.
- Entries are reverse-chronological (newest first).
- Each entry lists: date (YYYY-MM-DD), version, change category, summary.
- Change categories: `Added`, `Changed`, `Deprecated`, `Removed`, `Fixed`, `Security`.

---

## Releases

### 1.1.0 — 2026-04-25
- **Fixed** inventory drift: `00-overview.md` and `99-consistency-report.md` now list all 8 `.mmd` files plus `97`/`98`. Previously rows 07 (rate-limit) and 08 (encryption-v3) existed on disk but were undocumented, causing the v2 audit to false-flag them as missing.
- **Added** clickable relative links for every entry in the overview inventory.
- **Added** §99 cross-reference health and explicit "Open Gaps: none" closure.

### 1.0.0 — 2026-04-25
- **Added** baseline module structure (00-overview, 97-acceptance-criteria, 98-changelog, 99-consistency-report).
- **Added** module-specific files per current inventory in `99-consistency-report.md`.
- Auto-scaffolded by `linter-scripts/fill-missing-changelogs.cjs` as part of root v3.7.x Phase 2c sweep.

---

## Cross-References

- [Module overview](./00-overview.md)
- [Module acceptance criteria](./97-acceptance-criteria.md)
- [Module consistency report](./99-consistency-report.md)
