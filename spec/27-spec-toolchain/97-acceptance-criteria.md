# Acceptance Criteria — Spec Toolchain

**Version:** 2.4.0
**Updated:** 2026-04-28 (Phase P15 / H10: added AC-T-26 codifying the §00 ↔ §98 Version-field parity gate landing + advisory-by-default rationale per AC-T-25 dispensation. AC count 25 → 26.)
**Scope:** `spec/27-spec-toolchain/`

---

## Module-level criteria

### AC-T-01 — Bijection between code and spec
- **Given** the set of executable files under `linter-scripts/` (extensions `.py`, `.cjs`, `.sh`, `.ps1`, `.go`) plus configuration files (`.toml`, `.allowlist`, `readme-cross-links.md`) and the set of workflows under `.github/workflows/*.yml`,
- **When** the spec sections in this module are enumerated,
- **Then** every code artifact MUST appear in exactly one spec section, and every spec section MUST point to exactly one code artifact that exists on disk.

### AC-T-02 — Inventory matches numbering convention
- **Given** the inventory tables in [`00-overview.md`](./00-overview.md),
- **When** each spec filename is checked against its category range (01–09 validators, 10–19 generators, 20–29 fillers, 30–39 auditors, 40–49 runners, 50–59 source validators, 60–69 configs, 70–79 CI),
- **Then** every entry MUST sit in the documented range for its category.

### AC-T-03 — Exit-code contract
- **Given** any spec file in slots 01–09 or 50–59 (validators),
- **When** the file is read,
- **Then** it MUST contain a section titled "Exit codes" listing at minimum `0` (pass), `1` (fail), and where applicable `2` (error / invocation problem).

### AC-T-04 — Idempotency declaration on fillers
- **Given** any spec file in slots 20–29,
- **When** the file is read,
- **Then** it MUST contain the literal phrase `idempotent` and explicitly state that re-runs on a satisfied tree are no-ops.

### AC-T-05 — Each spec lists the script's CLI surface
- **Given** any per-artifact spec section in this module,
- **When** the file is read,
- **Then** it MUST include a "Usage" code block showing the canonical invocation, and a list of supported CLI flags (or `_(none)_` if there are none).

### AC-T-06 — Each spec links back to the source file with a relative repo path
- **Given** any per-artifact spec section,
- **When** the file is read,
- **Then** it MUST link to the source file using a markdown link whose label is `` `linter-scripts/<name>` `` and whose target is `../../linter-scripts/<name>` (or `../../.github/workflows/<name>` for CI workflows).

### AC-T-07 — Slot immutability
- **Given** the §99 consistency report,
- **When** a script is deleted,
- **Then** its slot number MUST be marked "retired" in [`99-consistency-report.md`](./99-consistency-report.md) and MUST NOT be reused for a new artifact.

### AC-T-08 — Spec-health workflow trigger paths cover this module
- **Given** the workflow file `.github/workflows/spec-health.yml`,
- **When** its `on.push.paths` and `on.pull_request.paths` are inspected,
- **Then** they MUST include `spec/27-spec-toolchain/**` (so changes here re-run the gate) — see [`70-spec-health-yml.md`](./70-spec-health-yml.md) §3.

### AC-T-09 — Tree-health gate
- **Given** the entire `spec/` tree,
- **When** `node linter-scripts/check-tree-health.cjs --min=80` is run,
- **Then** this module MUST contribute `required=2/2` (overview + consistency report present) and the overall score MUST be ≥ 80.

### AC-T-10 — Spec cross-link gate (zero broken links)
- **Given** every relative link inside `spec/**/*.md`,
- **When** `python3 linter-scripts/check-spec-cross-links.py --github` is run as a CI gate (see `.github/workflows/spec-health.yml` step "Spec cross-link gate"),
- **Then** the script MUST exit 0 with `OK All internal spec cross-references resolve` and ANY broken target MUST fail the build (zero-broken-link contract). Allowlist exceptions live in `linter-scripts/spec-cross-links.allowlist`; entries there bypass the gate intentionally and MUST carry an inline comment justifying the exemption.
- **Verifies:** `linter-scripts/check-spec-cross-links.py` (binds the AC to its implementation per `linter-scripts/trace-map.toml:115-118`).

