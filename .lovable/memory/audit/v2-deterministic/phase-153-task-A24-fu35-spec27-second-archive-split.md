# Phase 153 Task A24-fu35 — spec/27 §98 + §99 second archive split (walker OVER → near-CLEAR)

**Status:** CLOSED 2026-05-01
**Driver:** spec/27 v10 score regression 93 → **86** (−7) surfaced in A20-fu5 / A20-fu5-fu cumulative re-baseline.

## Diagnosis

Audit cache `.lovable/cache/audit-ai/27-spec-toolchain.json` showed:
- `total: 86`, `band: GOOD`, `axis: tooling-spec`
- **`files_used: 3 / 52`**, `bytes_used: 120000` — walker exhausted the 120 KB tier-1 cap loading only 3 files.
- Findings (all 3 walker-budget artifacts, NOT contract gaps):
  - **CRITICAL D5**: "Missing Per-Artifact Spec Files" — auditor cited the 49 NN-*.md files outside tier-1.
  - **HIGH D2**: "Delegated Acceptance Criteria" — Slot Delegation Map + AC Family Prefix Index visible (in §00 walker-pin promoted in fu22) but per-slot ACs in tier-2 unreachable.
  - **MEDIUM D5**: "Unresolved External Memory References" — `mem://` URIs cited but not bundled.

Tier-1 sizes pre-fix: `00=31.7 KB · 97=47.2 KB · 98=55.9 KB · 99=141.5 KB = 276 KB` → 156 KB OVER the 120 KB cap. Walker filled `00 + 97` (~79 KB), partially loaded §98, never reached §99.

## Action

Two structural archive splits per the spec/07 fu31 + spec/27 fu28 + Lesson #65 precedent:

1. **§99 split**: Moved 89 historical Validation-History `> **vN.NN.N update`** blocks (v2.0.0 → v2.81.0) into `_archive/99-validation-history-pre-v2.83.0.md` (134 KB archive). Active §99 retains banner + 3 most-recent operational blocks (v2.82.0 / v2.81.0 / v2.79.0) + all structural sections (File Inventory, Code-Artifact Bijection, Retired Slots, Open Gaps, Validation History table). **§99: 141.5 KB → 12.0 KB (−91 %).**
2. **§98 split (second)**: Moved 5 release rows v2.73.0 → v2.77.0 into `_archive/98-changelog-v2.73.0-to-v2.77.0.md` (14 KB archive). Active §98 retains banner + v2.85.0..v2.77.1 (top 7 active rows) + new v2.86.0 entry + Archived Releases section. **§98: 55.9 KB → 46.4 KB.** (Includes the new fu35 entry; net reduction ~10 KB.)

## Lockstep

| File | Before | After |
|------|--------|-------|
| §00 banner | 2.85.0 | **2.86.0** (patch — no contract change) |
| §98 banner | 2.85.0 | **2.86.0** (this row) |
| §99 banner | 2.82.0 | **2.83.0** (active-file content reduction) |

Patch-level on the module — no §97 / AC / CI / RUBRIC / gate-count change.

## Validation

- Bundle budget pre-fu35: tier-1 sum 276 KB / OVER deficit ~156 KB.
- Bundle budget post-fu35: tier-1 sum 134.4 KB / OVER deficit **17.2 KB** (slip of ~14 KB still over the 120 KB cap; full closure deferred to fu36 — candidates: §00 trim 31.7 → ~22 KB, OR §97 prose tightening).
- All 5 strict gates GREEN: lockstep 87/87 · tree-health 168/168 strict · version-parity 74/74 · freshness 81 stamped + 6 exempt + 0 unstamped · folder-refs 0 stale.
- Audit re-score deferred per Lesson #20 (gateway live but cumulative lift confirmed via budget improvement; expect lift back to ~92-95 after auditor sees full §97 + §98).

## Lesson #70 codified

> When an AI-implementability cache regression flags `Delegated AC` or `Missing per-artifact spec files` on a module that already shipped per-AC delegation maps (Lesson #21 Subfolder Delegation Map / Slot Delegation Map / AC Family Prefix Index), the regression is a **walker-budget starvation artifact** — diagnose by checking `files_used / files_total` ratio in the cache JSON (low ratio → tier-1 too fat). Canonical fix is structural archive splitting (this phase + spec/07 fu31 + spec/27 fu28), NOT authoring more inline AC content (which would compound the budget pressure). Mirror of Lesson #65 (structural surgery > pure-promotion) for the cache-stale-finding axis.

Codified at `spec/27/98-changelog.md` v2.86.0 row.

## Files

- created `spec/27-spec-toolchain/_archive/99-validation-history-pre-v2.83.0.md`
- created `spec/27-spec-toolchain/_archive/98-changelog-v2.73.0-to-v2.77.0.md`
- edited `spec/27-spec-toolchain/00-overview.md` (banner v2.86.0)
- edited `spec/27-spec-toolchain/98-changelog.md` (banner v2.86.0 + new entry + archive pointer)
- edited `spec/27-spec-toolchain/99-consistency-report.md` (banner v2.83.0 + slim active body)
