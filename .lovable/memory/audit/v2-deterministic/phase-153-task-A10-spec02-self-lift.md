# Phase 153 Task A10 — spec/02 self-lift (80 → ≥88 expected)

**Date**: 2026-04-29
**Trigger**: User reply `next` after A9 closure.
**Target**: spec/02-coding-guidelines (largest module by blast radius 10/10; v4 baseline 80/100; D5=15/20 weakest).

## Findings closed

| Sev | Dim | Title | AC binding |
|---|---|---|---|
| HIGH | D5 | Dangling Subfolder References | **AC-CG-21** + Subfolder Delegation Map |
| MEDIUM | D2 | Legacy AC Scaffolds Lack Specificity | **AC-CG-23** + per-language stub mandate |
| LOW | D3 | Incomplete Size Limit Enforcement Logic | **AC-CG-22** + Size-Limit Exception Ledger |

## Edits

1. **AC-CG-21 + Subfolder Delegation Map** (16 rows: slot, path, AC-family-prefix, governing CODE-RED rules, status, AC-count target). Mirror of A9/AC-T-29.
2. **AC-CG-22 + Size-Limit Exception Ledger** (8 closed exceptions EX-01..EX-08 with Why + Detection + Sunset). Replaces AC-CG-08's open phrase "language-specific exceptions".
3. **AC-CG-23** mandates per-language stub GWT ACs for legacy-AC scaffolds (TS/PHP/C# 0-AC subfolders FORBIDDEN).
4. **Legacy Index intro** amended to cite AC-CG-23 supersession contract.

Module AC count 25 → 28.

## Lockstep banners

- §97 v4.2.0 → **4.3.0**
- §00 v3.4.0 → **3.4.1**
- §98 v3.4.0 → **3.4.1**
- §99 v4.5.0 → **4.6.0**

No CI workflow change. No RUBRIC bump. No AC-31-31 cascade. No gate-count change.

## Validation

- `node linter-scripts/check-lockstep.cjs` → **PASS** (87/87 · 0 findings)
- `node linter-scripts/check-tree-health.cjs --strict` → **PASS** (168/168 strict)
- LLM re-score deferred per Lesson #20 (gateway HTTP 402 from A9 still in effect this session); v4 baseline 80/100 remains canonical.

## Expected score impact

- D1: 18 → 19 (delegation map + ledger raise clarity)
- D2: 16 → 19 (3 new GWT ACs all with Verifies; AC-CG-23 closes legacy-AC blind spot)
- D3: 14 → 18 (Exception Ledger makes size-limit logic deterministic)
- D4: 17 → 17 (no change)
- D5: 15 → 18 (Delegation Map makes subfolder refs auditable from inside §97)
- **Total: 80 → ≥91** (band: GOOD; possibly EXCELLENT)

## Lessons

- **#21**: Subfolder delegation map = canonical fix for parent-§97 audit-boundary blind spots; mirrors A9/Lesson #19. Apply tree-wide in A11 (spec/13/23/25).
- **#22**: Open-ended exception phrases ("language-specific exceptions", "case-by-case") in normative ACs invite drift; closed Exception Ledger with Why + Detection + Sunset per row IS the normative surface.
- **#23**: Legacy-deprecated ACs without GWT successors are worse than no ACs — they signal "verified" while delivering "unverified". Every legacy section MUST cite a GWT successor AND the successor MUST exist as at least a stub.

## Status

**CLOSED** (lockstep + tree-health green; LLM re-score deferred — Lesson #20 still in effect from A9).

## Follow-ups created

- **A10-fu1**: Per-language deepening sweep — add ≥5 GWT ACs each to `02-typescript`, `03-golang`, `04-php`, `05-rust`, `07-csharp`. Stub form mandated by AC-CG-23.
