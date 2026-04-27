# Templates

**Version:** 3.3.0  
**Status:** Active  
**Updated:** 2026-04-27  
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

---

## Inlined Contracts (Phase 51 — boost)

### Error-code template manifest — JSON Schema 2020-12

```json
{
  "$schema": "https://json-schema.org/draft/2020-12/schema",
  "$id": "https://spec.local/03-error-manage/03-error-code-registry/09-templates/manifest.schema.json",
  "title": "ErrorTemplateManifest",
  "type": "object",
  "required": ["template_id", "language", "render_target", "body"],
  "additionalProperties": false,
  "properties": {
    "template_id":   { "type": "string", "pattern": "^tpl-[a-z0-9-]+$" },
    "language":      { "enum": ["ts", "go", "php", "csharp", "python", "rust", "json", "markdown"] },
    "render_target": { "enum": ["registry-entry", "modal-copy", "log-line", "doc-block"] },
    "body":          { "type": "string", "minLength": 1 },
    "placeholders": {
      "type": "array",
      "items": {
        "type": "object",
        "required": ["name", "type"],
        "additionalProperties": false,
        "properties": {
          "name": { "type": "string", "pattern": "^[a-z][a-z0-9_]*$" },
          "type": { "enum": ["string", "integer", "boolean", "iso-date", "code"] },
          "required": { "type": "boolean", "default": true }
        }
      }
    },
    "owner_module": { "type": "string", "pattern": "^spec/\\d{2}-[a-z0-9-]+(/.*)?$" }
  }
}
```

### Render-target + placeholder-type TypeScript enums

```ts
export enum TemplateRenderTarget {
  RegistryEntry = "registry-entry",
  ModalCopy     = "modal-copy",
  LogLine       = "log-line",
  DocBlock      = "doc-block",
}

export enum TemplatePlaceholderType {
  String   = "string",
  Integer  = "integer",
  Boolean  = "boolean",
  IsoDate  = "iso-date",
  Code     = "code",
}

export enum TemplateLanguage {
  Ts       = "ts",
  Go       = "go",
  Php      = "php",
  Csharp   = "csharp",
  Python   = "python",
  Rust     = "rust",
  Json     = "json",
  Markdown = "markdown",
}
```
