# Database Schema (v2, SQLite)

**Version:** 3.8.6  
**Updated:** 2026-04-26  
**Engine:** SQLite (root DB file owned by plugin) + per-SHA SQLite files under `wp-content/uploads/git-logs/<ShaLogsRoot>/` — see §39. **v3.8.3 (Q3 Split-DB):** root DDL no longer ships `LogEntry` / `ErrorLogEntry`; those tables live exclusively in per-SHA files. Root DB now ships `ShaRegistry` (canonical pointer + summary) plus 3 new `ConfigKv` keys (`ShaLogsRoot`, `MaxOpenShaDbHandles`, `ShaDbIdleCloseSec`).

---

## Conventions

- All tables/columns/JSON keys/values: **PascalCase**.
- PK on every table: `INTEGER PRIMARY KEY AUTOINCREMENT`, named `{TableName}Id`.
- Smallest appropriate integer type (SQLite stores INTEGER; semantic width is documented).
- All typed values (`Status`, `Type`, `Kind`, `Acceptance`, `Role`, `Permission`) are FK to lookup tables.
- All timestamps `INTEGER` Unix seconds (UTC). Columns named `CreatedAt`, `UpdatedAt`, `OccurredAt`.
- Booleans stored as `INTEGER` 0/1 with `Is` prefix.

---

## Lookup Tables (one row per enum value)

| Table | Columns |
|-------|---------|
| UserStatus | UserStatusId, Name |
| Role | RoleId, Name |
| Permission | PermissionId, Name |
| Provider | ProviderId, Name |
| Acceptance | AcceptanceId, Name |
| AppStatus | AppStatusId, Name |
| AppLinkType | AppLinkTypeId, Name |
| LogSeverity | LogSeverityId, Name, Numeric |
| PipelineActionType | PipelineActionTypeId, Name |
| SystemEventType | SystemEventTypeId, Name |
| AuditActionType | AuditActionTypeId, Name |
| AuditOutcome | AuditOutcomeId, Name |

> **v3.8.0**: `OwnerType` lookup retired — replaced by `GitProfile.IsOrganization` boolean.  
> **v3.8.0**: `ActionType` lookup renamed to `PipelineActionType` (the `Action` table was renamed to `PipelineAction` to reflect its true scope: pipeline-bound, not system-wide).  
> **v3.8.0**: New `SystemEventType` lookup feeds the new `SystemEvent` table for non-Git business events (ProfileCreated, KeyRevoked, AppCreated, …). See §08.

---

## Profile

| Column | Type | Notes |
|--------|------|-------|
| ProfileId | INTEGER PK AI | |
| UserName | TEXT UNIQUE NOT NULL | |
| Email | TEXT NOT NULL | |
| GeneratedKeyApi | TEXT NOT NULL | Plain v2 |
| Token | TEXT NOT NULL | Plain v2 |
| TempToken | TEXT NOT NULL | Random; rotated on demand |
| UserStatusId | INTEGER FK → UserStatus | |
| CreatedAt | INTEGER | |
| UpdatedAt | INTEGER | |

Index: `(UserName)`, `(TempToken)`.

---

## RoleAssignment (Profile ↔ Role, N-M)

| Column | Type | Notes |
|--------|------|-------|
| RoleAssignmentId | INTEGER PK AI | |
| ProfileId | INTEGER FK → Profile | |
| RoleId | INTEGER FK → Role | |
| CreatedAt | INTEGER | |

Unique: `(ProfileId, RoleId)`.

---

## RolePermission (Role ↔ Permission, N-M)

| Column | Type | Notes |
|--------|------|-------|
| RolePermissionId | INTEGER PK AI | |
| RoleId | INTEGER FK → Role | |
| PermissionId | INTEGER FK → Permission | |

Unique: `(RoleId, PermissionId)`.

> Authorization always checks **Permission** via this join (Admin role is seeded with every permission).

---

## GitProfile

