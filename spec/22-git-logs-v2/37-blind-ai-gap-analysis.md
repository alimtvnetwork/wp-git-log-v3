# Blind-AI Implementability Gap Analysis — v2 (folder 22)

**Version:** 1.1.0
**Updated:** 2026-04-27
**Question asked:** *"If I hand folder 22 to an AI blindly, how much can it build, and where will it stall?"*

---

## Headline verdict

**Blind-AI implementability score: 76/100 (B)** — confirmed by the deterministic auditor (`.lovable/memory/audit/v2-deterministic/22-git-logs-v2.md`).

| Dimension | Score | Why |
|-----------|------:|-----|
| Implementability | 85 | Inline DDL (`18-schema.sql`), OpenAPI 3.1, 89 fenced code blocks (PHP/SQL/YAML/JSON/bash/bats) |
| Completeness    | 40 | **41 ACs but 0 are Given/When/Then formatted** — auditor counts `ac_count=0` for that reason |
| Alignment       | 100 | Cross-spec links resolve |
| Consistency     | 100 | §99 present + bijection table maintained |
| Clarity         | 100 | waffle/kchar = 0.08 (excellent) |
| Testability     | 10 | Zero GWT blocks → fires gate `G-AC-02` (cap=60) and `G-AC-01`-adjacent |
| Maintainability | 100 | 0 unresolved markers (Phase 39b — both resolved 2026-04-27) |

**An AI given v2 today can build ~76% of the plugin without asking a human.** The 24% gap is enumerated below with file/line targets.

---

## What works (the AI can build it blind)

| Capability | Evidence | Confidence |
|------------|----------|-----------|
| Full SQLite schema + migrations | `18-schema.sql` (359 lines, all DDL inline; 25 `AuditActionType` seeds, 10 `ConfigKv` defaults, 6 `MigrationState` markers) | **High** |
| All 10 REST endpoints (request shape, response ack, error envelope) | `04-rest-api-endpoints.md` + `17-openapi.yaml` (machine-readable, 330 lines) | **High** |
| Auth Lane B / TempToken sub-mode (8-step validation order, error codes) | `05-auth-and-validation.md` §TempToken | **High** |
| Auth Lane B / SSH sub-mode (10-step order, signing string, headers) | `05-auth-and-validation.md` §SSH + `31-ssh-key-auth.md` | **High** |
| Permission matrix (Role × Permission × Screen) | `19-permission-matrix.md` | **High** |
| 37 `GL-*` error codes with HTTP status + caller action | `15-error-codes.md` | **High** |
| Admin UI menu structure + first-run bootstrap | `03-admin-ui.md` | Medium-High (some screens are prose, not wireframes) |
| Retention/pruning, backup/restore, multisite behavior | `22`/`23`/`24` | **High** |
| WP-CLI commands (`wp git-logs *`) | `27-wp-cli-reference.md` | **High** |

| Threat model, observability counters | `30`/`20` | Medium (counters listed; emission points partially prose) |
| Reference CI YAML, BATS + PHPUnit skeletons | `33`/`34`/`35` | **High** (drop-in) |

A reasonable AI starting from §00 can produce: complete SQLite schema, all 10 PHP REST controllers, both auth lanes, the permission gate, error envelope, WP-CLI, retention/backup commands, basic admin screens, and the bats/phpunit harness.

---

## Where the AI WILL stall — concrete gaps with file/line fixes

Each gap below is paired with the **exact file + section to patch** so a human can close it in one PR.

### GAP-V2-01 — ACs are Markdown rows, not Given/When/Then [HIGH]

- **Symptom:** `97-acceptance-criteria.md` has 41 testable items written as table rows (`| AC-01 | … |`). The auditor's `gwt_block_count = 0` triggers gate `G-AC-02` capping testability at 60. An AI builder can read each row but cannot generate a test stub mechanically.
- **Impact on blind build:** The AI will write tests that *paraphrase* each AC, so the test names will drift from the spec wording across runs.
- **Fix target:** `spec/22-git-logs-v2/97-acceptance-criteria.md` — convert each row to:

  ```md
  ### AC-01 — Top-level menu
  - **Given** a fresh activation,
  - **When** an admin loads the plugin admin page,
  - **Then** the top menu MUST render exactly: Profile, Roles, AccessToRoles, GitProfile, Repo, RepoVersion, History, Action.
  ```
- **Effort:** ~60 min (helper exists: `python3 linter-scripts/generate-gwt-acceptance.py`).
- **Score impact:** lifts testability 10 → ~85, raises overall to ~81.

### GAP-V2-02 — TypeScript enums never inlined [MEDIUM]

