# Error Catalog

**Version:** 1.1.1  
**Updated:** 2026-04-30 (Phase 153 Task A11g — `RLOGGER-DOCTOR-PROFILE-NOT-FOUND` row clarification: server-side `RepoUrl` → `GitProfile` resolution, CLI passive; bound by §97 AC-28-43)

All `RLOGGER-*` codes the CLI itself emits. Server-originated `GL-*` codes are surfaced verbatim per [`spec/22-git-logs-v2/15-error-codes.md`](../22-git-logs-v2/15-error-codes.md). Adding a new code requires a row here.

---

## Configuration

| Code | Exit | Cause | Caller action |
|------|-----:|-------|---------------|
| RLOGGER-CONFIG-MISSING-FILE | 2 | `--config <path>` does not exist | Pass an existing file or run `rlogger config print --defaults-only > rlogger.toml` |
| RLOGGER-CONFIG-PARSE-FAILED | 2 | `rlogger.toml` is not valid TOML | Run `taplo lint rlogger.toml` |
| RLOGGER-CONFIG-MISSING-ENV | 2 | `${env:NAME}` referenced but env var unset | Export the env var |
| RLOGGER-CONFIG-MISSING-TOKEN | 2 | `auth_mode=temptoken` but no `temp_token` | Set `RLOGGER_TEMP_TOKEN` |
| RLOGGER-CONFIG-SSH-KEY-MISSING | 2 | `auth_mode=ssh` but key file unreadable | `chmod 600 <key>` and check path |
| RLOGGER-CONFIG-INSECURE-URL | 2 | `server.url` is `http://` without `--insecure-http` | Use HTTPS or pass `--insecure-http` |
| RLOGGER-CONFIG-BACKOFF-LENGTH | 2 | `len(backoff_ms) != max_retries` | Adjust array length |
| RLOGGER-CONFIG-BAD-TEMPLATE | 2 | `pipeline.name_template` has unknown placeholder | Use only `{runtime}` and `{phase}` |
| RLOGGER-CONFIG-NO-RUNTIME | 2 | All `[runtime.*].enabled=false` | Enable at least one |
| RLOGGER-CONFIG-BATCH-TOO-LARGE | 2 | `batch_max_bytes > 1048576` | Lower the value |

## Detection

| Code | Exit | Cause | Caller action |
|------|-----:|-------|---------------|
| RLOGGER-DETECT-NONE | 2 | No runtime markers found | Add `package.json`/`go.mod`/`composer.json` or pass `--cwd` |
| RLOGGER-DETECT-AMBIGUOUS-LOCK | 2 | Multiple TS lockfiles present | Delete all but one or pin manager in `rlogger.toml` |
| RLOGGER-DETECT-MULTIPLE-MODULES | 2 | `go.work` references modules outside `--cwd` | Pass `--cwd` to repo root |

## Doctor

| Code | Exit | Cause | Caller action |
|------|-----:|-------|---------------|
| RLOGGER-DOCTOR-RUNNER-MISSING | 5 | A required runner binary is not on PATH | Install the runner (e.g. `npm i -D vitest`) |
| RLOGGER-DOCTOR-LINTER-MISSING | 5 (warn) | `golangci-lint` absent; falling back to `go vet` | Install golangci-lint for full coverage |
| RLOGGER-DOCTOR-SERVER-UNREACHABLE | 5 | TCP/TLS handshake to `server.url` failed | Check network/firewall/cert |
| RLOGGER-DOCTOR-AUTH-INVALID | 5 | Server returned `GL-AUTH-*` on probe | Re-issue TempToken in admin UI |
| RLOGGER-DOCTOR-PROFILE-NOT-FOUND | 5 | Server returned `GL-VALIDATION-PROFILE-NOT-FOUND` (server resolves the runner's `RepoUrl` to a `GitProfile` row in the admin database; the CLI itself MUST NOT attempt local profile lookup — it merely surfaces the server's 404 verbatim) | Add the GitProfile in the admin UI for this `RepoUrl` |
| RLOGGER-DOCTOR-CLOCK-SKEW | 5 | Local clock vs server > 60s; would fail SSH `GL-SSH-TIMESTAMP-SKEW` | NTP sync the runner |

