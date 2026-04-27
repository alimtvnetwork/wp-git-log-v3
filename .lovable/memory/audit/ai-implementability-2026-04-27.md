# AI-Implementability Audit — Spec Tree

**Generated:** 2026-04-27 04:12 (UTC+8 Malaysia)
**Auditor model:** `google/gemini-3-flash-preview` (via Lovable AI Gateway)
**Modules audited:** 23
**Schema:** 7-dimension structured-output scoring — clarity, testable acceptance, interface completeness, decision closure, self-containment, internal consistency, AI disambiguation.

---

## TL;DR

- **Composite tree score:** **82.5/100 (B)** — average across 23 modules.
- **Tier distribution:** **A+** ×4 · **A** ×8 · **B** ×7 · **D** ×2 · **F** ×2
- **Total failures flagged:** 71 (5 critical, 13 high, 25 medium, 28 low)
- **Weakest dimension (tree-wide):** `interface_completeness` at 7.0/10
- **Strongest dimension:** `clarity_of_purpose` at 9.8/10

> **Reading the score:** This is NOT the same as `check-tree-health.cjs` (structural rubric, currently 100/100). This audit asks the harder question: *would a mediocre AI agent (GPT-3.5, Gemini-Flash, Llama-70B) implement the module correctly with zero clarifying questions, given only the spec?* The structural rubric measures completeness of files; this measures completeness of **intent**.

---

## Dimension Averages (tree-wide, /10)

| Dimension | Avg | Interpretation |
|-----------|----:|----------------|
| `interface_completeness` | **7.0** | Every input/output/path/env/error/CLI flag specified |
| `decision_closure` | **7.8** | No TBD, no open Qs, no unresolved alternatives |
| `self_containment` | **8.1** | Module + declared cross-refs are sufficient (no tribal knowledge) |
| `ai_disambiguation` | **8.1** | When 2 readings possible, spec picks one with tiebreaker |
| `consistency_internal` | **8.5** | Numbers/names/versions/paths agree across §00/§97/§98/§99 |
| `testable_acceptance` | **8.6** | ACs are concrete GWT, verifiable from artifacts AI produces |
| `clarity_of_purpose` | **9.8** | Can AI state purpose in 1 sentence after reading §00? |

---

## Per-Module Scoreboard (worst → best)

| # | Module | Score | Tier | Verdict |
|--:|--------|------:|:----:|---------|
| 1 | `23-app-database` | **42** | F | Placeholder module lacking any actual schema or data model details; essentially a shell of links. |
| 2 | `24-app-design-system-and-ui` | **45** | F | Placeholder/routing module with circular or missing token definitions and no actual UI specs. |
| 3 | `10-research` | **62** | D | A placeholder index with contradictory ACs that demand content which the Overview explicitly states is currently ab |
| 4 | `11-powershell-integration` | **62** | D | High-level architectural spec lacking the critical technical schema (JSON/Param) needed for a functioning script. |
| 5 | `02-coding-guidelines` | **78** | B | High-quality constraints with 'Code Red' priority, but crippled by 7 TODOs and missing language-specific sub-schema |
| 6 | `17-consolidated-guidelines` | **78** | B | Thorough structural requirements but contains 'TODO' markers and ambiguity regarding external file sync status. |
| 7 | `22-git-logs-v2` | **78** | B | Robust architectural decisions and test cases marred by significant TODOs and a missing internal schema/API contrac |
| 8 | `27-spec-toolchain` | **78** | B | Structural logic is strong, but presence of TODOs and un-specced generator/filler ranges blocks full implementation |
| 9 | `25-app-issues` | **82** | B | Highly structured index module with clear linting rules, though the 'Reproduction' format lacks a literal schema. |
| 10 | `12-cicd-pipeline-workflows` | **84** | B | Highly structured and consistent, but lacks technical interface details (secrets, env vars, runners) for implementa |
| 11 | `15-distribution-and-runner` | **84** | B | Strong distribution contract with clear artifact lists, slightly hindered by an external broken link and CI logic g |
| 12 | `16-generic-release` | **89** | A | High-quality blueprint with strong cross-linking, slightly weakened by external CLI dependencies and 'generic' term |
| 13 | `28-universal-ci-cli` | **89** | A | High-quality Go CLI spec with clear detection logic, but contains minor TODOs and lacks a robust schema for the TOM |
| 14 | `05-split-db-architecture` | **93** | A | Exceptionally detailed architectural spec with rigid naming rules, slightly hindered by dual AC file fragmentation. |
| 15 | `06-seedable-config-architecture` | **93** | A | Highly structured and testable with specific DDL and logic, but missing explicit SQLite type mappings for JSON valu |
| 16 | `01-spec-authoring-guide` | **94** | A | Extremely rigorous and self-referential; only lacks specific regex/logic for the 'Scoring Metrics' calculation. |
| 17 | `03-error-manage` | **94** | A | Highly mature spec with strong GWT ACs and architectural clarity; minor gaps in implementation-level schemas. |
| 18 | `13-generic-cli` | **94** | A | Extremely robust framework for CLI construction; minor index/versioning drift holds it back from a perfect score. |
| 19 | `14-update` | **94** | A | Extremely robust architecture and lifecycle, but lacks concrete JSON schemas and specific error codes in the overvi |
| 20 | `26-gitlogs-diagrams` | **95** | A+ | Highly implementable; explicitly handles 'locked gaps' and cross-references schema constraints with 1:1 precision. |
| 21 | `04-database-conventions` | **96** | A+ | Extremely high-quality spec with rigid, non-negotiable naming and architectural rules that leave no room for AI err |
| 22 | `18-wp-plugin-how-to` | **96** | A+ | Extremely robust, high-density spec with zero broken links and rare enum-driven architecture clearly defined. |
| 23 | `07-design-system` | **98** | A+ | Extremely high-quality spec with rigorous regex-level constraints for design token enforcement. |

