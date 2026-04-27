# Project Memory

## Core
Spec-only project. No app code generation; do not modify src/pages/Index.tsx.
Markdown spec docs live under `spec/`; numbered `NN-name/` folders, each with `00-overview.md`.
Never touch `.release/`. Bump at least minor version on code changes.
Do NOT append boilerplate "If you have any question..." or "Do you understand?..." blocks.
Always list remaining tasks at end of session; on `next`, find pending work from memory if task list is empty AND EXECUTE the next task, never just list.
Spec edits keep these in lockstep: target file banner + §98 changelog row + §99 health/inventory + `mem://specs/git-logs.md` queued-decisions trail.
File slots are immutable once shipped — never reuse a number; if content moves, rename the slot and add a §99 audit row (precedent: §16 → §37 in v2.8.6).
Full-tree audit baseline: 45/100 (F) per `spec/17-consolidated-guidelines/31-full-tree-ai-audit-v4.md`. Folder-17 partial audits (25/26/29) score 99.8 but do NOT cover the rest.
Session-persistence regression observed twice — verify file presence at start of each session before declaring "fixed".
Phase 26 done (2026-04-26): 6 missing-contract firings cleared via inlined `text` JSON-schema blocks (≥10 non-blank lines each) in spec/00, spec/01-spec-authoring-guide/00, spec/02-coding-guidelines/08-file-folder-naming/00, spec/02-coding-guidelines/11-security/00, spec/03-error-manage/03-error-code-registry/08-linter-scripts/00, spec/25-app-issues/01-phase-2-git-logs-audit/00.
Phase 27a done (2026-04-26): 7 critical drift specs cleared via `kind: future-spec` frontmatter + Drift Acknowledgment section. Files updated: 02-coding-guidelines/04-php, 03-error-manage/01-error-resolution/03-retrospectives + 05-debugging-guides, 12-cicd-pipeline-workflows/01-browser-extension-deploy + 02-go-binary-deploy, 22-git-logs-v2 (banner v3.8.5→v3.8.6). 7th (23-app-database) already exempt as `kind: index`.
Phase 27b done (2026-04-26): 9 high-drift specs cleared via same pattern. Files: 02-coding-guidelines/{01-cross-language/04-code-style, 01-cross-language/15-master-coding-guidelines, 02-typescript, 06-ai-optimization, 06-cicd-integration, 11-security}, 03-error-manage, 11-powershell-integration, 15-distribution-and-runner.
Phase 27c done (2026-04-26): 17 medium-drift specs cleared. Modules: 01-spec-authoring-guide, 02-coding-guidelines (root + 01-cross-language/02-boolean-principles + 03-golang root/01-enum/04-standards-ref + 05-rust + 11-security/01-axios + 24-app-design-system), 03-error-manage/02-error-architecture/07-logging, 03-error-manage/03-error-code-registry (root + 08-linter-scripts), 05-split-db-architecture, 12-cicd-pipeline-workflows (root + 03-reusable-ci-guards), 25-app-issues (root + 02-consolidated-audit-findings).
Phase 27 remaining: 14 low drift findings. User chose "Full 47" strategy.

## Memories
- [Git Logs spec layout](mem://specs/git-logs) — Folder 22 v2.8.7 authoritative (SQLite root DB, no JWT, SSH-key Lane B in §31). Section map §00–§08 + §14–§37 + §97–§99 with locked intentional gap §09–§13. One open question: §07 App identity fields. Folder 26 = Mermaid diagrams; folder 21 = legacy v1, banner-deprecated. §00 banner now v3.8.6 with `kind: future-spec` frontmatter (Phase 27a).
- [Full-tree audit v4](mem://specs/full-tree-audit-v4) — 45/100 (F) verdict, 3-phase roadmap to 100, 4 critical findings, 3 user-blocked decisions (rename 22→25, archive 21, broken-link strategy), and list of work lost to rollback.
