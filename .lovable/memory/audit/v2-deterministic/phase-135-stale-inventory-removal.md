# Phase 135 — §99 stale duplicate-inventory removal

**Date:** 2026-04-27
**Trigger:** `next` after Phase 134. Phase 134 deferred this as "low-risk single-line addition" but investigation showed the gap was wider and the structural fix was cheaper than the patch.

## Discovery

Phase 134's lockstep verification flagged §99 missing §39 in its bottom inventory. On inspection:

- **Two inventory blocks coexisted in §99**: an authoritative one at lines 8–48 (with version annotations + ⚠️/✅/🗑️ status markers) and a duplicate "File Inventory" block at lines 330–358 (plain ✅ Present rows).
- The TOP block was current (already listed §32–§37, §39, §97–§99 with versions).
- The BOTTOM block was stale — **12 missing rows**: §17-openapi.yaml, §18-schema.sql, §28, §30, §31, §32–§37, §39. Last refreshed 2026-04-26 and silently fell out of sync as new files landed.

Phase 134 only saw the §39 omission because that's what its lockstep query happened to spot.

## Action

Removed the duplicate bottom block entirely (replaced with HTML comment documenting why). Single source of truth eliminates a class of drift instead of patching one symptom.

- §99 v3.9.3 → **v3.9.4** (banner + §98 row added)

## Lockstep
- §22 §99 v3.9.3 → v3.9.4
- §22 §98 row v3.8.9 added at top
- No contract / DDL / AC change

## Verification
- `check-lockstep.cjs`: 87/87 pass · 0 findings ✅
- Cross-link gate via dashboard regen: 2941 links checked, 0 broken ✅
- Dashboard re-generated cleanly (Files scanned 861, Folders 87)

## Backlog impact
Phase 135 closed. No autonomous follow-ups identified — remaining queue is fully user-blocked.
