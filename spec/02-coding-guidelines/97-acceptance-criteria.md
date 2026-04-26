# Coding Guidelines — Acceptance Criteria

**Version:** 4.0.0
**Updated:** 2026-04-26 (Phase 16e: full GWT rewrite — replaced 22 table-row criteria with 20 module-specific Given/When/Then ACs covering the §02 parent governance contract. Old AC-001..AC-022 table rows preserved at end of file as **AC-CG-LEGACY-001..022** for traceability.)
**Scope:** `spec/02-coding-guidelines/` (the parent module — language-specific ACs live in subfolder §97 files).

---

## Module Summary

§02 is the **parent module** for all language-specific and cross-cutting coding standards. It does NOT itself enumerate language rules — those live in subfolders (`01-cross-language/`, `02-typescript/`, `03-golang/`, `04-php/`, `05-rust/`, `06-ai-optimization/`, `07-csharp/`, `08-file-folder-naming/`, `09-powershell-integration/`, `10-research/`, `11-security/`, plus app-specific `21-app/`..`24-app-design-system-and-ui/`). This file specifies the **governance contract** every subfolder MUST satisfy and the **CODE-RED rules** every AI agent MUST internalize before generating code in any language.

---

## Inlined Contracts

> Required artifacts inlined here so each AC is self-contained — a mediocre AI does not need to chase cross-links. Three normative machine-parseable contract blocks (TypeScript, JSON Schema, YAML) follow the human-readable text summary; downstream linters and the deterministic spec auditor parse the fenced blocks by language tag.

### Human-readable summary

```text
PARENT_FOLDER:        spec/02-coding-guidelines/
NUMBERING_RANGES:     core=01-20, app-specific=21+
LANGUAGE_SUBFOLDERS:  01-cross-language, 02-typescript, 03-golang, 04-php,
                      05-rust, 06-ai-optimization, 07-csharp,
                      08-file-folder-naming, 09-powershell-integration,
                      10-research, 11-security
APP_SUBFOLDERS:       21-app, 22-app-issues (deprecated; canonical at spec/25-app-issues/),
                      23-app-database, 24-app-design-system-and-ui
NAMING_MATRIX (identifiers / DB columns / enum values):
  Go         PascalCase / PascalCase / PascalCase
  TypeScript PascalCase / PascalCase / PascalCase
  PHP        PascalCase / PascalCase / PascalCase
  C#         PascalCase / PascalCase / PascalCase
  Rust       snake_case / PascalCase / PascalCase   (intentional exception)
CODE_RED_RULES:
  R1 Error-management is #1 priority; wrap business logic from line 1.
  R2 Booleans positively named with is/has/should/can prefix.
  R3 Zero nesting; early returns; guard clauses.
  R4 DB tables singular PascalCase, PK = {Table}Id INTEGER PRIMARY KEY AUTOINCREMENT.
  R5 Never hallucinate; ask clarifying questions for unclear requirements.
  R6 Functions 8-15 lines; files <300 lines; React components <100 lines.
REQUIRED_PER_SUBFOLDER:  00-overview.md, 97-acceptance-criteria.md, 98-changelog.md, 99-consistency-report.md
```

### Normative TypeScript contract (CODE-RED rules + naming matrix)

