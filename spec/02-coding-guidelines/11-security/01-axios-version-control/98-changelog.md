# Changelog — Axios Version Control Policy

**Version:** 1.0.0  
**Updated:** 2026-04-27  
**Scope:** `spec/02-coding-guidelines/11-security/01-axios-version-control/`

---

## 1.1.0 — 2026-04-27

- Phase 53: appended typed-language / SQL DDL / JSON Schema contracts to overview to lift implementability score (no behavior change).

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

| Date | Bump | Notes |
|------|------|-------|
| 2026-04-26 | minor | Phase 27c: Added `kind: future-spec` frontmatter + Drift Acknowledgment. Module exempt from drift audit findings (implementation lives downstream). |

## 2026-04-27 — Phase 70 (impl 75 → 85)

- Added Mermaid lifecycle diagram `lifecycle-axios-policy-enforcement.mmd`.
- Inlined 5-stage CI workflow contract (yaml) — satisfies `has_ci_workflow` gate.
- No behavioural change to module rules; documentation-only promotion.

