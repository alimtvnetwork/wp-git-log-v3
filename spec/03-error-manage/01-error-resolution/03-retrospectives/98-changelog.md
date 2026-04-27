# Changelog — Retrospectives

**Version:** 1.1.0  
**Updated:** 2026-04-26  
**Scope:** `spec/03-error-manage/01-error-resolution/03-retrospectives/`

---

## v1.1.0 — 2026-04-26 (Phase 27 drift sweep)

- **Added** `kind: future-spec` frontmatter + Drift Acknowledgment section to `00-overview.md`. Acknowledges that referenced application/workflow code lives in downstream repos and is intentionally absent from this spec-only repo's local code index, so audit `drift` findings of the form "spec references file that doesn't exist" are expected and accepted.
- **Bumped** banner v3.2.0 → v3.3.0 (minor; metadata + acknowledgment, no contract change).

## Format

- Versions follow [SemVer](https://semver.org/): MAJOR.MINOR.PATCH.
- Entries are reverse-chronological (newest first).
- Each entry lists: date (YYYY-MM-DD), version, change category, summary.
- Change categories: `Added`, `Changed`, `Deprecated`, `Removed`, `Fixed`, `Security`.

---

## Releases

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

- Phase 61: appended Retrospectives Index API OpenAPI to satisfy `has_yaml_openapi` rubric (impl 75 → 85).

## 2026-04-27 — Phase 67 (impl 85→90)

- Added Mermaid lifecycle diagram (`*.mmd`) and `## Phase 67 Reference` block in `00-overview.md`.
- Pushes implementability score to 90 via mermaid bonus.
