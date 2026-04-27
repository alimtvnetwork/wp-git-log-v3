---
phase: 128
title: Lint Rule Catalog — canonical SoT created (Candidate O pre-req)
mode: implementation
predecessor: phase-127-folders-27-18-25-final-sweep.md
successor: TBD (Phase 117 mechanization, Phase 123 placeholder catalog)
date: 2026-04-27
---

# Phase 128 — `06-lint-rule-catalog.md` shipped

## What changed

Created `spec/03-error-manage/03-error-code-registry/06-lint-rule-catalog.md` v1.0.0
as the canonical SoT for the `<DOMAIN>-<NAME>-NNN` lint rule ID family surfaced as
Candidate O in Phase 126.

### Files touched (all in §03)

| File | Change |
|---|---|
| `spec/03-error-manage/03-error-code-registry/06-lint-rule-catalog.md` | **NEW** v1.0.0 — 7-rule canonical table + citation conventions + add/modify workflow |
| `spec/03-error-manage/03-error-code-registry/97-acceptance-criteria.md` | Added `06-lint-rule-catalog.md` to module-specific acceptance surface |
| `spec/03-error-manage/03-error-code-registry/98-changelog.md` | Phase 128 row appended |
| `spec/03-error-manage/03-error-code-registry/99-consistency-report.md` | Inventory 8 → 9, Phase 128 audit row appended |
| `spec/03-error-manage/98-changelog.md` | Parent §03 banner 3.3.0 → 3.4.0, Phase 128 row appended |

Slot `06-` was the next free integer in the registry folder; immutable-slot rule
respected (`05-overlap-validator.md` already shipped, never reused).

## Catalog content — 7 rules in 3 families

| Rule ID | Family | Citation sites |
|---|---|---|
| `DB-FREETEXT-001` | Free-text column presence | 4 files |
| `MISSING-DESC-001` | Free-text column conformance + waiver syntax | 20 files |
| `WAIVER-MALFORMED-001` | Waiver field completeness | 2 files |
| `MIG-NAMING-001` | Migration filename pattern | 2 files |
| `MIG-HEADERS-001` | Migration header presence | 2 files |
| `MIG-TARGET-001` | Migration target enum | 2 files |
| `MIG-NULLABLE-001` | Migration column nullability | 2 files |

Total: **34 cite sites** now resolve to a single canonical row.

## What's explicitly NOT in scope

- `CODE-RED-NN` markers in `spec/17-consolidated-guidelines/02-coding-guidelines.md`
  are coding-guidelines anti-pattern markers — separate namespace. If they need
  formalising, that is a §02 task, not this catalog.
- Rename guards in `linter-scripts/forbidden-strings.toml` — different concern
  (literal-string drift detection vs. lint rule enforcement).
- The actual enforcement scripts under `linter-scripts/sql-linter/` are still
  TBD. Catalog frontmatter explicitly carries `kind: future-spec` to acknowledge
  this. Phase 117's containment harness operates at the catalog layer regardless.

## Backlog impact

Candidate O is now **unblocked** for Phase 117 mechanization. Two of three
deferred candidates need only a catalog file each:

| Candidate | Pre-req status |
|---|---|
| N — §16 placeholder tokens | ❌ Catalog still missing (Phase 123) |
| O — Lint rule IDs | ✅ **Catalog shipped (this phase)** |

If the user runs `Phase 117: containment-only`, harness can now cover **3 of 4**
containment candidates (A + H + O), deferring only N.

## Recommended next phases

| Phase | Action | Mode |
|---|---|---|
| **117** | Mechanize 8-candidate AC-31-31 backlog. Containment harness now covers A+H+O (defers N until Phase 123). Uniform-parity covers B+E+K+L. | 🚧 Decision |
| **122** | §17 OpenAPI: enumerate `GLCI-*` codes or leave code-free. | 🚧 Decision |
| **123** | Create `spec/16-generic-release/09-placeholder-tokens.md` (last catalog blocker for Candidate N). | 🤖 Autonomous (after 117) |
| **124** | Audit §14 GOOS/GOARCH AC-20 cite to §16 generic source. | 🚧 Decision |

## Completion certification

- ✅ New file scaffolded with full v1.0.0 banner, frontmatter, 7-section content
- ✅ Lockstep: target file banner + §97 surface + §98 changelog + §99 inventory + parent §03 §98
- ✅ Slot rule respected (06- was free; never reused 05)
- ✅ Memory mirror updated (this memo)
- ✅ Pre-req unblocked for Phase 117 Candidate O
