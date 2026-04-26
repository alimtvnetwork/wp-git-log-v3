# Consistency Report (v2)

**Version:** 3.8.9  
**Updated:** 2026-04-26

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
| ~~21-i18n.md~~ | 🗑️ removed v3.7.8 — slot retired (i18n out of scope for v2; see audit row below) |
| 22-retention-and-pruning.md | ✅ |
| 23-backup-restore.md | ✅ |
| 24-multisite.md | ✅ |
| 25-headless-auth-notes.md | ✅ |
| 26-readme-and-screenshots.md | ✅ |
| 27-wp-cli-reference.md | ✅ |
| 28-example-github-actions.md | ✅ |
| 29-uninstall-policy.md | ✅ |
| 30-threat-model.md | ✅ |
| 36-why-v1-archived.md | ✅ (added 2026-04-25) |
| 37-blind-ai-gap-analysis.md | ✅ (added 2026-04-25) |
| 39-split-db-log-storage.md | ✅ (added v3.8.0 2026-04-26 — per-SHA SQLite storage spec) |
| 97-acceptance-criteria.md | ✅ (AC-01..AC-53; +AC-49..AC-53 in v3.8.0 for split-DB) |
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

Resolved by parallel-folder strategy; v2 wins. **Deprecation banners (v2.7.1, 2026-04-25)** prepended to all 10 legacy files in `spec/_archive/21-git-logs-v1/` cross-linking back to v2 canonical source. Legacy v1 banner in `spec/_archive/21-git-logs-v1/00-overview.md` retained.

## Open items (not blocking)

1. **App identity (§07)** — still awaiting user confirmation on whether to add `Environment`, `Platform`, or `OwnerEmail`. Current set: `AppName`, `AppSlug`, `Description`, `ProfileId`, `AppStatusId`.

> Note: the 09–13 numbering gap is **intentional and locked** — content is distributed across §05 (rate limit/payload caps), §37 (seed data, moved from §16 in v2.8.6), §30 R3 (encryption-deferred plan), §31 (SSH-key auth supersedes scaffold notes), and `spec/_archive/21-git-logs-v1/` legacy banner (v1↔v2 mapping). Do not author standalone 09–13 files.

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
| ~~`OwnerType`~~ | 0 | (retired v3.8.1 — replaced by `GitProfile.IsOrganization` boolean; tombstoned in §16) | 🗑️ |
| `LogSeverity` | 6 | (per-line truncation, no GL code per §15 note) | ✅ |
| `PipelineActionType` | 4 | (Append/Fixed/Clear/ClearAll dispatch — renamed from `ActionType` in v3.8.2) | ✅ |
| `SystemEventType` | 16 | (NEW v3.8.2 — feeds `SystemEvent` business-event feed; no GL code, internal) | ✅ |
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

## v3.8.0 Audit — Domain-model overhaul (user diagram review)

User reviewed `26-gitlogs-diagrams/02-domain-design.mmd` + `01-er-diagram.mmd` and raised four concerns:

| # | Concern | Resolution |
|---|---------|------------|
| 1 | "Why is `RepoVersionId` inside `Action`? Naming is the issue." | Renamed `Action` → `PipelineAction` + `ActionType` → `PipelineActionType`. Documented scope (RepoVersion + Pipeline only) in §08. |
| 2 | "History should also cover non-Git events (ProfileCreated, KeyRevoked, …)" | Introduced `SystemEvent` table with 16-value `SystemEventType` lookup; loose polymorphic (`TargetType` + `TargetId`, no FK CHECK). Four-table model documented in §08. |
| 3 | "Where are logs streamed? Use the split-DB pattern, per-SHA SQLite." | Created §39. `LogEntry`/`ErrorLogEntry` deleted from root DB; root DB keeps only `ShaRegistry` (registry + rolled-up summary). Per-SHA file at `logs/<RepoVersionId>/<GitSha256>.sqlite` with semantic tables (`PipelineRun`, `StatusSnapshot`) that answer last-status / failure-count / pipelines-failing in O(1). |
| 4 | "`GitProfile` doesn't mark organization vs user — needs `IsOrganization` checkbox." | Added `IsOrganization INTEGER 0/1` column on `GitProfile`; retired `OwnerType` lookup. Drives URL canonicalization + admin-UI checkbox. **§18 DDL + §03 UI + §16 seed tombstone landed v3.8.1.** |

