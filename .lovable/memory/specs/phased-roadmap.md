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

---

## ⏳ Phase 2 — Split-DB Schema Surgery (root DB)
**Files:** `18-schema.sql`, `02-database-schema.md`, `01-glossary-and-enums.md`
- Drop `LogEntry` and `ErrorLogEntry` from root DB DDL
- Add `ShaRegistry` table (PK: ShaRegistryId; cols: PipelineId FK, Sha, DbFilePath, RowCount, FirstSeenAt, LastSeenAt, FileSizeBytes, Sha256)
- Add 3 `ConfigKv` defaults: `MaxOpenShaDbHandles`, `ShaDbIdleCloseSec`, `ShaLogsRoot`
- Update §02 schema doc + ERD callouts
- Update §01 glossary: add `ShaRegistry`, `PerShaDb`; note `LogEntry` lives in per-SHA file

## ⏳ Phase 3 — Split-DB Error Codes & Cross-Section Updates
**Files:** `15-error-codes.md`, `22-retention-and-pruning.md`, `23-backup-restore.md`, `29-uninstall-policy.md`
- Add 4 `GL-SHA-DB-*` error codes (open/create/checksum/quota)
- §22 Pruning: walk `ShaRegistry`, delete per-SHA `.db` files, then row
- §23 Backup: manifest enumerates per-SHA files with row counts + sha256
- §29 Uninstall: Wipe mode deletes `logs/` folder root

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
**Top pending = Phase 2** (Split-DB Schema Surgery)
