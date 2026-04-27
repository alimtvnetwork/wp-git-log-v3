# AppError Package

**Version:** 3.2.0  
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
