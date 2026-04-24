# Log Retrieval Flow

**Version:** 1.0.0  
**Updated:** 2026-04-25  
**Status:** Active  
**AI Confidence:** Production-Ready  
**Ambiguity:** Low

---

## Purpose

Defines how authenticated callers navigate the `Repository → Branch → Pipeline → LogEntry` hierarchy. Covers query DSL, pagination, sort, ACL, and the standard response envelope. Endpoint contracts in [`04-rest-api-endpoints.md` §4.4](./04-rest-api-endpoints.md); audit semantics in [`10-audit-trail.md`](./10-audit-trail.md).

---

## 1. Hierarchy

```
Repository (allowlisted)
   └── Branch (distinct values of Pipeline.BranchName for the repo)
         └── Pipeline (PipelineName within branch)
               └── LogEntry (one per CI line)
```

Branches are not their own table — they are the distinct `Pipeline.BranchName` values for a given `RepositoryId`.

---

## 2. Endpoints (Recap)

| # | Route | Description |
|---|---|---|
| E12 | `GET /repositories/{id}/branches` | List distinct branch names |
| E13 | `GET /repositories/{id}/branches/{branch}/pipelines` | List pipelines on a branch |
| E14 | `GET /pipelines/{id}/entries` | List entries within a pipeline |
| E15 | `GET /logs/search` | Cross-repo full-text search |

All require `JwtBearer` with `Admin` ∪ `CanViewLogs`.

---

## 3. ACL

A caller may read a repository iff one of:

1. The caller has `Admin`.
2. The caller has `CanViewLogs` AND the repository is `RepositoryStatus::Active`.

`Disabled` repositories are still visible to `Admin` for forensic inspection. Listing endpoints (E05 `/repositories`) filter out `Disabled` for non-admins.

---

## 4. Pagination

Cursor pagination per [`04` §3](./04-rest-api-endpoints.md):

| Param | Default | Max | Notes |
|---|---|---|---|
| `pageSize` | 50 | 200 | |
| `cursor` | — | — | Opaque base64url string |
| `sort` | `OccurredAt:desc` (entries), `BranchName:asc` (branches) | — | Comma-separated `field:direction` |

The cursor encodes `{ lastSortKey, lastId }` for stable ordering. Server-side, the cursor is HMAC-signed (key derived from `AUTH_KEY`) so clients cannot tamper.

---

## 5. Filters

### 5.1 `GET /pipelines/{id}/entries`

| Param | Type | Maps to |
|---|---|---|
| `severity` | enum CSV | `LogSeverity.Code IN (…)` |
| `from`, `to` | RFC3339 | `OccurredAt` range |
| `commitSha` | string | `CommitSha = ?` |
| `q` | string ≤ 200 | LIKE search on `Message` (server-side, indexed via fulltext if available) |

### 5.2 `GET /logs/search`

| Param | Type | Notes |
|---|---|---|
| `q` | string, required, 1–200 | Required; rejects empty |
| `repositoryIds` | int CSV | Restricts scope; server further restricts by ACL |
| `severity`, `from`, `to`, `commitSha` | as §5.1 | |
| `pageSize` | as §4 | Hard max 100 here (search is heavier) |

Search uses the same view (`VwLogEntryDetail`) joined with `Repository` to enforce ACL in a single query.

---

## 6. Response Shape

### 6.1 Branches (`E12`)

```json
{
  "Status": "ok",
  "Attributes": { "SessionId": "...", "NextCursor": null, "HasMore": false },
  "Results": [
    { "BranchName": "main", "PipelineCount": 7, "LastEntryAt": "2026-04-25T11:50:00Z" },
    { "BranchName": "feature/x", "PipelineCount": 2, "LastEntryAt": "2026-04-24T09:10:00Z" }
  ],
  "Errors": null,
  "MethodsStack": null
}
```

### 6.2 Pipelines (`E13`)

