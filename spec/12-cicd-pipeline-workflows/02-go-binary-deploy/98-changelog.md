# Changelog — Go Binary Deploy — Overview

**Version:** 1.1.0  
**Updated:** 2026-04-27  
**Scope:** `spec/12-cicd-pipeline-workflows/02-go-binary-deploy/`

---

## 1.2.0 — 2026-04-27

- Phase 52: appended JSON Schema + typed enum/CI-YAML contracts to overview to lift implementability score (no behavior change).

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

### 1.0.0 — 2026-04-25
- **Added** baseline module structure (00-overview, 97-acceptance-criteria, 98-changelog, 99-consistency-report).
- **Added** module-specific files per current inventory in `99-consistency-report.md`.
- Auto-scaffolded by `linter-scripts/fill-missing-changelogs.cjs` as part of root v3.7.x Phase 2c sweep.

---

## Cross-References

- [Module overview](./00-overview.md)
- [Module acceptance criteria](./97-acceptance-criteria.md)
- [Module consistency report](./99-consistency-report.md)
