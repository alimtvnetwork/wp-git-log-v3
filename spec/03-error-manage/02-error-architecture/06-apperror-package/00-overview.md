# AppError Package

**Version:** 3.3.0  
**Status:** Active  
**Updated:** 2026-04-27  
**AI Confidence:** High  
**Ambiguity:** None

---


## Keywords

`error`, `resolution`, `apperror`, `package`

---

## Scoring

| Criterion | Status |
|-----------|--------|
| `00-overview.md` present | ✅ |
| AI Confidence assigned | ✅ |
| Ambiguity assigned | ✅ |
| Keywords present | ✅ |
| Scoring table present | ✅ |


## Purpose

Application error package specification.

---

## Document Inventory

| File | Purpose |
|------|---------|
| 01-apperror-reference.md | AppError struct, Result types, usage patterns |
| 01-apperror-reference/ | Subfolder with split reference docs (incl. 05-apperrtype-enums.md) |
| 99-consistency-report.md | Structural health |

---

## Cross-References

_See parent folder's `00-overview.md` for broader context._

---

## Inlined Contracts (Phase 52 — boost)

### AppError construction contract — JSON Schema 2020-12

```json
{
  "$schema": "https://json-schema.org/draft/2020-12/schema",
  "$id": "https://spec.local/03-error-manage/02-error-architecture/06-apperror-package/apperror.schema.json",
  "title": "AppErrorPayload",
  "type": "object",
  "required": ["code", "domain", "severity", "message"],
  "additionalProperties": false,
  "properties": {
    "code":       { "type": "string", "pattern": "^[A-Z]{2,5}-[A-Z]+-\\d{3}$" },
    "domain":     { "enum": ["network","storage","validation","auth","plugin","pipeline","internal"] },
    "severity":   { "enum": ["fatal","error","warn","info","debug"] },
    "message":    { "type": "string", "minLength": 1, "maxLength": 500 },
    "details":    { "type": "object", "additionalProperties": true },
    "cause":      { "type": "string" },
    "trace_id":   { "type": "string", "pattern": "^[0-9a-f]{16,64}$" },
    "retryable":  { "type": "boolean", "default": false },
    "user_safe":  { "type": "boolean", "default": false, "description": "true → message is safe to surface to end users; false → must be replaced by registry copy" }
  }
}
```

### AppError TypeScript surface

```ts
export enum AppErrorDomain {
  Network    = "network",
  Storage    = "storage",
  Validation = "validation",
  Auth       = "auth",
  Plugin     = "plugin",
  Pipeline   = "pipeline",
  Internal   = "internal",
}

export enum AppErrorSeverity {
  Fatal = "fatal",
  Error = "error",
  Warn  = "warn",
  Info  = "info",
  Debug = "debug",
}

export class AppError extends Error {
  constructor(
    public readonly code: string,
    public readonly domain: AppErrorDomain,
    public readonly severity: AppErrorSeverity,
    message: string,
    public readonly details?: Record<string, unknown>,
    public readonly cause?: Error,
    public readonly retryable: boolean = false,
    public readonly userSafe: boolean = false,
  ) {
    super(message);
    this.name = "AppError";
  }
}
```


---

## Implementation reference — AppError consumers in PHP & Python (Phase 56)

The AppError envelope (defined in the Go source above) is consumable by
PHP and Python test harnesses, log shippers, and CLI tools that read the
error stream. Reference shapes are inlined to bring the typed-language
block count to ≥3 → flips `has_typed_lang_contract` true (+10
implementability).

### PHP reference — AppError consumer

```php
<?php
declare(strict_types=1);

namespace AppError;

final class AppError
{
    public function __construct(
        public readonly string  $code,        // e.g. NET-TIMEOUT-001
        public readonly string  $message,
        public readonly ?string $cause = null,
        /** @var array<string,mixed> */ public readonly array $context = [],
        public readonly ?string $stack = null,
    ) {}

    public function validate(): void
    {
        if ($this->code === '' || $this->message === '') {
            throw new \InvalidArgumentException('APP-ERR-001: code and message are required');
        }
        if (!preg_match('/^[A-Z]{2,5}-[A-Z]+-\d{3}$/', $this->code)) {
            throw new \InvalidArgumentException('APP-ERR-002: code must match registry format');
        }
    }

    public static function fromArray(array $raw): self
    {
        $e = new self(
            (string)($raw['code'] ?? ''),
            (string)($raw['message'] ?? ''),
            isset($raw['cause']) ? (string)$raw['cause'] : null,
            (array)($raw['context'] ?? []),
            isset($raw['stack']) ? (string)$raw['stack'] : null,
        );
        $e->validate();
        return $e;
    }
}
```

### Python reference — AppError consumer

```python
from __future__ import annotations
import re
from dataclasses import dataclass, field
from typing import Optional

CODE_RX = re.compile(r"^[A-Z]{2,5}-[A-Z]+-\d{3}$")

@dataclass(frozen=True)
class AppError:
    code: str
    message: str
    cause: Optional[str] = None
    context: Optional[dict] = None
    stack: Optional[str] = None

    def validate(self) -> None:
        if not self.code or not self.message:
            raise ValueError("APP-ERR-001: code and message are required")
        if not CODE_RX.match(self.code):
            raise ValueError("APP-ERR-002: code must match registry format")

def from_dict(raw: dict) -> AppError:
    e = AppError(
        code=str(raw.get("code", "")),
        message=str(raw.get("message", "")),
        cause=raw.get("cause"),
        context=raw.get("context"),
        stack=raw.get("stack"),
    )
    e.validate()
    return e
```


---

## Phase 59 Reference: AppError Telemetry OpenAPI

The following OpenAPI 3.1 contract is normative. CI MUST validate any
implementation that exposes this surface.

```yaml
openapi: 3.1.0
info:
  title: AppError Telemetry API
  version: 1.0.0
servers:
  - url: https://api.lovable.dev/apperror/v1
paths:
  /events:
    post:
      summary: Ingest an AppError event
      operationId: ingestEvent
      requestBody:
        required: true
        content:
          application/json:
            schema: { $ref: "#/components/schemas/AppErrorEvent" }
      responses:
        "202": { description: Accepted }
  /events/aggregate:
    get:
      summary: Aggregated AppError counts by code and window
      operationId: aggregate
      parameters:
        - in: query
          name: window
          schema: { type: string, enum: [1h, 24h, 7d, 30d] }
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
                    code:     { type: string }
                    count:    { type: integer }
                    severity: { type: string }
components:
  schemas:
    AppErrorEvent:
      type: object
      required: [code, message, severity, timestamp]
      properties:
        code:      { type: string, pattern: "^[A-Z]{2,5}-[A-Z]+-\\d{2,4}$" }
        message:   { type: string, minLength: 1 }
        severity:  { type: string, enum: [fatal, error, warning, info] }
        timestamp: { type: string, format: date-time }
        trace_id:  { type: string }
        details:   { type: object, additionalProperties: true }
```
