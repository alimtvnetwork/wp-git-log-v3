# Acceptance Criteria

**Version:** 2.0.0
**Updated:** 2026-04-26 (Phase 16d-v: Deepen §28 §97 — 12 new module-specific GWT ACs added (AC-28-29..AC-28-40) closing the v1.1 deferred error-code coverage + provider auto-fill gaps. AC count 28 → 40. AC-28-01..AC-28-28 preserved verbatim.)

Each AC is written **Given / When / Then** so it can be lifted directly into a test (bats / phpunit / go test). When this file and a normative source disagree, the normative source wins and this file MUST be patched.

---

### AC-28-01 — Detection: TS-only repo

- **Given** a directory containing `package.json` and `tsconfig.json` and no `go.mod` or `composer.json`,
- **When** `glci detect --json` runs,
- **Then** it MUST exit `0` and the JSON `Runtimes[]` MUST contain exactly one entry with `Id="ts"`.
- **Verifies:** §03 TS detection table; §04 phase-runtime binding; AC-28-22 multi-runtime precedent.

### AC-28-02 — Detection: empty repo rejected

- **Given** a directory with no recognized markers,
- **When** `glci detect` runs,
- **Then** it MUST exit `2` and stderr MUST contain code `GLCI-DETECT-NONE`.
- **Verifies:** §07 `GLCI-DETECT-NONE`; §03 detection contract (no-marker rejection invariant).

### AC-28-03 — Detection: ambiguous TS lockfiles

- **Given** a directory with both `package-lock.json` and `pnpm-lock.yaml`,
- **When** `glci detect` runs,
- **Then** it MUST exit `2` with `GLCI-DETECT-AMBIGUOUS-LOCK`.
- **Verifies:** §07 `GLCI-DETECT-AMBIGUOUS-LOCK`; §03 single-lockfile invariant (eliminates non-deterministic install).

### AC-28-04 — Override order: flag beats env beats file

- **Given** `glci.toml` sets `server.url=A`, env `GLCI_SERVER_URL=B`, and CLI flag `--server=C`,
- **When** `glci config print --json` runs,
- **Then** the resolved `server.url` MUST equal `C` and its `provenance` MUST equal `flag`.
- **Verifies:** §05 three-layer config precedence (file < env < flag); provenance-tracking invariant for diagnosability.

### AC-28-05 — Batched POST to /append-log carries all required fields

- **Given** a passing `ts-test` phase with 3 log lines,
- **When** the CLI ships in batched mode,
- **Then** the request body MUST validate against `17-openapi-client.yaml#components.schemas.AppendLogBatched` AND `PipelineName` MUST equal `ts-test` AND `HasError` MUST equal `false`.
- **Verifies:** §06 `/append-log` schema contract; §17 OpenAPI client conformance; AC-28-28 client/server-mirror invariant.

### AC-28-06 — Streaming mode uses chunked NDJSON

- **Given** `--stream` is passed AND the server reachable,
- **When** the test phase runs,
- **Then** the HTTP request MUST set `Transfer-Encoding: chunked` AND `Content-Type: application/x-ndjson` AND `X-GL-Stream: 1` AND the first body chunk MUST contain `"StreamHeader":true`.
- **Verifies:** §06 streaming-mode header contract; §17 OpenAPI streaming variant; AC-28-31 stream-broken recovery precedent.

### AC-28-07 — `HasError` reflects ErrorLogs OR exit code

- **Given** a runner that exits 0 but emits a line matching `^FAIL\b`,
- **When** classification runs,
- **Then** `HasError` MUST equal `true` AND `ErrorLogs[]` MUST contain that line.
- **Verifies:** §09 classifier `HasError` invariant — disjunction of (exit≠0) OR (matched failure pattern); guards against false-green from runners that exit 0 on test failure.

### AC-28-08 — FilePaths sorted and deduplicated

- **Given** classifier output that extracts `src/b.ts` then `src/a.ts` then `src/a.ts`,
- **When** the payload is serialized,
- **Then** `FilePaths[]` MUST equal `["src/a.ts","src/b.ts"]`.
- **Verifies:** §06 `FilePaths[]` lex-sort + dedup invariant; §09 classifier output normalization; supports AC-28-23 byte-identical determinism.

