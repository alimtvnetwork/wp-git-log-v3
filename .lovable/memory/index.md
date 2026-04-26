# Memory: index.md
Updated: just now

# Project Memory

## Core
Spec-only project. No app code generation; do not modify src/pages/Index.tsx.
Markdown spec docs live under `spec/`; numbered `NN-name/` folders, each with `00-overview.md`.
Never touch `.release/`. Bump at least minor version on code changes.
Do NOT append boilerplate "If you have any question..." or "Do you understand?..." blocks.
Always list remaining tasks at end of session; on `next`, find pending work from memory if task list is empty.
Spec edits keep these in lockstep: target file banner + §98 changelog row + §99 health/inventory + `mem://specs/git-logs.md` queued-decisions trail.
File slots are immutable once shipped — never reuse a number; if content moves, rename the slot and add a §99 audit row (precedent: §16 → §37 in v2.8.6).
Full-tree audit baseline: 45/100 (F) per `spec/17-consolidated-guidelines/31-full-tree-ai-audit-v4.md`. Folder-17 partial audits (25/26/29) score 99.8 but do NOT cover the rest.
Session-persistence regression observed twice — verify file presence at start of each session before declaring "fixed".
v2-deterministic audit current: mean **78.7/100**, impl **54.9/100**, 17 A-tier, 1 D-tier, 0 F-tier (Phase 23, 2026-04-26). Audit script v2.1 honours `kind: tracker` front-matter to exempt issue/finding modules from `missing-contract` and `untestable` rubric findings.
§99 reports: deepening tool `linter-scripts/deepen-consistency-reports.py` is safety-guarded (never shrinks; skips ≥1500B, _archive/) — run it after adding new modules to keep §99 quality high.

## Memories
- [Git Logs spec layout](mem://specs/git-logs) — Folder 22 v2.8.7 authoritative (SQLite root DB, no JWT, SSH-key Lane B in §31). Section map §00–§08 + §14–§37 + §97–§99 with locked intentional gap §09–§13. One open question: §07 App identity fields. Folder 26 = Mermaid diagrams; folder 21 = legacy v1, banner-deprecated.
- [Full-tree audit v4](mem://specs/full-tree-audit-v4) — 45/100 (F) verdict, 3-phase roadmap to 100, 4 critical findings, 3 user-blocked decisions (rename 22→25, archive 21, broken-link strategy), and list of work lost to rollback.
- [Phase 20 progress](mem://specs/phase-20-progress) — COMPLETE & VERIFIED (11/11). Phase 22B audit confirmed both final modules (#10 Go enum, #11 PHP enum) lifted 73 C → 82 B (+9, impl 40→65). Tree mean now 78.7; G-CON-01 25. Phase 21 (§99 deepening, 25 modules) and Phase 23 (kind:tracker, 3 D→C lifts) since landed.