---

## Failure Catalogue — Ranked by Severity × Frequency

### By severity

- **CRITICAL:** 5
- **HIGH:** 13
- **MEDIUM:** 25
- **LOW:** 28

### By category (where the gaps cluster)

| Category | Count | Meaning |
|----------|------:|---------|
| `interface` | 29 | Inputs/outputs/paths/errors/CLI flags missing |
| `decision` | 12 | Open architectural questions, TBD, alternatives unresolved |
| `consistency` | 11 | Drift between §00/§97/§98/§99 or sub-files |
| `testable` | 8 | ACs not GWT, not verifiable, or missing |
| `disambiguation` | 5 | Multiple valid readings without tiebreaker |
| `self_containment` | 4 | Requires tribal knowledge or undocumented memory |
| `clarity` | 2 | Module purpose unclear from §00 |

---

## Critical & High-Severity Failures (full detail)

### [CRITICAL] `10-research` — ACs require content the spec claims doesn't exist yet
- **Category:** `testable`
- **Evidence:** AC-RES-000: 'Every research note has a date prefix... and a Decision: or Outcome: section.' vs Overview: 'No research documents added yet.'
- **Why a mediocre AI fails:** An AI runner will fail the test immediately because it cannot find 'dated filenames' or 'Source' lines in an empty directory.
- **Fix:** Explicitly state that AC-RES-000 is skipped if the folder contains only 00/97/98/99 files.

### [CRITICAL] `11-powershell-integration` — Missing Interface Definition (JSON & CLI)
- **Category:** `interface`
- **Evidence:** References 'powershell.json' and 'run.ps1' but provides no schema or parameter definitions in the excerpt or metrics.
- **Why a mediocre AI fails:** An AI will hallucinate the configuration structure (e.g., using 'root' vs 'path'), leading to scripts that don't match the backend's expectations.
- **Fix:** Add a 05-interface.md file defining the JSON schema and PowerShell script parameters.

### [CRITICAL] `23-app-database` — Empty Data Model Specification
- **Category:** `interface`
- **Evidence:** Document Inventory identifies (empty — awaiting content). No schema files listed.
- **Why a mediocre AI fails:** The AI has no tables, columns, or relations to implement. It only knows 'how' to name things, not 'what' to build.
- **Fix:** Create and link child specifications (e.g., 01-schema.md, 02-migrations.md) defining the actual tables.

### [CRITICAL] `23-app-database` — Shell Module with No Local Content
- **Category:** `self_containment`
- **Evidence:** Overview states: 'Intentionally empty until child specs are added'. Cross-references point to general conventions only.
- **Why a mediocre AI fails:** An AI implementer would have to search the entire project or prompt the user for the actual requirements of the 'App Database'.
- **Fix:** Populate the module with app-specific logic instead of deferring to core conventions.