### AC-28-09 — Auth lane: SSH headers exclude TempToken in body

- **Given** `--auth-mode=ssh`,
- **When** the CLI POSTs `/append-log`,
- **Then** the request MUST include all 5 headers (`X-GL-Auth-Mode`, `X-GL-Fingerprint`, `X-GL-Timestamp`, `X-GL-Nonce`, `X-GL-Signature`) AND the JSON body MUST NOT contain a `TempToken` field.
- **Verifies:** §10 SSH-lane separation (auth-in-headers, never-in-body) — mirrors `mem://specs/git-logs` SSH-key Lane B contract; AC-28-10 TempToken-mode complement.

### AC-28-10 — Auth lane: TempToken mode populates body fields

- **Given** `--auth-mode=temptoken` and `GLCI_TEMP_TOKEN=tt`, `GLCI_TOKEN=t`,
- **When** the CLI POSTs `/append-log`,
- **Then** the body MUST contain `"TempToken":"tt"` AND `"Token":"t"` AND no `X-GL-Auth-Mode` header.
- **Verifies:** §10 TempToken-lane separation (auth-in-body, never-as-X-GL-Auth-Mode-header) — guarantees the two auth modes never co-mingle credentials; AC-28-09 SSH-mode complement.

### AC-28-11 — 4xx is fatal; no retry

- **Given** the server returns `401 GL-AUTH-TEMPTOKEN-INVALID` on first POST,
- **When** the CLI processes the response,
- **Then** the CLI MUST NOT retry AND MUST exit `3` AND stderr MUST surface the verbatim server `ErrorCode`.
- **Verifies:** §06 4xx-is-deterministic invariant (no retry on client error); §07 exit-code-3 for server-rejected; verbatim-ErrorCode passthrough for diagnosability (mirrors `mem://specs/git-logs` Q3 server-error-code contract).

### AC-28-12 — 5xx triggers exponential backoff

- **Given** the server returns 502 three times then 200,
- **When** `max_retries=3` and `backoff_ms=[500,2000,8000]`,
- **Then** the CLI MUST sleep for ≥500, ≥2000, ≥8000 ms between attempts AND exit `0` AND only one phase POST MUST be visible to the server (the final 200).
- **Verifies:** §06 5xx-is-transient invariant; §05 `push.backoff_ms` exact-honor; idempotency at server (one logical phase = one record).

### AC-28-13 — Retries exhausted → exit 4

- **Given** the server returns 502 four times in a row with `max_retries=3`,
- **When** the CLI processes the responses,
- **Then** the CLI MUST exit `4` with `GLCI-PUSH-RETRIES-EXHAUSTED`.
- **Verifies:** §07 exit-code-4 for retries-exhausted; §06 retry-budget invariant (max_retries is a hard cap, never silently extended); AC-28-31 stream-broken fallback uses same exit code.

### AC-28-14 — Payload cap enforced before send

- **Given** a phase produces 2 MiB of `Logs[]` and `batch_max_bytes=1048576`,
- **When** the payload is built,
- **Then** the serialized JSON body MUST be ≤ 1 MiB AND `ErrorLogs[]` MUST contain a `"GLCI: log truncated, N lines dropped"` synthetic entry where `N≥1`.
- **Verifies:** §05 `push.batch_max_bytes` cap-before-send invariant; §06 truncation-must-be-loud rule (synthetic ErrorLogs entry, not silent drop); pairs with AC-28-36 streaming buffer cap.

### AC-28-15 — `glci doctor` happy path

- **Given** valid config, reachable server, valid TempToken, all runners on PATH,
- **When** `glci doctor` runs,
- **Then** it MUST exit `0` AND stdout MUST list each check with `OK`.
- **Verifies:** §11 `glci doctor` happy-path contract — config-valid, server-reachable, auth-valid, runners-on-PATH; AC-28-16 / AC-28-26 failure-mode complements.

### AC-28-16 — `glci doctor` flags clock skew for SSH mode

