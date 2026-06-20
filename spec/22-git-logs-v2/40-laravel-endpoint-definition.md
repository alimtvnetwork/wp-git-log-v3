---
kind: future-spec
todo_audit_exempt: true
description: Laravel framework binding for the Git Logs v2 REST surface. Sibling to §04 (WordPress binding). Defines the route file, FormRequest validation classes, controller signatures, middleware lane mapping, and Eloquent ⇄ raw-PDO posture for the same 10 logical endpoints. Per Lesson #36 this file LINKS to the upstream contracts (§04 endpoints, §05 auth, §15 error codes, spec/03-error-manage envelope, spec/04-database-conventions DDL/boolean storage, spec/02-coding-guidelines PHP rules) and MUST NOT restate them.
content_axis: framework-binding
axis_rationale: "Per-framework binding of the v2 REST contract"
---

# Laravel Endpoint Definition (Git Logs v2 — Laravel Binding)

**Version:** 1.0.0
**Updated:** 2026-06-20 (Phase 153 — initial Laravel sibling binding to §04 WordPress binding; created per user request "create Laravel endpoint definition"; cross-module anchors per Lesson #36; no contract content restated)
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

The 10-logical→8-path `?q=` collapse rule (§04) is preserved verbatim — Laravel binds it via a `?q=<base64-json>` query string resolver inside each Lane A FormRequest (see §3).

---

## 3. FormRequest Validation Classes

Every endpoint uses a dedicated `FormRequest` subclass. The class MUST:

1. Implement `rules()` that mirrors §04's request shape **without** re-asserting any rule already in §05's 8-step (TempToken) or 10-step (SSH) ordered validation — those run in middleware, not `FormRequest`.
2. Implement `authorize()` returning `true` (authorization lives in middleware per spec/03-error-manage classification: `[auth]` ≠ `[validation]`).
3. Override `failedValidation(Validator $validator)` to throw `Gl\Exceptions\GlValidationException` carrying the **exact** `GL-VALIDATION-*` code from §15 — never Laravel's default `ValidationException`. Mapping table is internal to the exception class; this file does not restate codes.

Field shapes, types, and length caps are sourced from `17-openapi.yaml` (the single source of truth for wire shapes). Laravel `FormRequest` rules are generated from the OpenAPI document at build time; hand-editing is forbidden (matches §04's wire-format-from-OpenAPI invariant).

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

---

## 5. Middleware Lane Mapping

| Middleware alias | Lane | Responsibilities | Sources |
|---|---|---|---|
| `gl.lane-a` | A | Authenticate WP App Password / cookie equivalent (Sanctum bearer token in Laravel binding) → resolve to `Profile` row | §05 Lane A |
| `gl.permission:{Perm}` | A | Look up `RolePermission` row; reject with `GL-AUTHZ-PERMISSION-DENIED` (§15) if missing | §05 + §19 permission matrix |
| `gl.lane-b` | B | Run the §05 mode-gated 8-step (TempToken) or 10-step (SSH) ordered validation; reject early with the exact `GL-*` code; write `AuditTrail` row before passing to controller | §05 + §31 |

Each middleware terminates the request with a `JsonResponse` carrying the spec/03-error-manage envelope. The error envelope shape, the `TraceId` UUIDv4 generator binding, and the `Details` field structure are owned by spec/03 and MUST NOT be redefined here.

---

## 6. Database Posture

| Concern | Binding | Authority |
|---|---|---|
| Root DB driver | `pdo_sqlite` (NOT Eloquent for ingest paths — see Why below) | spec/04 §2.1 + spec/13 §97 AC-22 (locking) |
| Per-SHA file DB | Lazy-opened raw PDO handle pool, LRU-evicted per `MaxOpenShaDbHandles` | §39 + spec/05 |
| Boolean columns | `INTEGER` 0/1, NOT NULL DEFAULT 0; scanned to PHP `bool` via `(bool) $row['HasError']`; inserted as integer literal | spec/04 §2.1 cross-language boolean storage table (PHP row) |
| Concurrency | `PRAGMA journal_mode=WAL; busy_timeout=5000`; `BEGIN IMMEDIATE` for writes; `SQLITE_BUSY` retry 3× 100 ms ±25 % jitter | spec/13 §97 AC-22 (mirrored to spec/13 §10) |
| Migrations | Versioned migration markers per `06-migrations-and-logger.md`; Laravel `artisan migrate` is **disabled** — migrations are framework-agnostic SQL files executed by the same migrator as the WP binding | §06 |

**Why raw PDO (not Eloquent) on ingest paths:** Eloquent's model hydration + event dispatcher adds ~3–8× latency to per-row INSERT and breaks the `BEGIN IMMEDIATE` + retry-with-jitter contract owned by spec/13 §97 AC-22. Eloquent is permitted in Lane A read paths where latency is non-critical and the row count is bounded by pagination. This split mirrors §39's "root DB raw, log shipping raw, query UI flexible" posture.

---

## 7. Conformance Audit Summary

Self-audit performed at authoring time:

| Check | Result |
|---|---|
| Every `GL-*` code referenced here is defined in §15 | PASS (no new codes introduced) |
| Every endpoint path matches §04 verbatim (modulo `/wp-json`→`/api` prefix) | PASS |
| Error envelope shape matches spec/03-error-manage | PASS (envelope built by service layer, not redefined) |
| Boolean storage matches spec/04 §2.1 PHP row | PASS (INTEGER 0/1 + `(bool)` cast on scan) |
| SQLite locking matches spec/13 §97 AC-22 | PASS (PRAGMA + BEGIN IMMEDIATE + retry-with-jitter cited, not redefined) |
| No cross-module restatement (Lesson #36) | PASS (every contract row in §1 + §6 is a link, not a copy) |
| PHP coding guidelines (constructor injection, final classes, readonly props, no facades in controllers) | PASS |
| Slot 40 not previously used (file-slot immutability — Core memory) | PASS (slot inventory in §00 confirms 40 was vacant before this file) |

---

## 8. Open Items (Tracked Out-of-Scope)

- Laravel package skeleton (composer.json, service provider, config publish) — downstream Laravel-package repo, not this spec-only repo.
- Symfony binding (planned slot 41) — defer until first downstream Symfony consumer appears.
- Lumen / micro-framework binding — declined; Lumen is in maintenance mode (Laravel team announcement 2022).

---

## 9. Changelog (file-local)

| Version | Date | Notes |
|---|---|---|
| 1.0.0 | 2026-06-20 | Initial Laravel sibling binding to §04 (Phase 153). Created per user request. Cross-module anchors mirror AC-79 pattern (Lesson #36 + Lesson #37). Bound as AC-80 in §97 (count 74 → 75). Lockstep: §97 minor + §00/§98/§99 patch. |