### AC-T-11 — Validator output goes to the correct stream
- **Given** any validator script in slots 01–09 or 50–59 (extensions `.py`, `.cjs`, `.sh`, `.ps1`, `.go`),
- **When** the script is executed and produces findings,
- **Then** ALL violation messages MUST be written to **stderr** (not stdout) and structured as `<file>:<line>: <rule-id>: <message>` so CI log parsers + IDE problem-matchers can extract them deterministically; success-summary lines (e.g. `0 findings`, `OK`) MAY go to stdout; mixing the two streams is FORBIDDEN because downstream tooling pipes stdout to JSON parsers and stderr to log aggregators.
- **Verifies:** AC-T-03 exit-code contract; `linter-scripts/run.sh` orchestrator (relies on stderr separation to surface failures); `.github/workflows/spec-health.yml` problem-matcher convention.

### AC-T-12 — Filler scripts MUST be safe to run in a tight loop
- **Given** any filler script in slots 20–29 (`fill-missing-acceptance-criteria.cjs`, `fill-missing-changelogs.cjs`, `fill-missing-consistency-reports.cjs`),
- **When** the script is executed N times consecutively against the same `spec/` tree,
- **Then** the second through Nth runs MUST produce **byte-identical** output to the disk state at the end of the first run — no new files created, no existing files mutated, no banner timestamps bumped, no AC bodies regenerated. Idempotency is verified by the `git diff --exit-code` invariant in `linter-scripts/run.sh` after the self-heal pipeline. Re-running a filler against an already-complete tree MUST exit `0` with a stdout summary like `0 files created` and zero stderr output.
- **Verifies:** AC-T-04 idempotency declaration; SELF-HEAL pipeline contract in `mem://index.md` Core; `linter-scripts/run.sh` post-fill `git diff` gate.

### AC-T-13 — Generators are deterministic given identical disk truth
- **Given** any generator in slots 10–19 (e.g. `generate-spec-index.cjs`, `generate-dashboard-data.cjs`, `generate-trace-map.py`),
- **When** the generator is run twice on the same `spec/` snapshot (no edits between runs),
- **Then** the produced artifact MUST be **byte-identical** between runs — no embedded `Date.now()`, no `Math.random()`, no environment-leaked values (`$USER`, `$HOSTNAME`, `process.env.CI`), no map-iteration order non-determinism (use `Array.from(map).sort()` or equivalent). Embedded `Generated: <date>` lines MUST use a **content-derived** timestamp (`git log -1 --format=%cI` of the latest spec-tree change) rather than `new Date()` — non-deterministic generators trigger phantom diffs that drown real changes in PRs.
- **Verifies:** AC-T-01 bijection (regenerated artifacts must round-trip); `linter-scripts/generate-spec-index.cjs`; `linter-scripts/generate-dashboard-data.cjs`.

### AC-T-14 — Auditor scripts MUST emit machine-readable JSON alongside human-readable Markdown
- **Given** any auditor script in slots 30–39 (`audit-spec-vs-code.py`, `audit-spec-vs-code-v2.py`),
- **When** the auditor is invoked with `--format json` (mandatory flag),
- **Then** the output MUST be valid JSON parseable by `json.loads()` / `JSON.parse()` with a fixed top-level shape `{score: number, max_score: number, grade: string, findings: Array<{rule_id: string, severity: "low"|"medium"|"high"|"critical", file: string, line: number|null, message: string}>, generated_at: string}`. The default invocation (no flag) emits Markdown for humans. Auditors WITHOUT a JSON mode are FORBIDDEN because the dashboard generator (§11) consumes JSON only. Schema versioning: bumping the JSON shape is a major version of the auditor and triggers a §98 changelog entry.
- **Verifies:** AC-T-05 CLI surface; §11 `generate-dashboard-data.cjs` (consumer); `mem://index.md` measured-not-narrated rule.

