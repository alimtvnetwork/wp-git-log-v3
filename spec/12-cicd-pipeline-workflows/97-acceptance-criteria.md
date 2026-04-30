# Acceptance Criteria — CI/CD Pipeline Workflows

**Version:** 1.1.0  
**Updated:** 2026-04-29 (Phase P48-1-fu1-batch P3 sweep: added `**Verifies:**` clauses to AC-01..AC-08 — closes 8/8 P3 gap, graduates AC-block from Medium → High AI-confidence.)  
**Scope:** `spec/12-cicd-pipeline-workflows/`

---

## Purpose

This document defines testable acceptance criteria for the **CI/CD Pipeline Workflows** module. Every criterion is verifiable from the module's content alone — an AI implementer or human reviewer can check pass/fail without external context.

---

## Criteria

### AC-01: Module entry point exists and is non-trivial
- **Given** the module folder `spec/12-cicd-pipeline-workflows/`
- **When** `00-overview.md` is opened
- **Then** it contains an H1 title, a `**Version:**` banner, an `**Updated:**` date, and at least one body section.
- **Verifies:** the structural-floor contract enforced by `check-tree-health.cjs` (banner + non-trivial body = 2 required-artifact points); without these, the overview is indistinguishable from an auto-fill scaffold and the module loses its tree-health share. Note: this module's `00-overview.md` declares `kind: index` (or sibling `kind: future-spec` / `kind: tracker`) in YAML front-matter, which exempts it from `missing-contract` (AC-06) but NOT from this structural floor.
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
- **Given** the module file `spec/12-cicd-pipeline-workflows/00-overview.md`
- **When** the file is read by `linter-scripts/audit-spec-vs-code-v2.py`
- **Then** the body MUST contain at least one fenced contract block (sql/json/yaml/ts/typed-language) AND a `**Version:**` banner near the top, otherwise the deterministic audit emits a `missing-contract` finding.
- **Verifies:** the rubric-v2.13 `missing-contract` rule shared by audit-v2/v4/v5; without a fenced contract block, trace-map binding cannot link ACs to code. Note: this module's `00-overview.md` declares `kind: index` (or sibling `kind: future-spec` / `kind: tracker`) in YAML front-matter, which exempts it from `missing-contract` (AC-06) but NOT from this structural floor.
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

### AC-09: Slot-collision disambiguation pin (Phase 153 audit-v6 close-out)  `[critical]`
- **Given** this module legitimately ships **slot-collision pairs** at numeric prefixes 01/02/04/05/06/07 — each slot has BOTH a root `.md` file AND a sibling subfolder, all six pairs predate the file-slot-immutability rule (`mem://index.md` Core: "File slots are immutable once shipped — never reuse a number") and are GRANDFATHERED by precedent: (a) `01-ci-pipeline.md` + `01-browser-extension-deploy/` + `01-shared-conventions.md`; (b) `02-release-pipeline.md` + `02-github-release-standard.md` + `02-go-binary-deploy/`; (c) `03-vulnerability-scanning.md` + `03-reusable-ci-guards/`; (d) `04-install-script-generation.md` + `04-installation-flow.md`; (e) `05-changelog-integration.md` + `05-code-signing.md`; (f) `06-self-update-mechanism.md` + `06-version-and-help.md`; (g) `07-environment-variable-setup.md` + `07-release-body-and-changelog.md`,
- **When** any LLM auditor or fresh implementer reads this module's Feature Inventory in `00-overview.md` AND the §99 File Inventory AND the §97 Module-Specific Files list,
- **Then** the implementer MUST treat slot collisions as **TOPIC PARTITIONS, NOT VERSION CONFLICTS** — each member of a colliding-slot pair owns a distinct topic axis (root `.md` = generic CI/CD pipeline contract that applies to any binary; subfolder = platform/target-specific binding e.g. browser-extension, Go-binary, reusable-guards). Cross-references citing `01-ci-pipeline.md` MUST resolve to the root file; cross-references citing `01-browser-extension-deploy/00-overview.md` MUST resolve to the subfolder. The implementer MUST NOT (1) treat any colliding pair as duplicates and merge them; (2) treat the subfolder as "shadowing" the root file (both are normative — the root is the generic contract, the subfolder is the binding); (3) re-author either side of a pair to the other slot (file-slot-immutability rule applies — moves require a new slot + §99 audit row per Phase 130 precedent); (4) rename root `.md` files to add a topic suffix (would break Phase H1+ retros + 100+ inbound cross-references). Path-resolution discipline: ALL cross-references from outside this module MUST use the explicit on-disk path including the `.md` extension OR the subfolder trailing slash — bare slot numbers (e.g. "see §01") are FORBIDDEN in this module's inbound-link contract because they are inherently ambiguous. Mirrors `spec/02 AC-CG-21` Subfolder Delegation Map (Lesson #21) and `spec/11 AC-10` asset-inventory pin (Lesson #29 extension): when a module's normative surface has a structural feature that LLM auditors and fresh implementers will misread by default (slot collision, non-`.md` assets, audit-corpus quoted evidence), the entry-point §97 MUST carry an explicit pin with line-anchored citations declaring the structure-meaning contract.
- **Source:** `00-overview.md` § "Feature Inventory"; this file § "Module-Specific Files" (lines 82–100); `99-consistency-report.md` § "File Inventory".
- **Verifies:** all six slot-collision pairs enumerated above (12 root + subfolder file paths + 3 subfolder overviews on disk 2026-04-29: `01-browser-extension-deploy/`, `02-go-binary-deploy/`, `03-reusable-ci-guards/`); `mem://index.md` Core § "File slots are immutable once shipped — never reuse a number; if content moves, rename the slot and add a §99 audit row (precedent: §16 → §37 in v2.8.6; Phase 130 caught a slot-32 collision pre-commit)" — this AC declares slot collisions IN THIS MODULE are pre-rule grandfathered exceptions (NOT new violations); future contributors MUST NOT add new colliding-slot pairs to ANY module. Codifies **Lesson #29 second extension** — audit-corpus pattern (originally for verbatim-quoted evidence in spec/25 post-mortem prose, AC-AI-09/10/11; first extended to non-`.md` assets in spec/11 AC-10) extends to **structural ambiguities** (slot collisions, multi-overview folders, parallel taxonomies) under the same auditor-misreads-by-default class. Future modules with structural ambiguities MUST add a structure-meaning pin AC.

---

## Module-Specific Files

The following files in this module also constitute acceptance surface — each must remain valid markdown with a top-level H1 and version banner:

- `00-overview.md`
- `01-ci-pipeline.md`
- `01-shared-conventions.md`
- `02-github-release-standard.md`
- `02-release-pipeline.md`
- `03-vulnerability-scanning.md`
- `04-install-script-generation.md`
- `04-installation-flow.md`
- `05-changelog-integration.md`
- `05-code-signing.md`
- `06-self-update-mechanism.md`
- `06-version-and-help.md`
- `07-environment-variable-setup.md`
- `07-release-body-and-changelog.md`
- `08-terminal-output-standards.md`
- `09-binary-icon-branding.md`
- `10-release-pipeline-issues-rca.md`
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
