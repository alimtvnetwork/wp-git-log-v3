# Gitlogs Diagrams

**Version:** 1.1.0  
**Updated:** 2026-04-25

Authoritative source: [`../22-git-logs-v2/00-overview.md`](../22-git-logs-v2/00-overview.md).

## Inventory

| # | File | Purpose |
|---|------|---------|
| 00 | [00-overview.md](./00-overview.md) | This index |
| 01 | [01-er-diagram.mmd](./01-er-diagram.mmd) | Full ER schema |
| 02 | [02-domain-design.mmd](./02-domain-design.mmd) | GitProfile → Repo → RepoVersion → History/Action; App linkage |
| 03 | [03-endpoints-write.mmd](./03-endpoints-write.mmd) | append-log / fixed-log / clear-log / clear-log-all flows |
| 04 | [04-endpoints-read.mmd](./04-endpoints-read.mmd) | get-logs / get-pipeline-logs / get-error-logs / get-pipeline-error-logs flows |
| 05 | [05-auth-validation.mmd](./05-auth-validation.mmd) | TempToken + URL/Branch validation |
| 06 | [06-permission-flow.mmd](./06-permission-flow.mmd) | Role → Permission → Action authorization |
| 07 | [07-rate-limit-flow.mmd](./07-rate-limit-flow.mmd) | Per-Profile token-bucket: refill, allow/deny, 429 + Retry-After, AuditTrail rejection |
| 08 | [08-encryption-v3-flow.mmd](./08-encryption-v3-flow.mmd) | v3 deferred: MasterKey → DataKey → LookupKey derivation, ALTER, per-row encrypt, MigrationState |
| 97 | [97-acceptance-criteria.md](./97-acceptance-criteria.md) | Diagram acceptance criteria (AC-D-01..10) |
| 98 | [98-changelog.md](./98-changelog.md) | Version history |
| 99 | [99-consistency-report.md](./99-consistency-report.md) | Health/structure |
