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
fields flat. Justified because `ac_traced` flat → no code-binding loss.

## Resolution (task 2 — root-cause investigation)

**Root cause: STALE BASELINE SNAPSHOT, not new ACs.**

Reproducible with `git show <H7-commit>:.lovable/memory/audit/trace-map.json`:

| Snapshot | `trace-map.json` (live) | `trace-map-baseline.json` (recorded) |
|----------|-------------------------|--------------------------------------|
| Pre-H7 commit (claimed 1315) | 1320 | 1315 |
| H7 commit (claimed 1320) | 1322 | 1320 |
| Phase 18 (today) | 1322 | 1232 → rebaselined to 1232 ✅ |

Drift set diff between H7 commit and current HEAD: **0 added, 0 removed** — the
1232 drift entries are identical. The "+2 untraced ACs" was an artifact of the
H7 phase author writing `trace-map-baseline.json` against a stale
`trace-map.json` (gap of 2 between snapshot regen and baseline write). H5 had
the same problem with a gap of 5.

## Impact

- Rebaseline (+2 ac_total, +2 ac_drifted) was the correct fix.
- No real AC additions occurred between H7 and Phase 18.
- No `[[trace]]` bindings missing or required.

## Lesson codified (added to memory Core)

**Phase 18-resolution lesson**: when shipping a rebaseline phase, ALWAYS run
`generate-trace-map.py` immediately before writing
`trace-map-baseline.json` (no intervening edits) — otherwise the baseline
encodes stale numbers and the next sweep falsely reports growth. Better:
`check-trace-map-regression.py --update-baseline` runs them atomically and
should be preferred over manual JSON edits.

## Status

✅ **RESOLVED** — no user action required.


---
## Status

**Status:** Resolved
**Resolved-in-phase:** pre-Phase-153 (legacy archival — exact phase not recorded at closure time; this footer added by hygiene-round-3 to normalize the closure protocol per README convention)
**Resolved-on:** unknown (legacy)
**Resolution:** see body — original "RESOLVED" / "SELF-RESOLVED" note retained verbatim above this footer.
**Do not re-surface:** yes