- **Symptom:** v2 ships PHP, SQL, YAML, JSON, bash code blocks — but `has_ts_enums = false`. Anyone implementing a JS/TS admin SPA against v2 (e.g. block editor, headless dashboard) has to retype every enum from prose.
- **Fix target:** `spec/22-git-logs-v2/01-glossary-and-enums.md` — append a `## TypeScript Mirror` section with a single fenced `ts` block containing every enum (`UserStatus`, `AppStatus`, `Acceptance`, `LogSeverity`, `Provider`, `OwnerType`, `ActionType`, `AuditOutcome`).
- **Effort:** ~15 min.
- **Score impact:** removes one finding from §99; nudges implementability +2.

### GAP-V2-03 — Streaming wire format is behavioral, not byte-level [MEDIUM]

- **Symptom:** AC-12 says "`/append-log` supports streaming ingestion (`Transfer-Encoding: chunked`)". A blind AI will pick a framing format (NDJSON? raw bytes? CRLF-delimited?) and the client (`spec/28-universal-ci-cli/06-log-shipping-contract.md`) will pick a different one.
- **Fix target:** `spec/22-git-logs-v2/04-rest-api-endpoints.md` §1 (`POST /append-log`) — add subsection "Streaming wire format" pinning:
  - `Content-Type: application/x-ndjson`
  - First chunk = identity + `"StreamHeader":true`
  - Final chunk = `"StreamFooter":true,"HasError":<bool>`
  - `X-GL-Stream: 1` request header
- **Cross-impact:** unblocks `28-universal-ci-cli/06` AC-28-06 from being a *proposal*.
- **Effort:** ~15 min.

### GAP-V2-04 — Ack envelope lacks `PreviousHasError` [MEDIUM]

- **Symptom:** `04-rest-api-endpoints.md` shows the standard ack envelope as `{Status, Message, TraceId, Retrieval}`. There is no field telling the client whether the previous run for this `(Repo, Branch, Pipeline)` had `HasError=1`. Without it, no client can know whether to send `PUT /fixed-log` automatically (per AC-13).
- **Fix target:** `spec/22-git-logs-v2/04-rest-api-endpoints.md` §Common ack + `17-openapi.yaml` `Ack` schema — add:
  ```yaml
  PreviousHasError:
    type: boolean
    description: True iff prior /append-log on this (Repo,Branch,Pipeline) had HasError=1
  ```
- **Effort:** ~10 min (schema only; server-side: 1 SQL lookup).

### GAP-V2-05 — App identity fields unfinished [HIGH — user-blocked]

- **Symptom:** `99-consistency-report.md` open item #1: "App identity (§07) — still awaiting user confirmation on whether to add `Environment`, `Platform`, or `OwnerEmail`."
- **Why it blocks AI:** §07 currently lists `AppName, AppSlug, Description, ProfileId, AppStatusId`. An AI building an admin "Create App" form will guess what other fields exist; whatever it picks will be wrong on the next iteration.
- **Fix target:** `spec/22-git-logs-v2/07-app-entity.md` §Schema — once the user picks Environment/Platform/OwnerEmail (or "none"), append the chosen columns + add a row to `18-schema.sql`'s `App` CREATE TABLE.
- **Effort:** 5 min once decided. **Decision required.**

### GAP-V2-06 — Locked vacant slots §09–§13 are easy to misread [LOW — RESOLVED 2026-04-28, Phase P6]

- **Original symptom:** Five slot numbers (`09-seed-data`, `10-rate-limit-and-payload`, `11-encryption-deferred-plan`, `12-wp-plugin-scaffold`, `13-v1-vs-v2-mapping`) are referenced from `00-overview.md` as "**Locked vacant slot**" with redirects. A blind AI top-down reader will try to follow the italicised entry, find no file, and emit broken-link warnings.
- **Original fix recipe (REJECTED):** Author 5 stub `.md` files (one per slot) carrying a "Slot intentionally vacant" blockquote.
- **Why rejected — supersedes original recipe:**
  - **Conflicts with Core memory rule:** *"File slots are immutable once shipped — never reuse a number; if content moves, rename the slot and add a §99 audit row."* The five slots have already been **retired** (content redistributed to §05/§08/§18/§30/§31/§37/§38). Authoring stub files would re-occupy the slot numbers and re-open them for accidental future authoring — the exact failure mode the immutability rule prevents.
  - **Conflicts with `check-tree-health.cjs --strict`:** Stub files with only a one-line blockquote score 0 on the rubric (no §97/§99 banner, no inventory entry, no AC bindings). A 5-stub authoring would drop tree health from **168/168 strict-pass → 158/168** — a regression worse than the cosmetic ambiguity it tries to fix.
  - **§00 inventory already disambiguates:** Lines 77–81 render the slot rows in *italic* with the explicit `**Locked vacant slot**` label and a content-redirect pointer. No real link is followed; the rows are advisory anchors, not navigation targets.
