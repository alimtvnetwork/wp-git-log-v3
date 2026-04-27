# Changelog — App Issues

**Version:** 1.1.0  
**Updated:** 2026-04-27  
**Scope:** `spec/03-error-manage/01-error-resolution/app-issues/`

---

## 1.1.0 — 2026-04-27

- Phase 51: appended JSON Schema + typed enum contracts to overview to lift implementability score (no behavior change).

## Format

- Versions follow [SemVer](https://semver.org/): MAJOR.MINOR.PATCH.
- Entries are reverse-chronological (newest first).
- Each entry lists: date (YYYY-MM-DD), version, change category, summary.
- Change categories: `Added`, `Changed`, `Deprecated`, `Removed`, `Fixed`, `Security`.

---

## Releases

### 3.3.0 — 2026-04-27 (Phase 56 — typed-language reference)
- **Added** Added Python app-issue consumer (already had 1 Go + 1 PHP); brings typed-lang count to ≥3 → flips `has_typed_lang_contract` true (+10 impl).

### 1.0.0 — 2026-04-26
- **Added** baseline module structure (00-overview, 97-acceptance-criteria, 98-changelog, 99-consistency-report).
- **Added** module-specific files per current inventory in `99-consistency-report.md`.
- Auto-scaffolded by `linter-scripts/fill-missing-changelogs.cjs` as part of root v3.7.x Phase 2c sweep.

---

## Cross-References

- [Module overview](./00-overview.md)
- [Module acceptance criteria](./97-acceptance-criteria.md)
- [Module consistency report](./99-consistency-report.md)

## 2026-04-27 — Phase 61 impl-sweep

- Phase 61: appended App Issues Tracker API OpenAPI to satisfy `has_yaml_openapi` rubric (impl 75 → 85).
