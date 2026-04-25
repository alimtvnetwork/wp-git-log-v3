# Seed Data (v2)

**Version:** 2.0.0  
**Updated:** 2026-04-25

All seeded rows are inserted inside the same migration transaction that creates the schema (see [`06-migrations-and-logger.md`](./06-migrations-and-logger.md)). Inserts use `INSERT OR IGNORE` so re-running on an already-seeded DB is a no-op.

---

## Lookup tables

### UserStatus
| UserStatusId | Name |
|---|---|
| 1 | Active |
| 2 | Suspended |
| 3 | Revoked |

### Role
| RoleId | Name |
|---|---|
| 1 | Admin |
| 2 | Editor |

### Permission
| PermissionId | Name |
|---|---|
| 1  | AppCreate |
| 2  | AppView |
| 3  | AppModify |
| 4  | AppDelete |
| 5  | ProfileCreate |
| 6  | ProfileView |
| 7  | ProfileModify |
| 8  | ProfileDelete |
| 9  | GitProfileCreate |
| 10 | GitProfileView |
| 11 | GitProfileModify |
| 12 | GitProfileDelete |
| 13 | RepoView |
| 14 | RepoModify |
| 15 | RepoDelete |
| 16 | HistoryView |
| 17 | LogPush |

### Provider
| ProviderId | Name |
|---|---|
| 1 | GitHub |
| 2 | GitLab |  *(reserved, not selectable in v2 UI)*

### OwnerType
| OwnerTypeId | Name |
|---|---|
| 1 | User |
| 2 | Organization |

### Acceptance
| AcceptanceId | Name |
|---|---|
| 1 | AcceptAllRepos |
| 2 | AcceptSelectedRepoOnly |
| 3 | AcceptSelectedRepoInAllVersions |

### AppStatus
| AppStatusId | Name |
|---|---|
| 1 | Active |
| 2 | Disabled |
| 3 | Archived |

### AppLinkType
| AppLinkTypeId | Name |
|---|---|
| 1 | GitProfile |
| 2 | Repo |

### LogSeverity
| LogSeverityId | Name | Numeric |
|---|---|---|
| 1 | Trace | 10 |
| 2 | Debug | 20 |
| 3 | Info  | 30 |
| 4 | Warn  | 40 |
| 5 | Error | 50 |
| 6 | Fatal | 60 |

### ActionType
| ActionTypeId | Name |
|---|---|
| 1 | Append |
| 2 | Fixed |
| 3 | Clear |
| 4 | ClearAll |

### AuditActionType
| AuditActionTypeId | Name |
|---|---|
| 1  | ProfileCreate |
| 2  | ProfileUpdate |
| 3  | ProfileDelete |
| 4  | GitProfileCreate |
| 5  | GitProfileUpdate |
| 6  | GitProfileDelete |
| 7  | RepoCreate |
| 8  | RepoUpdate |
| 9  | RepoDelete |
| 10 | AppCreate |
| 11 | AppUpdate |
| 12 | AppDelete |
| 13 | AppLinkChange |
| 14 | LogPush |
| 15 | LogQuery |
| 16 | AuthSuccess |
| 17 | AuthFail |
| 18 | MigrationRun |

### AuditOutcome
| AuditOutcomeId | Name |
|---|---|
| 1 | Success |
| 2 | Rejected |
| 3 | Error |

---

## RolePermission seeds

### Admin (RoleId=1) — every permission
Insert one row `(RoleId=1, PermissionId=N)` for every PermissionId 1..17.

### Editor (RoleId=2) — read + modify, no create/delete
| RoleId | PermissionId | Permission Name |
|---|---|---|
| 2 | 2  | AppView |
| 2 | 3  | AppModify |
| 2 | 6  | ProfileView |
| 2 | 10 | GitProfileView |
| 2 | 11 | GitProfileModify |
| 2 | 13 | RepoView |
| 2 | 14 | RepoModify |
| 2 | 16 | HistoryView |

> `LogPush` is intentionally **not** in either role — CI/CD push is gated by Profile credentials + URL/branch/Acceptance, not by a role check.

---

## ConfigKv seeds

| KeyName | ValueText |
|---|---|
| LogLevelMin | Info |
| PluginVersion | 2.0.0 |
| RatePerMinPerProfile | 60 |
| MaxPushPayloadBytes | 1048576 |

---

## MigrationState seed

A single row inserted at the end of the bootstrap migration:

| PluginVersion | AppliedAt | Checksum |
|---|---|---|
| 2.0.0 | unix-now | sha256(schema.sql + seeds) |

---

## No default Profile

The plugin does **not** seed any plugin Profile. The first WP admin user with `manage_options` capability that visits the admin UI is offered a one-time bootstrap form to create the first Profile (UserName, Email). That Profile is auto-assigned `RoleId=1 (Admin)` and gets a freshly generated `GeneratedKeyApi` / `Token` / `TempToken`. This event is recorded in `AuditTrail` (`AuditActionType=ProfileCreate`).
