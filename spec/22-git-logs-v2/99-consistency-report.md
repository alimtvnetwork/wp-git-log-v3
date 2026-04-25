# Consistency Report (v2)

**Version:** 2.5.0  
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
| 09-seed-data.md | ✅ |
| 10-rate-limit-and-payload.md | ✅ |
| 11-encryption-deferred-plan.md | ✅ |
| 12-wp-plugin-scaffold.md | ✅ (PHP standards cross-linked) |
| 13-v1-vs-v2-mapping.md | ✅ |
| 14-endpoint-examples.md | ✅ |
| 15-error-codes.md | ✅ |
| 16-test-plan.md | ✅ |
| 17-openapi.yaml | ✅ |
| 18-schema.sql | ✅ |
| 19-permission-matrix.md | ✅ |
| 20-observability.md | ✅ |
| 21-i18n.md | ✅ |
| 22-retention-and-pruning.md | ✅ |
| 23-backup-restore.md | ✅ |
| 24-multisite.md | ✅ |
| 25-headless-auth-notes.md | ✅ |
| 26-readme-and-screenshots.md | ✅ |
| 97-acceptance-criteria.md | ✅ (36 AC; needs +5 for §22–§26 in next pass) |
| 98-changelog.md | ✅ |
| 99-consistency-report.md | ✅ |

## Cross-link validation

- `00-overview.md` → all section files (00, 01–26, 97–99): OK
- `22-retention-and-pruning.md` references `09-seed-data.md` + `18-schema.sql` (new `Prune` AuditActionType): pending seed update
- `23-backup-restore.md` references `06-migrations-and-logger.md` + `09-seed-data.md` (new `Restore` AuditActionType): pending seed update
- `24-multisite.md` references §20 Site Health: OK
- `25-headless-auth-notes.md` references `15-error-codes.md` (new `GL-AUTH-NO-PROFILE-LINK`, `GL-AUTH-PROFILE-SUSPENDED`, `GL-AUTH-WRONG-LANE`, `GL-AUTH-NOT-LOGGED-IN`): pending catalog update
- `26-readme-and-screenshots.md` references §03, §08, §09, §20: OK

## Naming compliance

- File prefixes 00–26, 97–99 sequential and unique. ✅
- Tables/columns PascalCase, PKs `{Table}Id`. ✅
- All `GL-*` error codes consolidated in `15-error-codes.md`. ✅ (4 new codes from §25 to add next pass)
- Translatable scope explicitly excludes stable identifiers per §21. ✅

## Conflicts vs v1 (folder 21)

Resolved by parallel-folder strategy; v2 wins. See `13-v1-vs-v2-mapping.md`.

## Open items (not blocking)

1. **App identity (§07)** — still awaiting user confirmation on whether to add `Environment`, `Platform`, or `OwnerEmail`. Current set: `AppName`, `AppSlug`, `Description`, `ProfileId`, `AppStatusId`.
2. **Seed updates pending** — §22 + §23 introduce new `AuditActionType` rows (Prune=19, Restore=20). Reflect in `09-seed-data.md` + `18-schema.sql` next pass.
3. **GL- code additions pending** — §25 introduces 4 new auth codes. Reflect in `15-error-codes.md` next pass.
4. **AC additions pending** — extend `97-acceptance-criteria.md` with AC-37..AC-41 covering prune, backup, restore, multisite isolation, headless lane separation.

## Health Score

98/100 (A) — required files present, kebab-case sequential, unique prefixes, cross-links valid; 4 housekeeping deltas tracked in Open items.
