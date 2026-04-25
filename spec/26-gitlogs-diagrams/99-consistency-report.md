# Consistency Report — Gitlogs Diagrams

**Version:** 1.1.0  
**Updated:** 2026-04-25

## File Inventory

| File | Present | Notes |
|------|---------|-------|
| 00-overview.md | ✅ | Index + inventory (v1.1.0) |
| 01-er-diagram.mmd | ✅ | erDiagram, full schema |
| 02-domain-design.mmd | ✅ | graph TD, hierarchy |
| 03-endpoints-write.mmd | ✅ | sequenceDiagram, 4 write endpoints |
| 04-endpoints-read.mmd | ✅ | sequenceDiagram, 6 read endpoints |
| 05-auth-validation.mmd | ✅ | flowchart TD, validation order |
| 06-permission-flow.mmd | ✅ | flowchart LR, RolePermission union |
| 07-rate-limit-flow.mmd | ✅ | Token-bucket refill + 429 + AuditTrail (v2 §10) |
| 08-encryption-v3-flow.mmd | ✅ | v3 deferred: MasterKey/DataKey/LookupKey + MigrationState (v2 §11) |
| 97-acceptance-criteria.md | ✅ | AC-D-01..AC-D-10 |
| 98-changelog.md | ✅ | v1.1.0 |
| 99-consistency-report.md | ✅ | This file |

All diagrams reflect `spec/22-git-logs-v2/`. Where v1 (folder 21) conflicts, v2 + diagrams win.

## Cross-Reference Health

- [`00-overview.md`](./00-overview.md) inventory matches every file on disk (12/12).
- [`97-acceptance-criteria.md`](./97-acceptance-criteria.md) covers all 8 `.mmd` files via AC-D-01..AC-D-10.
- Authoritative source link [`../22-git-logs-v2/00-overview.md`](../22-git-logs-v2/00-overview.md) resolves.
- No JWT / RS256 / JWKS references (locked decision 5).

## Open Gaps

_None._
