# Phase H5 — §99 Stamp-Bump Gate CI Wiring

**Date:** 2026-04-28
**Status:** CLOSED
**Predecessor:** Phase H4 (shipped validator + self-test + spec slot 27 v1.0.0; deferred CI wiring)
**Successor:** none queued

## What

Wired Phase H4's `linter-scripts/check-99-stamp-bump.py` validator and `linter-scripts/test/test-check-99-stamp-bump.sh` self-test into `.github/workflows/spec-health.yml` as the **16th strict CI gate**, completing the H4→H5 split.

## Why split H4 / H5

H4 codified the lesson "don't rush AC-31-31 cascades": shipping the gate code without the cascade keeps the diff small and reviewable; the cascade lands cleanly in H5 once the tool is verified-stable in isolation. This split also surfaced the workflow-step parity decision (single step or two?) without coupling it to validator correctness.

## Wiring decisions

1. **Single collapsed workflow step** named `§99 Stamp-bump gate (Phase H5)`. Per the H1 workflow-step parity lesson recorded in core memory: single-gate self-tests (those exclusively exercising one numbered footer gate) MUST collapse into the parent gate's step. Standalone self-test steps belong only to broader-contract tests (#5–#7, #9, #10, #12, #13). Verifies AC-31-28 footer-row ↔ workflow-step ↔ declared-count parity at **16 / 16 / 16**.
2. **Conditional validator** — runs only on `pull_request` events. On `push` to `main` there's no PR base SHA, the script would exit 2 (cannot determine diff base), and the gate would always fail on direct merges. The self-test always runs, so the AC-27-01..08 contract is locked regardless of event type.
3. **`fetch-depth: 0`** on the checkout step — the script needs full git history to resolve `git diff $BASE_REF...HEAD`. Default depth-1 checkout would break diff resolution.
4. **`STAMP_BUMP_BASE_REF: ${{ github.event.pull_request.base.sha }}`** env var — passes the PR base SHA explicitly so the script doesn't fall back to `origin/main` (which may not exist locally on the runner under shallow defaults).

## AC-31-31 cascade (the reason H5 exists as its own phase)

| Surface | Before | After |
|---|---|---|
| `RUBRIC_VERSION` constant | `v2.24` | **`v2.25`** |
| QA-baseline footer count | "15 strict CI gates" | **"16 strict CI gates"** |
| QA-baseline footer entries | 15 numbered rows | **16** rows (added #16 §99 Stamp-bump) |
| `EXECUTIVE-SUMMARY.md` cross-ref | "15 strict CI gates" | **"16"** |
| `test/test-qa-baseline-footer.sh` awk | 15 patterns | **16** (added `/§99 Stamp-bump gate/`) |
| `.github/workflows/spec-health.yml` quality-gate steps | 15 | **16** |

Self-test confirms: `Workflow gates: 16, Footer rows: 16, Declared: 16` — parity green.

## Verification (full gate suite)

- **H5 self-test**: 23/23 ✅ (10 test cases T1–T10 for skip-rules, archive exclusion, structural errors)
- **H1 freshness**: 75 stamped / 0 stale ✅
- **QA-baseline-footer**: 11/11 ✅
- **Lockstep**: 87/87 / 0 findings ✅
- **Tree-health strict**: 168/168 ✅
- **Audit thresholds**: mean weighted 98.0, mean impl 99.8 ✅ (`--min-weighted=97 --min-impl=99`)
- **Trace-map regression**: ✅ at unchanged baseline `{ac_total:1315, ac_traced:85, code_total:50, code_orphan:26}`

## What did NOT change

- No §99 stamps re-bumped — H5 doesn't materially edit any §99 narrative claim (only §27 changelog/consistency-report bookkeeping, which carry their own banner versions, not freshness stamps)
- Trace-map baseline unchanged — no new code files, no new ACs (the wiring is a workflow yaml change, not a script change)
- No new spec slot — slot 27 (Phase H4) is the spec home; H5 is pure CI plumbing
- No memory core changes (the H1 workflow-step parity lesson stays the relevant rule)

## Lessons codified

1. **AC-31-31 cascade has a definite shape** — RUBRIC bump + footer count + footer enumeration + EXECUTIVE-SUMMARY back-ref + QA-baseline-footer awk pattern + workflow step. The H4/H5 split makes this auditable as a single coherent change.
2. **Conditional CI gates need explicit event guards** — `if: github.event_name == 'pull_request'` (or inline shell guard) prevents always-fail on push-to-main when the gate is diff-based.
3. **`fetch-depth: 0` is an unavoidable cost for diff-based gates** — slower checkout, but required.

## Files touched

- `.github/workflows/spec-health.yml` (added H5 step + bumped checkout fetch-depth)
- `linter-scripts/audit-spec-vs-code-v2.py` (RUBRIC v2.24→v2.25, footer 15→16, +entry #16, EXECUTIVE-SUMMARY back-ref)
- `linter-scripts/test/test-qa-baseline-footer.sh` (awk +1 pattern, count comment 15→16)
- `spec/27-spec-toolchain/98-changelog.md` (v2.44.0 → v2.45.0)
- `spec/27-spec-toolchain/99-consistency-report.md` (v2.41.0 → v2.42.0)
- `.lovable/memory/audit/v2-deterministic/phase-h5-stamp-bump-ci-wiring.md` (this memo)
- `.lovable/memory/index.md` (CI gate count 15→16, RUBRIC v2.24→v2.25)
