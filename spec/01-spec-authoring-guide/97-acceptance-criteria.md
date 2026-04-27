# Spec Authoring Guide — Acceptance Criteria

**Version:** 4.1.0
**Updated:** 2026-04-27 (Phase 89: added AC-SAG-21 [`kind:` rubric branch selector] and AC-SAG-22 [`todo_audit_exempt: true` opt-out] documenting the front-matter keys read by `audit-spec-vs-code-v2.py` v2.10–v2.14.)
**Scope:** `spec/01-spec-authoring-guide/` (the meta-spec — governs every other §97 / §98 / §99 / §00 in the tree).

---

## Module Summary

§01 is the **meta-spec** — it specifies how every OTHER spec in the repository must be authored, named, structured, cross-linked, and version-controlled. The 11 normative content files (`01-folder-structure.md` … `11-root-readme-conventions.md`) define the rules; this `97` codifies the rules as testable Given/When/Then criteria so an AI agent or linter can verify any spec module satisfies them. **Dogfooding rule**: this module MUST itself satisfy every AC it specifies — failures here cascade across the entire spec tree.

---

## Inlined Contracts

> Required artifacts inlined here so each AC is self-contained — a mediocre AI does not need to chase cross-links.

```text
META_FOLDER:               spec/01-spec-authoring-guide/
NORMATIVE_FILES:           01-folder-structure.md, 02-naming-conventions.md,
                           03-required-files.md, 04-cli-module-template.md,
                           05-app-project-template.md, 06-non-cli-module-template.md,
                           07-memory-folder-guide.md, 08-cross-references.md,
                           09-exceptions.md, 10-mandatory-linter-infrastructure.md,
                           11-root-readme-conventions.md
                           (also non-numbered: 04-ai-onboarding-prompt.md)
REQUIRED_PER_MODULE:       00-overview.md, 97-acceptance-criteria.md,
                           98-changelog.md, 99-consistency-report.md
RESERVED_PREFIXES:         00=overview, 97=acceptance-criteria,
                           98=changelog, 99=consistency-report
NAMING_REGEX:              ^[0-9]{2}-[a-z0-9-]+(\.md)?$
NUMBERING_RANGES:          spec root: 01-99 (slot immutable once shipped)
                           subfolders: 01-99 within parent
OVERVIEW_REQUIRED_SECTIONS: H1 title, Version banner, Updated date, AI Confidence,
                            Ambiguity, Overview paragraph, Scoring table, Keywords,
                            Files inventory table, Cross-References table
CROSS_LINK_RULES:          relative paths only (../ or ./), .md extension required,
                           lowercase paths, target must exist on disk
LINTER_GATES:              node linter-scripts/check-tree-health.cjs (≥ 75 default, locked at 100)
                           node linter-scripts/generate-spec-index.cjs (regen on every spec edit)
                           python3 linter-scripts/check-spec-cross-links.py (zero broken)
                           bash linter-scripts/run.sh (full self-heal pipeline)
LOCKSTEP_RULE:             every spec edit MUST update target file banner +
                           §98 changelog row + §99 health/inventory + spec-index regen
SLOT_IMMUTABILITY:         once a numbered slot ships, it is permanent;
                           moves require a new slot + §99 audit row (precedent: §16→§37 v2.8.6)
```

---

## Acceptance Criteria

### AC-SAG-01 — Every spec module under `spec/` has the four required files

- **Given** any module folder under `spec/` (e.g. `spec/02-coding-guidelines/`, `spec/22-git-logs-v2/`, `spec/27-spec-toolchain/03-runner/`),
- **When** its file inventory is listed,
- **Then** it MUST contain `00-overview.md`, `97-acceptance-criteria.md`, `98-changelog.md`, AND `99-consistency-report.md` at minimum. Folders with fewer than 3 content files MAY omit `97`/`98` only if explicitly listed in `09-exceptions.md`. Modules missing any required file MUST be flagged by `node linter-scripts/check-tree-health.cjs --report` as `required=N/4` AND MUST drop the tree-health score below 100. The `_archive/` subtree is the ONLY whole-tree exemption; archived modules retain their last shipped state but are NOT re-validated.
- **Verifies:** `03-required-files.md` + `09-exceptions.md` + `linter-scripts/check-tree-health.cjs` contract.