### AC-T-15 — Configuration files (slots 60–69) MUST be self-validating
- **Given** any configuration file in slots 60–69 (`forbidden-strings.toml`, `spec-cross-links.allowlist`, `spec-folder-refs.allowlist`, `readme-cross-links.md`),
- **When** the file is loaded by its consumer validator,
- **Then** the consumer MUST refuse to start (exit `2`, NOT exit `1`) if the config is malformed: TOML files MUST be parseable by `tomllib.load()`; `.allowlist` files MUST follow the documented `# comment` + `<path>` line format; every allowlist entry MUST carry a trailing `# reason: <free-text>` comment explaining why the exception exists (silent allowlist entries accumulate as zombie waivers). The consumer's spec section MUST cite the config's spec section bidirectionally — §03 ↔ §60, §01 ↔ §61, §02 ↔ §62.
- **Verifies:** AC-T-06 bidirectional source links; §03/§60 contract; §01/§61 contract; §02/§62 contract.

### AC-T-16 — Runners (slots 40–49) MUST be functionally equivalent across platforms
- **Given** the two orchestrator entry points `linter-scripts/run.sh` (slot 40) and `linter-scripts/run.ps1` (slot 41),
- **When** both are executed against the same `spec/` tree on Linux + Windows respectively,
- **Then** they MUST execute the SAME ordered pipeline: (1) validate → (2) fill-consistency → (3) fill-AC → (4) fill-changelogs → (5) regen-index → (6) tree-health gate. Each pipeline step MUST exit with the same code on both platforms (deterministic to within line-ending normalization). Adding a new step requires editing BOTH runners in the same PR — drift between `run.sh` and `run.ps1` is a CI failure caught by a future twin-diff linter. The shared step list lives in `mem://index.md` Core ("SELF-HEAL pipeline") so a single source of truth governs both.
- **Verifies:** AC-T-01 bijection; SELF-HEAL pipeline in `mem://index.md` Core; §40/§41 spec sections.

### AC-T-17 — Trace-map (§14) MUST round-trip from spec to code and back
- **Given** the spec ↔ code traceability map produced by `linter-scripts/generate-trace-map.py` (slot 14) consuming `linter-scripts/trace-map.toml`,
- **When** the trace map is rebuilt from disk truth,
- **Then** every spec module folder MUST appear as either a `[[mappings]]` entry (with at least one `target` path that exists on disk) OR an explicit `[[orphans]]` entry (with a written justification ≥ 20 chars). `target` paths MUST resolve to files that exist (no dangling code references); spec sections MUST resolve to existing folders (no dangling spec references). Orphan growth (delta ≥ 1 since main branch) fails CI via `check-trace-map-regression.py` (slot 17). The three FORBIDDEN trace-map ideas (auto-proposer, OpenAPI export, sub-file endpoint extraction) per `mem://constraints/forbidden-trace-map-ideas` MUST NOT be implemented or scaffolded — hard-block.
- **Verifies:** §14 generator; §17 regression gate; `mem://constraints/forbidden-trace-map-ideas` (FORBIDDEN ideas).

