# App Issues

**Version:** 3.2.0  
**Updated:** 2026-04-27  
**AI Confidence:** High  
**Ambiguity:** None

---

## Keywords

`app-issues`, `error-documentation`, `root-cause`, `prevention`, `code-red`

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

Documented application errors with root cause analysis, solutions, and prevention steps. Each entry follows the [Error Documentation Guideline](../00-error-documentation-guideline.md) to prevent AI hallucination on previously solved problems.

---

## Document Inventory

| File | Purpose |
|------|---------|
| [2026-04-02-url-error-casing-fix.md](./2026-04-02-url-error-casing-fix.md) | URLError renamed to UrlError — inconsistent casing fix |
| [error-management-file-path-and-missing-file-code-red-rule.md](./error-management-file-path-and-missing-file-code-red-rule.md) | 🔴 Code Red: Mandatory file path and failure reason in all file/path error logs |

---

## Cross-References

- [Error Documentation Guideline](../00-error-documentation-guideline.md) — Mandatory documentation process
- [Error Resolution Overview](../00-overview.md) — Parent folder

---

*App issues overview — created: 2026-04-07*

---

## Inlined Contracts (Phase 51 — boost)

### App-issue resolution record — JSON Schema 2020-12

```json
{
  "$schema": "https://json-schema.org/draft/2020-12/schema",
  "$id": "https://spec.local/03-error-manage/01-error-resolution/app-issues/record.schema.json",
  "title": "AppIssueResolutionRecord",
  "type": "object",
  "required": ["issue_id", "error_code", "root_cause", "prevention", "status"],
  "additionalProperties": false,
  "properties": {
    "issue_id":   { "type": "string", "pattern": "^AI-\\d{4}$" },
    "error_code": { "type": "string", "pattern": "^[A-Z]{2,5}-[A-Z]+-\\d{3}$" },
    "summary":    { "type": "string", "minLength": 1, "maxLength": 200 },
    "root_cause": { "type": "string", "minLength": 10, "maxLength": 4000 },
    "prevention": { "type": "string", "minLength": 10, "maxLength": 4000 },
    "status":     { "enum": ["open", "investigating", "resolved", "wontfix"] },
    "severity":   { "enum": ["code-red", "blocker", "major", "minor", "info"] },
    "opened_at":  { "type": "string", "format": "date" },
    "closed_at":  { "type": "string", "format": "date" },
    "owner":      { "type": "string", "minLength": 1 }
  }
}
```

### Resolution-status TypeScript enums

```ts
export enum AppIssueStatus {
  Open          = "open",
  Investigating = "investigating",
  Resolved      = "resolved",
  WontFix       = "wontfix",
}

export enum AppIssueSeverity {
  CodeRed = "code-red",
  Blocker = "blocker",
  Major   = "major",
  Minor   = "minor",
  Info    = "info",
}
```
