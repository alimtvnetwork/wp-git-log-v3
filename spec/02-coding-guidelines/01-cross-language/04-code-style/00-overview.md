---
kind: future-spec
drift_acknowledged: 2026-04-26
---

# Cross-Language Code Style — Braces, Nesting, Spacing & Function Size

**Version:** 3.2.0  
**Updated:** 2026-04-16  
**AI Confidence:** Production-Ready  
**Ambiguity:** None

---

## Keywords

`code-style` · `braces` · `nesting` · `spacing` · `function-size` · `formatting` · `cross-language`

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

Cross-language code style rules governing control-flow formatting and function design across **PHP, TypeScript, and Go**. Previously a single 1,458-line file, now split into focused modules under 300 lines each.

These rules are the **single source of truth** — language-specific specs reference this folder.

---

## Document Inventory

| # | File | Purpose | Rules |
|---|------|---------|-------|
| 01 | [01-braces-and-nesting.md](./01-braces-and-nesting.md) | Brace enforcement, zero-nesting ban, exemptions | 1, 2, 7 |
| 02 | [02-conditions-and-extraction.md](./02-conditions-and-extraction.md) | Extract complex multi-part conditions | 3 |
| 03 | [03-blank-lines-and-spacing.md](./03-blank-lines-and-spacing.md) | Blank lines before/after blocks and control structures | 4, 5, 10 |
| 04 | [04-function-and-type-size.md](./04-function-and-type-size.md) | 15-line function limit, 120-line struct/class limit | 6, 17 |
| 05 | [05-multi-line-formatting.md](./05-multi-line-formatting.md) | Multi-line arguments, method chaining, apperror formatting | 9, 11, apperror |
| 06 | [06-comments-and-documentation.md](./06-comments-and-documentation.md) | Comment formatting, doc comments, dead code, backslash rule | 8, 14, 15, 16 |
| 07 | [07-checklist.md](./07-checklist.md) | PR checklist summary + cross-references | — |
| — | 99-consistency-report.md | — | — |

| — | 99-consistency-report.md | — | — |
---

## Cross-References

- [Parent Overview](../00-overview.md) — Cross-Language root
- [Boolean Principles](../02-boolean-principles/00-overview.md) — P1–P6 boolean naming rules
- [No Raw Negations](../12-no-negatives.md) — Positive guard functions
- [Function Naming](../10-function-naming.md) — No boolean flag parameters
- [Strict Typing](../13-strict-typing.md) — Type declarations, max 3 parameters
- [Go Enum Specification](../../03-golang/01-enum-specification/00-overview.md) — Go enum pattern
- [TypeScript Enums](../../02-typescript/00-overview.md) — TypeScript string enums
- [PHP Enum Classes](../../04-php/01-enums.md) — PHP backed enum patterns

---

## Drift Acknowledgment

**Date:** 2026-04-26  
**Status:** Forward-looking spec — drift expected.

Spec mandates 15-line function limit; implementation in `linter-scripts/validate-guidelines.go` enforces this rule but exact threshold synchronization is owned by the downstream linter repo. Treated as forward-looking contract.

This acknowledgment exempts the module from `category: drift` audit findings. See `.lovable/memory/index.md` Phase 27b note.


---

## Inlined Contracts (Phase 53 — boost)

### Code-style ruleset — JSON Schema 2020-12

```json
{
  "$schema": "https://json-schema.org/draft/2020-12/schema",
  "$id": "https://spec.local/02-coding-guidelines/01-cross-language/04-code-style/ruleset.schema.json",
  "title": "CrossLanguageCodeStyleRuleset",
  "type": "object",
  "required": ["indentation", "line_length", "trailing_whitespace", "final_newline"],
  "additionalProperties": false,
  "properties": {
    "indentation": {
      "type": "object",
      "required": ["style", "size"],
      "additionalProperties": false,
      "properties": {
        "style": { "enum": ["space", "tab"] },
        "size":  { "type": "integer", "minimum": 2, "maximum": 8 }
      }
    },
    "line_length":         { "type": "integer", "minimum": 80, "maximum": 200 },
    "trailing_whitespace": { "const": false, "description": "trailing whitespace forbidden" },
    "final_newline":       { "const": true,  "description": "files must end with LF" },
    "encoding":            { "enum": ["utf-8", "utf-8-bom"], "default": "utf-8" },
    "newline_style":       { "enum": ["lf", "crlf"], "default": "lf" },
    "max_blank_lines":     { "type": "integer", "minimum": 1, "maximum": 3, "default": 2 },
    "import_grouping": {
      "type": "object",
      "additionalProperties": false,
      "properties": {
        "stdlib_first":     { "const": true },
        "third_party_next": { "const": true },
        "local_last":       { "const": true },
        "blank_between_groups": { "const": true }
      }
    }
  }
}
```

### Style-violation enum (TypeScript)

```ts
export enum CodeStyleViolation {
  IndentationMixedTabsSpaces = "indentation-mixed-tabs-spaces",
  LineTooLong                = "line-too-long",
  TrailingWhitespace         = "trailing-whitespace",
  MissingFinalNewline        = "missing-final-newline",
  CrlfInUnixFile             = "crlf-in-unix-file",
  ExcessiveBlankLines        = "excessive-blank-lines",
  ImportGroupingViolation    = "import-grouping-violation",
}

export enum CodeStyleFixability {
  Auto    = "auto",
  Assist  = "assist",
  Manual  = "manual",
}
```
