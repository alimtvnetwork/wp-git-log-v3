# Phase 87 — Contributor DX (PR template + CONTRIBUTING.md)

**Date:** 2026-04-27
**Scope:** `.github/PULL_REQUEST_TEMPLATE.md` (new), `CONTRIBUTING.md` (new)
**Status:** ✅ Complete

## Why
Phases 81–86 ratcheted the CI gates to a tight bar (tree 100/100 strict, lockstep 0
findings strict, audit weighted ≥97 / impl ≥99) but contributors had **no surfaced
documentation** of the bar. A new contributor would discover the rules only by
pushing and watching CI fail. This phase makes the bar discoverable before the
first push.

## What landed

### `.github/PULL_REQUEST_TEMPLATE.md` (new)
- Summary section (1 paragraph)
- Spec-impact checklist (4 boxes covering overview / AC / changelog+consistency / lifecycle)
- **Quality gates checklist** with copy-pasteable commands for all 4 CI gates
- Special section "If you touched `audit-spec-vs-code-v2.py`" — enforces paired
  spec/AC/changelog/empirical-memo updates (mirrors Phase 85's bijection rule)
- Reviewer notes section

### `CONTRIBUTING.md` (new, repo root)
- **Quality bar table** — current thresholds with source-of-truth scripts
- **The four CI gates** — purpose + invocation for each, including `--min-weighted` / `--min-impl` flags from Phase 81/84
- **Working in `spec/`** — module folder shape, edit checklist, front-matter keys (`kind: tracker|index|meta-toolchain`, `todo_audit_exempt: true`)
- **Working on the audit script** — paired-update rule + reference to Phase 86
  rejected-experiment memo as the negative-result template
- **Phase memos** — describes the structure used in `.lovable/memory/audit/v2-deterministic/`
- **Local dev tips** — `AUDIT_DETERMINISTIC=1` default, `AUDIT_ONLY` smoke-test, lockstep date alignment

Both docs reference the live floors (97 / 99) and call out the script-spec
bijection rule that Phase 85 documented.

## Verification
- **Tree health (strict):** ✓ 100/100
- **Lockstep (strict):** ✓ 0 findings
- **Audit (`--min-weighted=97 --min-impl=99`):** ✓ 98.0 / 99.8 PASS

These docs live outside `spec/` so they don't trigger the audit script (which
only scans `spec/<NN>-*` folders). No spec-side changes were needed.

## Effect
First-time contributors now see:
1. The exact 4 commands to run before pushing (PR template checklist)
2. The full rubric reference + module shape (CONTRIBUTING.md)
3. The "negative results count" rule that prevents lossy re-investigation of
   rejected experiments (Phase 86 cited as canonical example)

Reduces CI churn and onboarding cost without adding any new gates.
