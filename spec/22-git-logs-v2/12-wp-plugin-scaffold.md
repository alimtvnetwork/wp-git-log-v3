# WordPress Plugin Scaffold (v2)

**Version:** 2.0.0  
**Updated:** 2026-04-25

Maps spec entities to a concrete PHP file tree. PSR-4 autoloading; one class/enum/trait per file; no business logic in the bootstrap file.

---

## File tree

```
git-logs/
├── git-logs.php                       # Bootstrap: plugin header, autoloader, plugin_loaded hook
├── readme.txt                         # WP.org readme (with milestone markers)
├── uninstall.php                      # Drops options; preserves SQLite DB unless GITLOGS_DROP_DB=1
├── composer.json                      # PSR-4 mapping: "GitLogs\\" => "inc/"
├── inc/
│   ├── Plugin.php                     # Singleton entry; wires hooks
│   ├── Bootstrap/
│   │   ├── Activator.php              # On activation: ensure DB file, run migrations
│   │   ├── Deactivator.php
│   │   └── Uninstaller.php
│   ├── Db/
│   │   ├── SqliteConnection.php       # PDO wrapper, single root DB file
│   │   ├── QueryBuilder.php           # Lightweight typed query helper (no ORM dep)
│   │   └── Repositories/
│   │       ├── ProfileRepository.php
│   │       ├── GitProfileRepository.php
│   │       ├── RepoRepository.php
│   │       ├── RepoVersionRepository.php
│   │       ├── PipelineRepository.php
│   │       ├── LogEntryRepository.php
│   │       ├── ErrorLogEntryRepository.php
│   │       ├── AppRepository.php
│   │       ├── AppLinkRepository.php
│   │       ├── HistoryRepository.php
│   │       ├── ActionRepository.php
│   │       ├── AuditTrailRepository.php
│   │       ├── ConfigKvRepository.php
│   │       └── MigrationStateRepository.php
│   ├── Migrations/
│   │   ├── MigrationRunner.php        # Boot-time orchestrator
│   │   ├── V2_0_0.php                 # Initial schema + lookup-table seeds + RolePermission seeds
│   │   └── (future) V2_1_0.php …
│   ├── Enums/
│   │   ├── UserStatus.php
│   │   ├── Role.php
│   │   ├── Permission.php
│   │   ├── Provider.php
│   │   ├── OwnerType.php
│   │   ├── Acceptance.php
│   │   ├── AppStatus.php
│   │   ├── AppLinkType.php
│   │   ├── LogSeverity.php
│   │   ├── ActionType.php
│   │   ├── AuditActionType.php
│   │   └── AuditOutcome.php
│   ├── Domain/
│   │   ├── Validation/
│   │   │   ├── RepoUrlParser.php      # Parses provider/owner/repo/versionSuffix
│   │   │   ├── AcceptanceMatcher.php  # AcceptAllRepos / Selected / SelectedAllVersions
│   │   │   └── BranchRestrictionGate.php
│   │   ├── Auth/
│   │   │   ├── WpBridgeAuthenticator.php   # App Password / cookie -> Profile
│   │   │   ├── TempTokenAuthenticator.php  # CI/CD lane
│   │   │   └── PermissionGate.php          # RolePermission union check
│   │   └── Services/
│   │       ├── LogIngestService.php
│   │       ├── LogQueryService.php
│   │       ├── ProfileService.php
│   │       ├── GitProfileService.php
│   │       ├── AppService.php
│   │       └── RateLimiter.php
│   ├── Rest/
│   │   ├── RestRouter.php             # Registers /git-logs/v2 namespace
│   │   ├── Controllers/
│   │   │   ├── AppendLogController.php
│   │   │   ├── FixedLogController.php
│   │   │   ├── ClearLogController.php
│   │   │   ├── ClearLogAllController.php
│   │   │   ├── GetLogsController.php
│   │   │   ├── GetPipelineLogsController.php
│   │   │   ├── GetErrorLogsController.php
│   │   │   └── GetPipelineErrorLogsController.php
│   │   ├── ResponseEnvelope.php       # Ack + Retrieval hints
│   │   └── ErrorResponder.php         # GL-* code mapping (no swallowed errors)
│   ├── Admin/
│   │   ├── AdminMenu.php              # Registers 8 top-level pages
│   │   ├── Pages/
│   │   │   ├── ProfilePage.php
│   │   │   ├── RolesPage.php
│   │   │   ├── AccessToRolesPage.php
│   │   │   ├── GitProfilePage.php
│   │   │   ├── RepoPage.php
│   │   │   ├── RepoVersionPage.php
│   │   │   ├── HistoryPage.php
│   │   │   └── ActionPage.php
│   │   └── Forms/                     # Form renderers + validators
│   ├── Logging/
│   │   ├── Logger.php                 # Level-aware writer
│   │   ├── LogLevelFilter.php         # Reads ConfigKv.LogLevelMin
│   │   └── DedupBuffer.php            # 60s LRU
│   └── Support/
│       ├── Hooks.php                  # Hook-name constants (no magic strings)
│       ├── Capabilities.php           # WP capability constants
│       └── ErrorCodes.php             # GL-* code constants
├── views/                             # Pure presentation templates (no logic)
│   └── admin/
│       ├── profile-list.php
│       ├── profile-edit.php
│       └── …
├── assets/
│   ├── css/admin.css
│   └── js/admin.js
└── tests/
    ├── Unit/
    └── Integration/
```

