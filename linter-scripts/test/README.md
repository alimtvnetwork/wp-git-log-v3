# `linter-scripts/test/` — Self-Tests for the Spec-Toolchain CLI

**Last updated:** 2026-04-27 (Phase 103)
**Source of truth for:** the contract guarantees of every script under
`linter-scripts/` that has user-visible CLI semantics (exit codes,
stdout/stderr structure, idempotency, determinism).

---

## Why this directory exists

The pre-existing CI gates (tree-health, lockstep, audit thresholds,
trace-map regression, mermaid syntax) all check the **state of the spec
tree** — they answer *"does the repo currently satisfy the rules?"*.

They do **not** answer *"do the linters that enforce those rules still
behave correctly?"*. A subtle bug in `audit-spec-vs-code-v2.py` — flipping
a comparison operator, swapping an exit code, breaking the `--explain`
output, or losing determinism — could go unnoticed for months because:

- The production audit gate runs **once per CI build**, so it cannot detect
  non-determinism.
- All 87 modules currently sit comfortably above the score floors
  (98.0 / 99.8 vs 97 / 99), so a comparison-operator inversion wouldn't
  flip the gate's verdict.
- The `--explain` flag is only invoked manually by contributors, never by
  CI, so a silent regression in its stdout structure would be invisible
  until the next time someone debugged a score outlier.

The self-tests in this directory close those blind spots. Each one is a
**contract test**: it pins one specific behaviour of one specific script
with a small number of high-signal assertions, and it runs in CI on every
PR so any regression fails the build at the assertion level (with
`✅`/`❌` per-check output) rather than as a downstream symptom.

---

## Test inventory

| # | Test script | Phase | Asserts about | Assertions | Runtime | Locked AC |
|---|---|---|---|:-:|:-:|---|
| 1 | [`test-audit-cli-thresholds.sh`](./test-audit-cli-thresholds.sh) | 91 | `audit-spec-vs-code-v2.py` `--min-weighted=N` / `--min-impl=N` exit-code contract | 6 | ~3 s | [AC-31-22](../../spec/27-spec-toolchain/31-audit-spec-vs-code-v2.md) |
| 2 | [`test-audit-explain-contract.sh`](./test-audit-explain-contract.sh) | 94 | `audit-spec-vs-code-v2.py --explain=<substring>` stdout structure, exit codes, no-side-effects | 14 | ~6 s | [AC-31-23](../../spec/27-spec-toolchain/31-audit-spec-vs-code-v2.md) + [AC-31-25](../../spec/27-spec-toolchain/31-audit-spec-vs-code-v2.md) |
| 3 | [`test-audit-deterministic-stability.sh`](./test-audit-deterministic-stability.sh) | 95 | `audit-spec-vs-code-v2.py` produces byte-identical `raw-results.json` across two runs under `AUDIT_DETERMINISTIC=1` | 7 | ~12 s | [AC-31-26](../../spec/27-spec-toolchain/31-audit-spec-vs-code-v2.md) |
| 4 | [`test-readme-inventory.sh`](./test-readme-inventory.sh) | 102 | This README's inventory table is in sync with the actual `test-*.sh` files on disk; required structural sections present; every script linked + executable | 14+ | ~1 s | [AC-31-27](../../spec/27-spec-toolchain/31-audit-spec-vs-code-v2.md) |
| 5 | [`test-qa-baseline-footer.sh`](./test-qa-baseline-footer.sh) | 103 | The audit script's "QA tooling baseline" footer (in `00-index.md` + `EXECUTIVE-SUMMARY.md`) is consistent with `RUBRIC_VERSION` constant + `.github/workflows/spec-health.yml` step list — declared gate count = footer rows = workflow gate steps | 11+ | ~3 s | [AC-31-28](../../spec/27-spec-toolchain/31-audit-spec-vs-code-v2.md) |

**Totals:** 5 scripts · 52+ assertions · ~25 s of CI time.

