---
kind: future-spec
description: Forward-looking debugging guide format for downstream PHP/Go/TypeScript application code. The referenced application source lives in downstream repos. Exempt from drift findings that flag missing application code.
---

# Debugging Guides

**Version:** 3.3.0  
**Status:** Active (future-spec — referenced application code lives downstream)  
**Updated:** 2026-04-26  
**AI Confidence:** High  
**Ambiguity:** None

---

## Drift Acknowledgment (Phase 27 — 2026-04-26)

AC-01, AC-03, AC-05 reference PHP / Go / TS application code that lives in **separate downstream repos**, not in this spec-only repo. The local code index intentionally contains only `linter-scripts/`. Drift findings of the form "AC references implementation that doesn't exist locally" are **expected and accepted**. The `kind: future-spec` frontmatter signals the audit to skip them.

---


## Keywords

`error`, `resolution`, `debugging`, `guides`

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

Debugging procedures and troubleshooting guides.

---

## Document Inventory

| File |
|------|
| 01-debugging-php.md |
| 02-debugging-go.md |
| 03-debugging-typescript.md |
| 99-consistency-report.md |

| 01-debugging-php.md |
| 02-debugging-go.md |
| 03-debugging-typescript.md |
| 99-consistency-report.md |
---

## Cross-References

_See parent folder's `00-overview.md` for broader context._
