# Phase H11 — Cross-Cluster Surface-Elimination Survey (read-only audit)

**Date:** 2026-04-28
**Trigger:** Backlog item #7 — extend H10's surface-elimination lens beyond the slot-26 / spec-validator surface to the remaining linter clusters: trace-map, lockstep, tree-health.
**Type:** Audit-only — no §98/§99 banner bumps (per H6 lesson #2).

## Goal
Apply the H10 graduation filter (mechanically detectable + active regression surface + low false-positive risk) and surface-elimination preference (remove failure modes rather than detect them) to the three remaining linter clusters. Identify candidates for promotion or surface-elimination.

## Clusters surveyed

### Cluster A — Trace-map (`generate-trace-map.py` + `check-trace-map-regression.py`)
- Generator scans `linter-scripts/trace-map.toml`, walks `spec/**` for AC IDs, walks `linter-scripts/**` for code files. Already excludes `spec/_archive/**` (Phase H3, line 58).
- Regression gate compares fresh summary vs `trace-map-baseline.json`. Tracks 9 keys; fails on `ac_traced` drop, `ac_drifted`/`code_orphan` growth, any `missing_ac`/`missing_file`.
- **Lessons in memory affecting this cluster:**
  - G-series: "always `ls linter-scripts/<name>.*` BEFORE authoring `[[trace]]` entries" (procedural).
  - "trace-map regression gate is reliable belt-and-braces but pre-flight saves cycles" (procedural).
  - "Future single-phase delta >50 ACs or >5 code files MUST be inspected before rebaselining again" (procedural threshold).

### Cluster B — Lockstep (`check-lockstep.cjs`)
- Three structural rules L1/L2/L3 enforcing date ordering across §00 / §98 / §99.
- Already excludes `_archive` (Phase H3, line 105).
- Memory rule: "Spec edits keep these in lockstep: target file banner + §98 changelog row + §99 health/inventory + `mem://specs/git-logs.md` queued-decisions trail."
- Self-test coverage: `test-check-99-stamp-bump.sh`, `test-qa-baseline-footer.sh` (cousins, not direct).

### Cluster C — Tree-health (`check-tree-health.cjs`)
- Rubric: required (60%) + recommended (25%) + quality (15%). `--strict` requires per-module max.
- Already excludes `_archive` (line 59).
- Memory rule: "Run `check-tree-health.cjs --strict` periodically — default mode rounds to 100 even when one module slips; strict caught 22-git-logs-v2 at q=2/3 invisible to default gate."
- Memory rule: "§99 inventory heading must match `(File Inventory|Module Inventory|Top-Level Modules|Document Inventory|Modules)` — bare `## Inventory` silently loses the rubric-v2 inventory credit (precedent: Phase 137)."

## Candidates surveyed

| # | Candidate | Cluster | Verdict | Reasoning |
|---|---|---|---|---|
| K1 | "Single-phase delta >50 ACs or >5 code files MUST be inspected" → automated rebaseline guard | A | **Defer — already enforced indirectly** | `--update-baseline` is an explicit human action; no mechanical pathway exists for accidental large rebaselines. The 50/5 thresholds are review-time guidance, not regression detection. Adding a `--max-delta=N` flag would gate human intent — false-positive prone (legitimate G-series style sweeps would fire). Better as procedural rule (already in Core). |
| K2 | "Always `ls linter-scripts/<name>.*` before authoring `[[trace]]` entries" → pre-validation in TOML loader | A | **No-op — already enforced** | `generate-trace-map.py` already validates each `[[trace]]` entry's `code` path and emits `missing_file` count; regression gate fails build on `missing_file > 0`. The procedural "ls first" rule saves cycles (G-series lesson #2 explicitly says "regression gate is reliable belt-and-braces"). Surface eliminated by existing `missing_file` check. |
| K3 | "Default `check-tree-health.cjs` rounds to 100 even when one module slips" → make `--strict` the default | C | **Defer — backwards-compat / dual-purpose** | The dual-mode design is intentional: default = adoption-friendly threshold (75), strict = CI gate. Memory Core already mandates "Run `check-tree-health.cjs --strict` periodically" and CI uses `--strict`. Flipping the default would break ad-hoc local runs documented in spec/27. No regression surface — CI is already strict. |
| K4 | "Bare `## Inventory` silently loses rubric-v2 inventory credit" (Phase 137 lesson) → warn on near-miss heading | C | **Defer — low-value lint** | Mechanizable: emit a per-module advisory when a §99 contains `## Inventory` (or other near-miss like `## Spec Inventory`) AND scores 0/1 on `QUALITY_INVENTORY_RE`. But: (a) Phase 137 audit confirmed all 168 modules currently match the canonical regex; (b) future near-misses surface as quality-credit drops in the existing strict gate; (c) the canonical regex is documented in §27 slot 25 + memory Core. Surface effectively monitored via `--strict` per-module floor. |
| K5 | Lockstep gate accepts only the four documented release-line formats (heading variants + table-row) | B | **No-op — already enforced** | The four accepted formats are codified in `releaseDates()` (lines 70-86). Any new format silently fails L2. This is by design — adding a new format requires touching the linter, which is the correct governance boundary. No regression surface. |
| K6 | "mem://specs/git-logs.md queued-decisions trail" lockstep step (Core rule #4 in lockstep tuple) | B | **Defer — non-mechanical without scope creep** | Lockstep gate enforces 3 of 4 lockstep targets (§00/§98/§99). The 4th (mem trail) is a memory-namespace artifact outside spec/. Mechanizing it would require lockstep to traverse `mem://`, conflating spec governance with memory governance. Procedural rule is correct — keep as-is. |
| K7 | `_archive/` exclusion is reapplied per-cluster (3 different code paths) → consolidate into shared helper | A+B+C | **Defer — premature abstraction** | Each cluster's exclusion is one line (`_archive` literal or `ARCHIVE_PREFIX` constant). Consolidation would create cross-language coupling (Python + 2× JS). The H3 lesson is already in memory Core ("`_archive/` exclusion is the standard pattern for any spec-traversing linter"). Phase H7 self-test (`test-archive-exclusion-runtime.sh`) already runtime-verifies 3 enumerators return 0 archive-leaked entries. Surface eliminated by H7 gate. |
| K8 | Trace-map's 25 remaining orphans (run.sh, deprecated-v1, helpers, audit-internals) → mechanical binding | A | **Blocked — needs R1** | Memory Core: "remaining ~25 orphans are run.sh/deprecated-v1/helpers/audit-internals — R1 (blocked on Lovable Cloud) is the proper deeper-binding tool." Already correctly classified as out-of-scope for mechanical work. Not a surface-elimination candidate. |

## Net result

**0 candidates promoted, 0 surface-elimination opportunities found.**

Breakdown:
- 3 already enforced (K2, K5, K7) — surfaces previously eliminated by `missing_file` check, governance boundary, and H7 runtime gate respectively.
- 3 deferred as low-value or backwards-compat (K1, K3, K4) — adding lints would create false-positive risk or break adoption-friendly defaults.
- 1 procedural-by-design (K6) — would require scope creep across memory/spec boundary.
- 1 blocked (K8) — needs R1 (Lovable Cloud).

## Lessons codified

### L1 — H10 filter generalises cleanly across clusters
The H10 graduation filter (mechanical detectability + active regression surface + low false-positive risk) eliminated all 8 cross-cluster candidates without modification. Surface-elimination preference (K2, K5, K7) remained the dominant pattern — 3 of 8 were already surface-eliminated by prior gates.

### L2 — H7 retroactively closed the cross-cluster `_archive` surface
Phase H7's runtime probe gate covers all three enumerators (slot-26 freshness, audit-v5, trace-map). The `_archive` exclusion code in lockstep + tree-health remains correct but is now belt-and-braces because H7 is the mechanical proof. New spec-traversing linters added in the future inherit H7 coverage by adding their enumerator to the probe (AC-28-04 floor ≥ 3 — already satisfied at 3).

### L3 — Adoption-friendly defaults are a legitimate governance choice
K3 (tree-health default = 75, strict in CI) and K1 (no `--max-delta` rebaseline cap) both resolved to "keep as-is" because the dual-mode design is intentional governance, not a missing lint. The H10 filter's "low false-positive risk" criterion correctly flags both as risky-to-mechanize.

### L4 — Cross-language consolidation is premature without ≥3 incidents
K7 surveyed whether to extract `_archive` exclusion into a shared helper. Verdict: no — three single-line exclusions across two languages is below the abstraction threshold. The H3 memory rule + H7 runtime gate are sufficient. Establish abstraction only after a 3rd incident of "forgot to exclude `_archive`".

## No code changes
- 0 new files
- 0 modified scripts
- 0 spec edits
- 0 §98/§99 bumps
- 0 AC-31-31 cascade
- All gates remain green at H9-close baseline.

## Memory updates
- Mark backlog item #7 RESOLVED with verdict NO-OP.
- Add H11 lessons L1–L4 (compressed) to Core under the existing graduation-pattern bullet.
