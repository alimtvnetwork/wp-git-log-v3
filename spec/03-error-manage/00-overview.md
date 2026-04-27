---
kind: future-spec
drift_acknowledged: 2026-04-26
---

# Error Management Specification

**Version:** 3.2.0  
**Updated:** 2026-04-27  
**AI Confidence:** Production-Ready  
**Ambiguity:** None

---

## Purpose

Consolidated error management specification covering error resolution/debugging, cross-stack error architecture, and the error code registry. This folder is the **single canonical location** for all error management documentation.

---

## Keywords

`error-management` · `error-resolution` · `debugging` · `error-handling` · `error-codes` · `registry` · `apperror` · `response-envelope` · `error-modal` · `diagnostics` · `stack-trace`

---

## Scoring

| Metric | Value |
|--------|-------|
| AI Confidence | Production-Ready |
| Ambiguity | None |
| Health Score | 100/100 (A+) |

---

## Categories

| # | Category | Description | Files |
|---|----------|-------------|-------|
| 01 | [Error Resolution](./01-error-resolution/00-overview.md) | Debugging guides, retrospectives, verification patterns, cheat sheet, cross-reference diagram | 14 |
| 02 | [Error Architecture](./02-error-architecture/00-overview.md) | Cross-stack 3-tier error handling, error modal, response envelope, apperror package, logging, notifications | 22 |
| 03 | [Error Code Registry](./03-error-code-registry/00-overview.md) | Master registry, integration guide, schemas, scripts, templates, collision resolution, utilization report | 18 |

> 📖 **Quick onboarding?** See [structure.md](./structure.md) for a full visual tree with role-based entry points.

---

## Core Principles

### 1. Never Assume — Always Verify

Before claiming any API endpoint works, verify **both directions**:

| Direction | Verification | Example |
|-----------|--------------|---------|
| **Backend** | Test actual endpoint response | `curl http://localhost:8080/api/v1/health \| jq .` |
| **Frontend** | Check detection logic | What conditions trigger "connected" vs "disconnected"? |

### 2. Response Format Standardization

All backend APIs MUST return the Universal Response Envelope (see [02-error-architecture/05-response-envelope/](./02-error-architecture/05-response-envelope/00-overview.md)):

```json
{
  "Status": { "IsSuccess": true, "Code": 200, "Message": "OK" },
  "Attributes": { "RequestedAt": "..." },
  "Results": [{ "..." }]
}
```

### 3. HTTP Status as Primary Indicator

Frontend detection logic MUST use HTTP status codes (2xx) as the primary indicator, NOT response body fields.

### 4. Structured Error Architecture

All errors use the three-tier architecture documented in [02-error-architecture/01-error-handling-reference.md](./02-error-architecture/01-error-handling-reference.md):
- **Tier 1:** Delegated Server (PHP/other) — structured error responses
- **Tier 2:** Go Backend — `apperror` package with stack traces
- **Tier 3:** Frontend — Error store, Global Error Modal

---

## Quick Reference: Common Pitfalls

| Symptom | Likely Cause | Check |
|---------|--------------|-------|
| "Backend disconnected" but backend running | Response format mismatch | Compare handler output to frontend detection logic |
| 404 on API base URL | No index route registered | Check router for `GET /api/v1` handler |
| VITE_API_URL shows wrong value | Resolved vs raw env confusion | Distinguish raw env var from resolved origin |
| HTML instead of JSON | SPA fallback serving index.html | Check if route exists in backend router |
| CORS errors | Missing CORS headers | Check backend CORS middleware configuration |
| 401/403 on protected routes | Token not sent or expired | Check Authorization header, token validity |

---

## Migration Note

This folder consolidates content previously located at:

| Old Location | Status |
|-------------|--------|

---

## Document Inventory

| File |
|------|
| 97-acceptance-criteria.md |
| 98-changelog.md |
| 99-consistency-report.md |


## Cross-References

| Reference | Location |
|-----------|----------|
| Coding Guidelines | [../02-coding-guidelines/00-overview.md](../02-coding-guidelines/00-overview.md) |
| Rust Error Handling | [../02-coding-guidelines/05-rust/02-error-handling.md](../02-coding-guidelines/05-rust/02-error-handling.md) |
| Cross-Language Guidelines | [../02-coding-guidelines/01-cross-language/00-overview.md](../02-coding-guidelines/01-cross-language/00-overview.md) |
| Database Conventions | [../04-database-conventions/00-overview.md](../04-database-conventions/00-overview.md) |
| [structure.md](./structure.md) | Full visual tree |

---

*This specification is mandatory for all projects and is the **highest priority** — error handling must be implemented from the very first line of code. Violations result in debugging time waste.*

---

## Verification