Files touched in this cycle: `00-overview.md` (+§39 row), `01-glossary-and-enums.md` (OwnerType retired, PipelineActionType renamed, SystemEventType added, ShaRegistry+SystemEvent+PipelineAction terms), `02-database-schema.md` (GitProfile.IsOrganization, lookup list updated, LogEntry+ErrorLogEntry removed, ShaRegistry added, History rename, PipelineAction rename, SystemEvent added), `08-history-and-action.md` (4-table model), `97-acceptance-criteria.md` (AC-07 + AC-21 reworded, AC-49–AC-53 added), `98-changelog.md`, `99-consistency-report.md`, `26-gitlogs-diagrams/01-er-diagram.mmd` (regenerated with split boundary), `26-gitlogs-diagrams/02-domain-design.mmd` (regenerated with subgraphs).

**Queued (NOT in this commit, tracked in `mem://specs/git-logs.md` queued decisions):**
- §18 `18-schema.sql`: ~~drop `OwnerType` table+seed~~ ✅ landed v3.8.1; ~~add `GitProfile.IsOrganization`~~ ✅ landed v3.8.1; ~~rename `Action`→`PipelineAction` + `ActionType`→`PipelineActionType`~~ ✅ landed v3.8.2; ~~add `SystemEvent`+`SystemEventType` tables + 16 seeds~~ ✅ landed v3.8.2; ~~drop `LogEntry`+`ErrorLogEntry`, add `ShaRegistry` table, add `MaxOpenShaDbHandles`/`ShaDbIdleCloseSec`/`ShaLogsRoot` `ConfigKv` defaults~~ ✅ landed v3.8.3 (Phase 2); add 4 `GL-SHA-DB-*` codes to §15 (queued for Phase 3).
- §22 retention: prune walks `ShaRegistry` + deletes per-SHA files.
- §23 backup: manifest must list per-SHA file inventory + per-file row counts + sha256.
- §29 uninstall: Wipe mode deletes the `logs/` folder.
- ~~§03 admin-ui: add "Is organization" checkbox to GitProfile create/edit screen.~~ ✅ landed v3.8.1.
- §15 error-codes: add `GL-SHA-DB-CREATE-FAILED`, `GL-SHA-DB-OPEN-FAILED`, `GL-SHA-DB-CORRUPT`, `GL-SHA-DB-NOT-FOUND`.
- Per-SHA SVG re-render of `01-er-diagram.mmd` + `02-domain-design.mmd`.
- `26-gitlogs-diagrams/00-overview.md` banner bump v1.1.0 → v1.2.0 + inventory note for the new split-DB callouts.

## v3.8.1 Audit — Q1 IsOrganization lockstep

| File | Change |
|------|--------|
| `03-admin-ui.md` | Removed `OwnerType (derived)` row; added `Is organization` checkbox row bound to `GitProfile.IsOrganization`. Banner v2.0.0 → v2.1.0. |
| `16-seed-data.md` | `OwnerType` section converted to retirement tombstone (no seed rows). Banner v2.7.0 → v2.7.1. |
| `18-schema.sql` | `CREATE TABLE OwnerType` deleted; `GitProfile.OwnerTypeId` → `IsOrganization INTEGER NOT NULL DEFAULT 0 CHECK (IsOrganization IN (0,1))`; `OwnerType` seed deleted; `ConfigKv.PluginVersion` 2.8.7 → 2.8.8; banner v2.8.7 → v2.8.8. |
| `97-acceptance-criteria.md` | Added AC-54 (UI checkbox binding) + AC-55 (DDL constraint). Banner v3.8.0 → v3.8.1. |
| `98-changelog.md` | Added v3.8.1 row. |
| `99-consistency-report.md` | Tombstoned `OwnerType` seed-coverage row; flipped Q1 status in v3.8.0 audit table; this audit table added. Banner v3.8.0 → v3.8.1. |