- **Resolution:** Locked-vacant slots §09–§13 remain **file-absent by design**. The §00 inventory rendering is the single source of truth. Future contributors MUST NOT create `09-*.md … 13-*.md` files; the next available slot for new content is §40+. To eliminate the residual blind-AI ambiguity, AC-22-LV1 is added in §97 (see Phase P6 row in §98) declaring the prohibition machine-checkable.
- **Outcome:** Cosmetic ambiguity accepted as a deliberate trade-off. Locked-vacant integrity > blind-AI link-follow comfort.

### GAP-V2-07 — Two raw TODO/FIXME markers in body [LOW — RESOLVED 2026-04-27, Phase 39b]

- **Original symptom:** `30-threat-model.md:66` ("TODO: add seed") and `32-cli-test-plan.md:202` ("TODO comment linking the GitHub issue"). Auditor counted `todo_density = 2`.
- **Resolution:**
  - `30-threat-model.md:66` — replaced parenthetical "(TODO: add seed)" with explicit reference "(seed id 25, shipped in v2.8.0 — see `16-seed-data.md` and `18-schema.sql:409`)". Confirmed `ConfigChange` seed already lives in `18-schema.sql:409`; backfilled the corresponding row into `16-seed-data.md` AuditActionType table (id 25).
  - `32-cli-test-plan.md:202` — replaced "TODO comment linking the GitHub issue" with the explicit `# QUARANTINE(<issue-ref>): <reason>` comment-format contract, enforceable by `linter-scripts/check-quarantine-tracking.py`.
- **Outcome:** `todo_density = 0`. Maintainability dimension lifted from 90 → 100.

### GAP-V2-08 — `16` slot collision [COSMETIC]

- **Symptom:** Two files use the prefix `16-`: `16-seed-data.md` AND `16-test-plan.md`. The user-preferences file (`.lovable/user-preferences`) explicitly says file slots are immutable; this duplication slipped through.
- **Fix target:** Rename `16-seed-data.md` → `37-seed-data.md` (already implied by `99-consistency-report.md` §95 "slot moved in v2.8.6"). Verify no other file links to the old path: `rg -n "16-seed-data" spec/22-git-logs-v2/`.
- **Effort:** ~5 min.

### GAP-V2-09 — No outbound CI client contract [MEDIUM — now closed]

- **Symptom:** v2 specifies the *server* but not the matching *client*. Every team integrating CI/CD has to invent its own poster.
- **Fix target:** **Already addressed** — `spec/28-universal-ci-cli/` now provides the canonical client contract (28 ACs, OpenAPI 3.1, JSON Schema). Cross-link from `spec/22-git-logs-v2/00-overview.md` Document Inventory.
- **Effort:** ~2 min (one new row).

### GAP-V2-10 — Rate limit + payload caps are not in §04 [LOW]

- **Symptom:** `10-rate-limit-and-payload` is a vacant slot; values live as `ConfigKv` defaults inside `18-schema.sql`. AI implementing endpoint validation order won't know to enforce `MaxPushPayloadBytes` *before* parse (per AC-27).
- **Fix target:** `spec/22-git-logs-v2/04-rest-api-endpoints.md` §1 — add a "Pre-parse caps" subsection naming the four `ConfigKv` keys and the order they are checked.
- **Effort:** ~10 min.

---

## Side-by-side v1 ↔ v2 feature delta (full table)

Legend: ✅ kept, ✏ changed shape, ❌ removed, ➕ new in v2.