### [CRITICAL] `24-app-design-system-and-ui` — Circular/Missing Interface Definitions
- **Category:** `interface`
- **Evidence:** AC-ADS-01: 'semantic token definitions in 00-overview.md' vs 00-overview.md: '(empty — awaiting content)'
- **Why a mediocre AI fails:** The AI is told to use tokens defined in the overview, but the overview explicitly states it is empty. The AI has no tokens to apply.
- **Fix:** Define the specific CSS variables or JSON token map required for the design system within this module.

### [HIGH] `02-coding-guidelines` — Unresolved TODOs in Core Guidelines
- **Category:** `decision`
- **Evidence:** todo_count: 7
- **Why a mediocre AI fails:** A mediocre AI will either skip the feature or invent its own implementation, leading to architectural drift.
- **Fix:** Resolve all TBDs and TODOs. An AI cannot implement a 'TODO' without guessing the missing logic.

### [HIGH] `10-research` — Ambiguous naming convention interface
- **Category:** `interface`
- **Evidence:** Overview says '01-framework-comparison.md' while AC-RES-000 says 'date prefix'.
- **Why a mediocre AI fails:** A mediocre AI will name files 01-x.md based on the example, but then fail the 'date prefix' check in AC-RES-000.
- **Fix:** Specify the required regex for filenames (e.g., ^\d{2}-.*\.md$ vs ^\d{4}-\d{2}-\d{2}-.*\.md$).

### [HIGH] `11-powershell-integration` — Underspecified Dependency Management
- **Category:** `decision`
- **Evidence:** Summary mentions 'auto-install missing dependencies' but doesn't define the toolchain versions or installation methods.
- **Why a mediocre AI fails:** One AI might use 'npm install -g', another might use 'winget', and a third might fail if the user isn't Admin, creating non-portable scripts.
- **Fix:** Specify exact versions (e.g., Node 20 LTS, Go 1.22) and the installer provider (e.g., Winget or direct download).

### [HIGH] `11-powershell-integration` — Non-Functional Acceptance Criteria
- **Category:** `testable`
- **Evidence:** AC-01 and AC-02 are 'meta-tests' about the spec documents themselves, not the PowerShell script's behavior.
- **Why a mediocre AI fails:** The AI can verify the document exists, but has no criteria to verify the generated script actually works or handles errors correctly.
- **Fix:** Rewrite ACs to follow GWT patterns for script execution outcomes (e.g., AC-Cleanup: Given -Force flag, When executed, Then node_modules is deleted).

### [HIGH] `12-cicd-pipeline-workflows` — Missing Pipeline Infrastructure Interfaces
- **Category:** `interface`
- **Evidence:** No mention of specific CI provider (Actions/GitLab), runner specs, or secret naming conventions (SIGNPATH_API_KEY, etc).
- **Why a mediocre AI fails:** An AI would have to guess whether to use GitHub Actions syntax or GitLab CI, and what to name the secrets required for deployment.
- **Fix:** Add a 'Technical Interface' section to 01-shared-conventions.md defining the required secrets, environment variables, and runner OS requirements.

### [HIGH] `15-distribution-and-runner` — Implicit Update Logic in Runner scripts
- **Category:** `decision`
- **Evidence:** 'Everything in this folder is end-user-facing.' followed by 'The runner scripts... update the local clone'.
- **Why a mediocre AI fails:** A mediocre AI will write a basic runner but might ignore edge cases like auth failures during git pull or local unstaged changes.
- **Fix:** Explicitly state the git update strategy (e.g., 'git pull --rebase') and what to do if the working directory is dirty.

### [HIGH] `17-consolidated-guidelines` — Presence of TODOs in 'Production-Ready' Spec
- **Category:** `decision`
- **Evidence:** todo_count: 5
- **Why a mediocre AI fails:** The AI will encounter unresolved sections, leading to incomplete implementation of the guidelines it is supposed to follow.
- **Fix:** Exhaust all TODOs. A consolidated guideline module must be a finished reference to be 'Production-Ready' for an AI.

### [HIGH] `22-git-logs-v2` — Unfinished Architectural Decisions & TODOs
- **Category:** `decision`
- **Evidence:** todo_count: 10; Locked Decisions table cuts off at #15 in mid-sentence.
- **Why a mediocre AI fails:** A mediocre AI will invent logic for TODO sections (e.g., migration logic or specific auth steps) that likely contradicts the author's intent.
- **Fix:** Complete the decision table and resolve all 10 TODOs to ensure no architectural gaps remain for the AI to guess.