## v3.8.2 Audit — Q2 PipelineAction rename + SystemEvent lockstep

| File | Change |
|------|--------|
| `18-schema.sql` | `CREATE TABLE ActionType` → `PipelineActionType`; `CREATE TABLE Action` → `PipelineAction` (PK rename, FK rename, added `RepoVersionId NOT NULL` + `ProfileId` FK + 2 indexes); `History.ActionTypeId` → `PipelineActionTypeId`; **NEW** `CREATE TABLE SystemEventType` lookup; **NEW** `CREATE TABLE SystemEvent` with loose-polymorphic Target + 3 indexes; `INSERT INTO ActionType` → `INSERT INTO PipelineActionType` (4 rows); **NEW** `INSERT INTO SystemEventType` (16 rows); `ConfigKv.PluginVersion` 2.8.8 → 2.8.9; `MigrationState` markers 2.8.8 + 2.8.9 appended; banner v2.8.8 → v2.8.9. |
| `03-admin-ui.md` | History menu item gains "Activity tab" note for SystemEvent; Action menu item reworded (UI label retained, backing table renamed); History columns relabel `ActionType` → `PipelineActionType`. Banner v2.1.0 → v2.2.0. |
| `01-glossary-and-enums.md` | Banner v3.8.0 → v3.8.1 (entries already correct from v3.8.0 doc-only pass). |
| `97-acceptance-criteria.md` | Added AC-56 (no `Action`/`ActionType` tables), AC-57 (`SystemEvent` columns + indexes), AC-58 (16 `SystemEventType` seeds in canonical order), AC-59 (Activity tab + Action menu wording). Banner v3.8.1 → v3.8.2. |
| `98-changelog.md` | Added v3.8.2 row. |
| `99-consistency-report.md` | Flipped Q2 status in v3.8.0 audit table; this audit table added; seed-coverage table updated for new lookup counts. Banner v3.8.1 → v3.8.2. |

**SQLite validation (in-memory `executescript` of `18-schema.sql`):**
- `PipelineActionType` = 4 rows (Append/Fixed/Clear/ClearAll) ✅
- `SystemEventType` = 16 rows ✅
- `AuditActionType` = 25 rows (unchanged) ✅
- `Permission` = 17, `LogSeverity` = 6, `Acceptance` = 3, `AppStatus` = 3, `AppLinkType` = 2, `UserStatus` = 3, `Provider` = 2 ✅
- `ConfigKv` = 10 defaults (`PluginVersion='2.8.9'`) ✅
- `MigrationState` = 8 markers (2.0.0/2.5.0/2.6.0/2.7.0/2.8.0/2.8.7/2.8.8/2.8.9) ✅
- Legacy tables `Action`/`ActionType`/`OwnerType` confirmed **absent**. ✅
- New tables `PipelineAction`/`PipelineActionType`/`SystemEvent`/`SystemEventType` confirmed **present**. ✅

## v3.8.3 Audit — Phase 2 Q3 Split-DB schema surgery (root DB)

