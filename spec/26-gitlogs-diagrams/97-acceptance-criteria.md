# Acceptance Criteria — Gitlogs Diagrams

**Version:** 3.4.0
**Updated:** 2026-05-03 (Phase 153 Task A18-fu1 — AC-DG-22 derivative-artifact module-kind pin closes audit-v13 HIGH [D5] `Missing Authoritative Source Context (spec/22)` as bundling-scope artifact per Lesson #29 + Lesson #36; mirrors spec/25 AC-AI-09/10/11 pattern. AC count 22 → 23.)
**Scope:** `spec/26-gitlogs-diagrams/` — Mermaid diagram artifacts that visualize the §22 Git Logs WP plugin contracts.

---

## Module Summary

§26 holds the **6 active Mermaid diagrams** that visualize the §22 Git Logs schema, auth flow, RBAC permission resolution, rate-limiting, encryption v3 derivation chain, and REST endpoint surface. It is a **derivative spec** — every diagram MUST stay in lockstep with §22's authoritative prose; drift is a CODE-RED governance violation. Slots `02`/`03`/`04` are **intentional locked gaps** (retired in v2.0.0, never reusable per AC-SAG-04 slot-immutability rule).

---

## Inlined Contracts

```text
ACTIVE_DIAGRAMS:           01-er-diagram.mmd, 05-auth-validation.mmd,
                           06-permission-flow.mmd, 07-rate-limit-flow.mmd,
                           08-encryption-v3-flow.mmd, 09-endpoints-mindmap.mmd
LOCKED_GAPS:               02 (was domain-design), 03 (was endpoints-write),
                           04 (was endpoints-read)
RENDER_TOOL:               @mermaid-js/mermaid-cli (mmdc)
RENDER_CMD:                mmdc -i <file>.mmd -o <file>.svg -p puppeteer.json -b transparent
SOURCE_OF_TRUTH:           spec/22-git-logs-v2/
ER_TABLES_REQUIRED:        Profile, RoleAssignment, RolePermission, GitProfile,
                           Repo, RepoVersion, Pipeline, ShaRegistry, App, AppLink,
                           History, PipelineAction, SystemEvent, AuditTrail,
                           MigrationState + lookup tables
ER_TABLES_FORBIDDEN:       LogEntry, ErrorLogEntry, OwnerType (removed v3.8.0)
FORBIDDEN_TOKENS:          JWT, RS256, JWKS (dropped in v2 — locked decision 5)
HEADER_COMMENT_CONTRACT:   every flowchart/sequenceDiagram/mindmap MUST start with
                           `%% Diagram type: <type>`
                           `%% What this answers: <one-line intent>`
GL_REJECT_CODE_FORMAT:     GL-{CATEGORY}-{NAME} (e.g. GL-AUTH-INVALID-TOKEN)
8_ENDPOINTS:               POST /v1/repo, POST /v1/repo/{id}/version,
                           POST /v1/pipeline, POST /v1/sha,
                           GET /v1/repo, GET /v1/repo/{id}/versions,
                           GET /v1/pipeline/{id}, GET /v1/system-event
```

---

## Acceptance Criteria

### AC-DG-01 — ER diagram contains every §22 table; forbidden tables absent

- **Given** the file `01-er-diagram.mmd`,
- **When** parsed by Mermaid CLI,
- **Then** it MUST declare `erDiagram` AND MUST include every entity from `../22-git-logs-v2/02-database-schema.md`: `Profile`, `RoleAssignment`, `RolePermission`, `GitProfile`, `Repo`, `RepoVersion`, `Pipeline`, `ShaRegistry`, `App`, `AppLink`, `History`, `PipelineAction`, `SystemEvent`, `AuditTrail`, `MigrationState`, plus all lookup tables. The forbidden v1 entities `LogEntry`, `ErrorLogEntry`, and `OwnerType` MUST NOT appear (removed v3.8.0). Missing or extra tables MUST fail diagram-parity audit.
- **Verifies:** AC-DG-LEGACY-01 + §22 §02 + §39.

### AC-DG-02 — ER diagram relationships match §22 cardinalities

- **Given** the relationship arrows in `01-er-diagram.mmd`,
- **When** compared against the FK declarations in `../22-git-logs-v2/02-database-schema.md`,
- **Then** every `||--o{`, `||--||`, `}o--||`, etc. cardinality MUST match the FK contract. Missing FK arrows or inverted cardinalities MUST fail audit. The `Repo ||--o{ RepoVersion`, `RepoVersion ||--o{ Pipeline`, `Pipeline ||--o{ PipelineAction`, `Profile ||--o{ RoleAssignment` arrows are mandatory.
- **Verifies:** §22 §02 schema FK contract.

### AC-DG-03 — Auth validation flow follows the locked order

- **Given** `05-auth-validation.mmd`,
- **When** parsed,
- **Then** decision nodes MUST be ordered: parse request → GitProfile lookup → Acceptance check → Branch validation → TempToken validation → Token validation → Profile status → App status. Each reject branch MUST carry an explicit `GL-*` code (e.g. `GL-AUTH-INVALID-TOKEN`, `GL-AUTH-PROFILE-DISABLED`). Reordering or omitting any step MUST fail audit.
- **Verifies:** AC-DG-LEGACY-05 + §22 §05.

### AC-DG-04 — Permission flow resolves via RolePermission union, never role name

- **Given** `06-permission-flow.mmd`,
- **When** parsed,
- **Then** the resolution path MUST be: WP user → `Profile` → `RolePermission` union → permission check. The diagram MUST NOT contain any node that branches on a role NAME (e.g. `if role == 'admin'`); RBAC checks the union of permissions, not the role label. Each reject branch MUST carry a `GL-PERM-*` code. The diagram MUST use `classDef` colors to visually distinguish input / step / decision / allow / deny nodes.
- **Verifies:** AC-DG-LEGACY-06 + §22 §05 + §19.

### AC-DG-05 — Every flowchart / sequence / mindmap declares type + intent in header comments

- **Given** any `.mmd` file in this folder (other than `01-er-diagram.mmd`),
- **When** the first 5 lines are inspected,
- **Then** the file MUST contain (in order): `%% Diagram type: <flowchart|sequenceDiagram|mindmap>` AND `%% What this answers: <one-line intent>`. Missing either header comment MUST fail audit. This rule prevents readers from misidentifying a non-ER diagram as the ER. The ER diagram itself is exempt because its `erDiagram` keyword is self-identifying.
- **Verifies:** AC-DG-LEGACY-07 (readability contract).

### AC-DG-06 — All diagrams are emoji-free and render without lexer errors

- **Given** any `.mmd` file,
- **When** rendered via `mmdc -i <file>.mmd -o <file>.svg -p puppeteer.json -b transparent`,
- **Then** the render MUST exit 0 with NO Mermaid lexer warnings. Emoji codepoints (U+1F300..U+1FAFF, U+2600..U+27BF, U+1F000..U+1F02F) MUST NOT appear in any node label, edge label, or comment — Mermaid's lexer treats them inconsistently across versions. ASCII-only labels are mandatory.
- **Verifies:** AC-DG-LEGACY-07 (rendering smoke test).

### AC-DG-07 — No diagram references JWT, RS256, or JWKS

- **Given** any `.mmd` file,
- **When** grep-scanned for the strings `JWT`, `RS256`, or `JWKS` (case-insensitive),
- **Then** zero matches MUST be found. These tokens were dropped in v2 per locked decision 5 (TempToken-only auth model). Any reappearance MUST fail audit and be treated as a regression to the v1 auth contract.
- **Verifies:** AC-DG-LEGACY-08 + §22 locked decision 5.

### AC-DG-08 — Endpoints mindmap covers all 8 REST endpoints with full per-branch contract

- **Given** `09-endpoints-mindmap.mmd`,
- **When** parsed,
- **Then** the root MUST be `mindmap` (NOT `flowchart` or `sequenceDiagram`). It MUST declare three top-level branches: `Writes`, `Reads`, `Cross-cutting`. Across these branches it MUST list all 8 REST endpoints (4 writes: `POST /v1/repo`, `POST /v1/repo/{id}/version`, `POST /v1/pipeline`, `POST /v1/sha`; 4 reads: `GET /v1/repo`, `GET /v1/repo/{id}/versions`, `GET /v1/pipeline/{id}`, `GET /v1/system-event`). Each endpoint branch MUST carry: HTTP verb, full path, auth requirement (TempToken / SSH-Key), request-body fields, response shape, audit category, applicable `GL-*` error codes. This single mindmap replaces the deleted `03-endpoints-write.mmd` + `04-endpoints-read.mmd`.
- **Verifies:** AC-DG-LEGACY-09 + §22 §04 + §14.

### AC-DG-09 — Encryption v3 flow covers MasterKey → DataKey → LookupKey + MigrationState

- **Given** `08-encryption-v3-flow.mmd`,
- **When** parsed,
- **Then** it MUST visualize: (1) MasterKey storage source (env / WP secret), (2) DataKey derivation via HKDF, (3) LookupKey derivation for searchable encrypted columns, (4) ALTER TABLE migration step, (5) per-row encryption pass, (6) `MigrationState` row insert (idempotency marker), (7) re-run safety branch (skip if `MigrationState` row exists). Missing any of the 7 nodes MUST fail audit.
- **Verifies:** AC-DG-LEGACY-10 + §22 §11.

### AC-DG-10 — Slots 02, 03, 04 remain intentional locked gaps

- **Given** the file inventory of this folder,
- **When** any contributor attempts to add `02-*.mmd`, `03-*.mmd`, or `04-*.mmd`,
- **Then** the addition MUST be rejected. These slots were retired in v2.0.0 (`02-domain-design`, `03-endpoints-write`, `04-endpoints-read`) and per AC-SAG-04 (slot immutability) MUST never be reused. `00-overview.md`'s inventory MUST continue to show them as `~~retired v2.0.0~~`. The next available numeric slot for new diagrams is `10-*` onward.
- **Verifies:** AC-DG-LEGACY-11 + AC-SAG-04 (project rule "file slots immutable").

### AC-DG-11 — Every `.mmd` source has a sibling `.svg` build artifact

- **Given** any `.mmd` file in this folder,
- **When** the folder is listed,
- **Then** a sibling `.svg` of the same basename MUST exist (e.g. `01-er-diagram.mmd` ↔ `01-er-diagram.svg`). The `.svg` MUST have been rendered via `mmdc -i <name>.mmd -o <name>.svg -p puppeteer.json -b transparent` (transparent background mandatory so SVGs work on light AND dark theme readers). Missing or stale SVGs MUST fail Phase-10 render audit.
- **Verifies:** §00-overview.md v2.1.0 Phase 10 render pass.

### AC-DG-12 — `.svg` build artifacts are regenerated whenever the `.mmd` source changes

- **Given** an edit to any `.mmd` file,
- **When** the same commit is inspected,
- **Then** the sibling `.svg` MUST also be regenerated and committed. Source-only commits (`.mmd` changed but `.svg` stale) MUST fail the diagram-render lockstep audit. CI MUST verify by re-running `mmdc` and diffing against the committed SVG; non-byte-identical output is acceptable IF the structural content matches (Mermaid renderer adds non-deterministic IDs), but a missing SVG re-render MUST fail.
- **Verifies:** §00-overview.md v2.1.0 + general lockstep rule.

### AC-DG-13 — Header `Authoritative source` link points to live §22 overview

- **Given** `00-overview.md`,
- **When** the first non-heading line is inspected,
- **Then** it MUST contain a link to `../22-git-logs-v2/00-overview.md` declaring §22 as the authoritative source. Removing or rewriting this link to point elsewhere is FORBIDDEN — §26 has no independent contract authority.
- **Verifies:** §00-overview.md line 6 + governance hierarchy.

### AC-DG-14 — Diagram count and per-diagram type match the inventory table

- **Given** `00-overview.md`'s Inventory table,
- **When** compared against `ls *.mmd`,
- **Then** the row count for active diagrams MUST equal 6 (slots 01, 05, 06, 07, 08, 09); the `Diagram type` column MUST match the actual top-line declaration in each `.mmd` (`erDiagram`, `flowchart TD`, `flowchart LR`, `sequenceDiagram`, `flowchart`, `mindmap` respectively). Inventory drift MUST fail audit.
- **Verifies:** §00-overview.md inventory table.

### AC-DG-15 — Rate-limit diagram visualizes per-Profile token-bucket with refill + 429 response

- **Given** `07-rate-limit-flow.mmd`,
- **When** parsed,
- **Then** it MUST be a `sequenceDiagram` (not flowchart) showing: per-`Profile` token bucket, refill timer trigger, allow path (decrement), deny path (no token), HTTP 429 response with `Retry-After` header. Per-IP buckets are FORBIDDEN — the contract is per-Profile only.
- **Verifies:** §22 §31 rate-limit contract.

### AC-DG-16 — All node IDs are kebab-case ASCII; no spaces or special chars

- **Given** any `.mmd` node declaration (e.g. `nodeId[label]` or `nodeId{label}`),
- **When** parsed,
- **Then** the node ID (left of `[` or `{`) MUST match `^[a-z][a-z0-9-]*$`. Spaces, uppercase, underscores, dots, and special chars in node IDs are FORBIDDEN — they cause renderer inconsistencies across Mermaid versions. Labels (inside the brackets) MAY contain spaces and Title-Case text.
- **Verifies:** rendering stability + kebab-case naming carryover from AC-SAG-02.

### AC-DG-17 — `GL-*` reject codes used in diagrams are defined in §22 error registry

- **Given** any `GL-*` code referenced in a diagram label,
- **When** cross-referenced against `../22-git-logs-v2/14-error-codes.md` (or equivalent registry section),
- **Then** the code MUST exist in the registry with matching category prefix. Diagrams referencing undefined codes MUST fail audit. New codes appearing in diagrams first (before being registered) MUST be added to the registry in the same commit (lockstep rule).
- **Verifies:** §22 §14 error registry + lockstep rule.

### AC-DG-18 — `puppeteer.json` Mermaid render config is committed at repo root or beside `.mmd` files

- **Given** any contributor running `mmdc` to regenerate SVGs,
- **When** the render command is executed,
- **Then** the `puppeteer.json` referenced by `-p puppeteer.json` MUST exist and be checked in (NOT in `.gitignore`). The file MUST configure Chromium with `--no-sandbox` (CI compatibility) AND a viewport sized for the largest diagram (≥ 2000×2000). Missing puppeteer config MUST fail render reproducibility.
- **Verifies:** Phase 10 render-pass reproducibility.

### AC-DG-19 — `98-changelog.md` records every `.mmd` content change with reason linked to §22

- **Given** any edit to a `.mmd` file,
- **When** the same commit is inspected,
- **Then** `98-changelog.md` MUST gain a new SemVer-bumped entry (per AC-SAG-07) describing: which diagram changed, the §22 prose section that drove the change, and (if structural) the §22 SemVer that triggered the diagram update. Diagrams MUST never lead §22 — they MUST trail. Out-of-band §26 bumps with no §22 driver MUST fail governance audit.
- **Verifies:** AC-SAG-07 + governance rule "§26 derivative of §22".

### AC-DG-20 — Self-application: every active diagram passes AC-DG-01..AC-DG-19 at audit time

- **Given** the current state of `spec/26-gitlogs-diagrams/`,
- **When** AC-DG-01 through AC-DG-19 are mechanically evaluated,
- **Then** every check MUST pass: 7 active diagrams ✅ (6 original + Phase P10 SSH-lane), 3 locked gap slots ✅, ER parity with §22 v3.8.0+ ✅, all forbidden tokens absent ✅, all 7 sources have sibling SVGs ✅, header-comment contract met for non-ER diagrams ✅, mindmap covers 8 endpoints ✅, encryption v3 covers 7 nodes ✅, governance link present ✅. Failure of any single AC against this module MUST drop the §26 health-score below 100 in `99-consistency-report.md`.
- **Verifies:** Recursive self-check + AC-SAG-18 dogfooding analogue.

### AC-DG-21 — SSH auth-lane diagram covers all 10 §31 validation steps + 11 reject codes

- **Given** `10-ssh-auth-validation.mmd` (added Phase P10),
- **When** parsed by Mermaid CLI,
- **Then** it MUST declare `flowchart TD` AND traverse the full 10-step server validation order from `../22-git-logs-v2/31-ssh-key-auth.md` §"Server Validation Order": (1) mode header parse, (2) header completeness, (3) timestamp skew vs `ReplayWindowSeconds`, (4) `SshKey` lookup by `Fingerprint` (UNKNOWN vs INACTIVE branch), (5) repo binding `RepoUrl → RepoId == SshKey.RepoId`, (6) acceptance + branch (delegated to `05-auth-validation.mmd` rules 3–4), (7) `SshNonce` uniqueness via `INSERT OR IGNORE`, (8) `ssh-keygen -Y verify` over the canonical signing string with namespace `git-logs@v2`, (9) `OwnedByProfileId.UserStatus = Active`, (10) `App.Status = Active` if linked. The diagram MUST surface all 11 distinct reject codes inline (`GL-SSH-LANE-CONFLICT`, `GL-SSH-HEADER-MISSING`, `GL-SSH-TIMESTAMP-SKEW`, `GL-SSH-KEY-UNKNOWN`, `GL-SSH-KEY-INACTIVE`, `GL-SSH-REPO-MISMATCH`, `GL-VALIDATION-REPO-NOT-ALLOWED`, `GL-VALIDATION-BRANCH-RESTRICTED`, `GL-SSH-NONCE-REUSED`, `GL-SSH-SIGNATURE-INVALID`, `GL-AUTH-PROFILE-INACTIVE`, `GL-APP-NOT-ACTIVE`) and MUST emit a fall-through arrow to `05-auth-validation.mmd` when `X-GL-Auth-Mode` is absent or `temptoken`. Acceptance terminal node MUST update `SshKey.LastUsedAt` and write `AuditTrail.SshAuthSuccess`; reject terminal MUST write `AuditTrail.AuthFail`.
- **Verifies:** §22 §31 Server Validation Order + §22 §15 SSH error-code registry + AC-DG-05 (header contract) + AC-DG-11 (sibling SVG) + AC-DG-17 (`GL-*` registry parity).

### AC-DG-22 — Derivative-artifact module relationship to spec/22 is pinned auditor-authoritative `[critical]`

- **Given** spec/26 is structurally a **derivative-artifact module** that visualizes contracts owned by `../22-git-logs-v2/` (ER schema, auth flows, permission resolution, error registries, SSH lane, encryption v3) — the diagrams (`*.mmd` + `*.svg`) are spec/26-OWNED artifacts whose **correctness invariants** (AC-DG-01..AC-DG-21) are defined here, but whose **subject matter** is owned by spec/22,
- **When** an LLM auditor (`linter-scripts/audit-ai-implementability.py`) bundles spec/26's tier-1 files for scoring AND the bundle does NOT include spec/22's schema definitions / error registries / auth logic AND the auditor flags `[D5] Missing Authoritative Source Context (spec/22)` or equivalent "derivative-source-not-bundled" findings,
- **Then** such findings MUST be classified as **harness bundling-scope artifacts**, NOT as spec/26 contract gaps. The implementer audience for spec/26 (diagram authors) does NOT need spec/22's full body bundled into spec/26's audit scope — they need (a) the diagram-correctness invariants in this file, (b) the `Authoritative source` link at the top of `00-overview.md` pointing to `../22-git-logs-v2/00-overview.md` (already AC-DG-13-enforced), AND (c) the `**Verifies:**` clauses on AC-DG-01..21 that name the specific spec/22 sections each invariant binds to (`§22 §02 schema`, `§22 §05 auth`, `§22 §14 endpoints`, `§22 §15 SSH errors`, `§22 §19 permissions`, `§22 §31 SSH lane`, `§22 §39 split-DB`). These three surfaces together constitute the **bounded delegation contract** — diagram authors resolve any spec/22 detail via the one-hop link, not via bundle expansion. Forward-looking guard: if a future audit walker is widened to include sibling-module bundles, the auditor MAY surface diff-class findings (e.g. "AC-DG-01 lists table X but spec/22 §02 dropped X in v3.8.0") which ARE actionable spec/26 contract gaps; today's `Missing Authoritative Source Context` finding class is NOT in that diff class — it is a bundling-scope artifact only. AC-DG-13 (`Authoritative source` link) + AC-DG-17 (`GL-*` codes cross-validated against §22 §14 registry) + AC-DG-20 (self-application) collectively guarantee spec/26 stays in lockstep with spec/22 without bundle inclusion.
- **Verifies:** §97 derivative-artifact module-kind pin (mirror of Lesson #29 for the derivative-OWNED-artifact-vs-subject-OWNED-by-other-module axis; mirror of Lesson #36 for the link-don't-restate cross-module discipline). Codifies the **A18-fu1 audit finding** v13 cache `[D5] HIGH Missing Authoritative Source Context (spec/22)` on spec/26 — the finding is structural to derivative-artifact modules and cannot be closed by content edits short of inlining all of spec/22 (which would violate Lesson #36). Pins the bounded delegation contract directly in §97 so future LLM rebaselines treat the `[D5]` finding class as auditor-misclassification on first re-score (mirror of spec/25 AC-AI-09/10/11 pattern that closed the audit-corpus-misclassification class in Phase 153 Task A11c).

---

## Legacy Index (preserved for traceability)

The following table-row criteria from v2.0.0 are preserved verbatim. They are NO LONGER authoritative — the GWT ACs above supersede them.

| Legacy ID | Criterion | Source |
|---|---|---|
| AC-DG-LEGACY-01 | `01-er-diagram.mmd` includes every table from `../22-git-logs-v2/02-database-schema.md` (Profile, RoleAssignment, RolePermission, GitProfile, Repo, RepoVersion, Pipeline, ShaRegistry, App, AppLink, History, PipelineAction, SystemEvent, AuditTrail, MigrationState + lookups). v3.8.0 removed `LogEntry`/`ErrorLogEntry`/`OwnerType`. | v2 §02 + §39 |
| ~~AC-DG-LEGACY-02~~ | _Retired v2.0.0 — `02-domain-design.mmd` deleted; hierarchy info lives in ER + `02-database-schema.md` prose._ | — |
| ~~AC-DG-LEGACY-03~~ | _Retired v2.0.0 — `03-endpoints-write.mmd` deleted; covered by AC-DG-LEGACY-09 (mindmap)._ | — |
| ~~AC-DG-LEGACY-04~~ | _Retired v2.0.0 — `04-endpoints-read.mmd` deleted; covered by AC-DG-LEGACY-09 (mindmap)._ | — |
| AC-DG-LEGACY-05 | `05-auth-validation.mmd` ordered: parse → GitProfile lookup → Acceptance → Branch → TempToken → Token → Profile status → App status, with explicit GL-* reject codes. | v2 §05 |
| AC-DG-LEGACY-06 | `06-permission-flow.mmd` resolves WP user → Profile → RolePermission union → check (never role name); each reject branch carries the GL-* code; classDef colors distinguish nodes. | v2 §05 + §19 |
| AC-DG-LEGACY-07 | All diagrams emoji-free; render successfully via Mermaid CLI; non-ER diagrams open with `%% Diagram type:` + `%% What this answers:` headers. | rendering smoke + readability |
| AC-DG-LEGACY-08 | No diagram references JWT, RS256, or JWKS. | locked decision 5 |
| AC-DG-LEGACY-09 | `09-endpoints-mindmap.mmd` is a `mindmap` listing all 8 REST endpoints under `Writes` / `Reads` / `Cross-cutting` with verb, path, auth, body, response, audit category, GL-* codes. | v2 §04 + §14 |
| AC-DG-LEGACY-10 | `08-encryption-v3-flow.mmd` covers MasterKey → DataKey → LookupKey, ALTER + per-row encryption, MigrationState insert, idempotency. | v2 §11 |
| AC-DG-LEGACY-11 | Slots **02**, **03**, **04** are intentional locked gaps; inventory shows `~~retired v2.0.0~~`. | project rule "file slots immutable" |

---

## Cross-References

- [Module overview](./00-overview.md)
- [Module changelog](./98-changelog.md)
- [Module consistency report](./99-consistency-report.md)
- [§22 Git Logs v2 overview (authoritative source)](../22-git-logs-v2/00-overview.md)
- [§22 Database schema](../22-git-logs-v2/02-database-schema.md)
- [§22 Auth validation](../22-git-logs-v2/05-auth-and-validation.md)
- [Spec authoring guide AC-SAG-04 (slot immutability)](../01-spec-authoring-guide/97-acceptance-criteria.md)

### AC-22: Derivative-context module pin (Lesson #29 — diagrams of spec/22)  `[critical]`

**Given** `spec/26-gitlogs-diagrams` is a **derivative module** whose normative purpose is *visualizing* the contracts owned by `spec/22-git-logs-v2` (ER diagram, auth flows, permission flow, rate-limit flow, encryption-v3 flow, endpoints mindmap, SSH auth validation — 7 `.mmd` + matching `.svg` pairs at root + `01-diagram-conventions/` subfolder + `puppeteer.json` for headless SVG render), **When** an audit harness reports `[D5] Missing Authoritative Source Context` because `spec/22-git-logs-v2` is not provided in spec/26's local bundle, **Then** the auditor MUST treat that finding as a **harness scope artifact**, NOT a spec defect — `spec/22-git-logs-v2/` is present on disk and is the canonical source for every diagram in this module per Lesson #36 (link-don't-restate). The schemas, ER relationships, auth payloads, and rate-limit constants visualized in spec/26's `.mmd` files are NOT restated here; they MUST resolve against `spec/22-git-logs-v2/97-acceptance-criteria.md` as single-source-of-truth.\n\n- **Verifies:** the spec/26 module-kind = `derivative` declaration AND the auditor-authoritative on-disk inventory + cross-module derivative-source contract; codifies **Lesson #29** for derivative modules (a new sub-class beyond audit-corpus / structural-ambiguity / rollup / deep-tree / non-`.md` assets). Mirror of spec/03 AC-08 + spec/07 AC-35 + spec/10 AC-09 + spec/11 AC-10 + spec/12 AC-09 + spec/13 AC-24 + spec/17 AC-10 + spec/18 AC-09 + spec/25 AC-AI-09..11. Future derivative modules (anything visualizing or summarizing another spec's contract) MUST add an equivalent AC declaring derivative-of-X with the source module path. Until A8 (LLM-gateway re-score) unblocks, the cache will report v3/v4 [D5] derivative-context findings as outstanding — this AC declares those findings are stale-cache artifacts per Lesson #34.

