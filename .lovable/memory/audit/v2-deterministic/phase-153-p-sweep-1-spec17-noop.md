# Phase 153 P-sweep-1 — spec/17 Cross-Module Anchor Map (NO-OP — already complete)

**Date:** 2026-05-06
**Module:** spec/17-consolidated-guidelines
**Disposition:** **NO-OP** — Lesson #36/#37 pair already shipped (in fact, the most mature instance tree-wide)
**Lesson tested:** P-sweep-1's premise (spec/17 needs anchor-map AC) was a **false positive from the parent P-sweep grep heuristic**.

## Pre-flight discovery (Lesson #30 verify-before-open)

Listing actual ACs in `spec/17-consolidated-guidelines/97-acceptance-criteria.md`:

| AC | Title | Pattern role |
|---|---|---|
| AC-10 | Consolidated-guide module-kind pin (audit-v6 close-out) | Lesson #19 audit-boundary anchor |
| AC-11 | **Subfolder Delegation Map** (rollup-source binding with `[STUB]` markers) | **Lesson #36 cross-module anchor map** |
| AC-12 | Worked Example — source→consolidated mapping for `03-error-management.md` | Proof-of-parity pattern |
| AC-13 | **Source-Wins** conflict-resolution contract | Lesson #36 conflict-drift complement |
| AC-14 | `// LINTER-IGNORE-TODO` comment-syntax contract | Audit-marker exemption |
| AC-15 | **Rollup-not-first-party-contract structural pin** | Lesson #51 mirror (5th axis) |

This is the **richest integration-axis closure tree-wide** — strictly stronger than spec/22 (AC-78/AC-79) and spec/12 (AC-10/AC-11):
- spec/22/12 carry 2 ACs (pin + map).
- spec/17 carries **5 ACs** spanning the full Lesson #19/#36/#37/#51 quadrant.

Authoring any new "anchor map AC" would duplicate AC-11 and create the very dual-source drift class Lesson #36 forbids.

## Why P-sweep's grep produced a false positive

P-sweep's anchor-map detection pattern was:

```
grep -ciE "cross-module.*(citation|anchor|map) | externalized citation | restate-in.*forbidden"
```

spec/17 AC-11's heading is **"Subfolder Delegation Map"** (per the rollup module-kind nomenclature) and AC-13's contract is **"Source-Wins"** — neither phrase contains "cross-module", "externalized", or "restate-in". The grep missed both. Re-running with widened heuristics:

```
grep -ciE "module-kind | kind:.*(interface|tracker|index|post-mortem|rollup|normative) | audit-boundary | walker.*(cap|tier) | structural-pin"  (PIN)
grep -ciE "delegation map | cross-module.*(citation|anchor|map|binding) | externalized citation | restate-in.*forbidden | source-wins | subfolder delegation"  (MAP)
```

**Corrected pair-coverage table (9 modules surveyed):**

| Module | ACs | Pin | Map | Pair |
|---|---:|---:|---:|---|
| 03-error-manage | 12 | 3 | 0 | ❌ |
| 10-research | 10 | 3 | 1 | ✅ |
| 11-powershell-integration | 13 | 2 | 1 | ✅ |
| 12-cicd-pipeline-workflows | 14 | 6 | 8 | ✅ |
| 14-update | 22 | 1 | 0 | ❌ |
| 16-generic-release | 21 | 1 | 0 | ❌ |
| 17-consolidated-guidelines | 15 | 17 | 12 | ✅ (richest) |
| 18-wp-plugin-how-to | 16 | 8 | 1 | ✅ |
| 22-git-logs-v2 | 73 | 3 | 7 | ✅ |

**6/9 already pair-complete.** True remaining gaps: **spec/03 (43 ext refs — HIGH), spec/14 (17 — MED), spec/16 (11 — LOW)**.

## NEW Lesson #87 — Survey heuristics MUST be cross-validated against §97 AC titles before opening dependent phases

Mirror of Lesson #30 (verify-before-open) at the survey-heuristic level:

> Before treating a survey count as ground truth for opening N follow-up phases, list the §97 AC titles in at least 2 sample modules from the "missing" bucket and confirm the pattern is genuinely absent (not just hidden behind axis-specific terminology). Survey grep is a starting point — never the verdict.

Specifically: **anchor-map** and **delegation map** and **source-wins** and **structural-pin** are all the same Lesson #36 pattern under different module-kind nomenclatures. The pattern adapts to the module's `kind:` front-matter:

- `kind: rollup` (spec/17) → "Subfolder Delegation Map" + "Source-Wins"
- `kind: interface-contract` (spec/12) → "Cross-Module Externalized Citation Map"
- `kind: integration` (spec/22) → "Cross-Module Externalized Citation Map"
- `kind: post-mortem` (spec/25) → "Module-kind pin" + "Quoted-evidence rule"

The Lesson #87 grep-pattern UNION (above) is now the canonical detection vocabulary.

## Disposition for next phases

P-sweep ranking is **revised** from the original memo:

| Phase | Module | Status | Notes |
|---|---|---|---|
| ~~P-sweep-1~~ | spec/17 | **NO-OP CLOSED** | AC-10/11/12/13/15 already in place — richest tree-wide |
| **P-sweep-2** | spec/03 | **next-up** | True gap; 43 ext refs; needs anchor map AC |
| P-sweep-3 | spec/14 | queued | True gap; 17 ext refs |
| P-sweep-4 | spec/16 | queued | True gap; 11 ext refs (may also NO-OP per Lesson #79 plateau) |
| P-sweep-5 | spec/10/11/18 | queued | Already pair-complete per corrected survey — verify completeness depth before opening |

## No edits this phase

Pure verification + lesson codification. Counter incremented. Lockstep/tree-health unaffected.

## Cross-references

- Original P-sweep memo: `phase-153-p-sweep-lesson37-pair-coverage.md` (heuristic was too narrow)
- Lesson #30 (verify-before-open): `mem://process/phase-153-lessons` § E
- Lesson #36 (link-don't-restate): `mem://process/phase-153-lessons` § C
- Lesson #37 (integration-axis pair): index narrative
- Lesson #51 (structural-pin pattern): spec/17 AC-15 verifies-clause
