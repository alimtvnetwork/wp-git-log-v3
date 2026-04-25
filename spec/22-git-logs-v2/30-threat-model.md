# Threat Model (v2)

**Version:** 2.5.0  
**Updated:** 2026-04-25

STRIDE pass over the v2 attack surface. Each row names the threat, the affected asset, the v2 mitigation, and any residual risk deferred to v3.

Legend: **S**poofing · **T**ampering · **R**epudiation · **I**nformation disclosure · **D**enial of service · **E**levation of privilege

---

## Trust boundaries

```
       ┌────────────────┐         ┌────────────────────┐
CI/CD →│ Lane B endpoints│────────→│  SQLite root DB    │
       │  /append-log etc│         │  uploads/git-logs/ │
       └────────────────┘         └─────────┬──────────┘
                                            │
       ┌────────────────┐                   │
WP user→│ Lane A endpoints│──────────────────┘
       │ admin UI + reads │
       └────────────────┘
```

Three boundaries:
1. **Internet → Lane B** (CI runner, untrusted from plugin's POV).
2. **WP-authenticated user → Lane A** (semi-trusted, role-gated).
3. **WP filesystem → SQLite file** (trusted; only PHP-FPM user reads/writes).

---

## S — Spoofing

| # | Threat | Asset | Mitigation | Residual |
|---|--------|-------|------------|----------|
| S1 | Attacker submits valid-looking `/append-log` with stolen `TempToken`+`Token`. | Pipeline integrity | Lane B requires both tokens + `RepoUrl` matching a configured GitProfile + Acceptance + branch rule. Profile rotation invalidates within the next read of the row (no token cache). | TempToken theft from a CI secret store still wins until rotated. v3 plan: short-lived signed tokens (§11). |
| S2 | Attacker forges a WP App Password to hit Lane A. | Read access to logs | WP core + Application Passwords + Profile-link by email. Brute force throttled by WP core. | If the WP user account is compromised, all readable logs leak. Mitigated by per-Profile permission scoping and `AuditTrail.LogQuery` rows. |
| S3 | Attacker spoofs `Origin` header to bypass CORS. | Read endpoints | CORS read from `ConfigKv.AllowedReadOrigins`; default empty (same-origin only). Header is advisory; browser enforces. | Server-to-server callers ignore CORS entirely; same-origin policy is browser-only. Not a server-side defense. |
| S4 | Multiple `/append-log` calls forge a different Pipeline's identity by reusing `Pipeline` name. | Cross-pipeline data injection | Pipeline uniqueness is `(RepoVersionId, Branch, Pipeline)`. Cross-Profile collisions blocked by GitProfile ownership of the RepoVersion. | A Profile owning two GitProfiles can collapse Pipelines across them — operator's choice. |

---

## T — Tampering

| # | Threat | Asset | Mitigation | Residual |
|---|--------|-------|------------|----------|
| T1 | Direct edit of SQLite file by a co-tenant on the same host. | DB integrity | File chmod 0600, owned by web user. WP best-practice hosting isolates per-site filesystems. | Shared-hosting tenants on the same UID can read/write. v3 encryption-at-rest (§11) blunts read; integrity remains a hosting concern. |
| T2 | SQL injection via `RepoUrl`, `Branch`, `Pipeline`. | All tables | All queries use prepared statements via `inc/Db/Connection.php` `prepare()` wrapper; no string interpolation. CI gate: `phpstan --level=max` flags raw concat. | Bypass possible only via PHP eval, which is out of scope. |
| T3 | XSS via stored log content rendered in History UI. | Admin browser | All admin output passes `esc_html()` / `esc_attr()`. Severity badges built from enum lookup (no user-controlled HTML). | If a future feature renders raw HTML, must add CSP. v2 doesn't; recommend `Content-Security-Policy: default-src 'self'` on plugin admin pages. |
| T4 | CSRF on admin mutations. | Profile/GitProfile/App tables | Every admin form uses WP nonces (`wp_nonce_field` + `check_admin_referer`). REST mutate endpoints use cookie + `X-WP-Nonce`. | Weak nonces if an attacker controls JS in the admin context — covered by T3. |
| T5 | Tampered backup restored to escalate. | All tables | Restore checks manifest `SchemaChecksum`; downgrade across major versions refused; `--force` audited. | Operator with `--force` can restore arbitrary content — by design. |

---

## R — Repudiation

| # | Threat | Asset | Mitigation | Residual |
|---|--------|-------|------------|----------|
| R1 | Admin claims they didn't delete a Profile / change Acceptance / restore a backup. | Audit trail | `AuditTrail` table records `AuditActionType`, `AuditOutcome`, `ProfileId`, `RouteName`, `RequestId`, `HttpStatus`, `Detail`, `OccurredAt` for every state-changing route + CLI command. | Audit table itself can be edited by anyone with DB access (T1). v3 plan: append-only journal + checksum chain. |
| R2 | CI claims a push happened that the plugin never received. | Push acknowledgment | Successful `/append-log` returns `PipelineId` + counts; CI logs that ack alongside its own logs. AuditTrail row also written. | If both CI logs and DB are lost, no recovery. Encourage backup retention. |
| R3 | Operator silently disables logging by setting `LogLevelMin=Fatal`. | Diagnostic visibility | Config changes via `wp git-logs config set` write `AuditTrail.AuditActionType=ConfigChange` (TODO: add seed). Admin UI Settings change does same via REST. | Direct `ConfigKv` row edit (T1) bypasses audit. |

---

## I — Information disclosure

| # | Threat | Asset | Mitigation | Residual |
|---|--------|-------|------------|----------|
| I1 | Logs contain secrets (tokens, env vars) leaked into stdout. | Log content | Plugin does not parse content; treats lines as opaque. Operators must redact in CI before pushing. Documented in §28. | Leaks are a CI-side concern; plugin can't help. |
| I2 | `/metrics` endpoint reveals Pipeline counts to anyone. | Org telemetry | Auth required (`HistoryView` permission). | Once granted, full counters visible. Acceptable trade-off for ops. |
| I3 | TempToken leaked via WP error log when DB write fails. | Credential | Logger redacts `TempToken`/`Token` fields by name in `inc/Logging/Redactor.php` before any `error_log`. Unit-tested. | Unknown future field names need to be added to redaction list. CI grep checks for `$tempToken` near `error_log`. |
| I4 | Backup file readable by web. | All data | Backups default to `wp-content/uploads/git-logs/backups/` with `.htaccess` `Deny from all`; nginx hosts must add equivalent. Documented in §23. | Misconfigured nginx exposes them. Add a `wp git-logs verify` check that probes the backup URL externally? — deferred. |
| I5 | Cross-tenant disclosure on multisite. | Site data | Per-site DB file (§24); zero cross-site queries possible. | Shared-DB hosting (T1) is the only attack vector. |

---

## D — Denial of service

| # | Threat | Asset | Mitigation | Residual |
|---|--------|-------|------------|----------|
| D1 | Attacker floods `/append-log` to fill DB. | Disk + DB perf | Per-Profile token bucket (`RatePerMinPerProfile`, default 60). `MaxPushPayloadBytes` (1 MiB), `MaxLinesPerPush` (10000), `MaxLineBytes` (64 KiB). | Token theft (S1) bypasses; rotate. Multiple Profiles compromised → DDoS. WAF in front of WP recommended. |
| D2 | Slowloris-style chunked push holds connection. | PHP-FPM workers | PHP/nginx request timeouts (host-managed). Plugin reads body in one shot, no streaming hold. | Out-of-scope; covered by hosting. |
| D3 | Pathological JSON (deeply nested) causes parse OOM. | PHP memory | `json_decode` with depth limit 32; reject `GL-VALIDATION-FIELD-TYPE` on overflow. | None significant. |
| D4 | SQLite write contention from many concurrent pushes. | Latency | WAL mode + per-Pipeline batching + token bucket cap. | High-traffic sites (>100 push/s sustained) outgrow SQLite — documented as deferred to a v3 MySQL/Postgres backend. |
| D5 | Prune command run during peak hours locks DB. | Read latency | Prune uses `BEGIN IMMEDIATE` with batched 1000-row deletes; releases between batches. WAL keeps reads non-blocking. | Operator can still misuse `--batch=100000`; document recommended ranges. |

---

## E — Elevation of privilege

| # | Threat | Asset | Mitigation | Residual |
|---|--------|-------|------------|----------|
| E1 | Editor user grants themselves `AppCreate` via the AccessToRoles screen. | Role assignment | The AccessToRoles screen requires WP `manage_options` (Admin only) — not a plugin Permission. Documented in §19. | If WP Admin ≠ trusted, all bets off. WP-level concern. |
| E2 | Bootstrap form re-runs after first Admin exists, granting another credential. | First-run security | Bootstrap renders only when `Profile` table empty AND user has `manage_options`. Subsequent visits show the normal Profile screen. `wp git-logs bootstrap` CLI requires shell access (already root-equivalent). | Empty-Profile scenario after intentional delete is by design — re-creates the first Admin. |
| E3 | IDOR via direct PipelineId guessing on `/get-pipeline-logs`. | Cross-app log read | Endpoint resolves PipelineId → Pipeline → RepoVersion → Repo → GitProfile → ownership chain. If the requesting Profile has no GitProfile in that chain (and is not Admin), reject with `GL-AUTHZ-PERMISSION-DENIED`. | Admin can read everything — by design. |
| E4 | SSRF via attacker-controlled `ProfileUrl` on GitProfile. | Internal network | The plugin never fetches arbitrary URLs from `ProfileUrl`. It only parses the host + owner segment. No outbound HTTP triggered by save. | If a future feature fetches GitHub API, must whitelist hosts. Documented as a v3 prerequisite. |
| E5 | Permission bit-flip via tampered `RolePermission` row. | Authorization | Mutating `RolePermission` requires `manage_options` (E1) + nonce (T4). | Direct DB edit (T1) wins. |

---

## Summary

| Severity | Count | Notes |
|----------|-------|-------|
| Mitigated in v2 | 23 | Listed above. |
| Deferred to v3 | 4 | S1 (signed tokens), I3 (rotating redaction list), R1 (append-only audit chain), D4 (multi-engine backend). |
| Out of scope | 5 | T1 (filesystem), D2 (hosting), I1 (CI-side), E1/E5 (WP Admin trust). |

---

## Required v3 prerequisites that fall out of this model

1. Short-lived signed tokens replacing `TempToken` (S1).
2. Append-only audit journal with checksum chain (R1).
3. Encryption-at-rest with Argon2id-hashed lookup keys (T1, I3) — already planned in `11-encryption-deferred-plan.md`.
4. Optional MySQL/Postgres backend for high-volume sites (D4).
5. Outbound HTTP allow-list before any GitHub API integration (E4).
