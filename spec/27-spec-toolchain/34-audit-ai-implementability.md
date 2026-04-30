# 34 — audit-ai-implementability.py

**Version:** 1.3.0  
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

**Strict on BLOCKING (Phase 153 Task A12, 2026-04-30).** Graduated from `--report-only` advisory after Task A8's v5 rebaseline measured tree mean **84.7/100** (5 EXCELLENT · 18 GOOD · 0 NEEDS_WORK · 0 BLOCKING) — H10 graduation filter satisfied: (1) mechanically detectable (per-module score from gateway response), (2) active regression surface (any new module or contract amputation could drop into BLOCKING), (3) low false-positive risk (BLOCKING<60 sits 15 points below the current 75-floor — wide moat).

**CI wiring (Phase 153 Task A12 — graduated from A5):** runs `python3 linter-scripts/audit-ai-implementability.py --strict` in `.github/workflows/spec-health.yml` between the Trace-map regression gate and the Summary step. Exits 1 if any module scores BLOCKING (<60); GOOD/NEEDS_WORK still pass (advisory inside the report). Step is skipped when `LOVABLE_API_KEY` is unset (community PRs from forks — Lesson #15). Cache lives at `.lovable/cache/audit-ai/` and is repo-local. **Threshold lock**: the 4 modules at the structural 75-floor (03/12/17/25 — Rubric v6 ceiling per Lesson #29/#36/#37) are addressed by Rubric v7 (A15-A20), NOT by lowering the strict threshold. Lowering the threshold is **forbidden** without a corresponding Rubric v7 design memo update.

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

---

## Rubric v7 — Axis-driven dimension weight cascades (Phase 153 Task A17)

Rubric v6 (active in Tasks A1–A12) applied uniform 0–20 weights to D1–D5 across every module. The Phase 153 Task A8 v5 baseline exposed a **structural 75-point ceiling** on modules whose content axis is incompatible with uniform weighting:

| Module | v6 score | Axis | Penalty source |
|---|---|---|---|
| 03-error-manage | 75 | `audit-corpus` | D2 penalised "missing AC coverage" — but module *describes* errors, doesn't contract them |
| 12-cicd-pipeline-workflows | 75 | `integration-spec` | D5 penalised external GitHub Actions refs as "unresolved" |
| 17-consolidated-guidelines | 75 | `process-guidance` | D2 penalised checklist prose for not being GWT |
| 25-app-issues | 75 | `audit-corpus` | D3 penalised quoted bug descriptions as "unhandled edge cases" (per Lesson #29) |

Rubric v7 reads `content_axis` from each module's `00-overview.md` front-matter (introduced in Phase 153 Task A16) and applies axis-appropriate weight multipliers BEFORE summing to the 0–100 total. Sum-of-multipliers is **always 5.0** (mean 1.0) so total range stays 0–100 — multipliers redistribute scoring weight, they do not inflate it.

### Weight cascade (Normative)

| `content_axis` | D1 (Clarity) | D2 (AC Coverage) | D3 (Edge/Error) | D4 (Examples) | D5 (Cross-Ref) | Sum |
|---|---|---|---|---|---|---|
| `normative-contract` | 1.0 | **1.5** | **1.2** | 0.8 | 0.5 | 5.0 |
| `process-guidance` | **1.5** | **0.7** | 0.8 | 1.0 | 1.0 | 5.0 |
| `integration-spec` | 1.0 | 0.9 | 0.9 | **1.4** | **1.2** (allowed external) | 5.4 → renormalised to 5.0 |
| `audit-corpus` | 1.0 | **0.5** | **0.5** | 1.5 | **1.5** (citation density) | 5.0 |
| `tooling-spec` | 1.0 | **1.3** | 1.0 | **1.3** | 0.9 | 5.5 → renormalised to 5.0 |

Renormalisation rule: if raw sum ≠ 5.0, every multiplier is divided by `(raw_sum / 5.0)` so the module total stays bounded at 100.

### Per-axis caps + floor preservation

| `content_axis` | Soft cap | Floor | Rationale |
|---|---|---|---|
| `normative-contract` | 100 | 60 | Full range — these MUST be implementable |
| `process-guidance` | 95 | 60 | Inherent ambiguity — reaching 100 would require GWT-encoding human conventions |
| `integration-spec` | 95 | 60 | External-system uncertainty caps achievable D5 |
| `audit-corpus` | 95 | 60 | Per Lesson #29 — describing other specs has inherent semantic distance |
| `tooling-spec` | 100 | 60 | Full range — script ACs are GWT-checkable |

**Strict CI threshold remains 60 (BLOCKING) for every axis** — caps are upper bounds for the GOOD/EXCELLENT band assignment, not the strict gate. The 15-point moat between the v6 75-floor and the 60 strict threshold (per Lesson #40) is preserved across all axes.

### Acceptance Criteria

### AC-34-10 — Axis-driven weight multipliers applied per module `[critical]`
- **Given** a module's `00-overview.md` declares `content_axis: <one-of-5>`,
- **When** the auditor computes the per-module score,
- **Then** the five raw dimension scores (D1–D5, each 0–20) MUST be multiplied by the axis-appropriate multipliers from the Rubric v7 weight cascade table above BEFORE summing to the 0–100 total. The multiplier sum MUST be normalised to 5.0 (so the module total stays bounded at 100); for axes whose raw multiplier sum exceeds 5.0 (`integration-spec`=5.4, `tooling-spec`=5.5), every multiplier MUST be divided by `(raw_sum / 5.0)` before scoring.
- **Verifies:** §34 Rubric v7 contract — uniform v6 weighting penalised non-contract axes (Phase 153 Task A8 v5 baseline showed 4 modules at structural 75-floor); axis-appropriate weights close the ceiling per Lesson #29 + Lesson #36.

### AC-34-11 — Per-axis soft cap applied to band assignment, NOT to strict gate `[high]`
- **Given** a module's score after Rubric v7 multiplication,
- **When** the band (EXCELLENT/GOOD/NEEDS_WORK/BLOCKING) is computed,
- **Then** the soft cap from the per-axis caps table MUST be applied (e.g. `process-guidance` cannot exceed 95 even if raw weighted sum is 97); BUT the strict CI gate threshold MUST remain 60 (BLOCKING) for every axis. The 15-point moat between the v6 75-floor and the 60 strict threshold (Lesson #40) is preserved tree-wide.
- **Verifies:** §34 strict-gate stability under axis-aware scoring — caps adjust band labels without weakening the regression-detection contract Task A12 graduated.

### AC-34-12 — Missing or invalid `content_axis` fails fast `[critical]`
- **Given** a module's `00-overview.md` lacks `content_axis:` or declares a value outside `{normative-contract, process-guidance, integration-spec, audit-corpus, tooling-spec}`,
- **When** the auditor processes that module,
- **Then** the script MUST exit code 2 (CLI/data error) with a message naming the offending module + the missing/invalid value, and MUST NOT silently fall back to v6 uniform weighting. The Phase 153 Task A16 bulk injection guarantees all 23 top-level modules carry valid axis values; this AC enforces that any future module addition (or accidental front-matter deletion) breaks CI immediately.
- **Verifies:** §34 fail-loud contract — silent v6 fallback would mask Rubric v7 regressions; mirrors AC-34-04 (unknown `--module=` slug exits 2).