All five scripts are wired into [`.github/workflows/spec-health.yml`](../../.github/workflows/spec-health.yml)
as discrete steps (named `Audit CLI threshold contract self-test (Phase 91)`,
`Audit --explain contract self-test (Phase 94)`, `Audit determinism / JSON-stability self-test (Phase 95)`,
`Self-test README inventory parity (Phase 102)`, `Self-test QA baseline footer (Phase 103)`).

---

## Coverage triad: what each test catches

The five tests together form a **complete blind-spot coverage matrix** for
the audit subsystem (gates 1–3) plus the meta-suite itself (gates 4–5):

| Blind spot | Why production gate misses it | Self-test catching it |
|---|---|---|
| Comparison-operator inversion (`<` vs `≤`, `≥` vs `>`) | All scores currently above floor; bug invisible | **Phase 91** (6 cases at the boundary) |
| `--explain` diagnostic tool silently broken | Production gate never invokes `--explain` | **Phase 94** (14 assertions across single-match / no-match / multi-match) |
| Non-determinism introduced into the rubric | Production gate runs only once per build | **Phase 95** (sha256 byte-identity across two runs) |
| Self-test added/removed without updating this README | Reviewer-attention only; AC-31-27 was unenforced | **Phase 102** (filesystem ↔ inventory parity, structural sections, executable bit) |
| QA-baseline footer drifting from `RUBRIC_VERSION` / workflow / declared count | Production audit gate still passes while docs lie; AC-31-28 was unenforced | **Phase 103** (4-way enumeration consistency: script constant ↔ 00-index ↔ EXECUTIVE-SUMMARY ↔ workflow steps) |

If you add a sixth contract guarantee to the audit script (or any other
linter), add a sixth self-test here following the same template — see
**"Adding a new self-test"** below. The Phase 102 gate will fail on your
PR if you forget to add the row; the Phase 103 gate will fail if you wire
the new step into the workflow without bumping the audit footer's
gate-count enumeration in lockstep.

---

## Adjacent gates (not in this directory but logically siblings)

These are full-tree linter gates rather than per-script contract tests, so
they live one level up in `linter-scripts/`. They are listed here for
discoverability:

| Script | Phase | Gate type | What it parses |
|---|---|---|---|
| [`../check-mermaid-syntax.mjs`](../check-mermaid-syntax.mjs) | 97 | Full-tree | Every `spec/**/*.mmd` file with the `mermaid` library under a `jsdom` shim — catches broken diagram syntax pre-merge |
| [`../check-tree-health.cjs`](../check-tree-health.cjs) | (multiple) | Full-tree | Every `spec/<module>/` for the four-required-files rule + naming + structure |
| [`../check-lockstep.cjs`](../check-lockstep.cjs) | (multiple) | Full-tree | §97/§98/§99 version-bump synchronicity per module |
| [`../check-spec-cross-links.py`](../check-spec-cross-links.py) | (multiple) | Full-tree | Every internal `[link](./path)` resolves |
| [`../check-trace-map-regression.py`](../check-trace-map-regression.py) | 30 | Full-tree | AC coverage doesn't drop, drift doesn't grow, no orphan code |

---

## Local execution

Run any single test directly:

```bash
bash linter-scripts/test/test-audit-cli-thresholds.sh
bash linter-scripts/test/test-audit-explain-contract.sh
bash linter-scripts/test/test-audit-deterministic-stability.sh
bash linter-scripts/test/test-readme-inventory.sh
bash linter-scripts/test/test-qa-baseline-footer.sh
```

Run all five sequentially:

```bash
for t in linter-scripts/test/test-*.sh; do
  echo "═══ $(basename "$t") ═══"
  bash "$t" || { echo "❌ $t FAILED"; exit 1; }
done
echo "✅ All self-tests passed."
```

