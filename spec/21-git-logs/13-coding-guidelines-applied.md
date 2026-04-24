# Coding Guidelines Applied — PHP / WordPress Plugin

**Version:** 1.0.0  
**Updated:** 2026-04-25  
**Status:** Active

---

## Purpose

Maps the master coding guidelines (`spec/02-coding-guidelines/01-cross-language/15-master-coding-guidelines/`) to concrete PHP / WordPress enforcement for this plugin. Implementers and downstream AIs MUST treat the rules below as binding.

The master guideline is the source of truth for **what** the rule is; this document is the source of truth for **how** to satisfy it in PHP / WP code inside `git-logs`.

---

## 1. PSR-12 + WordPress Subset

| Element | Rule | Conflict resolution |
|---|---|---|
| Indentation | 4 spaces (PSR-12) — never tabs | PSR-12 wins over WP "tabs" preference inside this plugin |
| Line length | Soft 120 chars; hard 160 | |
| Braces (control flow) | K&R / same-line | |
| Class braces | Next line (PSR-12) | |
| `declare(strict_types=1);` | First line of every PHP file | |
| Visibility | Always declared on properties + methods | |
| Type declarations | Required on every public param + return | |

PHPCS configuration (root `phpcs.xml`) extends `PSR12` and includes only the WP subset rules that do not conflict (`WordPress.WP.I18n`, `WordPress.Security.NonceVerification`, `WordPress.Security.EscapeOutput`, `WordPress.DB.PreparedSQL`).

---

## 2. Naming

Directly inherits the master rules. The only PHP-specific notes:

| Element | Rule |
|---|---|
| Class file | One class per file, file name = class name (PascalCase, `.php`) |
| Namespace | `GitLogs\` root; one namespace per directory |
| Method | camelCase |
| Constant | `UPPER_SNAKE_CASE` (PHP-language convention) — exempt from no-underscore rule |
| Enum case | PascalCase |
| Property | camelCase |
| Parameter | camelCase |
| Array key in code logic | camelCase (`'postId'`) |
| DB column / REST JSON field | PascalCase (`PostId`) |
| WP option key | `gitlogs_` prefix + snake_case (WP-platform exemption) |
| WP hook name | `gitlogs/` prefix + slash-separated lowercase (WP convention) |
| Capability | `gitlogs_` prefix + snake_case (WP convention) |

Abbreviations follow first-letter-cap (`Id`, `Url`, `Json`, `Jwt`, `Ip`, `Db`). PHP standard library names (`PDOStatement`, `JsonException`) are exempt.

---

## 3. Booleans

| Rule | PHP enforcement |
|---|---|
| `is`/`has` prefix required | `private bool $isActive;` |
| No negative names | `$isPending` not `$isNotReady` |
| No raw `!` on method calls | Add a positive counterpart: `$order->isInvalid()` |
| No boolean parameters | Use options arrays (`array{strict?: bool}`) or named methods (`processStrict()`) |
| Existence guards | Implement `Result::isDefined()`, `Result::isSafe()`, `Optional::isEmpty()` on wrapper classes |

---

## 4. Enums

PHP 8.1+ backed enums:

```
enum AuditOutcome: int {
    case Success  = 1;
    case Rejected = 2;
    case Error    = 3;

