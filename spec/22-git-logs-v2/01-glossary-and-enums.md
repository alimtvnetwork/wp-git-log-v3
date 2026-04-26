# Glossary and Enum Catalog (v2)

**Version:** 3.8.6  
**Updated:** 2026-04-26 (Q3 Split-DB: glossary entries for `PerShaDb`, `ShaLogsRoot`)

---

## Glossary

| Term | Definition |
|------|------------|
| Profile | Plugin-internal user (UserName, Email, GeneratedKeyApi, Token, TempToken). No password. Stored in SQLite root DB. |
| GeneratedKeyApi | API key issued to a Profile. Plain string in v2 (encryption deferred). |
| Token | Long-lived token issued to a Profile. Used together with GeneratedKeyApi for admin transport. |
| TempToken | Random per-Profile value used in CI/CD bodies. **Non-authoritative**: real validation is GitHub URL + branch. |
| GitProfile | Top-most domain entity. Wraps a GitHub user or organization URL with an Acceptance mode. |
| Repo | Master repository (URL stripped of any `-vN` suffix). Child of GitProfile. |
| RepoVersion | A specific version variant of a Repo (e.g., `repo`, `repo-v2`, `repo-v100`). |
| App | User-registered logical app, linked polymorphically to a Repo or a GitProfile via AppLink. |
| AppLink | Polymorphic join row binding an App to a target (GitProfile \| Repo). |
| Pipeline | Named CI/CD pipeline within a (RepoVersion, Branch) scope. |
| LogEntry | Single line of pipeline output (Info/Debug/etc.). **v3.8.0**: lives in the per-SHA SQLite file, not the root DB — see §39. |
| ErrorLogEntry | Single line tagged as error output. **v3.8.0**: lives in the per-SHA SQLite file. |
| ShaRegistry | Root-DB index pointing at every per-SHA SQLite file + roll-up summary stats (last status, failure count, …). One row per (PipelineId, Sha). |
| PerShaDb | A standalone SQLite file dedicated to one Git SHA. Contains `LogEntry`, `ErrorLogEntry`, and per-SHA metadata. Path: `<dataDir>/<ShaLogsRoot>/<Sha[0:2]>/<Sha>.db`. Schema in §39. |
| ShaLogsRoot | `ConfigKv` key (default `logs`) — folder name (relative to plugin data dir) that contains the two-char-prefix shard tree of per-SHA `.db` files. |
| History | Per-RepoVersion event timeline (who pushed what, when, on which branch, result). |
| PipelineAction | Enum-typed audit row for pipeline operations (Append, Fixed, Clear, ClearAll). **v3.8.0**: renamed from `Action`. |
| SystemEvent | **v3.8.0** — Business state changes that aren't Git pushes (ProfileCreated, KeyRevoked, AppCreated, RoleAssigned, …). Loose polymorphic target. |
| AuditTrail | System-wide append-only log of every endpoint hit and transaction outcome. |
| MigrationState | DB-config row marking a plugin version as migrated (boot-time idempotent). |

---

## Enum Catalog

> Each enum has a lookup table `{EnumName}` with PK `{EnumName}Id` and `Name` column. Codes PascalCase. Code never compares against raw strings.

### UserStatus
| Code | Meaning |
|------|---------|
| Active | Profile usable |
| Suspended | Auth blocked, tokens preserved |
| Revoked | Auth blocked, tokens invalidated |

### Role
| Code | Meaning |
|------|---------|
| Admin | All permissions implicitly |
| Editor | Subset (View, Modify) |

### Permission
| Code | Meaning |
|------|---------|
| AppCreate | Create apps |
| AppView | Read apps |
| AppModify | Update apps |
| AppDelete | Delete apps |
| ProfileCreate / ProfileView / ProfileModify / ProfileDelete | Plugin Profile CRUD |
| GitProfileCreate / GitProfileView / GitProfileModify / GitProfileDelete | GitProfile CRUD |
| RepoView / RepoModify / RepoDelete | Repo ops |
| HistoryView | Read history |
| LogPush | CI/CD push (granted via Profile, not role check) |

### Provider
| Code | Meaning |
|------|---------|
| GitHub | Active in v2 |
| GitLab | Reserved |

### OwnerType (retired in v3.8.0)

> Replaced by `GitProfile.IsOrganization` (boolean). The two-value lookup table was overkill for a true binary; the boolean drives URL canonicalization (`github.com/$org/$repo` vs `github.com/$username/$repo`) and the admin-UI "Is organization" checkbox directly.

### Acceptance
| Code | Meaning |
|------|---------|
| AcceptAllRepos | Any repo under owner accepted |
| AcceptSelectedRepoOnly | Only the exact repo URL accepted |
| AcceptSelectedRepoInAllVersions | Main repo + all `-vN` variants accepted |

### AppStatus
| Code | Meaning |
|------|---------|
| Active | Push accepted |
| Disabled | Push rejected; visible in UI |
| Archived | Push rejected; hidden by default |

### AppLinkType
| Code | Meaning |
|------|---------|
| GitProfile | App targets a GitProfile |
| Repo | App targets a specific Repo |

### LogSeverity
| Code | Numeric | Meaning |
|------|---------|---------|
| Trace | 10 | Verbose |
| Debug | 20 | Debug |
| Info | 30 | Info |
| Warn | 40 | Warning |
| Error | 50 | Error |
| Fatal | 60 | Fatal |

### PipelineActionType (renamed from `ActionType` in v3.8.0)
| Code | Meaning |
|------|---------|
| Append | `/append-log` write |
| Fixed | `/fixed-log` write |
| Clear | `/clear-log` write |
| ClearAll | `/clear-log-all` write |

### SystemEventType (NEW v3.8.0)
| Code | Meaning |
|------|---------|
| ProfileCreated | New plugin Profile row |
| ProfileDeleted | Profile removed |
| ProfileStatusChanged | Active ↔ Suspended ↔ Revoked |
| RoleAssigned | Profile granted a Role |
| RoleRevoked | Role removed from a Profile |
| GitProfileCreated | New GitProfile row |
| GitProfileAcceptanceChanged | Acceptance enum value changed |
| GitProfileBranchRestrictionChanged | `IsRestrictInBranch` / `StrictBranch` modified |
| AppCreated | App row created |
| AppStatusChanged | Active ↔ Disabled ↔ Archived |
| AppLinkAdded | New AppLink row |
| AppLinkRemoved | AppLink soft-disabled (`IsActive = 0`) |
| SshKeyRegistered | New SshKey row |
| SshKeyRevoked | SshKey `IsActive` flipped to 0 |
| SshKeyRotated | New key registered to replace an existing one |
| TempTokenRotated | Profile.TempToken regenerated |

### AuditActionType
| Code | Meaning |
|------|---------|
| ProfileCreate / ProfileUpdate / ProfileDelete | Plugin profile changes |
| GitProfileCreate / GitProfileUpdate / GitProfileDelete | GitProfile changes |
| RepoCreate / RepoUpdate / RepoDelete | Repo changes |
| AppCreate / AppUpdate / AppDelete / AppLinkChange | App changes |
| LogPush / LogQuery | Endpoint access |
| AuthSuccess / AuthFail | Auth outcomes |
| MigrationRun | Migration executed |

### AuditOutcome
| Code | Meaning |
|------|---------|
| Success | Completed |
| Rejected | Validation/policy denied |
| Error | Runtime failure |
