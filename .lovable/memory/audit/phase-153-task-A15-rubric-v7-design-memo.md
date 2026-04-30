---
phase: 153
task: A15
date: 2026-04-30
status: DESIGN-MEMO (no spec edits, no script edits)
supersedes: none (forward-looking)
---

# Phase 153 Task A15 — Rubric v7 Design Memo

> **Purpose.** Specify the next-generation AI-implementability rubric to dissolve two structural ceilings exposed by the v6 baseline (Phase 152) and the A11–A14 self-lift series (Phase 153). All affected modules are **contract-closed**; the gap is rubric-side, not spec-side.

---

## 1. Problem statement

### 1.1 The 75-floor audit-corpus class

Four modules sit at exactly **75/100 GOOD** with near-identical dimensional breakdowns despite shipping AC-AI-09/10/11-class module-kind pins (Lesson #29):

| Module | total | D1 | D2 | D3 | D4 | D5 | kind |
|---|---|---|---|---|---|---|---|
| `spec/03-error-manage` | 75 | 18 | 14 | 15 | 16 | 12 | error-catalog index |
| `spec/12-cicd-pipeline-workflows` | 75 | 18 | 14 | 15 | 16 | 12 | workflow registry |
| `spec/17-consolidated-guidelines` | 75 | 18 | 14 | 15 | 16 | 12 | audit/post-mortem corpus |
| `spec/25-app-issues` | 75 | 18 | 14 | 16 | 12 | 15 | post-mortem tracker |

The clustering — D2=14, D3=15-16, D4=12-16, D5=12-15 — is **structural**, not coincidental. These modules' normative surface is *describing other modules* (error catalogs, workflow lists, audit findings, post-mortems). The v6 rubric scores D2 (AC Coverage) and D3 (Edge/Error Specificity) against the **content being described** rather than the **contract for describing it**, producing systematically depressed scores.

### 1.2 The cross-ref ceiling class (89-cap)

Lesson #36 ("Cross-module cross-references MUST link, never restate") forces modules with heavy cross-module dependencies to delegate via links. The v6 rubric's D5 (Cross-Module Coherence) penalizes "missing inline definitions" without honoring AC-SD-24-class harness pins. Result:

| Module | total | D5 | reason |
|---|---|---|---|
| `spec/05-split-db-architecture` | 89 | 15 | AC-SD-24 pin to spec/02 — not honored |
| `spec/27-spec-toolchain` | 83 | 15 | Slot delegation to per-script §97s — not honored |

Both are 1-2 dim-points from EXCELLENT; the cap is rubric-bound (Lesson #29 generalised at A14).

### 1.3 What's NOT broken (out of scope for v7)

- **D1 (Contract Surface)**: Stable at 18-19 across all bands. v6 rubric correct.
- **D4 (Operational Edge)**: Honest signal — the few low scores (e.g. spec/25=12) reflect genuine edge-case gaps in described content, not rubric error.
- **Tier-1 walker** (Lesson #16 fix at A6): correctly bundles `{00,97,98,99}-*.md` first. Keep as-is in v7.
- **Walker bundling cap** (90 KB): correct trade-off; raising it would increase LLM cost without adding signal.

---

## 2. v7 rubric design

### 2.1 Two-axis classification (NEW)

Every module declares two axes via §00 front-matter (already present for `kind:`):

```yaml
---
kind: contract | index | tracker | corpus | post-mortem
content_axis: specifies-behavior | describes-other-specs
---
```

**Axis 1 (`kind`)** — already exists; v7 makes it normative for scoring routing.
**Axis 2 (`content_axis`)** — NEW; explicitly partitions:
- **`specifies-behavior`** (default) — module's ACs constrain runtime/build behavior. v6 rubric applies unchanged.
- **`describes-other-specs`** — module's ACs constrain *how the described content is captured/cited*. v7 routes to the **meta-rubric** (§2.3).

### 2.2 Module classification table (proposed)

| Module | `kind` | `content_axis` | Current | v7 expected |
|---|---|---|---|---|
| 03-error-manage | index | describes-other-specs | 75 | 90+ |
| 05-split-db-architecture | contract | specifies-behavior | 89 | 92+ (D5 honors AC-SD-24) |
| 12-cicd-pipeline-workflows | index | describes-other-specs | 75 | 90+ |
| 13-generic-cli | contract | specifies-behavior | 91 | held |
| 15-distribution-and-runner | contract | specifies-behavior | 92 | held |
| 17-consolidated-guidelines | corpus | describes-other-specs | 75 | 90+ |
| 25-app-issues | post-mortem | describes-other-specs | 75 | 90+ |
| 27-spec-toolchain | index | specifies-behavior (delegated to slots) | 83 | 90+ (D5 honors slot-delegation pins) |

### 2.3 Meta-rubric for `describes-other-specs` modules

When `content_axis: describes-other-specs`, swap D2 and D3 with citation-quality dimensions:

| Dim | v6 (specifies-behavior) | v7 (describes-other-specs) | Max |
|---|---|---|---|
| D1 | Contract Surface | Contract Surface (unchanged) | 20 |
| **D2** | AC Coverage | **Citation Density** — % of described items with line-anchored citations to source spec | 20 |
| **D3** | Edge/Error Specificity | **Source-of-Truth Discipline** — presence of AC-AI-09/10/11-class module-kind pin + verbatim-quote rule + inventory disambiguation | 20 |
| D4 | Operational Edge (unchanged) | Operational Edge — applies to the *describing process* (e.g. "what if cited spec moves?") | 20 |
| D5 | Cross-Module Coherence (unchanged) | Cross-Module Coherence — honors AC-SD-24-class link-don't-restate pins | 20 |

**Rationale.** A post-mortem about `spec/_archive/21-git-logs-v1/` cannot be "fixed" by adding ACs about HS256 (the cited bug); it can only be assessed on whether HS256 is **correctly cited as a finding from the archive** (Lesson #29). v7's D2/D3 measure that.

### 2.4 D5 honor list (NEW)

v7 D5 must honor any AC bearing the **link-don't-restate pin pattern**, identifiable by:
- AC body contains `link-don't-restate` OR `delegates to spec/NN` OR `forbids inlining`
- AC severity `[critical]` OR `[high]`
- AC `**Verifies:**` clause cites a sibling-module path

When N such pins are present, D5 receives `min(20, 15 + N×2.5)` instead of being penalized for the absent inline content. Worked example: spec/05 with AC-SD-24 (1 pin) → D5 = 17.5 → total 91.5 → EXCELLENT.

---

## 3. Implementation plan (post-A15, when LLM gateway unblocks)

### 3.1 Phase order

1. **A16** — Add `content_axis:` front-matter to all 23 top-level modules. Pure metadata edit; lockstep §00/§98/§99 patch only. No score movement.
2. **A17** — Extend `linter-scripts/audit-ai-implementability.py` to read `content_axis` and route to one of two prompt templates (specifies-behavior / describes-other-specs). Slot 34 minor bump.
3. **A18** — Add D5-honor-list logic: pre-scan §97 for link-don't-restate ACs; pass count to LLM as scoring hint. Slot 34 minor bump.
4. **A19** — v7 rebaseline. Expected tree mean 84.3 → 88-90.
5. **A20** — Snapshot v7 baseline; supersede `spec/17-consolidated-guidelines/34-full-tree-ai-audit-v6.md` with `35-full-tree-ai-audit-v7.md`.

### 3.2 Hard dependency

**A12 LLM gateway unblock is required for A17–A19.** Until then, A16 (metadata only) is the only v7 prep work that can land. A16 is **not opened in this phase** because it's mechanical and best done in a single bulk edit when A12 is imminent (avoids 23 individual lockstep ripples).

### 3.3 Risk register

| Risk | Mitigation |
|---|---|
| `content_axis` becomes a foot-gun (authors mis-classify to chase scores) | Tie to `kind:` enum: `index|tracker|corpus|post-mortem` MUST set `describes-other-specs`. Add gate to `check-tree-health.cjs --strict`. |
| Meta-rubric prompt drifts from contract | Pin prompt template SHA in slot 34 v2.0.0; bump on any change. |
| D5 honor list double-counts AC-SD-24-class pins also bearing AC coverage | D5 is a separate dimension; no double-count risk. AC-SD-24 still counts toward D2 (AC Coverage) on its own merit. |
| LLM gateway 402 returns mid-rebaseline | A19 runs `--report-only` advisory until 23/23 modules re-scored cleanly (Lesson #20). |

---

## 4. What this memo does NOT do

- **No spec edits.** Pure design memo.
- **No script edits.** A17/A18 are future phases.
- **No score movement.** v6 baseline (84.3) stands until A19.
- **No banner bumps.** No lockstep, no version-parity, no freshness ripple.
- **No new ACs.** v7 design is meta-process; will land via §17-consolidated-guidelines new slot.

---

## 5. Lessons codified for v7

- **Lesson #29** (A11c): Module-kind pins close *contracts* but not *scores* — v7 routes via `content_axis` instead of asking authors to chase score lift.
- **Lesson #36** (P3 / spec/04 §4.3): Cross-module link-don't-restate creates D5 false-positives — v7 honor list dissolves the false-positive class.
- **Lesson #16** (A6): Tier-1 walker `{00,97,98,99}-*.md` first — keep in v7.
- **Lesson #18** (A7): Honest-baseline corrections are features, not regressions — v7 accepts that some modules currently EXCELLENT will move to GOOD when meta-rubric routes them more accurately. Do not engineer around this.
- **Lesson #20** (A9): LLM gateway 402 → defer score, don't block phase — A19 follows the same advisory-first pattern.
- **NEW Lesson #37** (A15, codified here): When ≥3 modules of the same `kind:` cluster at an identical score with identical dimensional breakdowns (e.g. 75 / D2=14 / D3=15-16), the rubric — not the spec — is the bottleneck. The fix is a meta-rubric routed by an explicit content-axis declaration in §00 front-matter, NOT additional ACs in the affected modules. **Do not author "lift" ACs in a module whose ceiling is structural** — they will not move the score and will accumulate as audit-debt (precedent: spec/05 AC-SD-24 added 0 score points; AC-AI-09/10/11 added 0 score points).

---

## 6. Closure

A15 produces this memo only. v7 is **specified, not implemented.** Implementation queued behind A12 (LLM gateway unblock).

**Gates (all GREEN, no spec edits this phase):**
- lockstep 87/87
- tree-health 168/168 strict
- version-parity 74/74
- freshness 81 stamped + 6 exempt + 0 unstamped
- folder-refs 0 stale

**Memo file:** `.lovable/memory/audit/phase-153-task-A15-rubric-v7-design-memo.md`
