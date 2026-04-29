# 33 — check-ai-confidence.py

**Version:** 1.0.0  
**Updated:** 2026-04-29  
**Source:** [`linter-scripts/check-ai-confidence.py`](../../linter-scripts/check-ai-confidence.py)  
**Self-test:** [`linter-scripts/test/test-check-ai-confidence.sh`](../../linter-scripts/test/test-check-ai-confidence.sh)  
**Category:** Validator (read-only)

---

## Slot-range note

Slot **33** sits in the 30-39 auditor band, but `check-ai-confidence.py` is a deterministic read-only validator (not AI-driven). Validator slots 01-09, 17-19, 24-29 are full as of Phase P48-1-fu1; renaming would break P107/H1/P47-fu0 retros and the Phase 31 lockstep. Placement follows the precedent codified for slots 18/19/32 (validators in non-validator bands). Future contributors MUST NOT "correct" the band.

---

## Purpose

Mechanizes the **`AI Confidence` four-gate rubric (P1 → P4)** defined in [`spec/17-consolidated-guidelines/01-spec-authoring.md`](../17-consolidated-guidelines/01-spec-authoring.md) § *AI Confidence Rubric (normative)* and bound by [AC-09 in §17 §97](../17-consolidated-guidelines/97-acceptance-criteria.md).

Closes **P48-1-fu1**, the open follow-up logged in P48-1's §99 row: prior to this linter, the `AI Confidence` field was author-judgement; after this linter, it is machine-derived from on-disk signals so the entire class of author-drift is eliminated. The first run on the live tree (Phase P48-1-fu1) found **13/15 modules drifting** — concrete, actionable findings (5 P1 inventory gaps, 5 P3 `**Verifies:**` coverage gaps, 3 deeper P3/P4 issues), each with a deterministic reason chain.

---

## Inputs

- `spec/<module>/00-overview.md` — declared `**AI Confidence:**` value.
- `spec/<module>/97-acceptance-criteria.md` — GWT presence, `**Verifies:**` coverage.
- `spec/<module>/99-consistency-report.md` — H1 stamp freshness.
- `spec/<module>/*.md` — truncation tail scan.
- `.github/workflows/spec-health.yml` — CI-gate references.

## Usage

```bash
python3 linter-scripts/check-ai-confidence.py
python3 linter-scripts/check-ai-confidence.py --strict
python3 linter-scripts/check-ai-confidence.py --report-only
python3 linter-scripts/check-ai-confidence.py --json
```

## CLI flags

| Flag | Default | Purpose |
|------|---------|---------|
| `--strict` | off | Exit 1 on ANY drift (tree-wide CI gate once adoption matures) |
| `--report-only` | off | Never fail; overrides `--strict` and per-file stamps; for dashboards |
| `--json` | off | Machine-readable JSON output (includes `stamped`, `stamped_failed`, `rows[]`) |

## Detection rules (a module's row is `match` iff)

The script computes a **derived** tier by walking gates P1 → P4 in order; the highest gate that passes wins. The declared banner value MUST equal the derived tier.

| Gate | Passes when |
|------|-------------|
| **P1** | §00 lists every sibling `.md` (excluding meta-slots `97`/`98`/`99`) AND `**Updated:**` year matches current calendar year. |
| **P2** | P1 holds AND §97 contains ≥1 `**Given**`/`**When**`/`**Then**` marker AND no `*.md` in the module ends with a truncation marker (`...`, `…`, bare `TODO`/`TBD`/`FIXME`). |
| **P3** | P2 holds AND every `### AC-…` heading in §97 has a `**Verifies:**` clause. |
| **P4** | P3 holds AND module dir name appears in `.github/workflows/spec-health.yml` AND §99 carries a `<!-- verified-phase: NNN -->` stamp ≤ 30 phases stale relative to the highest stamp anywhere in the tree. |

