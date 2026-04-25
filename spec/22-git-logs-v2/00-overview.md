# Git Logs v2 — Spec Overview

**Version:** 2.7.0  
**Updated:** 2026-04-25  
**Status:** Draft  
**AI Confidence:** Production-Ready  
**Ambiguity:** Low  
**Supersedes:** `spec/21-git-logs/` (legacy v1 retained for history)

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
| 09 | [09-seed-data.md](./09-seed-data.md) | Lookup-table rows, RolePermission seeds, ConfigKv defaults |
| 10 | [10-rate-limit-and-payload.md](./10-rate-limit-and-payload.md) | Per-Profile token bucket, payload caps |
| 11 | [11-encryption-deferred-plan.md](./11-encryption-deferred-plan.md) | v3 encryption-at-rest blueprint |
| 12 | [12-wp-plugin-scaffold.md](./12-wp-plugin-scaffold.md) | PHP file tree mapping spec → code |
| 13 | [13-v1-vs-v2-mapping.md](./13-v1-vs-v2-mapping.md) | Side-by-side v1 ↔ v2 reference |
| 14 | [14-endpoint-examples.md](./14-endpoint-examples.md) | Curl + JSON samples for all 10 endpoints |
| 15 | [15-error-codes.md](./15-error-codes.md) | Unified `GL-*` error catalog |
| 16 | [16-test-plan.md](./16-test-plan.md) | Unit + Integration test scope |
| 17 | [17-openapi.yaml](./17-openapi.yaml) | OpenAPI 3.1 machine-readable spec for all 10 endpoints |
| 18 | [18-schema.sql](./18-schema.sql) | Verbatim DDL for V2_0_0 migration |
| 19 | [19-permission-matrix.md](./19-permission-matrix.md) | Role × Permission × Screen audit grid |
| 20 | [20-observability.md](./20-observability.md) | Site Health card, metrics endpoint, counters |
| 21 | [21-i18n.md](./21-i18n.md) | Text-domain rules, translatable scope, RTL/CI |
| 22 | [22-retention-and-pruning.md](./22-retention-and-pruning.md) | `wp git-logs prune` command + eligibility rules |
| 23 | [23-backup-restore.md](./23-backup-restore.md) | SQLite Online Backup + manifest + restore validation |
| 24 | [24-multisite.md](./24-multisite.md) | Per-site vs network behavior |
| 25 | [25-headless-auth-notes.md](./25-headless-auth-notes.md) | Headless WP + JWT/OAuth supported combos |
| 26 | [26-readme-and-screenshots.md](./26-readme-and-screenshots.md) | WP.org `readme.txt` + screenshot inventory |
| 27 | [27-wp-cli-reference.md](./27-wp-cli-reference.md) | Consolidated `wp git-logs *` subcommand catalog |
| 28 | [28-example-github-actions.md](./28-example-github-actions.md) | Drop-in workflow YAML for Lane B push + fixed |
| 29 | [29-uninstall-policy.md](./29-uninstall-policy.md) | DB retention modes on plugin removal |
| 30 | [30-threat-model.md](./30-threat-model.md) | STRIDE pass over the v2 attack surface |
| 97 | [97-acceptance-criteria.md](./97-acceptance-criteria.md) | Testable AC (mirrors brief §Acceptance) |
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
