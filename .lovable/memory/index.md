# Project Memory

## Core
Spec-only project. No app code generation; do not modify src/pages/Index.tsx.
Markdown spec docs live under `spec/`; numbered `NN-name/` folders, each with `00-overview.md`.
Never touch `.release/`. Bump at least minor version on code changes.
Do NOT append boilerplate "If you have any question..." or "Do you understand?..." blocks.
Always list remaining tasks at end of session; on `next`, find pending work from memory if task list is empty.
Spec edits keep these in lockstep: target file banner + §98 changelog row + §99 health/inventory + `mem://specs/git-logs.md` queued-decisions trail.
File slots are immutable once shipped — never reuse a number; if content moves, rename the slot and add a §99 audit row (precedent: §16 → §37 in v2.8.6; §22 app-issues → §25 in v3.7.0).
Tree health is MEASURED by `node linter-scripts/check-tree-health.cjs --report` — never narrate scores. Current measured baseline: 71/100 (C); CI threshold locked at 70 (v3.7.2). Run `node linter-scripts/generate-spec-index.cjs` after spec changes.
Session-persistence regression CONFIRMED THIRD TIME (v3.7.2 dashboard edit was rolled back between sessions) — always re-verify file contents at session start before declaring "fixed".
Legacy v1 spec lives at `spec/_archive/21-git-logs-v1/` (archived 2026-04-25); v2 authoritative at `spec/22-git-logs-v2/`.

## Memories
- [Git Logs spec layout](mem://specs/git-logs) — Folder 22 v2.8.7 authoritative (SQLite root DB, no JWT, SSH-key Lane B in §31). Section map §00–§08 + §14–§37 + §97–§99 with locked intentional gap §09–§13. One open question: §07 App identity fields. Folder 26 = Mermaid diagrams; folder 21 archived.
- [Full-tree audit v4](mem://specs/full-tree-audit-v4) — Pre-triage 45/100 (F). Post Phase-1 (v3.7.0) narrated 78. MEASURED v3.7.2 = 71/100 (C). Phase 2 remaining: 14 missing `99-consistency-report.md`, ~13 missing `97-acceptance-criteria.md`. CI gate at threshold 70.