### AC-T-18 — Twin implementations (Python + Go) MUST agree byte-for-byte
- **Given** the validator twins `linter-scripts/validate-guidelines.py` (slot 50) and `linter-scripts/validate-guidelines.go` (slot 51), or any future twin-pair where Python is the reference and Go is the port,
- **When** both binaries are run against the same source tree,
- **Then** the union of findings MUST be identical: same rule IDs, same file paths, same line numbers, same severity, same message text (modulo language-specific quote characters which MUST be normalized to ASCII `"`). Drift is detected by a daily CI job that diffs `validate-guidelines.py --format json` against `validate-guidelines.go --format json` and fails on any mismatch. The Python implementation is the SOURCE OF TRUTH — when adding a new rule, the Python version lands first; the Go port follows in the same PR. Shipping the Go port WITHOUT the Python rule update is FORBIDDEN.
- **Verifies:** AC-T-14 JSON output contract; §50/§51 spec sections; `mem://specs/full-tree-audit-v4.md` twin-implementation invariant.

### AC-T-19 — CI workflows (slots 70–79) MUST trigger on every relevant path
- **Given** the CI workflow `.github/workflows/spec-health.yml` (slot 70),
- **When** its `on.push.paths` and `on.pull_request.paths` arrays are inspected,
- **Then** they MUST include ALL of: `spec/**` (any spec edit re-runs the gate), `linter-scripts/**` (any toolchain edit re-runs the gate), `.github/workflows/spec-health.yml` (workflow edits self-validate), `linter-scripts/spec-cross-links.allowlist` (allowlist edits re-validate), `linter-scripts/forbidden-strings.toml` (config edits re-validate). Workflow MUST run on `push` to `main` AND on `pull_request` to `main` — single-trigger workflows are FORBIDDEN because they leak unguarded merges. The minimum-score threshold MUST be `100` (locked at A+ per `mem://index.md` Core) — lowering the threshold is a major version bump of slot 70 and requires a §98 entry with written justification.
- **Verifies:** AC-T-08 trigger paths; §70 spec section; `mem://index.md` Core "CI threshold locked at 100".

### AC-T-20 — `trace-map.md` is informational, NOT acceptance surface
- **Given** the `trace-map.md` file present in this module (alongside slots 01–79),
- **When** the file inventory in `99-consistency-report.md` is built,
- **Then** `trace-map.md` MUST be classified as a **rendered output** of slot 14 (`generate-trace-map.py`) and MUST NOT carry a slot number, version banner, or §97 acceptance criteria of its own. The file is regenerated by §14 on every spec change and is NOT hand-edited. Its acceptance surface is delegated entirely to AC-T-17 (round-trip integrity). Adding a slot number to `trace-map.md` (e.g. `80-trace-map.md`) is FORBIDDEN — slots 80+ are reserved for future categories not yet defined. The file MUST be referenced from §14's spec section as "Output: `./trace-map.md`" so readers can discover the rendered artifact without searching.
- **Verifies:** AC-T-17 trace-map round-trip; §14 spec section; slot-immutability rule (slots 80+ reserved).

### AC-T-21 — Phase 107 orphan ledger as transitional INV-01 satisfaction (Phase 108-min, INV-08)
- **Given** an executable file under `linter-scripts/` or `.github/workflows/` exists WITHOUT a corresponding `spec/27-spec-toolchain/NN-*.md` slot,
- **When** `linter-scripts/test/test-overview-inventory-parity.sh` (Phase 112) enumerates the §27 inventory triangle (filesystem ↔ §00 overview ↔ Phase 107 orphan ledger),
- **Then** the script MUST exit 0 if AND ONLY IF the orphan code file is listed in the "Code → Spec orphans" table of `.lovable/memory/audit/v2-deterministic/phase-107-overview-inventory-drift-audit.md`. A code file that is neither specced in §27 NOR ledgered in Phase 107 MUST cause exit 1; AND every ledger entry MUST migrate to a real `NN-*.md` spec within two release cycles (sustained ledger-only tracking is technical debt, not a permanent home — see Phase 108 Strategy B for the migration cleanup); AND adding a new orphan to the ledger MUST happen in the SAME PR that adds the code (no after-the-fact ledger backfills); AND the ledger is **acknowledgement, not absolution**: it satisfies the INV-01 gate transitionally but does NOT exempt the code from the eventual `NN-*.md` spec requirement.
- **Verifies:** INV-08 (Normative Contract block in §00 overview), Phase 112 self-test (`linter-scripts/test/test-overview-inventory-parity.sh`), AC-31-31 (the upstream invariant Phase 112 enforces), Phase 107 orphan ledger (`.lovable/memory/audit/v2-deterministic/phase-107-overview-inventory-drift-audit.md`), Phase 108 backlog (Strategy B migration plan).

