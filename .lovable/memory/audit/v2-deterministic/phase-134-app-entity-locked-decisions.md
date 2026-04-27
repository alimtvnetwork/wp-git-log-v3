# Phase 134 — §07 locked-decision text materialization + Q3/B1 status reconciliation

**Date:** 2026-04-27
**Trigger:** `next` after Phase 133. Investigated B1 + Q3 backlog items to determine if either is a phantom.

## Discoveries

### Q3 (per-SHA split-DB log storage) — fully implemented, never marked done
- `spec/22-git-logs-v2/39-split-db-log-storage.md` exists at v1.0.0 (created 2026-04-26, "Active (introduced in v3.8.0)")
- `ShaRegistry` integrated across §00, §01, §02, §04, §15, §17, §18, §22, §23, §29 (10 files — exactly the touch list Q3 was scoped for)
- §22 §98 v3.9.0 entry (2026-04-26) confirms "split-DB pointer (`ShaRegistry` with `LastStatus` enum CHECK)" landed in v3.9.0 / 18-schema.sql v2.9.3
- **Q3 is DONE.** Should be removed from active backlog.

### B1 (App identity columns) — locked-decision-12 materialization gap
- §97 AC-17 (Phase 13/16c) explicitly references "§07 locked decision 12" in the contract: forbids `Environment`/`Platform`/`OwnerEmail` until B1 user reply
- §07 itself was 54 lines and contained ZERO numbered locked-decision blocks — citation pointed nowhere
- Same pattern for citations to locked decisions 10, 11, 13 in AC-17/18/19/20

**This is a real lockstep drift, not a phantom.** B1 still requires user reply (Environment / all-three / keep-forbidden), but the de-facto state ("forbidden until unblocked") is already encoded in §97 AC-17 and was authoritative — just under-documented in §07.

### Follow-up finding (NOT addressed in Phase 134, logged for future)
- **§22 §99 inventory missing §39.** `99-consistency-report.md` lines 335-358 list §00–§31 but skip §39-split-db-log-storage.md entirely. The split-DB file landed without §99 inventory update. Discovered during Phase 134 lockstep verification. Recommend Phase 135 to add the §39 row + bump §99 v3.9.3 → v3.9.4. Low-risk single-line addition, no architectural decision required.

## Action (Phase 134 ONLY — tight scope)
Bumped `spec/22-git-logs-v2/07-app-entity.md` v2.0.0 → **v2.1.0** with new "Locked decisions referenced from this section" subsection materializing decisions 10, 11, **12**, 13. Decision 12 explicitly:
1. Codifies "identity is exactly the 5 columns above"
2. Names the FORBIDDEN columns (`Environment`, `Platform`, `OwnerEmail`)
3. Cites §97 AC-17 + §15 `GL-SCHEMA-DRIFT` as the enforcement layer
4. Documents the "forbidden-by-default, awaiting user `B1: …` reply" status
5. Provides rationale (undecided columns mean undecided semantics; early code would foreclose the actual decision)

## Lockstep
- §22 §07 banner v2.0.0 → v2.1.0
- §22 §98 v3.8.7 → **v3.8.8** (Phase 134 row added)
- §22 §99 banner v3.9.2 → **v3.9.3** (note: §39 inventory gap deferred to Phase 135)
- Memory `mem://specs/git-logs.md` open-question entry to be refreshed (B1 anchored to a real decision-12 block; Q3 status flipped to landed)

## Verification
- Lockstep gate: 87/87 pass · 0 findings ✅
- Python cross-link gate: green ✅
- Dashboard: **100/100 (A+)** · 0 broken ✅

## Backlog impact
- **Q3 retired** (already done since v3.9.0)
- **B1 status clarified**: still user-blocked but now anchored to a real §07 block, not a phantom citation. The default "keep forbidden" is already in force per locked decision 12; user can ratify it or override it.
- **Phase 135 queued**: add §39 to §22 §99 inventory (single-line lockstep follow-up)

## Files touched
- `spec/22-git-logs-v2/07-app-entity.md` — added Locked decisions subsection, banner bump
- `spec/22-git-logs-v2/98-changelog.md` — Phase 134 row added at top
- `spec/22-git-logs-v2/99-consistency-report.md` — banner bump only
- `.lovable/memory/audit/v2-deterministic/phase-134-app-entity-locked-decisions.md` — this memo