    public function isEqual(self $other): bool {
        return $this === $other;
    }
}
```

Rules:

- Every domain enum lives in `GitLogs\Domain\Enum\`.
- Switch over enum MUST have `default` arm that throws `UnhandledEnumException`.
- Reserved-but-disabled cases (e.g., `Provider::GitLab`) MUST be rejected at the validation layer with `GL-VAL-PROVIDER-DISABLED` per finding [`F-09`](../22-app-issues/02-consolidated-audit-findings/00-overview.md).

---

## 5. Errors

Master rule: never swallow.

| Mechanism | Required form |
|---|---|
| `try`/`catch` | Catch by narrowest type; re-throw or wrap in `AppError`. Empty catch blocks forbidden. |
| `@` suppression | Forbidden. |
| `WP_Error` | Forbidden as a return type for plugin code. The error envelope mapper translates only at the REST boundary. |
| Custom errors | All extend `GitLogs\Error\AppError` and carry an `ErrorCode` from the registry. |

Every controller MUST:

```
try {
    $result = $this->service->run($input);
    return ResponseEnvelope::ok($result);
} catch (AppError $e) {
    AppErrorLogger::record($e, $traceId);
    throw $e;            // mapper at the REST boundary converts to envelope
} catch (Throwable $e) {
    AppErrorLogger::record($e, $traceId);
    throw new AppError(ErrorCode::GL_SYS_001, 'Unexpected error.', previous: $e);
}
```

A `finally` block writes the terminal `AuditTrail` row per [`10-audit-trail.md` §2.2](./10-audit-trail.md).

---

## 6. Type Safety

| Topic | Rule |
|---|---|
| `mixed` | Forbidden in domain code; allowed only at JSON decode boundary, immediately narrowed. |
| Array shape | Use `phpstan-array{…}` annotations; PHPStan level `8` minimum. |
| Nullable | Prefer `Optional<T>` wrapper; raw nulls only at boundaries. |
| Result | Use `Result<TValue, TError>` with `isSafe()` / `hasError()`. |

---

## 7. Magic Strings

Forbidden literals in business code:

| Literal | Replace with |
|---|---|
| `'admin'`, `'success'`, etc. | Enum case (`Role::Admin`, `AuditOutcome::Success`) |
| HTTP status codes | `HttpStatus::OK->value` constants |
| Hook names | `Hooks::PIPELINE_CREATED` constants |
| Option keys | `Options::TRUSTED_PROXIES` constants |
| Capability names | `Capabilities::MANAGE_REPOS` constants |
| Error codes | `ErrorCode::GL_*` enum |

PHPStan custom rule `NoMagicStringsRule` rejects PRs that introduce new literals matching the patterns above.

---

## 8. Database

| Rule | Enforcement |
|---|---|
| Table names | PascalCase (`Repository`, `AuditTrail`) — no underscores |
| Column names | PascalCase |
| Indexes | `Idx{Table}_{Cols}` |
| Prepared statements only | Direct `$wpdb->query("INSERT … {$x}")` is banned |
| `wpdb` access | Wrapped behind `GitLogs\Db\Connection` |
| Migrations | One file per change, numeric prefix; idempotent re-runs |

WordPress core tables (`wp_posts`, `wp_options`) keep their snake_case names — exemption documented inline.

---

## 9. WordPress Specifics

| Topic | Rule |
|---|---|
| Hook namespacing | `gitlogs/{area}/{event}` (e.g., `gitlogs/auth/token_issued`) |
| Cron | Schedule via `wp_schedule_event`; cron hook is `gitlogs_purge_revoked_jti` |
| Translation | Every user-visible string wrapped in `__()` / `_x()` with text-domain `git-logs` |
| Output escaping | Use `esc_html`, `esc_attr`, `esc_url` at print time; not at storage time |
| Nonce | Required on every cookie-auth REST request and admin-form submission |
| Capabilities | Custom caps registered at activation, unregistered at deactivation |
| `wp_die` | Forbidden in REST code paths; throw `AppError` instead |

---

## 10. Dependency Injection

| Rule | Enforcement |
|---|---|
| No singletons in domain code | Singletons only at the WP-bootstrap container level |
| Constructor injection | Required for all services and controllers |
| Container | A small custom container (`GitLogs\Di\Container`); no third-party DI dependency |
| Lifetimes | `Transient` (default), `Scoped` (per-request), `Singleton` (process-wide) |

---

## 11. Testing (Outline)

Detailed test plan lives outside this file; the rules here are the floor:

| Layer | Required tests |
|---|---|
| Unit | Every service public method; every enum's `isEqual` and `default` arm |
| Integration | Every REST endpoint happy path + at least 2 failure paths per `Errors` table |
| Contract | The PascalCase response envelope shape; JWKS payload shape |
| Schema | Every migration runs forward and is idempotent |

---

## 12. Acceptance Criteria

| ID | Given | When | Then |
|---|---|---|---|
| AC-CGA-01 | Any new PHP file | PHPCS runs | Zero PSR-12 errors |
| AC-CGA-02 | Any new PHP file | PHPStan level 8 runs | Zero errors |
| AC-CGA-03 | A controller throws `Throwable` | The request finishes | One terminal `AuditTrail (AuditOutcome=Error)` row exists |
| AC-CGA-04 | A new boolean property is added | Code review | It starts with `is`/`has` and contains no negative words |
| AC-CGA-05 | A `switch` over an enum | PHPStan runs | All cases handled or `default` throws |
| AC-CGA-06 | A REST handler returns | Integration test | Response uses the PascalCase envelope from `11` |
| AC-CGA-07 | A direct SQL string is added | PHPStan custom rule | Rejects unless wrapped in `Connection::prepared()` |

---

## 13. Cross-References

| Reference | Location |
|---|---|
| Master coding guidelines | [../02-coding-guidelines/01-cross-language/15-master-coding-guidelines/00-overview.md](../02-coding-guidelines/01-cross-language/15-master-coding-guidelines/00-overview.md) |
| PHP standards reference | [../02-coding-guidelines/04-php/07-php-standards-reference/00-overview.md](../02-coding-guidelines/04-php/07-php-standards-reference/00-overview.md) |
| Database conventions | [../04-database-conventions/01-naming-conventions.md](../04-database-conventions/01-naming-conventions.md) |
| Error management | [11-error-management.md](./11-error-management.md) |
| Audit trail | [10-audit-trail.md](./10-audit-trail.md) |
| Spec consistency checklist | [17-spec-consistency-checklist.md](./17-spec-consistency-checklist.md) |
