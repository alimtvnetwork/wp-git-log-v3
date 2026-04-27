# 31 — audit-spec-vs-code-v2.py

**Version:** 1.8.0  
**Updated:** 2026-04-27  
**Source:** [`linter-scripts/audit-spec-vs-code-v2.py`](../../linter-scripts/audit-spec-vs-code-v2.py) (script v2.14)  
**Category:** Auditor (AI-driven by default; **deterministic mode** + **hard scoring gates** + **CI threshold flags**)  
**Predecessor:** §30 [`30-audit-spec-vs-code.md`](./30-audit-spec-vs-code.md)

---

## Purpose

AI-implementability audit. Asks: *"Could a mediocre AI ship a working implementation from this spec alone with zero human clarification?"*

## Methodology

1. Broader code index: `linter-scripts/` + `.github/` + `src/` presence.
2. Deterministic pre-checks computed BEFORE AI scoring (so AI can be calibrated):
   - waffle ratio (should/may/might/optionally per 1k chars) — **prose only** (v2.4)
   - contract presence (DDL, JSON, TS enums, YAML/OpenAPI, Mermaid)
   - cross-spec link health (broken count) — **prose only** (v2.6); links inside fenced code blocks (e.g. `markdown` template examples) are excluded
   - AC count + Given/When/Then block count
   - TODO/TBD/FIXME density — **prose only** (v2.4); tokens inside fenced code blocks and inline `code` spans are excluded
3. AI receives metrics + raw digest, must justify scores against them.
4. Outputs blast-radius (0–10): how many other specs would benefit from fixing this one.

## Weights

| Dimension | Weight |
|-----------|-------:|
| Implementability | 35% |
| Completeness | 20% |
| Alignment | 15% |
| Consistency | 10% |
| Clarity | 10% |
| Testability | 7% |
| Maintainability | 3% |

## Usage

```bash
python3 linter-scripts/audit-spec-vs-code-v2.py                                    # AI mode
AUDIT_ONLY="22-git-logs-v2" python3 linter-scripts/audit-spec-vs-code-v2.py        # smoke test one module
AUDIT_DETERMINISTIC=1 python3 linter-scripts/audit-spec-vs-code-v2.py              # deterministic mode
AUDIT_DETERMINISTIC=1 python3 linter-scripts/audit-spec-vs-code-v2.py \
    --min-weighted=97 --min-impl=99                                                # CI threshold gate (v2.12)
```

### CLI flags (v2.12, Phase 81)

| Flag | Type | Effect |
|------|------|--------|
| `--min-weighted=N` | int 0–100 | Exit non-zero when the **mean weighted score** across all audited modules falls below `N`. Stderr emits `✗ FAIL: weighted mean X < threshold N`. |
| `--min-impl=N` | int 0–100 | Exit non-zero when the **mean implementability score** falls below `N`. Stderr emits `✗ FAIL: implementability mean X < threshold N`. |

When at least one threshold is supplied AND none fail, stderr emits `✓ PASS: thresholds met`. When neither flag is supplied the script preserves its pre-v2.12 behaviour (exit 0 unless an AI-mode module errored). Used by the `spec-health.yml` workflow audit gate (currently `--min-weighted=97 --min-impl=99`, set in Phase 84).

## Modes

| Mode | Trigger | Output dir | Reproducibility |
|------|---------|------------|-----------------|
| AI (default) | no env var | `.lovable/memory/audit/v2/` | Non-deterministic (model-dependent) |
| Deterministic | `AUDIT_DETERMINISTIC=1` | `.lovable/memory/audit/v2-deterministic/` | **Byte-identical** across runs |

Deterministic mode bypasses the AI gateway entirely and scores each module from a pure-function rubric over the same `deterministic_metrics()` digest used in AI mode. JSON output is sorted by module name, written with `sort_keys=True`, uses ASCII encoding, and ends with a single trailing newline — guaranteeing identical SHA-256 across consecutive runs on the same spec tree.

