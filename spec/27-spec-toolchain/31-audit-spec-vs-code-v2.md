# 31 — audit-spec-vs-code-v2.py

**Version:** 1.3.0  
**Updated:** 2026-04-27  
**Source:** [`linter-scripts/audit-spec-vs-code-v2.py`](../../linter-scripts/audit-spec-vs-code-v2.py)  
**Category:** Auditor (AI-driven by default; **deterministic mode** + **hard scoring gates**)  
**Predecessor:** §30 [`30-audit-spec-vs-code.md`](./30-audit-spec-vs-code.md)

---

## Purpose

AI-implementability audit. Asks: *"Could a mediocre AI ship a working implementation from this spec alone with zero human clarification?"*

## Methodology

1. Broader code index: `linter-scripts/` + `.github/` + `src/` presence.
2. Deterministic pre-checks computed BEFORE AI scoring (so AI can be calibrated):
   - waffle ratio (should/may/might/optionally per 1k chars)
   - contract presence (DDL, JSON, TS enums, YAML/OpenAPI, Mermaid)
   - cross-spec link health (broken count)
   - AC count + Given/When/Then block count
   - TODO/TBD/FIXME density
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
```

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
| `G-CON-01`  | implementability | 50 | No `sql/json/ts/yaml` contract block in body |
| `G-CON-02`  | implementability | 30 | `overview_chars < 500` |
| `G-WAF-01`  | clarity | 70 | `waffle_per_kchar > 3` |
| `G-WAF-02`  | clarity | 50 | `waffle_per_kchar > 6` |
| `G-CR-01`   | maintainability | 60 | Missing `99-consistency-report.md` |
| `G-TODO-01` | completeness | 70 | `todo_density >= 3` |

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

## Cross-references

- §13 [`13-generate-gwt-acceptance.md`](./13-generate-gwt-acceptance.md) — consumes `raw-results.json`.
- §16 [`16-generate-gate-report.md`](./16-generate-gate-report.md) — explains which gate caps each module.
- §30 [`30-audit-spec-vs-code.md`](./30-audit-spec-vs-code.md) — predecessor.
