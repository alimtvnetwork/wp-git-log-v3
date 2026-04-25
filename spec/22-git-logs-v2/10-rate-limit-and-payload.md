# Rate Limit and Payload Caps (v2)

**Version:** 2.0.0  
**Updated:** 2026-04-25

Carries forward the v1 caps with two adjustments: scope is **per-Profile** (not per-Repo) and the cap is **configurable via `ConfigKv`** so ops can tune without redeploying.

---

## Caps

| Concern | Default | ConfigKv key | Enforced where |
|---------|---------|--------------|----------------|
| Push payload size | 1 MiB (1048576 bytes) | `MaxPushPayloadBytes` | Before body parse; reject if `Content-Length` exceeds. |
| Push rate | 60 requests/minute per Profile | `RatePerMinPerProfile` | Token-bucket in WP transients keyed by `ProfileId`. |
| Streaming chunk size | unlimited individual chunks; total still capped by `MaxPushPayloadBytes` | — | Streaming reader keeps a running byte counter. |
| Logs[]/ErrorLogs[] line count per request | 10000 | `MaxLinesPerPush` | After body parse; reject if exceeded. |
| Single log line length | 64 KiB | `MaxLineBytes` | Per-line check while streaming; over-limit lines are truncated and tagged with `LogSeverity=Warn`. |

---

## Token bucket (per Profile)

- **Capacity**: `RatePerMinPerProfile` (default 60).
- **Refill rate**: capacity / 60 tokens per second.
- **Storage**: WP transient `gitlogs_rate_{ProfileId}` holding `{ Tokens: float, LastRefillAt: unix }`.
- **Algorithm**:
  1. Read transient (default to full bucket if missing).
  2. Refill: `Tokens = min(capacity, Tokens + (now - LastRefillAt) * rate)`.
  3. If `Tokens >= 1`: decrement, write back, allow.
  4. Else: reject `GL-RATE-LIMIT-EXCEEDED` with HTTP 429 and `Retry-After: ceil((1 - Tokens) / rate)`.

The bucket is **per Profile**, not per Repo, because Profile is the credential boundary. A Profile pushing for many repos shares one bucket.

---

## Error responses

| Code | HTTP | When |
|------|------|------|
| GL-PAYLOAD-TOO-LARGE | 413 | `Content-Length > MaxPushPayloadBytes` |
| GL-RATE-LIMIT-EXCEEDED | 429 | Token bucket empty; `Retry-After` header set |
| GL-LINES-TOO-MANY | 413 | `len(Logs) + len(ErrorLogs) > MaxLinesPerPush` |
| GL-LINE-TOO-LONG | n/a (silent truncation + Warn) | Per-line; line length > `MaxLineBytes` |

Every reject writes `AuditTrail` with `AuditActionType=LogPush, AuditOutcome=Rejected` and the offending size in `Detail`.

---

## Read endpoints

Read endpoints (`/get-*`) are **not** rate-limited at the token-bucket layer; they rely on WP App Password / cookie auth and the `HistoryView` permission. Operators may layer a reverse-proxy rate limit if needed.
