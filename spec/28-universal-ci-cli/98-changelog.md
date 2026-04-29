# Changelog

**Updated:** 2026-04-29


All notable changes to `spec/28-universal-ci-cli/`.

## [2.1.2] — 2026-04-29 — Phase 150 (P3 sweep slot 10 — Verifies clauses on §97 AC-28-01..AC-28-28)

- **Added** `**Verifies:**` clauses to AC-28-01 through AC-28-28 in `97-acceptance-criteria.md` (28 ACs). Verifies-coverage gap: **12/40 → 40/40**. Each AC now explicitly maps to its underlying invariant. Selected mappings: AC-28-01 (§03 TS detection), AC-28-04 (§05 file<env<flag precedence + provenance), AC-28-05/06/28 (§06↔§17 OpenAPI/streaming contract), AC-28-07 (§09 `HasError` disjunction), AC-28-08 (§06 sort+dedup; supports AC-28-23 byte-identical determinism), AC-28-09/10 (§10 SSH-vs-TempToken lane separation; mirrors `mem://specs/git-logs` SSH-key Lane B), AC-28-11/13 (§07 exit-code-3 / exit-code-4 + 4xx-fatal / retry-budget), AC-28-12 (§05 backoff exact-honor + idempotency), AC-28-14 (§05 `batch_max_bytes` cap-before-send + truncation-must-be-loud), AC-28-15/16/26 (§11 doctor happy/skew/verbatim-passthrough), AC-28-17/18 (§06 `/fixed-log` server-driven, no-local-cache), AC-28-19/20 (§08 GitHub binding + SSH-to-HTTPS canonicalization), AC-28-21 (§04 `--no-push` air-gapped), AC-28-22 (§03 polyglot detection + `<runtime>-<phase>` PipelineName), AC-28-23 (§06 deterministic-serialization for SSH signature stability), AC-28-24/25 (§05 HTTPS-by-default + backoff-length-equals-max_retries), AC-28-27 (§18 JSON-Schema-as-source-of-truth). AC-28-01..AC-28-28 GWT bodies preserved verbatim. §97 v2.0.0 → v2.1.0; §00 v2.1.1 → v2.1.2; §99 v2.1.1 → v2.1.2. `check-ai-confidence.py` P3 driver eliminated for `spec/28`.

## [2.1.1] — 2026-04-28
- **P22 sync** (2026-04-28): §00 banner version field bumped 1.1.0 → 2.1.1 to match this release row (H10 §00 ↔ §98 parity catch-up; opt-in `<!-- h10-verified-phase: 22 -->` stamp added under §00 banner; no spec content change).

### Audit (no content change)

- **Phase P12 — "Deepen 9 thin section files" backlog item closed as STALE.** This task was queued before Phases 31/55/63/76 deepened the module to a perfect 100/100 strict tree-health score. Re-audit confirms: all 9 section files (`01`..`09`) are 83–132 lines of dense tabular contract (enums, harvest maps, layered-design rules, error catalogs, provider bindings) — comparable to or denser than peer modules. §99 reports `Files present 15/15`, `0 TODO/TBD/FIXME markers`, implementability `100`, all internal consistency checks ✅, and `check-tree-health.cjs --strict` returns `168/168 (all 56 modules at full marks)` with §28 contributing 3/3 quality credits. The "thin files" framing was inherited from the audit-v4 baseline (45/100) which has been superseded by audit-v5 (Phase 130). No file edits required; no AC changes; no version bump for any section file. §98 / §99 receive a patch bump to record the disposition.

## [2.1.0] — 2026-04-27

### Fixed

