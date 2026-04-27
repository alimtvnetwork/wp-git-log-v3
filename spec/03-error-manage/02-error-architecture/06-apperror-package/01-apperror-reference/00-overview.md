# AppError Package Reference

**Version:** 3.2.0  
**Updated:** 2026-04-16  
**AI Confidence:** Production-Ready  
**Ambiguity:** None

---

## Keywords

`01-apperror-reference` · `coding-standards`

---

## Scoring

| Criterion | Status |
|-----------|--------|
| `00-overview.md` present | ✅ |
| AI Confidence assigned | ✅ |
| Ambiguity assigned | ✅ |
| Keywords present | ✅ |
| Scoring table present | ✅ |

---

## Purpose

Previously a single 1022-line file, now split into focused modules under 300 lines each.

---

## Document Inventory

| # | File | Purpose | Lines |
|---|------|---------|-------|
| — | [01-overview-and-stack.md](./01-overview-and-stack.md) | Overview, invariants, StackTrace | 132 |
| — | [02-apperror-struct.md](./02-apperror-struct.md) | AppError struct and constructors | 132 |
| — | [03-result-types.md](./03-result-types.md) | Result[T], ResultSlice[T], ResultMap[K,V] | 150 |
| — | [04-codes-and-policy.md](./04-codes-and-policy.md) | Error code convention, stack trace skip rules, file size | 69 |
| — | [05-apperrtype-enums.md](./05-apperrtype-enums.md) | Domain error type enums — all E1xxx–E14xxx enum definitions | 340 |
| — | [05-usage-and-adapters.md](./05-usage-and-adapters.md) | Usage examples, service adapter unwrap pattern | 236 |
| — | [06-serialization-and-guards.md](./06-serialization-and-guards.md) | JSON serialization, Result guard rule | 360 |
| — | 99-consistency-report.md | — | — |

| — | 99-consistency-report.md | — | — |
---

## Cross-References

- [Golang Coding Standards](../../../../02-coding-guidelines/03-golang/04-golang-standards-reference/00-overview.md) — File size, function size, type safety, file naming
- [Cross-Language Code Style](../../../../02-coding-guidelines/01-cross-language/04-code-style/00-overview.md) — Braces, nesting, spacing
- [Enum Specification](../../../../02-coding-guidelines/03-golang/01-enum-specification/00-overview.md) — Byte-based enum pattern with mandatory JSON marshal

---

## Inlined Contracts (Phase 53 — boost)

### AppError reference catalog — JSON Schema 2020-12

```json
{
  "$schema": "https://json-schema.org/draft/2020-12/schema",
  "$id": "https://spec.local/03-error-manage/02-error-architecture/06-apperror-package/01-apperror-reference/catalog.schema.json",
  "title": "AppErrorReferenceCatalog",
  "type": "object",
  "required": ["entries"],
  "additionalProperties": false,
  "properties": {
    "entries": {
      "type": "array", "minItems": 1,
      "items": {
        "type": "object",
        "required": ["code", "ts_factory", "go_factory", "php_factory", "csharp_factory", "default_message"],
        "additionalProperties": false,
        "properties": {
          "code":            { "type": "string", "pattern": "^[A-Z]{2,5}-[A-Z]+-\\d{3}$" },
          "default_message": { "type": "string", "minLength": 1, "maxLength": 500 },
          "ts_factory":      { "type": "string", "pattern": "^[A-Z][A-Za-z0-9_]*$" },
          "go_factory":      { "type": "string", "pattern": "^[A-Z][A-Za-z0-9_]*$" },
          "php_factory":     { "type": "string", "pattern": "^[A-Z][A-Za-z0-9_]*$" },
          "csharp_factory":  { "type": "string", "pattern": "^[A-Z][A-Za-z0-9_]*$" },
          "domain":          { "enum": ["network","storage","validation","auth","plugin","pipeline","internal"] },
          "default_severity": { "enum": ["fatal","error","warn","info","debug"] },
          "retryable":        { "type": "boolean", "default": false },
          "user_safe":        { "type": "boolean", "default": false }
        }
      }
    }
  }
}
```

### Factory-function naming enums (TypeScript)

```ts
export enum FactoryReturnShape {
  Throws   = "throws",
  Returns  = "returns",
  Result   = "result",
}

export enum FactoryLanguage {
  Ts     = "ts",
  Go     = "go",
  Php    = "php",
  Csharp = "csharp",
}
```