Each script:
- Sets `AUDIT_DETERMINISTIC=1` internally (no external env required).
- Uses `set -euo pipefail` — exits non-zero on first unhandled failure.
- Prints per-assertion `✅`/`❌` lines with the asserted condition.
- Ends with `Results: <pass> passed, <fail> failed` summary.
- Exits 0 on full pass, 1 on any failure, 2 on infrastructure failure
  (audit script missing, etc.).

No script writes to `.lovable/memory/` or `spec/` — they're
side-effect-free against the repository (Phase 94 and Phase 95 explicitly
verify this with sha256 snapshots).

---

## Adding a new self-test

When you add a new contract guarantee to a `linter-scripts/` script
(new flag, new exit code, new output format, new determinism guarantee),
add a paired self-test here. Template:

```bash
#!/usr/bin/env bash
# linter-scripts/test/test-<script>-<contract>.sh
#
# Phase NN — <one-line description of the locked contract>.
#
# Locks: <which behaviour, e.g. "v2.17 (Phase 99) --foo=<bar> exit codes">
#
# Without this self-test, <describe the regression that could ship
# unnoticed and why the production gate wouldn't catch it>.
#
# Spec: spec/<module>/<file>.md (AC-<MOD>-NN)
# Memo: .lovable/memory/audit/v2-deterministic/phase-NN-<slug>.md
set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
TARGET="$SCRIPT_DIR/<script-under-test>"
REPO_ROOT="$(cd "$SCRIPT_DIR/.." && pwd)"

[ -f "$TARGET" ] || { echo "❌ target script not found: $TARGET"; exit 2; }

cd "$REPO_ROOT"
PASS=0
FAIL=0

assert() {
  local label="$1"; shift
  if "$@"; then echo "✅ $label"; PASS=$((PASS+1))
  else          echo "❌ $label"; FAIL=$((FAIL+1)); fi
}

# ── Assertions ───────────────────────────────────────────────────
# assert "label" command-that-exits-0-on-success

# ── Summary ──────────────────────────────────────────────────────
echo "======================================="
echo "Results: $PASS passed, $FAIL failed"
[ "$FAIL" -eq 0 ] || exit 1
echo "✅ <contract name> intact."
```

Then:

1. `chmod +x` the new script.
2. Add a new step to `.github/workflows/spec-health.yml` (modelled on
   the existing three, with a 6-line comment block explaining the
   blind spot it covers).
3. Add a row to the **Test inventory** and **Coverage triad** tables
   in this README.
4. Add the corresponding `AC-<MOD>-NN` to the relevant `97-acceptance-criteria.md`,
   bumping that module's `97`/`98`/`99` in lockstep.
5. Write the post-merge phase memo at
   `.lovable/memory/audit/v2-deterministic/phase-NN-<slug>.md`.

---

## See also

- **Spec:** [`spec/27-spec-toolchain/31-audit-spec-vs-code-v2.md`](../../spec/27-spec-toolchain/31-audit-spec-vs-code-v2.md) — the canonical contract for the audit script (all `AC-31-*` IDs).
- **CI workflow:** [`.github/workflows/spec-health.yml`](../../.github/workflows/spec-health.yml).
- **Local runner:** [`linter-scripts/run.sh`](../run.sh) — runs the full-tree gates locally; the self-tests run in CI.
- **Phase memos:**
  [Phase 91](../../.lovable/memory/audit/v2-deterministic/phase-91-cli-self-test.md) ·
  [Phase 94](../../.lovable/memory/audit/v2-deterministic/phase-94-explain-contract-test.md) ·
  [Phase 95](../../.lovable/memory/audit/v2-deterministic/phase-95-determinism-stability.md) ·
  [Phase 97](../../.lovable/memory/audit/v2-deterministic/phase-97-mermaid-syntax-gate.md) ·
  [Phase 98](../../.lovable/memory/audit/v2-deterministic/phase-98-test-readme.md) ·
  [Phase 102](../../.lovable/memory/audit/v2-deterministic/phase-102-readme-inventory-test.md) ·
  [Phase 103](../../.lovable/memory/audit/v2-deterministic/phase-103-qa-baseline-footer-test.md)
