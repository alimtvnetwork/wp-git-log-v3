# `linter-scripts/test/` — Self-Tests for the Spec-Toolchain CLI

**Last updated:** 2026-04-28 (Phase P35 — added `test-check-spec-cross-links.sh` codifying the P34 lesson #1 fuzzy waiver matching as a standing CI gate; total 11 self-tests)
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
| 6 | [`test-overview-inventory-parity.sh`](./test-overview-inventory-parity.sh) | 112 | The §27 inventory triangle: every executable artifact under `linter-scripts/` + `.github/workflows/` is tracked in either `spec/27-spec-toolchain/00-overview.md` (specced) OR the Phase 107 orphan ledger memo (acknowledged); every overview-listed code path exists on disk; structural anchors intact | 6+ | ~1 s | [AC-31-31](../../spec/27-spec-toolchain/31-audit-spec-vs-code-v2.md) |
| 7 | [`test-weights-parity.sh`](./test-weights-parity.sh) | 113 | The 7-dimension `WEIGHTS` triangle: `audit-spec-vs-code-v2.py` `WEIGHTS` dict ↔ `generate-gate-report.py` `WEIGHTS` dict ↔ `spec/27-spec-toolchain/31-audit-spec-vs-code-v2.md` `## Weights` table — pairwise dict-equality + AC-31-02 invariants (impl == 35, total == 100) + dimension count == 7 | 8 | ~1 s | [AC-31-31 row #4](../../spec/27-spec-toolchain/31-audit-spec-vs-code-v2.md) (extends [AC-31-02](../../spec/27-spec-toolchain/31-audit-spec-vs-code-v2.md)) |
| 8 | [`test-check-99-summary-freshness.sh`](./test-check-99-summary-freshness.sh) | H1 | §26 `check-99-summary-freshness.py` exit-code contract: unstamped files don't fail (advisory), stamped+stale files exit 1 in strict mode and 0 with `--report-only`, stamped+fresh files exit 0, missing-phase-token exits 2. Phase H2 widened to inventory rubrics + `_archive/` exclusion (T8/T9/T10). Synthetic sandbox. | 17 | ~1 s | [AC-26-01..08](../../spec/27-spec-toolchain/26-check-99-summary-freshness.md) |
| 9 | [`test-check-99-stamp-bump.sh`](./test-check-99-stamp-bump.sh) | H4 | §27 `check-99-stamp-bump.py` exit-code contract: empty/unstamped/stamp-only diffs skip (exit 0); materially-edited stamped files without bump exit 1; `--report-only` never fails; `_archive/` excluded; bad base-ref/missing phase token exit 2. Uses `--changed-files` test injection to bypass git (sandbox forbids `git add`). | 23 | ~1 s | [AC-27-01..08](../../spec/27-spec-toolchain/27-check-99-stamp-bump.md) |
| 10 | [`test-archive-exclusion-runtime.sh`](./test-archive-exclusion-runtime.sh) | H7 | §28 runtime archive-exclusion gate: every spec-traversing linter MUST exclude `spec/_archive/` at RUNTIME (not just by source-reading). importlib-loads 3 critical linters (`check-99-summary-freshness.find_99_files()`, `audit-spec-vs-code-v2.ALL_MODULES`, `generate-trace-map.collect_ac_ids()`), calls each enumerator, asserts 0 archive-leaked results. Floor: probe count ≥ 3. Codifies the H6 lesson "runtime > source verification". | 10 | ~3 s | [AC-28-01..05](../../spec/27-spec-toolchain/28-check-archive-exclusion-runtime.md) |

**Totals:** 10 scripts · 110+ assertions · ~32 s of CI time.

All ten scripts are reachable from [`.github/workflows/spec-health.yml`](../../.github/workflows/spec-health.yml).
Seven run as discrete self-test steps (`Audit CLI threshold contract self-test (Phase 91)`,
`Audit --explain contract self-test (Phase 94)`, `Audit determinism / JSON-stability self-test (Phase 95)`,
`Self-test README inventory parity (Phase 102)`, `Self-test QA baseline footer (Phase 103)`,
`Self-test §27 inventory parity triangle (Phase 112)`, `Self-test WEIGHTS dimension-table parity (Phase 113)`).
The remaining three are folded into their broader-contract production gates per the H1
workflow-step parity lesson (footer rows = workflow gates = declared count): `test-check-99-summary-freshness.sh`
runs inside `§99 Summary freshness gate (Phase H1 / H8 / H9)`, `test-check-99-stamp-bump.sh` inside
`§99 Stamp-bump gate (Phase H5)`, and `test-archive-exclusion-runtime.sh` inside
`Runtime archive-exclusion gate (Phase H7)`.

---

## Coverage triad: what each test catches

The ten tests together form a **complete blind-spot coverage matrix** for
the audit subsystem (gates 1–3), the meta-suite itself (gates 4–7), and the
§99 lifecycle / archive-exclusion contracts (gates 8–10):

| Blind spot | Why production gate misses it | Self-test catching it |
|---|---|---|
| Comparison-operator inversion (`<` vs `≤`, `≥` vs `>`) | All scores currently above floor; bug invisible | **Phase 91** (6 cases at the boundary) |
| `--explain` diagnostic tool silently broken | Production gate never invokes `--explain` | **Phase 94** (14 assertions across single-match / no-match / multi-match) |
| Non-determinism introduced into the rubric | Production gate runs only once per build | **Phase 95** (sha256 byte-identity across two runs) |
| Self-test added/removed without updating this README | Reviewer-attention only; AC-31-27 was unenforced | **Phase 102** (filesystem ↔ inventory parity, structural sections, executable bit) |
| QA-baseline footer drifting from `RUBRIC_VERSION` / workflow / declared count | Production audit gate still passes while docs lie; AC-31-28 was unenforced | **Phase 103** (4-way enumeration consistency: script constant ↔ 00-index ↔ EXECUTIVE-SUMMARY ↔ workflow steps) |
| New script silently added to `linter-scripts/` or `.github/workflows/` without a §27 spec row OR an entry in the Phase 107 orphan ledger | `check-tree-health.cjs` allow-list inference is permissive (Phase 107 found 8 silent orphans); AC-31-31 / INV-01 / INV-02 were unenforced | **Phase 112** (3-way triangle: §27 overview ↔ filesystem ↔ Phase 107 orphan memo) |
| Dimension `WEIGHTS` drifting between `audit-spec-vs-code-v2.py`, `generate-gate-report.py`, and §31's `## Weights` table | AC-31-02's runtime assertion only catches in-script drift in the audit script alone; gate-report and §31 docs were unenforced and could silently produce divergent scoring | **Phase 113** (3-way dict-equality + AC-31-02 invariants + dimension count == 7) |
| §99 `## Summary` prose stamped `<!-- verified-phase: NNN -->` going stale (claimed phase older than newest §98 row) | Production audit gate scores §99 structurally; stale narrative claims are invisible to it (Phase 136 over-counted, Phase 139 found real count was 1) | **Phase H1** (advisory→strict per opt-in stamp; sandbox-tested 17 assertions covering unstamped/stamped-stale/stamped-fresh/missing-token paths) |
| §99 stamped+materially-edited without a phase-token bump | No production gate enforces "edited-then-stamp-must-bump"; reviewer-attention only | **Phase H4** (`--changed-files` injection bypasses git sandbox; 23 assertions across empty/unstamped/stamp-only/material-edit/`_archive/` exclusion/bad-base-ref paths) |
| Spec-traversing linters reading `_archive/` paths at RUNTIME despite source-level allow-list (the H6 lesson) | Source-reading proves intent, not behavior; only runtime enumeration proves a linter actually skips the directory | **Phase H7** (importlib-loads 3 critical enumerators, asserts 0 archive-leaked results; floor: probe count ≥ 3) |

If you add an eleventh contract guarantee to the audit script (or any other
linter), add an eleventh self-test here following the same template — see
**"Adding a new self-test"** below. The Phase 102 gate will fail on your
PR if you forget to add the row; the Phase 103 gate will fail if you wire
the new step into the workflow without bumping the audit footer's
gate-count enumeration in lockstep; the Phase 112 gate will fail if you
add a script without updating §27 §00-overview or the Phase 107 ledger;
the Phase 113 gate will fail if you change scoring weights in only one
of the three sites that restate them.

---

## Test-discovery policy (Phase F3 — keep `.sh`-only)

The ten scripts above are **shell tests**, and the parity gate
([`test-readme-inventory.sh`](./test-readme-inventory.sh)) discovers them
via `ls test-*.sh`. This is **deliberate**, not an oversight:

- **CI uniformity** — every shell test runs under the same `bash` runtime
  the workflow already provisions; no Python venv coupling, no
  hyphenated-module-name `importlib` dance, no per-test interpreter
  detection.
- **Side-effect surface** — `set -euo pipefail` + `assert` helper give us
  a uniform pass/fail/exit-code contract. A Python `unittest`/`pytest`
  test would need its own contract restatement (Phase 102 was authored
  against the shell shape).
- **Readability for spec authors** — most contributors editing
  `spec/27-spec-toolchain/` are spec authors first, not Python
  developers; a 30-line bash assertion file is more approachable than
  an `importlib.util.spec_from_file_location` incantation.

**Rule (codified Phase F3):** New self-tests SHOULD be `.sh`. The only
sanctioned exception is when the script-under-test fundamentally requires
Python introspection that bash cannot reproduce ergonomically — for
example, exercising a `load_allowlist()` function as a unit (rather than
its CLI surface) requires `importlib`-loading a hyphenated source file
([`test-check-spec-folder-refs.py`](./test-check-spec-folder-refs.py),
Phase 144 — locks AC-62-04). Such Python tests are listed in **Adjacent
`.py` tests** below; they are NOT covered by the README inventory parity
gate (which remains `.sh`-only by design) and instead rely on
[`test-overview-inventory-parity.sh`](./test-overview-inventory-parity.sh)
for filesystem-level acknowledgement.

If you find yourself reaching for `.py` for any other reason (better
assertion library, prettier diff output, etc.), that is **not** a
sanctioned exception — write the `.sh` test instead. The cost of
multi-runtime tests grows non-linearly; we pay the bash-test ergonomic
tax intentionally.

### Adjacent `.py` tests (acknowledged, not parity-gated)

| Test script | Phase | Asserts about | Why `.py` (sanctioned exception) |
|---|---|---|---|
| [`test-check-spec-folder-refs.py`](./test-check-spec-folder-refs.py) | 144 | `check-spec-folder-refs.py::load_allowlist()` strips inline `# comment` trailers (AC-62-04) — 4 `tempfile`-based unit tests | Exercises an internal function, not the CLI; loading the hyphenated source requires `importlib.util.spec_from_file_location`, which is awkward in bash. |

These are CI-runnable via `python3 linter-scripts/test/test-*.py` but
not yet wired as discrete steps in `spec-health.yml`. Acknowledgement
flows through `test-overview-inventory-parity.sh` (Phase 112) instead of
the README parity gate, which remains `.sh`-only by design.

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
bash linter-scripts/test/test-overview-inventory-parity.sh
bash linter-scripts/test/test-weights-parity.sh
bash linter-scripts/test/test-check-99-summary-freshness.sh
bash linter-scripts/test/test-check-99-stamp-bump.sh
bash linter-scripts/test/test-archive-exclusion-runtime.sh
```

Run all ten sequentially:

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
  [Phase 103](../../.lovable/memory/audit/v2-deterministic/phase-103-qa-baseline-footer-test.md) ·
  [Phase 107](../../.lovable/memory/audit/v2-deterministic/phase-107-overview-inventory-drift-audit.md) ·
  [Phase 112](../../.lovable/memory/audit/v2-deterministic/phase-112-overview-inventory-parity-test.md) ·
  [Phase 113](../../.lovable/memory/audit/v2-deterministic/phase-113-weights-parity-test.md)