## Environment variables

| Var | Purpose |
|-----|---------|
| `LOVABLE_API_KEY` | Required in AI mode — Lovable AI Gateway credential |
| `AUDIT_ONLY` | Substring filter; only audit modules whose path matches |
| `AUDIT_DETERMINISTIC` | `1`/`true`/`yes` → enable deterministic mode (no AI calls) |

## Outputs

- `<output-dir>/<module>.md` per module (overwritten).
- `<output-dir>/00-index.md` — full ranking + blast-radius leaderboard.
- `<output-dir>/EXECUTIVE-SUMMARY.md` — TL;DR.
- `<output-dir>/raw-results.json` — machine-readable; byte-identical across runs in deterministic mode.

## Exit codes

| Code | Meaning |
|------|---------|
| 0 | Audit complete |
| 1 | At least one module failed AI scoring (others still written) |
| 2 | `LOVABLE_API_KEY` missing (AI mode only) |

## Acceptance criteria

### AC-31-01 — Output lives under `v2/` subfolder
- **Given** a successful run,
- **When** `.lovable/memory/audit/` is listed,
- **Then** all v2 outputs MUST be inside the `v2/` subfolder (not mixed with v1).

### AC-31-02 — Implementability dominates the weighted score
- **Given** the rubric in code,
- **When** weights are summed,
- **Then** `implementability` MUST equal 35 and total MUST equal 100 (asserted at module load).

### AC-31-03 — Deterministic metrics include `.mmd` files
- **Given** a module folder containing `.mmd` files (e.g. `26-gitlogs-diagrams`),
- **When** the audit runs,
- **Then** `metrics.mmd_files` MUST equal the count of `.mmd` files and `metrics.has_mermaid` MUST be `true`.

### AC-31-04 — Blast-radius leaderboard surfaces foundational specs
- **Given** the generated `00-index.md`,
- **When** the "High blast-radius fixes" table is read,
- **Then** entries MUST be sorted by `(-blast_radius, weighted_overall)` (highest blast first).

### AC-31-05 — `AUDIT_ONLY` smoke test mode
- **Given** `AUDIT_ONLY="22-git-logs-v2"`,
- **When** the script runs,
- **Then** exactly one module MUST be audited.

### AC-31-06 — Deterministic mode produces byte-identical JSON
- **Given** `AUDIT_DETERMINISTIC=1` and an unchanged spec tree,
- **When** the script is run twice consecutively,
- **Then** `.lovable/memory/audit/v2-deterministic/raw-results.json` from both runs MUST have the same SHA-256 hash and identical byte length.

### AC-31-07 — Deterministic mode writes to a separate output directory
- **Given** `AUDIT_DETERMINISTIC=1`,
- **When** the script runs,
- **Then** outputs MUST be written under `.lovable/memory/audit/v2-deterministic/` and MUST NOT touch `.lovable/memory/audit/v2/`.

### AC-31-08 — Deterministic mode performs zero AI calls
- **Given** `AUDIT_DETERMINISTIC=1` and `LOVABLE_API_KEY` unset,
- **When** the script runs,
- **Then** it MUST complete with exit code 0 (no network call, no import of `lovable_ai`).

## Hard scoring gates

After the rubric computes raw per-dimension scores, a fixed table of **hard gates** is applied. Each gate caps ONE dimension when its predicate (a function of `metrics`) is true. Gates run in both deterministic AND AI mode — the AI cannot exceed these ceilings even if it gives an over-generous score.