### AC-T-22 — Slot 18 (`check-mermaid-syntax.mjs`) is bijection-satisfying (Phase 108-full)
- **Given** [`spec/27-spec-toolchain/18-check-mermaid-syntax.md`](./18-check-mermaid-syntax.md) exists and references `linter-scripts/check-mermaid-syntax.mjs`,
- **When** `linter-scripts/test/test-overview-inventory-parity.sh` and `node linter-scripts/check-tree-health.cjs --strict` run,
- **Then** both MUST exit 0 with the script counted as specced (NOT ledger-tracked); AND the §00 inventory row in the Generators table MUST point at the slot-18 spec; AND the script's slot-range exception (validator inside the 10-19 generator band) MUST be documented in §18 and acknowledged in this AC as a Phase 108-full deliberate choice (not a violation of INV-03 — the slot is new, not relabelled).
- **Verifies:** INV-01, INV-02, INV-08 (this AC moves O1 OUT of the ledger), Phase 107 ledger row O1 (now marked migrated), Phase 108-full retrospective.

### AC-T-23 — Slot 19 (`check-memo-retrospective-headings.py`) is bijection-satisfying (Phase 108-full)
- **Given** [`spec/27-spec-toolchain/19-check-memo-retrospective-headings.md`](./19-check-memo-retrospective-headings.md) exists and references `linter-scripts/check-memo-retrospective-headings.py`,
- **When** `linter-scripts/test/test-overview-inventory-parity.sh` and `node linter-scripts/check-tree-health.cjs --strict` run,
- **Then** both MUST exit 0 with the script counted as specced (NOT ledger-tracked); AND the §00 inventory row in the Generators table MUST point at the slot-19 spec; AND the slot-range exception (validator inside the 10-19 band) MUST be documented in §19 alongside the Phase 100 cadence-retirement rationale this script enforces.
- **Verifies:** INV-01, INV-02, INV-08, Phase 100 cadence retirement, Phase 107 ledger row O2 (now marked migrated), Phase 108-full retrospective.

### AC-T-24 — Slot 25 (`deepen-consistency-reports.py`) is bijection-satisfying (Phase 108-full)
- **Given** [`spec/27-spec-toolchain/25-deepen-consistency-reports.md`](./25-deepen-consistency-reports.md) exists and references `linter-scripts/deepen-consistency-reports.py`,
- **When** `linter-scripts/test/test-overview-inventory-parity.sh` and `node linter-scripts/check-tree-health.cjs --strict` run,
- **Then** both MUST exit 0 with the script counted as specced; AND the §00 inventory row in the Fillers table MUST point at the slot-25 spec; AND slot 25 satisfies the kind-range bijection (filler in 20-29 band) — no exception note required.
- **Verifies:** INV-01, INV-02, INV-08, Phase 21 deepen-sweep origin, Phase 107 ledger row O3 (now marked migrated), Phase 108-full retrospective.

