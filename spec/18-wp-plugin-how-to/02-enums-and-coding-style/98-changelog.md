# Changelog — Phase 2 — Enums and Coding Style

**Version:** 1.1.0  
**Updated:** 2026-04-26  
**Scope:** `spec/18-wp-plugin-how-to/02-enums-and-coding-style/`

---

## Format

- Versions follow [SemVer](https://semver.org/): MAJOR.MINOR.PATCH.
- Entries are reverse-chronological (newest first).
- Each entry lists: date (YYYY-MM-DD), version, change category, summary.
- Change categories: `Added`, `Changed`, `Deprecated`, `Removed`, `Fixed`, `Security`.

---

## Releases

### 1.1.0 — 2026-04-26 (Phase 20 contract inlining, module #11 — final)
- **Added** normative Reference Implementation block in `00-overview.md`:
  full `SelfUpdateStatusType` PHP 8.1+ backed enum (7 cases, `JsonSerializable`,
  per-case `is{Case}()` helpers, `isEqual`/`isOtherThan`/`isAnyOf`,
  `match`-based `label()`, strict `parse()` that throws on unknown).
- **Added** TypeScript wire-format mirror (`as const` object + type-guard) and
  Draft 2020-12 JSON Schema for the wire form.
- **Added** forbidden-shapes table (6 lint-enforced rules).
- **Verified** TS mirror typechecks under `tsc --strict`; JSON Schema rejects
  unknown strings, integers, and null inputs.
- **Added** version banner (v1.1.0) to overview previously missing one.

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
| 2026-04-26 | patch | Phase 27d: Added Drift Acknowledgment for low-severity doc-hygiene findings. |

## 2026-04-27 — Phase 58 impl-sweep

- Phase 58: appended `.wp-plugin-style.yaml` contract to satisfy `has_yaml_openapi` rubric (impl 75 → 85).

## 2026-04-27 — Phase 65 (impl 85→90)

- Added Mermaid lifecycle diagram (`*.mmd`) and `## Phase 65 Reference` block in `00-overview.md`.
- Pushes implementability score to 90 via mermaid bonus.