### [HIGH] `22-git-logs-v2` — Undefined Source-of-Truth Interface
- **Category:** `interface`
- **Evidence:** AC-25 references a 'mind-map source-of-truth tree' and 'format:pragma' family without providing the schema or file path for this source.
- **Why a mediocre AI fails:** The AI cannot implement the UI renderer if it doesn't know the structure of the tree it is supposed to walk.
- **Fix:** Include the mind-map schema or a sample JSON/YAML representation of the source-of-truth tree in a §98-technical-interfaces file.

### [HIGH] `23-app-database` — Underspecified Partitioning Assignment
- **Category:** `decision`
- **Evidence:** No mention of which SQLite partition (from 05-split-db-architecture) these tables belong to.
- **Why a mediocre AI fails:** The AI might place all tables in a single DB file, violating the project's 'Split DB Architecture' pattern.
- **Fix:** Explicitly assign tables to specific database files (e.g., 'logs.db', 'main.db').

### [HIGH] `24-app-design-system-and-ui` — Missing Architectural Content
- **Category:** `decision`
- **Evidence:** Document Inventory: '| — | (empty — awaiting content) | — |'
- **Why a mediocre AI fails:** The module claims to cover component patterns and layout conventions but provides zero definitions for them. An AI would have to invent the entire UI.
- **Fix:** Add child specs for Layout, Components, and Typography as promised by the Purpose section.

### [HIGH] `27-spec-toolchain` — Unresolved TODOs in core toolchain logic
- **Category:** `decision`
- **Evidence:** todo_count: 4
- **Why a mediocre AI fails:** The AI will either skip the logic or hallucinate a placeholder implementation.
- **Fix:** Resolve and remove all TODO markers. AI agents treat TODOs as 'stop-and-ask' blocks.

### [HIGH] `27-spec-toolchain` — Incomplete script inventory mapping
- **Category:** `interface`
- **Evidence:** Inventory table for 'Generators' is truncated in the overview excerpt and likely incomplete in the full file.
- **Why a mediocre AI fails:** The AI cannot enforce AC-T-01 (Bijection) if the source list of expected scripts is incomplete.
- **Fix:** Populate all inventory tables (10-79) with their respective spec/code mappings.

---

## Path to 100 — Per-Module Remediation

> Modules already at A+ (≥ 95) have no `remaining_to_100` entries. Everything else lists the concrete edits needed to reach a perfect score.

### `23-app-database`  →  current **42**, needs +58
- Define the actual Application Data Model (list tables, columns, types).
- Specify the SQLite file paths and partitioning logic (which tables go into which DB file).
- Provide the migration file naming convention and storage location path.
- List the specific queries or stored logic (if any) required by the app.
- Replace the placeholder linter script AC with tests that actually validate schema content.

### `24-app-design-system-and-ui`  →  current **45**, needs +55
- Populate the Document Inventory with actual UI component or layout spec files.
- Define the actual semantic tokens (colors, spacing, etc.) within this module or link to the specific file that contains them.
- Remove the self-referential Given clause in AC-ADS-01 that points to 00-overview.md for tokens that do not exist there.
- Specify the tooling (Tailwind, Styled-Components, CSS Modules) to remove developer choice on implementation method.
- Provide the 'baseline snapshots' or the path where they reside.

### `10-research`  →  current **62**, needs +38
- Delete AC-RES-000 or provide a 'null-set' exception for when no research files exist yet.
- Synchronize the verification command in 00-overview.md with the script name referenced in AC-02 (currently 'check-spec-folder-refs.py' vs 'check-spec-cross-links.py').
- Define the exact front-matter schema and filename pattern (e.g., 'YYYY-MM-DD-title.md' vs '01-title.md') mentioned in the ACs.
- Clarify if the index is strictly for 'root-level' research or if it must aggregate links to sub-module research.

### `11-powershell-integration`  →  current **62**, needs +38
- Define the exact JSON schema for `powershell.json` including all required and optional keys.
- Specify the PowerShell `Param()` block (flags, types, default values) for `run.ps1`.
- Define the expected exit codes and error handling behavior for failed Go/pnpm builds.
- Explicitly list the 'auto-install' logic: what versions of Node/Go? From what URLs/managers (Chocolatey, Scoop, or direct)?
- Replace meta-ACs (checking if files exist) with functional-ACs (e.g., 'Given config X, When run, Then directory Y is created').