```ts
// Authoritative type-level contract for the §02 Coding Guidelines parent module.
// Any implementer (human or AI) MUST conform to these types when generating code,
// linters, or downstream specs. This block is the source of truth for AC-CG-03..AC-CG-09.

/** The six CODE-RED rules — every language subfolder MUST honour all six. */
export enum CodeRedRule {
  R1_ErrorManagementFirst    = "R1",
  R2_BooleansPositivelyNamed = "R2",
  R3_ZeroNestingEarlyReturn  = "R3",
  R4_DbTablesSingularPascal  = "R4",
  R5_NoHallucinationAskFirst = "R5",
  R6_SizeLimitsEnforced      = "R6",
}

/** Hard size limits for R6. Lines counted excluding blank lines and comments. */
export interface R6SizeLimits {
  readonly functionMinLines: 8;
  readonly functionMaxLines: 15;
  readonly fileMaxLines: 300;
  readonly reactComponentMaxLines: 100;
}

/** Naming-case policy per language. Rust is the only intentional exception. */
export type NamingCase = "PascalCase" | "snake_case" | "camelCase";

export interface LanguageNamingPolicy {
  readonly language: "Go" | "TypeScript" | "PHP" | "CSharp" | "Rust";
  readonly identifiers: NamingCase;
  readonly dbColumns: NamingCase;
  readonly enumValues: NamingCase;
}

export const NAMING_MATRIX: readonly LanguageNamingPolicy[] = [
  { language: "Go",         identifiers: "PascalCase", dbColumns: "PascalCase", enumValues: "PascalCase" },
  { language: "TypeScript", identifiers: "PascalCase", dbColumns: "PascalCase", enumValues: "PascalCase" },
  { language: "PHP",        identifiers: "PascalCase", dbColumns: "PascalCase", enumValues: "PascalCase" },
  { language: "CSharp",     identifiers: "PascalCase", dbColumns: "PascalCase", enumValues: "PascalCase" },
  { language: "Rust",       identifiers: "snake_case", dbColumns: "PascalCase", enumValues: "PascalCase" },
] as const;

/** R2 — boolean prefix allowlist. Any boolean identifier failing this regex MUST be renamed. */
export const BOOLEAN_PREFIX_ALLOWLIST = ["is", "has", "should", "can", "will", "did", "was"] as const;
export const BOOLEAN_NAME_REGEX = /^(is|has|should|can|will|did|was)[A-Z]/;

/** R4 — primary-key contract. Every table named `{Table}` MUST have PK column `{Table}Id`. */
export interface PrimaryKeyContract {
  readonly tableName: string;                  // singular PascalCase, e.g. "User"
  readonly primaryKeyColumn: `${string}Id`;    // e.g. "UserId"
  readonly columnType: "INTEGER PRIMARY KEY AUTOINCREMENT";
}

/** Folder governance — every language subfolder MUST satisfy this shape. */
export interface SubfolderGovernance {
  readonly folderSlot: number;                 // 1..20 = core, 21+ = app-specific
  readonly requiredFiles: readonly [
    "00-overview.md",
    "97-acceptance-criteria.md",
    "98-changelog.md",
    "99-consistency-report.md"
  ];
  readonly minGwtAcceptanceCriteria: 5;
  readonly enforcedCodeRedRules: readonly CodeRedRule[];
}
```

### Normative JSON Schema (subfolder structural contract)

```json
{
  "$schema": "https://json-schema.org/draft/2020-12/schema",
  "$id": "https://lovable.spec/02-coding-guidelines/subfolder.schema.json",
  "title": "CodingGuidelinesSubfolder",
  "description": "Structural contract every subfolder under spec/02-coding-guidelines/ MUST satisfy. Verified by linter-scripts/check-tree-health.cjs and audit-spec-vs-code-v2.py.",
  "type": "object",
  "required": ["folderSlot", "requiredFiles", "minGwtAcceptanceCriteria"],
  "properties": {
    "folderSlot": {
      "type": "integer",
      "minimum": 1,
      "description": "Numeric prefix. 1-20 = core language/cross-cutting, 21+ = app-specific. Once shipped, immutable."
    },
    "requiredFiles": {
      "type": "array",
      "minItems": 4,
      "uniqueItems": true,
      "items": {
        "type": "string",
        "enum": [
          "00-overview.md",
          "97-acceptance-criteria.md",
          "98-changelog.md",
          "99-consistency-report.md"
        ]
      }
    },
    "minGwtAcceptanceCriteria": {
      "const": 5,
      "description": "Each 97-acceptance-criteria.md MUST contain >= 5 ### AC- headings, each with explicit Given/When/Then markers."
    },
    "enforcedCodeRedRules": {
      "type": "array",
      "items": { "enum": ["R1", "R2", "R3", "R4", "R5", "R6"] },
      "minItems": 6,
      "description": "Language subfolders MUST enforce all six CODE-RED rules. App subfolders (21+) MAY scope to a subset but MUST document the exclusion."
    },
    "namingCase": {
      "type": "object",
      "properties": {
        "identifiers": { "enum": ["PascalCase", "snake_case", "camelCase"] },
        "dbColumns":   { "enum": ["PascalCase"] },
        "enumValues":  { "enum": ["PascalCase"] }
      },
      "required": ["identifiers", "dbColumns", "enumValues"]
    }
  },
  "additionalProperties": true
}
```

