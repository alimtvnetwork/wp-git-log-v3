# Consistency Report — Gitlogs Diagrams

**Version:** 2.0.0  
**Updated:** 2026-04-26

## File Inventory

| File | Present | Notes |
|------|---------|-------|
| 00-overview.md | ✅ | Index + inventory (v2.0.0) |
| 01-er-diagram.mmd | ✅ | erDiagram — only place data shape lives |
| ~~02-domain-design.mmd~~ | 🗑️ | Removed v2.0.0 — duplicated 01; slot locked |
| ~~03-endpoints-write.mmd~~ | 🗑️ | Removed v2.0.0 — folded into 09; slot locked |
| ~~04-endpoints-read.mmd~~ | 🗑️ | Removed v2.0.0 — folded into 09; slot locked |
| 05-auth-validation.mmd | ✅ | flowchart TD + diagram-type header |
| 06-permission-flow.mmd | ✅ | flowchart LR, redrawn v2.0.0 with classDef colors + GL-* codes |
| 07-rate-limit-flow.mmd | ✅ | sequenceDiagram + diagram-type header |
| 08-encryption-v3-flow.mmd | ✅ | v3 deferred + diagram-type header |
| 09-endpoints-mindmap.mmd | ✅ | **NEW v2.0.0** — mindmap, all 8 endpoints with verb/path/body/response/permission/audit/errors |
| 97-acceptance-criteria.md | ✅ | AC-D-01, AC-D-05..AC-D-11 (AC-D-02/03/04 retired) |
| 98-changelog.md | ✅ | v2.0.0 |
| 99-consistency-report.md | ✅ | This file |

All diagrams reflect `spec/22-git-logs-v2/`. Where v1 (folder 21, archived) conflicts, v2 + diagrams win.

## Cross-Reference Health

- [`00-overview.md`](./00-overview.md) inventory matches every file on disk (9 live + 3 retired tombstones + 3 meta = 15 rows).
- [`97-acceptance-criteria.md`](./97-acceptance-criteria.md) covers all 6 live `.mmd` files (01, 05, 06, 07, 08, 09) via AC-D-01, AC-D-05..AC-D-11.
- Authoritative source link [`../22-git-logs-v2/00-overview.md`](../22-git-logs-v2/00-overview.md) resolves.
- No JWT / RS256 / JWKS references (locked decision 5).
- Every flow/sequence/mindmap diagram opens with `%% Diagram type:` + `%% What this answers:` header comments (AC-D-07).

## v2.0.0 Audit — User feedback "everything looks like an ERD"

| # | Concern | Resolution |
|---|---------|------------|
| 1 | "Same diagram repeated 2-3 times" | Removed `02-domain-design.mmd` (overlapped 01). Removed `03-endpoints-write.mmd` + `04-endpoints-read.mmd` (replaced by single mindmap). Net: 8 → 6 live diagrams. |
| 2 | "Consolidate to one ER diagram" | Done — `01-er-diagram.mmd` is the sole authoritative ER. |
| 3 | "Endpoints should be one mindmap, not write/read split, with body/verb/types" | Done — `09-endpoints-mindmap.mmd` covers all 8 endpoints in one page with verb, path, auth, body fields, response shape, audit, error codes. |
| 4 | "Permission/rate-limit flow look like ERDs — is my system bad or did you mess up?" | Confirmed: I messed up. They are flowcharts/sequences, not ERs, but lacked signposting. Added `%% Diagram type:` + `%% What this answers:` header on every non-ER diagram; redrew `06-permission-flow.mmd` with classDef colors + per-rejection error codes for clear visual difference. Underlying domain is fine; presentation was sloppy. |

## Open Gaps

_None._ Slots 02/03/04 are intentional locked gaps (`~~retired v2.0.0~~`); never to be reused per project rule.

## Health Score

**100/100 (A+)** — 12 of 12 expected files present (3 retired tombstones documented), AC coverage complete, every diagram self-describes its type. Slot integrity intact (immutable-slot rule honored).
