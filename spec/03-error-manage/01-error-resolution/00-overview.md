# Error Resolution

**Version:** 3.2.0  
**Updated:** 2026-04-16  
**AI Confidence:** Production-Ready  
**Ambiguity:** None

---

## Keywords

`error-resolution` · `debugging` · `retrospectives` · `verification-patterns` · `cheat-sheet` · `app-issues` · `error-documentation`

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

Error resolution patterns, debugging guides, retrospectives, and verification protocols. Covers the diagnostic side of error management — how to find, understand, and fix errors across PHP, Go, and TypeScript.

---

## Document Inventory

### Root Files

| # | File | Purpose |
|---|------|---------|
| 00 | [00-error-documentation-guideline.md](./00-error-documentation-guideline.md) | Mandatory process for documenting errors, root causes, and fixes |
| 01 | [01-cross-reference-diagram.md](./01-cross-reference-diagram.md) | Visual architecture of all connected specs |
| 02 | [02-debugging-cheat-sheet.md](./02-debugging-cheat-sheet.md) | Quick reference for PHP, Go, TypeScript debugging |
| — | 99-consistency-report.md | — |

### Subfolders

| # | Folder | Description | Files |
|---|--------|-------------|-------|
| 03 | [03-retrospectives/](./03-retrospectives/00-overview.md) | Case studies of resolved time-wasting issues | 4 |
| 04 | [04-verification-patterns/](./04-verification-patterns/00-overview.md) | Mandatory verification protocols | 1 |
| 05 | [05-debugging-guides/](./05-debugging-guides/00-overview.md) | Language-specific debugging guides (PHP, Go, TS) | 3 |
| 06 | [app-issues/](./app-issues/) | Documented errors with root cause and fix — prevents AI hallucination | 2 |

---

## Cross-References

- [Parent Overview](../00-overview.md) — Error Management root
- [Error Architecture](../02-error-architecture/00-overview.md) — Cross-stack error handling
- [Error Code Registry](../03-error-code-registry/00-overview.md) — Error code ranges

---

## Inlined Contracts (Phase 52 — boost)

### Resolution-document — JSON Schema 2020-12

```json
{
  "$schema": "https://json-schema.org/draft/2020-12/schema",
  "$id": "https://spec.local/03-error-manage/01-error-resolution/document.schema.json",
  "title": "ErrorResolutionDocument",
  "type": "object",
  "required": ["error_code", "title", "symptoms", "root_cause", "resolution"],
  "additionalProperties": false,
  "properties": {
    "error_code":       { "type": "string", "pattern": "^[A-Z]{2,5}-[A-Z]+-\\d{3}$" },
    "title":            { "type": "string", "minLength": 1, "maxLength": 200 },
    "symptoms":         { "type": "string", "minLength": 10, "maxLength": 4000 },
    "root_cause":       { "type": "string", "minLength": 10, "maxLength": 4000 },
    "resolution":       { "type": "string", "minLength": 10, "maxLength": 4000 },
    "prevention":       { "type": "string", "maxLength": 4000 },
    "verification": {
      "type": "array",
      "items": {
        "type": "object",
        "required": ["step", "expected"],
        "additionalProperties": false,
        "properties": {
          "step":     { "type": "string", "minLength": 1 },
          "expected": { "type": "string", "minLength": 1 }
        }
      }
    },
    "related_codes": {
      "type": "array",
      "items": { "type": "string", "pattern": "^[A-Z]{2,5}-[A-Z]+-\\d{3}$" },
      "uniqueItems": true
    },
    "owner_module": { "type": "string", "pattern": "^spec/\\d{2}-[a-z0-9-]+(/.*)?$" }
  }
}
```

### Resolution-doc status enum (TypeScript)

```ts
export enum ResolutionDocStatus {
  Draft     = "draft",
  Published = "published",
  Outdated  = "outdated",
  Superseded = "superseded",
}

export enum ResolutionDocAudience {
  EndUser     = "end-user",
  Operator    = "operator",
  Developer   = "developer",
  Auditor     = "auditor",
}
```