### Normative YAML (numbering ranges + linter wiring)

```yaml
# Authoritative numbering and tooling wiring for spec/02-coding-guidelines/.
# Parsed by linter-scripts/check-tree-health.cjs and the deterministic spec auditor.
parent_folder: spec/02-coding-guidelines/
numbering_ranges:
  core:
    min: 1
    max: 20
    purpose: "Language-agnostic cross-cutting guidelines (cross-language, language-specific, AI-opt, security, naming)."
  app_specific:
    min: 21
    max: 99
    purpose: "Application-tier conventions that build on core but are scoped to product surface area."
required_files_per_subfolder:
  - 00-overview.md
  - 97-acceptance-criteria.md
  - 98-changelog.md
  - 99-consistency-report.md
language_subfolders:
  - { slot: "01", name: "cross-language",          policy: "All six CODE-RED rules; defines defaults." }
  - { slot: "02", name: "typescript",              policy: "PascalCase identifiers, dbColumns, enumValues." }
  - { slot: "03", name: "golang",                  policy: "PascalCase across the board; idiomatic Go err returns satisfy R1." }
  - { slot: "04", name: "php",                     policy: "PascalCase across the board; PSR-12 base." }
  - { slot: "05", name: "rust",                    policy: "snake_case identifiers (intentional exception); PascalCase DB and enums." }
  - { slot: "06", name: "ai-optimization",         policy: "Canonical home for R5; other subfolders MUST NOT duplicate." }
  - { slot: "06", name: "cicd-integration",        policy: "Slot collision flagged; rename pending B2 user decision." }
  - { slot: "07", name: "csharp",                  policy: "PascalCase across the board; PascalCase enums map to .NET conventions." }
  - { slot: "08", name: "file-folder-naming",      policy: "Defines lowercase-kebab-case for all repo paths; supersedes language defaults for filenames." }
  - { slot: "09", name: "powershell-integration",  policy: "Placeholder; populate or archive per AC-CG-17." }
  - { slot: "10", name: "research",                policy: "Placeholder; populate or archive per AC-CG-17." }
  - { slot: "11", name: "security",                policy: "Version-pinned per AC-CG-16; axios subfolder canonical." }
app_subfolders:
  - { slot: "21", name: "app",                      status: "active" }
  - { slot: "22", name: "app-issues",               status: "deprecated; canonical at spec/25-app-issues/" }
  - { slot: "23", name: "app-database",             status: "active" }
  - { slot: "24", name: "app-design-system-and-ui", status: "active" }
linters:
  tree_health:    "node linter-scripts/check-tree-health.cjs --report --min=95"
  cross_links:    "python3 linter-scripts/check-spec-cross-links.py"
  forbidden:      "python3 linter-scripts/check-forbidden-strings.py"
  spec_audit:     "AUDIT_DETERMINISTIC=1 python3 linter-scripts/audit-spec-vs-code-v2.py"
gates:
  module_tree_health_min: 95
  per_subfolder_required_files: 4
  per_subfolder_min_gwt_acs:    5
```

---

## Acceptance Criteria

### AC-CG-01 — Numbering ranges are immutable: 01–20 = core, 21+ = app-specific

- **Given** a new subfolder is proposed under `spec/02-coding-guidelines/`,
- **When** the proposer assigns a numeric prefix,
- **Then** the prefix MUST be in `01-20` if and only if the content is reusable / language-agnostic / cross-cutting (e.g. naming, security, DB conventions, language standards), AND MUST be in `21+` if and only if the content describes a specific application's features, workflows, bugs, or design tokens. A subfolder placed in the wrong range MUST be rejected at PR review and renumbered before merge. Renumbering after release is FORBIDDEN — once a slot ships, the number is permanent (precedent: §16 → §37 in v2.8.6 of folder 22 required a §99 audit row, NOT a slot reuse).
- **Verifies:** `00-overview.md` "Numbering Convention" section + memory rule "File slots are immutable once shipped".

