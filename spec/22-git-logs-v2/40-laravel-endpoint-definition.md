---
kind: future-spec
todo_audit_exempt: true
description: Laravel framework binding for the Git Logs v2 REST surface. Sibling to §04 (WordPress binding). Defines the route file, base FormRequest with `resolveQParam()` collapse resolver, FormRequest `$errorCodes` mapping convention, controller signatures, middleware lane mapping, domain-service interface contracts, raw-PDO ingest posture, a worked migration example, and a testing harness story. Per Lesson #36 this file LINKS to the upstream contracts (§04 endpoints, §05 auth, §15 error codes, spec/03-error-manage envelope, spec/04-database-conventions DDL/boolean storage, spec/02-coding-guidelines PHP rules) and MUST NOT restate them.
content_axis: framework-binding
axis_rationale: "Per-framework binding of the v2 REST contract"
---

# Laravel Endpoint Definition (Git Logs v2 — Laravel Binding)

**Version:** 1.2.0
**Updated:** 2026-06-28 (Phase 153 — slot-40 confidence-lift round 2: closes LBR-04 (`?q=` resolver — also fixes the v1.0.0/v1.1.0 misnomer "base64-json"; §04 actually uses URL-style `q=github.com/{org}/{repo}` shorthand), LBR-05 (`GlValidationException` ⇄ §15 `protected array $errorCodes` mapping convention pinned), LBR-07 (`LogIngestService` / `ShaRegistryRepository` / `SplitDbWriter` interface signatures added in new §4.1), LBR-09 (worked Laravel migration example added in new §6.1 — framework-agnostic SQL files + Phinx-style migrator binding contract), LBR-10 (testing-harness story added in new §10 — Pest-preferred, `:memory:` for unit + file-DB for integration, AC-22 retry contract test pattern). All six lifts bound by new AC-83 `[high]` in §97. Cross-cutting parity AC-80 + AC-81 + AC-82 mechanically locked in CI by new `linter-scripts/test/test-ac80-laravel-wp-endpoint-parity.sh` (LBR-12 closure).)
<!-- h10-verified-phase: 153 -->
**Status:** Draft (future-spec — Laravel package code lives downstream, not in this spec-only repo)
**Pairs with:** [`04-rest-api-endpoints.md`](./04-rest-api-endpoints.md) (WordPress binding — authoritative for verb, path, body, response, error code; this file is binding-only)
**Sibling of (downstream):** WordPress plugin (§04), planned Symfony binding (future slot 41)

---

## 1. Scope and Non-Goals