- **Given** `--auth-mode=ssh` AND local clock is 120 s ahead of server (as detected via `Date:` response header from probe),
- **When** `glci doctor` runs,
- **Then** it MUST exit `5` with `GLCI-DOCTOR-CLOCK-SKEW`.
- **Verifies:** §07 `GLCI-DOCTOR-CLOCK-SKEW`; §11 SSH-mode signature-window invariant (clock skew breaks `X-GL-Timestamp` validation); exit-code-5 for doctor-failure class.

### AC-28-17 — `/fixed-log` auto-fires only when server reports prior failure

- **Given** the previous run posted `HasError=true` AND the server's ack envelope on the current passing run includes `PreviousHasError=true`,
- **When** the current phase passes,
- **Then** the CLI MUST send `PUT /fixed-log` for the same `(RepoUrl, Branch, PipelineName)` exactly once.
- **Verifies:** §06 `/fixed-log` server-driven invariant — recovery state lives on the server (no local cache); AC-28-18 negative complement.

### AC-28-18 — `/fixed-log` not sent when server omits `PreviousHasError`

- **Given** the server ack envelope does NOT contain `PreviousHasError`,
- **When** the current phase passes,
- **Then** the CLI MUST NOT call `/fixed-log` (no local cache).
- **Verifies:** §06 stateless-CLI invariant — when the server omits `PreviousHasError`, the CLI MUST NOT guess from local history; AC-28-17 positive complement; eliminates a class of duplicate `/fixed-log` posts.

### AC-28-19 — CI provider auto-fill: GitHub

- **Given** env `GITHUB_ACTIONS=true`, `GITHUB_SERVER_URL=https://github.com`, `GITHUB_REPOSITORY=org/repo`, `GITHUB_HEAD_REF=feat/x`, `GITHUB_SHA=abc…`,
- **When** any phase runs,
- **Then** the payload `RepoUrl` MUST equal `https://github.com/org/repo` AND `Branch` MUST equal `feat/x` AND `GitSha256` MUST equal `abc…`.
- **Verifies:** §08 GitHub provider-binding table; AC-28-33 GitLab / AC-28-34 Azure-Bitbucket-shell complements; AC-28-20 SSH-to-HTTPS normalization for the constructed `RepoUrl`.

### AC-28-20 — URL normalization: SSH → HTTPS

- **Given** `git config remote.origin.url` returns `git@github.com:org/repo.git` AND no CI env present,
- **When** payload is built,
- **Then** `RepoUrl` MUST equal `https://github.com/org/repo`.
- **Verifies:** §08 SSH-to-HTTPS canonicalization invariant — server records always store HTTPS form so `(RepoUrl,Branch,PipelineName)` keys collide correctly across SSH-clone vs HTTPS-clone hosts.

### AC-28-21 — `--no-push` skips all network IO

- **Given** `--no-push` is passed,
- **When** any phase runs,
- **Then** the CLI MUST NOT open any HTTPS connection AND exit code MUST reflect only phase outcome (`0` or `1`).
- **Verifies:** §04 `--no-push` air-gapped invariant — local CI rehearsal MUST be totally network-free; pairs with AC-28-35 telemetry-prohibition (network calls are ALWAYS opt-in).

### AC-28-22 — Multiple runtimes: separate PipelineName per (runtime, phase)

- **Given** a repo with both `package.json` AND `composer.json`,
- **When** `glci run` runs,
- **Then** at least 6 distinct `PipelineName` values MUST appear in posted payloads: `ts-lint`, `ts-build`, `ts-test`, `php-lint`, `php-build`, `php-test`.
- **Verifies:** §03 polyglot-detection invariant; §04 `<runtime>-<phase>` PipelineName naming convention; AC-28-37/38/39 per-runtime tool-selection complements.

### AC-28-23 — Determinism: identical input → identical body

- **Given** the same repo, env, and a recorded runner transcript,
- **When** `glci run --no-push --dump-payload` is invoked twice,
- **Then** the two dumped JSON bodies MUST be byte-identical (modulo wall-clock fields not present in this contract).
- **Verifies:** §06 deterministic-serialization invariant — supports payload-replay testing AND signature-stability for SSH-mode (signed-body cannot be a moving target); AC-28-08 sort/dedup is a precondition.

### AC-28-24 — Config validation: HTTPS required by default