| Column | Type | Notes |
|--------|------|-------|
| GitProfileId | INTEGER PK AI | |
| ProviderId | INTEGER FK → Provider | GitHub in v2 |
| IsOrganization | INTEGER 0/1 NOT NULL DEFAULT 0 | **v3.8.0** — replaces `OwnerTypeId` lookup. `1` ⇒ `github.com/$org/$repo`; `0` ⇒ `github.com/$username/$repo`. Drives URL canonicalization + admin-UI checkbox label "Is organization". |
| OwnerName | TEXT NOT NULL | e.g., `alimtvnetwork` |
| ProfileUrl | TEXT NOT NULL | Canonicalized w/ trailing slash |
| AcceptanceId | INTEGER FK → Acceptance | |
| SelectedRepoUrl | TEXT NULL | Required when Acceptance ≠ AcceptAllRepos |
| IsRestrictInBranch | INTEGER 0/1 NOT NULL DEFAULT 0 | |
| StrictBranch | TEXT NULL | Required when IsRestrictInBranch=1 |
| OwnedByProfileId | INTEGER FK → Profile | Creator |
| CreatedAt | INTEGER | |
| UpdatedAt | INTEGER | |

Unique: `(ProviderId, OwnerName, ProfileUrl)`.

---

## Repo

| Column | Type | Notes |
|--------|------|-------|
| RepoId | INTEGER PK AI | |
| GitProfileId | INTEGER FK → GitProfile | |
| RootRepoName | TEXT NOT NULL | Stripped of `-vN` |
| RootRepoUrl | TEXT NOT NULL | Canonical main repo URL |
| CreatedAt | INTEGER | |

Unique: `(GitProfileId, RootRepoName)`.

---

## RepoVersion

| Column | Type | Notes |
|--------|------|-------|
| RepoVersionId | INTEGER PK AI | |
| RepoId | INTEGER FK → Repo | |
| VersionSuffix | TEXT NOT NULL | `''` for main, `v2`, `v100`, … |
| RepoUrl | TEXT NOT NULL | Full URL of the variant |
| CreatedAt | INTEGER | |

Unique: `(RepoId, VersionSuffix)`.

---

## Pipeline

| Column | Type | Notes |
|--------|------|-------|
| PipelineId | INTEGER PK AI | |
| RepoVersionId | INTEGER FK → RepoVersion | |
| BranchName | TEXT NOT NULL | |
| PipelineName | TEXT NOT NULL | |
| HasError | INTEGER 0/1 NOT NULL DEFAULT 0 | Set by /append-log when HasError=true; cleared by /fixed-log |
| LastGitSha256 | TEXT NULL | |
| UpdatedAt | INTEGER | |

Unique: `(RepoVersionId, BranchName, PipelineName)`.

---

## ShaRegistry (root DB — points at per-SHA log files)

> **v3.8.0 split-DB:** `LogEntry` and `ErrorLogEntry` no longer live in the root DB. The root DB stores only the **registry** of which SHAs exist, where their per-SHA SQLite file is, and rolled-up summary stats sufficient to answer dashboard questions without opening the per-SHA file.

| Column | Type | Notes |
|--------|------|-------|
| ShaRegistryId | INTEGER PK AI | |
| RepoVersionId | INTEGER FK → RepoVersion | |
| GitSha256 | TEXT NOT NULL | Full SHA-256 hex |
| ShaDbPath | TEXT NOT NULL | Relative to `ShaLogsRoot` (`ConfigKv`), e.g. `<RepoVersionId>/<GitSha256>.sqlite` |
| FirstSeenAt | INTEGER NOT NULL | |
| LastSeenAt | INTEGER NOT NULL | |
| EntryCount | INTEGER NOT NULL DEFAULT 0 | Mirrored from per-SHA `StatusSnapshot` on every `/append-log` ack |
| ErrorCount | INTEGER NOT NULL DEFAULT 0 | Same source |
| LastSeverityId | INTEGER FK → LogSeverity NULL | |
| LastStatus | TEXT NOT NULL DEFAULT 'Pending' | `Green` \| `Red` \| `Pending` — derived from latest `PipelineRun.HasError` across this SHA |
| LastFailureAt | INTEGER NULL | |
| LastSuccessAt | INTEGER NULL | |

Unique: `(RepoVersionId, GitSha256)`.  
Index: `(RepoVersionId, LastSeenAt)`, `(LastStatus, LastSeenAt)`.

> Full per-SHA schema (`LogEntry`, `ErrorLogEntry`, `PipelineRun`, `StatusSnapshot`, `ShaMeta`, denormalized `LogSeverity` copy) lives in §39.

---

## App

