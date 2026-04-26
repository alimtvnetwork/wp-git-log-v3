# 70 — spec-health.yml

**Version:** 1.1.0  
**Updated:** 2026-04-26  
**Source:** [`.github/workflows/spec-health.yml`](../../.github/workflows/spec-health.yml)  
**Category:** CI workflow

---

## Purpose

GitHub Actions workflow wiring three spec-quality gates in series:

1. **Spec tree health gate** (§05) — score threshold.
2. **Spec cross-link gate** (§01) — zero broken internal markdown links.
3. **Trace-map regression gate** (§15) — AC coverage, drift, orphan code.

Triggers on every push to `main` and every pull request that touches `spec/`, any linter script, or this workflow file. Fails the build when any gate reports a regression.

## Trigger surface

- **Push to `main`**, paths:
  - `spec/**`
  - `linter-scripts/check-tree-health.cjs`
  - `linter-scripts/generate-spec-index.cjs`
  - `linter-scripts/check-spec-cross-links.py`
  - `linter-scripts/spec-cross-links.allowlist`
  - `.github/workflows/spec-health.yml`
- **Pull request**, paths:
  - `spec/**`
  - `linter-scripts/check-tree-health.cjs`
  - `linter-scripts/generate-spec-index.cjs`
  - `linter-scripts/check-spec-cross-links.py`
  - `linter-scripts/spec-cross-links.allowlist`

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
