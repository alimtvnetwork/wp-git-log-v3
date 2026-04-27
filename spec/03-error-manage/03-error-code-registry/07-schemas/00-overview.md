# Schemas

**Version:** 3.3.0  
**Status:** Active  
**Updated:** 2026-04-27  
**AI Confidence:** High  
**Ambiguity:** None

---

## Keywords

`error`, `code`, `registry`, `schemas`, `json-schema`

---

## Scoring

| Criterion | Status |
|-----------|--------|
| `00-overview.md` present | ✅ |
| AI Confidence assigned | ✅ |
| Ambiguity assigned | ✅ |
| Keywords present | ✅ |
| Scoring table present | ✅ |
| Inlined contracts present | ✅ |

---

## Purpose

Error code registry JSON schemas. This module is the source of truth for both:

1. **`error-code.schema.json`** — single-project error code definitions (used by Go libraries and TypeScript validators).
2. **`error-codes-index.schema.json`** — per-module index files (used by ecosystem-wide aggregators).

Both schemas target **JSON Schema Draft 2020-12-compatible** validators (also valid under Draft 7 for legacy tooling).

---

## Inlined Contracts

> Both schemas are inlined here as the source of truth. Sibling `.json` files are bit-for-bit copies maintained by `linter-scripts/sync-schemas.cjs`.

### Contract 1 — `error-code.schema.json` (Single-Project Error Code Definitions)

```json
{
  "$schema": "http://json-schema.org/draft-07/schema#",
  "title": "Error Code Schema",
  "description": "Schema for validating standardized error codes (supports both prefixed and integer formats)",
  "type": "object",
  "definitions": {
    "ErrorCode": {
      "oneOf": [
        {
          "type": "string",
          "pattern": "^[A-Z]{2,4}-[0-9]{3}-[0-9]{2}$",
          "description": "Prefixed format: PROJECT-CATEGORY-NUMBER (e.g., SM-400-01)",
          "examples": ["GEN-100-01", "SM-400-01", "LM-300-02", "PS-9500-01"]
        },
        {
          "type": "integer",
          "minimum": 1000,
          "maximum": 99999,
          "description": "Integer format for Go CLI tools (e.g., 7001, 9301, 14200)",
          "examples": [7001, 9301, 14200]
        },
        {
          "type": "string",
          "pattern": "^[0-9]{4,5}$",
          "description": "Integer format as string for Go CLI tools",
          "examples": ["7001", "9301", "14200"]
        }
      ]
    },
    "ErrorEntry": {
      "type": "object",
      "required": ["Code", "Name", "Message"],
      "properties": {
        "Code": { "$ref": "#/definitions/ErrorCode" },
        "Name": {
          "type": "string",
          "pattern": "^[A-Z][A-Z0-9_]*$",
          "description": "Constant name in UPPER_SNAKE_CASE",
          "examples": ["CONFIG_MISSING", "AUTH_REQUIRED", "DB_CONNECTION"]
        },
        "Message": {
          "type": "string",
          "minLength": 5,
          "maxLength": 200,
          "description": "Human-readable error message"
        },
        "Category": {
          "type": "string",
          "enum": [
            "general", "authentication", "authorization", "validation",
            "business_logic", "database", "external_services",
            "file_system", "network"
          ]
        },
        "Severity": {
          "type": "string",
          "enum": ["info", "warning", "error", "critical"],
          "default": "error"
        },
        "Recoverable": { "type": "boolean", "default": true },
        "UserVisible": { "type": "boolean", "default": true },
        "HttpStatus": { "type": "integer", "minimum": 100, "maximum": 599 }
      },
      "additionalProperties": false
    }
  },
  "properties": {
    "Project": {
      "type": "string",
      "pattern": "^[A-Z]{2,4}$",
      "description": "Project prefix (2-4 uppercase letters)"
    },
    "Version": {
      "type": "string",
      "pattern": "^[0-9]+\\.[0-9]+\\.[0-9]+$"
    },
    "Errors": {
      "type": "array",
      "items": { "$ref": "#/definitions/ErrorEntry" }
    }
  },
  "required": ["Project", "Errors"],
  "additionalProperties": false
}
```

