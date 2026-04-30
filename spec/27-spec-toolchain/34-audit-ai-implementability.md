# 34 — audit-ai-implementability.py

**Version:** 1.2.0  
**Updated:** 2026-04-30  
**Source:** [`linter-scripts/audit-ai-implementability.py`](../../linter-scripts/audit-ai-implementability.py)  
**Self-test:** [`linter-scripts/test/test-audit-ai-implementability.sh`](../../linter-scripts/test/test-audit-ai-implementability.sh)  
**Category:** Auditor (LLM-driven, deep-walk)

---

## Slot-range note

Slot **34** sits in the 30-39 auditor band — clean fit. Companion to slot 30 (`audit-spec-vs-code.py`) and slot 31 (`audit-spec-vs-code-v2.py`). Distinct from slot 33 (`check-ai-confidence.py`) which is **deterministic** read-only validation; slot 34 is **LLM-driven** semantic scoring.

---

## Purpose

Productionises the prototype harness used in **Phase 153 Tasks A1 + A2** (audit-v2 deep-walk). Scores every top-level `spec/<NN>-*` module on a 5-dimension AI-implementability rubric using `google/gemini-3-flash-preview` via the Lovable AI Gateway, and emits a markdown report ranked low → high.

The five dimensions, each 0-20 (total 0-100):

| Dim | Measures |
|---|---|
| **D1** Contract Clarity | types pinned, units explicit, error codes enumerated |
| **D2** AC Coverage | every behaviour has GWT + `**Verifies:**` clause |
| **D3** Edge / Error | nulls, concurrency, large inputs, timeouts, partial failures |
| **D4** Examples | sample I/O, code snippets, file paths, fixtures |
| **D5** Cross-Ref Closure | every external symbol resolved IN provided context |

Bands: **EXCELLENT** ≥90 · **GOOD** 75-89 · **NEEDS_WORK** 60-74 · **BLOCKING** <60.

---

## Improvements over Phase 153 Task A1 prototype

