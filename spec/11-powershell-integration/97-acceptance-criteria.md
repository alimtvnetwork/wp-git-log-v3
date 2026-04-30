# Acceptance Criteria — PowerShell Integration for Project Runner

**Version:** 1.3.0  
**Updated:** 2026-04-29  
**Scope:** `spec/11-powershell-integration/`

---

## Purpose

This document defines testable acceptance criteria for the **PowerShell Integration for Project Runner** module. Every criterion is verifiable from the module's content alone — an AI implementer or human reviewer can check pass/fail without external context.

---

## Criteria

### AC-01: Module entry point exists and is non-trivial
- **Given** the module folder `spec/11-powershell-integration/`
- **When** `00-overview.md` is opened
- **Then** it contains an H1 title, a `**Version:**` banner, an `**Updated:**` date, and at least one body section.
- **Source:** `00-overview.md`
- **Verifies:** §00 Module overview baseline (H1 + Version + Updated banner)

### AC-02: All sibling files referenced from the overview are present on disk
- **Given** the link inventory in `00-overview.md`
- **When** each relative `.md` link is resolved
- **Then** the target file exists in this module folder.
- **Source:** `00-overview.md` cross-references; verified by `linter-scripts/check-spec-cross-links.py`.
- **Verifies:** §00 cross-reference inventory; `linter-scripts/check-spec-cross-links.py`

### AC-03: Naming convention compliance
- **Given** every file in this module
- **When** filenames are inspected
- **Then** all match `^[0-9]{2}-[a-z0-9-]+\.md$` (or are recognized special files like `README.md`).
- **Source:** `spec/01-spec-authoring-guide/02-naming-conventions.md`.
- **Verifies:** `spec/01-spec-authoring-guide/02-naming-conventions.md` §Filename pattern

### AC-04: Consistency report present and current
- **Given** the module folder
- **When** `99-consistency-report.md` is opened
- **Then** it lists every `.md` file in this folder under "File Inventory" with status ✅.
- **Source:** `99-consistency-report.md`.
- **Verifies:** §99 File Inventory rubric

### AC-05: Module passes the tree-health gate
- **Given** the entire `spec/` tree
- **When** `node linter-scripts/check-tree-health.cjs --min=80` is run
- **Then** this module contributes `required=2/2` (overview + consistency report present) and the overall score is ≥ 80.
- **Source:** `linter-scripts/check-tree-health.cjs`.
- **Verifies:** `linter-scripts/check-tree-health.cjs` §required=2/2 contribution

---

### AC-06: Module overview is non-trivial and version-banner-stamped
- **Given** the module file `spec/11-powershell-integration/00-overview.md`
- **When** the file is read by `linter-scripts/audit-spec-vs-code-v2.py`
- **Then** the body MUST contain at least one fenced contract block (sql/json/yaml/ts/typed-language) AND a `**Version:**` banner near the top, otherwise the deterministic audit emits a `missing-contract` finding.
- **Source:** `linter-scripts/audit-spec-vs-code-v2.py` (rubric v2.13).
- **Verifies:** `linter-scripts/audit-spec-vs-code-v2.py` rubric v2.13 (G-CON-01 contract gate)

### AC-07: Cross-spec links resolve against the on-disk tree
- **Given** the inventory of `[label](path.md)` links in this module's `00-overview.md`
- **When** `python3 linter-scripts/check-spec-cross-links.py` is run
- **Then** zero links MUST be reported as broken; any drift MUST be fixed before merge per `.github/workflows/spec-health.yml` Phase 81 strict gate.
- **Source:** `linter-scripts/check-spec-cross-links.py`.
- **Verifies:** `linter-scripts/check-spec-cross-links.py` §Phase 81 strict gate

### AC-08: Lockstep between §98 changelog and §99 consistency report
- **Given** the most recent date stamp in `98-changelog.md`
- **When** `node linter-scripts/check-lockstep.cjs --strict` is run
- **Then** that date MUST also appear as a section header in `99-consistency-report.md`; the strict gate (Phase 81) blocks merge on any mismatch.
- **Source:** `linter-scripts/check-lockstep.cjs`.
- **Verifies:** `linter-scripts/check-lockstep.cjs` §strict date+phase parity