### AC-CG-02 — Every subfolder has the four required spec files

- **Given** any subfolder under `spec/02-coding-guidelines/`,
- **When** its file inventory is listed,
- **Then** it MUST contain `00-overview.md`, `97-acceptance-criteria.md`, `98-changelog.md`, AND `99-consistency-report.md` at minimum. Subfolders missing any of the four MUST be flagged by `node linter-scripts/check-tree-health.cjs --report` as `required=N/4` and contribute to a tree-health score below 100. `97-acceptance-criteria.md` MUST contain ≥ 5 `### AC-` headings, each followed by Given/When/Then.
- **Verifies:** Memory rule "Spec edits keep these in lockstep: target file banner + §98 changelog row + §99 health/inventory" + `linter-scripts/check-tree-health.cjs` contract.

### AC-CG-03 — CODE-RED rule R1 (Error-management priority) governs all language subfolders

- **Given** any AC-* in any language subfolder (`02-typescript/`, `03-golang/`, `04-php/`, `05-rust/`, `07-csharp/`),
- **When** that AC describes a happy-path business operation (e.g. "controller returns user data", "function computes total"),
- **Then** the AC MUST also specify the error path (what error type/envelope is returned on failure, per `spec/03-error-manage/`). A language-subfolder AC describing business logic without specifying error handling is a CODE-RED violation AND MUST be marked DRIFT in §99 of that subfolder. Go uses `apperror.Result[T]`; TS uses error envelopes per `03-error-manage/02-error-architecture/05-response-envelope/`; PHP uses `\WP_Error` or `AppError`; Rust uses `Result<T, E>`; C# uses typed exceptions. Mixing patterns within one language is FORBIDDEN.
- **Verifies:** `00-overview.md` MANDATORY block rule R1 + `spec/03-error-manage/00-overview.md`.

### AC-CG-04 — CODE-RED rule R2 (Boolean naming) is enforceable per language

- **Given** a boolean identifier in any language source file governed by a §02 subfolder,
- **When** the identifier is inspected,
- **Then** it MUST start with `is`/`has`/`should`/`can` (or the language-idiomatic equivalent for Rust: `is_`/`has_`/`should_`/`can_`) AND MUST be **positively named** (`IsActive`, `HasPermission`, `CanEdit`) — NEVER negatively (`IsDisabled`, `HasNoPermission`, `CannotEdit`). A negatively-named boolean is a CODE-RED violation. Multi-part conditions (e.g. `userIsActive && userHasPaid && !userIsBlocked`) MUST be extracted into a single named variable (e.g. `isEligibleForCheckout`) with at most 2 boolean operands per expression. The cross-language source of truth is `01-cross-language/02-boolean-principles/`; per-language refinements may add idioms but NEVER weaken these rules.
- **Verifies:** `00-overview.md` MANDATORY block rule R2 + `01-cross-language/02-boolean-principles/00-overview.md`.

### AC-CG-05 — CODE-RED rule R3 (Zero nesting) blocks PR merge

- **Given** any function in any language source file under spec governance,
- **When** static analysis (`go vet`, `eslint`, `phpstan`, `clippy`, `roslyn-analyzers`) runs,
- **Then** the function MUST contain ZERO nested `if` statements — every conditional MUST be implemented via early return / guard clause / extracted helper. A function with even one nested `if` MUST be flagged by the linter at error severity AND MUST block PR merge. The cyclomatic complexity cap is 10 per function (per `01-cross-language/06-cyclomatic-complexity.md`); functions exceeding 10 MUST be refactored, NOT exempted.
- **Verifies:** `00-overview.md` MANDATORY block rule R3 + `01-cross-language/06-cyclomatic-complexity.md`.

### AC-CG-06 — CODE-RED rule R4 (DB conventions) crosses language boundaries

