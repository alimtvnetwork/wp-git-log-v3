---
name: phase-153-task-A1-audit-v2-deep-walk
description: Deep-walk AI-implementability audit (v2) — corrects v1's harness artifacts and rebaselines tree score from 54.2 → 81.4
type: feature
---

# Phase 153 Task A1 — AI Implementability Audit v2 (deep-walk)

## What changed

Rebuilt the AI implementability audit harness to walk the **full sub-tree** of every top-level spec module (recursive `*.md` glob, 90 KB cap) instead of only sending §00 + §97 (24 KB cap in v1). Discovery: **most v1 CRITICAL findings were harness artifacts**, not real spec gaps.

## Headline results

- **Tree score**: 54.2 / 100 → **81.4 / 100** (Δ +27.2). Band: 🔴 BLOCKING → 🟡 GOOD.
- **CRITICAL findings**: 23 → 5 (and 2 of the remaining 5 are still harness artifacts at the 90 KB cap, e.g. `spec/25` whose AC-05 is intact at 102 lines).
- **Per-dimension** uplifts: D1 +4.9, D2 +6.7, D3 +2.9, D4 +5.0, D5 +6.5.
- **Top movers**:
  - `spec/28-universal-ci-cli` 37 → 97 (+60)
  - `spec/12-cicd-pipeline-workflows` 27 → 78 (+51)
  - `spec/27-spec-toolchain` 43 → 85 (+42)

## Genuine remaining issues (after dedup of harness noise)

| # | Module | Severity | Real issue |
|---|---|---|---|
| 1 | `spec/06-seedable-config-architecture` | CRITICAL D1 | §00 declares PascalCase schema; `01-fundamentals.md` uses camelCase — actual contract conflict |
| 2 | `spec/04-database-conventions` | CRITICAL D5 | Hard-references response-envelope spec across module boundary; auditor (and mediocre AI) cannot resolve without explicit pull |
| 3 | `spec/11-powershell-integration` | CRITICAL D5 | `schemas/powershell.schema.json` + `templates/run.ps1` declared as source-of-truth but live outside `spec/` tree |
| 4 | `spec/07-design-system` | CRITICAL D5 | References `src/index.css` + `tailwind.config.ts` (app code, intentionally outside spec scope) |
| 5 | `spec/02-coding-guidelines` | HIGH D3-D5 | 251 files; 90 KB cap surfaces only 13 — sub-modules need their own per-folder audit pass |

## Harness fixes applied during this phase

1. **User-Agent header**: Cloudflare 1010 blocks Python's default UA above ~25 KB payloads. Added `User-Agent: lovable-spec-audit/1.0`.
2. **Recursive walk**: `Path.rglob("*.md")` instead of two hard-coded files.
3. **Tolerant JSON parser**: strip stray backslashes from model output (one module needed it).
4. **Cache per module**: `/tmp/audit_v2_cache/<module>.json` enables incremental reruns.

## Key methodology lesson (codify)

> Any AI-implementability audit MUST walk the full module sub-tree, not just two files. The v1 harness pattern produced **18 false-positive CRITICALs** that distorted prioritisation. Future audits should default to recursive walk + size cap, with explicit "deep-walk skipped at N KB" markers in the prompt so the model penalises only what it cannot see.

## Artefacts

- Report: `/mnt/documents/spec-ai-implementability-audit-v2.md`
- Harness: `/tmp/run_ai_audit_v2.py` (ephemeral)
- Builder: `/tmp/build_audit_report_v2.py` (ephemeral)
- Cache: `/tmp/audit_v2_cache/*.json` (23 modules)

## Validation

Spec content untouched in this phase — pure analysis pass. Lockstep / tree-health / version-parity all unchanged from Task #34 close (87/87, 168/168, 51/51 AI-confidence).

## Status

**A1 CLOSED.** Score lift achieved by **harness correctness**, not content change. Genuine remaining gaps are now narrow and individually addressable (3 real CRITICALs; 22 HIGH).


---

**Lessons codified:** #11 → see [`mem://process/phase-153-lessons`](../../../memory/process/phase-153-lessons.md) for the canonical contributor-rule statements.