A module with `unset`/blank `AI Confidence` is **skipped** (matches the rubric's "omit rather than guess" rule — no drift can be computed).

## Per-file opt-in stamp

Authors who have verified their banner matches the rubric-derived tier add inside the first 40 lines of `00-overview.md`:

```markdown
<!-- ai-confidence-verified-phase: NNN -->
```

Once stamped, ANY future drift on that file fails the gate even in default (advisory-tree) mode. This per-file strict promotion mirrors the H1 / P20 pattern, letting modules opt in one at a time without waiting for the whole tree to converge. The stamp name is deliberately distinct from `verified-phase` (H1 §99 freshness) and `h10-verified-phase` (P20 version parity) so the three opt-in gates remain independently trackable per file.

## Outputs

```
AI-Confidence rubric parity: scanned=N; eligible=N; matches=N; mismatches=N; stamped=N; stamped_failed=N; h1_horizon=N
  (DRIFT) spec/<module>: declared='X' derived='Y' [stamped]
      reason: P3: §97 has 8 ACs but only 0 `**Verifies:**` clauses
```

## Exit codes

| Code | Meaning |
|------|---------|
| 0 | All matches OR only unstamped drifts (default advisory mode) |
| 1 | `--strict` flag set AND ≥1 mismatch, OR ≥1 stamped file drifting (default mode) |

## Acceptance criteria

### AC-33-01 — Drift detected on stamped banner
- **Given** a module whose §00 carries an `<!-- ai-confidence-verified-phase: NNN -->` stamp AND a declared tier different from the derived tier,
- **When** the validator runs in default mode,
- **Then** exit code MUST be 1 and the row MUST be marked `[stamped]` in stdout.
- **Verifies:** [`linter-scripts/check-ai-confidence.py`](../../linter-scripts/check-ai-confidence.py) per-file strict promotion path.

### AC-33-02 — Drift on unstamped banner is advisory
- **Given** a module whose §00 has NO `ai-confidence-verified-phase` stamp AND a declared tier different from the derived tier,
- **When** the validator runs in default mode,
- **Then** exit code MUST be 0 (advisory) and the drift MUST still appear in stdout for visibility.
- **Verifies:** [`linter-scripts/check-ai-confidence.py`](../../linter-scripts/check-ai-confidence.py) advisory-by-default contract (mirrors P20 H10 pattern).

### AC-33-03 — `--strict` flips advisory to blocking
- **Given** any drift exists in the tree,
- **When** the validator runs with `--strict`,
- **Then** exit code MUST be 1 regardless of stamp status.
- **Verifies:** [`linter-scripts/check-ai-confidence.py`](../../linter-scripts/check-ai-confidence.py) `--strict` flag.

### AC-33-04 — Lowest-passing gate wins
- **Given** a module passing P1 + P2 but failing P3,
- **When** the validator runs,
- **Then** the derived tier MUST be `Medium` (not `High`), and a module declaring `Production-Ready` MUST report a drift.
- **Verifies:** [`linter-scripts/check-ai-confidence.py`](../../linter-scripts/check-ai-confidence.py) `derive_tier()` walk order.

### AC-33-05 — `unset` declared value is skipped
- **Given** a module whose §00 does not carry an `**AI Confidence:**` banner OR whose value is not in `{Production-Ready, High, Medium, Low}`,
- **When** the validator runs,
- **Then** the module MUST NOT appear in `rows[]` and MUST NOT contribute to `eligible`/`matches`/`mismatches`.
- **Verifies:** [`linter-scripts/check-ai-confidence.py`](../../linter-scripts/check-ai-confidence.py) `parse_banner()` filter.

### AC-33-06 — Self-test parity
- **Given** the self-test [`test/test-check-ai-confidence.sh`](../../linter-scripts/test/test-check-ai-confidence.sh),
- **When** it runs,
- **Then** all assertions MUST pass.
- **Verifies:** [`linter-scripts/test/test-check-ai-confidence.sh`](../../linter-scripts/test/test-check-ai-confidence.sh).

## Cross-references

- §17 [`17-consolidated-guidelines/01-spec-authoring.md`](../17-consolidated-guidelines/01-spec-authoring.md) — *AI Confidence Rubric (normative)* (the contract this linter mechanizes).
- §17 [`17-consolidated-guidelines/97-acceptance-criteria.md`](../17-consolidated-guidelines/97-acceptance-criteria.md) — AC-09 binding.
- §17 [`17-consolidated-guidelines/98-changelog.md`](../17-consolidated-guidelines/98-changelog.md) — release rows 3.4.0 (P48-1) + 3.5.0 (P48-1-fu1).
- §27 [`29-check-version-parity.md`](./29-check-version-parity.md) — P20/P31 pattern this gate mirrors.
- §27 [`26-check-99-summary-freshness.md`](./26-check-99-summary-freshness.md) — H1 stamp pattern this gate reuses for P4 freshness.
