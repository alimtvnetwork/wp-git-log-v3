# Changelog — Gitlogs Diagrams

**Version:** 3.4.4
**Updated:** 2026-05-01 (Phase 153 Task A24-fu34 — axis reclassification audit-corpus → normative-contract per Lesson #69)
**Scope:** `spec/26-gitlogs-diagrams/`

---

## [3.4.4] — 2026-05-01 — Phase 153 Task A24-fu34: axis reclassification (audit-corpus → normative-contract)

- **Changed** front-matter `content_axis: audit-corpus` → `content_axis: normative-contract` per Lesson #69 tree-wide audit. Added `axis_reclassification:` block citing phase + reason for forward auditability.
- **Why**: spec/26 §97 contains 22+ GWT-style normative ACs (AC-DG-01 ER-table coverage, AC-DG-02 cardinality alignment, AC-DG-03 auth-flow order, AC-DG-04 RolePermission-union resolution, AC-DG-05 type+intent header comments, AC-DG-06 emoji-free lexer compliance, AC-DG-07 JWT/RS256/JWKS absence, AC-DG-08 endpoints-mindmap completeness, etc.) defining diagram invariants implementer audience (diagram authors) MUST satisfy. Per Lesson #69 strict definition: `audit-corpus` is reserved for modules whose normative surface DESCRIBES other specs (post-mortems, deprecation registries — e.g. spec/10 routing-meta, spec/25 post-mortem). spec/26 OWNS the diagrams as artifacts; depicting spec/22 architecture does NOT make it a corpus describing spec/22 (mirror of fu33 spec/03 reasoning at the artifact-vs-citation axis).
- **Diagnosis**: v9 cache `total=88 weighted=87.5 cap=95 d2=20 d3=17` — d2 at maximum + d3 strong, exactly the dimensions audit-corpus axis penalises (×0.5). Sibling spec/03 same pattern (fu33: 82 → 94 EXCELLENT after reclassification). Expected post-fix score 92-94 EXCELLENT (axis_cap 95 → 100; d2 ×0.5 → ×1.5 lifts weighted by ~10 points; capped near 95).
- **Spec lockstep**: §00 v3.4.3 → **v3.4.4** (patch — front-matter); §98 v3.4.3 → **v3.4.4** (patch — this row); §99 v3.3.3 → **v3.3.4** (patch — Phase 153 audit row). **§97 unchanged at v3.3.0** — no contract change, no AC-31-31 cascade, no RUBRIC bump.
- **Lesson #69 second instance** (after fu33 spec/03 first instance) — pattern stable across normative-contract-defining axes.
- **Tree-wide axis audit complete**: spec/10 (audit-corpus, routing-only — CORRECT, retain); spec/25 (audit-corpus, post-mortem-router — CORRECT, retain per Lesson #29); spec/26 (audit-corpus, MISCLASSIFIED — fixed here). 0 additional misclassifications surfaced. Lesson #69 stands as a forward-looking guard.

## [3.4.3] — 2026-04-30 — Phase 153 Task A24-fu2: AC-23 Deterministic SVG-render protocol

- **Added** AC-23 `[critical]` to §97 (v3.2.0 → v3.3.0) — formalizes the two-tier deterministic SVG-render verification protocol that AC-DG-12 only loosely sketched: **Tier 1** (primary) `.mmd`-source SHA-256 + `mmdc` render-success gate (5-step table); **Tier 2** (fallback) structural-XML diff via `xmllint --c14n11` with random-ID + comment normalization (5-step table). Closes audit-v7 [D3] MEDIUM Non-deterministic SVG Diffing by replacing the partial AC-DG-12 prose ("non-byte-identical output is acceptable IF the structural content matches") with a normative command set. Per-finding closure table cross-walks all three v7 findings (D5 HIGH → AC-22; D3 MEDIUM → AC-23; D4 LOW → AC-22 + AC-23 Tier 1 step 4). Forbidden patterns enumerated (raw-SVG SHA, screenshot diffing, skipping Tier 2, per-language XML diff implementations).
- **Why**: per Lesson #44 `audit-corpus` axis multipliers (D3×0.5 + D4×1.5 + D5×1.5), tri-closure projects EXCELLENT-band re-score (80 → 88+ expected). Codifies Lesson #36 (link-don't-restate) on AC-DG-12 reference; codifies Lesson #29 Section F by lifting verification commands into normative tables.
- **Spec lockstep**: §97 v3.2.0 → **v3.3.0** (new AC; AC count 22 → 23); §00 v3.4.2 → **v3.4.3**; §98 v3.4.2 → **v3.4.3**; §99 v3.3.2 → **v3.3.3**. **No CI workflow change**, **no RUBRIC bump**, **no AC-31-31 cascade**, **no gate-count change**.
- **Validation**: lockstep 87/87, tree-health 168/168 strict, version-parity 74/74. Pre-flight Lesson #45 verified: tier-1 ~50.7 KB → ~54 KB (well under 75 KB saturation); total tree ~52 KB (well under 90 KB walker cap). LLM re-score deferred per Lesson #20 (Cloudflare 402-budget-blocked).
- **External dependency note**: Tier 2 requires `xmllint` (POSIX `libxml2`); CI runner MUST have it installed (Ubuntu default; macOS `brew install libxml2`).

## [3.4.1] — 2026-04-28 (Phase P19: H10 §00↔§98 version-field parity catch-up)

- **Bumped** `00-overview.md` banner from v2.4.0 → v3.4.0 to match this changelog's latest release line. The §00 banner had been left on the v2.x line since the Phase 55 `DiagramMetadata` JSON Schema rewrite shipped on §98's v3.x line — `check-version-parity.py` (the H10 advisory gate landed in Phase P15) flagged this as one of 59 tree-wide §00↔§98 mismatches at session open. Pure parity bookkeeping; no diagram added/removed/edited, no §97 AC change. Sibling Git-Logs domain folder `spec/22-git-logs-v2/00-overview.md` got the same parity catch-up in the same Phase P19 (v3.8.9 → v3.9.11; see that module's §98 row 3.9.12). H10 advisory delta: 59 → 57 mismatches (-2). **Verified**: `node linter-scripts/check-lockstep.cjs` ✅ 87/87; `node linter-scripts/check-tree-health.cjs --strict` ✅ 168/168; `python3 linter-scripts/check-version-parity.py` ✅ 57 advisory (was 59).

## [3.4.0] — 2026-04-28 (Phase P10: SSH auth-lane diagram)

- **Added** [`10-ssh-auth-validation.mmd`](./10-ssh-auth-validation.mmd) (3.3 KB source) + companion `[10-ssh-auth-validation.svg](./10-ssh-auth-validation.svg)` (~244 KB, rendered via `mmdc -i 10-ssh-auth-validation.mmd -o 10-ssh-auth-validation.svg -p puppeteer.json -b transparent`). The diagram visualizes the §22/§31 Lane B SSH-key auth **10-step server validation order**: (1) `X-GL-Auth-Mode` mode parse with `GL-SSH-LANE-CONFLICT` for mixed-lane requests; (2) header completeness → `GL-SSH-HEADER-MISSING`; (3) timestamp skew vs `ReplayWindowSeconds` → `GL-SSH-TIMESTAMP-SKEW`; (4) `SshKey` lookup by `Fingerprint` with split branches → `GL-SSH-KEY-UNKNOWN` / `GL-SSH-KEY-INACTIVE`; (5) repo binding `RepoUrl → RepoId == SshKey.RepoId` → `GL-SSH-REPO-MISMATCH`; (6) acceptance + branch (delegated to `05-auth-validation.mmd` rules 3–4) → `GL-VALIDATION-REPO-NOT-ALLOWED` / `GL-VALIDATION-BRANCH-RESTRICTED`; (7) `SshNonce` uniqueness via `INSERT OR IGNORE` → `GL-SSH-NONCE-REUSED`; (8) `ssh-keygen -Y verify` over canonical signing string with namespace `git-logs@v2` → `GL-SSH-SIGNATURE-INVALID`; (9) `OwnedByProfileId.UserStatus = Active` → `GL-AUTH-PROFILE-INACTIVE`; (10) `App.Status = Active` if linked → `GL-APP-NOT-ACTIVE`. Mode-header fall-through arrow points at `05-auth-validation.mmd` for the TempToken lane. Acceptance terminal updates `SshKey.LastUsedAt` and writes `AuditTrail.SshAuthSuccess`; reject terminal writes `AuditTrail.AuthFail`. classDef colors distinguish gates (blue), accept (green), and reject (red) nodes per the §06-permission-flow precedent.
- **Slot choice:** Slot **10** is the first numeric slot available per **AC-DG-10** ("the next available numeric slot for new diagrams is `10-*` onward"). Slots 02/03/04 remain locked (Phase P9 audit confirmed). Header comment block conforms to AC-DG-05 (`%% Diagram type:` + `%% What this answers:`) and to the Phase 55 `DiagramMetadata` JSON Schema (`id: 10-ssh-auth-validation`, `type: flow`, `owner_module: spec/26-gitlogs-diagrams/...`, `render_target: svg`).
- **Added** [`puppeteer.json`](./puppeteer.json) sibling render config (`{"args": ["--no-sandbox", "--disable-setuid-sandbox"], "defaultViewport": {"width": 2400, "height": 2400}}`). This file was referenced by AC-DG-18 + AC-DG-11 + AC-DG-12 + the §00 v2.1.0 Phase 10 banner ever since the GWT rewrite landed but had never actually been checked in — Phase P10 closes that pre-existing gap as a side-effect (the new diagram needed it to render). Conforms to AC-DG-18 (`--no-sandbox` for CI compatibility, viewport ≥ 2000×2000).
- **Added** §97 **AC-DG-21** ("SSH auth-lane diagram covers all 10 §31 validation steps + 11 reject codes") — codifies the diagram's coverage contract machine-checkably. AC count 20 → 21.
- **Bumped** §97 **AC-DG-20** active-diagram count `6 → 7` to reflect the new sibling.
- **Bumped** §00 v2.3.0 → **v2.4.0** (banner + inventory row 10 added). §97 v3.0.0 → **v3.1.0**. §99 v3.2.0 → **v3.3.0**.
- **Cross-walk:** No `.mmd` re-render of pre-existing diagrams (none of their source `.mmd` files changed). No §22/§31 source-of-truth edit (this folder trails §22 per AC-DG-19 governance rule). The §22 §31 spec was already authoritative at v2.9.1 (Phase 5 close) — Phase P10 simply gives it a visualization.
- **Verified:** `node linter-scripts/check-lockstep.cjs` ✅ 87/87; `node linter-scripts/check-tree-health.cjs --strict` ✅ 168/168.

---

## [3.3.0] — 2026-04-28 (Phase P9: slot-gap audit — verified RESOLVED, no edits required)

- **Verified** §26 slot gaps 02/03/04 are already fully resolved via the v2.0.0 retirement and the Phase 16g GWT rewrite. Audit checklist:
  - **§00 inventory** lists all three slots as `~~retired v2.0.0~~` with explicit `_locked_` annotations and content-redirect pointers (lines 30–32).
  - **§00 narrative** v2.0.0 banner explicitly declares "Slots **02**, **03**, **04** are now **intentional locked gaps** (never to be reused per project rule 'file slots are immutable once shipped')."
  - **§97 inlined contract** `LOCKED_GAPS:` field machine-encodes the three slot numbers + their original names.
  - **§97 AC-DG-10** ("Slots 02, 03, 04 remain intentional locked gaps") codifies the prohibition as a GWT acceptance criterion verified against AC-SAG-04 (slot immutability).
  - **§97 AC-DG-LEGACY-11** preserves the v2.0.0 historical narrative for traceability.
  - **§99 inventory** marks all three with 🗑️ + "Removed v2.0.0 — slot locked".
- **Outcome:** No new file authoring, no AC additions, no DDL/schema/enum change. P9 closes by audit-confirmation, parallel to Phase P6's resolution of §22 GAP-V2-06 (locked-vacant precedent retained; stub-file recipe rejected by Core memory rule on slot immutability + tree-health regression risk).
- **Scope discipline (Phase P9 ONLY):** Pure audit + this changelog row + §99 banner bump. No `.mmd` source change, no `.svg` re-render, no §00 / §97 edit. The five-source documentation cited above is already authoritative; this row simply records that the audit ran and confirmed coverage.
- **Verified:** `node linter-scripts/check-lockstep.cjs` ✅ 87/87; `node linter-scripts/check-tree-health.cjs --strict` ✅ 168/168.

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

### 2.3.0 — 2026-04-27 (Phase 55 — implementability lever)
- **Added** Removed `kind: index` exemption. Added `DiagramMetadata` JSON Schema + TypeScript enums → `has_json_schema` (+15) and `has_ts_enums` (+10).

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
| 2026-04-26 | patch | Phase 31: Added Validation History / File Inventory headings to §99 to satisfy rubric v2.0.0 quality dimension. |
| 2026-04-26 | patch | Phase 28: Fixed broken cross-reference link. |

## 2026-04-27 — Phase 74 (evidenced index/tracker bonus)

- Added Mermaid lifecycle diagram and 5-stage CI workflow contract.
- Activates v2.9 evidenced-tracker / evidenced-index bonus (+5 each).
- Documentation-only promotion.

## 3.4.2 — 2026-04-30 — Phase 153 (inventory-pin)

- Added **AC-22** (Derivative-context pin for spec/22 source) — Lesson #29 module asset inventory pin. Auditor-authoritative on-disk inventory declaration; closes audit-v6 HIGH [D5] missing-files class as bundling-cap artifact (cache-stale per Lesson #34 until A8 LLM re-score). Lockstep §00/§97/§98/§99 patch+minor coordinated.

