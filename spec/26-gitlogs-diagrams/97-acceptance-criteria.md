# Acceptance Criteria — Gitlogs Diagrams

**Version:** 2.0.0  
**Updated:** 2026-04-26

| # | Criterion | Source |
|---|-----------|--------|
| AC-D-01 | `01-er-diagram.mmd` includes every table from `spec/22-git-logs-v2/02-database-schema.md` (Profile, RoleAssignment, RolePermission, GitProfile, Repo, RepoVersion, Pipeline, ShaRegistry, App, AppLink, History, PipelineAction, SystemEvent, AuditTrail, MigrationState + lookup tables). v3.8.0: removed `LogEntry`/`ErrorLogEntry`/`OwnerType`; added `ShaRegistry`/`SystemEvent`/`PipelineAction`/`SystemEventType`. | v2 §02 + §39 |
| ~~AC-D-02~~ | _Retired v2.0.0 — `02-domain-design.mmd` deleted; hierarchy info lives in ER relationship arrows + `02-database-schema.md` prose._ | — |
| ~~AC-D-03~~ | _Retired v2.0.0 — `03-endpoints-write.mmd` deleted; covered by AC-D-09 (mindmap)._ | — |
| ~~AC-D-04~~ | _Retired v2.0.0 — `04-endpoints-read.mmd` deleted; covered by AC-D-09 (mindmap)._ | — |
| AC-D-05 | `05-auth-validation.mmd` ordered: parse → GitProfile lookup → Acceptance → Branch → TempToken → Token → Profile status → App status, with explicit GL-* reject codes. Header comment declares diagram type + intent. | v2 §05 |
| AC-D-06 | `06-permission-flow.mmd` resolves WP user → Profile → RolePermission union → Permission check (never role name); each reject branch carries the GL-* code; classDef colors distinguish input/step/decision/allow/deny visually. Header comment declares diagram type + intent. | v2 §05 + §19 |
| AC-D-07 | All diagrams emoji-free (no lexer errors); render successfully via Mermaid CLI. Every flowchart / sequenceDiagram / mindmap opens with `%% Diagram type:` and `%% What this answers:` comments so readers can't mistake them for the ER. | rendering smoke test + readability |
| AC-D-08 | No diagram references JWT, RS256, or JWKS (dropped in v2). | locked decision 5 |
| AC-D-09 | **NEW v2.0.0** — `09-endpoints-mindmap.mmd` is a `mindmap` (not a sequence) that lists all 8 REST endpoints under `Writes` / `Reads` / `Cross-cutting` branches, each carrying: HTTP verb, full path, auth requirement, request-body fields, response shape, audit category, and applicable GL-* error codes. Replaces the deleted `03-endpoints-write.mmd` + `04-endpoints-read.mmd`. | v2 §04 + §14 |
| AC-D-10 | `08-encryption-v3-flow.mmd` covers MasterKey → DataKey → LookupKey derivation, ALTER + per-row encryption, MigrationState insert, idempotency. Header comment declares diagram type + intent. | v2 §11 |
| AC-D-11 | **NEW v2.0.0** — Slots **02**, **03**, **04** are intentional locked gaps; `00-overview.md` inventory shows them as `~~retired v2.0.0~~`; no future diagram reuses these numbers. | project rule "file slots immutable" |
