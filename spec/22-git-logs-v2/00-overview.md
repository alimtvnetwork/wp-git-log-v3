---
kind: future-spec
todo_audit_exempt: true
description: Authoritative spec for the Git Logs WordPress plugin (SQLite-backed). The actual PHP plugin code lives in a downstream WordPress-plugin repo, not in this spec-only repo. Exempt from drift findings that flag missing PHP / SQL / REST endpoint files. TODO markers in body files are historical-resolution narrative inside `37-blind-ai-gap-analysis.md` (Phase 39b) — quoted, not actionable.
---

# Git Logs v2 — Spec Overview

**Version:** 3.9.12  
**Updated:** 2026-04-28 (Phase P19: H10 §00↔§98 version-field parity catch-up — banner bumped from v3.8.9 to v3.9.11 to match the latest §98 release row (Phase P18). Phases P5/P6/P7/P7b/P8/P16/P17/P18 had each bumped §98 without re-stamping the §00 banner; this is pure parity bookkeeping (no content change). Eats our own dog food before any tree-wide H10-strict promotion.)
**Status:** Draft (future-spec — plugin code lives downstream)  
**AI Confidence:** Production-Ready  
**Ambiguity:** Low  
**Supersedes:** `spec/_archive/21-git-logs-v1/` (legacy v1 retained for history)

---

## Drift Acknowledgment (Phase 27 — 2026-04-26)

This module is the **authoritative contract** for the Git Logs WordPress plugin (SQLite root DB, REST endpoints, App-Password auth, etc.). The actual plugin implementation (PHP files, SQLite migrations, REST handlers) lives in a **separate downstream WordPress-plugin repository**, not in this spec-only repo. The local code index only contains `linter-scripts/`. Drift findings of the form "spec describes WP plugin but no PHP/SQL files exist locally" are **expected and accepted**. The `kind: future-spec` frontmatter signals the audit to skip them. Until the downstream repo is wired into a unified codebase, an alignment score of N/A (not 0) is correct.

---

## Origin

This module is the authoritative rewrite of the Git Logs WordPress plugin spec, derived from the verbatim brief at [`../_archive/21-git-logs-v1/reference/00-verbatim-brief.md`](../_archive/21-git-logs-v1/reference/00-verbatim-brief.md). Where v1 (folder 21) and v2 (folder 22) conflict, **v2 wins**.

---

## Locked Decisions

| # | Decision | Value |
|---|----------|-------|
| 1 | Database engine | SQLite (Gitlogs root DB), single file |
| 2 | Naming | PascalCase tables, columns, JSON keys, JSON values |
| 3 | Primary keys | `INTEGER AUTOINCREMENT`, named `{TableName}Id` |
| 4 | Auth (writes / admin UI) | WordPress App Password / cookie auth |
| 5 | Auth (CI/CD) | `TempToken` + GitHub URL + branch validation; **JWT dropped** |
| 6 | Roles | Plugin-internal SQLite (Admin, Editor); not WP roles |
| 7 | Authorization | Always check **Permission**, never Role |
| 8 | Acceptance modes | `AcceptAllRepos`, `AcceptSelectedRepoOnly`, `AcceptSelectedRepoInAllVersions` |
| 9 | Branch restriction | `IsRestrictInBranch` + `StrictBranch` on GitProfile |
| 10 | App linkage | Polymorphic `AppLink` table (LinkType: GitProfile \| Repo) |
| 11 | App credentials | Inherit from parent Profile (no own tokens) |
| 12 | App lifecycle | `AppStatus` enum: Active, Disabled, Archived |
| 13 | Audit model | Three tables: `AuditTrail` (system), `History` (per RepoVersion), `Action` (enum log) |
| 14 | Migrations | Marker per plugin version in DB config table; idempotent |
| 15 | Logger | Level-aware (Trace/Debug/Info/Warn/Error/Fatal); Info/Debug runtime-disable |
| 16 | REST namespace | `git-logs/v2` |
| 17 | Endpoint count | 10 (see 04) |
| 18 | Plugin slug | `git-logs` |
| 19 | DB table prefix | none (SQLite root DB owned by plugin) |

