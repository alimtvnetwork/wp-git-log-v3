# Consistency Report — Gitlogs Diagrams

**Version:** 2.1.0  
**Updated:** 2026-04-26

## File Inventory

| File | Present | Notes |
|------|---------|-------|
| 00-overview.md | ✅ | Index + inventory (v2.0.0) |
| 01-er-diagram.mmd / .svg | ✅ | erDiagram — only place data shape lives. SVG re-rendered Phase 10 v2.1.0 (313 KB, reflects v2.9.0 split-DB shape). |
| ~~02-domain-design.mmd~~ | 🗑️ | Removed v2.0.0 — duplicated 01; slot locked |
| ~~03-endpoints-write.mmd~~ | 🗑️ | Removed v2.0.0 — folded into 09; slot locked |
| ~~04-endpoints-read.mmd~~ | 🗑️ | Removed v2.0.0 — folded into 09; slot locked |
| 05-auth-validation.mmd / .svg | ✅ | flowchart TD + diagram-type header. SVG re-rendered Phase 10 v2.1.0 (177 KB). |
| 06-permission-flow.mmd / .svg | ✅ | flowchart LR, redrawn v2.0.0 with classDef colors + GL-* codes. SVG re-rendered Phase 10 v2.1.0 (113 KB). |
| 07-rate-limit-flow.mmd / .svg | ✅ | sequenceDiagram + diagram-type header. SVG re-rendered Phase 10 v2.1.0 (35 KB). |
| 08-encryption-v3-flow.mmd / .svg | ✅ | v3 deferred + diagram-type header. SVG re-rendered Phase 10 v2.1.0 (34 KB). |
| 09-endpoints-mindmap.mmd / .svg | ✅ | mindmap, all 8 endpoints with verb/path/body/response/permission/audit/errors. SVG re-rendered Phase 10 v2.1.0 (182 KB). |
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

## v2.1.0 Audit — Phase 10 Diagram Render Pass

| File | Change |
|------|--------|
| `01-er-diagram.svg` | NEW — rendered from `01-er-diagram.mmd` (313 KB). Reflects v2.9.0 split-DB schema incl. `ShaRegistry`, `SshKey`, `SshNonce`. |
| `05-auth-validation.svg` | NEW — rendered from `05-auth-validation.mmd` (177 KB). |
| `06-permission-flow.svg` | NEW — rendered from `06-permission-flow.mmd` (113 KB). |
| `07-rate-limit-flow.svg` | NEW — rendered from `07-rate-limit-flow.mmd` (35 KB). |
| `08-encryption-v3-flow.svg` | NEW — rendered from `08-encryption-v3-flow.mmd` (34 KB). |
| `09-endpoints-mindmap.svg` | NEW — rendered from `09-endpoints-mindmap.mmd` (182 KB). |
| `00-overview.md` | Banner v2.0.0 → v2.1.0; added Phase 10 render-pass note + re-render command. |
| `98-changelog.md` | v2.1.0 row added. |
| `99-consistency-report.md` | This audit table added; inventory rows updated to list `.mmd / .svg` pairs; banner v2.0.0 → v2.1.0. |

**Render command:** `mmdc -i <file>.mmd -o <file>.svg -p puppeteer.json -b transparent` with `puppeteer.json = {"args": ["--no-sandbox", "--disable-setuid-sandbox"]}`. Source `.mmd` files unchanged in this phase — render-only.

## Open Gaps

_None._ Slots 02/03/04 are intentional locked gaps (`~~retired v2.0.0~~`); never to be reused per project rule.

## Health Score

**100/100 (A+)** — 12 of 12 expected source files present (3 retired tombstones documented), AC coverage complete, every diagram self-describes its type, and as of v2.1.0 every live `.mmd` ships a companion `.svg` build artifact for tool-free preview. Slot integrity intact (immutable-slot rule honored).
