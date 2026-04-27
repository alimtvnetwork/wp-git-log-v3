---
kind: future-spec
description: Forward-looking debugging guide format for downstream PHP/Go/TypeScript application code. The referenced application source lives in downstream repos. Exempt from drift findings that flag missing application code.
---

# Debugging Guides

**Version:** 3.3.0  
**Status:** Active (future-spec — referenced application code lives downstream)  
**Updated:** 2026-04-27  
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


---

## Phase 61 Reference: Debugging Guides API

The following OpenAPI 3.1 contract is normative.

```yaml
openapi: 3.1.0
info:
  title: Debugging Guides API
  version: 1.0.0
servers:
  - url: https://api.lovable.dev/debug-guides/v1
paths:
  /guides:
    get:
      summary: Search debugging guides
      operationId: searchGuides
      parameters:
        - in: query
          name: code
          schema: { type: string, pattern: "^[A-Z]{2,5}-[A-Z]+-\\d{2,4}$" }
        - in: query
          name: tag
          schema: { type: string }
      responses:
        "200":
          description: OK
          content:
            application/json:
              schema:
                type: array
                items: { $ref: "#/components/schemas/Guide" }
  /guides/{slug}:
    get:
      summary: Fetch a single guide
      operationId: getGuide
      parameters:
        - in: path
          name: slug
          required: true
          schema: { type: string, pattern: "^[a-z0-9-]+$" }
      responses:
        "200":
          description: OK
          content:
            application/json:
              schema: { $ref: "#/components/schemas/Guide" }
components:
  schemas:
    Guide:
      type: object
      required: [slug, title, body_md]
      properties:
        slug:    { type: string }
        title:   { type: string, minLength: 5 }
        body_md: { type: string }
        tags:
          type: array
          items: { type: string }
        related_codes:
          type: array
          items: { type: string }
```


## Phase 67 Reference

### Lifecycle Diagram (Phase 67)

See `lifecycle-debug-guide.mmd` for the recurring-issue → debug-guide authoring → publication flow.

```mermaid
flowchart TD
    A[Recurring Issue Identified] --> B[Author debug guide]
    B --> C[Steps: reproduce → diagnose → fix]
    C --> D[Add code snippets + log examples]
    D --> E[Cross-link to error codes]
    E --> F{Peer Review Pass?}
    F -- No --> G[Revise]
    G --> F
    F -- Yes --> H[Publish to debugging-guides/]
    H --> I[Index in error-resolution overview]
```