| Gate id | Dimension | Cap | Trigger |
|---------|-----------|----:|---------|
| `G-LINK-01` | consistency | 70 | `links_broken > 0` |
| `G-LINK-02` | alignment | 60 | `links_broken >= 3` |
| `G-AC-01`   | testability | 20 | `ac_count == 0` |
| `G-AC-02`   | testability | 60 | `ac_count > 0 and gwt_block_count == 0` |
| `G-CON-01`  | implementability | 50 | No `sql/json/ts/yaml` contract block in body (skip when `kind ∈ {tracker, index, meta-toolchain}`, v2.7; `meta-toolchain` also satisfies via `has_normative_contract` text-block, v2.8) |
| `G-CON-02`  | implementability | 30 | `overview_chars < 500` (skip when `kind ∈ {tracker, index}`, v2.7) |
| `G-WAF-01`  | clarity | 70 | `waffle_per_kchar > 3` |
| `G-WAF-02`  | clarity | 50 | `waffle_per_kchar > 6` |
| `G-CR-01`   | maintainability | 60 | Missing `99-consistency-report.md` |
| `G-TODO-01` | completeness | 70 | `todo_density >= 3` (skip when `kind: meta-toolchain`, v2.5) |

The result envelope adds two new top-level keys:
- `raw_scores` — pre-gate rubric output (so reductions are visible).
- `applied_gates` — list of `{id, dimension, cap, before, after, active, rationale}`. Gate is `active=true` only when it actually lowered the score; `active=false` means the predicate fired but the rubric was already at/below the cap.

A companion script renders these into a human report — see §16 [`16-generate-gate-report.md`](./16-generate-gate-report.md).

### AC-31-09 — Hard gates apply in both modes
- **Given** any module whose `links_broken > 0`,
- **When** the audit runs in deterministic OR AI mode,
- **Then** `scores.consistency` MUST be ≤ 70 AND `applied_gates` MUST contain an entry with `id="G-LINK-01"` and `active=true` (when the raw score exceeded 70).

### AC-31-10 — Raw scores are preserved for audit trail
- **Given** any audited module,
- **When** the result envelope is read,
- **Then** it MUST contain `raw_scores` (pre-gate) and `scores` (post-gate), and `weighted(scores) <= weighted(raw_scores)` for every module.

