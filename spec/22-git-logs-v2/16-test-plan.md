# Test Plan (v2)

**Version:** 2.2.0  
**Updated:** 2026-04-25

Defines the minimum test coverage the v2 plugin must ship with. Implementation lives under `tests/Unit/` and `tests/Integration/` (see [`12-wp-plugin-scaffold.md`](./12-wp-plugin-scaffold.md)).

---

## Stack

- **PHPUnit** (≥ 10) for both Unit and Integration suites.
- **WP-CLI scaffold** for Integration-level WP bootstrapping (`wp scaffold plugin-tests`).
- **In-memory SQLite** (`:memory:`) for Unit tests touching repositories — no temp files.
- **GitHub Actions matrix**: PHP 8.1 / 8.2 / 8.3 × WP latest / latest-1.

---

## Unit suite (no WP, no filesystem)

| File under test | Test focus |
|-----------------|------------|
| `Domain/Validation/RepoUrlParser.php` | Parses GitHub user + org URLs, with/without `.git`, with/without `-vN` suffix; rejects malformed. |
| `Domain/Validation/AcceptanceMatcher.php` | All 3 Acceptance modes × match/mismatch matrix. |
| `Domain/Validation/BranchRestrictionGate.php` | On/off × match/mismatch. |
| `Domain/Auth/PermissionGate.php` | Union of multiple roles; lookup never uses role name. |
| `Domain/Services/RateLimiter.php` | Token-bucket refill arithmetic; allow boundary; deny + retryAfter math. |
| `Logging/LogLevelFilter.php` | Severity ≥ `LogLevelMin` admitted; below dropped. |
| `Logging/DedupBuffer.php` | Identical line within 60s dropped; after 60s admitted; LRU eviction. |
| `Enums/*` | Lookup-table id ↔ name round-trip; reject unknown ids. |

Coverage target: ≥ 90% lines for `Domain/`, `Logging/`, `Enums/`.

---

## Integration suite (real WP + real SQLite file)

| Scenario | Asserts |
|----------|---------|
| **Activator → Migration V2_0_0** | All schema tables exist; all seed rows present (counts match §09). |
| **Re-activate twice** | Migration is no-op on second activation; no duplicate seeds. |
| **First-run Bootstrap** | Empty Profile + admin visit → bootstrap form rendered; submit creates Profile + Admin role + AuditTrail row. |
| **Append-log happy path** | Valid TempToken+Token+URL+Branch → 200, `LogEntry` rows persisted, Pipeline created, ack envelope correct. |
| **Append-log streaming** | `Transfer-Encoding: chunked` body of 5000 lines → all rows persisted, no truncation. |
| **Append-log HasError flip** | First push `HasError=true` sets `Pipeline.HasError=1`; `/fixed-log` flips to 0; AuditTrail records both. |
| **Validation rejects** | One test per `GL-VALIDATION-*` code in `15-error-codes.md`. |
| **Auth rejects** | One test per `GL-AUTH-*` and `GL-APP-NOT-ACTIVE`. |
| **Rate limit** | 61 pushes/minute → 61st returns 429 with `Retry-After`; `AuditTrail` shows `LogPush, Outcome=Rejected`. |
| **Payload size** | Body > `MaxPushPayloadBytes` → `GL-PAYLOAD-TOO-LARGE` (413), no DB write. |
| **Read endpoints** | Each of the 6 read routes returns expected shape, pagination cursor round-trips. |
| **Permission gate** | Editor role can View+Modify but not Create/Delete; verified for App, GitProfile, Repo. |
| **App lifecycle** | Setting `AppStatus=Disabled` causes subsequent push to reject with `GL-APP-NOT-ACTIVE`. |
| **AppLink polymorphism** | Insert AppLink with both `GitProfileId` and `RepoId` set → CHECK constraint violation. |
| **Audit split** | One push writes one `AuditTrail` row, one `History` row, ≥ 1 `Action` row. Counts verified per §08. |
| **Uninstall** | Default uninstall preserves SQLite DB; uninstall with `GITLOGS_DROP_DB=1` removes it. |

Coverage target: every endpoint exercised at least once; every GL- code asserted at least once.

---

## Smoke / E2E (manual or scripted)

- Walk all 8 admin screens in a fresh WP install; no PHP notices, no console errors.
- Run a real GitHub Actions job pushing to `/append-log`; verify rows appear in History view within 10s.
- Bootstrap a Profile → push → view logs → clear logs → confirm Audit timeline.

---

## Running

```bash
# Unit only
vendor/bin/phpunit --testsuite Unit

# Integration (requires WP test scaffold)
vendor/bin/phpunit --testsuite Integration

# All
composer test
```

CI fails the build on:
- Any test failure.
- Coverage drop below targets above.
- New `GL-*` code in `inc/Support/ErrorCodes.php` without a corresponding integration test (grep check).
