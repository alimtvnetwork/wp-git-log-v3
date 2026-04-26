# Acceptance Criteria — Consolidated Guidelines

**Version:** 1.0.0  
**Updated:** 2026-04-25  
**Scope:** `spec/17-consolidated-guidelines/`

---

## Purpose

This document defines testable acceptance criteria for the **Consolidated Guidelines** module. Every criterion is verifiable from the module's content alone — an AI implementer or human reviewer can check pass/fail without external context.

---

## Criteria

### AC-01: Module entry point exists and is non-trivial
- **Given** the module folder `spec/17-consolidated-guidelines/` (the canonical "table of contents" module that consolidates references to every other top-level numbered folder under `spec/`, currently 32 sibling files `00-overview.md` + `01-..` through `31-..` + `97`/`98`/`99`)
- **When** `00-overview.md` is opened and parsed as Markdown
- **Then** the file MUST satisfy ALL of the following structural rules: (a) the FIRST non-blank line MUST be an H1 heading (`# <title>`) — the title MUST mention "consolidated" or "guidelines" so a reader landing from the spec index immediately understands the module's role; (b) within the first 10 lines after the H1 there MUST be a `**Version:** X.Y.Z` banner where `X.Y.Z` is a valid SemVer triple — this banner MUST be present (the linter `linter-scripts/check-tree-health.cjs` counts it as a "required artifact"); (c) within the same first 10-line window there MUST be an `**Updated:** YYYY-MM-DD` banner using ISO-8601 calendar-date format — relative dates ("yesterday", "last week") and locale-specific formats (`DD/MM/YYYY`, `MM-DD-YYYY`) are FORBIDDEN because the tree-health audit sorts files by this date; (d) below the banners there MUST be at least one `## ` H2 section with at least one paragraph of body content — an empty file with only banners FAILS this AC because it provides no navigational value; (e) the file MUST NOT contain `TODO:`, `TBD`, or `FIXME` markers anywhere outside fenced code blocks (these signal unfinished spec work and the linter's `forbidden-strings.toml` blocks them per `linter-scripts/check-forbidden-strings.py`).
- **Source:** `00-overview.md` (the file under test); `linter-scripts/check-tree-health.cjs` (banner + non-trivial enforcement); `linter-scripts/check-forbidden-strings.py` + `linter-scripts/forbidden-strings.toml` (TODO/TBD/FIXME ban); `spec/01-spec-authoring-guide/03-required-files.md` (the canonical "what every module needs" reference).

### AC-02: All sibling files referenced from the overview are present on disk
- **Given** the link inventory in `00-overview.md`
- **When** each relative `.md` link is resolved
- **Then** the target file exists in this module folder.
- **Source:** `00-overview.md` cross-references; verified by `linter-scripts/check-spec-cross-links.py`.

### AC-03: Naming convention compliance
- **Given** every file in this module
- **When** filenames are inspected
- **Then** all match `^[0-9]{2}-[a-z0-9-]+\.md$` (or are recognized special files like `README.md`).
- **Source:** `spec/01-spec-authoring-guide/02-naming-conventions.md`.

### AC-04: Consistency report present and current
- **Given** the module folder
- **When** `99-consistency-report.md` is opened
- **Then** it lists every `.md` file in this folder under "File Inventory" with status ✅.
- **Source:** `99-consistency-report.md`.

### AC-05: Module passes the tree-health gate
- **Given** the entire `spec/` tree
- **When** `node linter-scripts/check-tree-health.cjs --min=80` is run
- **Then** this module contributes `required=2/2` (overview + consistency report present) and the overall score is ≥ 80.
- **Source:** `linter-scripts/check-tree-health.cjs`.

---

## Module-Specific Files

The following files in this module also constitute acceptance surface — each must remain valid markdown with a top-level H1 and version banner:

- `00-overview.md`
- `01-spec-authoring.md`
- `02-coding-guidelines.md`
- `03-error-management.md`
- `04-enum-standards.md`
- `05-split-db-architecture.md`
- `06-seedable-config.md`
- `07-design-system.md`
- `08-docs-viewer-ui.md`
- `09-code-block-system.md`
- `10-powershell-integration.md`
- `11-research.md`
- `12-root-research.md`
- `13-app.md`
- `14-app-issues.md`
- `15-cicd-pipeline-workflows.md`
- `16-app-design-system-and-ui.md`
- `17-self-update-app-update.md`
- `18-database-conventions.md`
- `19-gap-analysis.md`
- `20-wp-plugin-conventions.md`
- `21-lovable-folder-structure.md`
- `22-app-database.md`
- `23-generic-cli.md`
- `24-folder-mapping.md`
- `25-blind-ai-implementability-audit.md`
- `26-blind-ai-audit-v2.md`
- `27-linter-authoring-guide.md`
- `28-distribution-and-runner.md`
- `29-blind-ai-audit-v3.md`
- `30-readme-improvement-suggestions.md`
- `31-full-tree-ai-audit-v4.md`

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
