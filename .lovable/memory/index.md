# Project Memory

## Core
Spec-only project. No app code generation; do not modify src/pages/Index.tsx.
Markdown spec docs live under `spec/`; numbered `NN-name/` folders, each with `00-overview.md`.
Never touch `.release/`. Bump at least minor version on code changes.
Do NOT append boilerplate "If you have any question..." or "Do you understand?..." blocks.
Always list remaining tasks at end of session; on `next`, find pending work from memory if task list is empty.
Spec edits keep these in lockstep: target file banner + §98 changelog row + §99 health/inventory + `mem://specs/git-logs.md` queued-decisions trail.
File slots are immutable once shipped — never reuse a number; if content moves, rename the slot and add a §99 audit row (precedent: §16 → §37 in v2.8.6; §22 app-issues → §25 in v3.7.0).
Full-tree audit baseline: 78/100 (B) per v3.7.0 (Phase 1 Triage complete). Folder-17 partial audits (25/26/29) score 99.8 but do NOT cover the rest.
Session-persistence regression observed twice — verify file presence at start of each session before declaring "fixed".
Legacy v1 spec lives at `spec/_archive/21-git-logs-v1/` (archived 2026-04-25); v2 authoritative at `spec/22-git-logs-v2/`.

## Memories
- [Git Logs spec layout](mem://specs/git-logs) — Folder 22 v2.8.7 authoritative (SQLite root DB, no JWT, SSH-key Lane B in §31). Section map §00–§08 + §14–§37 + §97–§99 with locked intentional gap §09–§13. One open question: §07 App identity fields. Folder 26 = Mermaid diagrams; folder 21 archived.
- [Full-tree audit v4](mem://specs/full-tree-audit-v4) — Pre-triage 45/100 (F). Post Phase-1 (v3.7.0): 78/100 (B). Phase 2 remaining: 13 missing AC files, 15 missing consistency reports, spec-index regen.
