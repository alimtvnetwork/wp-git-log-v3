# Log Shipping Contract

**Version:** 1.0.0  
**Updated:** 2026-04-25  
**Server contract:** [`spec/22-git-logs-v2/04-rest-api-endpoints.md`](../22-git-logs-v2/04-rest-api-endpoints.md)

The CLI is a *client* of Git Logs v2. Wire shapes here MUST stay byte-compatible with the server spec — when they conflict, the server wins and this file is patched.

---

## Endpoint Mapping

| CLI event | HTTP | v2 endpoint | Trigger |
|-----------|------|-------------|---------|
| Phase finished, logs to deliver | `POST` | `/append-log` | Always (unless `--no-push`) |
| Phase passed AND server says previous run had `HasError=1` | `PUT`  | `/fixed-log` | Automatic post-success check |
| `glci clear --pipeline X` | `POST` | `/clear-log` | Manual |
| `glci clear --all` | `POST` | `/clear-log-all` | Manual |
| `glci doctor` reachability ping | `GET`  | `/get-logs?q=<repo>&limit=0` | Pre-flight only |

`/get-pipeline-logs`, `/get-error-logs`, `/get-pipeline-error-logs` are read endpoints owned by humans/admin UI; the CLI does NOT call them in any phase.

---

## Batched Mode (default)

One POST per `(Runtime, Phase)`. Body = full payload from §22 `/append-log`:

```json
{
  "RepoUrl":      "https://github.com/org/repo",
  "RootRepo":     "https://github.com/org/repo",
  "Branch":       "main",
  "TempToken":    "…",
  "Token":        "…",
  "PipelineName": "ts-test",
  "GitSha256":    "abc123…",
  "Logs":         ["line 1", "line 2", "..."],
  "ErrorLogs":    ["FAIL test/foo.spec.ts > should compile"],
  "FilePaths":    ["test/foo.spec.ts"],
  "HasError":     true
}
```

Field rules:

- `Logs[]` cap: `batch_max_bytes` (default 1 MiB) total UTF-8. Lines beyond cap are dropped and `ErrorLogs[]` gains a synthetic `"GLCI: log truncated, N lines dropped"`.
- `ErrorLogs[]` cap: same; dropped lines counted in same synthetic line.
- `FilePaths[]` derived from §09 classifier; deduplicated; max 100 entries.
- `HasError` is **true iff** `ErrorLogs[]` is non-empty OR runner exit code ≠ 0.
- `GitSha256` must be a real commit SHA — when unset, exit `2` with `GLCI-PUSH-NO-SHA`.

Retry on `5xx`/network: exponential backoff per `push.backoff_ms`. After `max_retries`, exit `4` with `GLCI-PUSH-RETRIES-EXHAUSTED`.

Retry on `4xx`: **never**. Surface server's `ErrorCode` directly and exit `3`.

---

## Streaming Mode (`--stream`)

```http
POST /append-log HTTP/1.1
Transfer-Encoding: chunked
Content-Type: application/x-ndjson
X-GL-Stream: 1

{"RepoUrl":"…","Branch":"…","TempToken":"…","Token":"…","PipelineName":"ts-test","GitSha256":"…","StreamHeader":true}
{"Line":"line 1"}
{"Line":"line 2"}
{"Line":"FAIL test/foo.spec.ts","IsError":true,"FilePath":"test/foo.spec.ts"}
{"StreamFooter":true,"HasError":true}
```

- First chunk MUST carry `StreamHeader=true` plus identity fields.
- Each subsequent chunk MUST be one valid JSON object terminated by `\n`.
- Final chunk MUST carry `StreamFooter=true` plus the resolved `HasError`.
- Server returns standard ack envelope after the final chunk; CLI parses and treats it identically to batched mode.
- Buffer cap: `max_buffer_lines` lines kept in memory; if the channel blocks (slow server), oldest non-error lines are dropped first and a `"GLCI: stream backpressure dropped N lines"` synthetic line is inserted before the next sent chunk.

Streaming and batched modes are mutually exclusive per invocation. Mixing per-phase is not supported in v1.

---

## `/fixed-log` Auto-Detection

After a phase passes, the orchestrator MAY emit `PUT /fixed-log` iff:

1. The previous run for `(RepoUrl, Branch, PipelineName)` had `HasError=1`, AND
2. The current run had zero `ErrorLogs[]`, AND
3. The runner exit code is 0.

Discovery of "previous run had `HasError=1`" uses the **server response** to the most recent `/append-log` POST — the v2 server's ack envelope SHOULD include `PreviousHasError` per `spec/22-git-logs-v2/04-rest-api-endpoints.md`. If the server omits this field, the CLI does NOT call `/fixed-log` (no local cache).

> **Open server-side dependency:** v2 ack envelope currently lacks `PreviousHasError`. Tracked as gap **GAP-22-04** in [`spec/22-git-logs-v2/99-consistency-report.md`](../22-git-logs-v2/99-consistency-report.md).

---

## Auth Lane Selection

Maps directly to v2 `X-GL-Auth-Mode`:

| `auth_mode` | Headers | Body fields | v2 lane |
|-------------|---------|-------------|---------|
| `temptoken` | _(none added)_ | `TempToken`, `Token` | Lane B / TempToken sub-mode |
| `ssh` | `X-GL-Auth-Mode: ssh`, `X-GL-Fingerprint`, `X-GL-Timestamp`, `X-GL-Nonce`, `X-GL-Signature` | `TempToken`/`Token` MUST NOT appear (else server returns `GL-SSH-LANE-CONFLICT`) | Lane B / SSH sub-mode |

Signing string for SSH mode is `GL-SSHSIG-V1` per `spec/22-git-logs-v2/05-auth-and-validation.md` step 8 — the CLI shells out to `ssh-keygen -Y sign -n git-logs@v2` for portability.

---

## Determinism

Two consecutive `glci run` invocations on the same commit, same env, same source MUST emit byte-identical request bodies (modulo `GitSha256` if HEAD changed). Specifically:

- `Logs[]` order reflects exec capture order, not goroutine scheduling.
- `FilePaths[]` is sorted lexicographically.
- JSON key order is insertion order from the typed struct (Go default with `encoding/json`).

Tests in `16-test-plan.md` (TODO — added in v1.1) MUST assert this property.
