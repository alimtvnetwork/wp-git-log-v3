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

## ✅ Phase 7 — AC Quality Pass (DONE, v3.8.8)

Landed in v3.8.8:
- §97 banner v3.8.5→v3.8.8. Every AC (AC-01..AC-59) rewritten from one-line table rows into Given/When/Then stanzas + `Verifies:` cross-refs + `[active]`/`[draft]`/`[deprecated]` status badges. Reorganized into 9 thematic sections (A UI · B Domain · C Auth/Lane · D Endpoints · E Logging/Migrations · F Audit · G Schema/Diagrams · H Per-SHA Split-DB · I SSH-Key Lane B).
- 7 new ACs added in Section I, all `[active]`: AC-60 SshKey registration shape; AC-61 SshNonce replay defense (skew + per-key uniqueness + janitor); AC-62 lane gating via `SshAuthMode` + mixed-lane `GL-SSH-LANE-CONFLICT`; AC-63 signature stripping defense (header-completeness ordered first + mandatory HTTPS); AC-64 SshKey rotation flow (`IsActive=0` no-cache reject + dual SystemEvent + dual AuditTrail); AC-65 deploy-key one-Repo blast radius (FK CASCADE + `LastUsedAt` anomaly + rate cap); AC-66 canonical signing string + `git-logs@v2` namespace + `-H sha512`.
- AC-38 amended to list SSH AuditActionType seeds (22/23/24). AC count 59 → 66.

## ✅ Phase 8 — API Streaming Spec (DONE, v3.8.9)