---

## Key bindings (spec → file)

| Spec section | Implementation file(s) |
|---|---|
| §02 schema | `inc/Migrations/V2_0_0.php` + every `inc/Db/Repositories/*` |
| §01 enums | `inc/Enums/*.php` (one PHP enum per type) |
| §03 admin UI menu | `inc/Admin/AdminMenu.php` + `inc/Admin/Pages/*` |
| §04 endpoints | `inc/Rest/RestRouter.php` + `inc/Rest/Controllers/*` |
| §05 auth/validation | `inc/Domain/Auth/*` + `inc/Domain/Validation/*` |
| §06 migrations | `inc/Migrations/MigrationRunner.php`, `V2_0_0.php` |
| §06 logger | `inc/Logging/*` |
| §07 App entity | `inc/Db/Repositories/AppRepository.php`, `AppLinkRepository.php`, `inc/Domain/Services/AppService.php` |
| §08 audit split | `HistoryRepository.php`, `ActionRepository.php`, `AuditTrailRepository.php` |
| §09 seed data | `inc/Migrations/V2_0_0.php` (within same transaction) |
| §10 rate limit | `inc/Domain/Services/RateLimiter.php` |
| §11 encryption (deferred) | reserved file `inc/Db/Crypto/` (empty placeholder in v2) |

---

## Bootstrap (`git-logs.php`) responsibilities

1. WordPress plugin header (Name, Version=2.0.0, Author, License).
2. Composer autoloader require.
3. Register activation/deactivation hooks → `Bootstrap\Activator` / `Deactivator`.
4. On `plugins_loaded`: instantiate `Plugin::instance()` which:
   - Runs `MigrationRunner::ensureLatest()`.
   - Registers REST routes via `Rest\RestRouter`.
   - Registers admin menu via `Admin\AdminMenu` (only if `is_admin()`).
   - Boots `Logging\Logger` with level from `ConfigKv.LogLevelMin`.
5. **No business logic** lives in this file.

---

## Naming reminders enforced in code review

- All DB columns/JSON keys: PascalCase.
- All hook names, capability names, error codes, REST routes: defined as constants in `inc/Support/*` — no string literals at call sites.
- One class per file; file name = class name.
- Functions ≤ 15 lines; files ≤ 300 lines (PHP guideline).

---

## PHP standards cross-reference

This scaffold inherits the cross-cutting PHP rules from [`spec/02-coding-guidelines/04-php/`](../02-coding-guidelines/04-php/00-overview.md). In particular:

| Topic | Authoritative file |
|-------|--------------------|
| Enum-only typed values; no string-literal status comparisons | [`01-enums.md`](../02-coding-guidelines/04-php/01-enums.md) |
| Forbidden patterns (swallowed errors, `@`, magic strings, etc.) | [`02-forbidden-patterns.md`](../02-coding-guidelines/04-php/02-forbidden-patterns.md) |
| PascalCase classes, camelCase methods, PascalCase array keys | [`03-naming-conventions.md`](../02-coding-guidelines/04-php/03-naming-conventions.md) |
| REST response envelope shape | [`05-response-array-standard.md`](../02-coding-guidelines/04-php/05-response-array-standard.md) |
| Spacing, import order, namespace rules | [`08-spacing-and-imports.md`](../02-coding-guidelines/04-php/08-spacing-and-imports.md) |
| PHP↔Go consistency (when CLI helpers are added) | [`10-php-go-consistency-audit.md`](../02-coding-guidelines/04-php/10-php-go-consistency-audit.md) |

If a rule here conflicts with the cross-cutting standards folder, the cross-cutting folder wins and §12 is amended.
