# 31 — audit-spec-vs-code-v2.py

**Version:** 1.0.0  
**Updated:** 2026-04-25  
**Source:** [`linter-scripts/audit-spec-vs-code-v2.py`](../../linter-scripts/audit-spec-vs-code-v2.py)  
**Category:** Auditor (AI-driven, **current**)  
**Predecessor:** §30 [`30-audit-spec-vs-code.md`](./30-audit-spec-vs-code.md)

---

## Purpose

AI-implementability audit. Asks: *"Could a mediocre AI ship a working implementation from this spec alone with zero human clarification?"*

## Methodology

1. Broader code index: `linter-scripts/` + `.github/` + `src/` presence.
2. Deterministic pre-checks computed BEFORE AI scoring (so AI can be calibrated):
   - waffle ratio (should/may/might/optionally per 1k chars)
   - contract presence (DDL, JSON, TS enums, YAML/OpenAPI, Mermaid)
   - cross-spec link health (broken count)
   - AC count + Given/When/Then block count
   - TODO/TBD/FIXME density
3. AI receives metrics + raw digest, must justify scores against them.
4. Outputs blast-radius (0–10): how many other specs would benefit from fixing this one.

## Weights

| Dimension | Weight |
|-----------|-------:|
| Implementability | 35% |
| Completeness | 20% |
| Alignment | 15% |
| Consistency | 10% |
| Clarity | 10% |
| Testability | 7% |
| Maintainability | 3% |

## Usage

```bash
python3 linter-scripts/audit-spec-vs-code-v2.py
AUDIT_ONLY="22-git-logs-v2" python3 linter-scripts/audit-spec-vs-code-v2.py   # smoke test one module
```

## Environment variables

| Var | Purpose |
|-----|---------|
| `LOVABLE_API_KEY` | Required — Lovable AI Gateway credential |
| `AUDIT_ONLY` | Substring filter; only audit modules whose path matches |

## Outputs

- `.lovable/memory/audit/v2/<module>.md` per module (overwritten).
- `.lovable/memory/audit/v2/00-index.md` — full ranking + blast-radius leaderboard.
- `.lovable/memory/audit/v2/EXECUTIVE-SUMMARY.md` — TL;DR.
- `.lovable/memory/audit/v2/raw-results.json` — machine-readable.

## Exit codes

| Code | Meaning |
|------|---------|
| 0 | Audit complete |
| 1 | At least one module failed AI scoring (others still written) |
| 2 | `LOVABLE_API_KEY` missing |

## Acceptance criteria

### AC-31-01 — Output lives under `v2/` subfolder
- **Given** a successful run,
- **When** `.lovable/memory/audit/` is listed,
- **Then** all v2 outputs MUST be inside the `v2/` subfolder (not mixed with v1).

### AC-31-02 — Implementability dominates the weighted score
- **Given** the rubric in code,
- **When** weights are summed,
- **Then** `implementability` MUST equal 35 and total MUST equal 100 (asserted at module load).

### AC-31-03 — Deterministic metrics include `.mmd` files
- **Given** a module folder containing `.mmd` files (e.g. `26-gitlogs-diagrams`),
- **When** the audit runs,
- **Then** `metrics.mmd_files` MUST equal the count of `.mmd` files and `metrics.has_mermaid` MUST be `true`.

### AC-31-04 — Blast-radius leaderboard surfaces foundational specs
- **Given** the generated `00-index.md`,
- **When** the "High blast-radius fixes" table is read,
- **Then** entries MUST be sorted by `(-blast_radius, weighted_overall)` (highest blast first).

### AC-31-05 — `AUDIT_ONLY` smoke test mode
- **Given** `AUDIT_ONLY="22-git-logs-v2"`,
- **When** the script runs,
- **Then** exactly one module MUST be audited.

## Cross-references

- §13 [`13-generate-gwt-acceptance.md`](./13-generate-gwt-acceptance.md) — consumes `raw-results.json`.
- §30 [`30-audit-spec-vs-code.md`](./30-audit-spec-vs-code.md) — predecessor.
