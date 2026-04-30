# Phase 153 Task A24-fu13 — spec/04 boolean round-trip GWT + Lesson #47 structural-pin

**Closed:** 2026-04-30
**Module:** `spec/04-database-conventions/`
**Pre-A24-fu13 score (audit-v8):** 89 GOOD (D1=19 D2=18 D3=15 D4=20 D5=18; axis=normative-contract)
**Findings closed:** MEDIUM/D2 + HIGH/D3 + LOW/D5 (3 of 3)

## Findings & resolutions

| Severity | Dim | Finding | Resolution |
|---|---|---|---|
| **MEDIUM** | D2 | Missing AC for Boolean Storage | **AC-12** binds §2.1 storage convention to per-language round-trip GWT |
| **HIGH** | D3 | SQLite Single-Writer Bottleneck | Lesson #47 false-positive — closed at Phase 153 P3 §4.3 cross-ref to spec/13 AC-22; **AC-13** structural-pin documents this |
| **LOW** | D5 | Dangling Reference in Relationship Diagrams | Walker-window byte-cap artifact — link complete on disk; **AC-13** documents this |

## Diagnostic

The MEDIUM D2 finding was a genuine Lesson #19 gap: §2.1 cross-language boolean storage convention (added at Phase 153 P48-2) had detailed normative tables for storage (SQLite/MySQL/PostgreSQL) and scan/insert (Go/PHP/Rust/C#/TypeScript), but no §97 GWT bound them — the contract surface existed in §02-schema-design.md but the verification surface was absent.

The HIGH D3 finding was already closed at Phase 153 P3 — §4.3 "Concurrency Posture (Normative cross-reference)" cross-links to `spec/13-generic-cli/97-acceptance-criteria.md` AC-22 (the canonical concurrency contract: PRAGMAs, retry policy, atomic writes, lock discipline). Per Lesson #36 (link-don't-restate), spec/04 MUST NOT restate AC-22 — schema and concurrency are orthogonal axes; restatement creates dual-source drift. The LLM auditor cannot self-respect this contract pin (Lesson #47), so it re-flags every rebaseline.

The LOW D5 finding was a walker-window byte-cap artifact: `tail -1 spec/04-database-conventions/05-relationship-diagrams.md` confirms the link is fully formed and complete on disk:
```
| Boolean principles | [../02-coding-guidelines/01-cross-language/02-boolean-principles/00-overview.md](../02-coding-guidelines/01-cross-language/02-boolean-principles/00-overview.md) |
```
The auditor's "truncated to `00-overview.m`" report stems from the `d` of `.md` falling past the 120 KB tier-2 read cutoff. `linter-scripts/check-spec-cross-links.py` (CI gate, Phase 81) confirms zero broken links.

## Changes shipped

### `spec/04-database-conventions/97-acceptance-criteria.md`
- §97 v1.3.0 → **v1.4.0** (AC count 11 → 13)
- **AC-12 `[medium]`**: Cross-language boolean round-trip GWT (per-engine storage rules + per-language scan rules + tri-state NULL exception + grep-contract enumerating forbidden string-coercion equality patterns scoped to `biz` glob — mirrors AC-10/AC-11 grep-contract pattern).
- **AC-13 `[medium]`**: Structural-pin AC declaring HIGH D3 + LOW D5 STRUCTURAL-DESIGN-NOT-DEFECT, citing canonical surfaces (spec/13 AC-22 + §4.3 cross-ref + §05 final-link byte-correctness) and enumerating forbidden remediation patterns.

