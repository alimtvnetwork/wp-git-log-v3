---
kind: future-spec
drift_acknowledged: 2026-04-26
---

# Security Guidelines

**Version:** 2.2.0  
**Status:** Active  
**Updated:** 2026-04-27  
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

## Supply-Chain Pinning Contract (JSON Schema)

Every dependency declared in `package.json`, `composer.json`, `go.mod`, or `Cargo.toml` MUST resolve against this contract. The CI guard `linter-scripts/check-axios-version.sh` is the reference implementation; analogous guards inherit the same shape.

```json
{
  "$schema": "https://json-schema.org/draft/2020-12/schema",
  "$id": "https://lovable.dev/spec/11-security.schema.json",
  "title": "DependencyPinningContract",
  "type": "object",
  "required": ["ecosystem", "package", "policy"],
  "properties": {
    "ecosystem": { "type": "string", "enum": ["npm", "composer", "go-mod", "cargo"] },
    "package":   { "type": "string", "minLength": 1 },
    "policy": {
      "type": "object",
      "required": ["pin_strategy", "approved_versions"],
      "properties": {
        "pin_strategy":     { "type": "string", "enum": ["exact", "patch-range", "minor-range"], "default": "exact" },
        "approved_versions":{ "type": "array",  "items": { "type": "string", "pattern": "^[0-9]+\\.[0-9]+\\.[0-9]+(?:-[0-9A-Za-z.-]+)?$" }, "minItems": 1 },
        "forbidden_ranges": { "type": "array",  "items": { "type": "string" } },
        "cve_exceptions":   { "type": "array",  "items": { "type": "string", "pattern": "^CVE-[0-9]{4}-[0-9]{4,}$" } }
      }
    },
    "violation_code": { "type": "string", "const": "SECURITY-PIN-001" }
  },
  "additionalProperties": false
}
```

---

## Cross-References

| Reference | Location |
|-----------|----------|
| Parent Overview | [../00-overview.md](../00-overview.md) |
| Cross-Language Guidelines | [../01-cross-language/00-overview.md](../01-cross-language/00-overview.md) |
| File & Folder Naming | [../08-file-folder-naming/00-overview.md](../08-file-folder-naming/00-overview.md) |

---

*Security guidelines — single source of truth for all security-related coding policies.*

---

## Drift Acknowledgment

**Date:** 2026-04-26  
**Status:** Forward-looking spec — drift expected.

Sub-module `01-axios-version-control/` referenced by ACs lives in downstream JS tooling repo. Spec-only repo holds the contract; implementation is external.

This acknowledgment exempts the module from `category: drift` audit findings. See `.lovable/memory/index.md` Phase 27b note.

**Phase 27d (2026-04-26):** Version banner (3.2.0) vs AC version (2.0.0) — AC tracks its own minor cycle independent of overview. Intentional decoupling.

---

## Normative Contract (Phase 50)

```text
CONTRACT: coding-guidelines/security
PURPOSE: cross-language security floor for all generated and hand-written code
SCOPE: applies to every §02 language sub-module unless explicitly exempted

INV-01  no plaintext secrets in source, fixtures, snapshots, or example commands
INV-02  every outbound HTTP call MUST go through the project's vetted client (no raw net libs)
INV-03  every dependency version MUST be pinned (no floating ^ ~ * tags in lockstep manifests)
INV-04  every input crossing a trust boundary MUST be validated against an explicit schema
INV-05  every error path MUST avoid leaking PII, tokens, paths, or stack traces to end users
INV-06  every cryptographic primitive MUST be the platform-default high-level API (no hand-rolled crypto)
INV-07  every authentication check MUST happen server-side; client checks are advisory only

FAIL-01 secret detected by repo scanner → CI blocks merge (severity=blocker)
FAIL-02 unpinned dependency in lockstep manifest → CI blocks merge
FAIL-03 raw http/socket usage outside the vetted client → code review rejects
FAIL-04 user-controlled string concatenated into SQL/shell/eval → code review rejects

DEL-01  TLS termination, WAF, and network policy are owned by deployment platform
DEL-02  per-language idiomatic guidance lives in each §02 language sub-module
DEL-03  axios pinning specifically delegated to §02/11/01-axios-version-control sub-spec
```