| Column | Type | Notes |
|--------|------|-------|
| AppId | INTEGER PK AI | |
| AppName | TEXT NOT NULL | |
| AppSlug | TEXT UNIQUE NOT NULL | |
| Description | TEXT NULL | |
| ProfileId | INTEGER FK → Profile | Owner; supplies credentials |
| AppStatusId | INTEGER FK → AppStatus | |
| CreatedAt | INTEGER | |
| UpdatedAt | INTEGER | |

---

## AppLink (polymorphic)

| Column | Type | Notes |
|--------|------|-------|
| AppLinkId | INTEGER PK AI | |
| AppId | INTEGER FK → App | |
| AppLinkTypeId | INTEGER FK → AppLinkType | GitProfile \| Repo |
| TargetGitProfileId | INTEGER FK → GitProfile NULL | Set iff LinkType=GitProfile |
| TargetRepoId | INTEGER FK → Repo NULL | Set iff LinkType=Repo |
| IsActive | INTEGER 0/1 NOT NULL DEFAULT 1 | Disconnect = 0, preserve history |
| CreatedAt | INTEGER | |
| DisconnectedAt | INTEGER NULL | |

CHECK: exactly one of `TargetGitProfileId`/`TargetRepoId` is non-null and matches `AppLinkTypeId`.

---

## History (per RepoVersion event timeline)

| Column | Type | Notes |
|--------|------|-------|
| HistoryId | INTEGER PK AI | |
| RepoVersionId | INTEGER FK → RepoVersion | |
| AppId | INTEGER FK → App NULL | If push attributable to an App |
| BranchName | TEXT NOT NULL | |
| PipelineName | TEXT NULL | |
| GitSha256 | TEXT NULL | |
| PipelineActionTypeId | INTEGER FK → PipelineActionType | **v3.8.0** — was `ActionTypeId` |
| HasError | INTEGER 0/1 NOT NULL | Snapshot at event |
| Summary | TEXT NULL | Short message |
| OccurredAt | INTEGER NOT NULL | |

Index: `(RepoVersionId, OccurredAt)`.

---

## PipelineAction (enum-typed pipeline log; renamed from `Action` in v3.8.0)

> Scope clarification (v3.8.0): rows are **always** bound to a `RepoVersion` and (usually) a `Pipeline`. Non-pipeline business events (ProfileCreated, KeyRevoked, AppCreated, …) belong in `SystemEvent` below. Endpoint forensics belong in `AuditTrail`.

| Column | Type | Notes |
|--------|------|-------|
| PipelineActionId | INTEGER PK AI | Was `ActionId` |
| PipelineActionTypeId | INTEGER FK → PipelineActionType | Was `ActionTypeId` |
| RepoVersionId | INTEGER FK → RepoVersion | |
| PipelineId | INTEGER FK → Pipeline NULL | |
| ProfileId | INTEGER FK → Profile NULL | Caller, if resolvable |
| OccurredAt | INTEGER NOT NULL | |

---

## SystemEvent (business state changes — v3.8.0, NEW)

> Answers: "Show me the user-visible activity feed of meaningful changes (someone created a profile, revoked a key, accepted a GitProfile, changed branch restriction, …)."  
> Distinct from `AuditTrail` (HTTP forensics) and `History` (per-RepoVersion git events).

| Column | Type | Notes |
|--------|------|-------|
| SystemEventId | INTEGER PK AI | |
| SystemEventTypeId | INTEGER FK → SystemEventType | |
| ActorProfileId | INTEGER FK → Profile NULL | Acting Profile (NULL for system events) |
| TargetType | TEXT NULL | Loose polymorphic tag: `Profile` \| `GitProfile` \| `Repo` \| `App` \| `SshKey` \| `RoleAssignment` |
| TargetId | INTEGER NULL | PK of the row in the table named by `TargetType` (no FK CHECK — survives row deletion) |
| Summary | TEXT NULL | Short human label, e.g. `"Profile 'alice' created"` |
| DetailJson | TEXT NULL | JSON blob of the relevant changed fields |
| OccurredAt | INTEGER NOT NULL | |

Index: `(SystemEventTypeId, OccurredAt)`, `(ActorProfileId, OccurredAt)`, `(TargetType, TargetId, OccurredAt)`.

---

## AuditTrail (system-wide, append-only)