Landed in v3.8.9:
- §04 banner v2.8.3 → v2.9.2. Added new top-level §11 NDJSON Streaming Retrieval (v2.9.2) with 9 sub-sections covering rationale, opt-in via `Accept: application/x-ndjson`, 5-frame schema (`Header`/`Log`/`ErrorLog`/`Progress`/`End` + optional mid-stream `Error`), ordering & atomicity, 4 new ConfigKv keys (doc-only), resume via `?after-seq=N` + `?stream-id=<uuid>`, endpoint applicability matrix (#5–#10 ✅; #1–#4 ❌), wire example, cross-refs.
- 2 new error codes introduced doc-side: `GL-NDJSON-CLIENT-DISCONNECT` (499 informational), `GL-NDJSON-CURSOR-LOST` (500).
- **Streaming follow-ups deferred:** §18 ConfigKv seeding for 4 `Ndjson*` keys, §15 entries for 2 `GL-NDJSON-*` codes, §17 OpenAPI `application/x-ndjson` content variants for endpoints #5–#10, §97 ACs for streaming behavior. Tracked under "Streaming follow-ups" in §99 Phase 8 audit.

## ✅ Phase 9 — Pipeline PreviousHasError Flag (DONE, v3.8.10 / schema v2.9.2)

Landed in v3.8.10 / schema v2.9.2:
- §18: `Pipeline` table gained `PreviousHasError INTEGER NOT NULL DEFAULT 0 CHECK (PreviousHasError IN (0,1))` immediately after `HasError`; `HasError` itself gained explicit `CHECK (HasError IN (0,1))`. Inline back-fill rule (`UPDATE Pipeline SET PreviousHasError = HasError;` on v2.9.1→v2.9.2 upgrade) and write rule (single-`UPDATE` atomicity, no read-modify-write). PluginVersion 2.9.1→2.9.2; MigrationState 2.9.2 appended (11 markers).
- §02: banner v3.8.6→v3.8.10. Pipeline doc gains `PreviousHasError` row + state-transition labels (`first-failure`/`still-failing`/`just-recovered`/`still-green`).
- §01: banner v3.8.6→v3.8.10. Bare `Pipeline` glossary row split into 3 — `Pipeline`, `HasError`, `PreviousHasError`.
- **Phase 9 follow-ups deferred:** §97 ACs for `PreviousHasError` (state-transition matrix, back-fill correctness, single-statement write atomicity), §03 admin UI rendering of the four state labels, §04 NDJSON `Header` frame label exposure.

## ✅ Phase 10 — Diagram Render Pass (DONE, v2.1.0)

Landed in folder 26 v2.1.0:
- Rendered all 6 active `.mmd` sources to companion `.svg` artifacts via `@mermaid-js/mermaid-cli` v11+. Source `.mmd` unchanged.
- §00/§98/§99 banners + spec-index refreshed.

## ✅ Phase 11 — Streaming Follow-ups Pickup (DONE, v3.8.11 / schema v2.9.3)

Absorbed all Phase 8 streaming deferrals + AC coverage:
- §18: 4 NDJSON ConfigKv seeds (`NdjsonProgressEveryRows=10000`, `NdjsonProgressEveryMs=2000`, `NdjsonMaxRowsPerStream=1000000`, `NdjsonMaxFrameBytes=262144`); PluginVersion 2.9.2→2.9.3; MigrationState 2.9.3 appended (12 markers).
- §15: `GL-NDJSON-CLIENT-DISCONNECT` (499) + `GL-NDJSON-CURSOR-LOST` (500) registered.
- §17: `info.version` 2.8.2→2.9.3 (absorbs Phases 4/5/9/11). `ErrorCode` enum gained 4 `GL-SHA-DB-*` + 2 `GL-NDJSON-*` codes. 7 new `Ndjson*` schemas + `NdjsonStream` reusable response. All 4 GET paths carry `application/x-ndjson` content variant + `after-seq`/`stream-id` query params.
- §97: 6 new ACs (AC-67..AC-72) — opt-in, frame ordering, resume, disconnect, frame cap, Progress cadence. AC count 66→72.
- Validation: `pyyaml.safe_load` clean; in-memory SQLite confirms 19 ConfigKv rows, 12 MigrationState markers.

## ✅ Phase 12 — Phase 9 Follow-ups (DONE, v3.8.12 / OpenAPI v2.9.4)

Closed the unblocked subset of Phase 9 deferrals (§03 admin UI is consumer-side, intentionally out-of-scope for this spec-only project):
- §04 §11.3.1: `Header` frame example + bullet documents OPTIONAL `StateTransition` (4-value enum, single-pipeline-scope only). Banner v2.9.2→v2.9.3.
- §17: `info.version` 2.9.3→2.9.4. `NdjsonHeaderFrame` gained optional `StateTransition` enum property (NOT in `required`).
- §97: 3 new ACs (AC-73 label matrix, AC-74 NDJSON Header exposure, AC-75 back-fill + write atomicity). AC count 72→75.
- Validation: `pyyaml.safe_load` clean; `StateTransition` confirmed in `NdjsonHeaderFrame.properties` with all 4 enum values, confirmed absent from `required`.

## ✅ Phase 13 — Deepen Scaffolded ACs in §22 §97 (DONE, v3.8.13)

Closed §22-scope subset of `mem://specs/full-tree-audit-v4.md` deepening backlog. Doc-only, no schema/DDL/OpenAPI churn:
- §97: 8 high-traffic one-liner ACs deepened from ~200-260 chars to 1400-2200 chars each (5–10×): AC-02 (Profile no-password rule), AC-03 (migration semver ordering + 12-marker baseline), AC-12 (streaming ingest incremental caps), AC-14 (AckResponse Retrieval URLs), AC-17 (App columns + forbidden Phase B1 fields), AC-18 (AppLink XOR + CASCADE), AC-22 (§26 6-file Mermaid manifest), AC-30 (ErrorEnvelope shape + RequestId mirroring + NDJSON error split). AC count unchanged at 75 (verified sequential AC-01..AC-75).
- 4 simpler ACs (AC-04/AC-05/AC-25/AC-34) intentionally left lean.

---

## 🚧 Blocked (awaiting user decision)
- **Phase B1 — §07 App identity fields**: confirm `Environment`, `Platform`, `OwnerEmail` shape

---

## Status legend
- ✅ done
- ⏳ pending
- 🚧 blocked

## Next-pointer
**Top pending = Phase B1 (BLOCKED on user — §07 App identity fields).** All numbered phases 0–13 complete. All Phase 8/9 follow-ups closed; §22-scope AC deepening backlog closed. **Remaining unblocked candidates:** (a) extend Phase 13 deepening to §07 design-system + §17 consolidated-guidelines (other high-traffic modules per audit memo); (b) §03 admin UI rendering of state labels (consumer-side, out-of-scope for spec project unless user wants it scoped). On next `next`, propose (a) as Phase 14 or unblock B1.