- **Given** `glci.toml` sets `server.url="http://example.com/…"` AND `--insecure-http` is NOT passed,
- **When** `glci doctor` runs,
- **Then** it MUST exit `2` with `GLCI-CONFIG-INSECURE-URL`.
- **Verifies:** §05 HTTPS-by-default invariant; §07 `GLCI-CONFIG-INSECURE-URL`; `--insecure-http` is the single explicit opt-out (never silent fallback).

### AC-28-25 — Config validation: backoff length matches max_retries

- **Given** `push.max_retries=3` AND `push.backoff_ms=[500,2000]`,
- **When** config is loaded,
- **Then** the CLI MUST exit `2` with `GLCI-CONFIG-BACKOFF-LENGTH`.
- **Verifies:** §05 backoff-array length-must-equal-max_retries invariant — eliminates a class of off-by-one retry bugs (last attempt with no backoff value); §07 `GLCI-CONFIG-BACKOFF-LENGTH`.

### AC-28-26 — Doctor surfaces server ErrorCode verbatim

- **Given** the server returns `403 GL-AUTH-PROFILE-INACTIVE` on the doctor probe,
- **When** `glci doctor` runs,
- **Then** stderr MUST contain literal `GL-AUTH-PROFILE-INACTIVE` AND exit code MUST equal `5`.
- **Verifies:** §11 doctor-passes-server-codes-verbatim invariant; pairs with AC-28-11 (verbatim ErrorCode passthrough at runtime); exit-code-5 doctor-failure class.

### AC-28-27 — JSON Schema validates default config

- **Given** the output of `glci config print --defaults-only`,
- **When** the JSON is validated against `18-config-schema.json`,
- **Then** validation MUST pass with zero errors.
- **Verifies:** §18 JSON-Schema-as-source-of-truth invariant — defaults are machine-checkable, not human-curated prose; eliminates drift between docs and runtime parser.

### AC-28-28 — OpenAPI client mirrors server endpoint paths

- **Given** `17-openapi-client.yaml` AND `spec/22-git-logs-v2/17-openapi.yaml`,
- **When** the path sets are intersected,
- **Then** every path in the client file MUST exist in the server file.
- **Verifies:** §17 client-server endpoint-parity invariant — `spec/28` client cannot drift ahead of `spec/22-git-logs-v2` server; protects the §06↔§22 cross-spec contract.

---

## v1.1 Deferred-AC Closure (Phase 16d-v additions, AC-28-29..AC-28-40)

The following 12 ACs close the four error codes flagged "v1.1 deferred" in `99-consistency-report.md` plus eight gap-coverage criteria for CI-provider auto-fill, telemetry prohibition, streaming buffer cap, per-runtime tool selection, and direct invocation of `glci push-fixed` / `glci clear`. AC-28-01..AC-28-28 above remain authoritative; these additions extend coverage without modifying existing rules.

### AC-28-29 — `GLCI-EXEC-RUNNER-CRASHED` surfaces non-zero subprocess signals

- **Given** a runner subprocess (e.g. `npm test`, `go test`, `composer test`) terminates via signal SIGSEGV/SIGABRT/SIGKILL with no stdout/stderr output recoverable,
- **When** the CLI's process supervisor reaps the child,
- **Then** the CLI MUST exit `1` AND stderr MUST emit a single line `GLCI-EXEC-RUNNER-CRASHED: phase=<ts-test|go-build|...> signal=<SIGNAME> exit=<code>` AND the posted `ErrorLogs[]` MUST contain a synthetic entry `"GLCI: runner crashed with <SIGNAME> (exit=<code>); no captured output"` so the receiving server's diagnostic UI can distinguish a crash from a clean failure. The crash MUST NOT trigger HTTP retry (treated as a deterministic local failure, NOT a transient network condition).
- **Verifies:** §07 `GLCI-EXEC-RUNNER-CRASHED`; §06 `ErrorLogs[]` contract; §99 v1.1 deferred-coverage closure.

### AC-28-30 — `GLCI-EXEC-TIMEOUT` enforces phase wall-clock cap

