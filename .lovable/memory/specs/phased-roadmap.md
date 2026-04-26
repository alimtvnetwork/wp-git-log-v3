---
name: Phased Spec Roadmap
description: Authoritative phased task list for spec/22-git-logs-v2. On `next`, take top pending phase and complete it. Spec-only, no implementation.
type: feature
---

# Phased Spec Roadmap — Git Logs v2

**Rule:** On each `next`, take the **top pending phase** (lowest number, status = pending) and complete every task in it. Update the matching §98 changelog row, §99 consistency, and bump version. Mark phase ✅ here when done.

---

## ✅ Phase 0 — Q1 IsOrganization (DONE, v3.8.1)
## ✅ Phase 1 — Q2 PipelineAction + SystemEvent (DONE, v3.8.2)
## ✅ Phase 2 — Split-DB Schema Surgery (DONE, v3.8.3 / schema v2.9.0)

Landed in v3.8.3:
- §18: dropped `LogEntry` + `ErrorLogEntry` (and their indexes), added `ShaRegistry` table + 2 indexes, added 3 ConfigKv defaults (`ShaLogsRoot`, `MaxOpenShaDbHandles`, `ShaDbIdleCloseSec`), bumped PluginVersion 2.8.9→2.9.0, appended MigrationState 2.9.0.
- §02: banner 3.8.0→3.8.3, engine note rewritten, ConfigKv sub-table for new keys.
- §01: banner 3.8.1→3.8.3, ShaRegistry def refined, NEW glossary rows `PerShaDb` + `ShaLogsRoot`.
- §98 changelog v3.8.3 row added; §99 v3.8.3 audit table + Q3 status flip in v3.8.0 audit. Schema validated in-memory: 29 tables, no LogEntry/ErrorLogEntry, ShaRegistry present, 13 ConfigKv rows, 9 MigrationState markers.

---

## ✅ Phase 3 — Split-DB Error Codes & Cross-Section Updates (DONE, v3.8.4)

Landed in v3.8.4:
- §15: 4 new `GL-SHA-DB-*` codes (`OPEN-FAILED` 503, `CREATE-FAILED` 500, `CHECKSUM-MISMATCH` 500, `QUOTA-EXCEEDED` 507) under new section *Per-SHA log storage*. Banner v2.8.7→v2.9.0.
- §22: rewritten — eligibility on `ShaRegistry.LastSeenAt`, rename→delete-row→unlink crash-safety with `*.pruning` recovery, exit code 4 for FS errors, empty-shard cleanup. Banner v2.5.0→v2.9.0.
- §23: backup is now a directory tree; manifest gains `ShaFiles[]` with `{PipelineId,Sha,DbFilePath,RowCount,FileSizeBytes,Sha256}` + `ShaFileTotal`; restore is all-or-nothing with `.bak` rollback; new pre-v2.9.0 cross-version migration row. Banner v2.5.0→v2.9.0.
- §29: lifecycle table gained "Per-SHA tree" column; Wipe deletes `<ShaLogsRoot>/` first, then root DB, then `rmdir` parent. Banner v2.5.0→v2.9.0.

## ✅ Phase 4 — Split-DB Doc Closure (DONE, v3.8.5)

Landed in v3.8.5:
- §00: §39 inventory row refreshed for v2.9.0 path layout (`<dataDir>/<ShaLogsRoot>/<Sha[0:2]>/<Sha>.db`) + cross-refs to §15/§22/§23/§29. Banner v3.8.0→v3.8.5.
- §97: AC-49..AC-53 promoted from draft to **Active (v2.9.0)**, rewritten to match shipped DDL (`(PipelineId, Sha)` key, real ConfigKv defaults 32/120, GL-SHA-DB-* code refs, manifest `ShaFiles[]`, Wipe per-SHA-tree-first). Banner v3.8.2→v3.8.5.
- Root `spec/spec-index.md`: 9 version cells refreshed for files touched in Phases 2–4.
- `26-gitlogs-diagrams/01-er-diagram.mmd`: re-rendered for split-DB boundary — top annotation, stale edges removed, `ShaRegistry` entity rewritten to v2.9.0 columns.
- §98 v3.8.5 row + §99 Phase 4 audit table + Health Score updated.

---

## ✅ Phase 5 — SSH-Key Lane B: Schema & Errors (DONE, v3.8.6)

Landed in v3.8.6 / schema v2.9.1:
- §18: `CREATE TABLE SshKey` (11 cols) + 2 indexes; `CREATE TABLE SshNonce` + 1 index; 2 new ConfigKv (`SshAuthMode='optional'`, `SshNonceJanitorBatch='100'`); PluginVersion 2.9.0→2.9.1; MigrationState 2.9.1 appended. Banner v2.9.0→v2.9.1.
- §01: 3 new glossary rows (`SshKey`, `Ed25519Signature`, `SshNonce`). Banner v3.8.3→v3.8.6.
- §02: banner v3.8.3→v3.8.6 (existing SshKey/SshNonce sub-sections now backed by canonical DDL).
- §15: banner v2.9.0→v2.9.1 — 9 SSH lane codes already present, now backed by canonical schema.
- §31: banner v2.7.0→v2.9.1 with canonical-DDL note.
- AuditActionType seeds verified: `SshKeyRegister`(22), `SshKeyRevoke`(23), `SshKeyRotate`(24) already present.
- In-memory SQLite validation: 31 tables, 15 ConfigKv, 10 MigrationState markers, 3 SshKey* AuditActionTypes.

## ✅ Phase 6 — SSH-Key Lane B: Flow & Threat Doc (DONE, v3.8.7)

