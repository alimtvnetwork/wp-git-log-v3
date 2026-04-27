# Changelog — Templates

**Version:** 1.2.0  
**Updated:** 2026-04-27  
**Scope:** `spec/03-error-manage/03-error-code-registry/09-templates/`

---

## 1.2.0 — 2026-04-27

- Phase 51: appended JSON Schema + typed enum contracts to overview to lift implementability score (no behavior change).

## Format

- Versions follow [SemVer](https://semver.org/): MAJOR.MINOR.PATCH.
- Entries are reverse-chronological (newest first).
- Each entry lists: date (YYYY-MM-DD), version, change category, summary.
- Change categories: `Added`, `Changed`, `Deprecated`, `Removed`, `Fixed`, `Security`.

---

## Releases

### 3.4.0 — 2026-04-27 (Phase 56 — typed-language reference)
- **Added** Added PHP + Python template renderers (already had 1 Go); brings typed-lang count to ≥3 → flips `has_typed_lang_contract` true (+10 impl).

### 1.1.0 — 2026-04-27 (Phase 42 — Inlined contract + cleanup)
- **Added** machine-readable JSON-Schema "Template Envelope" block in §00 (`ErrorCodeTemplate`). Codifies error-code regex, domain enum, severity enum, message/remediation min-lengths, and SemVer `since` field. Promotes module from C-tier to B-tier in deterministic audit v2.7.
- **Fixed** §00 Document Inventory had a duplicated table; collapsed to a single canonical inventory.
- **Bumped** §00 banner v3.2.0 → v3.3.0 (synchronized with new contract content).

### 1.0.0 — 2026-04-26
- **Added** baseline module structure (00-overview, 97-acceptance-criteria, 98-changelog, 99-consistency-report).
- **Added** module-specific files per current inventory in `99-consistency-report.md`.
- Auto-scaffolded by `linter-scripts/fill-missing-changelogs.cjs` as part of root v3.7.x Phase 2c sweep.

---

## Cross-References

- [Module overview](./00-overview.md)
- [Module acceptance criteria](./97-acceptance-criteria.md)
- [Module consistency report](./99-consistency-report.md)