| File | Change |
|------|--------|
| `18-schema.sql` | **DROP** `CREATE TABLE LogEntry` + `IxLogEntryPipeline`; **DROP** `CREATE TABLE ErrorLogEntry` + `IxErrorLogEntryPipeline` (replaced with retirement comment block referencing §39). **ADD** `CREATE TABLE ShaRegistry` (PK `ShaRegistryId`, cols `PipelineId FK`, `Sha`, `DbFilePath`, `RowCount`, `FirstSeenAt`, `LastSeenAt`, `FileSizeBytes`, `Sha256 NULL`, `UNIQUE(PipelineId, Sha)`) + indexes `IxShaRegistrySha`/`IxShaRegistryLastSeen`. **ADD** 3 `ConfigKv` defaults (`ShaLogsRoot='logs'`, `MaxOpenShaDbHandles='32'`, `ShaDbIdleCloseSec='120'`). `ConfigKv.PluginVersion` 2.8.9 → 2.9.0; `MigrationState` marker 2.9.0 appended; banner v2.8.9 → v2.9.0. |
| `02-database-schema.md` | Banner 3.8.0 → 3.8.3. Engine line clarified: root DDL no longer ships `LogEntry`/`ErrorLogEntry`; ships `ShaRegistry` + 3 new ConfigKv keys. `ConfigKv` section gained a v3.8.3 sub-table with new keys + defaults + purpose. |
| `01-glossary-and-enums.md` | Banner 3.8.1 → 3.8.3. `ShaRegistry` definition refined ("One row per (PipelineId, Sha)"). NEW glossary rows: `PerShaDb` (per-SHA SQLite file + path layout) + `ShaLogsRoot` (ConfigKv key + sharded folder tree). |
| `98-changelog.md` | Added v3.8.3 row. |
| `99-consistency-report.md` | This audit table added; v3.8.0 audit table flipped (`LogEntry`/`ErrorLogEntry` drop + `ShaRegistry` + 3 ConfigKv defaults marked landed in v3.8.3); banner 3.8.2 → 3.8.3. |

**Phase 2 scope discipline:** §15 error codes (`GL-SHA-DB-*`), §22 prune walk, §23 backup manifest, §29 wipe, §97 ACs (AC-49..AC-53 promotion), §00 inventory row, Mermaid re-render, root `spec-index.md` bump are **deferred to Phases 3–4** per `mem://specs/phased-roadmap.md`.

## v3.8.4 Audit — Phase 3 Split-DB error codes & cross-section updates

| File | Change |
|------|--------|
| `15-error-codes.md` | Added new section *Per-SHA log storage (split-DB — see §39)* with 4 codes: `GL-SHA-DB-OPEN-FAILED` (503), `GL-SHA-DB-CREATE-FAILED` (500), `GL-SHA-DB-CHECKSUM-MISMATCH` (500), `GL-SHA-DB-QUOTA-EXCEEDED` (507). Banner v2.8.7 → v2.9.0. |
| `22-retention-and-pruning.md` | Rewrite: row-level `DELETE FROM LogEntry`/`ErrorLogEntry` removed; eligibility now `ShaRegistry.LastSeenAt` + `Pipeline.HasError` + history-window guard; rename → delete-row → unlink crash-safety with `*.pruning` recovery; new exit code 4 for FS errors; empty-shard `<aa>/` cleanup; audit JSON keys updated. Banner v2.5.0 → v2.9.0. |
| `23-backup-restore.md` | Backup is now a directory tree (`git-logs.sqlite` + `manifest.json` + `logs/<aa>/<sha>.db…`); each per-SHA file copied via Online Backup API + integrity_check + sha256. Manifest gains `ShaFiles[]` + `ShaFileTotal`. Restore is all-or-nothing with `.bak` rollback. New `--skip-sha-checksum` flag. New cross-version row pre-v2.9.0 → v2.9.0+. `verify` walks `ShaRegistry`. Banner v2.5.0 → v2.9.0. |
| `29-uninstall-policy.md` | Lifecycle table gained "Per-SHA tree" column. `Preserve` retains tree + records `ShaFileCount`; `Archive` renames tree to `<ShaLogsRoot>.archive-<unix>/`; `Wipe` deletes tree first then root, then `rmdir` parent. Banner v2.5.0 → v2.9.0. |
| `98-changelog.md` | Added v3.8.4 row. |
| `99-consistency-report.md` | This audit table added; Phase-3 deferred-list line in v3.8.3 audit superseded by Phase-4-only deferral note in Health Score; banner v3.8.3 → v3.8.4. |

