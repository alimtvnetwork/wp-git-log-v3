# Acceptance Criteria — Gitlogs Diagrams

**Version:** 1.0.0  
**Updated:** 2026-04-25

| # | Criterion | Source |
|---|-----------|--------|
| AC-D-01 | `01-er-diagram.mmd` includes every table from `spec/22-git-logs-v2/02-database-schema.md` (Profile, RoleAssignment, RolePermission, GitProfile, Repo, RepoVersion, Pipeline, LogEntry, ErrorLogEntry, App, AppLink, History, Action, AuditTrail, MigrationState + lookup tables). | v2 §02 |
| AC-D-02 | `02-domain-design.mmd` shows top-down hierarchy GitProfile → Repo → RepoVersion → History/Action with App linkable to either Repo or GitProfile via AppLink. | brief §Domain.1 |
| AC-D-03 | `03-endpoints-write.mmd` covers all 4 write endpoints: `/append-log`, `/fixed-log`, `/clear-log`, `/clear-log-all`, with full request fields and HasError flip behavior. | brief §Endpoints.2–5 |
| AC-D-04 | `04-endpoints-read.mmd` covers all 6 read endpoints incl. URL-style `?q=` variants and the error-only variants. | brief §Endpoints.6–11 |
| AC-D-05 | `05-auth-validation.mmd` ordered: parse → GitProfile lookup → Acceptance → Branch → TempToken → Token → Profile status → App status, with explicit GL-* reject codes. | v2 §05 |
| AC-D-06 | `06-permission-flow.mmd` resolves WP user → Profile → RolePermission union → Permission check (never role name). | v2 §05 + brief §5 |
| AC-D-07 | All diagrams emoji-free (no lexer errors); render successfully via Mermaid CLI. | rendering smoke test |
| AC-D-08 | No diagram references JWT, RS256, or JWKS (dropped in v2). | locked decision 5 |
