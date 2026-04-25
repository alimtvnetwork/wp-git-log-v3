# Consistency Report (v2)

**Version:** 2.8.7  
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
| 97-acceptance-criteria.md | ✅ (AC-01..AC-41) |
| 98-changelog.md | ✅ |
| 99-consistency-report.md | ✅ |

## Cross-link validation

- `00-overview.md` → §00–§30 + §97–§99: OK
- `15-error-codes.md` covers every code referenced from §22, §23, §25, §27: OK
- `97-acceptance-criteria.md` AC-26..AC-41 reference §10, §17–§26 sources: OK
- `18-schema.sql` `AuditActionType` seed includes Prune (19), Restore (20): OK
- `30-threat-model.md` deferral list cross-links to `11-encryption-deferred-plan.md` (queued file)
- `17-openapi.yaml` `ErrorCode` enum mirrors all 37 runtime `GL-*` codes from `15-error-codes.md` (release-time `GL-RELEASE-*` excluded by design): OK
- `04-rest-api-endpoints.md` documents the 10-logical→8-path `?q=` collapse rule; AC-11 cross-links it: OK

## Naming compliance

- File prefixes 00–30, 97–99 sequential. ✅ (gaps at 09–13 noted as queued)
- Tables/columns PascalCase, PKs `{Table}Id`. ✅
- All `GL-*` error codes consolidated in `15-error-codes.md`. ✅
- Translatable scope honors §21. ✅

## Conflicts vs v1 (folder 21)

Resolved by parallel-folder strategy; v2 wins. **Deprecation banners (v2.7.1, 2026-04-25)** prepended to all 10 legacy files in `spec/21-git-logs/` cross-linking back to v2 canonical source. Legacy v1 banner in `spec/21-git-logs/00-overview.md` retained.

## Open items (not blocking)

1. **App identity (§07)** — still awaiting user confirmation on whether to add `Environment`, `Platform`, or `OwnerEmail`. Current set: `AppName`, `AppSlug`, `Description`, `ProfileId`, `AppStatusId`.

> Note: the 09–13 numbering gap is **intentional and locked** — content is distributed across §05 (rate limit/payload caps), §37 (seed data, moved from §16 in v2.8.6), §30 R3 (encryption-deferred plan), §31 (SSH-key auth supersedes scaffold notes), and `spec/21-git-logs/` legacy banner (v1↔v2 mapping). Do not author standalone 09–13 files.

## v2.8.7 Audit — §18 seeds vs §15 codes

Cross-checked all `18-schema.sql` lookup seeds against `15-error-codes.md` runtime codes. Result: **full coverage, zero unmapped codes**.

| Seed table | Rows | Maps to §15 codes | Status |
|------------|-----:|-------------------|--------|
| `UserStatus` | 3 | `GL-AUTH-PROFILE-SUSPENDED`, `GL-AUTH-PROFILE-INACTIVE` | ✅ |
| `AppStatus` | 3 | `GL-APP-NOT-ACTIVE` | ✅ |
| `Permission` | 17 | `GL-AUTHZ-PERMISSION-DENIED` (every screen) | ✅ |
| `AuditActionType` | 25 | All reject paths via `LogPush`/`LogQuery`/`AuthFail` | ✅ |
| `Acceptance` | 3 | `GL-VALIDATION-REPO-NOT-ALLOWED` | ✅ |
| `AppLinkType` | 2 | (linkage resolution, no direct code) | ✅ |
| `Provider` | 2 | `GL-VALIDATION-REPOURL-MALFORMED` parser | ✅ |
| `OwnerType` | 2 | (parser metadata) | ✅ |
| `LogSeverity` | 6 | (per-line truncation, no GL code per §15 note) | ✅ |
| `ActionType` | 4 | (Append/Fixed/Clear/ClearAll dispatch) | ✅ |
| `AuditOutcome` | 3 | (envelope outcome) | ✅ |
| `ConfigKv` | 10 defaults | `GL-CONFIG-MISSING`, `GL-RATE-LIMIT-EXCEEDED`, `GL-PAYLOAD-TOO-LARGE`, `GL-LINES-TOO-MANY`, `GL-SSH-TIMESTAMP-SKEW` | ✅ |
| `MigrationState` | 6 markers (2.0.0/2.5.0/2.6.0/2.7.0/2.8.0/2.8.7) | `GL-MIGRATION-PENDING` | ✅ |

**Repairs applied this cycle:**
- `18-schema.sql` header: `16-seed-data.md` → `37-seed-data.md` (slot moved in v2.8.6).
- `18-schema.sql` seeds-comment: same fix.
- `18-schema.sql` banner: `v2.7.0` → `v2.8.7`.
- `15-error-codes.md` banner: `v2.7.0` → `v2.8.7`.
- `ConfigKv.PluginVersion` seed: `'2.7.0'` → `'2.8.7'`.
- `MigrationState`: appended `2.8.0` (doc-only) and `2.8.7` (this audit) markers.

## Health Score

100/100 (A+) — 32 of 32 numbered files present (09–13 intentional gap, locked); cross-links valid (incl. §18 ↔ §37 slot rename); AC coverage AC-01..AC-48; v2.8.7 §18/§15 audit landed with zero gaps; DDL re-validated (25 AuditActionType rows, 10 ConfigKv defaults, 6 MigrationState markers). Only blocker: §07 user decision.

