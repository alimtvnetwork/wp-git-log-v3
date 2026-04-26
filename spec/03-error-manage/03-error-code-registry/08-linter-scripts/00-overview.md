# Linter Scripts

**Version:** 1.1.0  
**Status:** Active  
**Updated:** 2026-04-26  
**AI Confidence:** High  
**Ambiguity:** None

---

## Keywords

`error` · `code` · `registry` · `linter-scripts` · `collision-detection` · `utilization-threshold` · `master-stats`

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

Error code registry automation scripts. This module specifies the four linter
scripts that maintain the project-wide error code registry: collision
detection, utilization threshold checking, utilization reporting, and
master-stats validation. The scripts run on every CI pipeline and locally via
`linter-scripts/run.sh`.

---

## Document Inventory

| File | Description |
|------|-------------|
| `detect-collisions.mjs` | Fail-fast on duplicate `(domain, code)` pairs across the registry |
| `check-utilization-threshold.mjs` | Warn when a domain exceeds 80 % of its allotted code range |
| `generate-utilization-report.mjs` | Emit `utilization-report.json` for the dashboard |
| `validate-master-stats.mjs` | Cross-check `master-stats.json` against the live registry |
| `97-acceptance-criteria.md` | GWT acceptance criteria for the four scripts |
| `98-changelog.md` | Module version history |
| `99-consistency-report.md` | Module health check |

---

## Normative Contract — Error Registry Schema

The four linter scripts in this module operate on a single canonical JSON
registry. Any script implementation MUST validate its input against the
schema below before processing.

```text
{
  "$schema": "https://json-schema.org/draft/2020-12/schema",
  "$id": "spec/03-error-manage/03-error-code-registry/08-linter-scripts/registry.schema.json",
  "title": "ErrorCodeRegistry",
  "type": "object",
  "required": ["version", "generated", "domains", "codes"],
  "properties": {
    "version":   { "type": "string", "pattern": "^[0-9]+\\.[0-9]+\\.[0-9]+$" },
    "generated": { "type": "string", "format": "date-time" },
    "domains": {
      "type": "array",
      "minItems": 1,
      "items": {
        "type": "object",
        "required": ["name", "prefix", "range_start", "range_end"],
        "properties": {
          "name":        { "type": "string", "pattern": "^[A-Z][A-Z0-9_]+$" },
          "prefix":      { "type": "string", "pattern": "^[A-Z]{2,5}$" },
          "range_start": { "type": "integer", "minimum": 1 },
          "range_end":   { "type": "integer", "maximum": 99999 },
          "utilization_warn_pct": { "type": "number", "minimum": 0, "maximum": 100, "default": 80 }
        }
      }
    },
    "codes": {
      "type": "array",
      "items": {
        "type": "object",
        "required": ["code", "domain", "severity", "message", "since"],
        "properties": {
          "code":     { "type": "string", "pattern": "^[A-Z]{2,5}-[0-9]{3,5}$" },
          "domain":   { "type": "string" },
          "severity": { "enum": ["fatal", "error", "warn", "info"] },
          "message":  { "type": "string", "minLength": 1 },
          "since":    { "type": "string", "pattern": "^[0-9]+\\.[0-9]+\\.[0-9]+$" },
          "owner":    { "type": "string" }
        }
      }
    }
  }
}
```

> **Enforcement.** `detect-collisions.mjs` exits non-zero on the first
> duplicate `code` value or any `code` whose numeric suffix falls outside
> its declared `domain` range. `check-utilization-threshold.mjs` exits 1
> when `count(codes_in_domain) / range_size >= utilization_warn_pct/100`.
> `validate-master-stats.mjs` requires byte-for-byte agreement between the
> registry and the published `master-stats.json`.

---

## Cross-References

- Parent: [`../00-overview.md`](../00-overview.md)
- Acceptance criteria: [`./97-acceptance-criteria.md`](./97-acceptance-criteria.md)
- Changelog: [`./98-changelog.md`](./98-changelog.md)
- Consistency report: [`./99-consistency-report.md`](./99-consistency-report.md)
