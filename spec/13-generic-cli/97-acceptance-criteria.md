# Acceptance Criteria — Generic CLI Creation Guidelines — Overview

**Version:** 2.1.0  
**Updated:** 2026-04-29  
**Scope:** `spec/13-generic-cli/`

> **v2.0.0 (Phase 16a):** Added 15 module-specific Given/When/Then ACs (AC-06..AC-20) covering subcommand dispatch, flag parsing, three-layer config, multi-format output, exit-code contract, code-style limits, embedded help, date formatting, constants discipline, verbose logging, progress tracking, batch execution, shell completion, terminal output design, and post-install shell activation. The 5 generic structural ACs (AC-01..AC-05) are preserved verbatim — they validate the spec module itself; AC-06+ validate the **CLI implementation** that consumes the spec.

---

## Purpose

This document defines testable acceptance criteria for the **Generic CLI Creation Guidelines — Overview** module. Every criterion is verifiable from the module's content alone — an AI implementer or human reviewer can check pass/fail without external context.

---

## Criteria

### AC-01: Module entry point exists and is non-trivial
- **Given** the module folder `spec/13-generic-cli/`
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

---

## Module-Specific Criteria (Implementation Contract)

> The following ACs validate a CLI implementation that **consumes** this spec, not the spec module itself. Each is verifiable by running the built binary against the listed command and inspecting stdout/stderr/exit code or by source-grep against the implementation.

