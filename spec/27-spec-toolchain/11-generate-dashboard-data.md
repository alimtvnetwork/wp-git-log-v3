# 11 — generate-dashboard-data.cjs

**Version:** 1.1.0  
**Updated:** 2026-04-26  
**Source:** [`linter-scripts/generate-dashboard-data.cjs`](../../linter-scripts/generate-dashboard-data.cjs)  
**Category:** Generator

---

## Purpose

Scan the `spec/` tree and emit `spec/dashboard-data.json` consumed by [`spec/health-dashboard.md`](../health-dashboard.md). Performs:

1. Validate all markdown cross-references (broken-link detection).
2. Check required files (`00-overview.md`, `99-consistency-report.md`).
3. Count files per subfolder.
4. **(v1.1.0, Phase 34)** Compute per-module rubric-v2.0.0 quality credits — mirrors [`05-check-tree-health.md`](./05-check-tree-health.md) so the dashboard score equals the CI gate score.
5. Output a JSON report.

## Rubric v2.0.0 (Phase 34 propagation)

The generator now mirrors the rubric used by `check-tree-health.cjs`:

| Dimension | Weight | Credit |
|-----------|-------:|--------|
| Required | 60 % | 1 per `00-overview.md`, 1 per `99-consistency-report.md` |
| Recommended | 25 % | 1 per `97-acceptance-criteria.md`, 1 per `98-changelog.md` |
| Quality | 15 % | 1 per §99 ≥30 non-blank lines + 1 per Validation History heading + 1 per File/Module Inventory heading |

`Health.Score` and `Health.Grade` are now driven by the rubric. The legacy
deduction-based score is retained as `Health.LegacyScore` for back-compat
with any tooling that depended on the previous behaviour.

## Usage

```bash
node linter-scripts/generate-dashboard-data.cjs
node linter-scripts/generate-dashboard-data.cjs --json
node linter-scripts/generate-dashboard-data.cjs --quiet
```

## CLI flags

| Flag | Purpose |
|------|---------|
| `--json` | Emit only the JSON report on stdout, suppress human summary |
| `--quiet` | Suppress non-error stderr noise |

## Outputs

- `spec/dashboard-data.json` (overwritten)
- Optional human summary on stderr

## Exit codes

| Code | Meaning |
|------|---------|
| 0 | Report generated (broken links may still exist; this script reports, doesn't gate) |
| 1 | I/O error |

## Acceptance criteria

### AC-11-01 — JSON schema is stable
- **Given** the generated `spec/dashboard-data.json`,
- **When** parsed,
- **Then** it MUST contain top-level keys `modules`, `brokenLinks`, `summary` with the same shape across runs.

### AC-11-02 — `--quiet` produces no stderr
- **Given** a successful run with `--quiet`,
- **When** stderr is captured,
- **Then** it MUST be empty.

### AC-11-03 — Output file ends with newline
- **Given** `spec/dashboard-data.json` after a run,
- **When** read,
- **Then** the final byte MUST be `\n`.

## Cross-references

- [`spec/health-dashboard.md`](../health-dashboard.md) — consumer.
