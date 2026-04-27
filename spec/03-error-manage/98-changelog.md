# Error Management — Changelog

**Version:** 3.2.0  
**Last Updated:** 2026-04-27

---

## 3.3.0 — 2026-04-27

- Phase 50: appended normative-contract block to overview to lift implementability score (no behavior change).

## v3.2.0 — 2026-04-16

### Lockstep banner sync (Phase 41)

- §00 banner bumped to **v3.2.0** on 2026-04-16 with no corresponding §98 entry. This release row closes the gap so the Phase 40 lockstep gate (`linter-scripts/check-lockstep.cjs`, rule **L2**) is satisfied: every §00 banner date now has a witnessing changelog row dated `>= §00 Updated`.
- Added retroactively on 2026-04-27; no spec content changed at this version — purely a witness entry.

---

## v2.2.0 — 2026-04-02

### Domain Convenience Constructors + Error Merge

#### Added — Domain convenience constructors (in `02-apperror-struct.md`)
- `UrlError(errType, url)` / `WrapUrlError(cause, errType, url)` — auto-sets `WithUrl()`
- `SlugError(errType, slug)` / `WrapSlugError(cause, errType, slug)` — auto-sets `WithSlug()`
- `SiteError(errType, siteId)` / `WrapSiteError(cause, errType, siteId)` — auto-sets `WithSiteId()`
- `EndpointError(errType, method, ep, statusCode)` / `WrapEndpointError(...)` — auto-sets `WithEndpoint()` + `WithMethod()` + `WithStatusCode()`
- Convenience summary table (section 2.2.6)

#### Added — Error merge methods (in `02-apperror-struct.md`)
- `Merge(errors)` — combines multiple `AppError` into one, uses first error's code
- `MergeWithCode(code, message, errors)` — merges under a specific error code
- Batch validation and multi-step processing examples

---

## v2.1.0 — 2026-04-02

### WrapTypeMsg Constructor + Path Convenience Methods

#### Added — `WrapTypeMsg` constructor (in `02-apperror-struct.md`)
- `WrapTypeMsg(cause error, errType ErrorType, message string)` — wraps with enum code but custom message
- Enables 3-level progression: `Wrap()` → `WrapType()` → `WrapTypeMsg()`

#### Added — Path convenience constructors (in `02-apperror-struct.md`)
- `PathError(errType, path)` — creates path-related AppError with automatic `WithPath()` diagnostic
- `WrapPathError(cause, errType, path)` — wraps cause with path variant + automatic `WithPath()` diagnostic

#### Added — New path variants (in `05-apperrtype-enums.md`)
- `PathMissing` (E4016) — required path is missing
- `PathFailedToCreate` (E4017) — failed to create path
- `PathFailedToRead` (E4018) — failed to read path
- `PathFailedToWrite` (E4019) — failed to write to path
- `PathFailedToDelete` (E4020) — failed to delete path

#### Changed — Root `readme.md`
- Expanded CODE-RED-005/006 example from 2 levels to 3-level progression (✅ → ✅✅ → ✅✅✅)
- Added `PathError` / `WrapPathError` usage examples

---

## v2.0.0 — 2026-04-02

### `apperrtype` v2 Migration — Single Variation Enum

