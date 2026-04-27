# App Entity (v2)

**Version:** 2.1.0
**Updated:** 2026-04-27

---

## Identity

| Field | Required | Notes |
|-------|----------|-------|
| AppName | Yes | Display name |
| AppSlug | Yes | URL/identifier slug, unique. Format `[a-z0-9][a-z0-9-]*` per locked decision 11. |
| Description | No | Free text |
| ProfileId | Yes | Owner Profile; supplies credentials for CI/CD. `ON DELETE RESTRICT` per locked decision 12. |
| AppStatusId | Yes | Active / Disabled / Archived. `ON DELETE RESTRICT` per locked decision 12. |

Credentials: **App has no own GeneratedKeyApi/Token/TempToken**. CI/CD calls authenticate using the parent Profile's tokens; resolution from request body to App happens through `AppLink`.

### Locked decisions referenced from this section

- **Locked decision 10 — Polymorphic linkage.** `AppLink` carries either `TargetRepoId` OR `TargetGitProfileId` (XOR), discriminator implicit, no `LinkType` column. See "Linkage" below + §02 (AppLink table) + §18 v2.9.3 (DDL CHECK + UNIQUE + FK CASCADE).
- **Locked decision 11 — `AppSlug` regex.** `[a-z0-9][a-z0-9-]*` — kebab-case identifier used in `/append-log` payloads. SQLite-level UNIQUE constraint mandatory. See §05 (REST contract) + §97 AC-17.
- **Locked decision 12 — Identity is exactly the 5 columns above; speculative additions are FORBIDDEN.** The `App` table MUST NOT carry the Phase B1-blocked fields `Environment`, `Platform`, `OwnerEmail` (or any other speculative identity column) until the Phase B1 user decision unblocks them. Adding them speculatively is a schema violation per §97 AC-17 and §15 `GL-SCHEMA-DRIFT`. Rationale: undecided columns mean undecided semantics — adding now would lock the project to whatever default behaviour the column happens to acquire in early code, foreclosing the actual decision. Status: **forbidden-by-default**, awaiting user reply `B1: add Environment only` / `B1: add all three` / `B1: keep forbidden` (the last makes the current state permanent).
- **Locked decision 13 — Lifecycle column is `AppStatusId` lookup, not free-text.** See "Lifecycle" below + §08 (Audit) + §18 v3.8.0 DDL.



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