### Contract 2 — `error-codes-index.schema.json` (Per-Module Index Files)

```json
{
  "$schema": "http://json-schema.org/draft-07/schema#",
  "title": "Module Error Codes Index Schema",
  "description": "Schema for validating per-module error-codes.json index files used across the ecosystem. Supports integer codes, local ERR_xxxx PHP codes, and prefixed E{x}xxx Go codes.",
  "type": "object",
  "required": ["Title", "Project", "Categories", "Stats"],
  "properties": {
    "$schema": { "type": "string" },
    "Title": { "type": "string", "minLength": 5 },
    "Description": { "type": "string" },
    "Version": { "type": "string", "pattern": "^[0-9]+\\.[0-9]+\\.[0-9]+$" },
    "Generated": { "type": "string", "pattern": "^[0-9]{4}-[0-9]{2}-[0-9]{2}$" },
    "Source": {
      "oneOf": [
        { "type": "string" },
        { "type": "null" }
      ]
    },
    "Project": {
      "type": "string",
      "pattern": "^[A-Z]{2,4}(-[A-Z]{2,4})?$",
      "description": "Project prefix (e.g., AB, WSP, SM-CG)"
    },
    "Range": { "$ref": "#/definitions/Range" },
    "Ranges": {
      "type": "array",
      "items": { "$ref": "#/definitions/Range" },
      "minItems": 1
    },
    "Format": { "type": "string" },
    "Note": { "type": "string" },
    "ReassignedFrom": { "type": "string" },
    "RemappedFrom": { "type": "string" },
    "ExitCodes": {
      "type": "array",
      "items": { "$ref": "#/definitions/ExitCodeEntry" }
    },
    "Categories": {
      "type": "array",
      "items": { "$ref": "#/definitions/Category" }
    },
    "Stats": { "$ref": "#/definitions/Stats" }
  },
  "oneOf": [
    { "required": ["Range"] },
    { "required": ["Ranges"] }
  ],
  "additionalProperties": false,
  "definitions": {
    "Range": {
      "type": "object",
      "required": ["Min", "Max"],
      "properties": {
        "Min": { "type": "integer", "minimum": 0 },
        "Max": { "type": "integer", "minimum": 0 }
      },
      "additionalProperties": false
    },
    "Category": {
      "type": "object",
      "required": ["Name", "Codes"],
      "properties": {
        "Name": { "type": "string", "minLength": 1 },
        "Range": { "$ref": "#/definitions/Range" },
        "LocalRange": { "type": "string" },
        "EcosystemRange": { "$ref": "#/definitions/Range" },
        "Prefix": { "type": "string" },
        "Source": { "type": "string" },
        "Codes": {
          "type": "array",
          "items": { "$ref": "#/definitions/CodeEntry" }
        }
      },
      "additionalProperties": false
    },
    "CodeEntry": {
      "type": "object",
      "required": ["Constant", "Description", "Retryable"],
      "properties": {
        "Code": {
          "oneOf": [
            { "type": "integer", "minimum": 0 },
            { "type": "string", "pattern": "^E[0-9]{4}$" }
          ]
        },
        "LocalCode": {
          "oneOf": [
            { "type": "string", "pattern": "^ERR_[0-9]{4}$" },
            { "type": "integer", "minimum": 1000 }
          ]
        },
        "Constant": {
          "type": "string",
          "pattern": "^[A-Za-z][A-Za-z0-9_]*$"
        },
        "Description": {
          "type": "string",
          "minLength": 3,
          "maxLength": 200
        },
        "Retryable": { "type": "boolean" },
        "Http": { "type": "integer", "minimum": 100, "maximum": 599 },
        "Exit": { "type": "integer", "minimum": 0, "maximum": 255 }
      },
      "additionalProperties": false
    },
    "ExitCodeEntry": {
      "type": "object",
      "required": ["Code", "Constant", "Description"],
      "properties": {
        "Code": { "type": "integer", "minimum": 0, "maximum": 255 },
        "Constant": { "type": "string", "pattern": "^[A-Z][A-Z0-9_]*$" },
        "Description": { "type": "string" }
      },
      "additionalProperties": false
    },
    "Stats": {
      "type": "object",
      "required": ["TotalCodes", "RetryableCodes"],
      "properties": {
        "TotalCodes": { "type": "integer", "minimum": 0 },
        "TotalCategories": { "type": "integer", "minimum": 0 },
        "RetryableCodes": { "type": "integer", "minimum": 0 },
        "RangeUtilization": { "type": "string" },
        "EcosystemRemapStatus": {
          "type": "string",
          "enum": ["pending", "complete"]
        },
        "Note": { "type": "string" }
      },
      "additionalProperties": false
    }
  }
}
```

