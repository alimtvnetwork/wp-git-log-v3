# Contributing

This repo is a **spec-driven** codebase. The `spec/` tree is the source of truth; `linter-scripts/` enforces the spec; `.github/workflows/` runs the linters in CI. Code changes that drift from spec — or spec changes that drift from code — are rejected by automated gates.

This document explains the quality bar, the four CI gates, and how to keep them green.

---

## Quality bar (current as of Phase 147 / v2-deterministic Phase 23)

| Metric | Threshold | Source |
|---|---:|---|
| Tree health (strict) | **100/100** across all 56+ modules | `linter-scripts/check-tree-health.cjs --strict` |
| Lockstep alignment | **0 findings** (§98 changelog date == §99 consistency-report date) | `linter-scripts/check-lockstep.cjs --strict` |
| Audit mean weighted score | **≥ 97/100** | `linter-scripts/audit-spec-vs-code-v2.py --min-weighted=97` |
| Audit mean implementability | **≥ 99/100** | `linter-scripts/audit-spec-vs-code-v2.py --min-impl=99` |
| Trace-map regression | **No AC-coverage drop, no orphan growth** | `linter-scripts/check-trace-map-regression.py` |

Current state: tree 100/100, lockstep 0, audit weighted 98.0 / impl 99.8.

---

## The four CI gates

Defined in `.github/workflows/spec-health.yml`. All four MUST pass on every PR. Run them locally before pushing.

### 1. Tree health (strict)

```bash
node linter-scripts/check-tree-health.cjs --strict
```

Verifies every spec module has the canonical 5-file shape: `00-overview.md`, `97-acceptance-criteria.md`, `98-changelog.md`, `99-consistency-report.md`, plus at least one body file. `--strict` allows zero partial credit (any missing file fails).

### 2. Lockstep (strict)

```bash
node linter-scripts/check-lockstep.cjs --strict
```

Enforces that when you bump `98-changelog.md` you also bump `99-consistency-report.md` (and vice versa) with the same `Updated:` date. Prevents silent half-updates.

### 3. AI-implementability audit (deterministic, with floors)

```bash
AUDIT_DETERMINISTIC=1 python3 linter-scripts/audit-spec-vs-code-v2.py \
    --min-weighted=97 --min-impl=99
```

Scores every module on 7 dimensions (implementability 35%, completeness 20%, alignment 15%, consistency 10%, clarity 10%, testability 7%, maintainability 3%) and applies hard scoring gates. `--min-weighted=N` and `--min-impl=N` (added in Phase 81) exit non-zero when the corpus mean drops below the floor. Floors set ~1 pt below current means to absorb noise while catching genuine regressions.

Full rubric: `spec/27-spec-toolchain/31-audit-spec-vs-code-v2.md` (currently at v1.23.0, covering script v2.17, `RUBRIC_VERSION = "v2.26"`).

### 4. Trace-map regression

```bash
python3 linter-scripts/check-trace-map-regression.py
```

Compares current trace-map (AC ↔ source-file mapping) against the committed baseline. Fails if AC coverage drops, drift grows, orphan code grows, or `trace-map.toml` references a missing AC/file.

---

## Working in `spec/`

Each module folder follows this shape:

```
spec/<NN>-<slug>/
├── 00-overview.md              # purpose, scope, version, key concepts
├── 97-acceptance-criteria.md   # AC-NN-MM with Given/When/Then
├── 98-changelog.md             # dated change log
├── 99-consistency-report.md    # dated lockstep partner
├── lifecycle-*.mmd             # (optional) Mermaid lifecycle diagram
└── <NN>-*.md                   # body files (deep-dives, references)
```

When you edit a module:
1. Update the body content.
2. Add a row to `98-changelog.md` with today's date.
3. Add a corresponding row to `99-consistency-report.md` with the **same date**.
4. Bump `Version:` and `Updated:` in `00-overview.md` if the change is normative.
5. If you added or changed behaviour, add a new `AC-NN-MM` block in `97-acceptance-criteria.md`.

Front-matter keys (place at top of `00-overview.md`):
- `kind: tracker` — issue/finding ledgers (impl baseline 75)
- `kind: index` — placement-rule routers (impl baseline 70)
- `kind: meta-toolchain` — auditor-self-reference modules (impl baseline 75–95)
- `todo_audit_exempt: true` — opt out of the TODO-density penalty (only for modules that legitimately quote `TODO:` markers, e.g. when documenting the TODO detector itself)

Full guide: `spec/01-spec-authoring-guide/00-overview.md`.

---

## Working on `linter-scripts/audit-spec-vs-code-v2.py`

The audit script is itself spec'd in `spec/27-spec-toolchain/31-audit-spec-vs-code-v2.md`. Any rubric change MUST land paired with:

1. Bumped `**Version:**` header in `spec/27-spec-toolchain/31-audit-spec-vs-code-v2.md`
2. New `AC-31-NN` block describing the behaviour with Given/When/Then
3. Row added to the "Rubric changelog" table in §31
4. **Empirical measurement on the 87-module corpus** — record before/after means in a `.lovable/memory/audit/v2-deterministic/phase-NN-*.md` memo

**Negative results count.** A rejected experiment SHOULD be documented (source comment + memo) so future contributors don't re-propose it without the data. See `.lovable/memory/audit/v2-deterministic/phase-86-schema-cap-rejected.md` for the template.

---

## Phase memos

Each completed work phase is captured in `.lovable/memory/audit/v2-deterministic/phase-NN-<slug>.md` with the following structure:

- **Why** — what problem this phase addressed
- **Change** — what was modified (files, lines, formulae)
- **Verification** — which gates were re-run and their results
- **Effect** — measurable impact (score deltas, locked-in behaviour, etc.)

Look at `phase-81` through `phase-86` for rubric-tuning examples; `phase-18` through `phase-23` for recent stale-prose / drift-sweep examples.

---

## Local dev tips

- The audit script runs in **deterministic mode by default** (`AUDIT_DETERMINISTIC=1`). AI mode requires `LOVABLE_API_KEY` and is currently unused in CI.
- The audit script supports `AUDIT_ONLY="<substring>"` for fast smoke-testing one module.
- `linter-scripts/audit-spec-vs-code-v2.py` writes outputs to `.lovable/memory/audit/v2-deterministic/`. These are committed and reviewed; don't edit them by hand.
- Lockstep failures usually mean you forgot to bump `99-consistency-report.md` after editing `98-changelog.md` (or vice versa). Check the dates match exactly.
