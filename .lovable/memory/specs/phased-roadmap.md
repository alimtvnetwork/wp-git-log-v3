---
name: Phased Spec Roadmap
description: Authoritative phased task list for spec/22-git-logs-v2. On `next`, take top pending phase and complete it. Spec-only, no implementation.
type: feature
---

# Phased Spec Roadmap — Git Logs v2

**Rule:** On each `next`, take the **top pending phase** (lowest number, status = pending) and complete every task in it. Update the matching §98 changelog row, §99 consistency, and bump version. Mark phase ✅ here when done.

---

## ✅ Phase 0 — Q1 IsOrganization (DONE, v3.8.1)
## ✅ Phase 1 — Q2 PipelineAction + SystemEvent (DONE, v3.8.2)
## ✅ Phase 2 — Split-DB Schema Surgery (DONE, v3.8.3 / schema v2.9.0)

Landed in v3.8.3:
- §18: dropped `LogEntry` + `ErrorLogEntry` (and their indexes), added `ShaRegistry` table + 2 indexes, added 3 ConfigKv defaults (`ShaLogsRoot`, `MaxOpenShaDbHandles`, `ShaDbIdleCloseSec`), bumped PluginVersion 2.8.9→2.9.0, appended MigrationState 2.9.0.
- §02: banner 3.8.0→3.8.3, engine note rewritten, ConfigKv sub-table for new keys.
- §01: banner 3.8.1→3.8.3, ShaRegistry def refined, NEW glossary rows `PerShaDb` + `ShaLogsRoot`.
- §98 changelog v3.8.3 row added; §99 v3.8.3 audit table + Q3 status flip in v3.8.0 audit. Schema validated in-memory: 29 tables, no LogEntry/ErrorLogEntry, ShaRegistry present, 13 ConfigKv rows, 9 MigrationState markers.

---

## ✅ Phase 3 — Split-DB Error Codes & Cross-Section Updates (DONE, v3.8.4)

Landed in v3.8.4:
- §15: 4 new `GL-SHA-DB-*` codes (`OPEN-FAILED` 503, `CREATE-FAILED` 500, `CHECKSUM-MISMATCH` 500, `QUOTA-EXCEEDED` 507) under new section *Per-SHA log storage*. Banner v2.8.7→v2.9.0.
- §22: rewritten — eligibility on `ShaRegistry.LastSeenAt`, rename→delete-row→unlink crash-safety with `*.pruning` recovery, exit code 4 for FS errors, empty-shard cleanup. Banner v2.5.0→v2.9.0.
- §23: backup is now a directory tree; manifest gains `ShaFiles[]` with `{PipelineId,Sha,DbFilePath,RowCount,FileSizeBytes,Sha256}` + `ShaFileTotal`; restore is all-or-nothing with `.bak` rollback; new pre-v2.9.0 cross-version migration row. Banner v2.5.0→v2.9.0.
- §29: lifecycle table gained "Per-SHA tree" column; Wipe deletes `<ShaLogsRoot>/` first, then root DB, then `rmdir` parent. Banner v2.5.0→v2.9.0.

## ⏳ Phase 4 — Split-DB Doc Closure
**Files:** `00-overview.md`, `97-acceptance-criteria.md`, `98-changelog.md`, `99-consistency-report.md`, `spec-index.md`, `26-gitlogs-diagrams/`
- §00 inventory: add §39 row
- §97 ACs: promote AC-49..AC-53 (split-DB) from draft to active
- §98 changelog: v3.8.3 row
- §99 consistency: flip Q3 status
- Re-render Mermaid diagrams: show split-DB boundary
- Update root `spec-index.md`

---

## ⏳ Phase 5 — SSH-Key Lane B: Schema & Errors
**Files:** `18-schema.sql`, `15-error-codes.md`, `01-glossary-and-enums.md`
- Add `SshKey` table to §18 DDL (already specified in §31; sync to canonical schema file)
- Add 7 `GL-SSH-*` error codes
- Add 3 `AuditActionType` seeds: `SshKeyRegister`, `SshKeyRevoke`, `SshKeyRotate`
- Glossary entries for `SshKey`, `Ed25519Signature`

## ⏳ Phase 6 — SSH-Key Lane B: Flow & Threat Doc
**Files:** `05-auth-and-validation.md`, `28-example-github-actions.md`, `30-threat-model.md`
- §05 auth flow: SSH signature verification path
- §28: SSH-signed workflow example
- §30: SSH threats (key theft, signature replay, key rotation)

---

## ⏳ Phase 7 — AC Quality Pass
**Files:** `97-acceptance-criteria.md`
- Convert all ACs to GWT (Given/When/Then) format

## ⏳ Phase 8 — API Streaming Spec
**Files:** `04-rest-api-endpoints.md`
- Define NDJSON streaming format for log retrieval

## ⏳ Phase 9 — Pipeline PreviousHasError Flag
**Files:** `18-schema.sql`, `02-database-schema.md`, `01-glossary-and-enums.md`
- Add `PreviousHasError` boolean to `Pipeline` table
- Document semantics + back-fill rule

## ⏳ Phase 10 — Diagram Render Pass
**Files:** `26-gitlogs-diagrams/`
- Re-render all `.mmd` → `.svg` after Phase 4 changes land

---

## 🚧 Blocked (awaiting user decision)
- **Phase B1 — §07 App identity fields**: confirm `Environment`, `Platform`, `OwnerEmail` shape

---

## Status legend
- ✅ done
- ⏳ pending
- 🚧 blocked

## Next-pointer
**Top pending = Phase 4** (Split-DB Doc Closure)
