# Changelog — Consolidated Guidelines

**Version:** 2.0.0
**Updated:** 2026-04-26
**Scope:** `spec/17-consolidated-guidelines/`

---

## Format

- Versions follow [SemVer](https://semver.org/): MAJOR.MINOR.PATCH.
- Entries are reverse-chronological (newest first).
- Each entry lists: date (YYYY-MM-DD), version, change category, summary.
- Change categories: `Added`, `Changed`, `Deprecated`, `Removed`, `Fixed`, `Security`.

---

## Releases

### 2.0.0 — 2026-04-26
- **Phase 16d-iii — Deepen §17 consolidated-guidelines §97.** Per `mem://specs/full-tree-audit-v4.md` open backlog item ("deepen scaffolded AC content for high-traffic modules"), expanded §97 from 5 generic scaffold ACs to **20 module-specific GWT ACs** (AC-06..AC-20 added; AC-01..AC-05 preserved verbatim). New ACs cover: standalone self-contained contract (AC-06), bidirectional mapping integrity (AC-07), blind-AI readiness scoring (AC-08), gap analysis currency (AC-09), linter inventory completeness (AC-10), linter authoring guide coverage (AC-11), folder-mapping matrix accuracy (AC-12), coverage heatmap truthfulness (AC-13), reverse index completeness (AC-14), README improvement tracking (AC-15), research file placement rules (AC-16), app file placement rules (AC-17), database convention consolidation (AC-18), design system consolidation (AC-19), WP plugin convention consolidation (AC-20). Each new AC averages 1500-2200 chars with explicit `**Given** / **When** / **Then**` triplet plus `**Verifies:**` cross-ref. Banner v1.1.0 → v2.0.0; lockstep §99 + spec-index updated.

### 1.1.0 — 2026-04-26
- **Phase 14 — Deepen Scaffolded ACs in §17 §97.** Per `mem://specs/full-tree-audit-v4.md` open backlog item ("deepen scaffolded AC content for high-traffic modules"), expanded the 4 shortest one-liner ACs in §97 from ~209-260 chars each to **1941-2254 chars each (8–10× depth)** with full Given/When/Then bodies + concrete cross-refs to linter scripts, regex specifics, and the slot-immutability precedent.
- **AC-01** Module entry point — exact 6-rule structural contract (H1 keyword check, ISO-8601 date, ≥1 H2 with body, no `TODO`/`TBD`/`FIXME` outside fenced code).
- **AC-02** Sibling links — 6-rule cross-link contract (real targets, no orphans, lowercase kebab, anchor resolution, slot-immutability prevents `../16-...` resolving, auto-fix proposals MUST be applied or suppressed).
- **AC-03** Naming convention — 6-rule regex contract with positive/negative examples (`02_coding.md` ❌, `02-Coding.md` ❌), `97`/`98`/`99` reserved-slot rule, slot-collision precedent (§22 → §25 in v3.7.0), exhaustive special-file allowlist.
- **AC-04** Consistency report — 7-rule freshness contract (auto-fill scaffold INSUFFICIENT alone, status-marker requirement, measured-not-narrated Health Score per `mem://index.md` Core rule, freshness-relative-to-siblings rule, version-≥-overview lockstep ordering).
- AC-05 already deep (1803 chars) — left as-is. AC count unchanged at 5.
- Banner v1.0.0 → v1.1.0; lockstep §98 + §99 + spec-index updated.

### 1.0.0 — 2026-04-25
- **Added** baseline module structure (00-overview, 97-acceptance-criteria, 98-changelog, 99-consistency-report).
- **Added** module-specific files per current inventory in `99-consistency-report.md`.
- Auto-scaffolded by `linter-scripts/fill-missing-changelogs.cjs` as part of root v3.7.x Phase 2c sweep.

---

## Cross-References

- [Module overview](./00-overview.md)
- [Module acceptance criteria](./97-acceptance-criteria.md)
- [Module consistency report](./99-consistency-report.md)
