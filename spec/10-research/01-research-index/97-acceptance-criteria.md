# Acceptance Criteria — Top-Level Research Index

**Version:** 2.1.0
**Updated:** 2026-04-30 (Phase 153 Task A13 — AC-RESEARCH-05 Verifies clause + AC-RESEARCH-07 domain-registry validator)

---

### AC-RESEARCH-01: Inlined contract validates  `[critical]`
- **Given** The contract block in `00-overview.md`.
- **When** The contract is parsed by its language tooling (jsonschema/tsc/sqlite).
- **Then** Parsing MUST succeed with zero diagnostics.

### AC-RESEARCH-02: Schema-required fields enforced  `[critical]`
- **Given** A new entry conforming to the contract.
- **When** A required field is omitted.
- **Then** Validation MUST fail with a clear "missing required field" error citing the field name.

### AC-RESEARCH-03: ID pattern enforced  `[high]`
- **Given** An entry with an `id` field.
- **When** The id does not match the documented regex pattern.
- **Then** Validation MUST fail and the offending value MUST be echoed in the error.

### AC-RESEARCH-04: Lifecycle diagram present and valid  `[high]`
- **Given** This subfolder.
- **When** Listing files.
- **Then** Exactly one `lifecycle-*.mmd` file MUST exist and parse as a valid Mermaid `flowchart TD`.

### AC-RESEARCH-05: Forward-only updates  `[medium]`
- **Given** A change to the contract block in `00-overview.md` (the inlined JSON Schema for `TopLevelResearchEntry`).
- **When** A diff is computed between the prior `00-overview.md` (preceding §98 row) and the new one.
- **Then** Removed `properties.*` keys MUST first be marked deprecated (added to `description` with the prefix `DEPRECATED: …`) for at least one minor §98 version before deletion; renamed fields MUST add the new key AND keep the old key as an alias (both validate) for one minor §98 version.
- **Verifies:** the SemVer minor-bump invariant for the inlined schema (a removed/renamed required field is a breaking change and forbidden inside a single minor bump); enforced by `linter-scripts/check-version-parity.py` (any §00-schema diff without a paired §98 row fails parity) + manual PR review against this AC. **Source:** A13 close of v6 audit D2 LOW finding "Vague Verification Clauses".

### AC-RESEARCH-06: Cross-references stay valid  `[medium]`
- **Given** This subfolder's `00-overview.md`.
- **When** `linter-scripts/check-spec-cross-links.py` runs.
- **Then** Exit code MUST be 0; all relative links MUST resolve.
