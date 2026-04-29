---
name: phase-153-task-A4-audit-ai-implementability-productionised
description: Productionised the prototype LLM audit harness as slot-34 first-class linter; closes the spec/11-style non-md walker blind spot
type: feature
---

# Phase 153 Task A4 — Productionise audit-ai-implementability.py

## Shipped

- **`linter-scripts/audit-ai-implementability.py`** (slot 34, auditor band 30-39) — LLM-driven 5-dim deep-walk auditor (D1 Clarity / D2 ACs / D3 Edge / D4 Examples / D5 Cross-Ref Closure × 0-20).
- **`linter-scripts/test/test-audit-ai-implementability.sh`** — 6/6 self-test (CLI surface, exit codes, JSON shape, walker coverage).
- **`spec/27-spec-toolchain/34-audit-ai-implementability.md`** v1.0.0 — 8 ACs (AC-34-01..08), all GWT + `**Verifies:**` clauses, slot-range note.
- **§27 lockstep**: §00 v2.74.0→**2.75.0** (slot-34 inventory row added), §98 v2.74.0→**2.75.0** release row, §99 v2.71.0→**2.72.0** reconciliation prose.

## Improvements over the Phase 153 Task A1 prototype

1. Walks `*.md|*.json|*.yaml|*.yml|*.tmpl|*.toml` — closes spec/11 schemas/templates blind spot (verified: spec/11 file count 16→19).
2. SHA-keyed on-disk cache at `.lovable/cache/audit-ai/<module>.json` — clean reruns are free.
3. Six CLI flags: `--module`, `--no-network`, `--force`, `--json`, `--report-only`, `--strict`.
4. Cloudflare 1010 immunity baked in (explicit `User-Agent`).
5. Tolerant JSON parser (strips fences + stray backslashes).
6. Mirrors `check-ai-confidence.py` (slot 33) shape: advisory-by-default, machine-readable JSON, deferred CI graduation.

## Validation

- Self-test: **6/6 pass**.
- §27 inventory parity triangle: **6/6 pass** (AC-31-31, INV-01/INV-02).
- Lockstep: **87/87, 0 findings**.
- Tree-health: **168/168 strict-pass**.
- Confirmed walker delta: spec/11 sees 19 files via slot 34 vs 16 in `*.md`-only mode.

## Status

**A4 CLOSED.** Slot 34 advisory-by-default; CI graduation to `spec-health.yml` deferred until adoption converges (mirrors slot 33 P48-1-fu1 cadence).