## Execution

| Code | Exit | Cause | Caller action |
|------|-----:|-------|---------------|
| RLOGGER-EXEC-RUNNER-FAILED | 1 | A runner exited non-zero | Read `ErrorLogs[]` in the admin UI |
| RLOGGER-EXEC-RUNNER-CRASHED | 1 | Runner died with signal (SIGSEGV, SIGKILL) | Inspect `ErrorLogs[]`; OOM likely |
| RLOGGER-EXEC-TIMEOUT | 1 | Phase exceeded `phase_timeout_secs` (default 1800) | Raise timeout or split tests |
| RLOGGER-EXEC-DEPS-MISSING | 1 | Per-runtime dependency directory absent before phase invocation: `node_modules/` for TypeScript (AC-28-37), `vendor/` for PHP (AC-28-39). Go is not subject to this code (modules cache lives outside the repo). The CLI MUST NOT install dependencies implicitly. | Run `<pm> install` (TS — `npm`/`pnpm`/`bun`/`yarn`) or `composer install` (PHP) before re-invoking the phase. |

## Push (transport)

| Code | Exit | Cause | Caller action |
|------|-----:|-------|---------------|
| RLOGGER-PUSH-NO-SHA | 2 | `GitSha256` could not be resolved | Pass `--git-sha` or run inside CI |
| RLOGGER-PUSH-RETRIES-EXHAUSTED | 4 | All `max_retries` POSTs failed (5xx/network) | Check server logs / network |
| RLOGGER-PUSH-BAD-RESPONSE | 4 | Server returned 2xx but body could not be parsed as ack envelope | Server/CLI version mismatch — check changelog |
| RLOGGER-PUSH-STREAM-BROKEN | 4 | Streaming connection dropped mid-flight after retry | Switch to batched mode for unstable links |
| RLOGGER-PUSH-PAYLOAD-TOO-LARGE | 4 | Server returned 413 | Lower `batch_max_bytes` or split phases |
| RLOGGER-STREAM-MALFORMED | 4 | While `--stream` is active, the server closed the chunked connection mid-frame (TCP reset, HTTP/2 GOAWAY, or NDJSON parse error reported by the server as `400 RLOGGER-STREAM-MALFORMED` per AC-28-26). Distinct from `RLOGGER-PUSH-STREAM-BROKEN`: that code indicates the underlying connection dropped after retries; this code indicates the server actively rejected the stream framing. | Re-invoke; if persistent, switch to batched mode (`shipping.mode=batched`) and capture the malformed frame for server-side debugging. |

## Auth (server-surfaced; exit 3)

These are NOT new codes — they are forwarded verbatim:

| Forwarded code | Source |
|----------------|--------|
| `GL-AUTH-TEMPTOKEN-INVALID` | §22/15 |
| `GL-AUTH-TOKEN-MISMATCH` | §22/15 |
| `GL-AUTH-PROFILE-INACTIVE` | §22/15 |
| `GL-AUTH-LANE-DISABLED` | §22/15 |
| `GL-SSH-*` (entire family) | §22/15 |
| `GL-VALIDATION-*` | §22/15 |
| `GL-APP-NOT-ACTIVE` | §22/15 |

CLI prints both: its own context line then the verbatim server envelope.

---

## Output Format

Every error printed by the CLI uses this exact shape on stderr:

```
rlogger: error
  Code:    RLOGGER-PUSH-RETRIES-EXHAUSTED
  Exit:    4
  Phase:   ts-test
  Server:  https://example.com/wp-json/git-logs/v2
  Cause:   POST /append-log failed 3 times (last: 502 Bad Gateway)
  Action:  Check server logs / network
```

In `--json` mode:

```json
{
  "Code": "RLOGGER-PUSH-RETRIES-EXHAUSTED",
  "Exit": 4,
  "Phase": "ts-test",
  "Server": "https://example.com/wp-json/git-logs/v2",
  "Cause": "POST /append-log failed 3 times (last: 502 Bad Gateway)",
  "Action": "Check server logs / network"
}
```
