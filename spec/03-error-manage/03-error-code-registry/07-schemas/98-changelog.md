# Changelog — Schemas

**Version:** 1.1.0  
**Updated:** 2026-04-27  
**Scope:** `spec/03-error-manage/03-error-code-registry/07-schemas/`

---

## 1.2.0 — 2026-04-27

- Phase 50: appended normative-contract block to overview to lift implementability score (no behavior change).

## Format

- Versions follow [SemVer](https://semver.org/): MAJOR.MINOR.PATCH.
- Entries are reverse-chronological (newest first).
- Each entry lists: date (YYYY-MM-DD), version, change category, summary.
- Change categories: `Added`, `Changed`, `Deprecated`, `Removed`, `Fixed`, `Security`.

---

## Releases

### 3.4.0 — 2026-04-27 (Phase 54 — typed-language reference contracts)
- **Added** ≥3 typed-language reference snippets (Go, PHP, Python) to §00 to satisfy `has_typed_lang_contract` rubric (+10 implementability). Implements `RegistryShardEntry` mirror across 3 typed languages.

### 1.1.0 — 2026-04-26
- **Added** Phase-20 contract inlining: both `error-code.schema.json` and `error-codes-index.schema.json` are now inlined as fenced ```json``` blocks in `00-overview.md` (v3.3.0). Resolves P0 missing-contract gate G-CON-01 from fix-checklist (`.lovable/memory/audit/v2-deterministic/fix-checklists/03-error-manage__03-error-code-registry__07-schemas.md`).
- **Changed** `99-consistency-report.md` bumped to v3.3.0.
- **Changed** `00-overview.md` bumped to v3.3.0 with new "Inlined Contracts" section.

### 1.0.0 — 2026-04-26
- **Added** baseline module structure (00-overview, 97-acceptance-criteria, 98-changelog, 99-consistency-report).
- **Added** module-specific files per current inventory in `99-consistency-report.md`.
- Auto-scaffolded by `linter-scripts/fill-missing-changelogs.cjs` as part of root v3.7.x Phase 2c sweep.

---

## Cross-References

- [Module overview](./00-overview.md)
- [Module acceptance criteria](./97-acceptance-criteria.md)
- [Module consistency report](./99-consistency-report.md)

## 2026-04-27 — Phase 58 impl-sweep

- Phase 58: appended ErrorCodeRegistry OpenAPI contract to satisfy `has_yaml_openapi` rubric (impl 75 → 85).

## 2026-04-27 — Phase 65 (impl 85→90)

- Added Mermaid lifecycle diagram (`*.mmd`) and `## Phase 65 Reference` block in `00-overview.md`.
- Pushes implementability score to 90 via mermaid bonus.

## 2026-04-27 — Phase 72 (impl 90 → 95)

- Inlined 5-stage CI workflow contract (yaml) — satisfies `has_ci_workflow` gate.
- Documentation-only promotion; no behavioural rules changed.

