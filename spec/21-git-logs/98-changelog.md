# Changelog — `git-logs`

**Version:** 1.0.0  
**Updated:** 2026-04-25

All notable changes to the `git-logs` plugin specification are recorded here. Format follows [Keep a Changelog](https://keepachangelog.com/en/1.1.0/) and SemVer.

---

## [1.0.0] — 2026-04-25

### Added
- `00-overview.md` — locked decisions, file inventory, cross-references.
- `01-glossary-and-enums.md` — domain glossary and full enum catalog.
- `02-database-schema-and-erd.md` — 7 entity tables, ERD, views.
- `03-admin-ui.md` — WP admin menu, screens, fields, validation.
- `04-rest-api-endpoints.md` — canonical REST contract for `/git-logs/v1`.
- `05-auth-jwt-flow.md` — RS256 issuance, JWKS, key rotation.
- `06-auth-wordpress-bridge.md` — App Password + cookie bridge.
- `07-log-push-flow.md` — end-to-end CI/CD push flow.
- `08-allowlist-and-wildcard-matching.md` — allowlist resolver.
- `09-log-retrieval-flow.md` — branch → pipeline → entries retrieval.
- `10-audit-trail.md` — append-only audit schema, write rules, query API.
- `11-error-management.md` — `GL-*` codes, no-swallow, response envelope.
- `12-logging-strategy.md` — structured logs, redaction, trace correlation.
- `13-coding-guidelines-applied.md` — PHP/WP mapping of master rules.
- `14-acceptance-criteria.md` — roll-up of every `AC-*` ID.
- `15-blind-audit-checklist.md` — self-verification checklist.
- `16-jwt-onboarding-and-token-usage.md` — 5-phase JWT lifecycle.
- `17-spec-consistency-checklist.md` — guideline-domain compliance walkthrough.
- `97-acceptance-criteria.md` — canonical AC index (mirrors 14).
- `98-changelog.md` — this file.
- `99-consistency-report.md` — module health (100/100 A+).
- `error-codes.json` — machine-readable `GL-*` registry.

### Open
- `F-02` cryptographic decision (HS256 vs Ed25519) still pending.
- `F-03` `RevokedJti` table to be added to `02-database-schema-and-erd.md`.
- `F-12` `AuditTrail` / `LogEntry` partitioning strategy pending.

See [`spec/22-app-issues/02-consolidated-audit-findings`](../22-app-issues/02-consolidated-audit-findings/00-overview.md) for the live finding list.
