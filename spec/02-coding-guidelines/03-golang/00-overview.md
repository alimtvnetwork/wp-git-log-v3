---
kind: future-spec
drift_acknowledged: 2026-04-26
---

# Golang Standards

**Version:** 3.2.0  
**Status:** Active  
**Updated:** 2026-04-16  
**AI Confidence:** High  
**Ambiguity:** None

---


## Keywords

`coding`, `golang`, `guidelines`

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

Go-specific coding standards and patterns.

---

## Document Inventory

| File |
|------|
| 02-boolean-standards.md |
| 03-httpmethod-enum.md |
| 04-golang-standards-reference.md |
| 05-defer-rules.md |
| 06-string-slice-internals.md |
| 07-code-severity-taxonomy.md |
| 08-pathutil-fileutil-spec.md |
| 98-changelog.md |
| 99-consistency-report.md |
| 97-acceptance-criteria.md |

| 02-boolean-standards.md |
| 03-httpmethod-enum.md |
| 04-golang-standards-reference.md |
| 05-defer-rules.md |
| 06-string-slice-internals.md |
| 07-code-severity-taxonomy.md |
| 08-pathutil-fileutil-spec.md |
| 97-acceptance-criteria.md |
| 98-changelog.md |
| 99-consistency-report.md |
---

## Cross-References

_See parent folder's `00-overview.md` for broader context._

---

## Drift Acknowledgment

**Date:** 2026-04-26  
**Status:** Forward-looking spec — drift expected.

Spec mandates apperror.Result error handling; the lone Go file in this repo is a meta-linter for spec validation, not application code. Real Go implementation lives downstream.

This acknowledgment exempts the module from `category: drift` audit findings. See `.lovable/memory/index.md` Phase 27c note.


## Inlined Contracts (Phase 51 — boost)

### go.mod / build invariants — JSON Schema 2020-12

```json
{
  "$schema": "https://json-schema.org/draft/2020-12/schema",
  "$id": "https://spec.local/02-coding-guidelines/03-golang/build-invariants.schema.json",
  "title": "GolangBuildInvariants",
  "type": "object",
  "required": ["go_version", "module_path", "linters_enabled"],
  "additionalProperties": false,
  "properties": {
    "go_version":      { "type": "string", "pattern": "^1\\.(2[2-9]|[3-9]\\d)(\\.\\d+)?$" },
    "module_path":     { "type": "string", "pattern": "^[a-z0-9._/-]+$" },
    "cgo_enabled":     { "const": false },
    "linters_enabled": {
      "type": "array",
      "minItems": 5,
      "items": { "enum": ["govet","staticcheck","errcheck","ineffassign","unused","gocritic","revive","gosec","gosimple"] },
      "uniqueItems": true
    },
    "test_race":       { "const": true }
  }
}
```

### Canonical Go contract (typed-language reference)

```go
// Package errors: every public function returning an error MUST wrap with %w.
package errors

import "fmt"

// LogLevel mirrors the canonical TS/C# LogLevel enum 1:1.
type LogLevel int

const (
    Fatal LogLevel = iota
    Error
    Warn
    Info
    Debug
    Trace
)

// Result is the discriminated-union convention for fallible APIs in Go.
type Result[T any] struct {
    Ok  *T
    Err error
}

func Wrap(op string, err error) error {
    if err == nil { return nil }
    return fmt.Errorf("%s: %w", op, err)
}
```

### Canonical handler signature

```go
package httpx

import (
    "context"
    "net/http"
)

// Handler is the only acceptable HTTP handler shape under this guideline.
// Raw http.HandlerFunc is forbidden — use this adapter.
type Handler func(ctx context.Context, w http.ResponseWriter, r *http.Request) error

// Adapt converts a Handler to net/http's HandlerFunc with logging + recovery.
func Adapt(h Handler) http.HandlerFunc {
    return func(w http.ResponseWriter, r *http.Request) {
        _ = h(r.Context(), w, r)
    }
}
```
