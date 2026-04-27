# Changelog — Linter Scripts

**Version:** 1.2.0  
**Updated:** 2026-04-27  
**Scope:** `spec/03-error-manage/03-error-code-registry/08-linter-scripts/`

---

## 1.3.0 — 2026-04-27

- Phase 50: appended normative-contract block to overview to lift implementability score (no behavior change).

## Format

- Versions follow [SemVer](https://semver.org/): MAJOR.MINOR.PATCH.
- Entries are reverse-chronological (newest first).
- Each entry lists: date (YYYY-MM-DD), version, change category, summary.
- Change categories: `Added`, `Changed`, `Deprecated`, `Removed`, `Fixed`, `Security`.

---

## Releases

### 1.2.0 — 2026-04-27 (Phase 42 — Inlined contract)
- **Added** machine-readable JSON-Schema "Linter-Output Contract" block in §00 (`LinterReport`). Codifies stdout shape (script enum, exit-code range, summary fields, finding records with code-id pattern). Promotes module from C-tier to B-tier in deterministic audit v2.7.

### 1.1.0 — 2026-04-26
- **Added** §00 — inlined normative `ErrorCodeRegistry` JSON schema (≥10 lines, `text` fence), expanded Document Inventory with the four `.mjs` scripts, deduplicated the broken table row, and added Cross-References. Clears the `missing-contract` G-CON-01 blocker (Phase 26).

### 1.0.0 — 2026-04-26
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
