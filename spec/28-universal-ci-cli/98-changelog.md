# Changelog

All notable changes to `spec/28-universal-ci-cli/`.

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