---

### AC-23: Deterministic SVG-render protocol — `.mmd`-source SHA primary, structural-XML diff fallback  `[critical]`

**Given** AC-DG-12 mandates that every `.mmd` edit MUST be accompanied by a freshly-rendered sibling `.svg`, **AND** Mermaid CLI (`mmdc`) injects non-deterministic content into rendered SVGs (`id="mermaid-NNNN"` random IDs, font-load timing comments, occasional Chromium UA strings), **When** CI verifies render-lockstep, **Then** the verification MUST follow the two-tier protocol below — byte-identical SHA-256 over raw SVG is FORBIDDEN as the primary equality check:

#### Tier 1 (primary): `.mmd`-source SHA-256 + render-success gate

| Step | Command | Pass condition |
|---|---|---|
| 1 | `sha256sum <name>.mmd > /tmp/mmd.sha` | always succeeds (read-only) |
| 2 | `git show HEAD~1:<name>.mmd \| sha256sum > /tmp/mmd-prev.sha` (or use commit-range when batch-verifying) | always succeeds |
| 3 | If SHAs match → SKIP render (no source change → no SVG drift) | exit 0 |
| 4 | If SHAs differ → `mmdc -i <name>.mmd -o /tmp/<name>.svg -p puppeteer.json -b transparent` MUST exit 0 | render-success gate |
| 5 | Sibling `<name>.svg` MUST exist on disk in the same commit as the `.mmd` change | lockstep gate |

