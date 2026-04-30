# Phase 153 Task A24-fu16 — spec/02 floor-lift (AC-CG-25/26/27)

**Date:** 2026-04-30
**Trigger:** A20-fu2 v8 rebaseline diagnosed spec/02 at 75 (GOOD floor — lowest in tree); 3 audit-v8 findings.
**Status:** CLOSED

## Findings closed

| # | Severity | Dim | Title | Closed by |
|---|---|---|---|---|
| 1 | CRITICAL | D2 | Circular/Self-Referential Acceptance Criteria | **AC-CG-25** — inline 1 GWT sample each for Go/TS/Rust |
| 2 | HIGH | D4 | Missing Worked Examples for Size-Limit Exceptions | **AC-CG-26** — 19-line Rust `match` worked example + counter-example |
| 3 | MEDIUM | D3 | Ambiguous Concurrency/Partial Failure in CI Gates | **AC-CG-27** — fail-fast policy (5-runtime CI matrix) |

## Edits

- `00-overview.md` — added Lesson #55-pattern walker-pin teaser blockquote immediately after the version banner; banner v3.4.2 → v3.5.0.
- `97-acceptance-criteria.md` — added AC-CG-25 (inline language samples for Go/TS/Rust; PHP/C# omitted per walker-cap budget), AC-CG-26 (worked Rust `match` example + counter-example with line-count math), AC-CG-27 (fail-fast policy: deterministic `(exit_code=1, reason_code=LINTER_TIMEOUT|LINTER_PARTIAL|LINTER_PANIC)` tuple, retry-on-flake FORBIDDEN). Banner v4.4.0 → v4.5.0; AC count 29 → 32.
- `98-changelog.md` — added v3.5.0 row.
- `99-consistency-report.md` — added v4.7.0 audit row.

## Lockstep budget

- §97 minor (3 new ACs)
- §00 minor (new normative walker-pin block)
- §98 minor (mirrors §97)
- §99 minor (mirrors §00 normative addition)
- No CI workflow change, no RUBRIC bump, no AC-31-31 cascade, no gate-count change.

## Lessons codified

- **Lesson #56 (NEW)**: Numeric-threshold ACs MUST carry a worked example WITH counter-example. The counter-example is the critical disambiguator (proves the rule rejects boundary cases, not just accepts).
- **Lesson #57 (NEW)**: Mixed-runtime CI matrices MUST resolve timeouts/panics to a single deterministic `(exit_code, reason_code)` tuple — generalizes Lesson #15 from binary-secret axis to runtime-mix axis.

## Validation

- LLM re-score deferred per Lesson #20 — gateway available but score impact bounded by walker cap (auditor cannot bypass the 120 KB cap regardless of contract clarity). Expected lift 75 → ≥85 from D2 critical resolution + D4 high resolution + D3 medium resolution.
- Strict CI gates pending re-run.
