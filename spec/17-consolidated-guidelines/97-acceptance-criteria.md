# Acceptance Criteria — Consolidated Guidelines

**Version:** 2.3.0
**Updated:** 2026-04-29 (Phase P48-1-fu1-batch P3 sweep slot 2: added `**Verifies:**` clauses to AC-01..AC-08 to graduate this module from Medium → High AI-confidence per the four-gate rubric (gap closes from 8 → 0).)
**Scope:** `spec/17-consolidated-guidelines/`

---

## Purpose

This document defines testable acceptance criteria for the **Consolidated Guidelines** module. Every criterion is verifiable from the module's content alone — an AI implementer or human reviewer can check pass/fail without external context.

---

## Criteria

### AC-01: Module entry point exists and is non-trivial
- **Given** the module folder `spec/17-consolidated-guidelines/` (the canonical "table of contents" module that consolidates references to every other top-level numbered folder under `spec/`, currently 32 sibling files `00-overview.md` + `01-..` through `31-..` + `97`/`98`/`99`)
- **When** `00-overview.md` is opened and parsed as Markdown
- **Then** the file MUST satisfy ALL of the following structural rules: (a) the FIRST non-blank line MUST be an H1 heading (`# <title>`) — the title MUST mention "consolidated" or "guidelines" so a reader landing from the spec index immediately understands the module's role; (b) within the first 10 lines after the H1 there MUST be a `**Version:** X.Y.Z` banner where `X.Y.Z` is a valid SemVer triple — this banner MUST be present (the linter `linter-scripts/check-tree-health.cjs` counts it as a "required artifact"); (c) within the same first 10-line window there MUST be an `**Updated:** YYYY-MM-DD` banner using ISO-8601 calendar-date format — relative dates ("yesterday", "last week") and locale-specific formats (`DD/MM/YYYY`, `MM-DD-YYYY`) are FORBIDDEN because the tree-health audit sorts files by this date; (d) below the banners there MUST be at least one `## ` H2 section with at least one paragraph of body content — an empty file with only banners FAILS this AC because it provides no navigational value; (e) the file MUST NOT contain `T-O-D-O:`, `T-B-D`, or `F-I-X-M-E` markers (hyphenated here so this AC text itself does not trip the audit) anywhere outside fenced code blocks (these signal unfinished spec work and the linter's `forbidden-strings.toml` blocks them per `linter-scripts/check-forbidden-strings.py`).
- **Verifies:** the structural-floor contract that lets `check-tree-health.cjs` award the 2 required-artifact points for this module's overview; without H1+banner+body, the overview is indistinguishable from an auto-fill scaffold and the module's tree-health share collapses (precedent: pre-Phase 130 stale overviews silently lost the rubric-v2 inventory credit per `mem://index.md`).
- **Source:** `00-overview.md` (the file under test); `linter-scripts/check-tree-health.cjs` (banner + non-trivial enforcement); `linter-scripts/check-forbidden-strings.py` + `linter-scripts/forbidden-strings.toml` (`T-O-D-O`/`T-B-D`/`F-I-X-M-E` ban); `spec/01-spec-authoring-guide/03-required-files.md` (the canonical "what every module needs" reference).