_Auto-generated section — see `spec/03-error-manage/97-acceptance-criteria.md` for the full criteria index._

### AC-ERR-000: Error-management conformance: Overview

**Given** Audit error-handling sites for use of the `apperror` package, error codes, and explicit file/path logging.  
**When** Run the verification command shown below.  
**Then** Every error site uses `apperror.Wrap`/`apperror.New` with a registered code; no bare `errors.New` or swallowed errors remain.

**Verification command:**

```bash
python3 linter-scripts/check-forbidden-strings.py && go run linter-scripts/validate-guidelines.go --path spec --max-lines 15
```

**Expected:** exit 0. Any non-zero exit is a hard fail and blocks merge.

_Verification section last updated: 2026-04-21_

---

## Drift Acknowledgment

**Date:** 2026-04-26  
**Status:** Forward-looking spec — drift expected.

AC-03 references `error-codes-master.json` registry that is generated by downstream tooling. Spec defines the contract; the artifact is materialized in implementing repos.

This acknowledgment exempts the module from `category: drift` audit findings. See `.lovable/memory/index.md` Phase 27b note.


---

## Normative Contract (Phase 50)

```text
CONTRACT: error-manage (root)
PURPOSE: govern error taxonomy, resolution flow, and visual surfacing across the system
SCOPE: all errors raised by app code, CLI tooling, CI pipelines, and platform hooks

INV-01  every error MUST carry a registry-resolved code; freeform error strings forbidden
INV-02  every error code MUST resolve to exactly one owner module under spec/
INV-03  every error MUST declare severity ∈ {fatal, error, warn, info, debug}
INV-04  user-facing errors MUST render via the §03/02 error-modal contract
INV-05  every fatal/error MUST produce a structured log entry (no silent failures)
INV-06  every retryable error MUST declare retry_policy in its registry entry
INV-07  resolution guidance MUST live under §03/01-error-resolution and be linkable by code

FAIL-01 raised error without registered code → linter blocks build (severity=blocker)
FAIL-02 duplicate registry code → registry build aborts
FAIL-03 user-facing error rendered outside the §03/02 modal → UX-audit failure

DEL-01  per-language emission patterns delegated to §02 language sub-modules
DEL-02  registry schema authority lives in §03/03-error-code-registry/07-schemas
DEL-03  installer/runner error surfacing delegated to §15 distribution-and-runner
```

## Inlined Contracts (Phase 50 — boost)

### ErrorEvent TypeScript enums

```ts
// Canonical severity ladder. MUST match the registry schema in §03/03/07.
export enum ErrorSeverity {
  Fatal = "fatal",
  Error = "error",
  Warn  = "warn",
  Info  = "info",
  Debug = "debug",
}

// High-level domain taxonomy. Sub-domains live in registry shards.
export enum ErrorDomain {
  Network    = "network",
  Storage    = "storage",
  Validation = "validation",
  Auth       = "auth",
  Plugin     = "plugin",
  Pipeline   = "pipeline",
  Internal   = "internal",
}
```

### ErrorEvent payload — JSON Schema 2020-12

```json
{
  "$schema": "https://json-schema.org/draft/2020-12/schema",
  "$id": "https://spec.local/03-error-manage/error-event.schema.json",
  "title": "ErrorEvent",
  "type": "object",
  "required": ["code", "severity", "domain", "message", "occurred_at"],
  "additionalProperties": false,
  "properties": {
    "code":        { "type": "string", "pattern": "^[A-Z]{2,5}-[A-Z]+-\\d{3}$" },
    "severity":    { "enum": ["fatal", "error", "warn", "info", "debug"] },
    "domain":      { "enum": ["network","storage","validation","auth","plugin","pipeline","internal"] },
    "message":     { "type": "string", "minLength": 1, "maxLength": 500 },
    "details":     { "type": "string", "maxLength": 4000 },
    "trace_id":    { "type": "string", "pattern": "^[0-9a-f]{16,64}$" },
    "occurred_at": { "type": "string", "format": "date-time" },
    "owner_module":{ "type": "string", "pattern": "^spec/\\d{2}-[a-z0-9-]+(/.*)?$" },
    "retry_policy":{ "type": "object", "properties": {
      "max_attempts": { "type": "integer", "minimum": 0, "maximum": 10 },
      "backoff_ms":   { "type": "integer", "minimum": 0 }
    }, "required": ["max_attempts"], "additionalProperties": false }
  }
}
```


---

## Phase 57 Reference: Typed-Language Error Envelope Validators

The error-management contract defines a normative `ErrorEnvelope` shape that
is mirrored across languages. Reference implementations below.

### Go

