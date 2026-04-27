# Phase 85 — Toolchain Doc Sync (Rubric v2.9 → v2.14 + CLI flags)

**Date:** 2026-04-27
**Scope:** `spec/27-spec-toolchain/31-audit-spec-vs-code-v2.md`
**Status:** ✅ Complete

## Why
The deterministic audit script `linter-scripts/audit-spec-vs-code-v2.py` had
advanced 5 rubric versions (v2.10 → v2.14) and gained 2 new CLI flags
(`--min-weighted`, `--min-impl`) without corresponding updates to its spec.
Toolchain bijection (every script ↔ exactly one spec, all behaviour documented)
requires the spec to track the script. Phase 85 closes the gap.

## Changes
| Section | Before | After |
|---|---|---|
| Header version | 1.7.0 | **1.8.0** |
| Source line | bare path | path + script v2.14 marker |
| Category | "deterministic mode + hard scoring gates" | + "CI threshold flags" |
| Usage block | 3 invocations | 4 invocations + new "CLI flags (v2.12, Phase 81)" subsection with full table |
| Acceptance criteria | AC-31-01 → AC-31-16 | + **AC-31-17 → AC-31-22** (6 new) |
| Rubric changelog | (none) | New "Rubric changelog (v2.9 → v2.14)" table |
| Cross-references | 3 entries | + §70 spec-health-yml |

## New ACs (one per rubric version, except v2.9 which gets one)
- **AC-31-17** — Root index inherits top-level folders as children (v2.9)
- **AC-31-18** — Evidenced-meta-toolchain bonuses: +5 mermaid / +5 ci-workflow (v2.10)
- **AC-31-19** — Contract-bearing index bonus: +5/contract, cap 90→100 (v2.11)
- **AC-31-20** — `--min-weighted` / `--min-impl` CLI threshold gates (v2.12)
- **AC-31-21** — Contract-bearing tracker bonus: +5/contract, cap 85→95 (v2.13)
- **AC-31-22** — Tightened TODO regex + `todo_audit_exempt: true` opt-out (v2.14)

## Verification
- **Tree health (strict):** ✓ 100/100 across 56 modules
- **Lockstep (strict):** ✓ 0 findings
- **Audit (`--min-weighted=97 --min-impl=99`):** ✓ mean weighted 98.0, mean impl 99.8 — PASS
- **No score regression:** §27 still A+, §31's parent (`27-spec-toolchain`) gets fuller documentation without losing any quality dimension (the overview's `todo_audit_exempt: true` already shields any new `TODO:` literals; new ACs use inline-code spans which `strip_code` removes before TODO scanning).

## Effect
Toolchain bijection trace-map is now consistent: every rubric branch and CLI flag
in `audit-spec-vs-code-v2.py` has a corresponding normative AC in
`spec/27-spec-toolchain/31-audit-spec-vs-code-v2.md`. Future rubric changes must
land with paired AC updates (lockstep gate enforces date alignment).
