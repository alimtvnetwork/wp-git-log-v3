# Security Guidelines

**Version:** 2.1.0  
**Status:** Active  
**Updated:** 2026-04-26  
**AI Confidence:** High  
**Ambiguity:** None

---

## Keywords

`security` · `dependency-pinning` · `vulnerability` · `version-control` · `axios` · `supply-chain` · `cve` · `audit`

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

Central location for all **security-related coding guidelines**, policies, and advisory documentation. This module covers dependency security, version pinning policies, vulnerability tracking, and secure coding practices.

Any security discussion, advisory, or policy that affects how code is written or dependencies are managed belongs here.

---

## Categories

| # | Subfolder | Description | Files |
|---|-----------|-------------|-------|
| 01 | [01-axios-version-control/](./01-axios-version-control/00-overview.md) | Axios HTTP client version pinning policy and security advisory | 4 |

---

## When to Add Content Here

Add a new subfolder under `11-security/` when:

- A **dependency security vulnerability** is discovered and requires a pinning policy
- A **secure coding pattern** needs to be documented (e.g., input sanitization, auth token handling)
- A **supply chain security** concern arises (e.g., compromised packages)
- A **security audit** produces findings that should be codified as rules

### Subfolder Template

```
11-security/
└── NN-{topic-name}/
    ├── 00-overview.md              ← Policy summary, version matrix
    ├── 01-implementation-rules.md  ← How to enforce the policy
    ├── 02-security-notes.md        ← Detailed advisory, audit trail
    └── 99-consistency-report.md    ← Health check
```

---

## Normative Contract — Security Policy Manifest

Every security subfolder MUST publish a machine-readable manifest matching the
JSON schema below. `linter-scripts/check-security-policies.py` consumes these
manifests to enforce dependency pinning, CVE acknowledgement, and
forbidden-string detection across every language target.

```text
{
  "$schema": "https://json-schema.org/draft/2020-12/schema",
  "$id": "spec/02-coding-guidelines/11-security/policy.schema.json",
  "title": "SecurityPolicy",
  "type": "object",
  "required": ["id", "title", "severity", "scope", "enforcement", "rules"],
  "properties": {
    "id":       { "type": "string", "pattern": "^SEC-[A-Z]+-[0-9]{3}$" },
    "title":    { "type": "string", "minLength": 1 },
    "severity": { "enum": ["critical", "high", "medium", "low", "advisory"] },
    "scope": {
      "type": "object",
      "required": ["languages", "ecosystems"],
      "properties": {
        "languages":  { "type": "array", "items": { "enum": ["go", "ts", "js", "php", "rust", "csharp"] } },
        "ecosystems": { "type": "array", "items": { "enum": ["npm", "composer", "go-mod", "cargo", "nuget"] } }
      }
    },
    "enforcement": {
      "type": "object",
      "required": ["linter", "ci_required", "forbidden_strings_toml"],
      "properties": {
        "linter":                 { "type": "string", "minLength": 1 },
        "ci_required":            { "type": "boolean" },
        "forbidden_strings_toml": { "type": "string", "pattern": "^.+\\.toml$" }
      }
    },
    "rules": {
      "type": "array",
      "minItems": 1,
      "items": {
        "type": "object",
        "required": ["rule_id", "kind", "pattern", "remediation"],
        "properties": {
          "rule_id":     { "type": "string", "pattern": "^R-[0-9]{3}$" },
          "kind":        { "enum": ["pin-version", "forbid-string", "require-header", "audit-cve"] },
          "pattern":     { "type": "string", "minLength": 1 },
          "remediation": { "type": "string", "minLength": 1 }
        }
      }
    },
    "cve_refs": {
      "type": "array",
      "items": { "type": "string", "pattern": "^CVE-[0-9]{4}-[0-9]+$" }
    }
  }
}
```

> **Enforcement.** A subfolder without a conforming manifest fails the
> security gate; `01-axios-version-control/` is the reference implementation.
> Forbidden-string detection uses `linter-scripts/forbidden-strings.toml`
> when `enforcement.forbidden_strings_toml` is omitted.

---

## Cross-References

| Reference | Location |
|-----------|----------|
| Parent Overview | [../00-overview.md](../00-overview.md) |
| Cross-Language Guidelines | [../01-cross-language/00-overview.md](../01-cross-language/00-overview.md) |
| File & Folder Naming | [../08-file-folder-naming/00-overview.md](../08-file-folder-naming/00-overview.md) |

---

*Security guidelines — single source of truth for all security-related coding policies.*