- **Given** `glci.toml` sets `exec.phase_timeout_sec=600` (default `1800` per §05) AND a runner phase exceeds the cap with no output for ≥ 60 s,
- **When** the timeout fires,
- **Then** the CLI MUST send SIGTERM, wait `exec.grace_period_sec` (default `10`) for graceful shutdown, then SIGKILL on grace expiry; exit `1`; emit stderr `GLCI-EXEC-TIMEOUT: phase=<name> elapsed=<sec>s cap=<cap>s`; populate `ErrorLogs[]` with `"GLCI: phase exceeded wall-clock cap of <cap>s; sent SIGTERM at <ts> SIGKILL at <ts>"`. The partial captured stdout up to the timeout MUST be preserved in `Logs[]` (NOT discarded) so the user can diagnose the hang.
- **Verifies:** §07 `GLCI-EXEC-TIMEOUT`; §05 `exec.phase_timeout_sec` + `exec.grace_period_sec`; §99 v1.1 deferred-coverage closure.

### AC-28-31 — `GLCI-PUSH-STREAM-BROKEN` recovers a broken NDJSON stream via batched fallback

- **Given** `--stream` is active AND the server closes the chunked connection mid-frame (TCP reset, HTTP/2 GOAWAY, or NDJSON parse error reported via `400 GLCI-STREAM-MALFORMED`),
- **When** the CLI detects the broken stream,
- **Then** the CLI MUST emit stderr `GLCI-PUSH-STREAM-BROKEN: bytes_sent=<N> at_seq=<seq>`, buffer the remaining un-acked frames in memory (capped by `push.stream_buffer_max_lines` default `10000`), and re-attempt delivery via batched `POST /append-log` (NOT another stream) with the SAME `(RepoUrl, Branch, PipelineName)` triple. If the batched fallback also fails, exit `4` (`GLCI-PUSH-RETRIES-EXHAUSTED` per AC-28-13). The CLI MUST NOT silently drop frames — frame loss is a hard failure surfaced via exit code.
- **Verifies:** §07 `GLCI-PUSH-STREAM-BROKEN`; §06 streaming + batched contracts; AC-28-06 streaming headers; §99 v1.1 deferred-coverage closure.

### AC-28-32 — `GLCI-DETECT-MULTIPLE-MODULES` rejects nested-monorepo ambiguity

- **Given** a directory containing TWO `go.mod` files at different depths (e.g. `./go.mod` AND `./services/api/go.mod`) without a `glci.toml` `detect.module_root` override,
- **When** `glci detect` runs,
- **Then** the CLI MUST exit `2` with stderr `GLCI-DETECT-MULTIPLE-MODULES: runtime=go found=[./go.mod, ./services/api/go.mod]` AND suggest the resolution `set detect.module_root=<path> in glci.toml or pass --module-root=<path>`. The same rule applies to multiple `package.json` files (Node.js workspaces) and multiple `composer.json` files (PHP monorepo). Ambiguity MUST NOT be resolved by "first wins" or "deepest wins" heuristics — the CLI ALWAYS demands an explicit resolution.
- **Verifies:** §07 `GLCI-DETECT-MULTIPLE-MODULES`; §03 detection contract; AC-28-02 detection-rejection precedent; §99 v1.1 deferred-coverage closure.

### AC-28-33 — CI provider auto-fill: GitLab

- **Given** env `GITLAB_CI=true`, `CI_PROJECT_URL=https://gitlab.com/org/repo`, `CI_COMMIT_REF_NAME=feature/x`, `CI_COMMIT_SHA=abc...`, `CI_JOB_NAME=test`,
- **When** any phase runs,
- **Then** the payload `RepoUrl` MUST equal `https://gitlab.com/org/repo` AND `Branch` MUST equal `feature/x` AND `GitSha256` MUST equal `abc...` AND when `CI_JOB_NAME` is set the `PipelineName` MUST default to `<runtime>-<phase>-<CI_JOB_NAME>` (e.g. `ts-test-test`) so distinct jobs in the same pipeline produce distinct pipeline records on the server. Conflicting flags (`--branch=foo`) override the env values per §05's flag-beats-env-beats-file order.
- **Verifies:** §08 GitLab binding; AC-28-04 override order; AC-28-19 GitHub-binding precedent.

