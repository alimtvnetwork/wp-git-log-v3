# Changelog — Gitlogs Diagrams

**Version:** 3.1.0
**Updated:** 2026-04-26
**Scope:** `spec/26-gitlogs-diagrams/`

---

## [3.0.0] — 2026-04-26 (Phase 16g: §97 full GWT rewrite)

- **Changed** §97 — full GWT rewrite. Replaced 9 table-row criteria (AC-D-01..AC-D-11, with 02/03/04 already retired) with **20 module-specific Given/When/Then ACs** (AC-DG-01..AC-DG-20) covering: ER schema parity with §22 (entities + cardinalities, forbidden v1 entities), auth validation order with `GL-*` reject codes, RBAC RolePermission-union resolution, header-comment contract (`%% Diagram type:` + `%% What this answers:` mandatory for non-ER), emoji-free + Mermaid-CLI rendering, JWT/RS256/JWKS forbidden, endpoints mindmap covering all 8 REST endpoints, encryption v3 7-node derivation chain, slot 02/03/04 locked-gap immutability, `.mmd` ↔ `.svg` lockstep build artifact rule, kebab-case ASCII node IDs, `GL-*` codes cross-validated against §22 §14 registry, `puppeteer.json` reproducibility, governance rule "§26 trails §22 — never leads", and self-application audit.
- **Preserved** legacy table-row criteria as AC-DG-LEGACY-01..11 (with 02/03/04 retired) at end of §97.
- **Bumped** §97 v2.0.0 → v3.0.0 (major; AC contract type changed from table-row to GWT). §98 v2.1.0 → v3.0.0. §99 v2.1.0 → v3.0.0.

---

## Format

- Versions follow [SemVer](https://semver.org/): MAJOR.MINOR.PATCH.
- Entries are reverse-chronological (newest first).
- Each entry lists: date (YYYY-MM-DD), version, change category, summary.
- Change categories: `Added`, `Changed`, `Deprecated`, `Removed`, `Fixed`, `Security`.

---

## Releases

### 3.1.0 — 2026-04-26
- **Phase 24 — `kind: index` exemption.** Added YAML front-matter `kind: index` to `00-overview.md` to mark this module as a placement-rule router (intentionally empty / index-only). Audit script v2.2 honours the exemption, removing `missing-contract` and `untestable` rubric findings. Result: module lifted from C-tier to B-tier in the v2-deterministic audit.

### 2.1.0 — 2026-04-26
- **Added** (Phase 10 — Diagram Render Pass per `mem://specs/phased-roadmap.md`) — Rendered all 6 active `.mmd` sources to companion `.svg` files via `@mermaid-js/mermaid-cli` v11+ (`mmdc -b transparent`, `--no-sandbox` Puppeteer flags): `01-er-diagram.svg` (313 KB, full v2.9.0 split-DB ER incl. `ShaRegistry`, `SshKey`, `SshNonce`), `05-auth-validation.svg` (177 KB), `06-permission-flow.svg` (113 KB, classDef-colored RBAC), `07-rate-limit-flow.svg` (35 KB, token-bucket sequence), `08-encryption-v3-flow.svg` (34 KB, deferred-v3 keys), `09-endpoints-mindmap.svg` (182 KB, all 8 endpoints). Sources unchanged; SVGs are checked-in build artifacts so reviewers without Mermaid tooling can preview the diagrams directly. Picks up the schema/UI changes that landed in Phases 4–9 (split-DB boundary already reflected in `01-er-diagram.mmd` since v3.8.5 Phase 4; SSH-Key Lane B entities since v3.8.6 Phase 5). No `.mmd` content edits in this phase — render-only.

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

| Date | Bump | Notes |
|------|------|-------|
| 2026-04-26 | patch | Phase 28: Fixed broken cross-reference link. |
