# Gitlogs Diagrams

**Version:** 1.0.0  
**Updated:** 2026-04-25

Authoritative source: [`../22-git-logs-v2/00-overview.md`](../22-git-logs-v2/00-overview.md).

## Inventory

| # | File | Purpose |
|---|------|---------|
| 00 | 00-overview.md | This index |
| 01 | 01-er-diagram.mmd | Full ER schema |
| 02 | 02-domain-design.mmd | GitProfile → Repo → RepoVersion → History/Action; App linkage |
| 03 | 03-endpoints-write.mmd | append-log / fixed-log / clear-log / clear-log-all flows |
| 04 | 04-endpoints-read.mmd | get-logs / get-pipeline-logs / get-error-logs / get-pipeline-error-logs flows |
| 05 | 05-auth-validation.mmd | TempToken + URL/Branch validation |
| 06 | 06-permission-flow.mmd | Role → Permission → Action authorization |
| 99 | 99-consistency-report.md | Health/structure |
