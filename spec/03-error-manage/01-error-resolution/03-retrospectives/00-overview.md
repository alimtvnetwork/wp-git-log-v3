---
kind: future-spec
description: Forward-looking retrospective format for full-stack (Go backend + React frontend) error post-mortems. The referenced backend/frontend source files live in downstream repos. Exempt from drift findings that flag missing application code.
---

# Retrospectives

**Version:** 3.3.0  
**Status:** Active (future-spec — referenced application code lives downstream)  
**Updated:** 2026-04-26  
**AI Confidence:** High  
**Ambiguity:** None

---

## Drift Acknowledgment (Phase 27 — 2026-04-26)

ACs in this module reference paths like `backend/internal/api/handlers/handlers.go` and React components. These files live in **separate downstream application repos**, not in this spec-only repo (which only ships `linter-scripts/`). Audit drift findings of the form "AC references file that doesn't exist in local code index" are **expected**. The `kind: future-spec` frontmatter signals the audit to skip them.

---


## Keywords

`error`, `resolution`, `retrospectives`

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

Post-incident retrospectives and lessons learned.

---

## Document Inventory

| File |
|------|
| 01-health-endpoint-mismatch.md |
| 02-retry-debounce-dedup-fixes.md |
| 03-zip-finalization-before-return.md |
| 04-activation-endpoint-mismatch.md |
| 99-consistency-report.md |

| 01-health-endpoint-mismatch.md |
| 02-retry-debounce-dedup-fixes.md |
| 03-zip-finalization-before-return.md |
| 04-activation-endpoint-mismatch.md |
| 99-consistency-report.md |
---

## Cross-References

_See parent folder's `00-overview.md` for broader context._


---

## Phase 61 Reference: Retrospectives Index API

The following OpenAPI 3.1 contract is normative.

```yaml
openapi: 3.1.0
info:
  title: Retrospectives Index API
  version: 1.0.0
servers:
  - url: https://api.lovable.dev/retros/v1
paths:
  /retros:
    get:
      summary: List retrospectives
      operationId: listRetros
      parameters:
        - in: query
          name: status
          schema: { type: string, enum: [draft, published, archived] }
      responses:
        "200":
          description: OK
          content:
            application/json:
              schema:
                type: array
                items: { $ref: "#/components/schemas/Retro" }
  /retros/{id}:
    get:
      summary: Get a retrospective
      operationId: getRetro
      parameters:
        - in: path
          name: id
          required: true
          schema: { type: string, format: uuid }
      responses:
        "200":
          description: OK
          content:
            application/json:
              schema: { $ref: "#/components/schemas/Retro" }
components:
  schemas:
    Retro:
      type: object
      required: [id, incident_code, summary, published_at]
      properties:
        id:            { type: string, format: uuid }
        incident_code: { type: string, pattern: "^[A-Z]{2,5}-[A-Z]+-\\d{2,4}$" }
        summary:       { type: string, minLength: 10 }
        published_at:  { type: string, format: date-time }
        action_items:
          type: array
          items:
            type: object
            properties:
              owner:    { type: string }
              due_date: { type: string, format: date }
              status:   { type: string, enum: [open, in_progress, done] }
```
