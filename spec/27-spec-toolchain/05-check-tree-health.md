# 05 — check-tree-health.cjs

**Version:** 1.0.0  
**Updated:** 2026-04-25  
**Source:** [`linter-scripts/check-tree-health.cjs`](../../linter-scripts/check-tree-health.cjs)  
**Category:** Validator (gate)

---

## Purpose

Compute a `spec/` tree health score from disk truth and fail (exit `1`) when below threshold. This is the **CI gate** that locks progress and prevents regressions in basic structural compliance.

## Scoring

For each module folder under `spec/`:

| Credit | Condition |
|-------:|-----------|
| +1 | `00-overview.md` present (required) |
| +1 | `99-consistency-report.md` present (required) |
| +1 | `97-acceptance-criteria.md` present (recommended) |
| +1 | `98-changelog.md` present (soft credit) |

`score = (sum_credits / max_credits) * 100`

## Usage

```bash
node linter-scripts/check-tree-health.cjs                # default --min=75
node linter-scripts/check-tree-health.cjs --min=80       # custom threshold
node linter-scripts/check-tree-health.cjs --report       # per-module breakdown
```

## CLI flags

| Flag | Default | Purpose |
|------|---------|---------|
| `--min=<n>` | `75` | Minimum acceptable score (0–100) |
| `--report` | off | Print every module's credits |

## Outputs

Single line summary: `Tree health: NN/100 (required=X/Y, recommended=A/B, optional=C/D)` plus per-module table when `--report`.

## Exit codes

| Code | Meaning |
|------|---------|
| 0 | Score ≥ `--min` AND no module is missing a required file |
| 1 | Score below threshold OR any required file missing |

## Acceptance criteria

### AC-05-01 — Default threshold is 75
- **Given** the script invoked with no arguments,
- **When** the spec tree scores 74,
- **Then** it MUST exit `1`.

### AC-05-02 — Missing required file fails regardless of score
- **Given** a module without `00-overview.md`,
- **When** the script runs,
- **Then** it MUST exit `1` even if total score is ≥ `--min`.

### AC-05-03 — `--report` lists every module
- **Given** `--report`,
- **When** the script runs,
- **Then** every module folder MUST appear with its credit breakdown.

## Cross-references

- §20–§22 fillers raise the score by scaffolding missing files.
- §70 [`70-spec-health-yml.md`](./70-spec-health-yml.md) wires this into CI.
