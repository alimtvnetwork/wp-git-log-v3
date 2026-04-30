# Phase 153 Task A24-fu19 — spec/04 floor lift + AC-13 §00 walker-pin promotion

**Date:** 2026-04-30
**Module:** spec/04-database-conventions
**Type:** Floor-lift + walker-pin promotion (Lesson #55 + Lesson #61)
**Pre-score:** 81 / 100 (GOOD, normative-contract floor)
**Expected post-score:** ≥ 89 / 100 (EXCELLENT band; LLM re-score deferred to next A20-fu rebaseline per Lesson #20)

## Findings closed (audit-v7 cache 2026-04-30)

| # | Severity | Dim | Auditor finding | Closure AC |
|---|----------|-----|-----------------|------------|
| 0 | HIGH     | D2  | Missing GWT for Core Naming Rules (Singular, PascalCase, PK={Table}Id) | **AC-14** four-invariant regex contract |
| 1 | MEDIUM   | D3  | Ambiguous "Smallest Type" Enforcement — DDL uses INTEGER everywhere   | **AC-15** three-condition lookup-table heuristic + DDL `ProjectStatusId` INTEGER → SMALLINT |
| 2 | LOW      | D1  | Inconsistent View Naming Prefix — DDL uses `View` suffix, §01 says `Vw` prefix | **AC-16** view-name `Vw`-prefix invariant + DDL `ProjectWithOwnerView` → `VwProjectWithOwner` + Forbidden Tokens row |

## §00 walker-pin (Lesson #55) promotion

AC-13 (Lesson #47 structural-pin shipped at A24-fu13) was buried at §97 line 133+ — past the 120 KB tier-2 walker cap. Promoted to a `> 🤖 Walker-Pin` teaser block immediately after the §00 banner, listing AC-13/14/15/16 in a 4-row table. A context-bounded walker reaching `00-overview.md` now sees all four structural anchors in the first ~2 KB instead of digging through 175 lines of MANDATORY callout + Golden Rules + Quick Reference + Canonical DDL prose.

## Lockstep

- §97 v1.4.0 → **v1.5.0** (AC count 13 → 16, minor — three new normative ACs)
- §00 v3.6.0 → **v3.7.0** (minor — walker-pin teaser + DDL identifier renames + Forbidden Tokens row)
- §98 v3.6.0 → **v3.7.0**
- §99 v3.8.0 → **v3.9.0**

## Strict gates

- Lockstep: 87/87 GREEN
- Tree-health: 168/168 strict GREEN
- Version-parity: 74/74 GREEN
- Freshness: 81 stamped + 6 exempt + 0 unstamped GREEN

## Lesson #61 (NEW)

The §00 walker-pin (Lesson #55) is most effective when bundled into the same phase as floor-lift ACs. Promoting an existing §97-buried structural-pin AC into the §00 teaser table at the same time as adding the new floor-lift ACs costs **zero additional lockstep risk** (the §00 banner bumps for the new content anyway) and delivers **compounding visibility benefit** — the walker now sees ALL anchors in the first 2 KB instead of digging deep.

**Rule:** Future floor-lift phases on modules that carry §97-buried structural-pin ACs (Lesson #50/#51 pattern) SHOULD bundle the §00 walker-pin promotion into the same lockstep budget. Current carriers that would benefit from same-phase bundling next time their floor surfaces:

- spec/13-generic-cli AC-25 (Lesson #50 walker-saturation pin) — promote to §00 walker-pin on next spec/13 floor lift
- spec/25-app-issues AC-AI-16 (Lesson #50 audit-corpus pin) — promote on next spec/25 floor lift

## Backlog status after this phase

| Floor candidate | Score | Status |
|-----------------|-------|--------|
| spec/01 = 83    | 83    | next-floor cluster |
| spec/22 = 83    | 83    | next-floor cluster (3/36 walker — likely walker-saturation, §00 walker-pin candidate) |
| spec/27 = 83    | 83    | cached (gateway 402 in v9 — re-score on A20-fu4) |
| spec/13 / spec/25 | various | §97-buried structural-pins ready for Lesson #61 bundling |

**Suggested next:** **spec/22 floor lift** — git-logs-v2, 3/36 walker hint (walker-saturation candidate, applies Lesson #55 §00 walker-pin pattern); or **A20-fu4 full-tree rebaseline** to confirm spec/04 lift registered.