Landed in v3.8.7:
- §05: banner v2.1.0→v2.9.1. SSH lane block (10-step validation order) confirmed authoritative; cross-refs to §31/§15/§18 verified.
- §28: banner v2.7.0→v2.9.1. Drop-in `git-logs-ssh.yml` workflow (namespace `git-logs@v2`, four headers, canonical signing string, deploy-key rotation, key-wipe `if: always()`) confirmed authoritative.
- §30: banner v2.7.0→v2.9.1. Added 4 STRIDE Spoofing rows (S5 replay, S6 key theft, S7 sig stripping/lane downgrade, S8 lane-mode forgery) — closes the "S5–S8 SSH-lane additions" forward reference in summary.

---

## ✅ Phase 7 — AC Quality Pass (DONE, v3.8.8)

Landed in v3.8.8:
- §97 banner v3.8.5→v3.8.8. Every AC (AC-01..AC-59) rewritten from one-line table rows into Given/When/Then stanzas + `Verifies:` cross-refs + `[active]`/`[draft]`/`[deprecated]` status badges. Reorganized into 9 thematic sections (A UI · B Domain · C Auth/Lane · D Endpoints · E Logging/Migrations · F Audit · G Schema/Diagrams · H Per-SHA Split-DB · I SSH-Key Lane B).
- 7 new ACs added in Section I, all `[active]`: AC-60 SshKey registration shape; AC-61 SshNonce replay defense (skew + per-key uniqueness + janitor); AC-62 lane gating via `SshAuthMode` + mixed-lane `GL-SSH-LANE-CONFLICT`; AC-63 signature stripping defense (header-completeness ordered first + mandatory HTTPS); AC-64 SshKey rotation flow (`IsActive=0` no-cache reject + dual SystemEvent + dual AuditTrail); AC-65 deploy-key one-Repo blast radius (FK CASCADE + `LastUsedAt` anomaly + rate cap); AC-66 canonical signing string + `git-logs@v2` namespace + `-H sha512`.
- AC-38 amended to list SSH AuditActionType seeds (22/23/24). AC count 59 → 66.

## ✅ Phase 8 — API Streaming Spec (DONE, v3.8.9)