| Column | Type | Notes |
|--------|------|-------|
| AuditTrailId | INTEGER PK AI | |
| AuditActionTypeId | INTEGER FK → AuditActionType | |
| AuditOutcomeId | INTEGER FK → AuditOutcome | |
| ActorProfileId | INTEGER FK → Profile NULL | |
| ActorIp | TEXT NULL | |
| RouteName | TEXT NULL | e.g., `git-logs/v2/append-log` |
| HttpStatus | INTEGER NULL | |
| RequestId | TEXT NULL | TraceId |
| Detail | TEXT NULL | JSON blob of relevant fields |
| OccurredAt | INTEGER NOT NULL | |

Index: `(OccurredAt)`, `(AuditActionTypeId, OccurredAt)`.

---

## MigrationState (boot-time idempotent marker)

| Column | Type | Notes |
|--------|------|-------|
| MigrationStateId | INTEGER PK AI | |
| PluginVersion | TEXT UNIQUE NOT NULL | e.g., `2.0.0` |
| AppliedAt | INTEGER NOT NULL | |
| Checksum | TEXT NULL | Optional integrity check |

> On boot: `SELECT 1 FROM MigrationState WHERE PluginVersion = ?`. If absent → run migration → insert row.

---

## ConfigKv (single-row settings)

| Column | Type | Notes |
|--------|------|-------|
| ConfigKvId | INTEGER PK AI | |
| KeyName | TEXT UNIQUE NOT NULL | |
| ValueText | TEXT NULL | |
| UpdatedAt | INTEGER | |

Used for runtime toggles (e.g., `LogLevelMin = "Warn"` to disable Info/Debug, `SshAuthMode`, `ReplayWindowSeconds`, `SshNonceJanitorBatch`, `UninstallMode`, `MaintenanceMode`). Default rows seeded in §16.

**v3.8.3 (Q3 Split-DB) — new keys:**

| KeyName | Default | Purpose |
|---------|---------|---------|
| `ShaLogsRoot` | `logs` | Folder name (relative to plugin data dir) where per-SHA `.db` files are stored. Final layout: `<dataDir>/<ShaLogsRoot>/<Sha[0:2]>/<Sha>.db`. |
| `MaxOpenShaDbHandles` | `32` | LRU cache cap for simultaneously open per-SHA SQLite handles. Eviction drops the least-recently-used handle and closes its connection. |
| `ShaDbIdleCloseSec` | `120` | Idle window (seconds) after which an open per-SHA handle is closed even if cache cap is not exceeded. |

---

## SshKey (Lane B deploy-key auth — see §31)

| Column | Type | Notes |
|--------|------|-------|
| SshKeyId | INTEGER PK AI | Project-wide PK convention. |
| Fingerprint | TEXT UNIQUE NOT NULL | `SHA256:` + base64 SHA-256 of public key (RFC 4716). Uppercase prefix normalized on insert. |
| RepoId | INTEGER FK → Repo NOT NULL | Deploy-key binding (one key → one Repo). |
| KeyType | TEXT NOT NULL | `ssh-ed25519`, `ssh-rsa`, `ecdsa-sha2-nistp256`, … |
| PublicKey | TEXT NOT NULL | Full OpenSSH single-line public key (`<type> <base64> [comment]`). |
| Label | TEXT NULL | Human label (`gh-actions-prod`). |
| OwnedByProfileId | INTEGER FK → Profile NOT NULL | Profile that registered the key. |
| IsActive | INTEGER 0/1 NOT NULL DEFAULT 1 | Soft-disable on rotation. |
| LastUsedAt | INTEGER NULL | Updated on successful auth. |
| CreatedAt | INTEGER NOT NULL | |
| RevokedAt | INTEGER NULL | Set when `IsActive` flipped to 0. |

Indexes: `(RepoId, IsActive)`, `(OwnedByProfileId)`. Uniqueness on `Fingerprint` covers the lookup path.

---

## SshNonce (replay defense, short-lived)

| Column | Type | Notes |
|--------|------|-------|
| SshNonceId | INTEGER PK AI | |
| SshKeyId | INTEGER FK → SshKey NOT NULL | Bound to the verified key. |
| Nonce | TEXT NOT NULL | Client-supplied, ≥16 bytes base64. |
| SeenAt | INTEGER NOT NULL | Unix seconds. |

Unique: `(SshKeyId, Nonce)`.  
Retention: `ReplayWindowSeconds` only (default 300s). Pruned on every request (LIMIT `SshNonceJanitorBatch`) and via daily WP-cron. No long-term forensic copy.

