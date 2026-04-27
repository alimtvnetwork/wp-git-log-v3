# Changelog: Static Analysis & Linter Enforcement

**Updated:** 2026-04-26


All notable changes to the `16-static-analysis/` subfolder.

---

## [4.0.0] — 2026-04-26

### Changed
- `97-acceptance-criteria.md` — **Phase 16p: full GWT rewrite.** Replaced 7 stub checkboxes (AC-SA-LEGACY-001..007) with 20 module-specific Given/When/Then ACs (AC-SA-01..AC-SA-20) covering: inheritance from AC-CL-* (AC-SA-01), 8-language file inventory with content requirements (AC-SA-02), identical universal thresholds across all languages (AC-SA-03), bidirectional SonarQube→linter mapping with 7-rule minimum (AC-SA-04), standardized integration checklist format (AC-SA-05), unified CI quality gate with parallel execution + single SARIF (AC-SA-06), machine-parseable 8×8 coverage matrix (AC-SA-07), TypeScript cross-reference validation (AC-SA-08), Keywords and Scoring sections (AC-SA-09), exemption/suppression syntax with before/after examples (AC-SA-10), CI pipeline parallel execution + both platform YAMLs (AC-SA-11), coverage matrix machine-parseability + badges (AC-SA-12), ≥4 native mappings per SonarQube rule (AC-SA-13), exact SemVer version pinning (AC-SA-14), copy-pasteable CI config snippets (AC-SA-15), self-application doctest gate (AC-SA-20). Banner v3.2.0 → v4.0.0.

## [3.2.0] — 2026-04-16

### Changed
- `97-acceptance-criteria.md` — banner v3.2.0 (stub checkboxes, 7 items).
- `99-consistency-report.md` — banner v3.2.0.

## [1.2.0] — 2026-04-01

### Added
- `10-cross-language-rule-matrix.md` — side-by-side SonarQube rule mapping across all 8 languages
- `97-acceptance-criteria.md` — acceptance criteria for the subfolder
- `98-changelog.md` — this file

## [1.1.0] — 2026-04-01

### Added
- `09-ci-pipeline-quality-gate.md` — unified CI pipeline spec with GitHub Actions and GitLab CI templates
- `99-consistency-report.md` — initial consistency report

### Changed
- All 8 language specs bumped to v1.1.0 — standardized Keywords/Scoring sections, integration checklist format, and added missing SonarQube rules (S1126, S4144)
- `00-overview.md` bumped to v1.1.0 — added CI pipeline to inventory

## [1.0.0] — 2026-03-31

### Added
- `00-overview.md` — subfolder overview with document inventory and rule mapping table
- `02-go-golangci-lint.md` — Go static analysis spec
- `03-php-phpcs-phpstan.md` — PHP static analysis spec
- `04-csharp-stylecop.md` — C# static analysis spec
- `05-rust-clippy.md` — Rust static analysis spec
- `06-vb-dotnet-analyzers.md` — VB.NET static analysis spec
- `07-nodejs-eslint.md` — Node.js static analysis spec
- `08-python-ruff.md` — Python static analysis spec

| Date | Bump | Notes |
|------|------|-------|
| 2026-04-26 | patch | Phase 27d: Added Drift Acknowledgment for low-severity doc-hygiene findings. |