```json
{
  "Status": "ok",
  "Attributes": { "SessionId": "...", "NextCursor": null, "HasMore": false },
  "Results": [
    { "PipelineId": 17, "PipelineName": "build", "IsActive": true, "EntryCount": 1245, "LastEntryAt": "2026-04-25T11:50:00Z" }
  ],
  "Errors": null
}
```

### 6.3 Entries (`E14`)

```json
{
  "Status": "ok",
  "Attributes": { "SessionId": "...", "NextCursor": "eyJ...", "HasMore": true },
  "Results": [
    {
      "LogEntryId": 9000123,
      "Severity": "Error",
      "Message": "Compilation failed",
      "CommitSha": "a1b2c3d4",
      "OccurredAt": "2026-04-25T11:49:50Z",
      "MetadataJson": { "Job": "build", "Step": "compile" }
    }
  ],
  "Errors": null
}
```

---

## 7. Performance Constraints

| Concern | Bound |
|---|---|
| Entry list per page | ≤ 200 rows |
| Search results per page | ≤ 100 rows |
| Server-side timeout | 10 s; exceed → `504 GL-SYS-002` |
| Index used for entries | `IdxLogEntry_PipelineOccurred` |
| Index used for search | Fulltext on `LogEntry.Message` (when available) + `IdxLogEntry_PipelineOccurred` |

---

## 8. Failure Modes

| Code | HTTP | Reason |
|---|---|---|
| `GL-AUTH-001` | 401 | Missing/invalid JWT |
| `GL-AUTH-005` | 403 | Caller lacks `CanViewLogs` |
| `GL-VAL-001` | 400 | Invalid query parameter (e.g., bad enum, malformed cursor) |
| `GL-VAL-002` | 400 | Empty `q` on `/logs/search` |
| `GL-NF-001` | 404 | Repository or pipeline not found |
| `GL-RATE-001` | 429 | Read rate limit exceeded |
| `GL-SYS-002` | 504 | Query timeout |

---

## 9. Audit

Every successful read writes one `AuditTrail (LogQuery, Success)` row with `RepositoryId` populated and `DetailsJson.queryParams` (sanitized — no full `q` text persisted; `q` is hashed for trend analytics).

---

## 10. Acceptance Criteria

| ID | Given | When | Then |
|---|---|---|---|
| AC-RET-01 | Caller with `CanViewLogs` and active repo | `GET /pipelines/{id}/entries` | Returns 200 with rows |
| AC-RET-02 | Same caller, repo `Disabled` | Same | Returns `404 GL-NF-001` (not 403, to avoid existence oracle) |
| AC-RET-03 | `Admin` caller | Same | Returns 200 even when repo is `Disabled` |
| AC-RET-04 | Two consecutive paginated calls | Same | Second call's first row equals immediately after first call's last |
| AC-RET-05 | `pageSize=500` requested | Same | Server clamps to 200 and indicates via `Attributes.PageSizeApplied=200` |
| AC-RET-06 | `from` after `to` | Same | Returns `400 GL-VAL-001` |
| AC-RET-07 | Tampered cursor | Same | Returns `400 GL-VAL-001` |
| AC-RET-08 | Query exceeds 10 s | Same | Returns `504 GL-SYS-002`, `AuditTrail (LogQuery, Error)` written |
| AC-RET-09 | Successful read | Same | One `AuditTrail (LogQuery, Success)` row exists |
| AC-RET-10 | `/logs/search` with empty `q` | `GET /logs/search?q=` | Returns `400 GL-VAL-002` |

---

## 11. Cross-References

| Reference | Location |
|---|---|
| Endpoint catalog | [04-rest-api-endpoints.md](./04-rest-api-endpoints.md) §4.4 |
| Audit trail | [10-audit-trail.md](./10-audit-trail.md) |
| Error envelope | [11-error-management.md](./11-error-management.md) |
| Database views | [02-database-schema-and-erd.md](./02-database-schema-and-erd.md) §6 |
| Glossary & enums | [01-glossary-and-enums.md](./01-glossary-and-enums.md) |