### AC-06: Subcommand dispatch is a single-level switch on `os.Args[1]`
- **Given** a CLI binary built per `03-subcommand-architecture.md`
- **When** the binary is invoked with any subcommand (e.g. `mycli build`, `mycli deploy --target=prod`, `mycli help`)
- **Then** the entry point (`main.go` or equivalent) MUST dispatch via a **single switch statement on `os.Args[1]`** — no nested routers, no command-tree libraries (cobra/urfave/clap-v3 are FORBIDDEN per the spec's "Convention over configuration" principle); AND each `case` MUST delegate to a single handler function `handle<Name>(args []string) int` that returns the process exit code; AND the `default` branch MUST print the unknown-command error to stderr in the form `unknown command: <name>` AND exit with code `2` (per AC-10 exit code contract); AND a binary invoked with **no** subcommand MUST print the top-level help (per AC-12) AND exit `0`; AND `os.Args[1]` access MUST be guarded by a `len(os.Args) >= 2` check before indexing — a panic on `index out of range` is a hard fail.
- **Source:** `03-subcommand-architecture.md` (Entry Point + Dispatch Pattern sections), `07-error-handling.md` (exit code 2 = misuse).
- **Verifies:** `linter-scripts/audit-spec-vs-code-v2.py` rubric v2.13 (G-CON-01 contract gate)

### AC-07: Every flag is kebab-case and registered per-command (no global flags)
- **Given** a CLI binary built per `04-flag-parsing.md`
- **When** any subcommand's `--help` is requested (e.g. `mycli build --help`)
- **Then** every flag listed MUST be **kebab-case** (`--dry-run`, `--max-retries`, `--output-dir` — NEVER `--dryRun`, `--max_retries`, or `--outputdir`); AND every flag MUST be registered against a **per-command `flag.FlagSet`** (NOT the global `flag.CommandLine`) so flags are scoped to the subcommand they belong to; AND short flags MAY exist only for frequently-used flags (`-v` for `--verbose`, `-h` for `--help`) — short flags for rarely-used options are FORBIDDEN; AND every flag MUST have a default value (`""`, `false`, `0` are all acceptable defaults — `nil` defaults are FORBIDDEN); AND every required positional argument missing from the invocation MUST cause the handler to print `error: missing required argument: <name>` to stderr AND exit `1` — handlers MUST NOT proceed with empty/zero values for required args; AND flag-name constants MUST live in `pkg/constants/flags.go` (per `15-constants-reference.md`) — no string literals like `"verbose"` may appear inline in handlers.
- **Source:** `04-flag-parsing.md` (Per-Command FlagSets + Flag Naming Conventions + Defaults sections), `15-constants-reference.md`.
- **Verifies:** `linter-scripts/check-spec-cross-links.py` §Phase 81 strict gate

### AC-08: Three-layer config merges in fixed precedence order
- **Given** a CLI binary built per `05-configuration.md`
- **When** the binary loads its configuration at startup
- **Then** values MUST resolve in **exactly this precedence (lowest to highest)**: (1) hardcoded defaults in `pkg/config/defaults.go`, (2) JSON config file at `~/.config/<binary-name>/config.json` (or `$XDG_CONFIG_HOME/<binary-name>/config.json` if set), (3) CLI flags from the current invocation; AND a flag value MUST always override a config-file value, which MUST always override a default — no exceptions, no per-key overrides of the precedence rule; AND the config file MUST be **flat JSON** — no nested objects beyond one level — so the Go struct can mirror the JSON 1:1 with no transformation logic; AND a missing config file MUST be treated as "use defaults" (NOT an error) — only a config file that is present-but-malformed is an error (`error: invalid config file at <path>: <reason>`, exit 1); AND the resolved final config MUST be readable via `mycli config show` which prints the merged JSON to stdout AND exits 0; AND environment variables are NOT a layer — env-var-driven config is FORBIDDEN per the spec's "Convention over configuration" principle (the only env vars consulted are `XDG_CONFIG_HOME` and `HOME` for path resolution).
- **Source:** `05-configuration.md` (Three-Layer Config + Config File Schema sections).
- **Verifies:** `linter-scripts/check-lockstep.cjs` §strict date+phase parity

### AC-09: Output formatters are pluggable and selected by `--format`
- **Given** a CLI binary built per `06-output-formatting.md`
- **When** any data-producing subcommand is invoked
- **Then** the binary MUST accept a `--format=<terminal|json|csv|markdown>` flag with default `terminal`; AND each format MUST be implemented as a **separate function in `pkg/output/`** (`renderTerminal`, `renderJSON`, `renderCSV`, `renderMarkdown`) — a single switch in the handler picks one; AND `--format=json` output MUST be parseable by `jq` with no preamble, no trailing whitespace beyond a single `\n`, and MUST use 2-space indentation; AND `--format=csv` MUST emit RFC-4180-compliant CSV (CRLF line endings, double-quote escaping, header row first); AND `--format=markdown` MUST emit a GitHub-Flavored-Markdown table; AND `terminal` format MUST detect non-TTY stdout (`isatty(1) == false`) AND automatically suppress ANSI color codes — piping to `less`, `cat`, or `grep` MUST produce plain text without escape sequences; AND output files written by `--output-dir=<path>` MUST be named `<command>-<timestamp>.<ext>` where timestamp is per AC-14 date format.
- **Source:** `06-output-formatting.md` (Multi-Format Output Strategy + Terminal Output sections), AC-14 (date format).

### AC-10: Exit codes follow a fixed five-value contract
- **Given** a CLI binary built per `07-error-handling.md`
- **When** the binary terminates for any reason
- **Then** the exit code MUST be one of exactly five values: **`0`** = success, **`1`** = generic runtime error (operation failed but invocation was valid), **`2`** = misuse (unknown command, invalid flags, missing required args), **`3`** = configuration error (config file malformed or unreadable), **`4`** = batch partial failure (some items succeeded, some failed — only emitted by `exec` subcommand per `18-batch-execution.md`); AND any other exit code (5–127, 128+, negative) is a SPEC VIOLATION — handlers that return arbitrary integers MUST be caught by a top-level normaliser that clamps to `1` and logs the violation; AND every error path MUST print a single line to **stderr** (NEVER stdout) in the form `error: <human-readable message>` — no stack traces in production builds, no multi-line errors except for batch summaries; AND error messages MUST be **actionable** — "file not found" alone is insufficient; "file not found: ./config.json (run `mycli init` to create it)" is correct; AND panics MUST be recovered at the top level and converted to exit `1` with the panic value printed to stderr.
- **Source:** `07-error-handling.md` (Exit Codes + Error Message Rules sections), `18-batch-execution.md` (exit 4 batch contract).

### AC-11: Code style limits are enforced
- **Given** a CLI implementation per `08-code-style.md`
- **When** any source file is inspected
- **Then** every function MUST be **≤ 50 lines** (counting non-blank, non-comment lines); AND every file MUST be **≤ 400 lines**; AND every package MUST have ≤ 15 files; AND every variable name MUST be either **camelCase** (locals + unexported) or **PascalCase** (exported) — `snake_case` is FORBIDDEN in Go code; AND every conditional MUST be **flat** — `if/else` chains > 3 branches MUST be refactored into a switch or table-lookup; AND nested `if` deeper than 3 levels MUST be refactored using guard-clause early returns; AND every magic string/number MUST live in a `constants` package (per `15-constants-reference.md`) — string literals appearing inline outside of error messages, log lines, or test fixtures are SPEC VIOLATIONS; AND every exported function MUST have a doc comment beginning with the function name (per Go convention).
- **Source:** `08-code-style.md` (Function length, file length, naming, conditionals sections), `15-constants-reference.md`.

### AC-12: Help system embeds Markdown files at compile time
- **Given** a CLI binary built per `09-help-system.md`
- **When** the user invokes `mycli help` or `mycli <subcommand> --help`
- **Then** the help text MUST come from **Markdown files embedded at compile time** via `//go:embed help/*.md` (or equivalent for the target language) — runtime file-reads from disk for help are FORBIDDEN (the binary must work standalone with no help-file dependency); AND `mycli help` (no args) MUST print the top-level help listing every subcommand with one-line descriptions; AND `mycli help <subcommand>` MUST be equivalent to `mycli <subcommand> --help`; AND `--help` MUST be intercepted **before** flag parsing so unknown flags don't cause errors when help is requested; AND help output MUST be paged through `less` only when stdout is a TTY AND output exceeds the terminal height — non-TTY MUST dump full help to stdout without paging; AND every subcommand MUST have a corresponding `help/<subcommand>.md` file — a missing help file is a build-time error (caught by `go test` per `12-testing.md`), not a runtime error.
- **Source:** `09-help-system.md` (Embedded help files + `--help` interception sections), `12-testing.md` (build-time help file presence test).

### AC-13: Date format is centralized in `pkg/dateformat/`
- **Given** a CLI implementation per `14-date-formatting.md`
- **When** any date or timestamp is rendered to user-facing output (terminal, JSON, CSV, log lines, file names)
- **Then** the date MUST be formatted by a **single function** `dateformat.Display(t time.Time) string` living in `pkg/dateformat/dateformat.go` — calls to `t.Format(...)` outside this package are SPEC VIOLATIONS; AND the canonical display layout MUST be `2006-01-02 15:04:05 MST` (Go reference time, ISO-8601-ish with timezone abbreviation); AND filename timestamps MUST use `dateformat.Filename(t)` returning `2006-01-02-150405` (no spaces, no colons, no timezone — filesystem-safe); AND JSON output MUST use `dateformat.ISO8601(t)` returning RFC-3339 (`2006-01-02T15:04:05Z07:00`) — JSON consumers expect machine-parseable timestamps, NOT the human-readable display format; AND all three functions MUST accept a `time.Time` (NOT a string, NOT a Unix epoch int) — input normalisation is the caller's responsibility; AND tests for these three functions MUST live in `pkg/dateformat/dateformat_test.go` and cover at least: UTC time, non-UTC time, zero time (`time.Time{}`), and far-future time (year 9999).
- **Source:** `14-date-formatting.md` (Principle + Pipeline + Layout + Implementation sections).

### AC-14: All constants live in `pkg/constants/` with category sub-files
- **Given** a CLI implementation per `15-constants-reference.md`
- **When** any source file outside `pkg/constants/` is grep'd for string literals
- **Then** the only allowed inline literals are: (a) error messages passed to `fmt.Errorf` / `errors.New`, (b) log format strings, (c) test fixture strings, (d) struct-tag literals (`json:"..."`); AND every other string literal MUST be a reference to a constant defined in `pkg/constants/`; AND `pkg/constants/` MUST be split into category sub-files: `flags.go` (flag names per AC-07), `commands.go` (subcommand names per AC-06), `paths.go` (file/directory paths), `formats.go` (output format names per AC-09), `exit.go` (exit code constants per AC-10); AND constant naming MUST follow `<Category><Name>` pattern: `FlagVerbose`, `CmdBuild`, `PathConfigDir`, `FormatJSON`, `ExitMisuse`; AND each sub-file MUST be ≤ 100 lines (constants packages CAN exceed AC-11's 50-line function limit because they contain only declarations, not functions).
- **Source:** `15-constants-reference.md` (Every constant category with naming patterns), AC-06/AC-07/AC-09/AC-10/AC-11 (cross-references for category-specific contracts).

### AC-15: Verbose logging is gated by `--verbose` and writes to stderr
- **Given** a CLI binary built per `16-verbose-logging.md`
- **When** the binary is invoked with `--verbose` (or `-v`) flag
- **Then** debug output MUST be written to **stderr** (NEVER stdout — stdout is reserved for the command's primary output per AC-09); AND debug lines MUST be prefixed with `[DEBUG] ` followed by the source location `<file>:<line>:` and the message; AND verbose output MUST include at minimum: (a) every external command invocation (binary + args + cwd), (b) every file read/write with full path, (c) every HTTP request with method + URL + status code, (d) every config-key resolution with the layer it came from per AC-08; AND when `--verbose` is NOT set, the verbose package's print functions MUST be **no-ops** (empty function bodies) — log filtering MUST happen at compile-time-equivalent via a package-level boolean check, NOT by writing-then-discarding lines (performance contract: verbose-disabled overhead must be < 0.1ms per call); AND verbose output MUST be redactable — secrets-tagged values (passwords, tokens, API keys identified by struct tags or naming convention) MUST be replaced with `<redacted>` even when `--verbose` is set.
- **Source:** `16-verbose-logging.md` (Purpose + Design Rules + Package Structure sections), AC-08 (config layer reporting), AC-09 (stdout reservation).

### AC-16: Progress tracking is rendered for any operation > 1 second
- **Given** a CLI binary built per `17-progress-tracking.md`
- **When** any subcommand performs a batch operation (multiple files, multiple network calls, multiple repo iterations)
- **Then** a progress indicator MUST be rendered to **stderr** (NEVER stdout, same reasoning as AC-15); AND the indicator MUST appear within 500ms of the operation starting AND update at least every 200ms (no "frozen" appearance); AND the format MUST be `[<current>/<total>] <action>: <item>` (e.g. `[12/47] processing: ./repos/foo`); AND when stdout is NOT a TTY, progress output MUST be SUPPRESSED (no progress noise in CI logs or piped output); AND on operation completion the progress line MUST be CLEARED (overwritten with spaces + carriage return) before the final summary is printed — progress indicators MUST NOT pollute scrollback; AND the progress package MUST expose a single `progress.New(total int) *Tracker` constructor and `tracker.Step(item string)` increment method — alternative APIs (callbacks, channels, observers) are FORBIDDEN per "Consistency over cleverness".
- **Source:** `17-progress-tracking.md`, AC-15 (stderr discipline).

### AC-17: Batch execution emits exit code 4 on partial failure
- **Given** a CLI binary built per `18-batch-execution.md` exposing an `exec` subcommand
- **When** `mycli exec --in=<list-file> '<command>'` is invoked over N items
- **Then** the binary MUST iterate through every item in the list (NEVER stop on first failure unless `--fail-fast` is explicitly set); AND each item's command output MUST be captured AND prefixed with `[<item>] ` when echoed to stderr; AND the final exit code MUST be: **`0`** if all items succeeded, **`4`** if some succeeded and some failed (partial), **`1`** if ALL items failed; AND a final summary MUST be printed to stderr in the form `summary: <ok>/<total> succeeded, <fail> failed` AND when `--format=json` is set per AC-09 the summary MUST also be available as structured JSON to stdout; AND the list file MUST support both newline-separated plain items AND a JSON array of objects (auto-detected by first non-whitespace char `[`); AND `--parallel=N` MUST be supported with a default of `1` (sequential) — when `N > 1`, output ordering MUST remain deterministic by buffering per-item output and flushing in input order.
- **Source:** `18-batch-execution.md`, AC-09 (`--format` contract), AC-10 (exit code 4 for partial).

### AC-18: Shell completion is generated per shell, not hand-written
- **Given** a CLI binary built per `19-shell-completion.md`
- **When** the user runs `mycli completion <bash|zsh|powershell|fish>`
- **Then** the binary MUST print to stdout a complete, sourceable shell-completion script for that shell — NEVER to a file (the user redirects to wherever they want); AND the script MUST be GENERATED at runtime from the binary's own subcommand + flag registry (NOT hand-maintained per shell); AND the script MUST tab-complete: (a) subcommand names, (b) flag names per subcommand, (c) flag values from a `--list-values` discovery mechanism for enum-like flags (e.g. `--format` returns `terminal json csv markdown` per AC-09); AND an undocumented hidden subcommand `mycli __complete <args>` MUST exist as the runtime completion provider invoked by the generated scripts (the user never calls this directly); AND `mycli completion` (no shell) MUST print an error `error: missing required argument: <shell>` AND exit `1` per AC-07.
- **Source:** `19-shell-completion.md` (Completion Subcommand + List Flag Behaviour + Completed Contexts sections).

### AC-19: Terminal output uses the documented color palette + section conventions
- **Given** a CLI binary built per `20-terminal-output-design.md`
- **When** any subcommand renders a "rich" terminal report (the default `--format=terminal` per AC-09)
- **Then** color usage MUST follow the documented palette: **green** = success, **red** = error/failure, **yellow** = warning, **cyan** = informational/headers, **gray** = de-emphasised/secondary metadata; AND no other ANSI colors (magenta, blue, white-bg, etc.) MAY be used unless added to the palette in `20-terminal-output-design.md` first; AND every report MUST start with a **header line** (cyan, surrounded by `═` U+2550 box-drawing characters); AND sections within a report MUST be separated by `─` U+2500 lines; AND tabular data MUST be rendered with aligned columns (NOT raw `\t` tabs which align unpredictably across terminal widths); AND when stdout is non-TTY (per AC-09), ALL color codes AND box-drawing characters MUST be stripped — fall back to plain ASCII (`=`, `-`); AND terminal output MUST handle terminal widths from 80 to 200 columns gracefully — wider data MUST wrap or truncate (with a `…` indicator), NEVER overflow horizontally.
- **Source:** `20-terminal-output-design.md`, AC-09 (TTY detection contract).

### AC-20: Post-install runs a `doctor` check and offers shell profile injection
- **Given** a CLI binary distributed per `21-post-install-shell-activation.md`
- **When** the user runs the install script (curl-pipe-bash, package manager, or self-update per `11-build-deploy.md`)
- **Then** the install script MUST invoke `mycli doctor` immediately after the binary lands on disk; AND `doctor` MUST check: (a) binary is on `$PATH`, (b) shell-completion script is sourced for the user's current shell (per AC-18), (c) config directory exists at `~/.config/<binary-name>/`, (d) any required external dependencies are present and meet minimum version; AND when checks fail, `doctor` MUST print a numbered list of remediations AND offer (interactively, not silently) to inject a shell-profile snippet (`source <(mycli completion <shell>)` + `export PATH=...:$PATH`) into `~/.bashrc` / `~/.zshrc` / `$PROFILE` (PowerShell) — the user MUST type `y` to confirm; AND when stdout is non-TTY, `doctor` MUST skip the interactive prompt AND print the snippet to stdout for manual sourcing; AND `doctor` MUST exit `0` if all checks pass, exit `1` if any check fails AND the user declined remediation, exit `0` if checks failed but the user accepted remediation (the remediation succeeded); AND a separate `mycli doctor --json` invocation MUST emit structured findings per AC-09.
- **Source:** `21-post-install-shell-activation.md` (post-install shell wrapper activation, `doctor` check, profile injection sections), AC-09/AC-18 (cross-references).

---

## Module-Specific Files

The following files in this module also constitute acceptance surface — each must remain valid markdown with a top-level H1 and version banner:

- `00-overview.md`
- `02-project-structure.md`
- `03-subcommand-architecture.md`
- `04-flag-parsing.md`
- `05-configuration.md`
- `06-output-formatting.md`
- `07-error-handling.md`
- `08-code-style.md`
- `09-help-system.md`
- `10-database.md`
- `11-build-deploy.md`
- `12-testing.md`
- `13-checklist.md`
- `14-date-formatting.md`
- `15-constants-reference.md`
- `16-verbose-logging.md`
- `17-progress-tracking.md`
- `18-batch-execution.md`
- `19-shell-completion.md`
- `20-terminal-output-design.md`
- `21-post-install-shell-activation.md`

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
