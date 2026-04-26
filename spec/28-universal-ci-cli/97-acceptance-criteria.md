# Acceptance Criteria

**Version:** 2.0.0
**Updated:** 2026-04-26 (Phase 16d-v: Deepen §28 §97 — 12 new module-specific GWT ACs added (AC-28-29..AC-28-40) closing the v1.1 deferred error-code coverage + provider auto-fill gaps. AC count 28 → 40. AC-28-01..AC-28-28 preserved verbatim.)

Each AC is written **Given / When / Then** so it can be lifted directly into a test (bats / phpunit / go test). When this file and a normative source disagree, the normative source wins and this file MUST be patched.

---

### AC-28-01 — Detection: TS-only repo

- **Given** a directory containing `package.json` and `tsconfig.json` and no `go.mod` or `composer.json`,
- **When** `glci detect --json` runs,
- **Then** it MUST exit `0` and the JSON `Runtimes[]` MUST contain exactly one entry with `Id="ts"`.

### AC-28-02 — Detection: empty repo rejected

- **Given** a directory with no recognized markers,
- **When** `glci detect` runs,
- **Then** it MUST exit `2` and stderr MUST contain code `GLCI-DETECT-NONE`.

### AC-28-03 — Detection: ambiguous TS lockfiles

- **Given** a directory with both `package-lock.json` and `pnpm-lock.yaml`,
- **When** `glci detect` runs,
- **Then** it MUST exit `2` with `GLCI-DETECT-AMBIGUOUS-LOCK`.

### AC-28-04 — Override order: flag beats env beats file

- **Given** `glci.toml` sets `server.url=A`, env `GLCI_SERVER_URL=B`, and CLI flag `--server=C`,
- **When** `glci config print --json` runs,
- **Then** the resolved `server.url` MUST equal `C` and its `provenance` MUST equal `flag`.

### AC-28-05 — Batched POST to /append-log carries all required fields

- **Given** a passing `ts-test` phase with 3 log lines,
- **When** the CLI ships in batched mode,
- **Then** the request body MUST validate against `17-openapi-client.yaml#components.schemas.AppendLogBatched` AND `PipelineName` MUST equal `ts-test` AND `HasError` MUST equal `false`.

### AC-28-06 — Streaming mode uses chunked NDJSON

- **Given** `--stream` is passed AND the server reachable,
- **When** the test phase runs,
- **Then** the HTTP request MUST set `Transfer-Encoding: chunked` AND `Content-Type: application/x-ndjson` AND `X-GL-Stream: 1` AND the first body chunk MUST contain `"StreamHeader":true`.

### AC-28-07 — `HasError` reflects ErrorLogs OR exit code

- **Given** a runner that exits 0 but emits a line matching `^FAIL\b`,
- **When** classification runs,
- **Then** `HasError` MUST equal `true` AND `ErrorLogs[]` MUST contain that line.

### AC-28-08 — FilePaths sorted and deduplicated

- **Given** classifier output that extracts `src/b.ts` then `src/a.ts` then `src/a.ts`,
- **When** the payload is serialized,
- **Then** `FilePaths[]` MUST equal `["src/a.ts","src/b.ts"]`.

### AC-28-09 — Auth lane: SSH headers exclude TempToken in body

- **Given** `--auth-mode=ssh`,
- **When** the CLI POSTs `/append-log`,
- **Then** the request MUST include all 5 headers (`X-GL-Auth-Mode`, `X-GL-Fingerprint`, `X-GL-Timestamp`, `X-GL-Nonce`, `X-GL-Signature`) AND the JSON body MUST NOT contain a `TempToken` field.

### AC-28-10 — Auth lane: TempToken mode populates body fields

- **Given** `--auth-mode=temptoken` and `GLCI_TEMP_TOKEN=tt`, `GLCI_TOKEN=t`,
- **When** the CLI POSTs `/append-log`,
- **Then** the body MUST contain `"TempToken":"tt"` AND `"Token":"t"` AND no `X-GL-Auth-Mode` header.

### AC-28-11 — 4xx is fatal; no retry

- **Given** the server returns `401 GL-AUTH-TEMPTOKEN-INVALID` on first POST,
- **When** the CLI processes the response,
- **Then** the CLI MUST NOT retry AND MUST exit `3` AND stderr MUST surface the verbatim server `ErrorCode`.

### AC-28-12 — 5xx triggers exponential backoff

- **Given** the server returns 502 three times then 200,
- **When** `max_retries=3` and `backoff_ms=[500,2000,8000]`,
- **Then** the CLI MUST sleep for ≥500, ≥2000, ≥8000 ms between attempts AND exit `0` AND only one phase POST MUST be visible to the server (the final 200).

### AC-28-13 — Retries exhausted → exit 4

- **Given** the server returns 502 four times in a row with `max_retries=3`,
- **When** the CLI processes the responses,
- **Then** the CLI MUST exit `4` with `GLCI-PUSH-RETRIES-EXHAUSTED`.