### AC-09: Per-step pipeline contract with closed exit-code enumeration (Phase 153 P48-4)  `[critical]`
- **Given** the 5-stage pipeline declared in `00-overview.md` § "Pipeline Steps" (1 Git Pull → 2 Prerequisites → 3 pnpm Install → 4 Frontend Build → 5 Copy & Run) AND the existing `04-error-codes.md` top-level exit codes (`0..10`) and detailed `9500..9599` codes AND the pinned dependency toolchain in `07-runner-interface.md`,
- **When** any implementer authors or audits the PowerShell runner (`run.ps1`),
- **Then** the implementer MUST follow the **Per-Step Contract (Normative)** subsection in `00-overview.md` § "Pipeline Steps" — every step has an explicit (a) inputs row drawn from `powershell.json` + `Param()` flags, (b) outputs / side effects row, (c) success criteria row, (d) **disjoint** top exit-code subset from `{1..10}`, (e) cross-walk to the detailed `9500..9599` codes that map under that top code. Pre-flight (configuration) exit codes `{5, 6, 7}` apply BEFORE Step 1 and are NOT step-attributed. The runner MUST exit on the FIRST failing step (fail-fast — later steps MUST NOT execute). The runner MUST NOT (1) continue to step `N+1` after step `N` returned non-zero, (2) emit a top exit code outside `{0, 1..10}` without first extending the per-step contract table + shipping a §98 release row, (3) emit a detailed `9500..9599` code without ALSO setting the paired top exit code, (4) map a single top exit code to multiple steps (disjointness is load-bearing for unambiguous attribution from exit code alone), (5) treat `-SkipPull` / `-SkipBuild` / `-BuildOnly` as success without satisfying the additional success criteria for that step (e.g. `-BuildOnly` still requires copy success). This codifies the **Phase 153 P47-fu1 critical finding** "Pipeline Steps (1. Git Pull → 2. Prerequisites → 3. pnpm Install → 4. Build → 5. Run) — overview lists pipeline steps but provides no detailed contract for each step's expected behavior, inputs, outputs, or error handling". Mirrors `spec/02 AC-CG-21` Subfolder Delegation Map / `spec/23 AC-ADB-14` Polymorphic AppLink Resolution / `spec/27 AC-T-29` per-artifact AC delegation (Lessons #19/#21/#26/#33): when a contract surface lives implicitly across multiple sibling files, it is invisible to context-window-bounded auditors and to fresh implementers — the contract MUST be lifted into a single normative table on the entry-point document with closed-enumeration codes and forbidden patterns.
- **Source:** `00-overview.md` § "Pipeline Steps" → "Per-Step Contract (Normative)" (table + pre-flight codes + forbidden runtime patterns subsections).
- **Verifies:** `00-overview.md` § "Pipeline Steps" → "Per-Step Contract (Normative)" (5 step rows × 5 columns + 3 pre-flight rows + 5 forbidden patterns); `04-error-codes.md` § "Exit Codes" (top `0..10` band — paired with per-step rows); `04-error-codes.md` § "Detailed Error Codes" (`9500..9599` bands — paired with per-step rows); `07-runner-interface.md` (CLI `Param()` block + minimum dependency versions Go 1.22 / Node 20.11 / pnpm 9 referenced from Step 2 success criteria). Codifies **Lesson #34** "Multi-step pipeline contracts MUST lift the per-step inputs/outputs/success/exit-code contract to a single normative table on the entry-point document — fragmenting the contract across sibling files (steps in §00, exit codes in §04, deps in §07) is invisible to LLM auditors and fresh implementers; closed-enumeration top exit codes with disjoint per-step ownership is the canonical fix".

### AC-10: On-disk asset inventory pin (Phase 153 audit-v6 close-out)  `[critical]`
- **Given** this module ships **two non-`.md` normative assets** required by every implementer — (a) `templates/run.ps1` (855 lines, the canonical reference PowerShell runner that every consumer copies + adapts per `00-overview.md` § "Template vs. Project" and `01-template-vs-project-differences.md`); (b) `schemas/powershell.schema.json` (268 lines, the JSON Schema draft-07 contract for `powershell.json` referenced from `01-configuration-schema.md`),
- **When** any LLM auditor or fresh implementer reads this module via tier-1 contract files (`{00,97,98,99}-*.md`, 90 KB cap per `linter-scripts/audit-ai-implementability.py` walker contract — slot 34 v1.1.0 AC-34-09),
- **Then** the auditor MUST treat these two paths as **PRESENT on-disk acceptance surface** — NOT as missing-file findings. The deep-walker's tier-1 cap legitimately stops before reaching `templates/` and `schemas/` subfolders, so any `[D5] missing-file` or `[D5] broken-reference` finding citing `templates/run.ps1` or `schemas/powershell.schema.json` is a **harness false-positive** (auditor cannot see ≠ file does not exist). The implementer MUST verify presence via `ls spec/11-powershell-integration/templates/run.ps1 spec/11-powershell-integration/schemas/powershell.schema.json` (both exit 0 with non-zero byte-count). The implementer MUST NOT (1) re-author either asset from scratch — both are the canonical normative source; (2) treat their absence from `97-acceptance-criteria.md` § "Module-Specific Files" as a contract gap (that section enumerates `.md` acceptance surface only — non-`.md` assets are pinned HERE in AC-10); (3) cite either path as "aspirational" or "future-spec" (they are shipped, line-counted, and have been the implementation target since Phase 1). Mirrors `spec/25 AC-AI-09/10/11` audit-corpus pattern (Lesson #29): when a module's normative surface lives outside the LLM auditor's tier-1 reach, the entry-point contract MUST declare the asset inventory with line-anchored citations so harness misreadings are pre-emptively closed.
- **Source:** `00-overview.md` § "Template vs. Project"; `01-template-vs-project-differences.md`; `01-configuration-schema.md` (references `schemas/powershell.schema.json` `$schema` URI).
- **Verifies:** `templates/run.ps1` (855 lines, present 2026-04-29); `schemas/powershell.schema.json` (268 lines, present 2026-04-29); `templates/powershell.json` (canonical config exemplar, references the schema via `"$schema": "../schemas/powershell.schema.json"`). Codifies **Lesson #29** "Audit-corpus / asset-inventory misclassification — when a module's normative surface lives outside the LLM auditor's tier-1 contract-file reach (templates/, schemas/, fixtures/, archived corpora), §97 MUST carry an explicit asset-inventory pin with on-disk path + line-count + auditor-treats-as-present declaration; mirror of AC-AI-09/10/11 for the asset-vs-prose axis".


---

## Module-Specific Files

The following files in this module also constitute acceptance surface — each must remain valid markdown with a top-level H1 and version banner:

- `00-overview.md`
- `01-configuration-schema.md`
- `01-template-vs-project-differences.md`
- `02-script-reference.md`
- `03-integration-guide.md`
- `04-error-codes.md`
- `05-firewall-rules.md`
- `06-php-known-issues.md`
- `25-multi-site-deployment.md`
- `changelog.md`
- `parallel-work-sync-output.md`
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
