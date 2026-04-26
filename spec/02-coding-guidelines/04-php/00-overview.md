---
kind: future-spec
description: Forward-looking PHP coding standards for the RiseupAsia namespace. The implementation lives in downstream repositories (WordPress plugins, PHP libraries) and is intentionally NOT present in this spec-only repo. Exempt from drift findings that flag missing PHP source files.
---

# PHP Standards

**Version:** 3.3.0  
**Status:** Active (future-spec — implementation lives downstream)  
**Updated:** 2026-04-26  
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
