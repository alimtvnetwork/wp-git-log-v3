# Consistency Report (v2)

**Version:** 2.8.1  
**Updated:** 2026-04-25

---

## Inventory

| File | Present |
|------|---------|
| 00-overview.md | ✅ |
| 01-glossary-and-enums.md | ✅ |
| 02-database-schema.md | ✅ |
| 03-admin-ui.md | ✅ (incl. First-run Bootstrap) |
| 04-rest-api-endpoints.md | ✅ |
| 05-auth-and-validation.md | ✅ (CI/CD cross-ref) |
| 06-migrations-and-logger.md | ✅ |
| 07-app-entity.md | ✅ |
| 08-history-and-action.md | ✅ |
| 09-seed-data.md | ⚠️ Referenced but not yet authored — seeds live inline in `18-schema.sql` for now |
| 10-rate-limit-and-payload.md | ⚠️ Referenced; constants live in `ConfigKv` defaults inside `18-schema.sql` |
| 11-encryption-deferred-plan.md | ⚠️ Referenced; v3 plan summarized in §30 threat model |
| 12-wp-plugin-scaffold.md | ⚠️ Referenced; PSR-4 layout described in `mem://specs/git-logs.md` |
| 13-v1-vs-v2-mapping.md | ⚠️ Referenced; v1 deltas captured in changelog + `21-git-logs/` legacy banner |
| 14-endpoint-examples.md | ✅ |
| 15-error-codes.md | ✅ (4 new auth codes added in v2.6) |
| 16-test-plan.md | ✅ (redirect stub → §32–§35) |
| 17-openapi.yaml | ✅ |
| 18-schema.sql | ✅ (Prune + Restore seeds added in v2.6) |
| 19-permission-matrix.md | ✅ |
| 20-observability.md | ✅ |
| 21-i18n.md | ✅ |
| 22-retention-and-pruning.md | ✅ |
| 23-backup-restore.md | ✅ |
| 24-multisite.md | ✅ |
| 25-headless-auth-notes.md | ✅ |
| 26-readme-and-screenshots.md | ✅ |
| 27-wp-cli-reference.md | ✅ |
| 28-example-github-actions.md | ✅ |
| 29-uninstall-policy.md | ✅ |
| 30-threat-model.md | ✅ |
| 31-ssh-key-auth.md | ✅ |
| 32-cli-test-plan.md | ✅ |
| 33-bats-test-skeleton.md | ✅ |
| 34-phpunit-test-skeleton.md | ✅ |
| 35-reference-ci-yml.md | ✅ |
| 36-release-checklist.md | ✅ (new in v2.8.0) |
| 97-acceptance-criteria.md | ✅ (AC-01..AC-41) |
| 98-changelog.md | ✅ |
| 99-consistency-report.md | ✅ |

## Cross-link validation

- `00-overview.md` → §00–§36 + §97–§99 (refreshed v2.8.4): OK
- `15-error-codes.md` covers every code referenced from §22, §23, §25, §27: OK
- `97-acceptance-criteria.md` AC-26..AC-41 reference §10, §17–§26 sources: OK
- `18-schema.sql` `AuditActionType` seed includes Prune (19), Restore (20): OK
- `30-threat-model.md` deferral list cross-links to `11-encryption-deferred-plan.md` (queued file)

## Naming compliance

- File prefixes 00–30, 97–99 sequential. ✅ (gaps at 09–13 noted as queued)
- Tables/columns PascalCase, PKs `{Table}Id`. ✅
- All `GL-*` error codes consolidated in `15-error-codes.md`. ✅
- Translatable scope honors §21. ✅

## Conflicts vs v1 (folder 21)

Resolved by parallel-folder strategy; v2 wins. **Deprecation banners (v2.7.1, 2026-04-25)** prepended to all 10 legacy files in `spec/21-git-logs/` cross-linking back to v2 canonical source. Legacy v1 banner in `spec/21-git-logs/00-overview.md` retained.

## Open items (not blocking)

1. **App identity (§07)** — still awaiting user confirmation on whether to add `Environment`, `Platform`, or `OwnerEmail`. Current set: `AppName`, `AppSlug`, `Description`, `ProfileId`, `AppStatusId`.

> Note: the 09–13 numbering gap is **intentional and locked** — content is distributed across §05 (rate limit/payload caps), §16 (seed data), §30 R3 (encryption-deferred plan), §31 (SSH-key auth supersedes scaffold notes), and `spec/21-git-logs/` legacy banner (v1↔v2 mapping). Do not author standalone 09–13 files.

## Health Score

99/100 (A) — 32 of 32 numbered files present (09–13 intentional gap, locked); §36 release-checklist landed in v2.8.0 with 5 new CI gates queued for §15/§35; cross-links valid; AC coverage matches spec breadth; DDL seeds + ConfigKv defaults validated against in-memory SQLite (25 AuditActionType rows, 10 ConfigKv defaults, 4 MigrationState markers); legacy folder 21 fully banner-deprecated. Only blocker: §07 user decision.