**Phase 3 scope discipline:** §00 inventory row for §39, §97 ACs (AC-49..AC-53 promotion to active), Mermaid re-render, root `spec-index.md` bump are **deferred to Phase 4** per `mem://specs/phased-roadmap.md`. Phases 5–10 untouched.

## v3.8.5 Audit — Phase 4 Split-DB doc closure

| File | Change |
|------|--------|
| `00-overview.md` | §39 inventory row rewritten — old "NEW v3.8.0; logs/<RepoVersionId>/<GitSha256>.sqlite" replaced with v2.9.0-active wording (path `<dataDir>/<ShaLogsRoot>/<Sha[0:2]>/<Sha>.db`, ConfigKv keys, cross-refs to §15/§22/§23/§29). Banner v3.8.0 → v3.8.5. |
| `97-acceptance-criteria.md` | AC-49..AC-53 promoted from draft to **Active (v2.9.0)**; rewritten to match shipped DDL/runtime (key on `(PipelineId, Sha)`, `RowCount`/`LastSeenAt`/`FileSizeBytes`/`Sha256` mirrors, defaults `MaxOpenShaDbHandles=32` / `ShaDbIdleCloseSec=120`, `GL-SHA-DB-QUOTA-EXCEEDED` 507, prune crash-safety, manifest `ShaFiles[]`, Wipe per-SHA-tree-first). Banner v3.8.2 → v3.8.5. |
| `spec/spec-index.md` | Refreshed 9 version cells (00→3.8.5, 01→3.8.3, 02→3.8.3, 15→2.9.0, 22→2.9.0, 23→2.9.0, 29→2.9.0, 97→3.8.5, 99→3.8.5). |
| `26-gitlogs-diagrams/01-er-diagram.mmd` | Top annotation declares root-DB scope + split-DB boundary; stale `RepoVersion → ShaRegistry seenShas` and `LogSeverity → ShaRegistry lastSeverity` edges removed; `Pipeline → ShaRegistry lastSha` collapsed to canonical `sha` edge; `ShaRegistry` entity block rewritten to v2.9.0 §18 DDL columns (`PipelineId`, `Sha`, `DbFilePath`, `RowCount`, `FirstSeenAt`, `LastSeenAt`, `FileSizeBytes`, `Sha256`). |
| `98-changelog.md` | v3.8.5 row added. |
| `99-consistency-report.md` | This audit table added; banner v3.8.4 → v3.8.5. |

**Phase 4 scope discipline:** Phases 5–10 untouched. Phase B1 (§07 App identity fields) remains blocked on user decision.

## v3.8.6 Audit — Phase 5 SSH-Key Lane B schema & errors

| File | Change |
|------|--------|
| `18-schema.sql` | Added `CREATE TABLE SshKey` (11 cols: `SshKeyId PK AI`, `Fingerprint UNIQUE`, `RepoId FK ON DELETE CASCADE`, `KeyType`, `PublicKey`, `Label`, `OwnedByProfileId FK`, `IsActive CHECK 0/1 DEFAULT 1`, `LastUsedAt`, `CreatedAt`, `RevokedAt`) + 2 indexes (`IxSshKeyRepoActive`, `IxSshKeyOwner`); added `CREATE TABLE SshNonce` (`SshNonceId PK`, `SshKeyId FK ON DELETE CASCADE`, `Nonce`, `SeenAt`, `UNIQUE(SshKeyId, Nonce)`) + 1 index. Added 2 ConfigKv defaults (`SshAuthMode='optional'`, `SshNonceJanitorBatch='100'`). Bumped `PluginVersion` 2.9.0 → 2.9.1; appended `MigrationState` 2.9.1. Banner v2.9.0 → v2.9.1. |
| `01-glossary-and-enums.md` | 3 new entries: `SshKey` (deploy-key model + full column list + Lane B authoritative semantics), `Ed25519Signature` (OpenSSH PEM signature over `GL-SSHSIG-V1` canonical string, namespace `git-logs@v2`, `-H sha512`), `SshNonce` (replay-defense semantics, prune cadence). Banner v3.8.3 → v3.8.6. |
| `02-database-schema.md` | Banner v3.8.3 → v3.8.6 (existing SshKey/SshNonce sub-sections from earlier §31 doc work now backed by canonical §18 DDL). |
| `15-error-codes.md` | Banner v2.9.0 → v2.9.1 — confirms 9 SSH lane codes (`GL-SSH-HEADER-MISSING`, `-TIMESTAMP-SKEW`, `-KEY-UNKNOWN`, `-KEY-INACTIVE`, `-REPO-MISMATCH`, `-NONCE-REUSED`, `-SIGNATURE-INVALID`, `-LANE-CONFLICT`, plus `GL-AUTH-LANE-DISABLED`) are backed by canonical schema. |
| `31-ssh-key-auth.md` | Banner v2.7.0 → v2.9.1 — canonical-DDL note added. |
| `98-changelog.md` | v3.8.6 row added. |
| `99-consistency-report.md` | This audit table added; banner v3.8.5 → v3.8.6. |

