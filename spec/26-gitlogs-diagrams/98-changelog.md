# Changelog — Gitlogs Diagrams

**Version:** 2.0.0  
**Updated:** 2026-04-26  
**Scope:** `spec/26-gitlogs-diagrams/`

---

## Format

- Versions follow [SemVer](https://semver.org/): MAJOR.MINOR.PATCH.
- Entries are reverse-chronological (newest first).
- Each entry lists: date (YYYY-MM-DD), version, change category, summary.
- Change categories: `Added`, `Changed`, `Deprecated`, `Removed`, `Fixed`, `Security`.

---

## Releases

### 2.0.0 — 2026-04-26
- **Removed** (MAJOR) — `02-domain-design.mmd` retired; it overlapped ~70% with `01-er-diagram.mmd` (both showed GitProfile → Repo → RepoVersion connectivity), causing user confusion that "everything looks like an ERD". Hierarchy info now lives in the ER's relationship arrows + the prose schema in `../22-git-logs-v2/02-database-schema.md`. Slot 02 is now an intentional locked gap.
- **Removed** (MAJOR) — `03-endpoints-write.mmd` and `04-endpoints-read.mmd` retired. Two sequence diagrams arbitrarily split the REST API by HTTP verb, fragmenting endpoint discovery. Slots 03 + 04 are now intentional locked gaps.
- **Added** — `09-endpoints-mindmap.mmd` (NEW). Single Mermaid `mindmap` covering all 8 endpoints (`/append-log`, `/fixed-log`, `/clear-log`, `/clear-log-all`, `/get-logs`, `/get-pipeline-logs`, `/get-error-logs`, `/get-pipeline-error-logs`) under `Writes` / `Reads` / `Cross-cutting` branches. Each endpoint branch carries: HTTP verb, full path, auth requirement, request-body fields with types, response shape, possible GL-* error codes, audit category. Replaces former AC-D-03 + AC-D-04.
- **Added** — `%% Diagram type: …` + `%% What this answers: …` header comments to every flowchart/sequence/mindmap (`05`, `06`, `07`, `08`, `09`). Resolves user feedback "permission flow / rate-limit flow look like ERDs" — they were always flowcharts/sequences but lacked explicit signposting.
- **Changed** — `06-permission-flow.mmd` redrawn: added classDef colors (input/step/decision/allow/deny), per-rejection GL-* error codes (`GL-AUTHZ-WP-AUTH-FAILED`, `GL-AUTHZ-NO-PROFILE`, `GL-AUTHZ-PERMISSION-DENIED`), and a `Seed` subgraph showing Admin/Editor/Viewer role → permission seeds. Same flowchart shape, much more visually distinct from the ER.
- **Changed** — `00-overview.md` inventory rewritten to show Diagram type column and tombstone rows for retired slots 02/03/04, plus a layman "Why so few diagrams now" section.
- **Changed** — `97-acceptance-criteria.md`: AC-D-02/03/04 marked retired; AC-D-09 reused for the new mindmap; AC-D-11 added for the locked-slot rule.
- **Changed** — CI lock: cross-link checker now wired in `.github/workflows/spec-health.yml` (zero broken links allowed baseline). See `spec/27-spec-toolchain/70-spec-health-yml.md` v1.1.0.

### 1.1.0 — 2026-04-25
- **Fixed** inventory drift: `00-overview.md` and `99-consistency-report.md` now list all 8 `.mmd` files plus `97`/`98`. Previously rows 07 (rate-limit) and 08 (encryption-v3) existed on disk but were undocumented, causing the v2 audit to false-flag them as missing.
- **Added** clickable relative links for every entry in the overview inventory.
- **Added** §99 cross-reference health and explicit "Open Gaps: none" closure.

### 1.0.0 — 2026-04-25
- **Added** baseline module structure (00-overview, 97-acceptance-criteria, 98-changelog, 99-consistency-report).
- **Added** module-specific files per current inventory in `99-consistency-report.md`.
- Auto-scaffolded by `linter-scripts/fill-missing-changelogs.cjs` as part of root v3.7.x Phase 2c sweep.

---

## Cross-References

- [Module overview](./00-overview.md)
- [Module acceptance criteria](./97-acceptance-criteria.md)
- [Module consistency report](./99-consistency-report.md)