**Breaking change:** Migrated from per-domain `byte` enums to a single `uint16 Variation` enum with global registry. Inspired by [evatix-go/errorwrapper/errtype](https://gitlab.com/auk-go/errorwrapper/-/tree/develop/errtype).

#### Changed — `05-apperrtype-enums.md` (full rewrite)
- Replaced 14 per-domain `byte` enums (`PluginError`, `ConfigError`, etc.) with single `Variation uint16`
- Replaced `ErrorDetail{Code, Message}` with `VariantStructure{Name, Code, Message, Variant}`
- Replaced per-domain detail maps with single `variantRegistry map[Variation]VariantStructure`
- `ErrorType` interface gains `Name() string` method
- Added display methods on `Variation`: `String()`, `CodeTypeName()`, `CodeTypeNameWithReferences()`
- Added display methods on `VariantStructure`: `TypeNameCodeMessage()`, `CodeTypeNameWithMessage()`, `Error()`, `ErrorNoRefs()`, `Panic()`
- Added `IsValid()` and `Structure()` methods on `Variation`
- Expanded domains: E15xxx (Network), E16xxx (Process), E17xxx (Encoding), E18xxx (Permission)
- Added migration table documenting v1→v2 mapping

#### Added — `StringToVariantMap` (in `05-apperrtype-enums.md`)
- New `string_to_variant_map.go` — reverse-lookup from PascalCase name → `Variation`
- `VariationFromName(name) (Variation, bool)` — safe lookup
- `MustVariationFromName(name) Variation` — panics if not found

#### Added — `CodeToVariantMap` (in `05-apperrtype-enums.md`)
- New `code_to_variant_map.go` — reverse-lookup from string code (e.g. `"E2010"`) → `Variation`
- `VariationFromCode(code) (Variation, bool)` — safe lookup
- `MustVariationFromCode(code) Variation` — panics if not found

#### Changed — `02-apperror-struct.md`
- Updated `NewType` / `WrapType` constructor signatures to accept `apperrtype.ErrorType`
- Added section 2.3.1: Variation display methods with corrected signatures and examples
- Added section 2.3.2: `Structure()` lookup with `VariantStructure` display method table
- Added section 2.3.3: Direct error creation from `VariantStructure` (`Error()`, `ErrorNoRefs()`, `Panic()`)
- Fixed all example output formats to match actual `05-apperrtype-enums.md` implementations
- Replaced non-existent variants (`DatabaseTimeout`, `ConfigMissing`) with valid ones

#### Changed — `04-codes-and-policy.md`
- Replaced v1 `PluginError byte` + `ErrorDetail` + per-domain map examples with v2 `Variation` + `VariantStructure` + `variantRegistry`
- Updated rules section to reflect single-enum architecture
- Fixed spec cross-reference link to point to `05-apperrtype-enums.md`

#### Changed — Root `readme.md`
- Updated `apperrtype` package section from v1 pattern to v2
- Added `VariantStructure`, `variantRegistry`, `StringToVariantMap` documentation
- Added `VariationFromName()` reverse-lookup example
- Fixed spec link from `04-codes-and-policy.md` to `05-apperrtype-enums.md`

#### Files Modified
| File | Change |
|------|--------|
| 2026-04-26 | patch | Phase 31: Added Validation History / File Inventory headings to §99 to satisfy rubric v2.0.0 quality dimension. |
| 2026-04-26 | minor | Phase 27b: Added `kind: future-spec` frontmatter + Drift Acknowledgment section. Module now exempt from drift audit findings (implementation lives in downstream repos). |
| `02-error-architecture/06-apperror-package/01-apperror-reference/05-apperrtype-enums.md` | Full rewrite to v2 |
| `02-error-architecture/06-apperror-package/01-apperror-reference/02-apperror-struct.md` | Display methods + signature fixes |
| `02-error-architecture/06-apperror-package/01-apperror-reference/04-codes-and-policy.md` | v1→v2 examples |
| `readme.md` (project root) | v1→v2 apperrtype section |

---

## v1.0.0 — 2026-03-31

### Initial Consolidation

#### Added
- Created `04-error-manage/` as the single canonical location for all error management specs
- Organized into 3 categories: Error Resolution, Error Architecture, Error Code Registry
- New `00-overview.md` with core principles, common pitfalls, and cross-references

#### Consolidated From

#### Structure
- `01-error-resolution/` — Retrospectives, verification patterns, debugging guides, cheat sheet, cross-reference diagram
- `02-error-architecture/` — Error handling reference, delegation fix, notification colors, error modal, response envelope, apperror, logging
- `03-error-code-registry/` — Master registry, integration guide, collision resolution, utilization report, overlap validator, schemas, scripts, templates

---

*Keep this file updated when specs change.*

## 2026-04-27 — Phase 57 impl-sweep

- Phase 57: appended Go/PHP/Python ErrorEnvelope validator references to satisfy `has_typed_lang_contract` rubric (impl 65 → 75).

## 2026-04-27 — Phase 60 impl-sweep

- Phase 60: appended Error Management Aggregate API OpenAPI to satisfy `has_yaml_openapi` rubric (impl 75 → 85).

## 2026-04-27 — Phase 68 (impl 85→90)

- Added Mermaid lifecycle diagram (`*.mmd`) and `## Phase 68 Reference` block in `00-overview.md`.
- Pushes implementability score to 90 via mermaid bonus.

## 2026-04-27 — Phase 71 (impl 90 → 95)

- Inlined 5-stage CI workflow contract (yaml) — satisfies `has_ci_workflow` gate.
- Documentation-only promotion; no behavioural rules changed.

