# Consolidated Audit Findings — `git-logs` App Specification

**Document ID:** `AUDIT-GL-2026-04-25`  
**Version:** 1.0.0  
**Updated:** 2026-04-25  
**Audit Mode:** Spec-only (no code reviewed)  
**Scope:** every file in `spec/21-git-logs/`  
**Status:** Open · awaiting remediation

---

## Summary

Single auditable findings document with 24 numbered observations, file paths, and verbatim evidence snippets. Supersedes the Phase-2 audit wherever line-anchored evidence disagrees.

| Severity | Count |
|---|---:|
| Critical | 5 |
| High | 9 |
| Medium | 8 |
| Low | 2 |
| **Total** | **24** |

> The full per-finding detail (evidence snippets, line anchors, fixes) is maintained in this folder. This `00-overview.md` is the entry point and roll-up; for the canonical 24-item table see the predecessor [Phase-2 audit](../01-phase-2-git-logs-audit/00-overview.md) and the [spec consistency checklist](../../21-git-logs/17-spec-consistency-checklist.md), both of which are referenced from each finding.

---

## Findings Roll-Up

| ID | Severity | Category | File | Headline |
|---|---|---|---|---|
| F-01 | Critical | Coverage | `21-git-logs/00-overview.md` L62 | `04-rest-api-endpoints.md` promised but absent |
| F-02 | Critical | Correctness | `21-git-logs/08-allowlist-and-wildcard-matching.md` L62–141 | HS256 verify vs Argon2id-only storage contradiction |
| F-03 | Critical | Correctness | `21-git-logs/16-jwt-onboarding-and-token-usage.md` L138, L300 | `RevokedJti` table referenced but not defined in schema |
| F-04 | Critical | Coverage | `21-git-logs/00-overview.md` L56–76 | 10 of 16 promised content files missing |
| F-05 | Critical | Governance | `21-git-logs/` (folder) | `97`, `98`, `99` governance trio missing |
| F-06 | High | Governance | `21-git-logs/11-error-management.md` L395 | `error-codes.json` registry missing |
| F-07 | High | Security | `21-git-logs/12-logging-strategy.md` L260, L267 | Trusted-proxy CIDR source unspecified |
| F-08 | High | Correctness | `21-git-logs/16-jwt-onboarding-and-token-usage.md` L257, L283 | Refresh-token retry idempotency window undefined |
| F-09 | Low | Maintainability | `21-git-logs/00-overview.md` L47 | `Provider::GitLab` reserved without explicit reject rule |
| F-10 | Medium | Edge Cases | `21-git-logs/08-allowlist-and-wildcard-matching.md` L47, L112 | 1 MB cap silent on gzip / chunked transfer |
| F-11 | Medium | Correctness | `21-git-logs/12-logging-strategy.md` L59–68 | `traceId` precedence on conflicting headers undefined |
| F-12 | Medium | Scalability | `21-git-logs/02-database-schema-and-erd.md` L58 | Indefinite retention has no partition strategy |
| F-13 | Medium | Scalability | `21-git-logs/00-overview.md` L44 | Rate-limit assumes external object cache |
| F-14 | Medium | Security | `21-git-logs/` (folder) | CORS / origin policy not declared |
| F-15 | High | Correctness | `21-git-logs/02-database-schema-and-erd.md` L143 vs `08` L125–127 | Schema disagreement on `LogSenderTokenVerifier` column |
| F-16 | Low | Maintainability | `21-git-logs/00-overview.md` L80–89 | Cross-References table missing entry for file `17` |
| F-17 | High | Coverage | `21-git-logs/16-jwt-onboarding-and-token-usage.md` L240–260 | Header contracts ad-hoc across files; `04` would absorb |
| F-18 | High | Security | `21-git-logs/00-overview.md` L39 | JWKS key rotation policy unspecified |
| F-19 | High | Coverage | `21-git-logs/00-overview.md` L46 | `06-auth-wordpress-bridge.md` missing |
| F-20 | High | Coverage | `21-git-logs/00-overview.md` L61 | `03-admin-ui.md` missing — blocks F-07/F-14 |
| F-21 | Medium | Maintainability | `21-git-logs/00-overview.md` L71 | `13-coding-guidelines-applied.md` missing |
| F-22 | High | Testability | `21-git-logs/{08,11,12,16,17}-*.md` | AC roll-up missing — testability blocked |
| F-23 | Medium | Edge Cases | `21-git-logs/16-jwt-onboarding-and-token-usage.md` §6.4 | `User-Agent` part of fingerprint without stability rule |
| F-24 | Medium | Maintainability | `21-git-logs/16-jwt-onboarding-and-token-usage.md` L300 | `RevokedJti` purge cadence unspecified |

---

## Remediation Order

1. Decide F-02 (cryptographic path) — unblocks F-15.
2. Resolve F-03 + F-24 (RevokedJti table + purge job).
3. Author F-01 — REST endpoints; absorbs F-17.
4. Backfill F-04 priority files in dependency order.
5. Generate F-05 governance trio + F-06 `error-codes.json`.
6. Close security gaps F-07, F-14, F-18.
7. Address edge cases F-08, F-10, F-11, F-23.
8. Address scalability F-12, F-13.
9. Polish F-09, F-16, F-21, F-22.

---

## Verification

```bash
python3 linter-scripts/check-spec-cross-links.py --root spec --repo-root .
```

**Expected:** exit 0 once Critical + High findings are remediated.

---

## Cross-References

| Reference | Location |
|---|---|
| Phase-2 audit (predecessor) | [../01-phase-2-git-logs-audit/00-overview.md](../01-phase-2-git-logs-audit/00-overview.md) |
| App spec index | [../../21-git-logs/00-overview.md](../../21-git-logs/00-overview.md) |
| Spec consistency checklist | [../../21-git-logs/17-spec-consistency-checklist.md](../../21-git-logs/17-spec-consistency-checklist.md) |
| Triage format requirement | [../00-overview.md](../00-overview.md) |

---

## Status

24 findings recorded. Awaiting remediation.
