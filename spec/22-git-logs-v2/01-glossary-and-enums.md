# Glossary and Enum Catalog (v2)

**Version:** 2.0.0  
**Updated:** 2026-04-25

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
| LogEntry | Single line of pipeline output (Info/Debug/etc.). |
| ErrorLogEntry | Single line tagged as error output. |
| History | Per-RepoVersion event timeline (who pushed what, when, on which branch, result). |
| Action | Enum-typed audit row for repo/pipeline operations (Append, Fixed, Clear, ClearAll). |
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

### OwnerType
| Code | Meaning |
|------|---------|
| User | GitHub user account |
| Organization | GitHub organization |

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

### ActionType
| Code | Meaning |
|------|---------|
| Append | `/append-log` write |
| Fixed | `/fixed-log` write |
| Clear | `/clear-log` write |
| ClearAll | `/clear-log-all` write |

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