### AC-31-11 — TODO/waffle scanners ignore code samples (v2.4)
- **Given** a module whose only `TODO`/`FIXME` tokens appear inside fenced code blocks (```` ``` ````) or inline `code` spans,
- **When** the deterministic metrics are computed,
- **Then** `metrics.todo_density` MUST equal `0` AND the `G-TODO-01` gate MUST NOT fire. The same prose-only rule applies to `WAFFLE_RX` so `waffle_per_kchar` reflects spec narrative, not code samples.

### AC-31-12 — Meta-token sequences are stripped (v2.5)
- **Given** a module whose prose contains the canonical reference form `TODO/TBD/FIXME` (or any 2+ slash-joined work-tracking tokens) — typical of changelog rows, AC text, or fix-checklist category labels,
- **When** the deterministic metrics are computed,
- **Then** those meta-references MUST NOT contribute to `metrics.todo_density`. Standalone `TODO:` work markers in prose still count.

### AC-31-13 — `kind: meta-toolchain` exempts G-TODO-01 (v2.5)
- **Given** a module whose frontmatter declares `kind: meta-toolchain` (auditor-self-reference modules — currently `27-spec-toolchain`),
- **When** the audit runs,
- **Then** the `G-TODO-01` gate MUST be bypassed entirely (not even recorded as passive in `applied_gates`). Other gates apply normally.

### AC-31-14 — Cross-spec link extraction is prose-only (v2.6)
- **Given** a module whose `body_text` contains markdown links inside fenced markdown/text template blocks (e.g. `01-spec-authoring-guide`'s path-syntax examples — see that module for verbatim form),
- **When** the deterministic metrics are computed,
- **Then** those example links MUST NOT contribute to `metrics.links_total` or `metrics.links_broken`. Implementation: `LINK_RX.findall` runs against `strip_code(body_text)` (the same code-stripped prose used by the TODO/waffle scanners), NOT against the raw body. Standalone markdown links in prose still count and are still validated against the filesystem.

### AC-31-15 — `tracker` and `index` kinds skip the contract gates (v2.7)
- **Given** a module whose frontmatter declares `kind: tracker` (issue ledgers) or `kind: index` (placement-rule routers),
- **When** the audit runs,
- **Then** `G-CON-01` (no inlined contract) MUST be bypassed entirely for both kinds, AND `G-CON-02` (overview <500 chars) MUST also be bypassed for both kinds. Rationale: the rubric (`deterministic_score`) already exempts these `kind`s with baseline `implementability=75/70`; the gates must mirror that exemption to avoid double-penalising. `meta-toolchain` is also exempted from `G-CON-01` (auditor-self-reference modules).

### AC-31-16 — `meta-toolchain` rubric branch + normative-contract bonus (v2.8)
- **Given** a module whose frontmatter declares `kind: meta-toolchain` AND whose `00-overview.md` contains either (a) a ```text fenced block ≥10 non-blank lines containing ≥2 of the markers `CONTRACT:`, `INV-`, `FAIL-`, `DEL-`, `INVARIANT`, `BIJECTION`, OR (b) ≥30 child spec files (`md_files >= 30`) acting as the bijection inventory,
- **When** the deterministic metrics are computed,
- **Then** `metrics.has_normative_contract` MUST be `true` AND `scores.implementability` MUST start from baseline 75 (vs the default 30 for normal contract modules), with `+10` if `has_normative_contract` and `+5` if `md_files >= 30`. The `27-spec-toolchain` module — whose "contract" is the script-spec inventory plus the inlined bijection block — MUST score `implementability >= 85`.

### AC-31-17 — Root index inherits top-level folders as children (v2.9)
- **Given** the root spec `spec/00-overview.md` (`MOD_REL == "."`) with `kind: index`,
- **When** the deterministic metrics are computed,
- **Then** `metrics.child_modules` MUST equal the count of top-level `spec/<NN>-*` folders (`CHILDREN["."]`), AND `scores.implementability` MUST be ≥ 80 (baseline 70 + 10 for `child_modules > 0`). Rationale: pre-v2.9 the parent-derivation rule only fired for paths containing `/`, leaving the root index permanently at `child_modules=0` and impl=70.

### AC-31-18 — Evidenced-meta-toolchain bonuses (v2.10)
- **Given** a module whose frontmatter declares `kind: meta-toolchain`,
- **When** the audit runs,
- **Then** `scores.implementability` MUST receive `+5` for `has_mermaid` AND `+5` for `has_ci_workflow`, capped at 100. Rationale: a toolchain spec that documents its own lifecycle (Mermaid diagram) and CI integration (≥5 yaml workflow blocks) is materially more implementable. Same shape as the v2.9 evidenced-tracker / evidenced-index bonuses.

### AC-31-19 — Contract-bearing index bonus (v2.11)
- **Given** a module whose frontmatter declares `kind: index`,
- **When** the audit runs AND ≥1 of `has_sql_ddl` / `has_ts_enums` / `has_json_schema` / `has_yaml_openapi` / `has_typed_lang_contract` is true,
- **Then** each true contract type MUST add `+5` to `scores.implementability`, AND the implementability cap MUST raise from 90 to 100. When zero contract bonuses fire, the cap MUST remain 90. Rationale: an index that ALSO inlines a typed contract functions as both router AND contract authority.

### AC-31-20 — `--min-weighted` and `--min-impl` CLI threshold gates (v2.12)
- **Given** invocation `python3 linter-scripts/audit-spec-vs-code-v2.py --min-weighted=N1 --min-impl=N2`,
- **When** the audit completes,
- **Then** the script MUST exit `1` if mean weighted < `N1` OR mean impl < `N2`; MUST exit `0` and emit `✓ PASS: thresholds met` to stderr when both thresholds are met; AND MUST preserve pre-v2.12 exit behaviour (no threshold check) when neither flag is supplied. Both flags MUST work in deterministic AND AI mode.

### AC-31-21 — Contract-bearing tracker bonus (v2.13)
- **Given** a module whose frontmatter declares `kind: tracker`,
- **When** the audit runs AND ≥1 of `has_sql_ddl` / `has_ts_enums` / `has_json_schema` / `has_yaml_openapi` / `has_typed_lang_contract` is true,
- **Then** each true contract type MUST add `+5` to `scores.implementability`, AND the implementability cap MUST raise from 85 to 95. When zero contract bonuses fire, the cap MUST remain 85. Rationale: a tracker that ALSO inlines a typed contract supplies an authoritative schema for the issues it tracks (e.g. `tracker_issue` SQL DDL).

### AC-31-22 — Tightened TODO regex + `todo_audit_exempt` opt-out (v2.14)
- **Given** a module whose prose contains the bare token `TODO` (or `TBD`/`FIXME`/`XXX`/`HACK`) with **no** trailing `:`, `(name):`, or ` -` (e.g. "marked TODO", "TODO comment", "TODO/FIXME density"),
- **When** the deterministic metrics are computed,
- **Then** `metrics.todo_density` MUST treat such narrative mentions as zero matches. The regex MUST require the canonical work-tracker shape (`TODO:` / `TODO(name):` / `TODO -`).
- **And given** a module whose `00-overview.md` front-matter declares `todo_audit_exempt: true`,
- **Then** `metrics.todo_count` MUST be forced to `0` regardless of how many real `TODO:` markers appear in prose, AND completeness scoring MUST NOT penalise the module for them. Rationale: auditor-self-reference modules legitimately quote TODO markers when documenting how the TODO detector works.

## Rubric changelog (v2.9 → v2.14)

| Version | Phase | Change | Score effect |
|--------:|------:|--------|--------------|
| v2.9 | 46 | Root index spec (`MOD_REL == "."`) inherits top-level folders as `CHILDREN["."]`. Evidenced-tracker / evidenced-index bonuses (`+5` each for `has_mermaid` / `has_ci_workflow`). | Root spec impl 70 → 80; weighted out of D-tier. |
| v2.10 | 79 | Evidenced-meta-toolchain bonuses (`+5` each for `has_mermaid` / `has_ci_workflow`). | `27-spec-toolchain` impl 85 → 95. |
| v2.11 | 80 | Contract-bearing index bonus: `+5` per typed contract (SQL/TS/JSON/OpenAPI/typed-lang); cap 90 → 100 when ≥1 fires. | Index modules with inline contracts impl 90 → 100. |
| v2.12 | 81 | New CLI flags `--min-weighted=N` and `--min-impl=N` enforce mean-score floors in CI. | Workflow gate: locks current quality bar without external script. |
| v2.13 | 82 | Contract-bearing tracker bonus: `+5` per typed contract; cap 85 → 95 when ≥1 fires. | 3 trackers (`05-split-db.../03-issues`, `06-seedable.../03-issues`, `25-app-issues/02-...`) impl 85 → 95. |
| v2.14 | 83 | TODO regex tightened to require `:` / `(name):` / ` -` suffix; new `todo_audit_exempt: true` front-matter opt-out for auditor-self-reference modules. | Prevents false-positive TODO penalties on gap-analysis / changelog content. |

## Cross-references

- §13 [`13-generate-gwt-acceptance.md`](./13-generate-gwt-acceptance.md) — consumes `raw-results.json`.
- §16 [`16-generate-gate-report.md`](./16-generate-gate-report.md) — explains which gate caps each module.
- §30 [`30-audit-spec-vs-code.md`](./30-audit-spec-vs-code.md) — predecessor.
- §70 [`70-spec-health-yml.md`](./70-spec-health-yml.md) — CI workflow that invokes this auditor with `--min-weighted` / `--min-impl` floors.
