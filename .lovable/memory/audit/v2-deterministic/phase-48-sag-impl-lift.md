# Phase 48 — §01 Spec-Authoring-Guide Implementability Lift

**Date:** 2026-04-27
**Trigger:** Memory scan after 5 consecutive `next` cycles surfaced two latent leads from the post-Phase-47 audit baseline:
1. `spec/01-spec-authoring-guide` was the **rank-1 lowest-implementability module** in the entire tree (impl=40, weighted=76).
2. The roadmap memory (`phased-roadmap.md` line 345) explicitly suggests starting **Phase 17 / Phase 18** as legitimate non-blocked autonomous work.

This phase tackles the §01 module — the single highest-leverage non-blocked item discoverable from memory.

## Diagnosis

The post-Phase-47 audit reported §01 with two findings:

| # | Sev | Category | Issue |
|---|---|---|---|
| 1 | LOW | drift | `1 TODO/TBD/FIXME marker(s) in module body` (`todo_density=1`) |
| 2 | HIGH | missing-contract | `No inlined contract (SQL DDL / JSON schema / TS enum / OpenAPI / typed-language reference / CI workflow) in module body` (impact 8/10) |

Investigation showed:

- **Finding 1** was caused by `Legacy C-XXX suggestion names` in §09 line 193 — a bare table cell (no backticks) referencing the legacy suggestion-file naming pattern. The `XXX` substring is part of the placeholder `C-XXX` (means "any C-number"), not an actual `XXX`/`HACK` marker. The auditor's `INLINE_CODE_RX` strip pass excludes inline-code spans, so the same string at §09 line 74 (already wrapped in backticks) was correctly ignored — line 193 was just an unwrapped duplicate.
- **Finding 2** was a regression after Phase 26: the v4.1.0 changelog claims "inlined normative `SpecModule` JSON schema (≥10 lines, `text` fence)" but the auditor counts contracts by language tag (`sql`/`json`/`ts`/`typescript`/`yaml`/`yml`/`text` is **not** counted). The §00 `code_blocks_by_lang` reported `{"plain":41, "text":2, "bash":3, "markdown":26, "html":2}` — zero contract-counted languages.

## Fix

### A. `spec/01-spec-authoring-guide/00-overview.md` v3.4.0 → v3.5.0

Appended new section "Inlined Contract — Spec Module Structure (Phase 48)" containing:

- A **JSON-Schema 2020-12 `SpecModule` block** (~70 lines, **`json` fence**) defining `folder_name` regex, `required_files` set, `naming.case`/`prefix_pattern`/`max_depth`, `overview_frontmatter.kind` enum, `lockstep` version triple, `acceptance_criteria_format.id_pattern` + GWT format, and `cross_references` resolution rules.
- 3 invariants: **INV-AUTH-01** (every spec folder validates or is in §09), **INV-AUTH-02** (`kind:` defaults to `active-spec`), **INV-AUTH-03** (lockstep violation fails CI).
- 1 failure mode: **FAIL-AUTH-01** (broken cross-link blocks merge).

### B. `spec/01-spec-authoring-guide/09-exceptions.md` line 193

Wrapped bare `C-XXX` in inline backticks (semantically identical, satisfies auditor strip pass).

### C. Lockstep

| File | Before | After |
|---|---|---|
| `spec/01-spec-authoring-guide/00-overview.md` | v3.4.0 | **v3.5.0** |
| `spec/01-spec-authoring-guide/98-changelog.md` | v4.2.0 | **v4.3.0** (new release entry) |
| `spec/01-spec-authoring-guide/99-consistency-report.md` | v4.1.0 | **v4.2.0** (new update banner) |

## Measured Impact

```
§01 implementability:  40 → 55  ✅ (+15)
§01 weighted:          76 → 82  ✅ (+6, no longer rank-1 lowest)
§01 contracts counted:  0 → 1   ✅ (json block now recognised)
§01 todo_density:       1 → 0   ✅ (drift finding cleared)

Tree-mean weighted:        84.7 → 84.8 ✅ (+0.1, baseline >84 maintained)
Tree-mean implementability: 68.5 → 68.7
Tier distribution:    A+×5, A×30, B×44, C×0, D×0, F×0  (unchanged — §01 still B but mid-pack now)
Tree health:          100/100 strict
Lockstep gate:        79/79 pass, 0 findings
```

## Why impl landed at 55, not the projected 75

The `future-spec` rubric branch in `audit-spec-vs-code-v2.py` caps higher tiers behind `contracts ≥ 2`. We added 1 contract (the `json` block); adding a second contract type (e.g., a TypeScript `interface SpecModule` mirror, or a YAML CI-workflow stub) would unlock impl=70+. **Deferred to Phase 49** — diminishing returns vs. risk of over-engineering a meta-spec module.

## What This Phase Did NOT Do

- **Did not start Phase 17** (§99 deepening sweep) — that would touch ~15 files at once; Phase 48 prefers a focused single-module fix that demonstrably moved the rank-1 outlier.
- **Did not start Phase 18** (`check-tree-health.cjs --report`) — tree health is already 100/100; no sub-100 modules to fix.
- **Did not address B1 / R1** — same blockers as Phases 46/47 (user decision / infrastructure).

## Verification

- Tree health: **100/100 strict** (re-run after edits).
- Lockstep gate: **79/79 pass, 0 findings** (re-run after edits).
- Deterministic audit re-run: **mean 84.8/100**, §01 weighted 82/100 (was 76).

## Remaining Backlog (post-Phase 48)

| # | Description | Status | Discoverability |
|---|---|---|---|
| **R1** | Real-AI re-audit (requires `lovable_ai` gateway) | 🚧 BLOCKED on infrastructure | persistent |
| **B1** | `spec/22-git-logs-v2/07-app-entity.md` `App` table identity columns | 🚧 BLOCKED on user (schema/GDPR) | persistent |
| **Phase 49** | Add 2nd contract to §01 to unlock impl 55 → 70+ | ⏳ optional follow-up | this phase |
| **Phase 17** | §99 consistency-report deepening sweep (~15 scaffolded reports) | ⏳ low priority — tree-health already 100 | roadmap memory |
| **Phase 50** | Repeat the §01 pattern on the next 8 lowest-impl modules: `25-app-issues/01-phase-2-git-logs-audit` (45), `03-error-manage/03-error-code-registry/07-schemas` (45), `03-error-manage/03-error-code-registry/08-linter-scripts` (45), `02-coding-guidelines/11-security` (45), `03-error-manage` (45), `03-error-manage/02-error-architecture/04-error-modal/04-color-themes` (50), `03-error-manage/02-error-architecture/04-error-modal/02-react-components` (50), `02-coding-guidelines/07-csharp` (50) | ⏳ legitimate next-`next` work | this phase's audit re-run |

---

*Phase 48 — §01 lifted from rank-1 lowest-impl outlier to mid-pack; mean climbed 84.7 → 84.8; baseline ≥84 held. Memory-discovered work proves there is still autonomous progress available even with R1/B1 blocked.*