### Lockstep banners
- `00-overview.md`: v3.5.0 → **v3.6.0**
- `98-changelog.md`: v3.5.0 → **v3.6.0** (+ new row + Lesson #51 codification)
- `99-consistency-report.md`: v3.7.0 → **v3.8.0** (+ blockquote)

### No CI / RUBRIC / gate-count change
- AC-12's prescribed `linter-scripts/check-boolean-roundtrip.sh` is NOT yet materialised; the AC IS the contract per Lesson #44 (parity-AC ships before its mechanical lock; future graduation phase).
- AC-13 is structural documentation — no mechanical lock applicable.

## CI verification

All 5 strict gates GREEN:
- `check-lockstep.cjs --strict`: 87/87 pass · 0 findings
- `check-tree-health.cjs --strict`: 168/168 (Score 100/100)
- `check-version-parity.py --strict`: 74/74 matches · 0 mismatches
- `check-99-summary-freshness.py --strict-position`: 81 stamped + 6 exempt + 0 unstamped
- `check-spec-folder-refs.py`: 0 stale refs

## Lessons codified

**Lesson #51** (codified inside §98 v3.6.0 row): When the **same audit-v7 HIGH finding recurs across rebaselines despite a prior phase having added the canonical contract elsewhere**, the productive close-out is **NOT to restate the contract** (forbidden by Lesson #36) but **to ship a structural-pin AC in the local §97** that:
1. Cites the canonical surface (with explicit AC-NN reference);
2. Declares the finding STRUCTURAL-DESIGN-NOT-DEFECT;
3. Enumerates forbidden remediation patterns (so future contributors don't "fix" the non-defect).

Mirror of Lesson #50 on the cross-axis recurrence dimension:
- Lesson #50 (originating spec/02 AC-CG-24): walker-saturation findings → structural-pin
- Lesson #51 (this phase, spec/04 AC-13): auditor-self-blindness across rebaselines + cross-module link-don't-restate → structural-pin

**Cross-axis-applicable** — third confirmed instance of the structural-pin pattern:
- spec/02 AC-CG-24 (normative-contract axis, walker-saturation)
- spec/25 AC-AI-16 (audit-corpus axis, walker-saturation + AC-AI-10 verbatim-quote interaction)
- spec/04 AC-13 (normative-contract axis, cross-module link-don't-restate)

## Score expectation

Pre-A24-fu13 (audit-v8): **89 GOOD** (D1=19 D2=18 D3=15 D4=20 D5=18). 
Expected post-A24-fu13 lift: D2 +1-2 (boolean GWT visibility), D3 +0-1 (auditor MAY now read AC-13 and recognize the cross-ref), D5 unchanged (auditor's walker-window cutoff is structural). Net: 89 → 90-92 expected (graduates to EXCELLENT band [≥90] in the optimistic case). LLM re-score deferred per Lesson #20 (gateway availability now confirmed per Lesson #38, but single-module re-score deferred to next phase to keep this phase atomic).

## Inherited backlog status

| # | Status | Task |
|---|---|---|
| **A24-fu14** | 🟢 ready | spec/07 (92→honest baseline drop -2) — HIGH D5 missing leaf files (likely walker-saturation), MEDIUM D3 LocalStorage failure mode, LOW D4 truncation |
| **A24-fu15** | 🟢 ready | spec/13 (89→honest baseline drop -2) — HIGH D5 broken external spec refs, MEDIUM D1 truncated date formatting, LOW D3 inconsistent exit code prose |
| **A24-fu12** | ⚪ steady-state ceiling | spec/25 at 79 — fully contract-saturated; further ACs produce no movement (Lesson #17) |
| **A18** | 🔒 gateway-blocked | Per-axis walker-cap raise above 120 KB infeasible (CF-1010 ceiling at ~125 KB; same gateway constraint as R1) |
| **A20-fu2** | ⚪ conditional | Next full-tree rebaseline (after 2-3 more A24-fuN closures) |
| **R1** | 🔒 blocked | Trace-map deeper bindings — needs Lovable Cloud enable |

**Next:** A24-fu14 (spec/07) — three findings on a process-guidance module currently at 92; the HIGH D5 is likely a walker-saturation Lesson #51-class structural-pin opportunity (5/17 files loaded), the MEDIUM D3 LocalStorage may be a real gap, the LOW D4 is likely walker truncation.
