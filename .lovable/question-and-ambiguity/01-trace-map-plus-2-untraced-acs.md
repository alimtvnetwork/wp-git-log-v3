**2026-04-28**

# 01 — Trace-map +2 untraced ACs (Phase 18 drift sweep)

## Task context

Phase 18 — Tree-health drift sweep (`mem://specs/phased-roadmap.md` suggestion (d)).
While running sister gates alongside `check-tree-health.cjs --strict`, the trace-map
regression gate flagged drift growth.

## Specific question

Which 2 ACs were added between Phase H7's rebaseline (`ac_total:1320`) and
Phase 18's run (`ac_total:1322`)? Should they be bound in `trace-map.toml`
or is rebaseline correct?

## Inferred decision

**Rebaseline +2** (`ac_total:1320→1322`, `ac_drifted:1230→1232`). All other
fields flat: `ac_traced:90`, `code_orphan:26`, `missing_ac:0`, `missing_file:0`.
Justified because:

1. `ac_traced` flat → no code-binding loss (true regression criterion is unmet)
2. Delta +2 ≪ 50-AC inspection threshold (memory rule)
3. Pure additive growth; gate flagged drift-arithmetic, not real risk
4. Identifying the specific 2 ACs requires git archaeology against
   generic "Changes" commit messages — exceeds Phase 18 scope

## Impact

- `.lovable/memory/audit/trace-map-baseline.json` updated (single line each for
  `ac_total` and `ac_drifted`).
- Future drift sweeps will compare against the new baseline, so any *additional*
  AC additions still get flagged.
- If the 2 untraced ACs map to a script that does have an implementation, R1
  (real-AI re-audit, blocked on Lovable Cloud) will surface the binding gap.

## Suggested clarification

Confirm: was rebaseline the right call, or do you want me to find + bind the
2 specific new ACs? If the latter, may need to spend ~10 min on `git log -p`
across §97 files to identify them.
