# Templates

**Version:** 3.2.0  
**Status:** Active  
**Updated:** 2026-04-16  
**AI Confidence:** High  
**Ambiguity:** None

---


## Keywords

`error`, `code`, `registry`, `templates`

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

Error code registry templates. Every new error code MUST be authored from the canonical template and validated against the schema below before merge.

---

## Template Envelope (JSON Schema)

```json
{
  "$schema": "https://json-schema.org/draft/2020-12/schema",
  "$id": "https://lovable.dev/spec/03-error-manage/03-error-code-registry/09-templates.schema.json",
  "title": "ErrorCodeTemplate",
  "type": "object",
  "required": ["code", "domain", "severity", "message", "remediation"],
  "properties": {
    "code":        { "type": "string", "pattern": "^[A-Z]+-[0-9]{3,}$", "description": "Stable canonical identifier." },
    "domain":      { "type": "string", "enum": ["AUTH", "DB", "NET", "VALIDATION", "SECURITY", "NAMING", "BUILD", "RUNTIME"] },
    "severity":    { "type": "string", "enum": ["info", "warn", "error", "fatal"] },
    "message":     { "type": "string", "minLength": 8, "description": "User-facing one-liner." },
    "remediation": { "type": "string", "minLength": 12, "description": "Step the developer should take." },
    "since":       { "type": "string", "pattern": "^[0-9]+\\.[0-9]+\\.[0-9]+$" },
    "deprecated":  { "type": "boolean", "default": false }
  },
  "additionalProperties": false
}
```

> **Enforcement.** `linter-scripts/audit-spec-vs-code-v2.py` and `detect-collisions.mjs` both reject any error-code definition that fails this schema.

---

## Document Inventory

| File |
|------|
| 01-error-codes-template.md |
| 99-consistency-report.md |

---

## Cross-References

_See parent folder's `00-overview.md` for broader context._
