---
kind: future-spec
drift_acknowledged: 2026-04-26
---

# Golang Coding Standards

**Version:** 3.2.0  
**Updated:** 2026-04-16  
**AI Confidence:** Production-Ready  
**Ambiguity:** None

---

## Keywords

`04-golang-standards-reference` · `coding-standards`

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

Previously a single 1281-line file, now split into focused modules under 300 lines each.

---

## Document Inventory

| # | File | Purpose | Lines |
|---|------|---------|-------|
| — | [01-file-and-function-rules.md](./01-file-and-function-rules.md) | File naming, size, function size, nesting ban | 224 |
| — | [02-type-safety-and-errors.md](./02-type-safety-and-errors.md) | Type safety, error handling, Result types | 362 |
| — | [03-database-and-structs.md](./03-database-and-structs.md) | Database naming, dbutil wrapper, struct design | 123 |
| — | [04-naming-and-organization.md](./04-naming-and-organization.md) | File organization, naming conventions, negations, guards | 272 |
| — | [05-enums-and-dry.md](./05-enums-and-dry.md) | Typed constants, enums, DRY enforcement | 186 |
| — | [06-concurrency-and-patterns.md](./06-concurrency-and-patterns.md) | Concurrency, forbidden patterns, imports, common mistakes | 274 |
| — | 99-consistency-report.md | — | — |

| — | 99-consistency-report.md | — | — |
---

## Cross-References

- [No Raw Negations](../../01-cross-language/12-no-negatives.md) — Positive guard functions (all languages)
- [Cross-Language Code Style](../../01-cross-language/04-code-style/00-overview.md) — Braces, nesting & spacing rules
- [Function Naming](../../01-cross-language/10-function-naming.md) — No boolean flag parameters
- [Strict Typing](../../01-cross-language/13-strict-typing.md) — Type declarations & docblock rules
- [DRY Principles](../../01-cross-language/08-dry-principles.md)
- [Boolean Standards](../02-boolean-standards.md) — Go-specific positive logic rules and exemptions
- apperror Package Spec — Full StackTrace, AppError, Result types specification <!-- external: spec/03-error-manage/01-error-resolution/10-apperror-package/01-apperror-reference.md -->
- [Enum Specification](../01-enum-specification/00-overview.md) — Byte-based enum pattern, required methods, folder structure
- [Master Coding Guidelines](../../01-cross-language/15-master-coding-guidelines/00-overview.md) — Consolidated cross-language reference
- [Issues & Fixes Log](../../01-cross-language/01-issues-and-fixes-log.md) — Full historical fixes
- [golangci-lint Enforcement](../../01-cross-language/16-static-analysis/02-go-golangci-lint.md) — Linter rule mapping for Go guidelines

---

## Drift Acknowledgment

**Date:** 2026-04-26  
**Status:** Forward-looking spec — drift expected.

PascalCase Go file naming is intentional house-style contract; standard Go snake_case convention is overridden by project linter (downstream).

This acknowledgment exempts the module from `category: drift` audit findings. See `.lovable/memory/index.md` Phase 27c note.


## Inlined Contracts (Phase 51 — boost)

### golangci-lint required ruleset — JSON Schema 2020-12

```json
{
  "$schema": "https://json-schema.org/draft/2020-12/schema",
  "$id": "https://spec.local/02-coding-guidelines/03-golang/04-golang-standards-reference/golangci.schema.json",
  "title": "GolangciLintRequiredConfig",
  "type": "object",
  "required": ["run", "linters"],
  "additionalProperties": true,
  "properties": {
    "run": {
      "type": "object",
      "required": ["timeout", "tests"],
      "additionalProperties": true,
      "properties": {
        "timeout": { "type": "string", "pattern": "^\\d+(s|m)$" },
        "tests":   { "const": true }
      }
    },
    "linters": {
      "type": "object",
      "required": ["enable", "disable-all"],
      "additionalProperties": true,
      "properties": {
        "disable-all": { "const": true },
        "enable": {
          "type": "array", "minItems": 5,
          "items": { "enum": ["govet","staticcheck","errcheck","ineffassign","unused","gocritic","revive","gosec","gosimple"] },
          "uniqueItems": true
        }
      }
    }
  }
}
```

### Reference idiomatic patterns (typed-language contract)

```go
// Pattern: context as first arg, error as last return.
package svc

import "context"

type Service interface {
    Get(ctx context.Context, id string) (*Entity, error)
    List(ctx context.Context, q Query)  ([]*Entity, error)
    Put(ctx context.Context, e *Entity) error
}

type Entity struct {
    ID   string `json:"id"`
    Name string `json:"name"`
}
```

```go
// Pattern: option struct + functional options for extensibility.
package client

import "time"

type Option func(*Client)

type Client struct {
    timeout time.Duration
    retries int
}

func WithTimeout(d time.Duration) Option { return func(c *Client) { c.timeout = d } }
func WithRetries(n int) Option           { return func(c *Client) { c.retries = n } }

func New(opts ...Option) *Client {
    c := &Client{timeout: 5 * time.Second, retries: 3}
    for _, o := range opts { o(c) }
    return c
}
```

```go
// Pattern: table-driven tests are mandatory for any pure function.
package math_test

import "testing"

func TestAdd(t *testing.T) {
    cases := []struct{ name string; a, b, want int }{
        {"zero", 0, 0, 0},
        {"pos",  2, 3, 5},
        {"neg", -1, 1, 0},
    }
    for _, tc := range cases {
        t.Run(tc.name, func(t *testing.T) {
            if got := tc.a + tc.b; got != tc.want {
                t.Fatalf("got %d, want %d", got, tc.want)
            }
        })
    }
}
```