### AC-SAG-02 — All spec files and folders use lowercase kebab-case with numeric prefixes

- **Given** any file or folder under `spec/`,
- **When** its name is matched against the regex `^[0-9]{2}-[a-z0-9-]+(\.md)?$`,
- **Then** it MUST match. Special files exempt from the numeric prefix: `readme.md` (root only), `consolidated-review-guide.md`, `consolidated-review-guide-condensed.md`, and the trailing `.gitkeep` markers. Uppercase letters, underscores, dots (other than the `.md` extension), and spaces are FORBIDDEN. The numeric prefix MUST be a two-digit zero-padded integer in `00-99`. Three-digit prefixes (`100-` etc.) MUST trigger a parent-folder split per `01-folder-structure.md`'s overflow rule.
- **Verifies:** `02-naming-conventions.md` (kebab-case + numeric prefix rules).

### AC-SAG-03 — Reserved prefixes 00/97/98/99 are used only for their designated purposes

- **Given** any file with prefix `00-`, `97-`, `98-`, or `99-`,
- **When** the file's purpose is inspected,
- **Then** `00-` MUST be `00-overview.md` (module entry point), `97-` MUST be `97-acceptance-criteria.md` (testable GWT ACs), `98-` MUST be `98-changelog.md` (reverse-chronological version history), AND `99-` MUST be `99-consistency-report.md` (file inventory + health metrics). Using `00-something-else.md`, `97-different.md`, etc. is FORBIDDEN. The prefixes `01-` through `96-` are free for content files.
- **Verifies:** `02-naming-conventions.md` reserved-prefix table + `03-required-files.md`.

### AC-SAG-04 — Slot numbers are immutable once shipped

- **Given** a numeric slot (e.g. `22-git-logs-v2/`) that has been merged to the main branch in any release,
- **When** a future change wants to relocate or rename the slot's content,
- **Then** the original slot number MUST NOT be reused for different content. The change MUST allocate a NEW slot (precedent: §22 collision → §25 `app-issues/`; §16 → §37 in folder-22 v2.8.6) AND MUST add an audit row to the source folder's `99-consistency-report.md` AND MUST add a corresponding row to `mem://specs/git-logs.md` queued-decisions trail. Renaming the kebab-case suffix (e.g. `22-git-logs/` → `22-git-logs-v2/`) is permitted ONLY for in-flight unreleased specs; once shipped, the suffix freezes too.
- **Verifies:** Memory rule "File slots are immutable once shipped" + Phase-1 Triage v3.7.0 precedent.

### AC-SAG-05 — Every `00-overview.md` includes the seven mandatory metadata sections

- **Given** any `00-overview.md` in the spec tree,
- **When** parsed,
- **Then** it MUST contain (in order): (1) H1 title matching the module name, (2) `**Version:**` SemVer banner, (3) `**Updated:**` ISO date, (4) `**AI Confidence:**` (Production-Ready / High / Medium / Low), (5) `**Ambiguity:**` (None / Low / Medium / High / Critical), (6) Keywords section with searchable tags, (7) Files inventory table with relative links AND descriptions, (8) Cross-References section. Missing any section MUST be flagged by the linter as a structural violation. The Scoring table (Health Score 0-100) MUST be present when the module has shipped (post-baseline).
- **Verifies:** `00-overview.md` "How to Write an Overview" section + `03-required-files.md`.

### AC-SAG-06 — `97-acceptance-criteria.md` files contain ≥ 5 GWT triplets, not table-row scaffolds

