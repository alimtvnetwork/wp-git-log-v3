> ⚠️ **DEPRECATED — Legacy v1 Spec (folder 21)**  
> This document is preserved for historical reference only. **Do not implement against it.**  
> The active specification is **v2** in [`spec/22-git-logs-v2/`](../22-git-logs-v2/00-overview.md) (SQLite, no JWT, SSH-key auth).  
> See [`spec/22-git-logs-v2/00-overview.md`](../22-git-logs-v2/00-overview.md) for the current canonical source.  
> Deprecated: 2026-04-25

---

# Git Logs WordPress Plugin

**Version:** 1.0.0  
**Updated:** 2026-04-24  
**Status:** Draft  
**AI Confidence:** Production-Ready  
**Ambiguity:** Low

---

## Overview

`git-logs` is a WordPress plugin that ingests, stores, and exposes CI/CD logs from GitHub repositories. It manages its own internal users (decoupled from `wp_users`), issues plugin-scoped tokens, signs JWTs (RS256), and exposes a versioned REST namespace at `/wp-json/git-logs/v1`. The plugin enforces a per-repo allowlist (with version-wildcard matching such as `repo`, `repo-v2`, `repo-v100`) for unauthenticated log-push, and gates retrieval/management endpoints behind JWT or WordPress authentication. Every endpoint hit and every transaction is recorded in an immutable audit trail; no error is ever swallowed.

This module contains the complete implementation specification: domain glossary, database schema (PascalCase), admin UI, REST API, auth flows (JWT + WP bridge), log-push and retrieval flows, allowlist/wildcard matching, error management, logging strategy, applied coding guidelines, acceptance criteria, and a blind-audit checklist for downstream AI implementation.

---

## Keywords

`wordpress-plugin` · `rest-api` · `jwt` · `rs256` · `github` · `ci-cd-logs` · `audit-trail` · `allowlist` · `wildcard-matching` · `git-logs` · `error-management`

---

## Scoring

| Metric | Value |
|--------|-------|
| AI Confidence | Production-Ready |
| Ambiguity | Low |
| Health Score | 100/100 (A+) |

---

## Locked Decisions

| # | Decision | Value |
|---|----------|-------|
| 1 | JWT signing | RS256, plugin keypair (private key in WP option, public key at `/wp-json/git-logs/v1/.well-known/jwks.json`) |
| 2 | Access token TTL | 24 hours |
| 3 | Refresh token TTL | 7 days, rotating, revocable |
| 4 | Log retention | Indefinite (no rolling deletion in v1) |
| 5 | Log push payload cap | 1 MB per request |
| 6 | Rate limit | 60 requests/min per repository (token-bucket via WP transients) |
| 7 | `logSenderToken` scope | Per repository |
| 8 | WP auth bridge | Application Passwords AND cookie auth (both accepted) |
| 9 | Provider scope | GitHub only (GitLab reserved in `Provider` enum, not used) |
| 10 | Plugin slug | `git-logs` |
| 11 | DB table prefix | `{wp_prefix}gitlogs_` |
| 12 | REST namespace | `git-logs/v1` |

---

## Document Inventory

| # | File | Description |
|---|------|-------------|
| 00 | [00-overview.md](./00-overview.md) | This index — locked decisions, file inventory, cross-references |
| 01 | [01-glossary-and-enums.md](./01-glossary-and-enums.md) | Domain glossary and full enum catalog |
| 02 | [02-database-schema-and-erd.md](./02-database-schema-and-erd.md) | Tables, columns, FKs, indexes, ERD |
| 03 | [03-admin-ui.md](./03-admin-ui.md) | WP admin menu, screens, fields, validation |
| 04 | [04-rest-api-endpoints.md](./04-rest-api-endpoints.md) | REST endpoints with request/response schemas |
| 05 | [05-auth-jwt-flow.md](./05-auth-jwt-flow.md) | JWT issuance, verification, refresh, JWKS |
| 06 | [06-auth-wordpress-bridge.md](./06-auth-wordpress-bridge.md) | WordPress App Password / cookie bridge |
| 07 | [07-log-push-flow.md](./07-log-push-flow.md) | CI/CD push flow, envelope JWT, rate limit |
| 08 | [08-allowlist-and-wildcard-matching.md](./08-allowlist-and-wildcard-matching.md) | Allowlist resolver, version-wildcard regex |
| 09 | [09-log-retrieval-flow.md](./09-log-retrieval-flow.md) | Branch → pipeline → entries retrieval |
| 10 | [10-audit-trail.md](./10-audit-trail.md) | Audit trail schema, write rules, query API |
| 11 | [11-error-management.md](./11-error-management.md) | Error classes, no-swallow rules, response envelope |
| 12 | [12-logging-strategy.md](./12-logging-strategy.md) | Internal diagnostic logs (separate from CI logs) |
| 13 | [13-coding-guidelines-applied.md](./13-coding-guidelines-applied.md) | Master guidelines applied to PHP/WP context |
| 14 | [14-acceptance-criteria.md](./14-acceptance-criteria.md) | Testable acceptance criteria |
| 15 | [15-blind-audit-checklist.md](./15-blind-audit-checklist.md) | Self-verification checklist for downstream AI |
| 17 | [17-spec-consistency-checklist.md](./17-spec-consistency-checklist.md) | Spec consistency checklist vs coding guidelines, naming, booleans/enums, split-DB, seedable config |
| 97 | [97-acceptance-criteria.md](./97-acceptance-criteria.md) | Canonical AC index (mirrors 14) |
| 98 | [98-changelog.md](./98-changelog.md) | Chronological changelog |
| 99 | [99-consistency-report.md](./99-consistency-report.md) | Structural health report |

---

## Cross-References

| Reference | Location |
|-----------|----------|
| Spec authoring guide | [../01-spec-authoring-guide/00-overview.md](../01-spec-authoring-guide/00-overview.md) |
| Master coding guidelines | [../02-coding-guidelines/01-cross-language/15-master-coding-guidelines/00-overview.md](../02-coding-guidelines/01-cross-language/15-master-coding-guidelines/00-overview.md) |
| PHP standards reference | [../02-coding-guidelines/04-php/07-php-standards-reference/00-overview.md](../02-coding-guidelines/04-php/07-php-standards-reference/00-overview.md) |
| Error management | [../03-error-manage/00-overview.md](../03-error-manage/00-overview.md) |
| Database conventions | [../04-database-conventions/00-overview.md](../04-database-conventions/00-overview.md) |
| WP plugin how-to | [../18-wp-plugin-how-to/00-overview.md](../18-wp-plugin-how-to/00-overview.md) |

---

## Continuation Marker

If you have any questions or confusion, feel free to ask. If you are creating multiple tasks — especially bigger ones — do it in a way so that when we say `next`, you continue with the remaining tasks.
