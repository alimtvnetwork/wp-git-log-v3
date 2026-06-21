# App Entity (v2)

**Version:** 2.3.0
**Updated:** 2026-06-21 (Phase 153 — added locked decisions 14 (Laravel raw-PDO ingest posture) + 15 (Laravel Lane A auth = Sanctum) closing LBR-06 + LBR-08 from `.lovable/memory/audit/v2-deterministic/phase-153-laravel-binding-confidence-analysis.md`; bound as AC-81 + AC-82 in §97)

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
- **Locked decision 12 — Identity is exactly the 5 columns above; speculative additions are PERMANENTLY FORBIDDEN.** The `App` table MUST NOT carry `Environment`, `Platform`, `OwnerEmail`, or any other identity-shaped column. Adding any such column is a schema violation per §97 AC-17 and `GL-SCHEMA-DRIFT` (§15). Rationale: the v2 App entity is a deployment-target identifier, not a metadata bag — environment/platform belong on the inbound `Pipeline` row (already carried by §08), and owner contact info belongs on the parent `Profile` (§02). Status: **permanent** as of 2026-04-28 (Phase 147 — user reply `B1: keep forbidden` makes locked-decision-12 final; the prior "awaiting Phase B1 unblock" hedge is retired). Future identity expansion REQUIRES a new locked decision (14+) with a fresh changelog row, not an edit to this one.
- **Locked decision 13 — Lifecycle column is `AppStatusId` lookup, not free-text.** See "Lifecycle" below + §08 (Audit) + §18 v3.8.0 DDL.
- **Locked decision 14 — Laravel binding (slot 40) persistence posture: raw PDO MANDATORY on ingest paths; Eloquent permitted ONLY on Lane A read paths bounded by pagination.** Applies to every controller/service in `40-laravel-endpoint-definition.md` that writes to the root DB or any per-SHA file DB (Lane B writers: `/append-log`, `/fixed-log`, `/clear-log`, `/clear-log-all`; root-DB writers under Lane A: any AuditTrail / AppLink mutation). **Forbidden:** Eloquent `Model::create()`, `Model::save()`, `$model->update()`, Eloquent transactions (`DB::transaction(...)` with model touches), or any code path where Eloquent's `creating`/`updating`/`saved` events fire on an ingest row. **Required:** direct `PDO` handle (via `DB::connection('gl_root')->getPdo()` is acceptable; Eloquent QueryBuilder `DB::table(...)->insert(...)` is NOT — it still routes through Laravel's connection event dispatcher which breaks the `BEGIN IMMEDIATE` + retry-with-jitter contract owned by spec/13 §97 AC-22). **Rationale:** Eloquent's model hydration + event dispatcher adds 3–8× per-row latency AND its `DB::transaction` helper does not expose `BEGIN IMMEDIATE` (only `BEGIN DEFERRED`), which silently breaks AC-22's write-lock acquisition contract under SQLite WAL — implementer cannot discover this until production lock-contention. Lane A read paths are exempt because (a) latency is non-critical, (b) row count is bounded by pagination, (c) reads do not contend on the write lock. Status: **permanent** as of 2026-06-21 (Phase 153 — closes LBR-06 from `.lovable/memory/audit/v2-deterministic/phase-153-laravel-binding-confidence-analysis.md`, which was the single HIGH-severity delivery-risk row for slot 40). Bound by §97 **AC-81** `[critical]`. Future framework bindings (Symfony slot 41, Slim, Lumen) MUST author their own equivalent locked decision pinning a raw-driver ingest posture — inheriting decision 14 is FORBIDDEN (each framework has its own ORM event-dispatcher idiom).
- **Locked decision 15 — Laravel binding (slot 40) Lane A authentication driver: Sanctum bearer tokens MANDATORY.** Applies to every Lane A route in `40-laravel-endpoint-definition.md` (`/get-logs`, `/get-pipeline-logs`, `/get-error-logs`, `/get-pipeline-error-logs`). **Forbidden:** Laravel Passport (full OAuth2 server — over-engineered for App-Password-equivalence; introduces an OAuth authorization-server surface that has no analogue in §05's WP App Password Lane A definition and cannot be reverse-mapped onto the §19 permission matrix without inventing scope-to-permission glue), custom token brokers (re-implements what Sanctum already provides), WP-cookie-bridge (couples the Laravel binding to a WordPress installation, defeating the framework-binding axis), JWT libraries (`tymondesigns/jwt-auth`, `firebase/php-jwt` — Sanctum's opaque token model intentionally rejects JWT to avoid stateless-revocation gaps), `auth:basic` (no token, breaks token-revocation parity with §05 Lane A). **Required:** Sanctum bearer token issued to a `Profile` row (mapped 1:1 to a Sanctum `PersonalAccessToken`); middleware alias `gl.lane-a` resolves bearer token → Profile → permission check via `gl.permission:{Perm}`. **Rationale:** Sanctum is the lightest-weight Laravel token broker that supports per-token revocation (matches §05 Lane A App-Password revoke semantics), per-token ability/permission scopes (maps cleanly onto §19), and has no OAuth-server surface area. Passport's full OAuth2 model maps poorly onto the §19 permission matrix because §19 is row-level (`RolePermission`), not scope-string-based. Status: **permanent** as of 2026-06-21 (Phase 153 — closes LBR-08 from the same confidence-analysis memo). Bound by §97 **AC-82** `[critical]`. Future framework bindings MUST author their own auth-driver locked decision — inheriting decision 15 is FORBIDDEN.



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
