# App Entity (v2)

**Version:** 2.0.0  
**Updated:** 2026-04-25

---

## Identity

| Field | Required | Notes |
|-------|----------|-------|
| AppName | Yes | Display name |
| AppSlug | Yes | URL/identifier slug, unique |
| Description | No | Free text |
| ProfileId | Yes | Owner Profile; supplies credentials for CI/CD |
| AppStatusId | Yes | Active / Disabled / Archived |

Credentials: **App has no own GeneratedKeyApi/Token/TempToken**. CI/CD calls authenticate using the parent Profile's tokens; resolution from request body to App happens through `AppLink`.

---

## Linkage (polymorphic via AppLink)

- One App may have multiple `AppLink` rows (history of (re)linking preserved).
- Exactly one of `TargetGitProfileId` / `TargetRepoId` is populated per row, matching `AppLinkTypeId`.
- Disconnect = set `IsActive=0`, `DisconnectedAt=now`. Reconnect = insert new row.

### Resolution at log push

Given inbound `RepoUrl`:
1. Resolve `Repo` and its `GitProfile`.
2. Find active `AppLink` rows where:
   - `AppLinkTypeId=Repo` AND `TargetRepoId=Repo.RepoId`, OR
   - `AppLinkTypeId=GitProfile` AND `TargetGitProfileId=Repo.GitProfileId`.
3. If multiple Apps match, attribute the History row to all of them (one History row per App link is acceptable; or store the primary App and a `LinkedAppCount`). v2 stores the **first** active match in `History.AppId` and emits `AppLinkChange` audit entries when any disambiguation occurs.

---

## Lifecycle

| Status | Push accepted | Visible by default |
|--------|---------------|--------------------|
| Active | Yes | Yes |
| Disabled | No (rejected `GL-APP-NOT-ACTIVE`) | Yes |
| Archived | No | No (filter toggle to show) |

Transitions are not constrained beyond admin permission (`AppModify`).

---

## Audit

- Every status change writes `AuditTrail(AuditActionType=AppUpdate)`.
- Every `AppLink` insert/disable writes `AuditTrail(AuditActionType=AppLinkChange)` with old/new target in `Detail`.