- **Given** any `97-acceptance-criteria.md`,
- **When** parsed,
- **Then** it MUST contain ≥ 5 `### AC-` headings, each followed by explicit `**Given**` / `**When**` / `**Then**` markers in that order, with each line of the triplet being a non-empty sentence. Table-row criteria (e.g. `| AC-001 | Boolean principles ... |`) MAY appear as a legacy index but MUST NOT be the sole content. Per the Phase 16d/e/f deepening sweep, the canonical depth target is 20 GWT ACs per module-level §97 and 40 for high-blast-radius modules (e.g. §22 has 68, §28 has 40). Modules with < 5 GWT ACs MUST be flagged for the next deepening phase.
- **Verifies:** `02-coding-guidelines/97-acceptance-criteria.md` AC-CG-10 + Phase 16e scan finding.

### AC-SAG-07 — Every `98-changelog.md` is reverse-chronological, SemVer-bumped on each edit

- **Given** any `98-changelog.md`,
- **When** parsed,
- **Then** entries MUST be ordered newest-first, each entry MUST have a `## [X.Y.Z] — YYYY-MM-DD` header, AND each header MUST follow [SemVer](https://semver.org/) (MAJOR.MINOR.PATCH). Editing the parent module file (`00-overview.md` or any content file) without bumping `98-changelog.md` is a CODE-RED maintenance violation. Categories MUST come from {`Added`, `Changed`, `Deprecated`, `Removed`, `Fixed`, `Security`}. The SemVer bump rule: behavioral change to a normative contract → MAJOR; new AC or new content file → MINOR; typo / clarification → PATCH.
- **Verifies:** Memory rule "lockstep §98 + §99 + audit row" + `02-coding-guidelines/98-changelog.md` format precedent.

### AC-SAG-08 — Every `99-consistency-report.md` reflects the current file inventory within 7 days

- **Given** any `99-consistency-report.md`,
- **When** the `**Last Updated:**` date is compared against `git log --format=%ad -1 -- <module-folder>`,
- **Then** the gap MUST be ≤ 7 days. The report MUST contain: (1) Module Health table (boolean ✅/❌ for `00-overview.md present`, `99 present`, naming compliance, prefix uniqueness), (2) File Inventory table listing every file in the folder with status, (3) Health Score (0-100), (4) any module-specific gap list. The auto-generated banner stamp ("v3.2.0 — 2026-04-16") MUST match the SemVer in `98-changelog.md`'s top entry. Stale reports (> 7 days) MUST drop the module's tree-health score by 10 points.
- **Verifies:** `00-overview.md` Scoring section + `linter-scripts/check-tree-health.cjs` freshness check.

### AC-SAG-09 — All cross-references use relative paths with `.md` extension; never absolute, never bare

- **Given** any markdown link in any spec file,
- **When** the link is parsed,
- **Then** it MUST start with `./` or `../` (relative path), MUST end with `.md` (NEVER bare paths like `[link](../foo)`), MUST use lowercase kebab-case throughout the path, AND MUST resolve to an existing file on disk. Absolute paths (`/spec/...`), root-relative paths, GitHub URLs (`https://github.com/.../spec/...`), and protocol-less paths (`spec/...` without `./` prefix) are FORBIDDEN. The depth (count of `../`) MUST be exactly correct. Bidirectional linking is RECOMMENDED (if A links to B, B should link to A) but not strictly enforced — broken links ARE strictly enforced.
- **Verifies:** `08-cross-references.md` 7-rule checklist + `linter-scripts/check-spec-cross-links.py` contract.

### AC-SAG-10 — Spec-edit lockstep: target file + §98 + §99 + spec-index regen

- **Given** any edit to any spec content file (NOT including the four reserved files themselves),
- **When** the edit is committed,
- **Then** the SAME commit MUST also: (1) bump the target file's `**Version:**` banner if the file has one, (2) add a row to the same module's `98-changelog.md` describing the change, (3) update the same module's `99-consistency-report.md` (file inventory if files changed; Last Updated date always), AND (4) regenerate `spec/spec-index.md` via `node linter-scripts/generate-spec-index.cjs`. Skipping any of the four MUST be flagged as a lockstep violation. This rule is the highest-frequency CODE-RED violation in the spec tree per Phase 16e audit findings.
- **Verifies:** Memory rule "Spec edits keep these in lockstep" + `linter-scripts/generate-spec-index.cjs`.

### AC-SAG-11 — CLI modules follow the 3-folder pattern; app modules follow features+issues; flat modules use `06-non-cli-module-template.md`

- **Given** a new spec module is being created,
- **When** the author chooses a folder layout,
- **Then** CLI tool modules MUST follow `04-cli-module-template.md` (3-folder pattern: `01-backend/`, `02-frontend/`, `03-deploy/` or `03-diagrams/`); app/WordPress modules MUST follow `05-app-project-template.md` (`01-fundamentals.md` + `02-features/{NN}-{name}/{01-backend.md, 02-frontend.md, 03-wp-admin.md}` + `03-issues/`); flat research/utility modules MUST follow `06-non-cli-module-template.md`. Mixing patterns within one module is FORBIDDEN. The choice of pattern MUST be declared in the module's `00-overview.md` "Folder Structure" section.
- **Verifies:** `01-folder-structure.md` + `04-cli-module-template.md` + `05-app-project-template.md` + `06-non-cli-module-template.md`.

### AC-SAG-12 — Subfolders with 3+ content files MUST have their own `00-overview.md`

- **Given** a subfolder under any module that contains 3 or more `.md` files (excluding `97`/`98`/`99`),
- **When** its inventory is listed,
- **Then** the subfolder MUST contain its own `00-overview.md` summarizing what's inside, with the same metadata sections as a top-level overview (per AC-SAG-05). Subfolders with 1 or 2 files are exempt unless they themselves have nested subfolders. The exemption boundary is documented in `09-exceptions.md`.
- **Verifies:** `03-required-files.md` "Subfolder rules" + `09-exceptions.md`.

### AC-SAG-13 — `.lovable/memories/` is the canonical memory folder; `.lovable/memory/` is FORBIDDEN

- **Given** any reference to a memory file across the codebase or specs,
- **When** the path is inspected,
- **Then** it MUST use `.lovable/memories/` (plural). The legacy singular form `.lovable/memory/` is FORBIDDEN — if found in any audit, the contents MUST be migrated to `.lovable/memories/` AND the legacy folder deleted in the same PR. Memory files use lowercase kebab-case (numeric prefixes optional, unlike spec files), max depth `memories/{category}/{file}.md`, AND every memory file MUST be referenced from `00-memory-index.md`. Duplication of spec content into memories is FORBIDDEN — memories hold patterns, decisions, and conventions; specs hold contracts.
- **Verifies:** `07-memory-folder-guide.md` consolidation rule + `00-overview.md` "The .lovable/ Folder" section.

### AC-SAG-14 — Mandatory linter scripts are present before any spec validation runs

- **Given** any spec validation pipeline (`bash linter-scripts/run.sh` or any sub-step),
- **When** the pipeline starts,
- **Then** the following scripts MUST exist in `linter-scripts/` and be executable: `check-tree-health.cjs`, `generate-spec-index.cjs`, `check-spec-cross-links.py`, `validate-guidelines.go` (or `.py` twin), AND `run.sh` (orchestrator). Missing any script MUST cause the pipeline to exit non-zero with a clear "missing infrastructure" error — NEVER silently skip the check. The Python+Go twin requirement (per `27-spec-toolchain/97` AC-T-18) means byte-equivalent output between the two implementations is mandatory.
- **Verifies:** `10-mandatory-linter-infrastructure.md` + `27-spec-toolchain/97-acceptance-criteria.md` AC-T-18.

### AC-SAG-15 — Root `readme.md` follows the mandatory hero-block + author + badges + §9 release-blocker format

- **Given** the repository root `readme.md`,
- **When** parsed,
- **Then** it MUST contain (in order): (1) centered icon/logo, (2) hero block with project name + tagline, (3) author/company template block, (4) status badges (build, version, license), AND (5) a §9 "Release Blockers" checklist enumerating all currently-blocking issues. Any of (1)-(5) missing MUST block release. The §9 checklist MUST be the last numbered section before any appendices. This rule applies ONLY to the repo root `readme.md`, NOT to nested `readme.md` files (which are unconstrained).
- **Verifies:** `11-root-readme-conventions.md` § Mandatory Sections.

### AC-SAG-16 — AI Confidence and Ambiguity scores reflect actual spec readiness, not aspirational claims

- **Given** any `00-overview.md` declaring `**AI Confidence:** Production-Ready` AND `**Ambiguity:** None`,
- **When** an audit verifies the claim against actual content,
- **Then** the module MUST satisfy: (a) all interfaces fully typed, (b) ≥ 5 GWT ACs in `97`, (c) zero broken cross-links, (d) all referenced contracts inlined or linked to existing files, (e) `99-consistency-report.md` Health Score = 100. False "Production-Ready" claims MUST be downgraded by the auditor (precedent: full-tree audit v4 dropped tree score from claimed 100 → actual 45 in 2026-04-25). Aspirational scoring is a CODE-RED maintenance violation. The full-tree audit's findings are codified in `mem://specs/full-tree-audit-v4.md`.
- **Verifies:** `00-overview.md` Scoring Metrics section + `mem://specs/full-tree-audit-v4.md`.

### AC-SAG-17 — Reliability Risk Reports are mandatory before implementing Complex Agentic / End-to-End modules

- **Given** a spec module with `Complexity Tier: Complex Agentic` OR `End-to-End` OR with AI Confidence < 70%,
- **When** any implementation work begins,
- **Then** a Reliability Risk Report MUST exist at `spec/validation-reports/<module-name>.md` OR inline in the module's `00-overview.md`. The report MUST contain: Complexity Tier, Success Probability (%), Failure Modes, Risk Mitigations, Dependency Risks. Beginning implementation without the report for these tiers is FORBIDDEN. Simple/Medium tiers are exempt. The user-preference rule "Wants reliability reports before implementation" is the source of this AC.
- **Verifies:** `00-overview.md` "Reliability Check Report" section + user-preferences memory.

### AC-SAG-18 — Self-application: `01-spec-authoring-guide/` itself satisfies every AC it specifies (dogfooding)

- **Given** this module (`spec/01-spec-authoring-guide/`),
- **When** every AC above (AC-SAG-01..AC-SAG-17) is applied to this module's own files,
- **Then** every check MUST pass: four required files present (AC-SAG-01) ✅; all 16 files match naming regex (AC-SAG-02) ✅; reserved prefixes correct (AC-SAG-03) ✅; slot 01 has been stable since baseline (AC-SAG-04) ✅; `00-overview.md` has all seven sections (AC-SAG-05) ✅; this `97` has 20 GWT ACs (AC-SAG-06) ✅ — verified by self-application; `98-changelog.md` reverse-chronological + SemVer (AC-SAG-07) ✅; `99-consistency-report.md` updated within 7 days of this commit (AC-SAG-08) ✅; all cross-references relative + `.md` (AC-SAG-09) ✅; this commit updates §98 + §99 + spec-index in lockstep (AC-SAG-10) ✅; this module follows the flat (non-CLI, non-app) pattern per `06-non-cli-module-template.md` (AC-SAG-11) ✅. **The dogfooding rule is binding** — failure of any AC against this module is a meta-failure that cascades to every other module's compliance claim.
- **Verifies:** All preceding ACs + recursive self-check.

### AC-SAG-19 — Linter pipeline (`bash linter-scripts/run.sh`) exits 0; tree-health ≥ 75 (locked at 100)

- **Given** any commit touching files under `spec/`,
- **When** `bash linter-scripts/run.sh` runs in CI,
- **Then** the pipeline MUST exit 0 AND `node linter-scripts/check-tree-health.cjs --report` MUST output `PASS: tree health N ≥ threshold 75`. The default threshold is 75 but the project lock per `mem://specs/full-tree-audit-v4.md` Phase 3 is 100. CI MUST fail any PR that drops the score below the locked threshold. The pipeline order is: (1) cross-link check, (2) self-heal (`--fix`), (3) regen spec-index, (4) tree-health gate. Steps (1)–(3) are repeatable/idempotent; step (4) is the gate.
- **Verifies:** `10-mandatory-linter-infrastructure.md` + `27-spec-toolchain/97` AC-T-15/16/19.

### AC-SAG-20 — `spec-index.md` is auto-generated; manual edits are FORBIDDEN

- **Given** the file `spec/spec-index.md`,
- **When** any change to it is committed,
- **Then** the change MUST come from `node linter-scripts/generate-spec-index.cjs` (deterministic, content-derived). Manual edits to `spec-index.md` are FORBIDDEN — the file's content is fully reproducible from the rest of the spec tree, and divergence introduces drift. The script MUST be re-run as part of the AC-SAG-10 lockstep on every spec edit. The output is byte-identical between Python and Go twin generators per AC-T-18 (cross-toolchain stability).
- **Verifies:** `linter-scripts/generate-spec-index.cjs` contract + AC-SAG-10 lockstep + AC-T-18 (twin equivalence).

### AC-SAG-21 — `kind:` front-matter selects the auditor rubric branch (Phase 89)

- **Given** a module's `00-overview.md` declaring `kind: <value>` in YAML front-matter where `<value> ∈ {active-spec, future-spec, tracker, index, meta-toolchain}`,
- **When** `linter-scripts/audit-spec-vs-code-v2.py` runs in deterministic mode,
- **Then** the rubric branch chosen MUST match the value: `tracker` → tracker branch (impl baseline 75, cap 85 prose-only / 95 with ≥1 typed contract per v2.13); `index` → index branch (impl baseline 70 + 10 if `child_modules > 0`, cap 90 prose-only / 100 with ≥1 typed contract per v2.11); `meta-toolchain` → meta-toolchain branch (impl baseline 75, +10 if `has_normative_contract`, +5 if `md_files >= 30`, cap 100 per v2.10); `active-spec`/omitted/`future-spec` → normal contract module branch (impl baseline 30 + additive contract bonuses).
- **And** an unrecognised `kind:` value MUST default to the normal contract branch (no crash, no hard error). The full enum is enumerated in §00 OpenAPI `SpecAudit` schema.
- **Verifies:** `linter-scripts/audit-spec-vs-code-v2.py` rubric branches + `spec/27-spec-toolchain/31-audit-spec-vs-code-v2.md` AC-31-15..AC-31-21.

### AC-SAG-22 — `todo_audit_exempt: true` opt-out is reviewer-gated (Phase 89, v2.14)

- **Given** a module's `00-overview.md` declaring `todo_audit_exempt: true` in YAML front-matter,
- **When** `linter-scripts/audit-spec-vs-code-v2.py` runs in deterministic mode,
- **Then** `metrics.todo_count` MUST be forced to `0` regardless of how many real `TODO:`/`TBD:`/`FIXME:` markers appear in prose, AND completeness scoring MUST NOT penalise the module for them.
- **And** the opt-out MUST only be set on auditor-self-reference modules (modules that legitimately quote work-tracker markers because they document the TODO detector itself — currently only `spec/27-spec-toolchain/`). Reviewer MUST reject the opt-in for any other module type, since misuse silently masks unresolved work.
- **And** the v2.14 tightened TODO regex requires the canonical work-tracker shape (`TODO:` / `TODO(name):` / `TODO -`); narrative mentions like "marked TODO" or "TODO/FIXME density" do **not** match, so most modules do not need this opt-out.
- **Verifies:** `linter-scripts/audit-spec-vs-code-v2.py` `TODO_EXEMPT_RX` + `spec/27-spec-toolchain/31-audit-spec-vs-code-v2.md` AC-31-22.

---


## Legacy Index (preserved for traceability)

The following table-row criteria from v3.2.0 are preserved verbatim. They are NO LONGER authoritative — the GWT ACs above supersede them.

### AC-SAG-LEGACY: Folder Structure & Required Files

| ID | Criterion | Source |
|---|---|---|
| AC-SAG-LEGACY-001 | Every spec module has `00-overview.md` at root | `03-required-files.md` |
| AC-SAG-LEGACY-002 | Every spec module has `99-consistency-report.md` at root | `03-required-files.md` |
| AC-SAG-LEGACY-003 | CLI modules follow 3-folder pattern (`01-backend/`, `02-frontend/`, `03-deploy/`) | `04-cli-module-template.md` |
| AC-SAG-LEGACY-004 | Subfolders with 3+ files include their own `00-overview.md` | `03-required-files.md` |

### AC-SAG-LEGACY: Naming Conventions

| ID | Criterion | Source |
|---|---|---|
| AC-SAG-LEGACY-005 | All files use lowercase kebab-case naming | `02-naming-conventions.md` |
| AC-SAG-LEGACY-006 | All folders use lowercase kebab-case naming | `02-naming-conventions.md` |
| AC-SAG-LEGACY-007 | All spec files have unique numeric sequence prefixes within their folder | `02-naming-conventions.md` |
| AC-SAG-LEGACY-008 | Reserved prefixes (00, 97, 98, 99) used only for their designated purposes | `02-naming-conventions.md` |

### AC-SAG-LEGACY: Overview Content Standards

| ID | Criterion | Source |
|---|---|---|
| AC-SAG-LEGACY-009 | Every `00-overview.md` includes Version and Updated metadata | `00-overview.md` |
| AC-SAG-LEGACY-010 | Every `00-overview.md` includes AI Confidence score | `00-overview.md` |
| AC-SAG-LEGACY-011 | Every `00-overview.md` includes Ambiguity score | `00-overview.md` |
| AC-SAG-LEGACY-012 | Every `00-overview.md` includes Keywords section | `00-overview.md` |
| AC-SAG-LEGACY-013 | Every `00-overview.md` includes Scoring table | `00-overview.md` |
| AC-SAG-LEGACY-014 | Every `00-overview.md` includes numbered file inventory table | `00-overview.md` |
| AC-SAG-LEGACY-015 | Every `00-overview.md` includes Cross-References table | `00-overview.md` |

### AC-SAG-LEGACY: Cross-References & Validation

| ID | Criterion | Source |
|---|---|---|
| AC-SAG-LEGACY-016 | All cross-references use relative paths (never root-relative or absolute) | `08-cross-references.md` |
| AC-SAG-LEGACY-017 | All linked files include `.md` extension | `08-cross-references.md` |
| AC-SAG-LEGACY-018 | Zero broken links reported by dashboard scanner | `08-cross-references.md` |

---

## Cross-References

- [Module overview](./00-overview.md)
- [Module changelog](./98-changelog.md)
- [Module consistency report](./99-consistency-report.md)
- [Required Files](./03-required-files.md)
- [Naming Conventions](./02-naming-conventions.md)
- [Cross-References Guide](./08-cross-references.md)
- [Mandatory Linter Infrastructure](./10-mandatory-linter-infrastructure.md)
- [Coding Guidelines parent governance](../02-coding-guidelines/97-acceptance-criteria.md)
- [Spec Toolchain](../27-spec-toolchain/97-acceptance-criteria.md)