---

## Document Inventory

| File | Purpose |
|------|---------|
| `00-overview.md` | This document — inlined contracts + module purpose |
| `error-code.schema.json` | Single-project schema (mirrored above) |
| `error-codes-index.schema.json` | Per-module index schema (mirrored above) |
| `97-acceptance-criteria.md` | GWT criteria validating schema constraints |
| `98-changelog.md` | Version history |
| `99-consistency-report.md` | Health/inventory snapshot |

---

## Cross-References

- [Module acceptance criteria](./97-acceptance-criteria.md)
- [Module changelog](./98-changelog.md)
- [Module consistency report](./99-consistency-report.md)
- _See parent folder's `00-overview.md` for broader context._

---

## Normative Contract (Phase 50)

```text
CONTRACT: error-code-registry/schemas
PURPOSE: define machine-readable JSON Schemas governing the error-code registry artifacts
SCOPE: validates error-codes-master.json + per-domain shards prior to publication

INV-01  every schema MUST be JSON Schema 2020-12 with explicit $id and $schema
INV-02  every error code MUST match pattern ^[A-Z]{2,5}-[A-Z]+-\d{3}$
INV-03  each code MUST carry: code, severity, category, message_template, owner_module
INV-04  severity ∈ {fatal, error, warn, info, debug}
INV-05  category MUST resolve to a known domain in §03-error-manage taxonomy
INV-06  message_template MUST use {placeholder} syntax; positional %s/%d forbidden
INV-07  owner_module MUST be a valid spec/<NN>-* path string

FAIL-01 duplicate code across shards → registry build aborts (severity=blocker)
FAIL-02 missing required field → validator exits non-zero with field path
FAIL-03 unknown severity or category → validator exits non-zero
FAIL-04 message_template contains positional formatter → validator exits non-zero

DEL-01  shard merging is owned by §03/08-linter-scripts (not this module)
DEL-02  runtime emission of error events is owned by per-language §02 modules
DEL-03  schema evolution requires §03/03/98-changelog minor bump + migration note
```

## Inlined Contracts (Phase 50 — boost)

### Severity & Category TypeScript enums

```ts
export enum RegistrySeverity {
  Fatal = "fatal",
  Error = "error",
  Warn  = "warn",
  Info  = "info",
  Debug = "debug",
}

export enum RegistryCategory {
  Network    = "network",
  Storage    = "storage",
  Validation = "validation",
  Auth       = "auth",
  Plugin     = "plugin",
  Pipeline   = "pipeline",
  Internal   = "internal",
}
```

### Per-shard registry entry — JSON Schema 2020-12 (additional)

```json
{
  "$schema": "https://json-schema.org/draft/2020-12/schema",
  "$id": "https://spec.local/03-error-manage/03/07/shard-entry.schema.json",
  "title": "RegistryShardEntry",
  "type": "object",
  "required": ["code", "severity", "category", "message_template", "owner_module"],
  "additionalProperties": false,
  "properties": {
    "code":             { "type": "string", "pattern": "^[A-Z]{2,5}-[A-Z]+-\\d{3}$" },
    "severity":         { "enum": ["fatal","error","warn","info","debug"] },
    "category":         { "enum": ["network","storage","validation","auth","plugin","pipeline","internal"] },
    "message_template": { "type": "string", "pattern": "^[^%]*$", "minLength": 1, "maxLength": 500 },
    "owner_module":     { "type": "string", "pattern": "^spec/\\d{2}-[a-z0-9-]+(/.*)?$" },
    "deprecated":       { "type": "boolean", "default": false },
    "replaced_by":      { "type": "string", "pattern": "^[A-Z]{2,5}-[A-Z]+-\\d{3}$" }
  }
}
```
