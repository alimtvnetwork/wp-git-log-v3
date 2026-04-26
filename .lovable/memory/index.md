# Project Memory

## Core
Spec-only project. No app code generation; do not modify src/pages/Index.tsx.
Communication: never use the words "suggest" or "never" in chat replies. Just do the work or state the action.
Markdown spec docs live under `spec/`; numbered `NN-name/` folders, each with `00-overview.md`.
Never touch `.release/`. Bump at least minor version on code changes.
Do NOT append boilerplate "If you have any question..." or "Do you understand?..." blocks.
Always list remaining tasks at end of session; on `next`, find pending work from memory if task list is empty.
Spec edits keep these in lockstep: target file banner + §98 changelog row + §99 health/inventory + `mem://specs/git-logs.md` queued-decisions trail.
File slots are immutable once shipped — never reuse a number; if content moves, rename the slot and add §99 audit row (precedent: §16 → §37 in v2.8.6; §22 app-issues → §25 in v3.7.0).
Tree health is MEASURED by `node linter-scripts/check-tree-health.cjs --report` — never narrate scores. Current measured baseline: 100/100 (A+); CI threshold locked at 100 (v3.7.7, wired into run.sh + GitHub Actions).
SELF-HEAL pipeline: `bash linter-scripts/run.sh` runs validate → fill-consistency → fill-AC → fill-changelogs → regen-index → gate. Four idempotent generators in `linter-scripts/`: `fill-missing-consistency-reports.cjs`, `fill-missing-acceptance-criteria.cjs`, `fill-missing-changelogs.cjs`, `generate-spec-index.cjs`.
Session-persistence regression CONFIRMED 6x — pattern: ~1 newly-created file per turn survives. Run `bash linter-scripts/run.sh` after a session restart to auto-recover; CI workflow at `.github/workflows/spec-health.yml` enforces the 100 threshold on every PR.
Legacy v1 spec lives at `spec/_archive/21-git-logs-v1/` (archived 2026-04-25); v2 authoritative at `spec/22-git-logs-v2/`.

## Memories
- [Git Logs spec layout](mem://specs/git-logs) — Folder 22 v2.8.7 authoritative (SQLite root DB, no JWT, SSH-key Lane B in §31). Section map §00–§08 + §14–§37 + §97–§99 with locked intentional gap §09–§13. One open question: §07 App identity fields. Folder 26 = Mermaid diagrams; folder 21 archived.
- [Full-tree audit v4](mem://specs/full-tree-audit-v4) — Pre-triage 45/100 (F). Phase 1 (v3.7.0) renames + archive. Phase 2a (v3.7.3) 14 consistency reports. Phase 2b (v3.7.6) 35 AC files. Phase 2c (v3.7.7) 43 changelog files. **MEASURED 100/100 (A+)**; required + recommended 208/208. Threshold locked at 100. Open: deepen scaffolded AC content for high-traffic modules (§07 design-system, §17 consolidated, §22 git-logs-v2); §07 App identity decision blocked on user.