**In-memory SQLite validation:** 31 tables (was 29 in v2.9.0); `SshKey` + `SshNonce` present; `LogEntry`/`ErrorLogEntry` still absent; 15 ConfigKv keys (was 13); `PluginVersion=2.9.1`; 10 MigrationState markers; 3 SshKey* AuditActionType seeds (`SshKeyRegister`/`SshKeyRevoke`/`SshKeyRotate`).

**Phase 5 scope discipline:** §05 SSH lane block insertion, §28 GH-Actions SSH-signed example, §30 STRIDE entries are **deferred to Phase 6**. AC additions for SshKey/SshNonce deferred to Phase 7 (AC GWT pass). Phases 7–10 untouched. Phase B1 still blocked on user.

## v3.8.7 Audit — Phase 6 SSH-Key Lane B flow & threat doc

| File | Change |
|------|--------|
| `05-auth-and-validation.md` | Banner v2.1.0 → v2.9.1. SSH lane block (10-step validation order) confirmed authoritative; cross-refs to §31 (signing string), §15 (9 SSH error codes), §18 v2.9.1 (canonical SshKey/SshNonce DDL) verified. Coexistence rules (`X-GL-Auth-Mode` parse, `SshAuthMode` gate, `GL-SSH-LANE-CONFLICT`, `GL-AUTH-LANE-DISABLED`) preserved. |
| `28-example-github-actions.md` | Banner v2.7.0 → v2.9.1. Drop-in `git-logs-ssh.yml` workflow confirmed authoritative — namespace `git-logs@v2`, four required headers, canonical signing string `GL-SSHSIG-V1\nMETHOD\nPATH\nTIMESTAMP\nNONCE\nsha256(body)`, deploy-key rotation, `~/.ssh-gitlogs` cleanup with `if: always()`. SSH-mode gotchas table covers all 7 SSH error codes + `GL-AUTH-LANE-DISABLED`. Legacy TempToken workflow retained as deprecation reference. |
| `30-threat-model.md` | Banner v2.7.0 → v2.9.1. Added 4 STRIDE Spoofing rows that the v2.7.0 summary already promised but never wrote: **S5 Signature replay** (`SshReplayWindowSec` skew, per-key `(SshKeyId, Nonce)` uniqueness, `SshNonceJanitorBatch` table-bound); **S6 Private-key theft from CI runner** (deploy-key one-Repo blast radius, immediate `IsActive=0` rotation, `LastUsedAt` anomaly surface, GH-Actions key-wipe, per-Profile rate cap); **S7 Signature stripping / lane downgrade** (mandatory HTTPS, `SshAuthMode=required` hard reject, `GL-SSH-LANE-CONFLICT` mixed-lane block, header-completeness ordered before signature check); **S8 Lane-mode forgery** (`ConfigKv.SshAuthMode` direct DB edit covered by T1, `AuditTrail.ConfigKvUpdate` for in-band changes). Closes the "S5–S8 SSH-lane additions" forward reference in §30 summary. |
| `98-changelog.md` | v3.8.7 row added. |
| `99-consistency-report.md` | This audit table added; banner v3.8.6 → v3.8.7. |

