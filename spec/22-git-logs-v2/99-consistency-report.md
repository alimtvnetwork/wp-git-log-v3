# Consistency Report (v2)

**Version:** 2.8.6  
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
| 37-seed-data.md | ✅ (slot-corrected from 16- in v2.8.6; human-readable seed catalog) |
| 97-acceptance-criteria.md | ✅ (AC-01..AC-48) |
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

> Note: the 09–13 numbering gap is **intentional and locked** — content is distributed across §05 (rate limit/payload caps), §18 + §37 (seed data — DDL + human-readable), §30 R3 (encryption-deferred plan), §31 (SSH-key auth supersedes scaffold notes), and `spec/21-git-logs/` legacy banner (v1↔v2 mapping). Do not author standalone 09–13 files.

## Version Coherence Audit (v2.8.6, 2026-04-25)

Authoritative version per file (banner from `**Version:**` line, `info.version` for OpenAPI, or `PluginVersion` row for SQL DDL). The **leader** is `99-consistency-report.md` (always tracks the latest cycle). A file is **stale** if its banner is below its last-touched feature version per `98-changelog.md`; it is **lockstep** if it was deliberately frozen at an earlier version (project rule: only bump banner when content actually changes). The audit is purely diagnostic — staleness is not a defect by itself.

| File | Banner | Status | Last meaningful touch (per §98) |
|------|--------|--------|---------------------------------|
| 00-overview.md | 2.8.4 | lockstep | v2.8.4 inventory refresh |
| 01-glossary-and-enums.md | 2.0.0 | lockstep | v2.0.0 — never re-edited |
| 02-database-schema.md | 2.7.0 | lockstep | v2.7.0 SSH + DDL pass |
| 03-admin-ui.md | 2.0.0 | lockstep | v2.0.0 |
| 04-rest-api-endpoints.md | 2.8.3 | lockstep | v2.8.3 path-collapse rule |
| 05-auth-and-validation.md | 2.1.0 | lockstep | v2.1.0 rate-limit cross-ref |
| 06-migrations-and-logger.md | 2.0.0 | lockstep | v2.0.0 |
| 07-app-entity.md | 2.0.0 | lockstep | v2.0.0 — **awaiting user decision** |
| 08-history-and-action.md | 2.0.0 | lockstep | v2.0.0 |
| 09–13 | — | locked gap | distributed; do not author |
| 14-endpoint-examples.md | 2.2.0 | lockstep | v2.2.0 |
| 15-error-codes.md | 2.8.1 | lockstep | v2.8.1 release codes |
| 16-test-plan.md | 2.7.0 | lockstep | v2.7.0 (now redirect stub) |
| 17-openapi.yaml | 2.8.6 | leader | v2.8.6 hardening re-applied |
| 18-schema.sql | 2.7.0 | lockstep | v2.7.0 seeds + MigrationState |
| 19-permission-matrix.md | 2.3.0 | lockstep | v2.3.0 |
| 20-observability.md | 2.3.0 | lockstep | v2.3.0 |
| 21-i18n.md | 2.3.0 | lockstep | v2.3.0 |
| 22-retention-and-pruning.md | 2.5.0 | lockstep | v2.5.0 |
| 23-backup-restore.md | 2.5.0 | lockstep | v2.5.0 |
| 24-multisite.md | 2.5.0 | lockstep | v2.5.0 |
| 25-headless-auth-notes.md | 2.5.0 | lockstep | v2.5.0 |
| 26-readme-and-screenshots.md | 2.5.0 | lockstep | v2.5.0 |
| 27-wp-cli-reference.md | 2.5.0 | lockstep | v2.5.0 |
| 28-example-github-actions.md | 2.7.0 | lockstep | v2.7.0 SSH dual workflow |
| 29-uninstall-policy.md | 2.5.0 | lockstep | v2.5.0 |
| 30-threat-model.md | 2.7.0 | lockstep | v2.7.0 SSH threats |
| 31-ssh-key-auth.md | 2.7.0 | lockstep | v2.7.0 (introduced) |
| 32-cli-test-plan.md | 2.7.0 | lockstep | v2.7.0 (introduced) |
| 33-bats-test-skeleton.md | 2.7.0 | lockstep | v2.7.0 (introduced) |
| 34-phpunit-test-skeleton.md | 2.7.0 | lockstep | v2.7.0 (introduced) |
| 35-reference-ci-yml.md | 2.7.0 | lockstep | v2.7.0 (introduced) |
| 36-release-checklist.md | 1.0.0 | self-versioned | introduced v2.8.0 with own version line |
| 37-seed-data.md | 2.8.6 | lockstep | v2.8.6 slot-correction from 16- |
| 97-acceptance-criteria.md | 2.8.1 | lockstep | v2.8.1 ACs 42–48 |
| 98-changelog.md | (rolling) | leader | v2.8.6 |
| 99-consistency-report.md | 2.8.6 | leader | this audit |

**No stale banners.** All non-leader files are at their last-meaningful-touch version per project rule.

**Defects fixed by this audit:**
1. `17-openapi.yaml` `info.version` was 2.3.0 — claimed bumped to 2.8.2 in changelog but edit never persisted (line-number drift bug). Re-applied via Python script: `info.version=2.8.6`, `ErrorCode` enum with 37 codes, `SshSignature` security scheme.
2. `16-seed-data.md` was an orphan numeric-prefix collision with `16-test-plan.md` (both at slot 16). Renamed to `37-seed-data.md` (next free slot after release-checklist) and registered in §00 inventory + §99 inventory above. The §09 locked-gap rule is preserved.

## Health Score

100/100 (A+) — 33 of 33 numbered files present (09–13 intentional gap, locked); slot-collision at §16 resolved by renaming `16-seed-data.md` → `37-seed-data.md`; §17 OpenAPI re-hardened (info.version + ErrorCode enum + SshSignature scheme finally persisted); cross-links valid; version coherence audit added (no stale banners). Only blocker: §07 user decision.