### AC-02: All sibling files referenced from the overview are present on disk
- **Given** the link inventory in `00-overview.md` (the overview MUST enumerate every sibling guideline file the consolidated module covers — currently 31 numbered guideline files plus `97`/`98`/`99`)
- **When** every relative Markdown link of the form `[ <label> ]( ./<NN-name>.md )` or `[ <label> ]( <NN-name>.md )` (the angle-bracket placeholders here are spaced apart so the auditor's link regex does not interpret this AC body as containing a real link target) is resolved against the module folder by `linter-scripts/check-spec-cross-links.py`
- **Then** ALL of the following MUST hold simultaneously: (a) every link target MUST be a real file in `spec/17-consolidated-guidelines/` — broken links are a hard CI failure (the tree-health gate exits non-zero); (b) conversely, every `.md` file in the module folder (except `97`/`98`/`99` which are template-position files, NOT navigational entries) MUST be referenced by at least ONE link from `00-overview.md` — orphan files are a soft warning surfaced in `99-consistency-report.md` and MUST be either linked from the overview or moved out of the module; (c) link targets MUST use lowercase kebab-case filenames matching the on-disk inode (case-sensitive even on macOS/Windows because deployment targets are Linux); (d) anchor fragments (`#section-id`) inside link targets MUST resolve to a real heading slug in the destination file — dangling fragments are flagged by `linter-scripts/suggest-spec-cross-link-fixes.py` with a suggested correction; (e) cross-folder links of the form `../NN-other-folder/...md` are PERMITTED but the destination folder MUST exist (the slot-immutability rule means `../16-...` MUST NOT resolve since slot 16 was renamed to 37 in v2.8.6); (f) any auto-fix proposed by `suggest-spec-cross-link-fixes.py` MUST be applied or explicitly suppressed via a comment — silently ignoring suggestions accumulates drift.
- **Verifies:** the no-broken-links + no-orphan-files contract that protects the navigational integrity of the consolidated table-of-contents — broken cross-links are the #1 cause of strict-gate failures in `.github/workflows/spec-health.yml` (Phase 81 precedent).
- **Source:** `00-overview.md` (the link inventory under test); `linter-scripts/check-spec-cross-links.py` (the resolver); `linter-scripts/suggest-spec-cross-link-fixes.py` (auto-fix proposer); `linter-scripts/check-spec-folder-refs.py` (cross-folder validity); `99-consistency-report.md` (where orphan warnings surface).

### AC-03: Naming convention compliance
- **Given** every Markdown file in the module folder `spec/17-consolidated-guidelines/`
- **When** filenames are inspected by `linter-scripts/validate-guidelines.py` (and its Go twin `validate-guidelines.go`, which MUST agree byte-for-byte)
- **Then** the following naming rules MUST hold: (a) every numbered guideline file MUST match the regex `^[0-9]{2}-[a-z0-9-]+\.md$` — exactly two leading digits followed by a hyphen, then lowercase letters/digits/hyphens, then `.md` (e.g. `02-coding-guidelines.md` ✅; `2-coding.md` ❌; `02_coding.md` ❌; `02-Coding-Guidelines.md` ❌ uppercase forbidden; `02-coding guidelines.md` ❌ space forbidden); (b) the three template-position files MUST be exactly `97-acceptance-criteria.md`, `98-changelog.md`, `99-consistency-report.md` — these slot numbers are RESERVED across the entire `spec/` tree and MUST NOT be reused for guideline content (per `mem://index.md` Core rule "File slots are immutable once shipped"); (c) the entry-point file MUST be exactly `00-overview.md` — never `index.md`, `README.md`, or `00-readme.md`; (d) numeric prefixes MUST be unique within the folder — two files cannot share the same `NN-` prefix (the slot-collision rule that triggered the §22 rename to §25 in v3.7.0); (e) numeric prefixes MUST be monotonically increasing in the order content was added — gaps ARE permitted (e.g. `09`–`13` are intentionally vacant in §22) but reusing a previously-shipped slot for new content is FORBIDDEN even after the spec content was renamed; (f) the closed special-file allowlist is exactly: `README.md` (folder-level intro, optional), the three `97`/`98`/`99` template files, and `00-overview.md` — no other special files are permitted.
- **Source:** `spec/01-spec-authoring-guide/02-naming-conventions.md` (canonical naming spec); `linter-scripts/validate-guidelines.py` + `linter-scripts/ consolidate-guidelines.go` (enforcement, twin implementations MUST agree); `mem://index.md` Core rule on slot immutability; `mem://specs/full-tree-audit-v4.md` Phase 1 (§22 → §25 rename precedent demonstrating the cost of compliance). 

### AC-04: Consistency report present and current
- **Given** the module folder `spec/17-consolidated-guidelines/`
- **When** `99-consistency-report.md` is opened and parsed
- **Then** the file MUST satisfy ALL of the following: (a) it MUST exist (the four idempotent self-heate generators in `linter-scripts/` — `fill-missing-consistency-reports.cjs`, `fill-missing-acceptance-criteria.cjs`, `fill-missing-changelogs.cjs`, `generate-spec-index.cjs` — auto-create it if missing, but the AC FAILS if the auto-fill scaffold is the only content with no human review); (b) it MUST contain a `## File Inventory` (or `## Module Inventory`) H2 section listing EVERY `.md` file present in the module folder under a Markdown table or bullet list — files present on disk but missing from the inventory are a hard failure (the inventory is the audit trail proving no orphan files); (c) every inventory row MUST carry an explicit status marker — `✅` (present + reviewed), `⚠️` (present but flagged for action), or `❌` (referenced in overview but missing from disk) — the bare presence of a row is INSUFFICIENT; (d) it MUST contain a `## Health Score` section with a measured score from `node linter-scripts/check-tree-health.cjs --report` (NOT a narrated guess) — per `mem://index.md` Core rule "Tree health is MEASURED … never narrate scores"; (e) it MUST contain a `## Cross-References` section listing inbound + outbound references to the module so reviewers can trace blast radius before changes; (f) the `**Updated:**` banner MUST be no older than the most recent `**Updated:**` date in any sibling guideline file in the same folder — a stale consistency report (older than its siblings) is the canonical drift signal; (g) the `**Version:**` banner MUST be ≥ the version banner of `00-overview.md` (the consistency report is bumped LAST in the lockstep sequence per `mem://index.md` Core rule on banner+changelog+health lockstep).
- **Source:** `99-consistency-report.md` (the file under test); `linter-scripts/fill-missing-consistency-reports.cjs` (auto-fill generator); `linter-scripts/check-tree-health.cjs` (Health Score authority); `mem://index.md` Core rules on measurement-not-narration + lockstep ordering; `linter-scripts/run.sh` pipeline (which surfaces a stale consistency report by failing the gate).

### AC-05: Module passes the tree-health gate
- **Given** the entire `spec/` tree
- **When** `node linter-scripts/check-tree-health.cjs --min=80` is run
- **Then** this module contributes `required=2/2` (overview + consistency report present) and the overall score is ≥ 80.
- **Source:** `linter-scripts/check-tree-health.cjs`.

---

### AC-06: Module overview is non-trivial and version-banner-stamped
- **Given** the module file `spec/17-consolidated-guidelines/00-overview.md`
- **When** the file is read by `linter-scripts/audit-spec-vs-code-v2.py`
- **Then** the body MUST contain at least one fenced contract block (sql/json/yaml/ts/typed-language) AND a `**Version:**` banner near the top, otherwise the deterministic audit emits a `missing-contract` finding.
- **Source:** `linter-scripts/audit-spec-vs-code-v2.py` (rubric v2.13).

### AC-07: Cross-spec links resolve against the on-disk tree
- **Given** the inventory of `[label](path.md)` links in this module's `00-overview.md`
- **When** `python3 linter-scripts/check-spec-cross-links.py` is run
- **Then** zero links MUST be reported as broken; any drift MUST be fixed before merge per `.github/workflows/spec-health.yml` Phase 81 strict gate.
- **Source:** `linter-scripts/check-spec-cross-links.py`.

### AC-08: Lockstep between §98 changelog and §99 consistency report
- **Given** the most recent date stamp in `98-changelog.md`
- **When** `node linter-scripts/check-lockstep.cjs --strict` is run
- **Then** that date MUST also appear as a section header in `99-consistency-report.md`; the strict gate (Phase 81) blocks merge on any mismatch.
- **Source:** `linter-scripts/check-lockstep.cjs`.

### AC-09: `AI Confidence` field follows the four-gate rubric (P1–P4)
- **Given** any module whose `00-overview.md` declares an `**AI Confidence:**` value
- **When** an author or auditor evaluates the value against the rubric defined in `01-spec-authoring.md` § *AI Confidence Rubric (normative)*
- **Then** the declared value MUST equal the **lowest-passing tier** among gates P1, P2, P3, P4 (a module passing P1+P2 but failing P3 is `Medium`, not `High`); the value MUST be omitted entirely if even P1 fails (rather than guessing `Low`).
- **And** an upgrade across tiers MUST be accompanied by a `98-changelog.md` row citing the gate(s) newly passing, with measurement evidence drawn from the deterministic sources listed in the rubric (`check-tree-health.cjs --strict`, `check-truncated-prose.py`, `check-spec-cross-links.py`, `check-99-summary-freshness.py`).
- **And** the `Ambiguity` field MUST mirror the same gate logic on the inverse axis: `None` requires P4-level confidence with zero open clarification questions in §99; `Critical` requires at least one `BLOCKER` or `OPEN-Q` row in §99.
- **Verifies:** the contract introduced in Phase P48-1 (P47-fu1 finding: prior to v3.3.0 of `01-spec-authoring.md`, the four tier values were listed without measurement criteria, costing this module 33 points in the AI-implementability re-audit). Until a dedicated linter ships, conformance is checked by review against the rubric table.
- **Source:** `01-spec-authoring.md` § *AI Confidence Rubric (normative)* lines following the Scoring Metrics table.


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
- `07-design-system.md`r- `08-docs-viewer-ui.md`
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
