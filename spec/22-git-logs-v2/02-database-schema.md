# Database Schema (v2, SQLite)

**Version:** 2.0.0  
**Updated:** 2026-04-25  
**Engine:** SQLite (single root DB file owned by plugin)

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
| OwnerType | OwnerTypeId, Name |
| Acceptance | AcceptanceId, Name |
| AppStatus | AppStatusId, Name |
| AppLinkType | AppLinkTypeId, Name |
| LogSeverity | LogSeverityId, Name, Numeric |
| ActionType | ActionTypeId, Name |
| AuditActionType | AuditActionTypeId, Name |
| AuditOutcome | AuditOutcomeId, Name |

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
| OwnerTypeId | INTEGER FK → OwnerType | User \| Organization |
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

## LogEntry

| Column | Type | Notes |
|--------|------|-------|
| LogEntryId | INTEGER PK AI | |
| PipelineId | INTEGER FK → Pipeline | |
| GitSha256 | TEXT NOT NULL | |
| LineNumber | INTEGER NOT NULL | Order within batch |
| LogText | TEXT NOT NULL | |
| LogSeverityId | INTEGER FK → LogSeverity | |
| FilePath | TEXT NULL | From request `FilePaths` |
| OccurredAt | INTEGER NOT NULL | |

Index: `(PipelineId, GitSha256, LineNumber)`.

---

## ErrorLogEntry

| Column | Type | Notes |
|--------|------|-------|
| ErrorLogEntryId | INTEGER PK AI | |
| PipelineId | INTEGER FK → Pipeline | |
| GitSha256 | TEXT NOT NULL | |
| LineNumber | INTEGER NOT NULL | |
| LogText | TEXT NOT NULL | |
| FilePath | TEXT NULL | |
| OccurredAt | INTEGER NOT NULL | |

Index: `(PipelineId, GitSha256, LineNumber)`.

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
| ActionTypeId | INTEGER FK → ActionType | |
| HasError | INTEGER 0/1 NOT NULL | Snapshot at event |
| Summary | TEXT NULL | Short message |
| OccurredAt | INTEGER NOT NULL | |

Index: `(RepoVersionId, OccurredAt)`.

---

## Action (enum-typed action log; lighter than History)

| Column | Type | Notes |
|--------|------|-------|
| ActionId | INTEGER PK AI | |
| ActionTypeId | INTEGER FK → ActionType | |
| RepoVersionId | INTEGER FK → RepoVersion | |
| PipelineId | INTEGER FK → Pipeline NULL | |
| ProfileId | INTEGER FK → Profile NULL | Caller, if resolvable |
| OccurredAt | INTEGER NOT NULL | |

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