Landed in v3.8.9:
- §04 banner v2.8.3 → v2.9.2. Added new top-level §11 NDJSON Streaming Retrieval (v2.9.2) with 9 sub-sections covering rationale, opt-in via `Accept: application/x-ndjson`, 5-frame schema (`Header`/`Log`/`ErrorLog`/`Progress`/`End` + optional mid-stream `Error`), ordering & atomicity, 4 new ConfigKv keys (doc-only), resume via `?after-seq=N` + `?stream-id=<uuid>`, endpoint applicability matrix (#5–#10 ✅; #1–#4 ❌), wire example, cross-refs.
- 2 new error codes introduced doc-side: `GL-NDJSON-CLIENT-DISCONNECT` (499 informational), `GL-NDJSON-CURSOR-LOST` (500).
- **Streaming follow-ups deferred:** §18 ConfigKv seeding for 4 `Ndjson*` keys, §15 entries for 2 `GL-NDJSON-*` codes, §17 OpenAPI `application/x-ndjson` content variants for endpoints #5–#10, §97 ACs for streaming behavior. Tracked under "Streaming follow-ups" in §99 Phase 8 audit.

## ✅ Phase 9 — Pipeline PreviousHasError Flag (DONE, v3.8.10 / schema v2.9.2)

Landed in v3.8.10 / schema v2.9.2:
- §18: `Pipeline` table gained `PreviousHasError INTEGER NOT NULL DEFAULT 0 CHECK (PreviousHasError IN (0,1))` immediately after `HasError`; `HasError` itself gained explicit `CHECK (HasError IN (0,1))`. Inline back-fill rule (`UPDATE Pipeline SET PreviousHasError = HasError;` on v2.9.1→v2.9.2 upgrade) and write rule (single-`UPDATE` atomicity, no read-modify-write). PluginVersion 2.9.1→2.9.2; MigrationState 2.9.2 appended (11 markers).
- §02: banner v3.8.6→v3.8.10. Pipeline doc gains `PreviousHasError` row + state-transition labels (`first-failure`/`still-failing`/`just-recovered`/`still-green`).
- §01: banner v3.8.6→v3.8.10. Bare `Pipeline` glossary row split into 3 — `Pipeline`, `HasError`, `PreviousHasError`.
- **Phase 9 follow-ups deferred:** §97 ACs for `PreviousHasError` (state-transition matrix, back-fill correctness, single-statement write atomicity), §03 admin UI rendering of the four state labels, §04 NDJSON `Header` frame label exposure.

## ✅ Phase 10 — Diagram Render Pass (DONE, v2.1.0)

Landed in folder 26 v2.1.0:
- Rendered all 6 active `.mmd` sources to companion `.svg` artifacts via `@mermaid-js/mermaid-cli` v11+. Source `.mmd` unchanged.
- §00/§98/§99 banners + spec-index refreshed.

## ✅ Phase 11 — Streaming Follow-ups Pickup (DONE, v3.8.11 / schema v2.9.3)

Absorbed all Phase 8 streaming deferrals + AC coverage:
- §18: 4 NDJSON ConfigKv seeds (`NdjsonProgressEveryRows=10000`, `NdjsonProgressEveryMs=2000`, `NdjsonMaxRowsPerStream=1000000`, `NdjsonMaxFrameBytes=262144`); PluginVersion 2.9.2→2.9.3; MigrationState 2.9.3 appended (12 markers).
- §15: `GL-NDJSON-CLIENT-DISCONNECT` (499) + `GL-NDJSON-CURSOR-LOST` (500) registered.
- §17: `info.version` 2.8.2→2.9.3 (absorbs Phases 4/5/9/11). `ErrorCode` enum gained 4 `GL-SHA-DB-*` + 2 `GL-NDJSON-*` codes. 7 new `Ndjson*` schemas + `NdjsonStream` reusable response. All 4 GET paths carry `application/x-ndjson` content variant + `after-seq`/`stream-id` query params.
- §97: 6 new ACs (AC-67..AC-72) — opt-in, frame ordering, resume, disconnect, frame cap, Progress cadence. AC count 66→72.
- Validation: `pyyaml.safe_load` clean; in-memory SQLite confirms 19 ConfigKv rows, 12 MigrationState markers.

## ✅ Phase 12 — Phase 9 Follow-ups (DONE, v3.8.12 / OpenAPI v2.9.4)

Closed the unblocked subset of Phase 9 deferrals (§03 admin UI is consumer-side, intentionally out-of-scope for this spec-only project):
- §04 §11.3.1: `Header` frame example + bullet documents OPTIONAL `StateTransition` (4-value enum, single-pipeline-scope only). Banner v2.9.2→v2.9.3.
- §17: `info.version` 2.9.3→2.9.4. `NdjsonHeaderFrame` gained optional `StateTransition` enum property (NOT in `required`).
- §97: 3 new ACs (AC-73 label matrix, AC-74 NDJSON Header exposure, AC-75 back-fill + write atomicity). AC count 72→75.
- Validation: `pyyaml.safe_load` clean; `StateTransition` confirmed in `NdjsonHeaderFrame.properties` with all 4 enum values, confirmed absent from `required`.

## ✅ Phase 13 — Deepen Scaffolded ACs in §22 §97 (DONE, v3.8.13)

Closed §22-scope subset of `mem://specs/full-tree-audit-v4.md` deepening backlog. Doc-only, no schema/DDL/OpenAPI churn:
- §97: 8 high-traffic one-liner ACs deepened from ~200-260 chars to 1400-2200 chars each (5–10×): AC-02 (Profile no-password rule), AC-03 (migration semver ordering + 12-marker baseline), AC-12 (streaming ingest incremental caps), AC-14 (AckResponse Retrieval URLs), AC-17 (App columns + forbidden Phase B1 fields), AC-18 (AppLink XOR + CASCADE), AC-22 (§26 6-file Mermaid manifest), AC-30 (ErrorEnvelope shape + RequestId mirroring + NDJSON error split). AC count unchanged at 75 (verified sequential AC-01..AC-75).
- 4 simpler ACs (AC-04/AC-05/AC-25/AC-34) intentionally left lean.

---

## ✅ Phase 14 — Deepen Scaffolded ACs in §17 §97 (DONE, v1.1.0)

Closed §17-scope subset of the deepening backlog. Doc-only, no schema/DDL churn:
- §17 §97: 4 short ACs (AC-01..AC-04) deepened from ~209-260 chars to 1941-2254 chars each (8–10×): AC-01 module-entry-point structural contract (6 rules), AC-02 cross-link contract (6 rules incl. slot-immutability `../16-...` rule), AC-03 naming-regex contract with positive/negative examples + slot-collision precedent, AC-04 consistency-report freshness contract (7 rules incl. measured-not-narrated rule + version-≥-overview lockstep).
- AC-05 already deep (1803 chars) — left as-is. AC count unchanged at 5.
- Banner v1.0.0 → v1.1.0; lockstep §98 v1.1.0 + §99 v3.6.0 + spec-index updated.
- §07 design-system NOT touched in Phase 14 — it uses table-style AC-001..AC-NNN format (different surgery needed); flagged as candidate Phase 15.

---

## ✅ Phase 15a — Convert §07 §97 Theme & Variables to GWT (DONE, v3.3.0)

First slice of the §07 structural conversion. AC IDs preserved across format change:
- §07 §97 banner v3.2.0 → v3.3.0; AC count unchanged at 34 (AC-001..AC-034 sequential, verified — no IDs added/removed/renamed).
- **6 ACs converted (AC-001..AC-006 Theme & Variables)** from one-row table format (~80 chars each) to full GWT subsections (994-4231 chars each, 12-50× depth) with concrete contracts + cross-refs to `01-design-principles.md`, `02-theme-variable-architecture.md`, `src/index.css`, `tailwind.config.ts`, theme provider, WCAG 2.1 §1.4.3/§1.4.11.
- Added top-of-file **Format note** documenting the mid-conversion state (some sections GWT, others still table) so consumers and future maintainers aren't confused — IDs are stable across formats so ID-scraping tooling continues to work unchanged.
- Lockstep §98 v1.0.0 → v1.1.0 + §99 v3.2.0 → v3.3.0 + spec-index updated.

---

## ✅ Phase 15b — Convert §07 §97 Typography to GWT (DONE, v3.4.0)

Second slice of the §07 structural conversion. AC IDs preserved:
- §07 §97 banner v3.3.0 → v3.4.0; AC count unchanged at 34 (verified).
- **5 ACs converted (AC-007..AC-011 Typography)** from one-row table format (~70 chars each) to full GWT subsections (1209-4005 chars each, **17-57× depth**) with concrete contracts + cross-refs to `03-typography.md`, `index.html` font-loading, `tailwind.config.ts` font registration, `02-theme-variable-architecture.md`, `07-code-blocks.md`, `12-page-creation-rules.md`, WCAG 2.1 §1.3.1/§2.4.6.
- AC-007 Ubuntu headings (font stack, Google Fonts `display=swap`, `tailwind.config.ts` `fontFamily.heading`); AC-008 Poppins body (weights 300-700, default `fontFamily.sans` override); AC-009 Ubuntu Mono / JetBrains Mono code (full mono stack, ligatures-off-by-default with `format:ligatures` opt-in); AC-010 gradient H1/H2 (4-property cross-browser contract, token-only stops, accessibility decoupling); AC-011 no-skipped-levels (exactly-one-`<h1>`, monotonic descent, `level`/`as` polymorphic prop requirement).
- Format note bumped to reflect 11/34 ACs now GWT.
- Lockstep §98 v1.1.0 → v1.2.0 + §99 v3.3.0 → v3.4.0 + spec-index updated.

---

## ✅ Phase 15c — Convert §07 §97 Motion & Transitions to GWT (DONE, v3.5.0)

Third slice of the §07 structural conversion. AC IDs preserved:
- §07 §97 banner v3.4.0 → v3.5.0; AC count unchanged at 34 (verified).
- **5 ACs converted (AC-012..AC-016 Motion & Transitions)** from one-row table format (~70 chars each) to full GWT subsections (1703-3816 chars each, **24-54× depth**) with concrete contracts + cross-refs to `06-motion-transitions.md`, `09-button-system.md`, `tailwind.config.ts`, `src/index.css`, `package.json` dependency audit, WCAG 2.1 §2.3.3, MDN `prefers-reduced-motion`.
- AC-012 ≤300ms hover (fixed timing vocabulary {150/200/300ms}, `cubic-bezier(0.4,0,0.2,1)` mandate, symmetric in/out); AC-013 no-JS-animation (exhaustive forbidden list — framer-motion/gsap/lottie/etc — narrow exception allowlist); AC-014 `prefers-reduced-motion` (exact global override with `0.01ms` not `0`, per-component opt-in pattern, scroll/parallax/auto-play disable); AC-015 link underline sweep (`::after` pseudo-element with `right:0` anchor, `position: relative` parent, no-`text-decoration` mixing, focus-visible instant); AC-016 CTA slide text (two-stacked-spans `overflow:hidden` with `translateY`, vertical-only direction, `aria-hidden` on duplicate).
- Format note bumped to reflect 16/34 ACs now GWT.
- Lockstep §98 v1.2.0 → v1.3.0 + §99 v3.4.0 → v3.5.0 + spec-index updated.

---

## ✅ Phase 15d — Convert §07 §97 Code Blocks to GWT (DONE, v3.6.0)

Fourth slice of the §07 structural conversion. AC IDs preserved:
- §07 §97 banner v3.5.0 → v3.6.0; AC count unchanged at 34 (verified).
- **9 ACs converted (AC-017..AC-025 Code Blocks)** from one-row table format (~80 chars each) to full GWT subsections (2100-4200 chars each, **26-52× depth**) with concrete contracts + cross-refs to `07-code-blocks.md`, `02-theme-variable-architecture.md`, `src/index.css` (lines 264–605), `src/components/markdown/codeBlockBuilder.ts`, AC-001/AC-012/AC-014.
- AC-017 fixed-dark-bg contract (static HSL, exception to token-only rule); AC-018 language badge (7px dot + 10-language color mapping, `--lang-accent` injection); AC-019 font-size controls (12-32px bounds, `localStorage` persistence, 150ms animate); AC-020 line pin (React state toggle, 3 visual markers, hover-vs-pin precedence); AC-021 shift-click range (bidirectional, idempotent, selection bar trigger); AC-022 fullscreen (2rem inset, backdrop overlay, `z-index` stack, scale animation); AC-023 Escape exit (immediate, restore all state, cleanup listeners, `beforeunload` safeguard); AC-024 copy button (Clipboard API + fallback, 2s success state, `aria-live`); AC-025 tree prefixes (📁/📄 emoji, guide characters, "STRUCTURE" label).
- Format note bumped to reflect 25/34 ACs now GWT.
- Lockstep §98 v1.3.0 → v1.4.0 + §99 v3.5.0 → v3.6.0 + spec-index updated.

---

## ✅ Phase 15e — Convert §07 §97 Navigation + Page Consistency to GWT (DONE, v3.7.0) — §07 §97 CONVERSION COMPLETE

Final slice of the §07 structural conversion. AC IDs preserved:
- §07 §97 banner v3.6.0 → v3.7.0; AC count unchanged at 34 (verified: AC-001..AC-034 sequential, zero gaps).
- **9 ACs converted (AC-026..AC-030 Navigation + AC-031..AC-034 Page Consistency)** from one-row table format (~70 chars each) to full GWT subsections (1900-3500 chars each, **27-50× depth**) with concrete contracts + cross-refs to `08-header-navigation.md`, `10-sidebar-system.md`, `11-section-patterns.md`, `12-page-creation-rules.md`, `tailwind.config.ts`, `index.html`, `src/components/ui/sidebar.tsx`, AC-001/AC-007/AC-008/AC-009/AC-010/AC-012/AC-014/AC-026/AC-029, WCAG 2.1 §1.3.1/§2.4.7/§2.5.5.
- AC-026 header icon scale (1.05 hover + 0.95 active, no-bounce); AC-027 menu gradient underline (`::after` + `transform-origin` flip, heading-gradient tokens); AC-028 dropdown primary-tinted hover (0.08 alpha + primary text); AC-029 mobile Sheet (slide from left at 200ms, backdrop no-blur, auto-close on file-select/escape/outside-click/breakpoint-grow); AC-030 Ctrl+B toggle (global window listener, input-guard, localStorage persistence, mobile opens Sheet); AC-031 section pattern composition (Header → Hero → N×Section → CTA → Footer, no ad-hoc layouts); AC-032 font registry enforcement (only Ubuntu/Poppins/Mono, no per-page loading, no inline `style="font-family"`); AC-033 state language (REQUIRED hover+active+focus-visible+disabled, `--ring` token NEVER `--primary`, 2px min); AC-034 responsive breakpoints (md/lg, mobile-first, 44px touch targets, no horizontal scroll).
- **Format note bumped to reflect 34/34 ACs now GWT — conversion COMPLETE.** Zero table rows remain in §07 §97.
- Lockstep §98 v1.4.0 → v1.5.0 + §99 v3.6.0 → v3.7.0 + spec-index updated.

---

## ✅ Phase 16a — Deepen §13 generic-cli §97 with module-specific GWT ACs (DONE, v2.0.0)

First module deepening pass after §07 §97 conversion completed:
- §13 §97 banner v1.0.0 → **v2.0.0** (major bump because AC count tripled and the new ACs validate a different surface — the CLI implementation — distinct from the original 5 which validate the spec module structure).
- **15 new GWT ACs added (AC-06..AC-20)**, AC-01..AC-05 preserved verbatim.
- AC-06 single-switch subcommand dispatch (no cobra/urfave); AC-07 kebab-case per-command flagsets + flag-name constants; AC-08 three-layer config precedence (defaults → JSON file → flags) + flat-JSON-only contract; AC-09 pluggable `--format` (terminal/json/csv/markdown) + TTY-detect color suppression + RFC-4180 CSV; AC-10 fixed five-value exit code contract (0 success, 1 generic, 2 misuse, 3 config, 4 batch partial) + stderr discipline; AC-11 50-line function / 400-line file / camelCase-PascalCase / no-magic-strings code style; AC-12 compile-time `//go:embed` help + interception before flag parse; AC-13 `pkg/dateformat/` three layouts (display `2006-01-02 15:04:05 MST`, filename `2006-01-02-150405`, ISO8601 RFC-3339); AC-14 `pkg/constants/` category split (flags/commands/paths/formats/exit) + `<Category><Name>` naming; AC-15 `--verbose` to stderr + secret redaction + zero-overhead when disabled; AC-16 progress to stderr + 500ms appearance + non-TTY suppression; AC-17 batch `exec` exit-4-on-partial + `[item]` prefix + deterministic parallel; AC-18 generated-not-handwritten shell completion + hidden `__complete` provider; AC-19 fixed terminal palette (green/red/yellow/cyan/gray) + box-drawing headers + ASCII fallback; AC-20 post-install `doctor` + interactive shell-profile injection + `--json` mode.
- Lockstep §98 v1.0.0 → v1.1.0 + §99 v1.0.0 → v1.1.0 + spec-index updated.

---

## ✅ Phase 16b — Deepen §14 update §97 with module-specific GWT ACs (DONE, v2.0.0)

Second module deepening pass:
- §14 §97 banner v1.0.0 → **v2.0.0** (major; AC count 5 → 20).
- **15 new GWT ACs added (AC-06..AC-20)**, AC-01..AC-05 preserved.
- AC-06 rename-first deploy (Windows-locked-file workaround, atomic same-dir rename, uniform across platforms); AC-07 detached `updater.exe` handoff (CREATE_NEW_PROCESS_GROUP|DETACHED_PROCESS, parent-PID poll, self-delete, log to `%TEMP%`); AC-08 three-branch version verification (deployed/source/declared, build-time `-ldflags -X main.Version` injection, no runtime `version.txt`); AC-09 mandatory code signing (Authenticode `signtool verify /pa`, macOS codesign+notarize, Linux GPG `.sig`, fail-on-unsigned, 30-day cert-expiry warning); AC-10 SHA-256 checksums.txt + verify-before-install (MD5/SHA-1 forbidden, signed checksums fingerprint); AC-11 idempotent install scripts (`%LOCALAPPDATA%\Programs`, `~/.local/bin`, `/usr/local/bin`, no-sudo-by-default, opt-in PATH modification); AC-12 latest-version probe (`releases/latest`, `--version=X.Y.Z` pin, no fallback guess, User-Agent); AC-13 XDG-compliant config (`$XDG_CONFIG_HOME/<binary>/update.json`, flat JSON per §13 AC-08, atomic .tmp+rename writes, 0600 perms); AC-14 ten-step `update` workflow (network → probe → compare → .partial → SHA-256 → signature → .new → detached spawn → exit ≤100ms → swap); AC-15 non-blocking 12h pre-command-hook (fire-and-forget detached child, NO daemon/cron, JSON fallback store, banner on next invocation); AC-16 startup cleanup (.old + stale .partial + old .log, silent failures, idempotent, ≤100ms cap); AC-17 atomic rollback (rename .old back, RECOVERY.md fallback if rollback itself fails); AC-18 deploy path resolution (default → build.json → --deploy-path, refuse system dirs, perm check); AC-19 `git describe --tags` last-release detection (commit-count/branch-name forbidden, conventional-commits drives bump); AC-20 cross-compilation matrix (windows/amd64+arm64, linux/amd64+arm64, darwin/amd64+arm64, CGO_ENABLED=0, `-ldflags -s -w`, fail-on-partial).
- Lockstep §98 v1.0.0 → v1.1.0 + §99 v1.0.0 → v1.1.0 + spec-index updated.

---

## ✅ Phase 16c — §22 git-logs §97 depth pass on AC-04/AC-05/AC-25/AC-34 (DONE, v3.9.0)

Closed the four lean ACs Phase 13 explicitly deferred. AC IDs preserved (still 75; AC-04/AC-05/AC-25/AC-34 grew 10-15× in depth):
- §22 §97 banner v3.8.13 → **v3.9.0**.
- AC-04 logger gating (constant-time integer compare at call boundary, sink-side filtering forbidden, request-scoped cache, 6-level fixed `Trace=0..Fatal=5`); AC-05 dedup window (rolling 60s from FIRST hit, fingerprint = source+level+template+ctx_hash, error/fatal carve-out, process-local LRU bounded at 1024, `[deduped: N]` suffix on expiry); AC-25 `format:hide` omitted from DOM entirely (not CSS-hidden — defense-in-depth against view-source/DevTools/screen-reader/session-replay leakage, server-side-only omission, consistent across all projections, empty parent containers must collapse); AC-34 multisite per-site DB (per-`blog_id` SQLite file at `wp_upload_dir()` path, shared file forbidden even network-activated, `switch_to_blog`/`restore_current_blog` connection invalidation, per-site lazy migration, `wp_delete_site` cleanup).
- Lockstep §98 v3.9.0 row added + §99 v3.8.13 → v3.9.0 + spec-index updated.

---

## ✅ Phase 16d-i — §15 distribution-and-runner §97 deepen (DONE, v2.0.0)

Expanded §15 §97 from 5 generic scaffold ACs (AC-01..AC-05 retained as universal floor) to **20 module-specific GWT ACs** (AC-06..AC-20 added). Each new AC averages 1500-2200 chars with explicit `**Given** / **When** / **Then**` triplet plus `**Verifies:**` cross-ref.
- §15 §97 banner v1.0.0 → **v2.0.0**.
- New ACs: AC-06 installer one-liner shape (Bash+PS parity, idempotent, dep pre-check, exit `1`/`2`/`3`/`4`, 60s SLO); AC-07 default 4-folder layout pinned to release SHA (additive, `--force` to overwrite); AC-08 install-config.json strict schema + lockstep-with-§00 CI gate; AC-09 byte-identical Bash↔PS parity + shared 7-flag surface; AC-10 runner sub-cmd dispatch (4-row contract from §00, unknown=exit `2`, post-cmd flag forwarding); AC-11 back-compat for legacy no-args (no banners, removal needs major + 2 deprecation cycles); AC-12 8-artifact release set (filenames exact, missing any blocks release); AC-13 checksums.txt format (`sha256sum`-compat, installers MUST verify before extract = supply-chain protection, mismatch=`4`); AC-14 linters-install.sh rename (avoids collision with top-level install.sh); AC-15 install destination defaults (EACCES detected pre-download, refuses install into source repo with symlink resolution); AC-16 release pipeline (tag-driven, pinned to tag's SHA not branch HEAD, atomic publish, no auto-publish to npm/PyPI); AC-17 slides browser-open (OS-appropriate, `--no-open` skip, `Ctrl-C` exits `0` not `130`); AC-18 `--ref` reproducible install (tag-or-full-SHA only, branches forbidden); AC-19 cross-refs intact + lockstep maintenance with §12/§13/§16/spec-slides; AC-20 sibling files versioned + `**Verifies:**` MUST cite both §00 and the relevant sibling.
- Lockstep: §98 v2.0.0 row added + §99 v1.0.0 → v2.0.0 + spec-index 3 cells bumped.
- Tree-health: 100/100 (A+) maintained.

---

## ✅ Phase 16d-ii — §16 generic-release §97 deepen (DONE, v2.0.0)

Expanded §16 §97 from 5 generic scaffold ACs (AC-01..AC-05 retained) to **20 module-specific GWT ACs** (AC-06..AC-20 added). Harmonized with §15 AC-12/13/16/18 — citation-only, no edit propagation. §16 is the upstream generic blueprint; §15 is the concrete consumer.
- §16 §97 banner v1.0.0 → **v2.0.0**.
- New ACs: AC-06 cross-comp matrix (6 minimum targets, `CGO_ENABLED=0`, build-once); AC-07 tag-driven trigger (no `main`/PR/schedule, SHA-pinned); AC-08 atomic publication (draft → verify-roundtrip → promote, no auto-publish to npm/PyPI); AC-09 asset naming (`<binary>-<version>-<os>-<arch>.<ext>`, flat archive, Go runtime tokens, `0755`, no symlinks); AC-10 release-metadata.json schema (required keys, `-ldflags -X` version, assets-superset-of-checksums); AC-11 version-pinned installers (no `/releases/latest` probe, spec-first ordering, deterministic re-gen); AC-12 SHA-256 protocol (`sha256sum`-compat, install-time verify before extract, signing-tool-agnostic); AC-13 PATH activation (idempotent fenced-marker block, `doctor` self-heal, no system-wide without `--system`); AC-14 terminal output (stderr for progress, `NO_COLOR` honored, color-when-TTY); AC-15 known-issues ledger (`REL-NNN` IDs, prevention rule REQUIRED in same fix PR, generalizable rules promote to siblings); AC-16 Mermaid diagrams (parseable by `mmdc`, cover all six referenced specs, `.mmd` source separate from `.svg` artifact); AC-17 generic-vs-concrete separation (placeholder convention preserved, consumer ACs cite generic ACs, deviations require justification); AC-18 Bash+PS installer parity (functionally equivalent, OS-conventional defaults, shared 6-flag surface, OS-mismatch detection, third installer flavor forbidden); AC-19 cross-refs intact + bi-directional (back-refs to §12/§13 encouraged); AC-20 sibling files versioned + content-aligned (§08 marked authoritative wins over §03 on installer questions).
- Lockstep: §98 v2.0.0 row added + §99 v1.0.0 → v2.0.0 + spec-index 3 cells bumped.
- Tree-health: 100/100 (A+) maintained.

---

## ✅ Phase 16d-iii — §17 consolidated-guidelines §97 deepen (DONE, v2.0.0)

Expanded §17 §97 from 5 generic scaffold ACs to **20 module-specific GWT ACs** (AC-06..AC-20 added; AC-01..AC-05 preserved). Lockstep §98 v2.0.0 + §99 v4.0.0 + spec-index updated. Tree-health: 100/100 (A+).

---

## ✅ Phase 16d-iv — §27 spec-toolchain §97 deepen (DONE, v2.0.0)

Expanded §27 §97 from 10 ACs (AC-T-01..AC-T-10) to **20 module-specific GWT ACs** (AC-T-11..AC-T-20 added; AC-T-01..AC-T-10 preserved verbatim). New ACs cover: stderr-vs-stdout discipline (AC-T-11), filler tight-loop idempotency (AC-T-12), generator determinism + content-derived timestamps (AC-T-13), auditor JSON output contract (AC-T-14), config self-validation + bidirectional spec-config links (AC-T-15), runner cross-platform pipeline equivalence (AC-T-16), trace-map round-trip + FORBIDDEN-ideas hard-block per `mem://constraints/forbidden-trace-map-ideas` (AC-T-17), Python+Go twin byte-equivalence (AC-T-18), CI workflow trigger-path completeness + threshold lock at 100 (AC-T-19), `trace-map.md` informational-not-spec status with slot 80+ reservation (AC-T-20). Lockstep: §98 v2.0.0 row added + §99 v1.1.0 → v2.0.0 + spec-index regenerated (3 cells bumped). Tree-health: 100/100 (A+) maintained.

---


## 🚧 Blocked (awaiting user decision)
- **Phase B1 — §07 App identity fields**: confirm `Environment`, `Platform`, `OwnerEmail` shape

---

## Status legend
- ✅ done · ⏳ pending · 🚧 blocked

## ✅ Phase 16d-v — §28 universal-ci-cli §97 deepen (DONE, v2.0.0)

Expanded §28 §97 from 28 ACs to **40 module-specific GWT ACs** (AC-28-29..AC-28-40 added; AC-28-01..AC-28-28 preserved). Closed all four v1.1-deferred error codes (`GLCI-EXEC-RUNNER-CRASHED`, `GLCI-EXEC-TIMEOUT`, `GLCI-PUSH-STREAM-BROKEN`, `GLCI-DETECT-MULTIPLE-MODULES`) + added GitLab/Azure/Bitbucket/generic-shell provider auto-fill, telemetry prohibition (Locked Decision #10), streaming buffer cap, per-runtime tool selection (TS/Go/PHP), and direct `glci push-fixed`/`clear` invocation. Lockstep §98 v2.0.0 + §99 v2.0.0 + spec-index regenerated. Tree-health: 100/100 (A+).

---

## ✅ Phase 16e — §02 coding-guidelines §97 deepen (DONE, v4.0.0)

Re-scan of all §97 files surfaced **15 modules with 0 GWT ACs** (table-row scaffolds only). Highest-impact target: **§02 coding-guidelines §97** (the parent governance module for all language subfolders). Rewrote from 22 table-row criteria to **20 module-specific GWT ACs** (AC-CG-01..AC-CG-20) covering numbering ranges, four-required-files rule, six CODE-RED rules (R1–R6), hybrid PascalCase / Rust-snake_case naming policy, AC-count compliance per subfolder, lockstep rule for consolidated review guides, cross-link health, language-vs-cross-language hierarchy, app-specific subfolder boundary, AI-rules canonicalization (`06-ai-optimization`), dependency version pinning, placeholder subfolder remediation, migration-history freshness, module tree-health gate ≥ 95, and recursive self-application (dogfooding). Legacy AC-001..022 preserved as AC-CG-LEGACY-001..022 for traceability. Lockstep: §98 v1.0.0 → v2.0.0 + §99 v3.2.0 → v4.0.0 + spec-index regenerated. Tree-health: **100/100 (A+)** maintained.

---

## ✅ Phase 16f — §01 spec-authoring-guide §97 deepen (DONE, v4.0.0)

The meta-spec — governs every OTHER spec in the tree. Rewrote §97 from 18 table-row criteria to **20 module-specific GWT ACs** (AC-SAG-01..AC-SAG-20). Tree-health: **100/100 (A+)**.

## ✅ Phase 16g — §26 gitlogs-diagrams §97 deepen (DONE, v3.0.0)

Completes the §22 Git Logs governance contract. Rewrote §97 from 9 table-row criteria (with 02/03/04 retired as locked gaps) to **20 module-specific GWT ACs** (AC-DG-01..AC-DG-20) covering: ER schema parity with §22 (entities + FK cardinalities, forbidden v1 entities `LogEntry`/`ErrorLogEntry`/`OwnerType`), auth validation locked order with `GL-*` reject codes, RBAC RolePermission-union resolution (never role name), header-comment contract for non-ER diagrams (`%% Diagram type:` + `%% What this answers:` mandatory), emoji-free + Mermaid-CLI rendering, JWT/RS256/JWKS forbidden, 8-endpoint mindmap completeness, encryption v3 7-node derivation chain, slot 02/03/04 locked-gap immutability, `.mmd` ↔ `.svg` build-artifact lockstep, kebab-case ASCII node IDs, `GL-*` codes cross-validated against §22 §14 registry, `puppeteer.json` reproducibility, governance rule **"§26 trails §22 — never leads"**, and self-application audit (AC-DG-20). Legacy AC-D-01..AC-D-11 preserved as AC-DG-LEGACY-* at end of §97. Lockstep: §98 v2.1.0 → v3.0.0 + §99 v2.1.0 → v3.0.0 + spec-index regenerated. Tree-health: **100/100 (A+)** maintained.

---

## ✅ Phase 16h — §02/01-cross-language §97 deepen (DONE, v4.0.0)

Parent contract for ALL language subfolders under §02. Rewrote §97 from 6 stub checkboxes to **20 module-specific GWT ACs** (AC-CL-01..AC-CL-20) covering: inheritance contract + waiver discipline (AC-CL-01), positive boolean naming (AC-CL-02), boolean-flag method prefixes `is`/`has`/`can`/`should`/`will`/`was`/`did` (AC-CL-03), strict typing no implicit any/`interface{}`/`mixed`/`dynamic` (AC-CL-04), typed conversion over raw casts (AC-CL-05), cyclomatic complexity ≤ 10 hard / ≤ 5 preferred (AC-CL-06), nesting depth ≤ 3 (AC-CL-07), magic-value extraction on rule-of-two (AC-CL-08), JSON keys PascalCase wire-format (AC-CL-09), language-idiomatic function names with cross-language semantic-verb consistency (AC-CL-10), DB tables singular PascalCase + columns PascalCase + FK `<TargetTable>Id` (AC-CL-11), kebab-case ASCII slugs (AC-CL-12), explicit nullability typing (AC-CL-13), lazy evaluation for branched expensive computations (AC-CL-14), regex hygiene (AC-CL-15), code mutation avoidance with `mutate*` exception (AC-CL-16), Result/Option/Either over throwing (AC-CL-17), `types/` folder convention forbids `interfaces/`/`models/`/`dto/`/`entities/` (AC-CL-18), `<unit>.test.<ext>` + behavior-named tests (AC-CL-19), DRY rule-of-three forbids premature abstraction (AC-CL-20). Legacy AC-01/AC-02 preserved as AC-CL-LEGACY-* at end. Lockstep: §98 v3.2.0 → v4.0.0 + §99 v3.2.0 → v4.0.0 + spec-index regenerated. Tree-health: **100/100 (A+)** maintained.

---

## ✅ Phase 16i — §02/02-typescript §97 deepen (DONE, v4.0.0)

First language child to inherit AC-CL-*. Rewrote §97 from 6 stub checkboxes to **20 module-specific GWT ACs** (AC-TS-01..AC-TS-20) covering: explicit AC-CL-01..AC-CL-20 inheritance (AC-TS-01); 6-flag strict tsconfig including `noUncheckedIndexedAccess` + `exactOptionalPropertyTypes` (AC-TS-02); `any` forbidden, `unknown` + narrowing only escape (AC-TS-03); `as const` string-literal-union enums NEVER `enum` keyword (AC-TS-04); `Promise.all` for independent async — CODE-RED rule (AC-TS-05); discriminated unions with `never` exhaustive checks (AC-TS-06); `AppError` discriminated union over `throw new Error` (AC-TS-07); functional components + hooks only (AC-TS-08); Zustand for client / React Query for server — never inverse (AC-TS-09); `async` returns `Promise<Result<T,AppError>>` (AC-TS-10); Zod schema at every external boundary (AC-TS-11); `noUncheckedIndexedAccess` enforces `T | undefined` (AC-TS-12); kebab-case files + PascalCase exports (AC-TS-13); `@typescript-eslint/recommended-type-checked` + `--max-warnings 0` (AC-TS-14); `interface` for shapes / `type` for unions (AC-TS-15); generic constraints required (AC-TS-16); import grouping external→internal-alias→relative + named over default (AC-TS-17); `react-hooks/exhaustive-deps` as error (AC-TS-18); Vitest + RTL behavior-named tests (AC-TS-19); self-application doctest (AC-TS-20). Legacy AC-01/AC-02 preserved as AC-TS-LEGACY-* at end. Lockstep: §98 v3.2.0 → v4.0.0 + §99 v3.2.0 → v4.0.0 + spec-index regenerated. Tree-health: **100/100 (A+)** maintained.

---

## Next-pointer
**Phase 16i complete.** §07 §97 (34 GWT) + §13 §97 (20 GWT) + §14 §97 (20 GWT) + §22 §97 (4 deferred deep) + §15 §97 (20 GWT) + §16 §97 (20 GWT) + §17 §97 (20 GWT) + §27 §97 (20 GWT) + §28 §97 (40 GWT) + §02 §97 (20 GWT) + §01 §97 (20 GWT) + §26 §97 (20 GWT) + §02/01-cross-language §97 (20 GWT) + §02/02-typescript §97 (20 GWT).

**11 remaining §97 files with 0 GWT ACs** (post-16i), priority order:

| # | Module | Current ACs | Priority | Notes |
|--:|---|--:|---|---|
| 1 | `02-coding-guidelines/03-golang/` | 0 | **P1** | Inherits §02/01 |
| 2 | `02-coding-guidelines/04-php/` | 7 (table) | P1 | Has rows but no GWT |
| 3 | `02-coding-guidelines/05-rust/` | 0 | P1 | Inherits §02/01 |
| 4 | `02-coding-guidelines/07-csharp/` | 0 | P1 | Inherits §02/01 |
| 5 | `02-coding-guidelines/06-ai-optimization/` | 0 | P1 | AI rules |
| 6 | `02-coding-guidelines/06-cicd-integration/` | 0 | P2 | 🚨 Slot collision |
| 7 | `02-coding-guidelines/01-cross-language/16-static-analysis/` | 0 | P2 | Deep nested |
| 8 | `14-update/24-update-check-mechanism/` | 0 | P2 | Sub-feature of §14 |
| 9 | `06-seedable-config-architecture/` (root) | 0 | P2 | Audit flagged "phantom" |
| 10 | `05-split-db-architecture/` (root) | 0 | P2 | Audit flagged "phantom" |
| 11 | `_archive/21-git-logs-v1/` | 0 | N/A | Archived; do NOT edit |

🚨 **Slot collision (B2)**: `02-coding-guidelines/` has BOTH `06-ai-optimization/` AND `06-cicd-integration/` — violates AC-CG-01 / AC-SAG-04 (immutable slots).

Remaining work:
- ⏳ **Phase 16j** — Deepen `02-coding-guidelines/03-golang/97` (P1 — second language child).
- ⏳ **Phase 16k–16n** — Deepen PHP/Rust/C#/AI-Opt subfolder §97 files (P1 batch).
- ⏳ **Phase 16p** — Deepen `14-update/24-update-check-mechanism/97` (P2).
- ⏳ **Phase 16q** — Deepen `06-seedable-config-architecture/97` + `05-split-db-architecture/97` (P2 audit-flagged "phantom specs").
- 🚧 **Phase B1** — §07 App identity fields (BLOCKED on user).
- 🚧 **Phase B2** — Slot collision §06 in `02-coding-guidelines/` (BLOCKED on user).

On next `next`: take **Phase 16j (`02-coding-guidelines/03-golang/97` deepen)** — P1 second language child after TypeScript. Unless you redirect or want to unblock B1/B2 first.