| Capability | v1 (folder 21) | v2 (folder 22) | Delta |
|------------|----------------|----------------|-------|
| Database engine | MySQL via `wpdb` | SQLite single file | ✏ |
| Table prefix | `{wp_prefix}gitlogs_` | _(none)_ | ✏ |
| Naming | snake_case | PascalCase | ✏ |
| Primary keys | `id` autoincrement | `{Table}Id` autoincrement | ✏ |
| Plugin JWT (RS256) | yes, with JWKS endpoint | **dropped** | ❌ |
| Refresh tokens | 7d rotating | n/a | ❌ |
| TempToken | (no concept) | per-Profile, regenerable | ➕ |
| SSH-key deploy auth | n/a | optional sub-mode (preferred from v2.7.0) | ➕ |
| Allowlist regex (`repo`, `repo-vN`) | yes | replaced by `Acceptance` enum | ✏ |
| Endpoint count | ~5 (`/logs/push`, `/logs`, `/logs/{id}`, …) | 10 logical → 8 HTTP paths | ✏ |
| Endpoint namespace | `git-logs/v1` | `git-logs/v2` | ✏ |
| Streaming `/append-log` | (no) | `Transfer-Encoding: chunked` | ➕ |
| Audit | single table | **3 tables** (AuditTrail / History / Action) | ✏ |
| Rate limit | 60/min/repo (transients) | per-Profile token bucket (`RatePerMinPerProfile`) | ✏ |
| Roles | WP roles | plugin SQLite Admin/Editor + Permission union | ✏ |
| Authorization check | role-name compare | **Permission-only** (never role name) | ✏ |
| App entity | (none) | `App` + polymorphic `AppLink` | ➕ |
| App lifecycle | n/a | `AppStatus` enum (Active/Disabled/Archived) | ➕ |
| First-run bootstrap | n/a | `Profile` empty + `manage_options` → reveal | ➕ |
| Retention | indefinite | `wp git-logs prune` w/ 7d floor + 2-phase delete | ➕ |
| Backup/restore | n/a | SQLite Online Backup + manifest + maintenance gate | ➕ |
| Multisite | unspecified | per-site DB always; no shared store | ➕ |
| Headless auth notes | n/a | `25-headless-auth-notes.md` | ➕ |
| Threat model | n/a | STRIDE pass §30 | ➕ |
| OpenAPI | n/a | 3.1 spec covering all 10 endpoints | ➕ |
| WP-CLI | n/a | `wp git-logs *` catalog (§27) | ➕ |
| Observability | n/a | Site Health card + Prometheus `/metrics` (§20) | ➕ |

| Test harness | n/a | BATS + PHPUnit skeletons (§33–§34) | ➕ |
| Reference CI YAML | n/a | §35 | ➕ |

**Net:** v2 is roughly 3× the surface of v1, with every shared feature reshaped. A side-by-side merge would have created chaos; the parallel-folder strategy was correct.

---

## Concrete fix list to reach 100/100 blind-implementability

| Order | Gap | File | Effort | Score gain |
|------:|-----|------|-------:|-----------:|
| 1 | GAP-V2-01 (ACs → GWT) | `97-acceptance-criteria.md` | 60m | +5 |
| 2 | GAP-V2-05 (App identity decision) | `07-app-entity.md` + `18-schema.sql` | 5m | +4 |
| 3 | GAP-V2-04 (`PreviousHasError` in ack) | `04-rest-api-endpoints.md` + `17-openapi.yaml` | 10m | +3 |
| 4 | GAP-V2-03 (streaming wire format) | `04-rest-api-endpoints.md` §1 | 15m | +3 |
| 5 | GAP-V2-02 (TS enum mirror) | `01-glossary-and-enums.md` | 15m | +2 |
| 6 | ~~GAP-V2-06 (5 stub files for §09–§13)~~ ✅ Phase P6 2026-04-28 — REJECTED, locked-vacant precedent retained; AC-22-LV1 prohibition added | `spec/22-git-logs-v2/97-acceptance-criteria.md` | n/a | n/a |
| 7 | GAP-V2-10 (rate-limit caps in §04) | `04-rest-api-endpoints.md` | 10m | +2 |
| 8 | GAP-V2-08 (rename `16-seed-data` → `37`) | filesystem + grep cross-refs | 5m | +1 |
| 9 | ~~GAP-V2-07 (resolve 2 TODO markers)~~ ✅ Phase 39b 2026-04-27 | `30-threat-model.md`, `32-cli-test-plan.md`, `16-seed-data.md` | 10m | +1 |
| 10 | GAP-V2-09 (link client CLI from §00) | `00-overview.md` | 2m | +1 |

**Total effort:** ~2h 22m. **Projected score:** 100/100 (A+).

---

## Cross-references

- v2 server canonical entry: [`00-overview.md`](./00-overview.md)
- Why v1 is archived: [`36-why-v1-archived.md`](./36-why-v1-archived.md)
- Deterministic audit: [`.lovable/memory/audit/v2-deterministic/22-git-logs-v2.md`](../../.lovable/memory/audit/v2-deterministic/22-git-logs-v2.md)
- Fix checklist (auto-generated): [`.lovable/memory/audit/v2-deterministic/fix-checklists/22-git-logs-v2.md`](../../.lovable/memory/audit/v2-deterministic/fix-checklists/22-git-logs-v2.md)
- Companion CI client: [`spec/28-universal-ci-cli/00-overview.md`](../28-universal-ci-cli/00-overview.md)
