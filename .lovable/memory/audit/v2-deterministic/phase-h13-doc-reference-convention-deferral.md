# Phase H13 — Doc-Reference Convention Deferral (NO-OP)

**Date:** 2026-04-28
**Trigger:** User `next` command; backlog item #5 (last actively tractable item).
**Mode:** Read-only audit; no code, spec, or version changes.
**Outcome:** **DEFER INDEFINITELY** — fails H10 graduation filter (2 of 3 criteria).

## Question

Should `verified-phase` tokens in narrative prose (changelogs, §99 validation history blockquotes, slot-26 spec doc) be backtick-quoted to make the "real stamp vs doc reference" distinction explicit to human readers?

## H10 Filter Application

| Criterion | Verdict | Reasoning |
|---|---|---|
| Mechanically detectable | ⚠️ Partial | The validator already discriminates structurally via H9's adjacency rule — backtick-presence is not the discriminator. |
| Active regression surface | ❌ NO | H9 `--strict-position` shipped CI-strict with 0 misplaced findings. The failure mode is structurally eliminated. §27's own §99 blockquote references pass cleanly without backticks. |
| Low false-positive risk | ❌ NO | ~35+ locations would need retrofit (§27/99: 6, §27/98: 6, §27/26: 11, ~12 phase memos). Each cosmetic edit triggers stamp-bump gate + AC-31-31 cascade review + changelog churn. Conflicts with existing backtick-in-table-cell usage. |

## Verdict

**NO-OP.** Backtick-quoting is a *cosmetic* author convention layered on top of an *already-working structural gate*. Adding it provides no new lint capability and creates significant edit churn for zero validation benefit.

## Lessons Codified

- **L1 (H13):** When a structural lint already disambiguates two surface forms, a cosmetic author convention is redundant — the lint is the source of truth.
- **L2 (H13):** "Make the distinction explicit for human readers" belongs in the slot-26 spec doc (already covered by AC-26-10's negative case + adjacency-only rationale), not as a tree-wide markdown rewrite.
- **L3 (H13):** Surface-elimination success (H9: 0 misplaced findings, strict-from-day-one) is **evidence no further intervention is needed at that surface** — resist belt-and-braces conventions on top of working structural gates.

## Backlog Status After H13

All actively tractable backlog items closed (H10–H13 swept items #4–#7 with NO-OP verdicts plus H7/H8/H9 promotions earlier in this arc).

| # | Task | Mode |
|---|---|---|
| 1 | R1 — Real-AI re-audit | 🚧 Blocked on Lovable Cloud |
| 2 | R2 — Session-persistence regression | 👁 Watch only |
| 3 | Audit-v5 baseline drift | 👁 Watch only |

No code or spec files modified. No version bumps. Memory index updated to mark item #5 resolved.