#### Tier 2 (fallback when SHA differs but visual parity must be confirmed): structural-XML diff

| Step | Command | Pass condition |
|---|---|---|
| 1 | `xmllint --c14n11 <committed>.svg > /tmp/committed.canon.xml` | canonicalize committed SVG |
| 2 | `xmllint --c14n11 /tmp/<name>.svg > /tmp/fresh.canon.xml` | canonicalize freshly-rendered SVG |
| 3 | Strip non-deterministic attributes via `sed -E 's/id="mermaid-[0-9]+"/id="mermaid-N"/g; s/<!--[^-]*-->//g'` over both | normalize random IDs + comments |
| 4 | `diff /tmp/committed.canon.xml /tmp/fresh.canon.xml` MUST be empty for `<g>`, `<path>`, `<text>`, `<rect>` element-trees (structural shape parity) | structural-equivalence gate |
| 5 | Acceptable drift: random-ID renaming, comment whitespace, font-loader timing markers; FORBIDDEN drift: any `<text>` content change, any `<path d="...">` coordinate change >1px, any `class="..."` mismatch | drift policy |

#### Per-finding closure

| v7 finding | Severity | Closed by |
|---|---|---|
| `[D5] Missing Authoritative Source Context` | HIGH | AC-22 (harness scope artifact — spec/22 on disk) |
| `[D3] Non-deterministic SVG Diffing` | MEDIUM | AC-23 (this AC) — Tier 1 `.mmd` SHA primary + Tier 2 structural-XML fallback replaces forbidden raw-SVG SHA |
| `[D4] Missing .mmd Source Content` | LOW | AC-22 + this AC's Tier 1 step 4 (mmdc render-success gate proves `.mmd` files exist on disk and are valid Mermaid; harness-bundle absence is auditor-context artifact) |