### `02-coding-guidelines`  →  current **78**, needs +22
- Remove all 7 'TODO' items and replace with concrete specifications.
- Define the schema for 'App subfolders (21+)' to clarify how they document 'exclusions' of Code Red rules.
- Explicitly define PascalCase rules for TypeScript (usually camelCase for locals/members), resolving the conflict between project-wide mandate and domain standards.
- Provide the full table for 'Go', 'PHP', and 'Rust' naming to include variables, locals, and file-naming (not just identifiers/DB).

### `17-consolidated-guidelines`  →  current **78**, needs +22
- Remove the 5 remaining 'TODO' markers identified in the module metrics.
- Explicitly define the sync protocol: specify if these files are manually updated or if a script (and which one) generates them.
- Complete the 'File Inventory' table (currently cuts off at item 14).
- Add a 'Verification' section in §00 explaining how to validate that a consolidated file accurately reflects its source module.

### `22-git-logs-v2`  →  current **78**, needs +22
- Resolve all 10 'TODO' markers in the spec text.
- Provide a formal SQL schema or a data-dictionary file for the 13+ SQLite tables mentioned.
- Define the REST API OpenAPI/Swagger specification for the endpoints mentioned in ACs.
- Complete the 'Locked Decisions' table (currently cuts off at #15).
- Explicitly define the 'mind-map source-of-truth' format mentioned in AC-25.

### `27-spec-toolchain`  →  current **78**, needs +22
- Remove the 4 TODOs in the logic flow.
- Complete the 'Generators', 'Fillers', and 'Auditors' inventory tables in 00-overview.md.
- Provide the specific schema/rules for each script (e.g., what makes a 'health score' in 05-check-tree-health.cjs).
- Explicitly define the CLI interface (args/stdin/stdout) for the runner scripts (40-49).

### `25-app-issues`  →  current **82**, needs +18
- Add a Markdown template or JSON schema for the 'Reproduction / Cause / Fix / Prevention' sections to AC-AI-000.
- Explicitly define the relationship between the '24 numbered items' in folder 02 and the 25 findings' in folder 01.
- Resolve the date discrepancy (2026-04-21 vs 2026-04-26) between the banner and the verification section.

### `12-cicd-pipeline-workflows`  →  current **84**, needs +16
- Define specific environment variable names and secret schemas required for SignPath and Chrome Web Store integrations.
- Specify the CI platform (e.g., GitHub Actions, GitLab CI) and runner types (Ubuntu-latest, Windows-latest) in the shared conventions.
- Fix duplicate index numbers in the Feature Inventory (e.g., multiple '04' and '05' entries).
- Provide specific JSON/YAML schemas for the 'Asset Matrix' mentioned in ACs.

### `15-distribution-and-runner`  →  current **84**, needs +16
- Fix the broken link to '../../spec-slides/00-overview.md' in 00-overview.md.
- Explicitly define the architecture of the run/install scripts (e.g., 'scripts must be self-contained bash/ps1').
- Add the exact logic for 'SHA-256 checksums' generation (e.g., standard sha256sum format vs custom).
- Specify handling for existing files during install (overwrite vs backup vs skip).

### `16-generic-release`  →  current **89**, needs +11
- Replace generic placeholders (e.g., 'your-binary-name') with a reserved 'SPEC_APP_NAME' variable definition in the overview.
- Include the JSON schema for `06-release-metadata.md` to ensure the metadata contract is machine-readable.
- Explicitly list the 6+ platform targets mentioned in the overview within 01-cross-compilation.md.
- Attach the Mermaid source files or ensure they are inline to prevent rendering-only dependency issues.

### `28-universal-ci-cli`  →  current **89**, needs +11
- Resolve the 2 remaining TODOs in the specification files.
- Provide the exact JSON schema for `glci.toml` in §03 or §05.
- Define the retry/backoff mathematical formula for `MaxRetries` to ensure cross-implementer consistency.
- Specify the exact mapping of CI environment variables (e.g., GITHUB_REF, CI_COMMIT_SHA) to the internal payload fields.

### `05-split-db-architecture`  →  current **93**, needs +7
- Merge 98-acceptance-criteria.md into 97-acceptance-criteria.md to provide a single source of truth for verification.
- Explicitly define the connection pooling logic (max connections, timeout values) rather than just listing it as a keyword.
- Provide the specific field types for the '2-step reset API standard' (e.g., exact Timestamp format) to ensure schema parity.

### `06-seedable-config-architecture`  →  current **93**, needs +7
- In §97 AC-SC-02, specify the SQLite storage type for the 'Value' column (e.g., TEXT with JSON check vs BLOB).
- Specify the lock/concurrency strategy for the file-system CHANGELOG.md update during simultaneous config writes.
- Define the schema for the Metadata table explicitly (DDL) to match the Configuration table detail.

### `01-spec-authoring-guide`  →  current **94**, needs +6
- Define the exact formula for 'Health Score' (e.g., weighted average of Confidence/Ambiguity).
- Provide the regex or schema for the Metadata block in 00-overview.md to ensure automated parsing.
- Explicitly define the 'Ambiguity' tier thresholds numerically (e.g., Low = <10% interpretative surface).

### `03-error-manage`  →  current **94**, needs +6
- Explicitly define the priority if 'Status.Code' (in JSON) disagrees with the HTTP Header Status Code.
- Include a TypeScript/Go interface snippet directly in 02-error-architecture/05-response-envelope/ to prevent AI drift into 'string' vs 'int' types for Status.Code.
- Define the behavior for multiple results in the 'Results' array during an error state (e.g., partial success).

### `13-generic-cli`  →  current **94**, needs +6
- Fix the broken/incomplete index entry #17 in 00-overview.md.
- Synchronize the naming in the index (01 vs 00 for overview) to match the actual filename on disk.
- Explicate the 'target language' selection logic—add a standard 'config.yaml' or env var where the AI reads the intended language (Go, Python, etc.) to prevent drift.

### `14-update`  →  current **94**, needs +6
- Inline the 'latest.json' JSON Schema directly in 01-self-update-overview.md or a 99-appendix.md.
- Define a table of standard Exit Codes for update failure modes (e.g., LockTimeout=74, ChecksumMismatch=75).
- Specify the exact hashing algorithm (e.g., SHA-256) instead of generic 'checksum' in the overview levels.

### `26-gitlogs-diagrams`  →  current **95**, needs +5
- Explicitly name the required color hex codes for the classDef mentioned in 06-permission-flow.mmd.
- Provide the content of 'puppeteer.json' mentioned in the render command to ensure SVG output consistency across environments.

### `04-database-conventions`  →  current **96**, needs +4
- In 02-schema-design.md, explicitly define the behavior for many-to-many join table naming (e.g., ProjectUser vs UserProject).
- Specify the casing of the 'AUTOINCREMENT' keyword in the SQL dialect (uppercase/lowercase/PascalCase) to match the PascalCase rule.

### `07-design-system`  →  current **98**, needs +2
- In AC-001 (iii), clarify exactly how a 'currentColor override' wrapper for SVGs should be structured to ensure consistency across the library.

---

## Recommendations — Tree-Wide

1. **Tackle the F-tier modules first** (`23-app-database`, `24-app-design-system-and-ui`). They drag the average and their gaps are systemic, not cosmetic.
2. **Audit the weakest dimension across the tree** — even strong modules can usually gain 1-2 points there with targeted edits.
3. **Wire this audit into CI** — re-run monthly (alongside `spec-monthly-audit.yml`) and fail when:
   - Any module drops below its previous tier, OR
   - Tree average drops below 85, OR
   - Critical-severity failure count > 0.
4. **For modules tagged `kind: future-spec`**: AI-implementability matters less (no working code yet), but they should still hit ≥ 75 so when implementation starts the spec is solid.
5. **Graduate the auditor**: this run used `google/gemini-3-flash-preview`. For the production audit run with `google/gemini-3-pro` once `lovable_ai` runtime is wired into CI (R1 from Phase 33). The Pro model catches more subtle disambiguation gaps.

---

## Methodology

1. **Per-module input bundle** built deterministically: file inventory, AC count + GWT count, cross-link integrity, TODO count, line counts, version banners.
2. **AI scoring** via structured-output tool call — 7 dimensions × 0-10, weighted composite (testable 25%, interface 20%, decision 15%, others 10% each), tier mapping A+/A/B/C/D/F.
3. **Failure list** capped at 5 per module, ranked by severity, with concrete evidence + fix.
4. **No human curation** — these are the model's raw judgments. Cross-check with a Pro-tier re-run before treating any single score as ground truth.

**Auditor:** Lovable AI Gateway · model `google/gemini-3-flash-preview` · 23 module-level prompts · ~3000 chars overview + 1500 chars AC sample per module.
