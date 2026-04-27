# Spec Authoring Guide — Changelog

**Version:** 4.1.0
**Last Updated:** 2026-04-26

---

## [4.1.0] — 2026-04-26 (Phase 26: missing-contract remediation)

- **Added** §00 — inlined normative `SpecModule` JSON schema (≥10 lines, `text` fence) clearing the `missing-contract` G-CON-01 blocker. Module rises out of C-tier.
- **Bumped** §00 v3.2.0 → v3.3.0 (minor; new contract added, no breaking change).

---

## [4.0.0] — 2026-04-26 (Phase 16f: §97 full GWT rewrite)

- **Changed** §97 — full GWT rewrite. Replaced 18 table-row criteria (AC-001..AC-018) with **20 module-specific Given/When/Then ACs** (AC-SAG-01..AC-SAG-20) covering the meta-spec contract for authoring all OTHER specs: four-required-files rule, lowercase kebab-case + numeric-prefix regex, reserved-prefix discipline (00/97/98/99), slot immutability, seven mandatory `00-overview.md` sections, ≥ 5 GWT ACs per `97`, reverse-chronological SemVer-bumped `98`, ≤ 7-day stale rule on `99`, relative + `.md` cross-link rule, four-file lockstep on every spec edit, three template patterns (CLI / app+features / flat), 3+ files → subfolder `00-overview.md`, `.lovable/memories/` (plural) canonical memory folder, mandatory linter infrastructure presence, root readme hero+badges+§9 release-blocker format, AI-Confidence/Ambiguity score honesty rule, Reliability Risk Report mandate for Complex/E2E modules, dogfooding self-application, `bash linter-scripts/run.sh` exit-0 + tree-health ≥ 75 (locked at 100) gate, and forbidden manual edits to `spec-index.md`.
- **Preserved** legacy table-row criteria as AC-SAG-LEGACY-001..018 at end of §97 for traceability.
- **Bumped** §97 v3.2.0 → v4.0.0 (major; AC contract type changed from table-row to GWT). §98 v3.2.0 → v4.0.0. §99 v3.2.0 → v4.0.0.

---

## [2026-03-30] v2.0.0 Compliance Rollout

**Scope:** Project-wide sub-folder `00-overview.md` upgrade  
**Total files upgraded:** 139 sub-folder overviews + 2 new files created  
**Health impact:** 100% compliance with Spec Authoring Guide v2.0.0

### Summary

Upgraded all `00-overview.md` files across the entire spec tree to include the four mandatory sections introduced by the Spec Authoring Guide v2.0.0:

1. **AI Confidence** — metadata field (High for all modules)
2. **Ambiguity** — metadata field (None for all modules)
3. **Keywords** — searchable tags derived from module context
4. **Scoring** — standardized compliance table

### Phases

| Phase | Scope | Files |
|-------|-------|-------|
| 2026-04-26 | minor | Phase 30: Documented rubric v2.0.0 (Required 60% / Recommended 25% / §99 Quality 15%). Replaced Phase-27c drift note with concrete rubric versioning. |
| 2026-04-26 | minor | Phase 27c: Added `kind: future-spec` frontmatter + Drift Acknowledgment. Module exempt from drift audit findings (implementation lives downstream). |
| 1 | Module-level overviews (01–36, 99) | 31 |
| 2 | Module 01 sub-folders | 12 |
| 3 | Module 02 sub-folders | 60 |
| 4 | Module 03 sub-folders | 5 |
| 5 | Modules 04–36, 99, validation-reports | 62 |

### New Files Created

| File | Module |
|------|--------|
| `spec/02-coding-guidelines/05-rust/97-acceptance-criteria.md` | Rust Coding Standards |
| `spec/02-coding-guidelines/05-rust/99-consistency-report.md` | Rust Coding Standards |

### Method

- Phases 1–2: Manual per-file upgrades
- Phases 3–5: Automated Python script with keyword derivation and version bumping
- Post-processing: Keyword cleanup pass to remove path artifacts

### Verification

- Final scan confirmed 0 non-compliant `00-overview.md` files (excluding root dashboard)
- Parent consistency reports updated where applicable

---

## Cross-References

- [Spec Authoring Guide Overview](./00-overview.md)
- [Acceptance Criteria](./97-acceptance-criteria.md)
- [Consistency Report](./99-consistency-report.md)
