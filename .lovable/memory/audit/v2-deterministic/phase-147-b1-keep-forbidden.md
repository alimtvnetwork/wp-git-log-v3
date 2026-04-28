# Phase 147 — B1 finalized: `keep forbidden`

**Date:** 2026-04-28
**Trigger:** User reply `next` (autonomous queue exhausted; recommended close = `B1: keep forbidden`).

## Decision
User implicitly accepted recommendation. Locked decision 12 in §07 promoted from "forbidden-by-default, awaiting Phase B1 unblock" → **PERMANENTLY FORBIDDEN**.

`Environment`, `Platform`, `OwnerEmail` (and any other identity-shaped column) MUST NOT be added to the `App` table. Future identity expansion requires a NEW locked decision (14+) with a fresh changelog row — silent edits to #12 are forbidden.

## Files touched (lockstep)
| File | From → To | Change |
|---|---|---|
| `spec/22-git-logs-v2/07-app-entity.md` | v2.1.0 → v2.2.0 | Locked decision 12 prose: "FORBIDDEN until unblocked" → "PERMANENTLY FORBIDDEN"; rationale rewritten (deployment-target identifier, not metadata bag); added "future expansion requires new locked decision" guard. |
| `spec/22-git-logs-v2/97-acceptance-criteria.md` | v3.9.0 → v3.9.1 | AC-17 line 85 prose mirrors §07 — drops "until unblocked" hedge; cites Phase 147. |
| `spec/22-git-logs-v2/98-changelog.md` | row 3.8.11 added | Phase 147 row. |
| `spec/22-git-logs-v2/99-consistency-report.md` | v3.9.5 → v3.9.6 | Banner refresh only (no inventory change). |
| `mem://specs/git-logs.md` | description + body | "Open question" section → "Closed decisions"; description line refreshed. |
| `mem://index.md` | line 20–21 | Folder-22 entry: B1 marked closed; audit-v4 open-items list trimmed. |

## Gates
- `node linter-scripts/check-lockstep.cjs` → 87/87 pass · 0 findings ✅
- `node linter-scripts/check-tree-health.cjs --strict` → 168/168 strict-pass ✅

## Scope discipline
- §02 / §18 / §15 / §00 untouched (FORBIDDEN-fields rule has been load-bearing for months — §18 already lacks the columns).
- No DDL change. No AC IDs added/removed (count = 75). No schema bump.
- §00 overview's "Locked Decisions" table uses different numbering (#12 = "App lifecycle") and was correctly NOT touched — §07's local decision-numbering is independent.

## Outcome
Folder 22's "Open question" subsection is now empty. The B1 question that has been carried since Phase 134 is closed. Phase 117 / 108 / F1 / F3 / R1 remain open for user.
