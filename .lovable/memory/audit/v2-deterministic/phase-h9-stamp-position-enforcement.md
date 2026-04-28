# Phase H9 — Stamp-Position Structural Enforcement

**Date:** 2026-04-28
**Trigger:** Backlog item #6 (promote H8 stamp-position rule to lint check).

## Outcome
- H8 precedent ("stamps MUST live INSIDE a tracked-heading body, not adjacent") promoted from one-time sweep + memory rule to a structural lint inside the existing slot-26 gate.
- Slot 26 v1.2.0 → v1.3.0. **No new gate**, no AC-31-31 cascade, no rubric bump. Same CI step, tighter contract.

## Implementation
`check-99-summary-freshness.py`:
- New `find_misplaced_stamps()` returns `[(line_no, snippet)]` for each `<!-- verified-phase: NNN -->` placed OUTSIDE any tracked-heading body AND followed within ≤3 non-empty lines by a tracked heading.
- New `--strict-position` flag: turns the finding into exit 1.
- Default mode emits advisory warning (`⚠️ N stamp(s) placed immediately BEFORE a tracked heading rather than under it (advisory)`); exit code unchanged.
- Strict mode emits same finding with `❌` and `(strict-position)` label; exit 1 unless `--report-only` also set.

## CI wiring
`.github/workflows/spec-health.yml` step renamed `§99 Summary freshness gate (Phase H1 / H8 / H9)` and now passes `--strict-position`. Wired strict immediately because real tree had **0 misplaced findings** post-H8 — locks the H8 gain with no migration window.

## Adjacency-only rule (key design decision)
Considered "any stamp outside any tracked body" → rejected because §27's own §99 legitimately documents past stamp values inside Validation History blockquotes (lines 6 and 24 reference `<!-- verified-phase: 147 -->` and `<!-- verified-phase: 146 -->` as historical narrative). A whole-file rule would emit false positives on documentation.

Adopted adjacency rule (`stamp → ≤3 non-empty lines → tracked heading`) which catches only the actual H8 failure mode (`stamp\n\n## Summary`) without flagging blockquoted documentation references. Codified as AC-26-10's negative case + T14 self-test.

## Self-test
12 → 20 (H8) → **27 assertions** (H9):
- T12: misplaced stamp in default mode emits warning, exits 0, mode label says `advisory`.
- T13: same input with `--strict-position` exits 1, mode label says `strict-position`.
- T14: stamp inside blockquote with no nearby tracked heading does NOT trigger misplaced warning even under `--strict-position` (negative case — false-positive guard).

## Verification
- Freshness `--strict-position` ✅ (87 scanned, 81 stamped, 6 exempt, 0 unstamped, 0 stale, 0 misplaced)
- Self-test 27/27 ✅
- Lockstep 87/87 / 0 findings ✅
- Tree-health 168/168 strict ✅
- §27-inventory 6/6 ✅

## Lessons codified
1. **One-time sweep + precedent → structural lint pattern**: the H8→H9 pair is the template — when a one-time fix uncovers a pattern, the lesson should graduate to enforcement within the next session if it can be detected mechanically. Equivalent precedent: H6 audit → H7 standing self-test gate.
2. **Negative-case ACs are first-class**: AC-26-10 deliberately includes the blockquote false-positive guard as a contractual requirement, not just a self-test detail. Future contributors who narrow the rule must preserve the negative case.
3. **Adjacency rules > whole-file rules** when documentation may legitimately reference the linted token. A `≤N non-empty lines` window is the right shape for "did the author intend this for the next heading?" detection.

## Bumps
- `linter-scripts/check-99-summary-freshness.py`: +`find_misplaced_stamps()`, +`--strict-position` flag, +misplaced-finding output block
- `linter-scripts/test/test-check-99-summary-freshness.sh`: +T12, +T13, +T14 (7 new sub-asserts)
- `.github/workflows/spec-health.yml`: step renamed + `--strict-position` arg
- `spec/27-spec-toolchain/26-check-99-summary-freshness.md`: +AC-26-10, +1.3.0 changelog entry
- §98 v2.46.1 → v2.46.2 (patch)
- §99 v2.43.1 → v2.43.2
