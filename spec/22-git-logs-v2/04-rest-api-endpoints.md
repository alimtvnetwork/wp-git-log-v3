# REST API Endpoints (v2)

**Version:** 2.9.2  
**Updated:** 2026-04-26 (Phase 8: NDJSON streaming retrieval format defined for endpoints #5/#6/#7/#8/#9/#10 — opt-in via `Accept: application/x-ndjson`, frame schema, error-mid-stream handling, backpressure & flush cadence, sentinel close frame)
**Namespace:** `/wp-json/git-logs/v2`

---

## Common

- All write endpoints respond with a structured acknowledgement and a retrieval hint.
- All statuses/kinds/types are Enum-backed (no magic strings).
- All endpoint hits write to `AuditTrail`; no error is swallowed.

### Standard Ack Envelope

```json
{
  "Status": "Success",
  "Message": "Logs appended.",
  "TraceId": "uuid-v4",
  "Retrieval": {
    "Logs": "/wp-json/git-logs/v2/get-logs",
    "ErrorLogs": "/wp-json/git-logs/v2/get-error-logs",
    "Pipeline": "/wp-json/git-logs/v2/get-pipeline-logs"
  }
}
```

### Standard Error Envelope

```json
{
  "Status": "Rejected",
  "ErrorCode": "GL-VALIDATION-001",
  "Message": "RepoUrl does not match GitProfile acceptance.",
  "TraceId": "uuid-v4"
}
```

---

## Endpoint Map

| # | Method | Path | Purpose | Auth |
|---|--------|------|---------|------|
| 1 | POST | `/append-log` | Append/stream log lines | TempToken + URL/Branch |
| 2 | PUT  | `/fixed-log` | Mark pipeline error cleared | TempToken + URL/Branch |
| 3 | POST | `/clear-log` | Clear logs for a single pipeline | TempToken + URL/Branch |
| 4 | POST | `/clear-log-all` | Clear all logs for repo+branch | TempToken + URL/Branch |
| 5 | GET  | `/get-logs` | All logs for repo at commit | App Password / Cookie |
| 6 | GET  | `/get-logs?q=…` | URL-style variant of #5 | App Password / Cookie |
| 7 | GET  | `/get-pipeline-logs` | One pipeline's logs at commit | App Password / Cookie |
| 8 | GET  | `/get-pipeline-logs?q=…` | URL-style variant of #7 | App Password / Cookie |
| 9 | GET  | `/get-error-logs` | Error logs across all pipelines | App Password / Cookie |
| 10 | GET | `/get-pipeline-error-logs` | Error logs for one pipeline | App Password / Cookie |

> **Logical-endpoint vs HTTP-path count.** This map lists **10 logical endpoints** but they collapse to **8 HTTP paths** in `17-openapi.yaml`. Rows #5/#6 share path `/get-logs` (the `?q=…` variant is a query parameter, not a separate route); same for #7/#8 sharing `/get-pipeline-logs`. AC-11 ("all 10 endpoints exist with the exact request/response field names") is satisfied by the 8-path OpenAPI document because the `q` query parameter is documented on the parent path. Implementations MUST handle both shapes (explicit `RepoUrl`+`Branch` AND shorthand `q=…`) inside the same route handler. Adding a brand-new logical endpoint requires either a new path or a new query-param shape — never silent overloading of an existing path.

---

## 1. POST /append-log

**Body**
```json
{
  "RepoUrl": "https://github.com/alimtvnetwork/macro-ahk-v23",
  "RootRepo": "https://github.com/alimtvnetwork/macro-ahk",
  "Branch": "main",
  "TempToken": "…",
  "Token": "…",
  "PipelineName": "build",
  "GitSha256": "abc123…",
  "Logs": ["line 1", "line 2"],
  "ErrorLogs": ["err 1"],
  "FilePaths": ["src/foo.ts"],
  "HasError": true
}
```
- **Streaming**: `Transfer-Encoding: chunked` accepted; lines processed incrementally.
- **Effect**: insert `LogEntry` + `ErrorLogEntry` rows; if `HasError=true`, set `Pipeline.HasError=1`. Append `History` (ActionType=`Append`) and `Action` row.
- **Response**: standard ack with `Retrieval`.

## 2. PUT /fixed-log

**Body**
```json
{ "RepoUrl": "...", "Branch": "main", "TempToken": "...", "RootRepo": "...", "Token": "...", "PipelineName": "build" }
```
- **Effect**: `Pipeline.HasError=0`. History `ActionType=Fixed`, Action row inserted.

## 3. POST /clear-log

**Body** identical to #2.
- **Effect**: delete `LogEntry`+`ErrorLogEntry` for `(Pipeline)`. History `ActionType=Clear`.

## 4. POST /clear-log-all

**Body** (no `PipelineName`)
```json
{ "RepoUrl": "...", "Branch": "...", "TempToken": "...", "RootRepo": "...", "Token": "..." }
```
- **Effect**: delete logs for all pipelines on `(RepoVersion, Branch)`. History `ActionType=ClearAll`.

---

## 5. GET /get-logs

**Body**
```json
{ "RepoUrl": "...", "GitSha256": "..." }
```
**Response**
```json
{
  "RepoUrl": "...",
  "RootRepo": "...",
  "BranchName": "main",
  "PipelineNames": ["build", "test"],
  "IsPass": false,
  "HasError": true,
  "ErrorLogs": [{ "PipelineName": "build", "LogText": "..." }],
  "Logs":      [{ "PipelineName": "build", "LogText": "..." }]
}
```

## 6. GET /get-logs?q=github.com/{org}/{repo}

Same response as #5; repo from query string, body carries only `GitSha256`.

## 7. GET /get-pipeline-logs

**Body**: `{ RepoUrl, GitSha256, PipelineName }`  
**Response**
```json
{
  "RepoUrl": "...", "RootRepo": "...", "BranchName": "main", "PipelineName": "build",
  "IsPass": false, "HasError": true,
  "ErrorLogs": ["err 1"],
  "Logs":      ["line 1", "line 2"]
}
```

## 8. GET /get-pipeline-logs?q=github.com/{org}/{repo}

Same response as #7; repo from query string; body `{ GitSha256, PipelineName }`.

## 9. GET /get-error-logs

**Body**: `{ RepoUrl, GitSha256 }`
**Response**
```json
{
  "RepoUrl": "...", "RootRepo": "...", "BranchName": "main",
  "PipelineNames": ["build", "test"],
  "IsPass": false, "HasError": true,
  "ErrorLogs": [{ "PipelineName": "build", "LogText": "..." }]
}
```

## 10. GET /get-pipeline-error-logs

**Body**: `{ RepoUrl, GitSha256, PipelineName }`
**Response**
```json
{
  "RepoUrl": "...", "RootRepo": "...", "BranchName": "main", "PipelineName": "build",
  "IsPass": false, "HasError": true,
  "ErrorLogs": ["err 1"]
}
```
