# Acceptance Criteria — Roll-Up

**Version:** 1.0.0  
**Updated:** 2026-04-25  
**Status:** Active

---

## Purpose

Canonical roll-up of every `AC-*` ID across the `git-logs` module. Mirrored by `97-acceptance-criteria.md`. Each row links back to the originating spec file.

---

## Index

| Group | Source | Count |
|---|---|---|
| `AC-AUD-*` | [10-audit-trail.md](./10-audit-trail.md) | 8 |
| `AC-API-*` | [04-rest-api-endpoints.md](./04-rest-api-endpoints.md) | 8 |
| `AC-JWT-*` | [16-jwt-onboarding-and-token-usage.md](./16-jwt-onboarding-and-token-usage.md) | 10 |
| `AC-JWT-A*` | [05-auth-jwt-flow.md](./05-auth-jwt-flow.md) | 9 |
| `AC-WPB-*` | [06-auth-wordpress-bridge.md](./06-auth-wordpress-bridge.md) | 10 |
| `AC-PUSH-*` | [07-log-push-flow.md](./07-log-push-flow.md) | 10 |
| `AC-ALW-*` | [08-allowlist-and-wildcard-matching.md](./08-allowlist-and-wildcard-matching.md) | 12 |
| `AC-RET-*` | [09-log-retrieval-flow.md](./09-log-retrieval-flow.md) | 10 |
| `AC-ERR-*` | [11-error-management.md](./11-error-management.md) | varies |
| `AC-LOG-*` | [12-logging-strategy.md](./12-logging-strategy.md) | 8 |
| `AC-UI-*` | [03-admin-ui.md](./03-admin-ui.md) | 8 |
| `AC-CGA-*` | [13-coding-guidelines-applied.md](./13-coding-guidelines-applied.md) | 7 |
| `AC-CHK-*` | [17-spec-consistency-checklist.md](./17-spec-consistency-checklist.md) | 8 |

**Approximate total:** 115+ acceptance criteria.

---

## Verification

```bash
python3 linter-scripts/check-spec-cross-links.py --root spec --repo-root .
rg -n '^\| AC-[A-Z]+-' spec/21-git-logs | wc -l
```

Every `AC-*` ID found in any file in this folder MUST appear under the matching group above. CI fails on orphan IDs.

---

## Cross-References

| Reference | Location |
|---|---|
| 97-AC mirror | [97-acceptance-criteria.md](./97-acceptance-criteria.md) |
| Module index | [00-overview.md](./00-overview.md) |
