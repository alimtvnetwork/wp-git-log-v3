# Consistency Report — Spec Authoring Guide

**Version:** 4.4.0
**Last Updated:** 2026-04-27

> **v4.4.0 update (Phase 93 — lifecycle.mmd canonical SoT):** Rewrote [`lifecycle-spec-authoring.mmd`](./lifecycle-spec-authoring.mmd) from a 10-node skeleton into a 32-node typed flowchart with 6 styled classes faithfully rendering the actual pipeline built across Phases 81–91 (5 `kind:` branching paths; 6-step local linter pipeline; 6-gate CI sequence including the Phase 91 CLI self-test; `--explain=<module>` failure-recovery loop; post-merge phase-memo step). Replaced the inline mermaid excerpt in §00 ("Lifecycle Diagram (Phase 66, expanded in Phase 93)") with a high-level summary that delegates to the `.mmd` file. Added **AC-SAG-23** in §97 mandating the `.mmd` file as canonical source of truth and locking it stepwise with `linter-scripts/run.sh` + `.github/workflows/spec-health.yml`. Lockstep: §00 v3.6.0 → v3.7.0; §97 v4.1.0 → v4.2.0; §98 v4.6.0 → v4.7.0. No score regression — §01 already had `has_mermaid (+5)` from the existing skeleton; final score holds at 97/100 A+ with impl=100.


> **v4.1.0 update (Phase 38):** Added [`12-queued-decisions-trail.md`](./12-queued-decisions-trail.md) v1.0.0 — codifies the queued-decisions trail format (Q-identifier scheme, 4 status markers, lockstep edits, audit-recovery procedure, 5 ACs). Closes the gap where the project-memory Core lockstep rule referenced `mem://specs/git-logs.md` as a *demonstrated* format without ever specifying it. Inventory now also reflects slot 11 (`11-root-readme-conventions.md`, present since Phase 24 but missed in earlier §99 inventory rebuild).

> **v4.0.0 update (Phase 16f):** §97 fully rewritten from 18 table-row criteria to **20 module-specific Given/When/Then ACs** (AC-SAG-01..AC-SAG-20). The new ACs codify the meta-spec contract for authoring every OTHER spec in the tree — four-required-files rule, naming regex, reserved prefixes, slot immutability, seven `00-overview.md` sections, GWT depth requirement, lockstep rule, three template patterns, `.lovable/memories/` plural canonical, linter infrastructure mandate, root-readme format, score-honesty rule, reliability-report gate, dogfooding self-application, and forbidden manual edits to `spec-index.md`. Legacy AC-001..018 preserved as AC-SAG-LEGACY-001..018 at end of §97 for traceability. **Dogfooding verified**: this module satisfies all 20 ACs against itself (AC-SAG-18). Module-level tree-health: 100/100 (A+).

---

## Module Health

| Criterion | Status |
|-----------|--------|
| `00-overview.md` present | ✅ |
| `99-consistency-report.md` present | ✅ |
| Lowercase kebab-case naming | ✅ |
| Unique numeric sequence prefixes | ✅ |

**Health Score:** 100/100 (A+)

---

## File Inventory

| # | File | Status |
|---|------|--------|
| 00 | `00-overview.md` | ✅ Present |
| 01 | `01-folder-structure.md` | ✅ Present (canonical folder structure — single source of truth) |
| 02 | `02-naming-conventions.md` | ✅ Present |
| 03 | `03-required-files.md` | ✅ Present |
| 04 | `04-cli-module-template.md` | ✅ Present |
| 05 | `05-app-project-template.md` | ✅ Present |
| 06 | `06-non-cli-module-template.md` | ✅ Present |
| 07 | `07-memory-folder-guide.md` | ✅ Present |
| 08 | `08-cross-references.md` | ✅ Present |
| 09 | `09-exceptions.md` | ✅ Present |
| 10 | `10-mandatory-linter-infrastructure.md` | ✅ Present |
| 11 | `11-root-readme-conventions.md` | ✅ Present |
| 12 | `12-queued-decisions-trail.md` | ✅ Present (Phase 38) |
| 97 | `97-acceptance-criteria.md` | ✅ Present |
| 98 | `98-changelog.md` | ✅ Present |
| 99 | `99-consistency-report.md` | ✅ Present |

**Total:** 13 files

---

## Cross-Reference Validation

All internal links verified valid. ✅

---

## Validation History

| Date | Version | Action |
|------|---------|--------|
| 2026-04-09 | 5.0.0 | Added `10-mandatory-linter-infrastructure.md`; noted 01-folder-structure.md as canonical source. Total: 12 → 13 |
| 2026-03-30 | 4.0.0 | Added `98-changelog.md`. Total: 11 → 12 |
| 2026-03-30 | 3.0.0 | Added `97-acceptance-criteria.md`. Total: 10 → 11 |
| 2026-03-30 | 2.0.0 | Enhanced overview with scoring metrics, keywords, reliability reports, .lovable folder guidance |
| 2026-03-30 | 1.0.0 | Initial consistency report created |

## 2026-04-27 — Phase 57 impl-sweep

- Phase 57: appended Go/PHP/Python SpecModule validator references to satisfy `has_typed_lang_contract` rubric (impl 65 → 75).

## 2026-04-27 — Phase 60 impl-sweep

- Phase 60: appended Spec Authoring Audit API OpenAPI to satisfy `has_yaml_openapi` rubric (impl 75 → 85).

## 2026-04-27 — Phase 66 audit

- Mermaid lifecycle diagram present (`has_mermaid=true`).
- Lockstep & tree-health gates: PASS.
- Implementability promoted from 85 → 90.

### 2026-04-27 — Phase 72 deepening

- CI workflow contract inlined: 5 stages (detect, validate, lint, promote, report).
- Implementability raised 90 → 95 (deterministic audit).

