# Glossary and Enum Catalog (v2)

**Version:** 3.8.10  
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
| Pipeline | Named CI/CD pipeline within a (RepoVersion, Branch) scope. Carries two boolean state columns: `HasError` (current state) and `PreviousHasError` (state immediately before the latest transition — see next two rows). |
| HasError | Boolean (0/1) on `Pipeline`. `1` means the pipeline currently has at least one unresolved error reported via `/append-log` with `HasError=true`; cleared back to `0` by `/fixed-log`. **Sticky**: remains `1` across subsequent `/append-log` calls until explicitly cleared (AC-13). |
| PreviousHasError | **NEW v2.9.2 (Phase 9).** Boolean (0/1) on `Pipeline`. Holds the value `HasError` had **immediately before** the most recent `/append-log` or `/fixed-log` transition, so the UI can label transitions as `first-failure` (`0→1`), `still-failing` (`1→1`), `just-recovered` (`1→0`), or `still-green` (`0→0`) without scanning the per-SHA log file. Write rule: every server-side mutation of `HasError` MUST set `PreviousHasError = OLD.HasError` in the same SQL transaction (single `UPDATE` statement, never read-modify-write). Back-fill rule on the v2.9.1 → v2.9.2 migration: `UPDATE Pipeline SET PreviousHasError = HasError;` so the very first transition after upgrade is correctly labeled `still-*` rather than spuriously appearing as a fresh `first-failure` / `just-recovered` event. |
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
| SshKey | **v2.9.1 (Phase 5)** — Public key registered for Lane B (CI/CD) signing. Deploy-key model: each `SshKey` row binds to exactly one `RepoId`. Stored fields: `Fingerprint` (`SHA256:` + base64 of SHA-256 of public key, RFC 4716), `KeyType`, `PublicKey` (full OpenSSH single-line), `OwnedByProfileId`, `IsActive`, `LastUsedAt`, `RevokedAt`. Authoritative for SSH lane authentication; replaces `TempToken` when `ConfigKv.SshAuthMode = required`. See §31. |
| Ed25519Signature | **v2.9.1 (Phase 5)** — OpenSSH-format signature (PEM-armored `-----BEGIN SSH SIGNATURE-----` block) produced by `ssh-keygen -Y sign -n git-logs@v2 -H sha512` over the canonical `GL-SSHSIG-V1` signing string (HTTP method, path, timestamp, nonce, sha256-hex of body). Verified server-side via `ssh-keygen -Y verify` or `phpseclib`. Ed25519 is the recommended `KeyType` (compact, fast, no parameter pitfalls); `ssh-rsa` and `ecdsa-sha2-*` also accepted. See §31 step 8. |
| SshNonce | Replay-defense row — `(SshKeyId, Nonce)` unique within `SshReplayWindowSec` (default 300s). Pruned on every request (LIMIT `SshNonceJanitorBatch`, default 100) and via daily WP-cron. No long-term forensic copy. |

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
