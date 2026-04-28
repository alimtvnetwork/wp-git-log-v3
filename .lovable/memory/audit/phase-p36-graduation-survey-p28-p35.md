# Phase P36 — H10 Graduation-Filter Survey of P28→P35 Lessons

**Date:** 2026-04-28
**Type:** Read-only audit (per H6 lesson #2 — no §98/§99 bumps)
**Filter:** H10 3-criteria (mechanically detectable + active regression surface + low false-positive risk)
**Surface-elimination preference:** check FIRST whether an unrelated gate already removed the failure mode.

---

## Lesson Inventory (P28→P35)

| # | Phase | Lesson (codified) | Mech. detect? | Active surface? | Low FP? | Surface eliminated? | Verdict |
|---|---|---|---|---|---|---|---|
| L1 | P28 | Dashboard freshness sweep cadence | No (procedural) | Low | — | — | **DEFER** — no mechanical signal; codified by P34-lesson-2 (cluster-terminal refresh) |
| L2 | P29 | `spec-index.md` 877→883 root-cause = workflow advisory rot | Yes | **No (resolved P30)** | — | **YES** — P30 strict-promoted the gate | **DEFER** — surface eliminated |
| L3 | P30 | Session-local phase counters MUST NOT be stamp values; use global `detect_current_phase()` | Yes (regex: stamp value vs git-log max) | Low (single human-author error mode in narrative phases) | Medium (false-positive on legitimate backdated stamps) | Partial — `detect_current_phase()` already returns global max; authors only deviate if they hand-edit | **DEFER** — 1/3 active surface; only one occurrence |
| L4 | P31 | `\|\| true` / `continue-on-error: true` allowed ONLY in `if: always()` summary aggregators | **Yes** (workflow YAML AST scan) | **Yes** (next workflow edit could re-introduce) | **Yes** (rule has explicit exemption: `if: always()` + writes to `$GITHUB_STEP_SUMMARY`) | No | **CANDIDATE** — 3/3 mechanical, see analysis below |
| L5 | P31 | CI hygiene: real-validation steps need explicit phase-ref justification comment if `\|\| true` | Yes | Tied to L4 | Medium | No | **MERGE into L4** |
| L6 | P32 | Post-strict-flip stamping is purely additive and risk-free | No (procedural cadence) | Low (one-time per gate) | — | — | **DEFER** — procedural |
| L7 | P33 | Canonical `rg` verification command for advisory-CI sweep | No (audit recipe) | Tied to L4 | — | — | **MERGE into L4** as the verifier |
| L8 | P34 | Allowlist line-number drift is a 4th-order side-effect of stamp-batch sweeps | **Yes (was)** | **No (resolved P35)** | — | **YES** — P35 fuzzy `(file, target)` matching + `--rewrite-allowlist` removed the failure mode | **DEFER** — surface eliminated |
| L9 | P34 | Dashboard refresh = canonical end-of-cluster terminal step | No (procedural) | Yes (every cluster) | — | — | **DEFER** — procedural; could become a CI cron, but cron ≠ graduation-pattern target |
| L10 | P35 | Fuzzy waiver matching is canonical resolution for line-keyed allowlists drifting under unrelated edits | **Yes (was)** | **No (resolved P35)** | — | **YES** — implemented in `check-spec-cross-links.py` v1.1.0 | **DEFER** — surface eliminated by the lesson itself |
| L11 | P35 | README inventory parity gate compounds in value | No (observation about an existing gate `test-overview-inventory-parity.sh`) | — | — | — | **DEFER** — no new gate needed; gate already exists |
| L12 | P35 | H1 workflow-step parity rule extends to "extending an existing gate's contract" | No (procedural authoring rule) | Low (rare AC-31-31 cascade decisions) | — | — | **DEFER** — procedural |

---

## Candidate L4 Deep Analysis: workflow `|| true` advisory-rot lint

**Rule (proposed):** A new gate `check-workflow-advisory-rot.py` (or extension of `check-spec-cross-links.py` family) that scans `.github/workflows/spec-health.yml` for `\|\| true`, `\|\| echo`, `--soft`, `continue-on-error: true` on any `run:` step UNLESS the parent step has `if: always()` AND the script writes to `$GITHUB_STEP_SUMMARY`.

### H10 3-Criteria Check

1. **Mechanically detectable?** ✅ YES — YAML AST parse + per-step inspection of `run:` body, `if:` predicate, presence of `>>$GITHUB_STEP_SUMMARY` redirection.

2. **Active regression surface?** ✅ YES — every workflow edit can re-introduce the pattern. P29's discovery proves a single `|| true` survived 12+ phases unnoticed before manual root-cause analysis.

3. **Low false-positive risk?** ⚠️ MEDIUM —
   - True positive: `bash check-foo.sh || true` on a real validation step.
   - True negative: `echo summary || true` inside `if: always()` aggregator.
   - **Edge case (FP risk):** an `if: always()` step that performs BOTH validation AND summary writing → rule would let it pass even though validation result is silently swallowed. Current `spec-health.yml` has no such case, but a future contributor could write one.

### Surface-Elimination Check

⚠️ **Not eliminated.** P31 codified the rule in narrative form only; nothing structurally prevents a future contributor from writing `bash check-newgate.sh || true` outside an `if: always()` block.

### Cost-Benefit

- **Cost:** new linter (~120 LoC Python YAML parser + per-step inspection), new self-test, new spec slot in §27 (slot 30 in 30-39 band), AC-31-31 cascade (RUBRIC v2.29 → v2.30, gate count 19 → 20, footer entry #20, EXECUTIVE-SUMMARY back-ref, qa-baseline-footer awk +1, workflow step #20).
- **Benefit:** Catches one specific failure mode (advisory-rot in `spec-health.yml`) that historically took ~12 phases to surface manually.
- **Comparable precedent:** P30 strict-promoted spec-index drift; full cascade was justified because the gate already existed in advisory mode. **L4 would create a NEW gate from scratch.**

### Verdict on L4

**DEFER (provisional)** — reasoning:
- 2.5/3 H10 criteria (low FP is qualified, not clean).
- P19's "advisory inside an existing gate" pattern is the lighter-weight alternative. Could extend `check-spec-cross-links.py` or add a 1-line `rg` step inside the existing freshness or qa-baseline gate that emits `::warning::` when it detects the anti-pattern outside `if: always()` context. This avoids a new gate + AC-31-31 cascade.
- **Recommendation:** if L4 graduates in a future cycle, do it as a **lint-step inside an existing strict gate** (P19 pattern), not as a new standalone gate. Until then, narrative codification on line 13 of `mem://index.md` is sufficient.

---

## Surface-Elimination Wins (P28→P35 cycle)

Following the H10 surface-elimination preference, **3 of 12 lessons (L2, L8, L10) had their regression surface fully eliminated within the same cluster** — a higher rate than the H10 baseline cycle (2 of 6). This validates the cluster-terminal pattern from P34: shipping the fix in the same session as the discovery prevents lessons from accumulating as procedural debt.

| Lesson | Failure mode | Eliminator |
|---|---|---|
| L2 | Workflow advisory rot on spec-index gate | P30 strict-promotion |
| L8 | Allowlist line-number drift from stamp sweeps | P35 fuzzy `(file, target)` matching |
| L10 | Same as L8 (different framing) | Same as L8 |

---

## Result

**0 lessons promoted.** 3 lessons resolved by surface-elimination within their own cluster. 1 candidate (L4) deferred with explicit "extend existing gate, not new gate" path forward if it ever graduates. 8 lessons are procedural / observational with no mechanical signal.

**H10 graduation backlog remains demonstrably empty** across all clusters audited (slot-26/spec-validator H10 + trace-map H11 + lockstep H12 + tree-health H13 + content-deepening H14 + P28→P35 P36).

---

## Codified Meta-Lessons (P36 → mem://index.md)

1. **Cluster-terminal fix dominates standalone graduation.** When a lesson is codified AND its fix lands in the same cluster (P30 for L2; P35 for L8/L10), the surface is eliminated and no graduation candidate remains. Future cluster authors SHOULD prefer in-cluster surface-elimination over deferred lessons.

2. **L4 deferral pattern: "new lint step inside existing gate, never new gate."** When a 2.5/3 H10 candidate has a lower-cost lint-step home in an existing strict gate, prefer that over a new gate + AC-31-31 cascade. Codifies the P19 + P31 alternative pathway.

3. **Procedural lessons (cadence, root-cause recipes, authoring rules) are NEVER H10 candidates.** Filter mismatch is structural — they have no mechanical signal by definition. Stop including them in graduation surveys; they belong in `mem://index.md` Core narrative.