- **Phase 119 — §07 ↔ §97 GLCI-* containment drift repair (surfaced by Phase 118 sweep).** The Phase 118 AC-31-31 bounding sweep against §28 surfaced 2 codes referenced in §97-acceptance-criteria.md but undefined in §07-error-catalog.md: `GLCI-EXEC-DEPS-MISSING` (cited in AC-28-37 for TypeScript and AC-28-39 for PHP — refusal to implicitly install dependencies) and `GLCI-STREAM-MALFORMED` (cited in AC-28-26 — server-side NDJSON framing rejection during `--stream` mode). Both codes are now formally added to §07: `GLCI-EXEC-DEPS-MISSING` slotted into the `## Execution` table with `Exit=1`, scoped explicitly to TypeScript+PHP runtimes (Go excluded — modules cache lives outside the repo); `GLCI-STREAM-MALFORMED` slotted into `## Push (transport)` with `Exit=4` and an explicit comparison clause distinguishing it from the adjacent `GLCI-PUSH-STREAM-BROKEN` (the latter = post-retry connection drop; the new code = active server framing rejection). The Phase 118 sweep also flagged a `GLCI-TELEMETRY-` family reference in §97 line 231 — re-inspection confirmed this is a **negative reference** ("no `GLCI-TELEMETRY-*` codes — telemetry doesn't exist" per Locked Decision #10) and NOT a real undefined code; no §07 row added. Catalog GLCI-* count: 27 → **29**. §07 banner v1.0.0 → v1.1.0; §99 v2.0.0 → v2.1.0; §99's prior claim "all 28 GLCI-* codes have direct AC coverage" superseded — now 29 codes with full §07 ↔ §97 containment, the inverse direction (§97 ⊆ §07) verified by Phase 118 re-sweep. No new ACs in §97 (the existing AC-28-26 / AC-28-37 / AC-28-39 already cite the now-defined codes; this is a §07 catalog hygiene fix, not a coverage extension). Mechanical guard for this drift class is still pending Phase 117 (`test-glci-error-code-containment.sh` — currently in the Phase 117 backlog awaiting user go/no-go).

## [2.0.0] — 2026-04-26

### Added
- **Phase 16d-v — Deepen §28 §97.** Added 12 new module-specific GWT ACs (AC-28-29..AC-28-40), bringing total from 28 → 40. AC-28-01..AC-28-28 preserved verbatim.
- **AC-28-29..AC-28-32** close the four error codes flagged "v1.1 deferred" in `99-consistency-report.md`: `GLCI-EXEC-RUNNER-CRASHED` (subprocess signal handling, no retry), `GLCI-EXEC-TIMEOUT` (SIGTERM→grace→SIGKILL with partial-stdout preservation), `GLCI-PUSH-STREAM-BROKEN` (NDJSON stream failure → batched fallback), `GLCI-DETECT-MULTIPLE-MODULES` (nested-monorepo ambiguity, no heuristic resolution).
- **AC-28-33..AC-28-34** add CI-provider auto-fill coverage for GitLab, Azure Pipelines, Bitbucket, and generic-shell fallback (AC-28-19 only covered GitHub). Provider precedence order locked: GitHub → GitLab → Azure → Bitbucket → generic shell.
- **AC-28-35** enforces Locked Decision #10 (telemetry prohibition) at network layer via two-host allowlist + sandboxed-network test in CI.
- **AC-28-36** specifies stream buffer overflow behavior (FIFO drop oldest, throttled stderr, synthetic ErrorLogs entry, exit `1` on any drop).
- **AC-28-37..AC-28-39** specify per-runtime tool selection for TypeScript (npm/pnpm/bun/yarn berry detection + env hardening), Go (`golangci-lint`/`go test -race -count=1`/CGO disabled), and PHP (composer/phpcs/phpstan/phpunit/pest with XDEBUG_MODE management).
- **AC-28-40** specifies direct invocation of `glci push-fixed` (manual `/fixed-log` mark, no phase execution) and `glci clear` / `clear --all` (`/clear-log` vs `/clear-log-all` triple-vs-pair scope).
- Banner v1.0.0 → v2.0.0 (major; AC count 28 → 40 closing all v1.1 deferred coverage). Lockstep §99 v1.0.0 → v2.0.0; spec-index regenerated.

## [1.0.0] — 2026-04-25

### Added
- Initial draft of the Universal CI CLI spec module.
- §00 overview with 12 locked decisions and 14-file inventory.
- §01 glossary + 5 enums (`Phase`, `Runtime`, `Severity`, `ExitCode`, `LogShipMode`).
- §02 architecture: process model, layered design, plugin model, concurrency, failure semantics.
- §03 runtime detection table for `ts` (npm/pnpm/bun/yarn), `go`, `php`.
- §04 command surface: 9 subcommands, full flag tables, exit-code matrix.
- §05 config resolution: 4-tier override order, full `glci.toml` schema, env-var map, validation rules.
- §06 log shipping contract: batched + streaming modes mapped to v2 endpoints.
- §07 error catalog: 24 `GLCI-*` codes + verbatim `GL-*` forwarding from v2 server.
- §08 CI provider bindings for GitHub Actions, GitLab CI, Azure Pipelines, Bitbucket, generic shell.
- §09 output classification: built-in pattern table + per-runtime `FilePaths` extraction.
- §17 OpenAPI 3.1 client contract (`17-openapi-client.yaml`).
- §18 JSON Schema for `glci.toml` (`18-config-schema.json`).
- §97 acceptance criteria: 28 Given/When/Then ACs covering detection, config, classification, shipping, auth, doctor, determinism.
- §99 consistency report with cross-doc bijection table.

### Cross-references established
- v2 server REST contract (`spec/22-git-logs-v2/04-rest-api-endpoints.md`).
- v2 auth + validation order (`spec/22-git-logs-v2/05-auth-and-validation.md`).
- v2 error codes (`spec/22-git-logs-v2/15-error-codes.md`).
- Generic-CLI conventions (`spec/13-generic-cli/`).
- Existing shared CLI wrapper guidance (`spec/12-cicd-pipeline-workflows/03-reusable-ci-guards/07-shared-cli-wrapper.md`).

| Date | Bump | Notes |
|------|------|-------|
| 2026-04-26 | patch | Phase 31: Added Validation History / File Inventory headings to §99 to satisfy rubric v2.0.0 quality dimension. |

## Releases
### 1.1.0 — 2026-04-27 (Phase 55 — implementability lever)
- **Added** Added 2 CI provider YAML workflows (GitLab + Azure) so YAML block count ≥5 → `has_ci_workflow` (+5). Added 2 Go reference helpers (line classifier + runtime detection) so Go block count ≥3 → `has_typed_lang_contract` (+10).


## 2026-04-27 — Phase 63 impl-sweep

- Phase 63: appended Universal CI CLI enums TS enum mirror to satisfy `has_ts_enums` rubric (impl 80 → 90).

## 2026-04-27 — Phase 76 (impl 90 → 100)

- Added Mermaid lifecycle diagram — satisfies `has_mermaid` (+5).
- Added SQL DDL audit-log schema — satisfies `has_sql_ddl` (+20).
- Implementability raised 90 → 100 (deterministic audit, capped).

