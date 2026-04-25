# Project Memory

## Core
Spec-only project. No app code generation; do not modify src/pages/Index.tsx.
Markdown spec docs live under `spec/`; numbered `NN-name/` folders, each with `00-overview.md`.
Never touch `.release/`. Bump at least minor version on code changes.
Do NOT append boilerplate "If you have any question..." or "Do you understand?..." blocks.
Always list remaining tasks at end of session; on `next`, find pending work from memory if task list is empty.
Spec edits keep these in lockstep: target file banner + §98 changelog row + §99 health/inventory + `mem://specs/git-logs.md` queued-decisions trail.
File slots are immutable once shipped — never reuse a number; if content moves, rename the slot and add §99 audit row (precedent: §16 → §37 in v2.8.6; §22 app-issues → §25 in v3.7.0).
Tree health is MEASURED by `node linter-scripts/check-tree-health.cjs --report` — never narrate scores. Current measured baseline: 81/100 (B); CI threshold locked at 80 (v3.7.3, wired into run.sh + GitHub Actions in v3.7.5).
SELF-HEAL: `node linter-scripts/fill-missing-consistency-reports.cjs` regenerates rolled-back consistency reports idempotently. Run `bash linter-scripts/run.sh` to execute the full pipeline (validate → self-heal → regen index → gate).
Session-persistence regression CONFIRMED 6x — pattern: only ~1 newly-created file per turn survives. Always run the run.sh pipeline after a rollback to recover; CI workflow at `.github/workflows/spec-health.yml` enforces this on PRs.
Legacy v1 spec lives at `spec/_archive/21-git-logs-v1/` (archived 2026-04-25); v2 authoritative at `spec/22-git-logs-v2/`.

## Memories
- [Git Logs spec layout](mem://specs/git-logs) — Folder 22 v2.8.7 authoritative (SQLite root DB, no JWT, SSH-key Lane B in §31). Section map §00–§08 + §14–§37 + §97–§99 with locked intentional gap §09–§13. One open question: §07 App identity fields. Folder 26 = Mermaid diagrams; folder 21 archived.
- [Full-tree audit v4](mem://specs/full-tree-audit-v4) — Pre-triage 45/100 (F). Phase 1 (v3.7.0) renames + archive. Phase 2a (v3.7.3) authored 14 consistency reports. Phase 2b (v3.7.4) wired CI gate into run.sh/run.ps1. Phase 2c (v3.7.5) GitHub Actions + idempotent self-healer. MEASURED 81/100 (B); required 104/104. Remaining: ~26 missing `97-acceptance-criteria.md` (recommended weight 25%, ~+19 score possible).