1. **Walks `*.md|*.json|*.yaml|*.yml|*.tmpl|*.toml`** — closes the spec/11 schemas/templates blind spot identified in Task A2 (the v1/v2 prototype only walked `*.md` and missed `powershell.schema.json`/`run.ps1` etc.).
2. **On-disk SHA-keyed cache** at `.lovable/cache/audit-ai/<module>.json` — re-runs are free until the bundle hash changes.
3. **`--module=<slug>` filter** for targeted re-scoring after edits.
4. **`--no-network`** prints bundle stats only (file count, byte count, SHA) — useful for self-test + dry-run cost estimation.
5. **`--json`** machine-readable output mirroring `check-ai-confidence.py` shape.
6. **Cloudflare 1010 immunity** — explicit `User-Agent: lovable-spec-audit/1.0` header (default Python UA gets blocked at >25 KB POST per Lesson #11).
7. **Tolerant JSON parser** — strips fenced code wrappers and stray backslashes the model occasionally emits.

---

## Inputs

- `spec/<module>/**/*.{md,json,yaml,yml,tmpl,toml}` — bundled up to 90 KB (Cloudflare-safe).
- `LOVABLE_API_KEY` env var — required unless `--no-network` is passed.

## Usage

```bash
python3 linter-scripts/audit-ai-implementability.py
python3 linter-scripts/audit-ai-implementability.py --module=04-database-conventions
python3 linter-scripts/audit-ai-implementability.py --no-network          # stats only
python3 linter-scripts/audit-ai-implementability.py --json                # machine-readable
python3 linter-scripts/audit-ai-implementability.py --strict              # CI gate (advisory until adoption)
```

## CLI flags

| Flag | Default | Purpose |
|------|---------|---------|
| `--module=<slug>` | all | Audit a single top-level module |
| `--no-network` | off | Print bundle stats only; never call gateway |
| `--force` | off | Ignore cache and re-score |
| `--json` | off | Machine-readable JSON to stdout |
| `--report-only` | off | Never fail; advisory mode (mirrors H1/P20/P48-1-fu1) |
| `--strict` | off | Exit 1 if any module scores BLOCKING (<60) |
| `--report=<path>` | `.lovable/memory/audit/v2-deterministic/audit-ai-implementability-latest.md` | Markdown report output |

## Outputs

- Stdout: per-module score line (or JSON if `--json`).
- File: ranked markdown report at `--report` path.
- Cache: `.lovable/cache/audit-ai/<module>.json` (gitignored).

## Status

**Advisory-by-default** per the H1 / P20 / P48-1-fu1 stamp pattern. Will graduate to a strict CI gate after adoption shows the rubric is stable AND the tree-wide score holds at GOOD or above.

**CI wiring (Phase 153 Task A5):** wired into `.github/workflows/spec-health.yml` between the Trace-map regression gate and the Summary step as `--report-only`. Step is skipped when `LOVABLE_API_KEY` is unset (community PRs from forks). Cache lives at `.lovable/cache/audit-ai/` and is repo-local.

---

## Acceptance Criteria

### AC-34-01 — Help surface advertises five mode flags `[medium]`
- **Given** the script invoked with `--help`.
- **When** stdout is captured.
- **Then** it MUST mention `--no-network`, `--module`, `--json`, `--report-only`, `--strict`.
- **Verifies:** §34 CLI surface stability so callers (CI, contributors, audit phases) can rely on flag names.

### AC-34-02 — `--no-network` exits 0 without API key `[critical]`
- **Given** `LOVABLE_API_KEY` is absent or unused.
- **When** the script runs with `--no-network`.
- **Then** it MUST exit 0 and emit one stats line per module.
- **Verifies:** §34 self-test contract — CI runners without an API key can still verify the script imports and walks the tree.

### AC-34-03 — `--module=<slug>` narrows scope `[high]`
- **Given** `--module=04-database-conventions`.
- **When** the script runs.
- **Then** stdout MUST mention `04-database-conventions` and MUST NOT mention any other top-level module.
- **Verifies:** §34 targeted-rerun contract for incremental audit phases.

### AC-34-04 — Unknown module slug exits 2 `[medium]`
- **Given** `--module=99-does-not-exist`.
- **When** the script runs.
- **Then** exit code MUST be 2 (CLI usage error), not 0 or 1.
- **Verifies:** §34 fail-loud contract — typos must not silently report "all green".

### AC-34-05 — `--json` emits parseable list `[high]`
- **Given** `--no-network --json --module=04-database-conventions`.
- **When** stdout is piped to `json.loads`.
- **Then** parsing MUST succeed and yield a list of length 1 with `module`, `bundle_sha`, `no_network: true`.
- **Verifies:** §34 machine-readable contract — downstream dashboards and gates depend on schema stability.

### AC-34-06 — Walker includes non-`*.md` artefacts `[critical]`
- **Given** `--no-network --module=11-powershell-integration`.
- **When** the bundle stats line is parsed.
- **Then** the file count MUST be ≥ 18 (verifies `schemas/powershell.schema.json` + `templates/powershell.json` + `templates/run.ps1` are walked).
- **Verifies:** §34 closure of the Phase 153 Task A2 blind spot — `*.md`-only walkers produced false-positive D5 CRITICALs.

### AC-34-07 — Cache hit when bundle SHA unchanged `[medium]`
- **Given** a previous run wrote `.lovable/cache/audit-ai/<module>.json`.
- **When** the script runs again on the same on-disk content.
- **Then** the cached row MUST be reused (`from_cache: true`) without invoking the gateway.
- **Verifies:** §34 reproducibility + cost-control contract; ensures clean runs are free.

### AC-34-08 — `--report-only` never fails `[medium]`
- **Given** any module scored BLOCKING.
- **When** the script runs with `--report-only --strict`.
- **Then** exit code MUST be 0 (`--report-only` overrides `--strict`).
- **Verifies:** §34 advisory-by-default contract — mirrors slot 33 `check-ai-confidence.py` and the H1/P20 pattern.

### AC-34-09 — Tier-1 contract files (`{00,97,98,99}-*.md`) prioritized in 90 KB bundle `[critical]`
- **Given** a module whose chunky `02-*` / `03-*` siblings would exhaust the `MAX_BYTES = 90000` cap before alphabetical iteration reaches `97-acceptance-criteria.md`,
- **When** `load_module_bundle()` orders files for inclusion,
- **Then** the four module-root contract files (`00-overview.md`, `97-acceptance-criteria.md`, `98-changelog.md`, `99-consistency-report.md`) MUST be placed FIRST in canonical order, followed by everything else under `WALK_GLOBS` alphabetically. Without this priority, the §97 binding contract is silently dropped from the prompt for any module > ~70 KB of feature/issue prose, and the auditor scores examples without seeing the contract — yielding a false-low D2 (AC coverage) and stable scores under contract edits (Phase 153 Task A6 first re-score loop produced ZERO movement on spec/05 for exactly this reason; bundle_sha changed, score didn't, because `97-acceptance-criteria.md` was alphabetically last and never made the cut). After the fix, spec/05 lifted 69 → 89 (+20) on the same content edits.
- **Verifies:** §34 contract-surface bundling guarantee — the auditor's prompt MUST contain the contract before the examples.
