# Phase 153 Task A24-fu44 — spec/14 AC-21 stale-cache enumeration extension

**Closed:** 2026-05-04
**Module:** spec/14-update (axis: normative-contract; cache total=91, files_used=13/54 = 24%)

## Findings closed (3, all walker-cap artifacts)
- **HIGH D5** "Missing Sub-Module Context (Files 09-27)" — already covered by AC-21 v1; re-affirmed.
- **MEDIUM D3** "Ambiguous 'updater.exe' Lifecycle" — canonical contract in `19-updater-binary.md` (224 lines) + `12-code-signing.md` + `06-cleanup.md` (AC-16 wall-clock budget) + AC-17 rollback-ownership; none in tier-1 bundle.
- **LOW D1** "Undefined 'latest.json' Schema in Overview" — schema canonically defined in `01-self-update-overview.md` + `13-release-assets.md`; §00 line 84 already links there per Lesson #36 (link-don't-restate).

## Resolution
Extended AC-21's `Then` clause from a 2-class enumeration (D5 + D4) to a 4-class enumeration adding D3 + D1, with cache `files_used=13/54 ≈ 24%` saturation citation. No new AC; no AC-31-31 cascade. AC count stays 22.

## Lockstep
- §97 v2.4.1 → **v2.4.2** (patch — prose-only AC extension)
- §00 v2.4.3 → **v2.4.4** (banner-only)
- §98 v2.4.3 → **v2.4.4** (this row)
- §99 v1.6.3 → **v1.6.4** (audit row)

## Lessons applied
- **#75** walker-cap triage before self-lift (24% saturation → harness-pin extension, not new contracts)
- **#34** cache ≠ truth source — disk inspection confirmed all 3 contracts on-disk
- **#36** link-don't-restate (D1 latest.json — §00 links, doesn't duplicate)
- **#71** no-op threshold variant: gap=9, but ALL findings are stale-cache → harness-pin extension only, not contract-tightening (counter to fu38 which had genuine prose gaps).

## Gates
Lockstep 87/87 · tree-health 168/168 strict · version-parity 74/74 — all GREEN.
LLM re-score deferred per Lesson #20.
