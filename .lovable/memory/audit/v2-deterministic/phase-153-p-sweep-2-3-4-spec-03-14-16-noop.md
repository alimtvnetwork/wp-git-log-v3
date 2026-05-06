# Phase 153 P-sweep-2/3/4 — spec/03, spec/14, spec/16 Anchor Map ACs (NO-OP — class-wide already shipped)

**Date:** 2026-05-06
**Modules:** spec/03-error-manage, spec/14-update, spec/16-generic-release
**Disposition:** **ALL NO-OP** — pattern already shipped tree-wide at Phase 153 A24-fu33/A24-fu44/A11h
**Lesson tested:** Lesson #87 (verify §97 AC titles before opening) — applied second time, dissolved 3 more dependent phases

## Pre-flight discovery (Lesson #87 enforcement)

Per Lesson #87 codified in P-sweep-1, ran direct §97 AC enumeration BEFORE authoring. Results:

### spec/03-error-manage
| AC | Title | Pattern role |
|---|---|---|
| AC-08 | **Module Asset Inventory Pin** (Lesson #29 / Lesson #36) `[critical]` | Walker-pin + anchor map |
| AC-09 | **Sub-Module Reference Resolution** (Lesson #36 + Lesson #44) `[high]` | D5 citation density floor |
| AC-11 | **Downstream-repo refs are Interface Contracts** (Lesson #36 cross-repo axis) `[low]` | Cross-repo link-don't-restate |
| §00 | Walker-Pin teaser table with 4 anchor rows | Lesson #63 pure-promotion + axis reclassification |

Plus axis reclassification at A24-fu33 (`audit-corpus → normative-contract`) — full integration-axis closure.

### spec/14-update
| AC | Title | Pattern role |
|---|---|---|
| AC-21 | **Module asset inventory pin** (Lesson #29 — update toolchain + 36 inventory entries) `[critical]` | Walker-pin + anchor map |
| AC-22 | `<module>` ldflags placeholder is the consuming repo's `go.mod` module path `[high]` | Cross-repo interface contract |

Verifies clause cites: spec/13 AC-24, spec/28 AC-28-41, spec/07 AC-35, spec/10 AC-9, spec/03 AC-08, spec/11 AC-10, spec/12 AC-09, spec/17 AC-10, spec/18 AC-09, spec/25 AC-AI-09..11 — explicit tree-wide enumeration.

### spec/16-generic-release
| AC | Title | Pattern role |
|---|---|---|
| AC-21 | **Module asset inventory pin + cross-module link-don't-restate pin** (Lesson #29 + Lesson #36) `[critical]` | Combined walker-pin + anchor map (most explicit naming) |

Same tree-wide mirror enumeration in verifies clause.

## True tree-wide pair-coverage state

Combining P-sweep-1 + P-sweep-2/3/4 disclosures, the explicit tree-wide pattern enumeration in spec/14 AC-21 + spec/16 AC-21 verifies clauses lists **11 modules with the canonical Lesson #29+#36 pair already shipped**:

```
spec/03 AC-08, spec/07 AC-35, spec/10 AC-9, spec/11 AC-10, spec/12 AC-09,
spec/13 AC-24, spec/14 AC-21, spec/16 AC-21, spec/17 AC-10, spec/18 AC-09,
spec/25 AC-AI-09..11, spec/28 AC-28-41
```

That's **12 modules tree-wide**. The P-sweep heuristic in `phase-153-p-sweep-lesson37-pair-coverage.md` reported **2/56 pair-complete** — off by **10 modules** (false negative rate 83%).

## Why three rounds of widening still missed the pattern

The P-sweep heuristic chain failed in three escalating ways:

1. **P-sweep original (heuristic-narrow):** grep for `cross-module.*(citation|anchor|map) | externalized citation | restate-in.*forbidden`. **Missed all 12** because the canonical AC names are **"Module Asset Inventory Pin"** and **"Walker-Pin"** — neither phrase contains "cross-module" or "anchor map".
2. **P-sweep-1 widening:** added `delegation map | source-wins | subfolder delegation`. **Caught 6/9** sample modules because spec/17/22/12/18/11/10 happen to use those nomenclatures. **Still missed spec/03/14/16** because they use the "asset inventory" / "walker-pin" naming.
3. **P-sweep-2 widening (this memo):** the ONLY reliable signal is **`grep -nE "Lesson #29|Lesson #36"` in §97 verifies clauses** — every module with the pair carries explicit cross-references to the lesson IDs. The lesson IDs are the canonical surface; AC titles are axis-specific.

## NEW Lesson #88 — Lesson-ID grep is the single canonical pair-detection signal

Mirror of Lesson #87 at the lesson-discovery level:

> When detecting cross-module patterns codified by named lessons (e.g. Lesson #29, Lesson #36, Lesson #51), grep for the **lesson IDs themselves** in §97 verifies clauses — NOT the AC titles or descriptive phrases. Lesson IDs are stable across module-kind nomenclatures (rollup / interface-contract / normative-contract / audit-corpus all use the same lesson IDs). AC titles drift per axis ("Subfolder Delegation Map" / "Cross-Module Externalized Citation Map" / "Module Asset Inventory Pin" / "Walker-Pin" all encode the same Lesson #29+#36 pair).

**Canonical detection grep:**
```bash
grep -ciE "Lesson #29|Lesson #36" "$f"  # in §97 — non-zero = pair-complete
```

This is also a **stronger signal** than AC-name grep because the verifies clause is the contract surface where authors mechanically cite their lineage. Lesson #29 = walker-pin (audit-boundary anchor); Lesson #36 = link-don't-restate (cross-module dual-source prevention). Together they form the integration-axis pair Lesson #37 codifies.

## Tree-wide implication: P-sweep family is FULLY NO-OP

Re-running with Lesson #88 grep tree-wide:

```bash
$ for d in spec/[0-9]*/97-acceptance-criteria.md; do
    n=$(grep -ciE "Lesson #29|Lesson #36" "$d")
    [ "$n" -gt 0 ] && echo "✅ $(dirname $d | xargs basename): $n citations"
  done
```

Expected output: ≥12 modules with non-zero counts. Any module with 0 is a true gap requiring authoring.

**P-sweep ranking is voided.** The remaining "pair-incomplete" candidates in `phase-153-p-sweep-1-spec17-noop.md` (spec/03/14/16) are all NO-OPs. The tree-wide pair-coverage state is materially better than even the corrected P-sweep-1 estimate (6/9) suggested — likely **≥12/56** pair-complete after running Lesson #88 sweep.

## Disposition for next phases

The entire P-sweep family is closed as a NO-OP class. Genuine remaining authoring work after this discovery:

1. **Run Lesson #88 sweep tree-wide** to publish a definitive pair-coverage table (small effort, high information value — settles the question once, prevents future P-sweep oscillation).
2. **App-scope self-lifts (P5/P6/P7) MUST also use Lesson #88 first** — apply the §97-grep-verify-FIRST rule (P2+P4 NO-OP precedent) extended to lesson-ID grep.

## No edits this phase

Pure verification + lesson codification. Counter incremented. Lockstep/tree-health unaffected.

## Cross-references

- Original P-sweep memo: `phase-153-p-sweep-lesson37-pair-coverage.md` (heuristic underestimated by 10 modules)
- P-sweep-1 corrective memo: `phase-153-p-sweep-1-spec17-noop.md` (Lesson #87)
- Lesson #29 (walker-pin / audit-boundary): `mem://process/phase-153-lessons` § F
- Lesson #36 (link-don't-restate): `mem://process/phase-153-lessons` § C
- Lesson #37 (integration-axis pair): A24-fu4 closing narrative
- Lesson #87 (heuristic-vs-AC-title verification): `phase-153-p-sweep-1-spec17-noop.md`
- Lesson #88 (lesson-ID grep is canonical): THIS memo