### AC-28-34 — CI provider auto-fill: Azure Pipelines + Bitbucket + generic shell

- **Given** EITHER (a) env `TF_BUILD=True`, `BUILD_REPOSITORY_URI=https://dev.azure.com/org/proj/_git/repo`, `BUILD_SOURCEBRANCH=refs/heads/main`, `BUILD_SOURCEVERSION=abc...` (Azure), OR (b) env `BITBUCKET_BUILD_NUMBER`, `BITBUCKET_GIT_HTTP_ORIGIN`, `BITBUCKET_BRANCH`, `BITBUCKET_COMMIT` (Bitbucket), OR (c) NO recognized CI env (generic shell fallback),
- **When** any phase runs,
- **Then** the payload `RepoUrl`/`Branch`/`GitSha256` MUST be derived per the §08 binding table for each provider; for the generic-shell case (c) the CLI MUST fall back to `git config remote.origin.url` (normalized per AC-28-20), `git rev-parse --abbrev-ref HEAD`, and `git rev-parse HEAD` AND emit a stderr warning `GLCI: no CI provider detected; using local git derivation`. Azure's `refs/heads/` prefix MUST be stripped from `Branch`. The provider-detection precedence order MUST be deterministic: GitHub → GitLab → Azure → Bitbucket → generic shell.
- **Verifies:** §08 Azure/Bitbucket/generic-shell bindings; AC-28-19 GitHub-binding precedent; AC-28-20 URL normalization.

### AC-28-35 — Telemetry prohibition is enforced at network layer

- **Given** Locked Decision #10 ("Telemetry: None. The CLI MUST NOT call any host other than the configured Git Logs server"),
- **When** the CLI is built AND its outbound HTTP allowlist is inspected (linker-time or runtime),
- **Then** the binary MUST refuse to make any HTTPS call to a host other than the resolved `server.url` host AND the host of any `git config remote.origin.url` (read-only, never written-to). Accidental analytics SDKs, crash-reporting endpoints (Sentry, Bugsnag), and update-check probes are FORBIDDEN — the CI test suite MUST include a sandboxed-network test that fails if ANY DNS resolution OR TCP connect targets a host outside the two-host allowlist. A telemetry violation is a CRITICAL security finding and blocks release.
- **Verifies:** Locked Decision #10; §07 (no `GLCI-TELEMETRY-*` codes — telemetry doesn't exist); CI release-gate sandboxed-network test.

### AC-28-36 — Streaming mode buffer cap drops oldest frames with audit log

- **Given** `--stream` is active AND the server is slow (ack lag ≥ 5 s) AND the CLI has buffered `push.stream_buffer_max_lines + 1` un-acked frames in memory,
- **When** the next frame would be enqueued,
- **Then** the CLI MUST drop the OLDEST un-acked frame (FIFO eviction, NOT the newest), increment a counter `dropped_frames`, emit stderr `GLCI: stream buffer full; dropped frame seq=<N>` once per 100 drops (NOT every drop, to avoid log flood), AND inject a synthetic `ErrorLogs[]` entry at end-of-phase `"GLCI: stream buffer overflow; dropped <count> frames (oldest first); consider increasing push.stream_buffer_max_lines or switching to batched mode"`. The phase exit code MUST be `1` (not `0`) when ANY frame was dropped, even if the underlying runner exited `0`, because dropped frames mean the server's record is incomplete.
- **Verifies:** §05 `push.stream_buffer_max_lines`; §06 streaming contract; AC-28-31 stream-broken precedent.

### AC-28-37 — Per-runtime tool selection: TypeScript

