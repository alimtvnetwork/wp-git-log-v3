# Git Logs v2 — Spec Overview

**Version:** 2.8.4  
**Updated:** 2026-04-25  
**Status:** Draft  
**AI Confidence:** Production-Ready  
**Ambiguity:** Low  
**Supersedes:** `spec/21-git-logs/` (legacy v1, all 10 files banner-deprecated in v2.7.1)

---

## ⚠ Open Question (only blocker)

**§07 App identity fields** — awaiting user confirmation: should `App` gain optional `Environment`, `Platform`, or `OwnerEmail` columns? Current locked set: `AppName`, `AppSlug`, `Description`, `ProfileId`, `AppStatusId`. Until answered, the v2.8.x cycle stays open. All other sections are frozen.

---

## Origin

This module is the authoritative rewrite of the Git Logs WordPress plugin spec, derived from the verbatim brief at [`../21-git-logs/reference/00-verbatim-brief.md`](../21-git-logs/reference/00-verbatim-brief.md). Where v1 (folder 21) and v2 (folder 22) conflict, **v2 wins**.

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
| 09–13 | *(intentional gap — locked)* | Content distributed across §05 (rate limit), §16/§18 (seeds), §30 R3 (encryption-deferred), §31 (SSH-key auth), legacy folder 21 (v1↔v2 mapping). Do **not** author standalone 09–13 files. |
| 14 | [14-endpoint-examples.md](./14-endpoint-examples.md) | Curl + JSON samples for all 10 endpoints |
| 15 | [15-error-codes.md](./15-error-codes.md) | Unified `GL-*` error catalog (37 runtime + 5 release-time codes) |
| 16 | [16-test-plan.md](./16-test-plan.md) | **Superseded** — redirects to §32–§35 |
| 17 | [17-openapi.yaml](./17-openapi.yaml) | OpenAPI 3.1 machine-readable spec (8 paths, 10 logical endpoints via `?q=` collapse) |
| 18 | [18-schema.sql](./18-schema.sql) | Verbatim DDL with seeds (25 AuditActionType, 10 ConfigKv defaults, 4 MigrationState markers) |
| 19 | [19-permission-matrix.md](./19-permission-matrix.md) | Role × Permission × Screen audit grid |
| 20 | [20-observability.md](./20-observability.md) | Site Health card, metrics endpoint, counters |
| 21 | [21-i18n.md](./21-i18n.md) | Text-domain rules, translatable scope, RTL/CI |
| 22 | [22-retention-and-pruning.md](./22-retention-and-pruning.md) | `wp git-logs prune` command + eligibility rules |
| 23 | [23-backup-restore.md](./23-backup-restore.md) | SQLite Online Backup + manifest + restore validation |
| 24 | [24-multisite.md](./24-multisite.md) | Per-site vs network behavior |
| 25 | [25-headless-auth-notes.md](./25-headless-auth-notes.md) | Headless WP + JWT/OAuth supported combos |
| 26 | [26-readme-and-screenshots.md](./26-readme-and-screenshots.md) | WP.org `readme.txt` + screenshot inventory |
| 27 | [27-wp-cli-reference.md](./27-wp-cli-reference.md) | Consolidated `wp git-logs *` subcommand catalog |
| 28 | [28-example-github-actions.md](./28-example-github-actions.md) | Drop-in workflow YAML (SSH primary + TempToken legacy tab) |
| 29 | [29-uninstall-policy.md](./29-uninstall-policy.md) | DB retention modes on plugin removal |
| 30 | [30-threat-model.md](./30-threat-model.md) | STRIDE pass over the v2 attack surface |
| 31 | [31-ssh-key-auth.md](./31-ssh-key-auth.md) | SSH-key Lane B sub-mode (preferred from v2.7.0) |
| 32 | [32-cli-test-plan.md](./32-cli-test-plan.md) | Seven-stage CLI test plan |
| 33 | [33-bats-test-skeleton.md](./33-bats-test-skeleton.md) | Bats CLI smoke skeleton |
| 34 | [34-phpunit-test-skeleton.md](./34-phpunit-test-skeleton.md) | PHPUnit unit skeleton (in-memory SQLite) |
| 35 | [35-reference-ci-yml.md](./35-reference-ci-yml.md) | Reference `ci.yml` (12-job full matrix) |
| 36 | [36-release-checklist.md](./36-release-checklist.md) | Semver gates, tag hygiene, release-day procedure |
| 37 | [37-seed-data.md](./37-seed-data.md) | Human-readable seed catalog (counterpart to §18 DDL) |
| 97 | [97-acceptance-criteria.md](./97-acceptance-criteria.md) | Testable AC-01..AC-48 |
| 98 | [98-changelog.md](./98-changelog.md) | Changelog |
| 99 | [99-consistency-report.md](./99-consistency-report.md) | Health/structure report |

---

## Cross-References

| Reference | Location |
|-----------|----------|
| Verbatim brief | [../21-git-logs/reference/00-verbatim-brief.md](../21-git-logs/reference/00-verbatim-brief.md) |
| Diagrams | [../26-gitlogs-diagrams/00-overview.md](../26-gitlogs-diagrams/00-overview.md) |
| Legacy v1 spec | [../21-git-logs/00-overview.md](../21-git-logs/00-overview.md) |
| DB conventions | [../04-database-conventions/00-overview.md](../04-database-conventions/00-overview.md) |
| Master coding guidelines | [../02-coding-guidelines/01-cross-language/15-master-coding-guidelines/00-overview.md](../02-coding-guidelines/01-cross-language/15-master-coding-guidelines/00-overview.md) |
