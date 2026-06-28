**2026-04-28**

# 02 — Stale-baseline CI guard (H10 graduation candidate)

## Task context

Phase 18-resolution (memo `phase-18r-stale-baseline-resolution.md`) traced
"+2 untraced ACs" to H5/H7 writing `trace-map-baseline.json` against a
not-just-regenerated `trace-map.json`. This is a recurring failure mode
(observed twice in 2 baseline bumps).

## Specific question

Should we add a CI gate that rejects `trace-map-baseline.json` writes
where the companion `trace-map.json` is not freshly regenerated against
the same tree state? Concretely: a guard that diffs
`set(baseline.summary.drift_count_implied)` vs
`set(generate_trace_map(HEAD).drift)` and exits non-zero on mismatch.

## H10 filter score

| Criterion | Score | Rationale |
|-----------|-------|-----------|
| Mechanically detectable | ✓ | Run generator → compare drift count to baseline-implied drift count |
| Active regression surface | ✓ | Bit us at H5 (off by 5) and H7 (off by 2) |
| Low false-positive risk | ✓ | Rule is "snapshots must agree" — no legitimate exception |

**3/3 → strong graduation candidate** (compare to Phase 17 which scored 1/3
and was deferred).

## Inferred decision (during No-Questions Mode)

**Defer to user review.** Rationale:
- H10 score is high but the failure mode has only manifested in
  *manual* baseline edits / two-step workflows. The
  `--update-baseline` flag already enforces atomicity.
- A "guard" gate may be redundant if all baseline writes go through
  `--update-baseline`. The simpler fix is **documentation + linter
  warning** ("did you use --update-baseline?") rather than a 16th CI
  gate.
- Adding a CI gate triggers AC-31-31 cascade (RUBRIC bump, footer
  count, EXECUTIVE-SUMMARY back-ref, qa-baseline-footer awk). High
  ceremony for a failure mode that's already structurally fixable
  via flag discipline.

Continuing without adding the gate. User can override during ambiguity
review.

## Impact

- No new CI gate added under No-Questions Mode.
- Phase 18-resolution lesson codified in memory (atomicity rule + drift-set
  diff verification rule) — relies on author discipline + memo learning,
  not enforcement.

## Suggested clarification

Choose one:
1. **Add the gate** (Phase H15 candidate) — full AC-31-31 cascade,
   slot 29 validator, ~90 min work.
2. **Document-only** (current default) — keep memo lesson; rely on
   `--update-baseline` flag discipline.
3. **Linter warning** (middle ground) — extend `check-trace-map-regression.py`
   to detect baseline staleness during normal runs and emit advisory
   warning (no exit code change).

## Resolution (2026-04-29)

✅ **CLOSED — option already shipped.** User selected "Linter advisory
warning (middle ground)" on review. Investigation shows the warning was
already implemented in `check-trace-map-regression.py` lines 144-162
(unattributed phase, predates Phase 153). Behaviour verified live today:

```
::warning::stale-baseline drift: ac_total +46, code_total +4 since baseline
was written. Re-run `check-trace-map-regression.py --update-baseline`
after a clean `generate-trace-map.py` to re-anchor.
```

No code changes required. **Side effect of verification**: discovered the
real H1 baseline was stale (last rebaseline = `ac_total:1304`, current =
`1400` after Phase 153's +96 ACs from Verifies sweep, AppLink/Pipeline/
Boolean ACs, A6 spec/05 lift, etc.). Rebaselined atomically:

```
{ac_total:1400, ac_traced:96, ac_drifted:1304, code_total:62, code_orphan:33}
```

Gate now GREEN. **Lesson #35 (operational)**: when verifying that an
advisory warning is wired, run the linter live — if the warning fires on a
real regression, that's a signal to inspect the baseline, not just confirm
the warning string.

## Resolution note (legacy)

✅ **RESOLVED** — no further user action required.


---
## Status

**Status:** Resolved
**Resolved-in-phase:** pre-Phase-153 (legacy archival — exact phase not recorded at closure time; this footer added by hygiene-round-3 to normalize the closure protocol per README convention)
**Resolved-on:** unknown (legacy)
**Resolution:** see body — original "RESOLVED" / "SELF-RESOLVED" note retained verbatim above this footer.
**Do not re-surface:** yes