---

## Top-Level UI Menus

Profile · Roles · AccessToRoles · GitProfile · Repo · RepoVersion · History · Action

Items marked `format:hide` in mind-map are informational only and never rendered.

---

## Document Inventory

| # | File | Description |
|---|------|-------------|
| 00 | [00-overview.md](./00-overview.md) | This index |
| 01 | [01-glossary-and-enums.md](./01-glossary-and-enums.md) | Terms + enum catalog |
| 02 | [02-database-schema.md](./02-database-schema.md) | Tables, columns, FKs, indexes (markdown) |
| 03 | [03-admin-ui.md](./03-admin-ui.md) | Menus, screens, fields |
| 04 | [04-rest-api-endpoints.md](./04-rest-api-endpoints.md) | 10 endpoints, request/response shapes |
| 05 | [05-auth-and-validation.md](./05-auth-and-validation.md) | TempToken + URL/branch validation |
| 06 | [06-migrations-and-logger.md](./06-migrations-and-logger.md) | Versioned migration markers + level-aware logger |
| 07 | [07-app-entity.md](./07-app-entity.md) | App schema, AppLink polymorphism, lifecycle |
| 08 | [08-history-and-action.md](./08-history-and-action.md) | History/Action vs AuditTrail separation |
| 09 | _09-seed-data_ | **Locked vacant slot** — content redistributed to §37 + §08 |
| 10 | _10-rate-limit-and-payload_ | **Locked vacant slot** — content redistributed to §05 + §18 |
| 11 | _11-encryption-deferred-plan_ | **Locked vacant slot** — content redistributed to §30 R3 |
| 12 | _12-wp-plugin-scaffold_ | **Locked vacant slot** — content redistributed to §38 (planned) |
| 13 | _13-v1-vs-v2-mapping_ | **Locked vacant slot** — mapping distributed across §05/§18/§30/§31 |
| 14 | [14-endpoint-examples.md](./14-endpoint-examples.md) | Curl + JSON samples for all 10 endpoints |
| 15 | [15-error-codes.md](./15-error-codes.md) | Unified `GL-*` error catalog |
| 16 | [16-seed-data.md](./16-seed-data.md) | Authoritative initial-row content for every lookup table + `ConfigKv` defaults (Phase P5 — slot 16 collision with old `16-test-plan.md` resolved by relocating the superseded stub to §38) |
| 17 | [17-openapi.yaml](./17-openapi.yaml) | OpenAPI 3.1 machine-readable spec for all 10 endpoints |
| 18 | [18-schema.sql](./18-schema.sql) | Verbatim DDL for V2_0_0 migration |
| 19 | [19-permission-matrix.md](./19-permission-matrix.md) | Role × Permission × Screen audit grid |
| 20 | [20-observability.md](./20-observability.md) | Site Health card, metrics endpoint, counters |
| 21 | _(removed v3.7.8 — slot retired, see §99)_ | i18n out of scope for v2 |
| 22 | [22-retention-and-pruning.md](./22-retention-and-pruning.md) | `wp git-logs prune` command + eligibility rules |
| 23 | [23-backup-restore.md](./23-backup-restore.md) | SQLite Online Backup + manifest + restore validation |
| 24 | [24-multisite.md](./24-multisite.md) | Per-site vs network behavior |
| 25 | [25-headless-auth-notes.md](./25-headless-auth-notes.md) | Headless WP + JWT/OAuth supported combos |
| 26 | [26-readme-and-screenshots.md](./26-readme-and-screenshots.md) | WP.org `readme.txt` + screenshot inventory |
| 27 | [27-wp-cli-reference.md](./27-wp-cli-reference.md) | Consolidated `wp git-logs *` subcommand catalog |
| 28 | [28-example-github-actions.md](./28-example-github-actions.md) | Drop-in workflow YAML for Lane B push + fixed |
| 29 | [29-uninstall-policy.md](./29-uninstall-policy.md) | DB retention modes on plugin removal |
| 30 | [30-threat-model.md](./30-threat-model.md) | STRIDE pass over the v2 attack surface |
| 38 | [38-test-plan-superseded.md](./38-test-plan-superseded.md) | **Superseded** — redirect stub for the old §16 test plan; authoritative content in §32–§35. Relocated from slot 16 in Phase P5 (2026-04-28) per Core memory file-slot-immutability rule. |
| 39 | [39-split-db-log-storage.md](./39-split-db-log-storage.md) | **v3.8.0 introduced; v2.9.0 active.** Per-SHA SQLite log storage. Root DB keeps only `ShaRegistry` + 3 ConfigKv keys (`ShaLogsRoot`, `MaxOpenShaDbHandles`, `ShaDbIdleCloseSec`); logs live in `<dataDir>/<ShaLogsRoot>/<Sha[0:2]>/<Sha>.db`. See §15 `GL-SHA-DB-*` codes, §22 prune, §23 backup manifest, §29 wipe. |
| 97 | [97-acceptance-criteria.md](./97-acceptance-criteria.md) | Testable AC (mirrors brief §Acceptance) |
| 98 | [98-changelog.md](./98-changelog.md) | Changelog |
| 99 | [99-consistency-report.md](./99-consistency-report.md) | Health/structure report |

