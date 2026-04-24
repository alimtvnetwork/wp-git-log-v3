# Blind Audit Checklist

**Version:** 1.0.0  
**Updated:** 2026-04-25  
**Status:** Active  
**Audience:** A downstream AI / engineer with no prior context

---

## Purpose

Self-verification checklist that lets a fresh reader confirm the spec is internally coherent before generating code. Walk top-to-bottom. Every "Yes" is required to start implementation.

---

## A. Coverage

- [ ] All files listed in [`00-overview.md`](./00-overview.md) inventory exist on disk.
- [ ] Every `AC-*` ID found in this folder is rolled up in [`14-acceptance-criteria.md`](./14-acceptance-criteria.md).
- [ ] Every `GL-*` error code mentioned in markdown appears in [`error-codes.json`](./error-codes.json).
- [ ] All four governance files exist (`14`, `97`, `98`, `99`).

## B. Internal consistency

- [ ] `02-database-schema-and-erd.md` declares every table referenced by other files (incl. `RevokedJti`).
- [ ] [`08`](./08-allowlist-and-wildcard-matching.md) and [`02`](./02-database-schema-and-erd.md) agree on `Repository` columns.
- [ ] Cryptographic decision (`F-02`) is resolved; `08` and `05` use the chosen path uniformly.
- [ ] `traceId` precedence (`Traceparent` > `X-Request-Id`) is consistent across `12` and `04`.
- [ ] PascalCase envelope shape in `11` matches every example body in `04`, `07`, `09`.

## C. Locked decisions honoured

- [ ] JWT = RS256 + JWKS (`05`).
- [ ] Access TTL 24 h, refresh 7 d rotating (`16`).
- [ ] Push payload cap 1 MB **decompressed** (`07` AC-PUSH-02).
- [ ] Rate limit 60/min/repo (`04` §5).
- [ ] WP bridge accepts App Password + cookie (`06`).
- [ ] Provider GitHub-only; GitLab reserved with reject rule (`13` §4).
- [ ] Plugin slug `git-logs`, table prefix `{wp_prefix}gitlogs_`, REST namespace `git-logs/v1`.

## D. Security

- [ ] No tokens, JWTs, passwords appear in any audited example payload.
- [ ] Trusted-proxy CIDR option (`gitlogs_trusted_proxies`) is named in both `12` and `03`.
- [ ] CORS allow-list (`gitlogs_allowed_origins`) is named in both `04`/`12` notes and `03`.
- [ ] `RevokedJti` purge cron is named (`gitlogs_purge_revoked_jti`).

## E. Open items closed

- [ ] `OI-ALLOW-01` (HS256 verifier vs Argon2id) decided.
- [ ] `OI-ERR-04` / `OI-JWT-02` (revoked-JTI storage) decided — `RevokedJti` table.
- [ ] `OI-LOG-02` (trusted-proxy source) decided — `gitlogs_trusted_proxies`.
- [ ] `OI-JWT-03` (refresh idempotency window) decided — 5 s.

## F. Build readiness

- [ ] PHP 8.1+ assumed (PHP-backed enums).
- [ ] PHPCS profile in `13` is acceptable to the team.
- [ ] PHPStan level 8 acceptable.
- [ ] WP minimum version supports Application Passwords (5.6+).

---

## Verdict

If every box is checked, the spec is safe to feed blindly to an implementing AI. Otherwise log the failing rows in [`spec/22-app-issues/02-consolidated-audit-findings`](../22-app-issues/02-consolidated-audit-findings/00-overview.md) and remediate.

---

## Cross-References

| Reference | Location |
|---|---|
| Module index | [00-overview.md](./00-overview.md) |
| Consistency checklist (deeper) | [17-spec-consistency-checklist.md](./17-spec-consistency-checklist.md) |
| Open audit findings | [../22-app-issues/02-consolidated-audit-findings/00-overview.md](../22-app-issues/02-consolidated-audit-findings/00-overview.md) |
