# C# Coding Standards

**Version:** 3.2.0  
**Status:** Active  
**Updated:** 2026-04-27  
**AI Confidence:** High  
**Ambiguity:** None

---

## Keywords

`coding` · `guidelines` · `csharp` · `dotnet` · `.net` · `naming` · `async` · `linq`

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

C#-specific coding standards that extend the [cross-language guidelines](../01-cross-language/00-overview.md). These rules apply to all .NET/C# code and align with the project's naming conventions, boolean principles, and method design patterns.

---

## File Index

| # | File | Description |
|---|------|-------------|
| 01 | [Naming and Conventions](./01-naming-and-conventions.md) | PascalCase methods, property naming, abbreviation casing |
| 02 | [Method Design](./02-method-design.md) | Boolean flag splitting, async patterns, LINQ usage |
| 03 | [Error Handling](./03-error-handling.md) | Exception patterns, Result types, guard clauses |
| 04 | [Type Safety](./04-type-safety.md) | Generics, nullable reference types, pattern matching |

---

## Document Inventory

| File |
|------|
| 97-acceptance-criteria.md |
| 98-changelog.md |
| 99-consistency-report.md |


| 01-naming-and-conventions.md |
| 02-method-design.md |
| 03-error-handling.md |
| 04-type-safety.md |
## Cross-References

- [Cross-Language Guidelines](../01-cross-language/00-overview.md) — universal rules applied to C#
- [Boolean Flag Methods](../01-cross-language/24-boolean-flag-methods.md) — method splitting pattern with C# examples
- [Boolean Principles](../01-cross-language/02-boolean-principles/00-overview.md) — boolean naming rules
- [Function Naming](../01-cross-language/10-function-naming.md) — cross-language function naming
- [SOLID Principles](../01-cross-language/23-solid-principles.md) — architecture patterns

---

## Normative Contract (Phase 50)

```text
CONTRACT: coding-guidelines/csharp
PURPOSE: define the binding C# coding floor for all C#/.NET code generated under this spec
SCOPE: every .cs file in implementing repos that target this guideline

INV-01  target framework MUST be .NET 8 LTS or newer; older TFMs require explicit waiver
INV-02  nullable reference types MUST be enabled project-wide (<Nullable>enable</Nullable>)
INV-03  every public type/member MUST have XML doc comments (CS1591 treated as error)
INV-04  every async method MUST end with the suffix "Async" and return Task or ValueTask
INV-05  every IDisposable/IAsyncDisposable MUST be consumed via using/await using
INV-06  exceptions MUST derive from a domain base type; raw throw new Exception(...) forbidden
INV-07  formatting MUST conform to the StyleCop ruleset under linters/stylecop/

FAIL-01 nullable disabled at project or file scope → CI blocks merge
FAIL-02 async method missing Async suffix → analyzer reports error
FAIL-03 raw new Exception(...) usage → analyzer reports error
FAIL-04 missing XML doc on public API → CS1591 fails the build

DEL-01  cross-language naming inherited from §02/01-cross-language
DEL-02  security floor inherited from §02/11-security
DEL-03  logging emission contract inherited from §02/02-typescript/10-log-level-enum
```
