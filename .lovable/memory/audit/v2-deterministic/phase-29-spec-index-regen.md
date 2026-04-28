# Phase 29 — `spec/spec-index.md` freshness regen

**Date:** 2026-04-28  
**Mode:** No-Questions Mode 13/40  
**Trigger:** Phase 28 close-out recommendation (b) — sweep root flat index.

## Drift found

Committed `spec/spec-index.md` was 877 files / 1042 lines. Fresh `node linter-scripts/generate-spec-index.cjs` produced **883 files / 1048 lines**, an 18-line / 6-file delta covering accumulated unflushed state:

| Category | Stale | Current |
|---|---|---|
| Total Files header | 877 | **883** |
| §27 entries | 40 | **46** (6 new slots: 18, 19, 25, 26, 27, 28) |
| `22-git-logs-v2/07-app-entity.md` | 2.1.0 | **2.2.0** (Phase 147 B1 close) |
| `22-git-logs-v2/97-acceptance-criteria.md` | 3.9.0 | **3.9.1** |
| `22-git-logs-v2/99-consistency-report.md` | 3.9.5 | **3.9.6** |
| `27-spec-toolchain/00-overview.md` | 1.7.0 | **2.46.3** |
| `27-spec-toolchain/31-audit-spec-vs-code-v2.md` | 1.22.0 | **1.23.0** |
| `27-spec-toolchain/62-spec-folder-refs-allowlist.md` | 1.0.0 | **1.2.0** |
| `27-spec-toolchain/97-acceptance-criteria.md` | 2.0.1 | **2.2.0** |
| `27-spec-toolchain/98-changelog.md` | 2.30.0 | **2.46.3** |
| `27-spec-toolchain/99-consistency-report.md` | 2.27.0 | **2.43.3** |
| `spec/98-changelog.md` | 3.5.0 | **3.5.1** (Phase 28) |

## Root cause

The `Regenerate spec-index.md` step in `.github/workflows/spec-health.yml` line 63 runs the regenerator and `git status --porcelain spec/`, but on drift it prints a `⚠️` warning and **exits 0**. The gate is **advisory**, not strict. Phases 145, 147, F2, F3, H1, H4–H7, 28 all bumped versions but never ran `bash linter-scripts/run.sh` locally and never committed the regenerated index — and CI silently rubber-stamped each one.

## Edits

- `spec/spec-index.md` — full regen, 877 → 883 files, 1042 → 1048 lines.
- `.github/workflows/spec-health.yml` — added Phase 29 `NOTE` comment block on the existing advisory step explaining the deferred-promotion rationale (Phase 30 = AC-31-31 cascade).

## Verification

- `check-lockstep.cjs --strict` → 87/87 pass · 0 findings ✅
- `check-tree-health.cjs --strict` → 100/100, 56/56 modules ✅
- `test-qa-baseline-footer.sh` → 11/11 pass; **17/17/17 parity preserved** (declared = footer rows = workflow gates = 17). Advisory step does NOT count toward the 17.

## Why the workflow YAML wasn't promoted to strict in this phase

Following the **H4 → H5 lesson** ("don't rush AC-31-31"), promoting the spec-index drift check from advisory to strict triggers the full multi-file cascade:
1. `RUBRIC_VERSION` v2.26 → v2.27 in `audit-spec-vs-code-v2.py`
2. Footer enumeration: 17 → 18 strict CI gates (add entry #18)
3. EXECUTIVE-SUMMARY back-reference 17 → 18
4. `test/test-qa-baseline-footer.sh` awk pattern + declared count
5. Workflow step rename (gate naming convention)
6. New AC under §27 §97 (e.g. AC-29-01 spec-index drift contract)
7. §27 §98 changelog entry, §99 stamp bump
8. Memory Core `gate count` line update (currently "15" stale; see Ambiguity-03 — actually now 17)

Phase H4 deliberately split: H4 shipped the gate + self-test + spec doc, H5 wired CI + ran the cascade. **Phase 29 follows the same playbook**: regen the artifact (mechanical, scope-bounded) and queue Phase 30 = strict-promote + cascade.

## Lessons

1. **Advisory CI gates silently rot.** This is the second instance (after Phase H1's session-persistence-regression class) where an exit-0-on-drift gate let real drift accumulate undetected for many phases. Pattern: any gate that detects a legitimately-fixable error condition SHOULD `exit 1` unless there's a specific reason it must remain advisory (e.g. H1's per-file rollout phase).
2. **Generator artifacts in `run.sh` need CI parity.** `run.sh` includes `generate-spec-index.cjs` (line 98), but contributors don't always run it before committing. Authoritative truth: CI must regen + diff strictly OR the artifact must not be committed at all (treat as build output).
3. **AC-31-31 cascade discipline.** Treat strict-gate promotion as its own phase to avoid mixing artifact regen with multi-file rubric/footer/contract bumps. H4 → H5 split worked; replicate.

## Status: CLOSED · Phase 30 queued (strict-promote + AC-31-31 cascade)
