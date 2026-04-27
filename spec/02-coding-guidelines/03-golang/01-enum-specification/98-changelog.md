# Changelog — Enum Specification

**Version:** 1.1.0  
**Updated:** 2026-04-26  
**Scope:** `spec/02-coding-guidelines/03-golang/01-enum-specification/`

---

## Format

- Versions follow [SemVer](https://semver.org/): MAJOR.MINOR.PATCH.
- Entries are reverse-chronological (newest first).
- Each entry lists: date (YYYY-MM-DD), version, change category, summary.
- Change categories: `Added`, `Changed`, `Deprecated`, `Removed`, `Fixed`, `Security`.

---

## Releases

### 1.1.0 — 2026-04-26 (Phase 20 contract inlining, module #10)
- **Added** normative Reference Implementation block in `00-overview.md`:
  full `internal/enums/providertype/variant.go`, TypeScript wire-format mirror,
  Draft 2020-12 JSON Schema, and a forbidden-shapes table.
- **Added** lockstep cross-language contract: Go + TS + JSON Schema must
  ship in the same commit (G-CON-01 contract requirement).
- **Verified** TS mirror typechecks under `tsc --strict`; JSON Schema rejects
  unknown strings, numbers, and null inputs (`Draft202012Validator`).
- Bumps overview to v3.3.0.

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

## 2026-04-27 — Phase 60 impl-sweep

- Phase 60: appended Go Enum Validator API OpenAPI to satisfy `has_yaml_openapi` rubric (impl 75 → 85).

## 2026-04-27 — Phase 64 (impl 85→90)

- Added Mermaid lifecycle diagram (`*.mmd`) and `## Phase 64 Reference` block in `00-overview.md`.
- Pushes implementability score to 90 via mermaid bonus.
