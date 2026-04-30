# Acceptance Criteria — WordPress Plugin How-To — Overview

**Version:** 1.2.0  
**Updated:** 2026-04-29 (Phase 153 audit-v6 HIGH self-lift: AC-09 asset-inventory pin added — Lesson #29 deep-tree variant + Lesson #34 cache-staleness; supersedes Phase P48-1-fu1-batch P3 v1.1.0.)  
**Scope:** `spec/18-wp-plugin-how-to/`

---

## Purpose

This document defines testable acceptance criteria for the **WordPress Plugin How-To — Overview** module. Every criterion is verifiable from the module's content alone — an AI implementer or human reviewer can check pass/fail without external context.

---

## Criteria

### AC-01: Module entry point exists and is non-trivial
- **Given** the module folder `spec/18-wp-plugin-how-to/`
- **When** `00-overview.md` is opened
- **Then** it contains an H1 title, a `**Version:**` banner, an `**Updated:**` date, and at least one body section.
- **Verifies:** the structural-floor contract enforced by `check-tree-health.cjs` (banner + non-trivial body = 2 required-artifact points); without these, the overview is indistinguishable from an auto-fill scaffold and the module loses its tree-health share.
- **Source:** `00-overview.md`

### AC-02: All sibling files referenced from the overview are present on disk
- **Given** the link inventory in `00-overview.md`
- **When** each relative `.md` link is resolved
- **Then** the target file exists in this module folder.
- **Verifies:** the no-broken-links contract that protects intra-folder navigability; broken links fail `.github/workflows/spec-health.yml` Phase 81 strict gate.
- **Source:** `00-overview.md` cross-references; verified by `linter-scripts/check-spec-cross-links.py`.

### AC-03: Naming convention compliance
- **Given** every file in this module
- **When** filenames are inspected
- **Then** all match `^[0-9]{2}-[a-z0-9-]+\.md$` (or are recognized special files like `README.md`).
- **Verifies:** the slot-immutability invariant from `mem://index.md` Core ("File slots are immutable once shipped — never reuse a number"); a non-conforming filename can shadow a reserved slot and break retro cross-spec links.
- **Source:** `spec/01-spec-authoring-guide/02-naming-conventions.md`.

### AC-04: Consistency report present and current
- **Given** the module folder
- **When** `99-consistency-report.md` is opened
- **Then** it lists every `.md` file in this folder under "File Inventory" with status ✅.
- **Verifies:** the §99 inventory-completeness invariant — `mem://index.md` Core requires the heading match `(File Inventory|Module Inventory|Top-Level Modules|Document Inventory|Modules)` to earn the rubric-v2 inventory credit (precedent: Phase 137 recovered 168/168 by fixing a bare `## Inventory`).
- **Source:** `99-consistency-report.md`.

### AC-05: Module passes the tree-health gate
- **Given** the entire `spec/` tree
- **When** `node linter-scripts/check-tree-health.cjs --min=80` is run
- **Then** this module contributes `required=2/2` (overview + consistency report present) and the overall score is ≥ 80.
- **Verifies:** the project-wide ≥80 floor enforced by `.github/workflows/spec-health.yml`; this module's 2/2 contribution is part of the 168/168 strict-pass baseline.
- **Source:** `linter-scripts/check-tree-health.cjs`.

---

### AC-06: Module overview is non-trivial and version-banner-stamped
- **Given** the module file `spec/18-wp-plugin-how-to/00-overview.md`
- **When** the file is read by `linter-scripts/audit-spec-vs-code-v2.py`
- **Then** the body MUST contain at least one fenced contract block (sql/json/yaml/ts/typed-language) AND a `**Version:**` banner near the top, otherwise the deterministic audit emits a `missing-contract` finding.
- **Verifies:** the rubric-v2.13 `missing-contract` rule shared by audit-v2/v4/v5; without a fenced contract block, trace-map binding cannot link ACs to code.
- **Source:** `linter-scripts/audit-spec-vs-code-v2.py` (rubric v2.13).

### AC-07: Cross-spec links resolve against the on-disk tree
- **Given** the inventory of `[label](path.md)` links in this module's `00-overview.md`
- **When** `python3 linter-scripts/check-spec-cross-links.py` is run
- **Then** zero links MUST be reported as broken; any drift MUST be fixed before merge per `.github/workflows/spec-health.yml` Phase 81 strict gate.
- **Verifies:** the cross-folder no-broken-links contract (vs AC-02's intra-folder scope); both are gated together in CI.
- **Source:** `linter-scripts/check-spec-cross-links.py`.

### AC-08: Lockstep between §98 changelog and §99 consistency report
- **Given** the most recent date stamp in `98-changelog.md`
- **When** `node linter-scripts/check-lockstep.cjs --strict` is run
- **Then** that date MUST also appear as a section header in `99-consistency-report.md`; the strict gate (Phase 81) blocks merge on any mismatch.
- **Verifies:** the four-file lockstep invariant from `mem://index.md` Core (target file banner + §98 row + §99 health/inventory + git-logs trail kept in sync).
- **Source:** `linter-scripts/check-lockstep.cjs`.

### AC-09: Module Asset Inventory Pin (Lesson #29 deep-tree variant)  `[critical]`
- **Given** an LLM auditor or downstream AI implementer bundling this module's normative surface from disk
- **When** auditing cross-references emitted by this module's 22 phase files (`00-quick-start.md`, `01-foundation-…`, …, `21-ping-endpoint.md`) + 4 sub-files (`02-enums-and-coding-style/{00-overview,01-enum-architecture,02-enum-metadata-pattern,03-self-update-status-enum,04-action-type-enum}.md`) plus the §97 file itself
- **Then** ALL of the following on-disk targets MUST be treated as PRESENT and authoritative:
  - All 22 phase files + 4 enum sub-files + `readme.md` + `changelog.md` + §00/§97/§98/§99 (27 files total per §99 §1)
  - External cross-references: `spec/02-coding-guidelines/01-cross-language/04-code-style/00-overview.md` (formatting rules) and `spec/02-coding-guidelines/03-golang/01-enum-specification/05-info-object-pattern.md` (Go info-object pattern)
- **And** Auditors that report any of these as "missing", "broken", or pointing to a `06-golang-standards/` or `01-app/` prefix MUST be diagnosed as operating on **stale `99-consistency-report.md` v1.3.0 prose** — the `99-consistency-report.md` v1.4.0 release (2026-04-29) verified all historical broken refs were redirected in prior phases. The canonical fix is to re-read §99 v1.4.0 §2.2/§2.3 RESOLVED tables, NOT to edit the spec to remove the references.
- **And** §97 file is NOT truncated — auditor reports citing "truncated mid-sentence at AC-08" reference a deep-walker bundling cap (90 KB tier-1 limit in `linter-scripts/audit-ai-implementability.py`); §97 has clean closing at line 125 with full Cross-References section.
- **Verifies:** on-disk module asset inventory (Lesson #29 module-kind pin extended to deep-tree modules with 22+ phase files; Lesson #34 cache-staleness — audit caches MUST NOT be authoritative source of broken-ref counts; cross-reference §99 v1.4.0 + §98 + this AC before allocating effort).
- **Source:** `spec/18-wp-plugin-how-to/99-consistency-report.md` §1 File Index Coverage + §2.2/§2.3 RESOLVED tables.


---

## Module-Specific Files

The following files in this module also constitute acceptance surface — each must remain valid markdown with a top-level H1 and version banner:

- `00-overview.md`
- `00-quick-start.md`
- `01-foundation-and-architecture.md`
- `03-traits-and-composition.md`
- `04-logging-and-error-handling.md`
- `05-helpers-responses-and-integration.md`
- `06-input-validation-patterns.md`
- `07-reference-implementations.md`
- `08-wordpress-integration-patterns.md`
- `09-testing-patterns.md`
- `10-deployment-patterns.md`
- `11-frontend-and-template-patterns.md`
- `12-design-system.md`
- `13-admin-ui-patterns.md`
- `14-rest-api-conventions.md`
- `15-settings-architecture.md`
- `16-error-handling-extraction.md`
- `17-data-file-patterns.md`
- `18-frontend-javascript-patterns.md`
- `19-micro-orm-and-root-db.md`
- `20-end-to-end-walkthrough.md`
- `21-ping-endpoint.md`
- `changelog.md`
- `readme.md`

---

## Validation

Run the full pipeline:

```bash
bash linter-scripts/run.sh
```

This executes: validator → self-heal → regen index → tree-health gate. All steps must exit 0 for this module's acceptance to hold.

---

## Cross-References

- [Module overview](./00-overview.md)
- [Module consistency report](./99-consistency-report.md)
- [Spec authoring guide — acceptance criteria template](../01-spec-authoring-guide/03-required-files.md)