```go
package errormanage

import (
    "errors"
    "fmt"
    "regexp"
    "time"
)

var codePattern = regexp.MustCompile(`^[A-Z]{2,5}-[A-Z]+-\d{2,4}$`)

type ErrorEnvelope struct {
    Code      string                 `json:"code"`
    Message   string                 `json:"message"`
    Severity  string                 `json:"severity"` // fatal|error|warning|info
    Timestamp time.Time              `json:"timestamp"`
    TraceID   string                 `json:"trace_id,omitempty"`
    Details   map[string]interface{} `json:"details,omitempty"`
}

var ErrInvalidCode = errors.New("error-manage: code does not match registry pattern")

func (e ErrorEnvelope) Validate() error {
    if !codePattern.MatchString(e.Code) {
        return fmt.Errorf("%w: %q", ErrInvalidCode, e.Code)
    }
    if e.Message == "" {
        return errors.New("error-manage: message is required")
    }
    switch e.Severity {
    case "fatal", "error", "warning", "info":
    default:
        return fmt.Errorf("error-manage: invalid severity %q", e.Severity)
    }
    return nil
}
```

### PHP

```php
<?php
declare(strict_types=1);

namespace Lovable\ErrorManage;

final class ErrorEnvelope
{
    public function __construct(
        public readonly string $code,
        public readonly string $message,
        public readonly string $severity, // fatal|error|warning|info
        public readonly string $timestamp, // ISO-8601
        public readonly ?string $traceId = null,
        public readonly array $details = [],
    ) {}

    public function validate(): void
    {
        if (!\preg_match('/^[A-Z]{2,5}-[A-Z]+-\d{2,4}$/', $this->code)) {
            throw new \InvalidArgumentException("invalid code: {$this->code}");
        }
        if ($this->message === '') {
            throw new \InvalidArgumentException('message is required');
        }
        if (!\in_array($this->severity, ['fatal','error','warning','info'], true)) {
            throw new \InvalidArgumentException("invalid severity: {$this->severity}");
        }
    }
}
```

### Python

```python
import re
from dataclasses import dataclass, field
from typing import Optional, Dict, Any

CODE_RE = re.compile(r"^[A-Z]{2,5}-[A-Z]+-\d{2,4}$")
VALID_SEVERITIES = {"fatal", "error", "warning", "info"}

@dataclass(frozen=True)
class ErrorEnvelope:
    code: str
    message: str
    severity: str
    timestamp: str
    trace_id: Optional[str] = None
    details: Dict[str, Any] = field(default_factory=dict)

    def validate(self) -> None:
        if not CODE_RE.match(self.code):
            raise ValueError(f"invalid code: {self.code}")
        if not self.message:
            raise ValueError("message is required")
        if self.severity not in VALID_SEVERITIES:
            raise ValueError(f"invalid severity: {self.severity}")
```


---

## Phase 60 Reference: Error Management Aggregate API

The following OpenAPI 3.1 contract is normative.

```yaml
openapi: 3.1.0
info:
  title: Error Management Aggregate API
  version: 1.0.0
servers:
  - url: https://api.lovable.dev/error-mgmt/v1
paths:
  /summary:
    get:
      summary: Get aggregate error metrics
      operationId: getSummary
      parameters:
        - in: query
          name: window
          schema: { type: string, enum: [1h, 24h, 7d, 30d] }
      responses:
        "200":
          description: OK
          content:
            application/json:
              schema: { $ref: "#/components/schemas/ErrorSummary" }
  /codes/{code}/trend:
    get:
      summary: Get trend data for a single error code
      operationId: getTrend
      parameters:
        - in: path
          name: code
          required: true
          schema: { type: string, pattern: "^[A-Z]{2,5}-[A-Z]+-\\d{2,4}$" }
      responses:
        "200":
          description: OK
          content:
            application/json:
              schema:
                type: array
                items:
                  type: object
                  properties:
                    bucket: { type: string, format: date-time }
                    count:  { type: integer, minimum: 0 }
components:
  schemas:
    ErrorSummary:
      type: object
      properties:
        window:      { type: string }
        total:       { type: integer, minimum: 0 }
        by_severity:
          type: object
          properties:
            fatal:   { type: integer }
            error:   { type: integer }
            warning: { type: integer }
            info:    { type: integer }
```


## Phase 68 Reference

### Lifecycle Diagram (Phase 68)

See `lifecycle-error-manage-overview.mmd` for the three-pillar error-management domain composition.

```mermaid
flowchart TD
    A[Error Domain] --> B[01: Error Resolution Process]
    A --> C[02: Error Architecture]
    A --> D[03: Error Code Registry]
    B --> E[Triage + Fix + Retro]
    C --> F[AppError + Modal + Envelope + Logging]
    D --> G[Schemas + Templates + Linters]
    E --> H[Closed Incident]
    F --> H
    G --> H
```