### AC-T-25 — Spec-index drift gate MUST be strict (Phase 30)
- **Given** the `Spec-index drift gate` step in `.github/workflows/spec-health.yml` (Phase 29 root-cause: previously named "Regenerate spec-index.md (drift check)" and exited 0 with a `⚠️` warning when `git status --porcelain spec/` reported a delta after `node linter-scripts/generate-spec-index.cjs`),
- **When** any future commit causes `node linter-scripts/generate-spec-index.cjs` to produce a `spec/` filesystem delta (e.g. a file/version bump landed without re-running `bash linter-scripts/run.sh` locally),
- **Then** the workflow step MUST `exit 1` (strict mode), printing the `git diff --stat` and a `head -100` of `git diff` so the contributor can locate the stale entries; AND the script enumeration footer in `audit-spec-vs-code-v2.py` MUST list this gate as entry #18 of the QA tooling baseline; AND `linter-scripts/test/test-qa-baseline-footer.sh`'s workflow-gates awk MUST include `/Spec-index drift gate/` so footer-rows = workflow-gates = declared-count parity holds at 18/18/18.
- **Verifies:** AC-31-31 (multi-file enumeration parity at the 4-file site: script `RUBRIC_VERSION` + `00-index.md` footer + `EXECUTIVE-SUMMARY.md` cross-ref + `spec-health.yml` step list — gate count 17 → 18, RUBRIC v2.26 → v2.27); codifies the **Phase 29 root-cause lesson** "advisory CI gates silently rot" (second instance after the Phase H1 session-persistence-regression class — first instance never cleanly auto-detected); also codifies the **second-order lesson** "generator artifacts in `run.sh` need CI parity" (treat `spec-index.md` as a build artifact whose canonical source is the regenerator, not a hand-edited document).

### AC-T-26 — §00 ↔ §98 Version-field parity gate (Phase P15 / H10)
- **Given** [`spec/27-spec-toolchain/29-check-version-parity.md`](./29-check-version-parity.md) and `linter-scripts/check-version-parity.py` exist as gate #19 of the QA tooling baseline (advisory-by-default per AC-T-25 dispensation; P15 baseline sweep found 59 / 74 eligible modules drifting, making strict-from-day-1 prohibitive),
- **When** the `Version-field parity gate (Phase P15 / H10)` step runs in `.github/workflows/spec-health.yml`,
- **Then** the step MUST first execute `bash linter-scripts/test/test-check-version-parity.sh` (10/10 assertions T1–T10) and then `python3 linter-scripts/check-version-parity.py` (default mode, exit 0 even on mismatch — the contract is that drift is reported but advisory); AND the script enumeration footer in `audit-spec-vs-code-v2.py` MUST list this gate as entry #19; AND `test-qa-baseline-footer.sh`'s workflow-gates awk MUST include `/Version-field parity gate/` so footer-rows = workflow-gates = declared-count parity holds at 19/19/19; AND per the H1 workflow-step-parity lesson the self-test MUST be collapsed into this gate's step (no standalone self-test step) since it exclusively exercises one numbered footer gate.
- **Verifies:** AC-31-31 (multi-file enumeration parity at the 4-file site: `RUBRIC_VERSION` v2.27 → v2.28 + `00-index.md` footer 18 → 19 gates + `EXECUTIVE-SUMMARY.md` `+ P15` appended + `spec-health.yml` step list); AC-T-25 (advisory dispensation rationale: 59/74 surface = phased-rollout justification documented in slot-29 spec's "Why advisory-by-default" subsection); codifies the **Phase 21 lesson** "lockstep gate L1 only checks date relations, not version strings — §00 banner can drift many releases behind §98 while lockstep stays green"; also codifies the **Phase P15 secondary lesson** "audit retrospective surface estimates are unreliable until validated by a mechanical sweep" (the Phase 21 disposition note "1/3 historical incident, low surface" understated the real surface by ~60×).

---

## Per-artifact criteria

Per-script acceptance criteria live inside each per-artifact spec file (slots 01–79) under their own "Acceptance criteria" section. The §97 file aggregates only module-level rules.

---

## Validation

```bash
bash linter-scripts/run.sh                          # full pipeline
node linter-scripts/check-tree-health.cjs --min=80  # gate
python3 linter-scripts/check-spec-cross-links.py    # link check
```

All commands MUST exit `0` for this module's acceptance to hold.
