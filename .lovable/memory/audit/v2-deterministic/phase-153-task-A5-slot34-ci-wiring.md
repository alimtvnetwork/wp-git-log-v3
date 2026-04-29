# Phase 153 Task A5 — Slot 34 wired into spec-health.yml (advisory)

**Date:** 2026-04-29  
**Closed by:** Edit to `.github/workflows/spec-health.yml` + lockstep on §27.

## Result

- New workflow step "AI-implementability deep-walk audit (Phase 153 Task A5, advisory)" between Trace-map regression gate and Summary block.
- Runs `python3 linter-scripts/audit-ai-implementability.py --report-only` (always exits 0).
- Conditionally skipped via `if: env.LOVABLE_API_KEY != ''` so community PRs from forks don't 401-fail.
- `LOVABLE_API_KEY` injected from `secrets.LOVABLE_API_KEY`.
- Cache `.lovable/cache/audit-ai/` is repo-local — re-runs are cheap.

## Lockstep

- Slot 34 `34-audit-ai-implementability.md` v1.0.0 → **v1.0.1** (banner + new `## Status` "CI wiring (Phase 153 Task A5)" subsection).
- §27 §00 v2.75.0 → **v2.75.1**.
- §27 §98 v2.75.0 → **v2.75.1** (added 2.75.1 row).
- §27 §99 v2.72.0 → **v2.72.1** (new v2.72.1 update block).
- **No** new ACs, AC-31-31 cascade, RUBRIC bump, or gate-count change. The LLM advisory step is broader-contract per H1 lesson — it gets its own workflow step rather than collapsing into a numbered footer gate (which would corrupt 15/15/15 parity).

## Validation

- `node linter-scripts/check-lockstep.cjs --strict` → 87/87 PASS, 0 findings.
- `node linter-scripts/check-tree-health.cjs --strict` → 168/168 PASS, score 100/100.

## Lesson #15 (codify in mem://index.md)

Advisory CI steps for LLM-driven gates MUST guard on the secret being available (`if: env.LOVABLE_API_KEY != ''`). Without the guard, every fork PR would 401-fail and contributors learn to ignore the signal. Same pattern applies to any future Lovable-AI-Gateway-backed CI step.

## Next

- Future graduation phase: flip `--report-only` → `--strict` once tree-wide score holds GOOD or above for ≥3 consecutive baselines.
- A6: lift spec/05-split-db-architecture 69 → 80+ (lone NEEDS_WORK module).
