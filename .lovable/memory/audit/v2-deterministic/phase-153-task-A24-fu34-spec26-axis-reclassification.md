# Phase 153 Task A24-fu34 — Lesson #69 tree-wide audit + spec/26 reclassification

**Date:** 2026-05-01 · **Phase:** 153 · **Task:** A24-fu34 · **Lesson:** #69 (forward audit)

## Survey: 3 audit-corpus modules tree-wide

| Module | §97 ACs | Rationale | Verdict |
|---|---|---|---|
| **spec/10-research** | AC-01..08 = routing-meta only (file-existence/lockstep/cross-link) | "Routing-only; child specs document explorations of other systems" | **CORRECT — retain** (no GWT defining contracts; pure router) |
| **spec/25-app-issues** | AC-01..08 routing-meta + AC-AI-09..11 module-kind pins | "Routing parent of kind:tracker post-mortems (Lesson #29)" | **CORRECT — retain** (AC-AI-* are meta-pins from A11c, not contracts; per Lesson #29 strict) |
| **spec/26-gitlogs-diagrams** | AC-DG-01..22+ GWT-style normative diagram-correctness ACs | "Diagrams describing the 22-git-logs-v2 architecture" | **MISCLASSIFIED — fix** |

## Why spec/26 is normative-contract, not audit-corpus

Per Lesson #69 strict definition + fu33 spec/03 reasoning extended to artifact-vs-citation axis:

| Test | spec/26 reality |
|---|---|
| Primary §97 content | 22+ GWT ACs: AC-DG-01 (ER tables), AC-DG-02 (cardinality), AC-DG-03 (auth-flow order), AC-DG-04 (RolePermission union), AC-DG-05 (header type+intent), AC-DG-06 (emoji-free lexer), AC-DG-07 (no JWT/RS256/JWKS), AC-DG-08 (8 endpoint mindmap), AC-DG-12 (deterministic render), AC-23 (SVG render protocol), AC-22 (asset inventory pin), etc. |
| Implementer obligation | Diagram authors MUST satisfy when modifying `.mmd` sources |
| Artifact ownership | spec/26 OWNS the `.mmd` + `.svg` files as artifacts; spec/22 owns the architecture; **depicting ≠ describing** |
| Cap & multiplier impact | Pre-fix: cap=95, d2×0.5 + d3×0.5 with d2=20+d3=17 = ~18.5 weighted lost vs normative-contract multipliers |

## Re-score

| Metric | Pre-fu34 (v9) | Post-fu34 | Δ |
|---|---|---|---|
| `total` | 88 | **91** | **+3** |
| `band` | GOOD | **EXCELLENT** | ↑ |
| `weighted_total` | 87.5 | 91.1 | +3.6 |
| `axis_cap` | 95 | 100 | +5 |
| `axis` | audit-corpus | normative-contract | — |
| d1/d2/d3/d4/d5 | 17/20/17/19/15 (cache pre) | 18/20/17/19/15 | d1 +1 |

Lift smaller than spec/03 (+12) because spec/26 was already at d2=20 max and d4=19; the audit-corpus penalty was less severe than for spec/03 (which had d2=19 + d3=15 + d4=17 + d5=14, fully penalised). Result still crosses GOOD → EXCELLENT band.

## Files changed

- `spec/26-gitlogs-diagrams/00-overview.md` — front-matter axis flip + audit block + banner v3.4.4
- `spec/26-gitlogs-diagrams/98-changelog.md` — release row 3.4.4 + banner
- `spec/26-gitlogs-diagrams/99-consistency-report.md` — banner v3.3.4 + Updated line
- `.lovable/cache/audit-ai/26-gitlogs-diagrams.json` — re-scored (91 EXCELLENT)
- `.lovable/memory/index.md` — fu34 entry
- this memo

## Validation — all 5 strict gates GREEN

| Gate | Result |
|---|---|
| `check-lockstep.cjs` | 87/87 · Findings: 0 |
| `check-tree-health.cjs --strict` | 168/168 · 56 modules full marks |
| `check-version-parity.py --strict` | 74/74 matches · 0 mismatches |
| `check-99-summary-freshness.py --strict-position` | 81 stamped + 6 exempt + 0 unstamped |
| `check-spec-folder-refs.py` | 0 stale |

## Lesson #69 status post-fu34

**Tree-wide audit complete.** 3 audit-corpus modules surveyed; 1 misclassified (spec/26, fixed); 2 retained correct (spec/10 routing-only, spec/25 post-mortem-router). **0 additional candidates surfaced** — Lesson #69 now stands as a forward-looking guard. Future contributors authoring new `audit-corpus` modules MUST verify §97 contains NO GWT-style normative ACs (only routing-meta or quoted-evidence pins per Lesson #29).

## Tree-wide impact

| Metric | Pre-fu34 (post-fu33 projection) | Post-fu34 actual cache |
|---|---|---|
| Tree mean | ~91.04 | ~91.17 (+3 from spec/26 / 23 modules) |
| EXCELLENT band count | 16 | **17** (spec/26 promoted) |
| GOOD band count | 7 | **6** |

## Forward triggers

- Lesson #69 re-application: ONLY if a new `audit-corpus` module ships AND its §97 contains GWT-style ACs (severity tags + GWT scenario + Verifies clause) — re-survey at that point. No proactive re-audit needed.