### AC-28-14 — Payload cap enforced before send

- **Given** a phase produces 2 MiB of `Logs[]` and `batch_max_bytes=1048576`,
- **When** the payload is built,
- **Then** the serialized JSON body MUST be ≤ 1 MiB AND `ErrorLogs[]` MUST contain a `"GLCI: log truncated, N lines dropped"` synthetic entry where `N≥1`.

### AC-28-15 — `glci doctor` happy path

- **Given** valid config, reachable server, valid TempToken, all runners on PATH,
- **When** `glci doctor` runs,
- **Then** it MUST exit `0` AND stdout MUST list each check with `OK`.

### AC-28-16 — `glci doctor` flags clock skew for SSH mode

- **Given** `--auth-mode=ssh` AND local clock is 120 s ahead of server (as detected via `Date:` response header from probe),
- **When** `glci doctor` runs,
- **Then** it MUST exit `5` with `GLCI-DOCTOR-CLOCK-SKEW`.

### AC-28-17 — `/fixed-log` auto-fires only when server reports prior failure

- **Given** the previous run posted `HasError=true` AND the server's ack envelope on the current passing run includes `PreviousHasError=true`,
- **When** the current phase passes,
- **Then** the CLI MUST send `PUT /fixed-log` for the same `(RepoUrl, Branch, PipelineName)` exactly once.

### AC-28-18 — `/fixed-log` not sent when server omits `PreviousHasError`

- **Given** the server ack envelope does NOT contain `PreviousHasError`,
- **When** the current phase passes,
- **Then** the CLI MUST NOT call `/fixed-log` (no local cache).

### AC-28-19 — CI provider auto-fill: GitHub

- **Given** env `GITHUB_ACTIONS=true`, `GITHUB_SERVER_URL=https://github.com`, `GITHUB_REPOSITORY=org/repo`, `GITHUB_HEAD_REF=feat/x`, `GITHUB_SHA=abc…`,
- **When** any phase runs,
- **Then** the payload `RepoUrl` MUST equal `https://github.com/org/repo` AND `Branch` MUST equal `feat/x` AND `GitSha256` MUST equal `abc…`.

### AC-28-20 — URL normalization: SSH → HTTPS

- **Given** `git config remote.origin.url` returns `git@github.com:org/repo.git` AND no CI env present,
- **When** payload is built,
- **Then** `RepoUrl` MUST equal `https://github.com/org/repo`.

### AC-28-21 — `--no-push` skips all network IO

- **Given** `--no-push` is passed,
- **When** any phase runs,
- **Then** the CLI MUST NOT open any HTTPS connection AND exit code MUST reflect only phase outcome (`0` or `1`).

### AC-28-22 — Multiple runtimes: separate PipelineName per (runtime, phase)

- **Given** a repo with both `package.json` AND `composer.json`,
- **When** `glci run` runs,
- **Then** at least 6 distinct `PipelineName` values MUST appear in posted payloads: `ts-lint`, `ts-build`, `ts-test`, `php-lint`, `php-build`, `php-test`.

### AC-28-23 — Determinism: identical input → identical body

- **Given** the same repo, env, and a recorded runner transcript,
- **When** `glci run --no-push --dump-payload` is invoked twice,
- **Then** the two dumped JSON bodies MUST be byte-identical (modulo wall-clock fields not present in this contract).

### AC-28-24 — Config validation: HTTPS required by default

- **Given** `glci.toml` sets `server.url="http://example.com/…"` AND `--insecure-http` is NOT passed,
- **When** `glci doctor` runs,
- **Then** it MUST exit `2` with `GLCI-CONFIG-INSECURE-URL`.

### AC-28-25 — Config validation: backoff length matches max_retries

- **Given** `push.max_retries=3` AND `push.backoff_ms=[500,2000]`,
- **When** config is loaded,
- **Then** the CLI MUST exit `2` with `GLCI-CONFIG-BACKOFF-LENGTH`.

### AC-28-26 — Doctor surfaces server ErrorCode verbatim

- **Given** the server returns `403 GL-AUTH-PROFILE-INACTIVE` on the doctor probe,
- **When** `glci doctor` runs,
- **Then** stderr MUST contain literal `GL-AUTH-PROFILE-INACTIVE` AND exit code MUST equal `5`.

### AC-28-27 — JSON Schema validates default config

- **Given** the output of `glci config print --defaults-only`,
- **When** the JSON is validated against `18-config-schema.json`,
- **Then** validation MUST pass with zero errors.

### AC-28-28 — OpenAPI client mirrors server endpoint paths

- **Given** `17-openapi-client.yaml` AND `spec/22-git-logs-v2/17-openapi.yaml`,
- **When** the path sets are intersected,
- **Then** every path in the client file MUST exist in the server file.
