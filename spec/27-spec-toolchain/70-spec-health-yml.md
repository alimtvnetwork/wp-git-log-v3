# 70 — spec-health.yml

**Version:** 1.0.0  
**Updated:** 2026-04-25  
**Source:** [`.github/workflows/spec-health.yml`](../../.github/workflows/spec-health.yml)  
**Category:** CI workflow

---

## Purpose

GitHub Actions workflow wiring the spec-health gate. Triggers on every push to `main` and every pull request that touches `spec/`, the gate script (§05), or the index generator (§10). Fails the build when the tree-health score drops below threshold.

## Trigger surface

- **Push to `main`**, paths:
  - `spec/**`
  - `linter-scripts/check-tree-health.cjs`
  - `linter-scripts/generate-spec-index.cjs`
  - `.github/workflows/spec-health.yml`
- **Pull request**, paths:
  - `spec/**`
  - `linter-scripts/check-tree-health.cjs`
  - `linter-scripts/generate-spec-index.cjs`

## Job

Single job `health-gate` running:

1. Checkout.
2. Set up Node.
3. Run `node linter-scripts/check-tree-health.cjs --min=80 --report`.

## Acceptance criteria

### AC-70-01 — Workflow file exists at canonical path
- **Given** the repository,
- **When** the path `.github/workflows/spec-health.yml` is checked,
- **Then** it MUST exist (asserted by §31 audit code-mapping).

### AC-70-02 — Trigger paths cover this toolchain module
- **Given** the workflow's `on.push.paths` and `on.pull_request.paths`,
- **When** they are inspected,
- **Then** they SHOULD include `spec/27-spec-toolchain/**` and `linter-scripts/**` so changes here re-run the gate. (Current file matches `spec/**` which already covers `spec/27-spec-toolchain/**`; expanding `linter-scripts/**` is a tracked enhancement — see §99.)

### AC-70-03 — Threshold is ≥ 80
- **Given** the `health-gate` job step,
- **When** the `check-tree-health.cjs` invocation is read,
- **Then** `--min=` MUST be ≥ `80`.

### AC-70-04 — Job name is stable
- **Given** the workflow,
- **When** the `name:` of the only job is read,
- **Then** it MUST be `Spec tree health gate` (used by branch-protection required-checks).

## Cross-references

- §05 [`05-check-tree-health.md`](./05-check-tree-health.md) — the gate this workflow invokes.
- §10 [`10-generate-spec-index.md`](./10-generate-spec-index.md) — auxiliary trigger path.