- **Given** a TS-only repo (per AC-28-01) AND a `package.json` with `scripts.lint`, `scripts.build`, `scripts.test` defined AND a lockfile of one of `package-lock.json` (npm), `pnpm-lock.yaml` (pnpm), `bun.lockb` (bun), `yarn.lock` (yarn classic) OR `yarn.lock` + `.yarnrc.yml` (yarn berry),
- **When** `glci lint` / `glci build` / `glci test` runs without `--runner` override,
- **Then** the CLI MUST select the package manager from the lockfile (per the §03 detection table) AND invoke `<pm> run <phase>` (e.g. `pnpm run test`); MUST set `CI=true`, `FORCE_COLOR=0`, `npm_config_progress=false` in the subprocess env to suppress interactive prompts and color codes that break log parsing; MUST NOT install dependencies implicitly — if `node_modules/` is absent the CLI exits `1` with `GLCI-EXEC-DEPS-MISSING: run <pm> install first`. Yarn berry's `.yarnrc.yml` MUST be detected even when `yarn.lock` is also present (berry takes precedence over classic yarn).
- **Verifies:** §03 TS detection table; §04 phase commands; AC-28-01 TS detection.

### AC-28-38 — Per-runtime tool selection: Go

- **Given** a Go repo (`go.mod` present, no `package.json`/`composer.json`),
- **When** `glci lint` / `glci build` / `glci test` runs,
- **Then** the CLI MUST invoke: `lint` → `golangci-lint run ./...` (if `.golangci.yml` exists) OR `go vet ./...` (fallback); `build` → `go build ./...` with `GOFLAGS=-buildvcs=false`; `test` → `go test -race -count=1 ./...` with `GOMAXPROCS` capped at `runtime.NumCPU()` (NOT unbounded, to keep CI runners stable); MUST set `CGO_ENABLED=0` unless `glci.toml` explicitly sets `runtime.go.cgo=true`; the test phase's stdout MUST be parsed by §09's Go-specific classifier to extract `FilePaths[]` from `--- FAIL: TestName (<duration>)` blocks via `t.go:<line>:` anchors. `go test -json` MAY be used internally for structured parsing but the human-readable output MUST still be preserved in `Logs[]`.
- **Verifies:** §03 Go detection table; §04 phase commands; §09 Go classifier; AC-28-08 FilePaths sort/dedup.

### AC-28-39 — Per-runtime tool selection: PHP

- **Given** a PHP repo (`composer.json` present, no `package.json`/`go.mod`),
- **When** `glci lint` / `glci build` / `glci test` runs,
- **Then** the CLI MUST invoke: `lint` → `composer run lint` (if defined) OR `vendor/bin/phpcs --standard=PSR12` (fallback) OR `vendor/bin/phpstan analyse` if `phpstan.neon` exists; `build` → `composer run build` if defined ELSE no-op exit `0` (PHP usually skips build); `test` → `vendor/bin/phpunit --colors=never` OR `vendor/bin/pest --colors=never`; MUST set `COMPOSER_NO_INTERACTION=1` AND `XDEBUG_MODE=off` (unless `--coverage` is passed, which sets `XDEBUG_MODE=coverage`); MUST refuse to run if `vendor/` is absent with `GLCI-EXEC-DEPS-MISSING: run composer install first`. The test classifier MUST extract `FilePaths[]` from PHPUnit's `<file>:<line>` anchors.
- **Verifies:** §03 PHP detection table; §04 phase commands; §09 PHP classifier; AC-28-08 FilePaths sort/dedup.

### AC-28-40 — `glci push-fixed` and `glci clear` invoke their endpoints directly without phase execution

- **Given** the user runs `glci push-fixed` OR `glci clear` (NOT `glci run`),
- **When** the command executes,
- **Then** `glci push-fixed` MUST send a single `PUT /fixed-log` for the current `(RepoUrl, Branch, PipelineName)` triple WITHOUT running any lint/build/test phase — the command exists to manually mark a green pipeline when the server didn't get the auto-fired `/fixed-log` (per AC-28-17); `glci clear` MUST send `POST /clear-log` for the same triple, AND with `--all` it MUST send `POST /clear-log-all` for `(RepoUrl, Branch)` — affecting ALL pipelines on the branch. Both commands MUST honor `--no-push` (in which case they print the would-be request body to stdout and exit `0`); both MUST surface server `ErrorCode` verbatim per AC-28-26; both MUST NOT auto-trigger `/fixed-log` (no recursive auto-fire). Exit codes match the table in AC-28-11/12/13.
- **Verifies:** §04 `push-fixed` / `clear` / `clear --all` subcommands; §06 endpoint mapping; AC-28-17 auto-fired `/fixed-log`; AC-28-21 `--no-push` precedent.
