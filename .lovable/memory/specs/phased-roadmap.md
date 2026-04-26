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

## ✅ Phase 4 — Split-DB Doc Closure (DONE, v3.8.5)

Landed in v3.8.5:
- §00: §39 inventory row refreshed for v2.9.0 path layout (`<dataDir>/<ShaLogsRoot>/<Sha[0:2]>/<Sha>.db`) + cross-refs to §15/§22/§23/§29. Banner v3.8.0→v3.8.5.
- §97: AC-49..AC-53 promoted from draft to **Active (v2.9.0)**, rewritten to match shipped DDL (`(PipelineId, Sha)` key, real ConfigKv defaults 32/120, GL-SHA-DB-* code refs, manifest `ShaFiles[]`, Wipe per-SHA-tree-first). Banner v3.8.2→v3.8.5.
- Root `spec/spec-index.md`: 9 version cells refreshed for files touched in Phases 2–4.
- `26-gitlogs-diagrams/01-er-diagram.mmd`: re-rendered for split-DB boundary — top annotation, stale edges removed, `ShaRegistry` entity rewritten to v2.9.0 columns.
- §98 v3.8.5 row + §99 Phase 4 audit table + Health Score updated.

---

## ✅ Phase 5 — SSH-Key Lane B: Schema & Errors (DONE, v3.8.6)

Landed in v3.8.6 / schema v2.9.1:
- §18: `CREATE TABLE SshKey` (11 cols) + 2 indexes; `CREATE TABLE SshNonce` + 1 index; 2 new ConfigKv (`SshAuthMode='optional'`, `SshNonceJanitorBatch='100'`); PluginVersion 2.9.0→2.9.1; MigrationState 2.9.1 appended. Banner v2.9.0→v2.9.1.
- §01: 3 new glossary rows (`SshKey`, `Ed25519Signature`, `SshNonce`). Banner v3.8.3→v3.8.6.
- §02: banner v3.8.3→v3.8.6 (existing SshKey/SshNonce sub-sections now backed by canonical DDL).
- §15: banner v2.9.0→v2.9.1 — 9 SSH lane codes already present, now backed by canonical schema.
- §31: banner v2.7.0→v2.9.1 with canonical-DDL note.
- AuditActionType seeds verified: `SshKeyRegister`(22), `SshKeyRevoke`(23), `SshKeyRotate`(24) already present.
- In-memory SQLite validation: 31 tables, 15 ConfigKv, 10 MigrationState markers, 3 SshKey* AuditActionTypes.

## ✅ Phase 6 — SSH-Key Lane B: Flow & Threat Doc (DONE, v3.8.7)

Landed in v3.8.7:
- §05: banner v2.1.0→v2.9.1. SSH lane block (10-step validation order) confirmed authoritative; cross-refs to §31/§15/§18 verified.
- §28: banner v2.7.0→v2.9.1. Drop-in `git-logs-ssh.yml` workflow (namespace `git-logs@v2`, four headers, canonical signing string, deploy-key rotation, key-wipe `if: always()`) confirmed authoritative.
- §30: banner v2.7.0→v2.9.1. Added 4 STRIDE Spoofing rows (S5 replay, S6 key theft, S7 sig stripping/lane downgrade, S8 lane-mode forgery) — closes the "S5–S8 SSH-lane additions" forward reference in summary.

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
**Top pending = Phase 7** (AC Quality Pass — convert all ACs to GWT format; add ACs for SshKey/SshNonce/replay/lane-downgrade)
