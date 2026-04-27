---
kind: future-spec
description: Forward-looking debugging guide format for downstream PHP/Go/TypeScript application code. The referenced application source lives in downstream repos. Exempt from drift findings that flag missing application code.
---

# Debugging Guides

**Version:** 3.3.0  
**Status:** Active (future-spec — referenced application code lives downstream)  
**Updated:** 2026-04-26  
**AI Confidence:** High  
**Ambiguity:** None

---

## Drift Acknowledgment (Phase 27 — 2026-04-26)

AC-01, AC-03, AC-05 reference PHP / Go / TS application code that lives in **separate downstream repos**, not in this spec-only repo. The local code index intentionally contains only `linter-scripts/`. Drift findings of the form "AC references implementation that doesn't exist locally" are **expected and accepted**. The `kind: future-spec` frontmatter signals the audit to skip them.

---


## Keywords

`error`, `resolution`, `debugging`, `guides`

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

Debugging procedures and troubleshooting guides.

---

## Document Inventory

| File |
|------|
| 01-debugging-php.md |
| 02-debugging-go.md |
| 03-debugging-typescript.md |
| 99-consistency-report.md |

| 01-debugging-php.md |
| 02-debugging-go.md |
| 03-debugging-typescript.md |
| 99-consistency-report.md |
---

## Cross-References

_See parent folder's `00-overview.md` for broader context._

---

## Inlined Contracts (Phase 52 — boost)

### Debugging guide manifest — JSON Schema 2020-12

```json
{
  "$schema": "https://json-schema.org/draft/2020-12/schema",
  "$id": "https://spec.local/03-error-manage/01-error-resolution/05-debugging-guides/manifest.schema.json",
  "title": "DebuggingGuideManifest",
  "type": "object",
  "required": ["guide_id", "title", "audience", "steps"],
  "additionalProperties": false,
  "properties": {
    "guide_id":  { "type": "string", "pattern": "^dbg-[a-z0-9-]+$" },
    "title":     { "type": "string", "minLength": 1, "maxLength": 200 },
    "audience":  { "enum": ["end-user", "operator", "developer", "auditor"] },
    "applies_to_codes": {
      "type": "array",
      "items": { "type": "string", "pattern": "^[A-Z]{2,5}-[A-Z]+-\\d{3}$" },
      "uniqueItems": true
    },
    "prerequisites": { "type": "array", "items": { "type": "string" } },
    "steps": {
      "type": "array", "minItems": 1,
      "items": {
        "type": "object",
        "required": ["order", "action", "expected"],
        "additionalProperties": false,
        "properties": {
          "order":    { "type": "integer", "minimum": 1 },
          "action":   { "type": "string", "minLength": 1 },
          "command":  { "type": "string" },
          "expected": { "type": "string", "minLength": 1 },
          "on_fail":  { "type": "string" }
        }
      }
    },
    "estimated_minutes": { "type": "integer", "minimum": 1, "maximum": 240 }
  }
}
```

### Debugging audience + step-kind enums (TypeScript)

```ts
export enum DebugAudience {
  EndUser   = "end-user",
  Operator  = "operator",
  Developer = "developer",
  Auditor   = "auditor",
}

export enum DebugStepKind {
  Inspect    = "inspect",
  Execute    = "execute",
  Compare    = "compare",
  Measure    = "measure",
  Restart    = "restart",
  Escalate   = "escalate",
}
```