This document binds the **already-defined** Git Logs v2 REST contract to Laravel 10+ idioms. It **does not** define new endpoints, error codes, validation rules, DDL, or authentication semantics. Every such element is owned upstream and cited via the cross-module anchor table below (mirror of AC-79 / Lesson #36 / Lesson #37).

| Concern | Authoritative source | Restate-here forbidden? |
|---|---|---|
| Endpoint inventory (10 logical / 8 paths), verbs, request/response shapes | [`04-rest-api-endpoints.md`](./04-rest-api-endpoints.md) | YES |
| Error envelope `{ ErrorCode, TraceId, Message, Details }` + classification | [`spec/03-error-manage/00-overview.md`](../03-error-manage/00-overview.md) | YES |
| `GL-*` error code catalog | [`15-error-codes.md`](./15-error-codes.md) | YES |
| Auth lanes (A = WP App Password / cookie · B = TempToken or SSH) + 8/10-step validation order | [`05-auth-and-validation.md`](./05-auth-and-validation.md) + [`31-ssh-key-auth.md`](./31-ssh-key-auth.md) | YES |
| DDL, boolean storage convention, SQLite locking posture | [`spec/04-database-conventions/02-schema-design.md`](../04-database-conventions/02-schema-design.md) §2.1 + spec/13 §97 AC-22 (cited via AC-78) | YES |
| Per-SHA split-DB log storage (root DB + `<Sha>.db` files) | [`39-split-db-log-storage.md`](./39-split-db-log-storage.md) + [`spec/05-split-db-architecture/00-overview.md`](../05-split-db-architecture/00-overview.md) | YES |
| PHP coding guidelines (PSR-12, type-hints, exception hierarchy, naming) | [`spec/02-coding-guidelines/03-php/`](../02-coding-guidelines/03-php/) | YES |
| Streaming (NDJSON) ingest wire format (Phase P2) | `04-rest-api-endpoints.md` §1.1 | YES |
| Migration file format + migrator CLI surface | [`06-migrations-and-logger.md`](./06-migrations-and-logger.md) | YES |

> **Lesson #36 reminder.** A row's "Restate-here forbidden?" column is `YES` by construction. If a future amendment needs to drop the FORBIDDEN flag, author a fresh locked decision in `07-app-entity.md` and add a §99 audit row before editing.

---

## 2. Route File Layout

Routes are declared in `routes/api.php` under a single versioned group. Path strings are **identical** to §04 (minus the `/wp-json` prefix — replaced by Laravel's `/api` prefix at the framework boundary, which is the only divergence from the WordPress binding):

```php
// routes/api.php — declarative only; controllers below
Route::prefix('git-logs/v2')->group(function () {

    // Lane B (CI/CD writers) — middleware: gl.lane-b
    Route::middleware('gl.lane-b')->group(function () {
        Route::post  ('append-log',     [LaneB\AppendLogController::class,    '__invoke']);
        Route::put   ('fixed-log',      [LaneB\FixedLogController::class,     '__invoke']);
        Route::post  ('clear-log',      [LaneB\ClearLogController::class,     '__invoke']);
        Route::post  ('clear-log-all',  [LaneB\ClearLogAllController::class,  '__invoke']);
    });

    // Lane A (Admin readers) — middleware: gl.lane-a + permission:HistoryView
    Route::middleware(['gl.lane-a', 'gl.permission:HistoryView'])->group(function () {
        Route::get('get-logs',                   [LaneA\GetLogsController::class,                 '__invoke']);
        Route::get('get-pipeline-logs',          [LaneA\GetPipelineLogsController::class,         '__invoke']);
        Route::get('get-error-logs',             [LaneA\GetErrorLogsController::class,            '__invoke']);
        Route::get('get-pipeline-error-logs',    [LaneA\GetPipelineErrorLogsController::class,    '__invoke']);
    });
});
```

The 10-logical→8-path `?q=` collapse rule (§04) is preserved verbatim. Implementation is pinned in §3.1 below (single `BaseLaneAFormRequest::resolveQParam()` method — multiple-design ambiguity closed per LBR-04).

> **Mechanical lock.** Verb+path parity between this §2 route block and §04's Endpoint Map is enforced by [`linter-scripts/test/test-ac80-laravel-wp-endpoint-parity.sh`](../../linter-scripts/test/test-ac80-laravel-wp-endpoint-parity.sh) (AC-80 + AC-81 + AC-82 graduation per Lesson L21 / Phase P47–P49 chain). The gate also asserts the AC-81 raw-PDO posture string and the AC-82 Sanctum citation appear in this file.

---

## 3. FormRequest Validation Classes

Every endpoint uses a dedicated `FormRequest` subclass. The class MUST:

1. Implement `rules()` that mirrors §04's request shape **without** re-asserting any rule already in §05's 8-step (TempToken) or 10-step (SSH) ordered validation — those run in middleware, not `FormRequest`.
2. Implement `authorize()` returning `true` (authorization lives in middleware per spec/03-error-manage classification: `[auth]` ≠ `[validation]`).
3. Declare a `protected array $errorCodes` property mapping each `rules()` key (or `key.rule_name` for rule-specific overrides) to its canonical `GL-VALIDATION-*` code from §15. Override `failedValidation(Validator $validator)` to throw `Gl\Exceptions\GlValidationException` carrying the resolved code (LBR-05 closure — the mapping convention is now normative, eliminating the four-design ambiguity from v1.1.0). Example:

   ```php
   final class AppendLogRequest extends BaseLaneBFormRequest
   {
       /** §15 code mapping — rule-key OR key.rule_name → GL-VALIDATION-* code. */
       protected array $errorCodes = [
           'RepoUrl'            => 'GL-VALIDATION-MISSING-FIELD',
           'RepoUrl.url'        => 'GL-VALIDATION-REPOURL-MALFORMED',
           'Branch'             => 'GL-VALIDATION-MISSING-FIELD',
           'HasError'           => 'GL-VALIDATION-MISSING-FIELD',
           'HasError.boolean'   => 'GL-VALIDATION-FIELD-TYPE',
       ];

       public function rules(): array
       {
           return [
               'RepoUrl'  => ['required', 'url', 'max:2048'],
               'Branch'   => ['required', 'string', 'max:255'],
               'HasError' => ['required', 'boolean'],
               // … per 17-openapi.yaml; hand-editing forbidden.
           ];
       }
   }
   ```

   Resolution order inside `failedValidation()`: (a) try `"{key}.{ruleName}"`; (b) fall back to `"{key}"`; (c) hard-fail with `GL-INTERNAL-MAPPING-MISSING` (never silently emit Laravel's default `ValidationException`). The mapping table is per-FormRequest (not global) because the same field can fail different rules with different `GL-*` codes; centralizing it in `$errorCodes` keeps every binding row co-located with the `rules()` row it documents.

Field shapes, types, and length caps are sourced from `17-openapi.yaml` (the single source of truth for wire shapes). Laravel `FormRequest` `rules()` arrays are generated from the OpenAPI document at build time; hand-editing of `rules()` is forbidden (matches §04's wire-format-from-OpenAPI invariant). The `$errorCodes` mapping table IS hand-authored — it is the only per-request implementer surface in this file.

### 3.1 `?q=` shorthand resolver (LBR-04 closure)

§04 defines two Lane A endpoints with a shorthand query parameter (`/get-logs?q=…` and `/get-pipeline-logs?q=…`). Per §04 row #6/#8 the shorthand shape is **URL-style** — `q=github.com/{org}/{repo}` (NOT base64-JSON; v1.0.0/v1.1.0 of this file mis-stated the format). The same route handler MUST accept BOTH `?q=…` AND the explicit `?RepoUrl=…&Branch=…` query shape, expanding the shorthand into the explicit fields before validation.

Pinned implementation pattern (closes LBR-04's "four-design ambiguity"):

```php
abstract class BaseLaneAFormRequest extends FormRequest
{
    /**
     * Expand the §04 ?q= URL-style shorthand into explicit RepoUrl + Branch.
     * MUST run BEFORE rules() validation. Idempotent: explicit fields win.
     *
     * Shorthand shape (per spec/22 §04 row #6/#8):
     *   q=github.com/{org}/{repo}            → RepoUrl=https://github.com/{org}/{repo}
     *   q=github.com/{org}/{repo}@{branch}   → RepoUrl=… + Branch={branch}
     * Branch default (when @branch absent) is resolved from the GitProfile
     * row by the LogIngestService, NOT here — keeps this resolver pure.
     */
    protected function prepareForValidation(): void
    {
        $q = $this->query('q');
        if ($q === null || $q === '') return;

        // Reject malformed shorthand early with the §15 code — never let
        // it reach rules() where the error would be a generic "url" miss.
        if (!preg_match('#^(?<host>[^/]+)/(?<org>[^/]+)/(?<repo>[^/@]+)(?:@(?<branch>.+))?$#', $q, $m)) {
            throw new GlValidationException('GL-VALIDATION-REPOURL-MALFORMED', [
                'q' => $q,
                'reason' => 'shorthand_parse_failed',
            ]);
        }

        $merge = [];
        if (!$this->has('RepoUrl')) {
            $merge['RepoUrl'] = "https://{$m['host']}/{$m['org']}/{$m['repo']}";
        }
        if (!$this->has('Branch') && !empty($m['branch'])) {
            $merge['Branch'] = $m['branch'];
        }
        if ($merge !== []) $this->merge($merge);
    }
}
```

`BaseLaneAFormRequest` is the **single** location for `resolveQParam()` semantics — duplicating the logic across multiple FormRequests, putting it in a global middleware (which would run AFTER route binding and miss `prepareForValidation()`'s pre-rule timing), or inlining it per controller are all FORBIDDEN. Lane B FormRequests do NOT extend this base class (Lane B has no `?q=` shorthand per §04).

---

## 4. Controller Signatures

Controllers are single-action invokable classes (PSR-12 + spec/02 PHP guidelines). Example skeleton (no body — body is downstream Laravel package code, out of scope for this spec-only repo):

```php
final class AppendLogController
{
    public function __construct(
        private readonly LogIngestService $ingest,            // domain service
        private readonly ShaRegistryRepository $shaRegistry,  // root DB
        private readonly SplitDbWriter $splitDb,              // per-SHA file writer
    ) {}

    public function __invoke(AppendLogRequest $request): JsonResponse
    {
        $result = $this->ingest->append($request->validated());
        return response()->json($result->toEnvelope(), 200);  // envelope per spec/03
    }
}
```

Constructor injection is mandatory (no facade access in controllers — spec/02 PHP rule). The `LogIngestService` owns the §1.2 11-step pre-parse cap + validation order; the controller is a pure HTTP-→-domain adapter.

### 4.1 Domain service contracts (LBR-07 closure)

The three services injected above are declared as interfaces in `Gl\Domain\Contracts\` (binding to concrete classes lives in a Laravel service provider, downstream). Method signatures are normative; bodies are downstream code. Method bodies MUST satisfy the upstream invariants cited in the docblock (spec/22 §04 / §05 / §39, spec/13 §97 AC-22) — this file pins the **surface**, not the implementation.

```php
namespace Gl\Domain\Contracts;

/**
 * Owns the §04 §1.2 11-step pre-parse cap + validation order + dedup
 * window + per-SHA split-DB write. Concurrency contract: spec/13 §97 AC-22
 * (`BEGIN IMMEDIATE` + SQLITE_BUSY retry 3× 100 ms ±25 % jitter).
 */
interface LogIngestService
{
    public function append(array $validated): IngestResult;
    public function markFixed(array $validated): IngestResult;
    public function clearOne(array $validated): IngestResult;
    public function clearAll(array $validated): IngestResult;
}

/**
 * Root DB only. Owns the (RepoUrl, Branch, Sha) → ShaId resolution
 * referenced by §39 split-DB log storage. Reads MAY use Eloquent reads
 * (Lane A read paths exempt per AC-81); writes MUST use raw PDO per AC-81.
 */
interface ShaRegistryRepository
{
    public function resolveOrCreate(string $repoUrl, string $branch, string $sha): int;
    public function findByCompositeKey(string $repoUrl, string $branch, string $sha): ?ShaRegistryRow;
}

/**
 * Per-SHA file DB writer. Maintains the LRU handle pool sized by
 * ConfigKv.MaxOpenShaDbHandles per §39. acquire() MUST issue
 * `PRAGMA journal_mode=WAL; busy_timeout=5000` on first open of any
 * file DB (idempotent — WAL persists in the file header).
 */
interface SplitDbWriter
{
    public function acquire(int $shaId): \PDO;        // raw PDO; pooled, LRU-evicted
    public function release(int $shaId): void;        // returns handle to pool
    public function flushAndCloseAll(): void;         // shutdown handler binding
}
```

Concrete bindings (e.g. `Sqlite\PdoLogIngestService implements LogIngestService`) live downstream. Future framework bindings (Symfony slot 41, etc.) MAY re-use these interfaces verbatim — they are framework-agnostic by construction (no Laravel imports, no facade refs, no `Request` types).

---

## 5. Middleware Lane Mapping

| Middleware alias | Lane | Responsibilities | Sources |
|---|---|---|---|
| `gl.lane-a` | A | Authenticate via Laravel Sanctum bearer token (MANDATORY per §07 locked decision 15 + §97 AC-82 — Passport / custom brokers / WP-cookie-bridge / JWT / `auth:basic` / Breeze/Jetstream session cookies all FORBIDDEN) → resolve `PersonalAccessToken` → `Profile` row (1:1 mapping); reject with `GL-AUTH-INVALID-TOKEN` (§15) on miss | §05 Lane A + §07 locked decision 15 + §97 AC-82 |
| `gl.permission:{Perm}` | A | Look up `RolePermission` row per §19; reject with `GL-AUTHZ-PERMISSION-DENIED` (§15) if missing | §05 + §19 permission matrix |
| `gl.lane-b` | B | Run the §05 mode-gated 8-step (TempToken) or 10-step (SSH) ordered validation; reject early with the exact `GL-*` code; write `AuditTrail` row before passing to controller | §05 + §31 |

Each middleware terminates the request with a `JsonResponse` carrying the spec/03-error-manage envelope. The error envelope shape, the `TraceId` UUIDv4 generator binding, and the `Details` field structure are owned by spec/03 and MUST NOT be redefined here.

**Auth-driver pin (Lesson #16 + LBR-08 closure).** The Sanctum requirement above is normative — bound to §07 locked decision 15 + §97 AC-82 `[critical]`. The 6 forbidden auth-driver classes (Passport / custom brokers / WP-cookie-bridge / JWT / `auth:basic` / Breeze/Jetstream session cookies) are enumerated with per-driver rationale in §07 locked decision 15. Future framework bindings (Symfony slot 41, etc.) MUST author their own auth-driver locked decision — inheriting decision 15 is FORBIDDEN.

---

## 6. Database Posture

| Concern | Binding | Authority |
|---|---|---|
| Root DB driver | `pdo_sqlite` (NOT Eloquent for ingest paths — see Why below) | spec/04 §2.1 + spec/13 §97 AC-22 (locking) |
| Per-SHA file DB | Lazy-opened raw PDO handle pool, LRU-evicted per `MaxOpenShaDbHandles` | §39 + spec/05 |
| Boolean columns | `INTEGER` 0/1, NOT NULL DEFAULT 0; scanned to PHP `bool` via `(bool) $row['HasError']`; inserted as integer literal | spec/04 §2.1 cross-language boolean storage table (PHP row) |
| Concurrency | `PRAGMA journal_mode=WAL; busy_timeout=5000`; `BEGIN IMMEDIATE` for writes; `SQLITE_BUSY` retry 3× 100 ms ±25 % jitter | spec/13 §97 AC-22 (mirrored to spec/13 §10) |
| Migrations | Versioned migration markers per `06-migrations-and-logger.md`; Laravel `artisan migrate` is **disabled** — migrations are framework-agnostic SQL files executed by the same migrator as the WP binding (worked example in §6.1) | §06 + §6.1 |

**Persistence-posture pin (Lesson #16 + LBR-06 closure).** The raw-PDO ingest posture above is normative — bound to §07 locked decision 14 + §97 AC-81 `[critical]`. **Forbidden patterns** (any occurrence is a SPEC VIOLATION, detectable by `phpcs` rule or grep at code-review time): (a) Eloquent model write methods (`Model::create()`, `Model::save()`, `$model->update()`, `$model->delete()`, `Model::updateOrCreate()`, `Model::firstOrCreate()`); (b) Eloquent QueryBuilder writes (`DB::table('Log_*')->insert(...)`, `DB::table('AuditTrail')->insert(...)` — still routes through the connection event dispatcher); (c) `DB::transaction(function () { ... })` wrapping any write — uses `BEGIN DEFERRED`, silently breaks spec/13 §97 AC-22's `BEGIN IMMEDIATE` contract; (d) Eloquent model observers/events (`creating`, `created`, `updating`, `updated`, `saved`, `deleted`) attached to any model whose table appears in §02 Lane B writer paths. **Required pattern**: acquire raw `PDO` via `DB::connection('gl_root')->getPdo()`; open transactions with `$pdo->exec('BEGIN IMMEDIATE')`; catch `SQLITE_BUSY` / `SQLITE_LOCKED` and retry 3× with 100 ms ±25 % jitter per spec/13 §97 AC-22.

**Why raw PDO (not Eloquent) on ingest paths:** Eloquent's model hydration + event dispatcher adds ~3–8× latency to per-row INSERT, AND `DB::transaction` issues `BEGIN DEFERRED` (not `BEGIN IMMEDIATE`) which silently breaks spec/13 §97 AC-22's write-lock acquisition contract under SQLite WAL — implementer cannot discover this until production lock-contention. Eloquent is permitted in Lane A read paths where latency is non-critical and the row count is bounded by pagination. This split mirrors §39's "root DB raw, log shipping raw, query UI flexible" posture. Future framework bindings MUST author their own equivalent locked decision per their ORM event-dispatcher idiom — inheriting decision 14 is FORBIDDEN.

### 6.1 Migration worked example (LBR-09 closure)

Migrations are **framework-agnostic SQL files** under `migrations/` in the downstream Laravel package, executed by the same `gl-migrator` CLI that the WordPress plugin uses (§06). Laravel's built-in `artisan migrate` is **disabled** — its migration table schema (`migrations.id, migration, batch`) is incompatible with the spec/22 versioned-marker format (`MigrationMarker.MarkerId, AppliedAt, ScriptHash`).

**File layout** (one file per migration, lexicographically sortable):

```
migrations/
├── 001-init-root-schema.sql              # CREATE TABLE Profile, GitProfile, …
├── 002-init-sha-registry.sql             # CREATE TABLE ShaRegistry
├── 003-add-app-link-table.sql            # ALTER / CREATE per §02 schema
├── 004-add-audit-trail-table.sql
└── 005-add-config-kv-defaults.sql        # INSERT INTO ConfigKv (idempotent)
```

**File body convention:**

```sql
-- gl-migrator: 003-add-app-link-table.sql
-- Author: <name> · Phase: <phase-id> · Reversible: NO (additive only)
-- Cross-ref: spec/22-git-logs-v2/02-database-schema.md §AppLink

BEGIN IMMEDIATE;

CREATE TABLE IF NOT EXISTS AppLink (
    AppLinkId   INTEGER PRIMARY KEY AUTOINCREMENT,
    AppId       INTEGER NOT NULL,
    LinkKind    TEXT    NOT NULL,
    LinkValue   TEXT    NOT NULL,
    IsActive    INTEGER NOT NULL DEFAULT 1,   -- §04 §2.1 boolean storage
    CreatedAt   TEXT    NOT NULL,
    UNIQUE (AppId, LinkKind, LinkValue)
);

CREATE INDEX IF NOT EXISTS idx_AppLink_lookup
    ON AppLink (LinkKind, LinkValue, IsActive);

-- Marker insert — gl-migrator validates ScriptHash matches file content.
INSERT INTO MigrationMarker (MarkerId, AppliedAt, ScriptHash)
VALUES ('003-add-app-link-table', strftime('%Y-%m-%dT%H:%M:%fZ','now'),
        '<sha256-of-this-file-minus-marker-row>');

COMMIT;
```

**Laravel developer workflow** (fresh dev env, no Laravel migrator involvement):

```bash
# Composer post-install hook OR manual one-shot.
./vendor/bin/gl-migrator migrate           # apply all pending markers
./vendor/bin/gl-migrator status            # list applied vs pending markers
./vendor/bin/gl-migrator verify-hashes     # detect tampered applied files
```

`gl-migrator` is a small standalone PHP binary shipped with the spec/22 reference implementation (NOT a Laravel artisan command — it MUST work without bootstrapping the Laravel application container, because migrations may run before `.env` is fully populated). It reads the same `migrations/` directory regardless of host framework, providing parity with the WP plugin's migration story.

**Rollback contract:** No automatic down-migration. Rollback = author a new forward migration (`006-revert-app-link.sql`). This matches §06's append-only marker model and avoids the "down-migration drift" class where the down script silently diverges from the inverse of the up.

---

## 7. Conformance Audit Summary

Self-audit performed at authoring time:

| Check | Result |
|---|---|
| Every `GL-*` code referenced here is defined in §15 | PASS (no new codes introduced) |
| Every endpoint path matches §04 verbatim (modulo `/wp-json`→`/api` prefix) | PASS — mechanically locked by `test-ac80-laravel-wp-endpoint-parity.sh` |
| Error envelope shape matches spec/03-error-manage | PASS (envelope built by service layer, not redefined) |
| Boolean storage matches spec/04 §2.1 PHP row | PASS (INTEGER 0/1 + `(bool)` cast on scan) |
| SQLite locking matches spec/13 §97 AC-22 | PASS (PRAGMA + BEGIN IMMEDIATE + retry-with-jitter cited, not redefined) |
| No cross-module restatement (Lesson #36) | PASS (every contract row in §1 + §6 is a link, not a copy) |
| PHP coding guidelines (constructor injection, final classes, readonly props, no facades in controllers) | PASS |
| `?q=` shorthand pinned to URL-style (NOT base64-JSON) — matches §04 row #6/#8 | PASS (v1.2.0 corrected the v1.0.0/v1.1.0 misnomer; §3.1 closure of LBR-04) |
| `$errorCodes` mapping convention pinned to per-FormRequest `protected array` (LBR-05) | PASS (§3 step 3) |
| Domain service interfaces signed (LBR-07) | PASS (§4.1) |
| Migration worked example + rollback contract (LBR-09) | PASS (§6.1) |
| Test-harness story (LBR-10) | PASS (§10) |
| Slot 40 not previously used (file-slot immutability — Core memory) | PASS (slot inventory in §00 confirms 40 was vacant before this file) |

---

## 8. Open Items (Tracked Out-of-Scope)

- Laravel package skeleton (composer.json, service provider, config publish) — downstream Laravel-package repo, not this spec-only repo.
- Symfony binding (planned slot 41) — defer until first downstream Symfony consumer appears.
- Lumen / micro-framework binding — declined; Lumen is in maintenance mode (Laravel team announcement 2022).
- Streaming (NDJSON) Laravel-specific guidance (LBR-11) — deferred until Phase P2 streaming ships in §04.

---

## 9. Changelog (file-local)

| Version | Date | Notes |
|---|---|---|
| 1.0.0 | 2026-06-20 | Initial Laravel sibling binding to §04 (Phase 153). Cross-module anchors mirror AC-79 pattern (Lesson #36 + #37). Bound as AC-80 in §97. |
| 1.1.0 | 2026-06-21 | §5 + §6 now cite §07 locked decisions 14 (raw-PDO ingest posture) + 15 (Sanctum Lane A auth); bound by §97 AC-81 + AC-82 `[critical]`; closes LBR-06 + LBR-08. Confidence ~70 % → ~85 %. |
| 1.2.0 | 2026-06-28 | Round-2 confidence lift closing 5 implementer-ergonomics LBR rows: §3 step 3 now pins `$errorCodes` mapping convention (LBR-05); new §3.1 pins `BaseLaneAFormRequest::resolveQParam()` URL-style shorthand resolver — also corrects the v1.0.0/v1.1.0 "base64-json" misnomer (LBR-04); new §4.1 signs `LogIngestService` / `ShaRegistryRepository` / `SplitDbWriter` interfaces (LBR-07); new §6.1 ships a worked migration example + `gl-migrator` CLI surface + rollback contract (LBR-09); new §10 pins Pest + `:memory:`/file-DB split + AC-22 retry test pattern (LBR-10). All five bound by new AC-83 `[high]`. AC-80/81/82 mechanically locked in CI by new `test-ac80-laravel-wp-endpoint-parity.sh` (LBR-12). Confidence ~85 % → ~93 %. |

---

## 10. Testing Harness (LBR-10 closure)

**Framework choice.** Pest is preferred for new test files (modern Laravel community default; per-file `describe`/`it` mirrors PHPUnit semantics without the class-boilerplate tax). PHPUnit remains supported — the spec is harness-agnostic at the assertion level. Dusk is NOT used (no SPA browser surface — REST-only).

**SQLite fixture split.**

| Test type | DB | Why |
|---|---|---|
| Unit (service-layer, no concurrency) | `:memory:` SQLite | Fast (~0.5 ms per test); per-test isolation guaranteed by connection lifecycle |
| Integration (HTTP → middleware → service → DB) | File DB under `storage/testing/<test-id>.db`, deleted in `tearDown()` | Real WAL journal mode — `:memory:` cannot exercise the `journal_mode=WAL` PRAGMA path |
| Concurrency (AC-22 retry-with-jitter contract) | File DB + 2-process fixture (`pcntl_fork` OR `symfony/process` child) | Lock contention requires two real connections; `:memory:` is single-process by definition |

**Reference test for AC-22 retry-with-jitter** (sketch — body downstream):

```php
test('SQLITE_BUSY triggers 3× retry with 100ms±25% jitter per AC-22', function () {
    $dbPath = storage_path('testing/'.uniqid('ac22-', true).'.db');
    initSchema($dbPath);

    // Process A: hold an IMMEDIATE write lock for 250ms.
    $hold = (new Process(['php', __DIR__.'/fixtures/hold-write-lock.php', $dbPath, '250']))->start();
    usleep(50_000);  // let A acquire the lock

    // Process B (us): attempt ingest; MUST retry, MUST succeed within ~500ms.
    $start  = microtime(true);
    $result = app(LogIngestService::class)->append(sampleAppendPayload());
    $elapsed = (microtime(true) - $start) * 1000;

    expect($result->isSuccess())->toBeTrue();
    expect($elapsed)->toBeGreaterThan(150);  // at least one retry observed
    expect($elapsed)->toBeLessThan(800);     // budget per AC-22 (3 retries × 125ms ceiling)
    $hold->wait();
})->group('concurrency');
```

**Coverage floor:** every Lane B writer endpoint MUST have at least (a) one happy-path test, (b) one `GL-VALIDATION-*` error-code test per `rules()` key, (c) one AC-22 retry-class test (the test above is sufficient for all 4 Lane B endpoints since they share the same `LogIngestService` retry path — one test, not four). Lane A read endpoints MUST have happy-path + at least one `GL-AUTH-*` failure test per `gl.lane-a` rejection branch.

**Forbidden test patterns:**

1. Mocking `LogIngestService` in integration tests — defeats the purpose of integration coverage (Lane A reads MAY mock for Lane B unit tests where the Lane B fixture is heavy).
2. Eloquent factories targeting the Log_* / AuditTrail / AppLink tables — these tables are AC-81 write-protected; tests MUST use the same raw-PDO insert path the production code uses, OR seed via `gl-migrator` SQL files.
3. `DB::transaction(function () { … })` in test setup — same AC-81 violation; use `$pdo->exec('BEGIN IMMEDIATE')` explicitly.
4. `RefreshDatabase` trait — relies on Laravel's migrator; incompatible with `gl-migrator` per §6.1.