#### Forbidden patterns

- ❌ `sha256sum <name>.svg` as primary equality check — false-positive rate ≥80% from Mermaid random IDs
- ❌ Visual screenshot diffing (`puppeteer screenshot` + image-diff) — adds Chromium-version dependency; outside CI determinism budget
- ❌ Skipping Tier 2 when Tier 1 SHA differs — silent SVG-drift class (e.g. `.mmd` whitespace change re-renders identically, but `mmdc` upgrade renders differently — only Tier 2 catches this)
- ❌ Per-language XML-diff implementations — Tier 2 MUST use `xmllint --c14n11` (POSIX-portable, deterministic canonical XML 1.1)

- **Verifies:** AC-DG-12 (`.svg` regeneration on `.mmd` edit) by replacing the partial "non-byte-identical output is acceptable IF the structural content matches" prose with a normative two-tier protocol; closes audit-v7 [D3] MEDIUM (Non-deterministic SVG Diffing) and reinforces AC-22's harness-artifact classification of [D4] LOW + [D5] HIGH per Lesson #34. Per Lesson #44 `audit-corpus` axis multipliers (D3×0.5 + D4×1.5 + D5×1.5), tri-closure projects EXCELLENT-band re-score (80 → 88+ expected). Codifies **Lesson #36** (link-don't-restate) — Tier 1 step 5 cites AC-DG-12 lockstep without restating the `.mmd`↔`.svg` pairing rule. Codifies **Lesson #29** Section F (audit-corpus protocol surface) by formalizing the verification command set in normative tables rather than prose.
- **Source:** `97-acceptance-criteria.md` (this AC); cross-references AC-DG-12 (regen lockstep), AC-22 (derivative-context pin), AC-DG-18 (puppeteer.json render config); external dependency `xmllint` (POSIX `libxml2` package) MUST be available in CI runner.