**Phase 6 scope discipline:** AC additions for SshKey/SshNonce/replay/lane-downgrade are **deferred to Phase 7** (AC GWT pass). §15/§18/§31 untouched (already at v2.9.1 from Phase 5). Phases 7–10 untouched. Phase B1 still blocked on user.

## v3.8.8 Audit — Phase 7 AC Quality Pass

| File | Change |
|------|--------|
| `97-acceptance-criteria.md` | **Full rewrite.** Banner v3.8.5 → v3.8.8. Every AC (AC-01..AC-59) converted from one-line table rows into full **Given / When / Then** stanzas with explicit `Verifies:` cross-refs to source sections. Status badges `[active]`/`[draft]`/`[deprecated]` introduced; all current ACs land at `[active]` for v2.9.1. Reorganized into 9 thematic sections (A UI · B Domain · C Auth/Lane · D Endpoints · E Logging/Migrations · F Audit · G Schema/Diagrams · H Per-SHA Split-DB · I SSH-Key Lane B). **7 new ACs added** (AC-60..AC-66) — all in Section I, all `[active]` — covering: SshKey registration shape + audit hooks (AC-60); SshNonce replay defense via skew + per-key uniqueness + janitor (AC-61); lane gating via `SshAuthMode` + mixed-lane conflict (AC-62); signature stripping defense via header-completeness ordering + mandatory HTTPS (AC-63); SshKey rotation via `IsActive=0` no-cache reject + dual SystemEvent + dual AuditTrail (AC-64); deploy-key one-Repo blast radius via FK CASCADE + `LastUsedAt` anomaly + rate cap (AC-65); canonical signing string `GL-SSHSIG-V1\nMETHOD\nPATH\nTIMESTAMP\nNONCE\nsha256(body)` + `git-logs@v2` namespace + `-H sha512` (AC-66). AC-38 amended to list SSH lane AuditActionType seeds (22/23/24). AC count 59 → 66. |
| `98-changelog.md` | v3.8.8 row added. |
| `99-consistency-report.md` | This audit table added; banner v3.8.7 → v3.8.8. |

**Phase 7 scope discipline:** §05/§15/§18/§28/§30/§31 untouched (already at v2.9.1 from Phases 5–6); only §97 was rewritten. Phases 8–10 untouched. Phase B1 still blocked on user.

**AC inventory check:** 66 ACs total (AC-01..AC-66). No AC numbers reused; gaps preserved. All carry GWT format + `Verifies:` pointer + status badge.

## Health Score

100/100 (A+) — 33 of 33 numbered files present (09–13 + 21 intentional gaps, locked); cross-links valid (incl. §00↔§39, §01↔§02↔§15↔§18↔§31, §05↔§28↔§30↔§31 SSH lane chain, §02↔§15↔§22↔§23↔§29↔§39 split-DB chain, §97↔§05/§15/§18/§28/§30/§31 SSH AC chain); AC coverage AC-01..AC-66 (66 total, all GWT, all `[active]`); ER diagram reflects v2.9.0 split-DB shape; v3.8.8 Phase 7 AC GWT pass landed in §97 (rewrite + AC-60..AC-66 SSH coverage) with full lockstep on changelog/consistency. Open follow-ups (per phased roadmap): (a) §07 user decision (App identity, Phase B1 blocked); (b) Phase 8 (NDJSON streaming on §04); (c) Phase 9 (`Pipeline.PreviousHasError` flag in §18 + §02 + §01); (d) Phase 10 (Mermaid `.mmd` → `.svg` render pass).