- **Given** a database column or table referenced from any language (Go ORM struct, TS Prisma model, PHP `$wpdb` query, Rust `sqlx` macro, C# EF Core entity),
- **When** the schema is inspected,
- **Then** table names MUST be **singular PascalCase** (`User`, NEVER `users`/`Users`), primary keys MUST be `{TableName}Id` (e.g. `UserId`, `SessionId`) typed `INTEGER PRIMARY KEY AUTOINCREMENT` (SQLite) or equivalent identity column (PostgreSQL/MySQL), foreign keys MUST use the **exact** PK name (a FK to `User.UserId` is named `UserId` in the child table, NOT `user_id` or `userIdFk`). Rust is NOT exempt from this rule — Rust source uses snake_case for fields but DB columns and serde-renamed JSON keys MUST be PascalCase via `#[serde(rename = "PascalCase")]`. Violations MUST be caught in `04-database-conventions/` AC enforcement AND blocked at PR review.
- **Verifies:** `00-overview.md` MANDATORY block rule R4 + `spec/04-database-conventions/00-overview.md`.

### AC-CG-07 — CODE-RED rule R5 (Anti-hallucination) is enforced via the AI optimization subfolder

- **Given** an AI agent receives a coding task with an ambiguous requirement (e.g. "add a user export feature" with no format specified),
- **When** the AI begins implementation,
- **Then** the AI MUST emit a clarifying question (CSV? JSON? Streaming? Paginated?) BEFORE writing any production code. Generating a guess and shipping it is a CODE-RED violation. The contract for clarifying-question patterns lives in `06-ai-optimization/`; every AC in that subfolder MUST reference at least one anti-hallucination rule. PRs that ship hallucinated implementations (e.g. invented API endpoints, fictional library methods) MUST be reverted, NOT patched forward.
- **Verifies:** `00-overview.md` MANDATORY block rule R5 + `06-ai-optimization/00-overview.md`.

### AC-CG-08 — CODE-RED rule R6 (Function/file/component size limits) is statically enforceable

- **Given** any source file under spec governance,
- **When** the file is parsed by a size-limit linter,
- **Then** every function MUST be 8–15 lines (excluding signature and closing brace), every file MUST be < 300 lines total, AND every React component file MUST be < 100 lines. Files exceeding 300 lines MUST be split via the same split algorithm specified in `spec/27-spec-toolchain/` for spec files (extract cohesive sub-modules). The 8-line minimum prevents premature one-liner extraction; the 15-line maximum forces decomposition. Three exempt categories: (a) generated code with `// AUTO-GENERATED` header, (b) test fixtures, (c) language-specific exceptions documented in the relevant subfolder's `97`.
- **Verifies:** `00-overview.md` MANDATORY block rule R6 + `linter-scripts/validate-guidelines.go` size checks.

### AC-CG-09 — Hybrid naming convention: PascalCase mandate with Rust exception

- **Given** an identifier in Go, TypeScript, PHP, or C# source,
- **When** the identifier is inspected,
- **Then** it MUST be PascalCase for exported/public symbols, JSON keys, enum values, AND database columns. **Rust is the ONLY exception** — Rust functions/variables MUST be snake_case (per RFC 430 / community standard / compiler enforcement) BUT Rust types/enums MUST be PascalCase AND Rust DB columns + serde-renamed JSON keys MUST be PascalCase via `#[serde(rename_all = "PascalCase")]`. The Rust exception is INTENTIONAL and codified in `00-overview.md`'s "Naming Convention Policy" block — fighting the Rust compiler to force PascalCase identifiers is FORBIDDEN.
- **Verifies:** `00-overview.md` "Naming Convention Policy" + `01-cross-language/11-key-naming-pascalcase.md` + `05-rust/01-naming-conventions.md`.

### AC-CG-10 — Each language subfolder has 5–34 module-specific GWT ACs (no table-row scaffolds)

- **Given** any language subfolder's `97-acceptance-criteria.md` (`02-typescript`, `03-golang`, `04-php`, `05-rust`, `07-csharp`),
- **When** the file is parsed,
- **Then** it MUST contain ≥ 5 `### AC-` headings AND each MUST include explicit `**Given**` / `**When**` / `**Then**` markers. Table-row criteria (e.g. `| AC-001 | Boolean principles ... |`) MUST NOT be the sole AC content — they MAY appear as a legacy index but the GWT triplets are authoritative. Subfolders currently scoring 0 ACs (TypeScript, Golang, Rust, C#, AI-Optimization, CI/CD-integration, cross-language root, security/axios) MUST be deepened in subsequent phases (16f+) to satisfy this rule.
- **Verifies:** `linter-scripts/check-tree-health.cjs` AC-count check + observed gap (15 subfolders currently have 0 ACs per Phase 16e scan).

### AC-CG-11 — `consolidated-review-guide.md` and `consolidated-review-guide-condensed.md` stay in lockstep

- **Given** the two consolidated review guides at `spec/02-coding-guidelines/consolidated-review-guide.md` and `consolidated-review-guide-condensed.md`,
- **When** either is edited,
- **Then** the other MUST be edited in the same PR to reflect the same rules at appropriate detail levels (full guide = examples + rationale; condensed = one-line bullet checklist). The condensed file MUST contain at most 1 line per rule in the full guide; deletions in the full guide MUST trigger deletions in the condensed guide. A PR that updates only one MUST be rejected at review.
- **Verifies:** `00-overview.md` Document Inventory + memory rule "Spec edits keep these in lockstep".

### AC-CG-12 — Subfolder cross-references resolve and use relative paths

- **Given** any markdown file in `spec/02-coding-guidelines/`,
- **When** `python3 linter-scripts/check-spec-cross-links.py` runs,
- **Then** every internal link MUST resolve to an existing file AND MUST use a relative path (NEVER an absolute path like `/spec/...` or a URL like `https://github.com/.../spec/...`). External links to specs in OTHER folders (e.g. `../03-error-manage/00-overview.md`) MUST traverse via `../`, NEVER duplicate spec content inline. Broken links MUST exit the linter with non-zero status AND block CI.
- **Verifies:** `linter-scripts/check-spec-cross-links.py` contract + AC-02 (per-subfolder lockstep).

### AC-CG-13 — Language-specific ACs MUST NOT contradict cross-language ACs

- **Given** an AC in `02-typescript/`, `03-golang/`, `04-php/`, `05-rust/`, or `07-csharp/`,
- **When** it overlaps with a rule in `01-cross-language/` (e.g. boolean naming, DRY, complexity cap),
- **Then** the language AC MUST be stricter or equal to the cross-language AC, NEVER weaker. A TypeScript-specific AC permitting `is_disabled` would contradict cross-language R2 and MUST be rejected at PR review. When a language idiom genuinely conflicts (Rust snake_case vs cross-language PascalCase), the conflict MUST be explicitly documented in `00-overview.md`'s "WHY RUST IS DIFFERENT" block AND the deviation MUST be limited to identifiers — NEVER DB columns or enum string values.
- **Verifies:** `00-overview.md` Naming Convention Policy + `01-cross-language/00-overview.md`.

### AC-CG-14 — App-specific subfolders (21+) MUST NOT define general coding rules

- **Given** any file in `21-app/`, `23-app-database/`, or `24-app-design-system-and-ui/`,
- **When** the file is reviewed,
- **Then** it MUST describe a specific application's features, schema, or design tokens — NEVER a reusable language rule. A reusable rule found in `21-app/` (e.g. "all functions return Result types") MUST be moved to the appropriate `01-20` subfolder AND the `21-app/` reference replaced with a relative link. The reverse direction (specific app data in `01-20`) is also forbidden. Note: `22-app-issues/` under `02-coding-guidelines/` is DEPRECATED — the canonical app-issues folder is `spec/25-app-issues/` per Phase-1 Triage v3.7.0.
- **Verifies:** `00-overview.md` "Numbering Convention" + Phase-1 Triage memory.

### AC-CG-15 — `06-ai-optimization/` is the canonical home for AI-specific rules; other subfolders MUST NOT duplicate

- **Given** an AI-specific rule (e.g. "do not invent library methods", "always cite source files in commit messages", "emit clarifying questions"),
- **When** the rule is added to the spec,
- **Then** it MUST live in `06-ai-optimization/` AND other subfolders MUST link to it via relative path, NEVER copy-paste the rule. The `00-overview.md` MANDATORY block at the top of `02-coding-guidelines/00-overview.md` is an EXCEPTION — it intentionally inlines the six CODE-RED rules for maximum AI visibility, AND those six MUST stay in lockstep with the canonical statements in their respective subfolders.
- **Verifies:** `00-overview.md` MANDATORY block + `06-ai-optimization/00-overview.md`.

### AC-CG-16 — Security policies (`11-security/`) are version-pinned and dependency-specific

- **Given** any third-party dependency referenced from §02 (e.g. axios, lodash, gorilla/mux, doctrine, tokio),
- **When** the dependency is added or upgraded,
- **Then** the version MUST be pinned EXACTLY (no `^`, `~`, `>=` ranges) AND MUST appear on the approved-versions list in `11-security/01-axios-version-control/` (or the analogous subfolder for that dependency). Versions NOT on the approved list MUST be treated as BLOCKED until manually verified per the Axios precedent (`1.14.0`/`0.30.3` approved; `1.14.1`/`0.30.4` blocked). A dependency upgrade PR that lacks a `11-security/` AC update MUST be rejected.
- **Verifies:** `11-security/00-overview.md` + `11-security/01-axios-version-control/00-overview.md`.

### AC-CG-17 — `09-powershell-integration/` and `10-research/` placeholder subfolders MUST be either populated or archived

- **Given** the §02 inventory shows `09-powershell-integration/` and `10-research/` with `Files: 0` in `00-overview.md`,
- **When** Phase 16f+ runs,
- **Then** EACH subfolder MUST either (a) gain ≥ 1 normative content file + ≥ 5 GWT ACs in its `97`, OR (b) be archived to `_archive/` with a `99` audit row explaining the deferral. Permanent placeholder subfolders are FORBIDDEN — they create false signals in the spec-index and skew tree-health metrics. The archive path follows the `_archive/21-git-logs-v1/` precedent (Phase-1 Triage v3.7.0).
- **Verifies:** `00-overview.md` Categories table + Phase-1 Triage memory.

### AC-CG-18 — Migration history MUST stay current; renames generate audit rows

- **Given** any structural change to `spec/02-coding-guidelines/` (rename, add, remove a subfolder),
- **When** the change merges,
- **Then** `00-overview.md` "Migration History" table MUST gain a row dated YYYY-MM-DD describing the change AND `98-changelog.md` MUST gain a SemVer-bumped entry AND `99-consistency-report.md` MUST reflect the new file inventory. The Phase 16e Phase-1 Triage precedents (slot 22 collision → §25, archive of `21-git-logs/`) are the canonical examples. Failing to update the history is a CODE-RED maintenance violation.
- **Verifies:** `00-overview.md` Migration History + memory rule "lockstep §98 + §99 + audit row".

### AC-CG-19 — Tree-health gate at module level MUST stay ≥ 95/100

- **Given** the §02 module folder is scored independently by `node linter-scripts/check-tree-health.cjs --module=02-coding-guidelines`,
- **When** the score is computed,
- **Then** it MUST be ≥ 95/100 — anything below blocks PR merge. The score weights: required-files presence (40%), AC-count compliance (25%), cross-link health (20%), changelog/consistency-report freshness within 7 days (15%). The 15 subfolders currently scoring 0 ACs (per the Phase 16e scan) drag this score below the gate AND MUST be remediated in Phase 16f before §02 can be declared green.
- **Verifies:** `linter-scripts/check-tree-health.cjs` module-level gate + observed Phase 16e scan results.

### AC-CG-20 — `97-acceptance-criteria.md` itself satisfies all rules it specifies

- **Given** this file (`spec/02-coding-guidelines/97-acceptance-criteria.md`),
- **When** it is parsed by the same checks AC-CG-02 / AC-CG-10 / AC-CG-12 / AC-CG-18 enforce on subfolders,
- **Then** it MUST contain ≥ 5 `### AC-` headings each with GWT triplets (this file has 20), MUST use only relative links, MUST have a SemVer-bumped banner on every edit (`v4.0.0` for Phase 16e), MUST have a matching row in `98-changelog.md`, AND MUST have an `inlined contracts` block so each AC is self-contained. Self-application is the "dogfooding" rule — the parent §97 cannot demand of subfolders what it does not satisfy itself.
- **Verifies:** AC-CG-02 + AC-CG-10 + AC-CG-12 + AC-CG-18 (recursive self-check).

---

## Legacy Index (preserved for traceability)

The following table-row criteria from v3.2.0 are preserved verbatim. They are NO LONGER authoritative — the GWT ACs above supersede them — but downstream linters / link-checkers may still reference these IDs. New linter checks SHOULD target `AC-CG-NN`, NOT `AC-CG-LEGACY-NNN`.

### AC-CG-LEGACY: Cross-Language

| ID | Criterion | Source |
|---|---|---|
| AC-CG-LEGACY-001 | Boolean principles define naming (`isX`, `hasX`, `canX`) and evaluation patterns | `01-cross-language/02-boolean-principles/00-overview.md` |
| AC-CG-LEGACY-002 | Casting elimination patterns cover type-safe alternatives to type assertions | `01-cross-language/03-casting-elimination-patterns.md` |
| AC-CG-LEGACY-003 | Code style defines formatting, naming, and structural conventions | `01-cross-language/04-code-style/00-overview.md` |
| AC-CG-LEGACY-004 | All guidelines include ❌ (forbidden) and ✅ (compliant) code examples | `01-cross-language/15-master-coding-guidelines/00-overview.md` |
| AC-CG-LEGACY-005 | DRY principles documented with refactoring patterns | `01-cross-language/08-dry-principles.md` |
| AC-CG-LEGACY-006 | Cyclomatic complexity limits defined with enforcement rules | `01-cross-language/06-cyclomatic-complexity.md` |

### AC-CG-LEGACY: TypeScript

| ID | Criterion | Source |
|---|---|---|
| AC-CG-LEGACY-007 | Connection status enums define all valid states with TypeScript string literals | `02-typescript/` |
| AC-CG-LEGACY-008 | Type definitions avoid `any` and use proper generic constraints | `02-typescript/` |
| AC-CG-LEGACY-009 | React component patterns follow functional component with hooks style | `02-typescript/` |
| AC-CG-LEGACY-010 | State management patterns use Zustand stores with typed selectors | `02-typescript/` |

### AC-CG-LEGACY: Golang

| ID | Criterion | Source |
|---|---|---|
| AC-CG-LEGACY-011 | Boolean standards define naming and evaluation patterns per Go idioms | `03-golang/02-boolean-standards.md` |
| AC-CG-LEGACY-012 | Error handling uses `apperror.Result[T]` pattern consistently | `03-golang/` |
| AC-CG-LEGACY-013 | HTTP method enum defines typed constants | `03-golang/03-httpmethod-enum.md` |
| AC-CG-LEGACY-014 | Service layer follows interface-based dependency injection | `03-golang/` |

### AC-CG-LEGACY: PHP

| ID | Criterion | Source |
|---|---|---|
| AC-CG-LEGACY-015 | Class naming follows WordPress PSR-4 autoloading conventions | `04-php/` |
| AC-CG-LEGACY-016 | Database queries use $wpdb prepared statements exclusively | `04-php/` |
| AC-CG-LEGACY-017 | Type declarations (parameter + return types) required on all functions | `04-php/` |
| AC-CG-LEGACY-018 | Input sanitization and output escaping follow WordPress security standards | `04-php/` |

### AC-CG-LEGACY: Rust

| ID | Criterion | Source |
|---|---|---|
| AC-CG-LEGACY-019 | Naming conventions follow Rust idioms (snake_case for functions, PascalCase for types) | `05-rust/` |
| AC-CG-LEGACY-020 | Error handling uses `Result<T, E>` pattern with custom error types | `05-rust/` |
| AC-CG-LEGACY-021 | Async patterns use tokio runtime with proper cancellation handling | `05-rust/` |
| AC-CG-LEGACY-022 | Memory safety patterns documented for FFI boundaries | `05-rust/` |

---

## Cross-References

- [Module overview](./00-overview.md)
- [Module changelog](./98-changelog.md)
- [Module consistency report](./99-consistency-report.md)
- [Cross-Language Standards](./01-cross-language/00-overview.md)
- [Error Management (priority spec)](../03-error-manage/00-overview.md)
- [Database Conventions](../04-database-conventions/00-overview.md)
- [AI Optimization](./06-ai-optimization/00-overview.md)