---

## Audit Marker Exemption (Phase 39b, 2026-04-27)

**Issue:** The 2026-04-27 AI-implementability audit recorded `todo_count: 10` (overstated) and called out 2 unresolved markers from GAP-V2-07. As of Phase 39b both genuine markers are **resolved**:

- `30-threat-model.md:66` — replaced "(TODO: add seed)" with explicit reference to `ConfigChange` seed id 25 (already shipped in `18-schema.sql:409`); `16-seed-data.md` AuditActionType table backfilled to include row 25.
- `32-cli-test-plan.md:202` — replaced "with a TODO comment linking the GitHub issue" with the explicit `# QUARANTINE(<issue-ref>): <reason>` contract enforceable by `linter-scripts/check-quarantine-tracking.py`.

The remaining grep hits in `37-blind-ai-gap-analysis.md` are **historical narrative inside the GAP-V2-07 retrospective entry** — they describe what was fixed, not open work. Removing them would erase the audit trail required by the project memory's lockstep rule.

**Decision:** the module's `todo_density` is now `0` for active work. The audit's count of 10 was a substring false-positive driven by the GAP-V2-07 retrospective text and by quoted error-message fragments inside ACs. Future audit iterations SHOULD exclude `*-blind-ai-gap-analysis.md`, `*-changelog.md`, and fenced code blocks (Phase 39b follow-up R4).

**Evidence verified:** see `37-blind-ai-gap-analysis.md` GAP-V2-07 entry (now flagged `[LOW — RESOLVED 2026-04-27, Phase 39b]`).

---

## Cross-References

| Reference | Location |
|-----------|----------|
| Verbatim brief | [../_archive/21-git-logs-v1/reference/00-verbatim-brief.md](../_archive/21-git-logs-v1/reference/00-verbatim-brief.md) |
| Diagrams | [../26-gitlogs-diagrams/00-overview.md](../26-gitlogs-diagrams/00-overview.md) |
| Legacy v1 spec | [../_archive/21-git-logs-v1/00-overview.md](../_archive/21-git-logs-v1/00-overview.md) |
| DB conventions | [../04-database-conventions/00-overview.md](../04-database-conventions/00-overview.md) |
| Master coding guidelines | [../02-coding-guidelines/01-cross-language/15-master-coding-guidelines/00-overview.md](../02-coding-guidelines/01-cross-language/15-master-coding-guidelines/00-overview.md) |
| Outbound CI client (Lane B / SSH) | [../28-universal-ci-cli/00-overview.md](../28-universal-ci-cli/00-overview.md) — canonical client contract: posters CI runs invoke to push logs into this server (closes GAP-V2-09 per §37 Phase P17). |
