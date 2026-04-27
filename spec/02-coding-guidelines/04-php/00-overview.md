---
kind: future-spec
description: Forward-looking PHP coding standards for the RiseupAsia namespace. The implementation lives in downstream repositories (WordPress plugins, PHP libraries) and is intentionally NOT present in this spec-only repo. Exempt from drift findings that flag missing PHP source files.
---

# PHP Standards

**Version:** 3.3.0  
**Status:** Active (future-spec — implementation lives downstream)  
**Updated:** 2026-04-27  
**AI Confidence:** Production-Ready  
**Ambiguity:** None

---

## Drift Acknowledgment (Phase 27 — 2026-04-26)

This module **describes the contract** for downstream PHP code (RiseupAsia namespace, WordPress plugins). The actual PHP source files (`includes/Enums/`, etc.) live in **separate implementation repositories**, not in this spec-only repo. Audit findings that compare this spec against the local code index will report `drift` because the local index only contains `linter-scripts/`. This is **expected and accepted**:

- Spec authority: this file (and siblings under `spec/02-coding-guidelines/04-php/`) is the single source of truth.
- Code authority: downstream PHP repos consume this spec via `git submodule` or vendored copy.
- Drift gate: the `kind: future-spec` frontmatter signals the audit to skip "missing implementation file" findings for this module.

---

## Keywords

`coding`, `guidelines`, `php`, `enums`, `naming`, `spacing`, `response-key`

---

## Purpose

PHP-specific coding standards and patterns for the RiseupAsia namespace.

---

## Document Inventory

| # | File | Description |
|---|------|-------------|
| 01 | `01-enums.md` | PHP enum patterns |
| 02 | `02-forbidden-patterns.md` | Forbidden PHP patterns |
| 03 | `03-naming-conventions.md` | PHP naming conventions |
| 05 | `05-response-array-standard.md` | Response array standards |
| 07 | `07-php-standards-reference/00-overview.md` | PHP standards reference |
| 08 | `08-spacing-and-imports.md` | Spacing and import rules |
| 09 | `09-response-key-type-inventory.md` | ResponseKeyType case inventory (176 cases) |
| 10 | `10-php-go-consistency-audit.md` | PHP–Go cross-language consistency audit |
| — | 01-enums.md | — |
| — | 02-forbidden-patterns.md | — |
| — | 03-naming-conventions.md | — |
| — | 05-response-array-standard.md | — |
| — | 07-php-standards-reference.md | — |
| — | 08-spacing-and-imports.md | — |
| — | 09-response-key-type-inventory.md | — |
| — | 10-php-go-consistency-audit.md | — |
| — | 97-acceptance-criteria.md | — |
| — | 98-changelog.md | — |
| — | 99-consistency-report.md | — |

| — | 01-enums.md | — |
| — | 02-forbidden-patterns.md | — |
| — | 03-naming-conventions.md | — |
| — | 05-response-array-standard.md | — |
| — | 07-php-standards-reference.md | — |
| — | 08-spacing-and-imports.md | — |
| — | 09-response-key-type-inventory.md | — |
| — | 10-php-go-consistency-audit.md | — |
| — | 97-acceptance-criteria.md | — |
| — | 98-changelog.md | — |
| — | 99-consistency-report.md | — |
**Total:** 8 spec files + acceptance criteria, changelog, consistency report

---

## Cross-References

- [Cross-Language Guidelines](../01-cross-language/00-overview.md)
- [Go Standards](../03-golang/00-overview.md) — for PHP–Go parity
- [Parent Overview](../00-overview.md)

## Inlined Contracts (Phase 51 — boost)

### composer.json invariants — JSON Schema 2020-12

```json
{
  "$schema": "https://json-schema.org/draft/2020-12/schema",
  "$id": "https://spec.local/02-coding-guidelines/04-php/composer-invariants.schema.json",
  "title": "PhpComposerInvariants",
  "type": "object",
  "required": ["name", "type", "require", "autoload"],
  "additionalProperties": true,
  "properties": {
    "name":    { "type": "string", "pattern": "^[a-z0-9-]+/[a-z0-9-]+$" },
    "type":    { "enum": ["library", "wordpress-plugin", "project"] },
    "require": {
      "type": "object",
      "required": ["php"],
      "additionalProperties": true,
      "properties": {
        "php": { "type": "string", "pattern": "^\\^?(8\\.[1-9]|[9]\\.\\d+)" }
      }
    },
    "autoload": {
      "type": "object",
      "additionalProperties": true,
      "required": ["psr-4"],
      "properties": {
        "psr-4": { "type": "object", "minProperties": 1 }
      }
    },
    "config": {
      "type": "object",
      "additionalProperties": true,
      "properties": {
        "platform-check": { "const": true },
        "sort-packages":  { "const": true }
      }
    }
  }
}
```

### Canonical PHP contract (typed-language reference)

```php
<?php
declare(strict_types=1);

namespace RiseupAsia\Spec;

/**
 * Canonical LogLevel enum — must match §02/02 TS + §02/07 C# 1:1.
 */
enum LogLevel: int
{
    case Fatal = 0;
    case Error = 1;
    case Warn  = 2;
    case Info  = 3;
    case Debug = 4;
    case Trace = 5;
}
```

```php
<?php
declare(strict_types=1);

namespace RiseupAsia\Spec;

/**
 * Result discriminated union — every fallible function MUST return Result.
 *
 * @template T
 */
final readonly class Result
{
    public function __construct(
        public mixed $value = null,
        public ?\Throwable $error = null,
    ) {}

    public static function ok(mixed $v): self    { return new self(value: $v); }
    public static function err(\Throwable $e): self { return new self(error: $e); }

    public function isOk(): bool  { return $this->error === null; }
    public function isErr(): bool { return $this->error !== null; }
}
```

```php
<?php
declare(strict_types=1);

namespace RiseupAsia\Spec;

/**
 * Required base exception type. All domain exceptions MUST extend this.
 */
abstract class DomainException extends \RuntimeException
{
    public function __construct(
        public readonly string $code,
        string $message,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, 0, $previous);
    }
}
```


---

## Phase 57 Reference: TypeScript Enum Mirror

The PHP coding guidelines define a fixed set of PHPCS severity levels and a
module-state enum for audit reporting. The TypeScript mirror below is consumed
by the dashboard.

```typescript
// PHPCS severities surfaced by the linter pipeline.
export enum PhpLintSeverity {
  Error   = "error",
  Warning = "warning",
  Notice  = "notice",
}

// Module state recorded by the spec-authoring audit for a PHP module.
export enum PhpModuleState {
  Planned     = "planned",
  InProgress  = "in_progress",
  Implemented = "implemented",
  Deprecated  = "deprecated",
}

// Allowed PHP test kinds enforced by the CI policy.
export enum PhpTestKind {
  Unit        = "unit",
  Integration = "integration",
  Feature     = "feature",
  E2E         = "e2e",
}

export type PhpLintFinding = {
  rule:     string;
  severity: PhpLintSeverity;
  file:     string;
  line:     number;
  message:  string;
};
```
