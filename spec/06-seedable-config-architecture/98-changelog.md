# Changelog — Seedable Config Architecture (CW Config)

**Version:** 4.3.0
**Updated:** 2026-04-29
**Scope:** `spec/06-seedable-config-architecture/`

---

## Releases

### 4.3.0 — 2026-04-29 — Phase 153 Task A11f (spec/06 D3 MEDIUM + D5 HIGH closure)

- **Added** AC-SC-21 (CHANGELOG concurrency lock-ordering) — binds AC-SC-11 + AC-SC-16 + AC-SC-17 via a single shared file lock that MUST be acquired BEFORE `BEGIN IMMEDIATE`, held through the COMMIT + CHANGELOG-append + fsync sequence, and released ONLY after fsync. Forbids per-CHANGELOG locks (would race) and forbids release-between-commit-and-changelog (would lose entries). Closes v5 D3 MEDIUM "Ambiguous CHANGELOG.md Write Concurrency".
- **Added** AC-SC-22 (apperror cross-reference) — every `apperror.Wrap`/`apperror.New`/`*apperror.AppError`/`Err*` sentinel/`AB-NNNN` code in `01-fundamentals.md` + `02-features/*.md` MUST resolve via the canonical contract at `spec/03-error-manage/02-error-architecture/06-apperror-package/` and registry at `spec/03-error-manage/03-error-code-registry/01-registry.md`. Forbids local re-definition (Lesson #36 — link, never restate). Sub-feature files introducing new error codes MUST add a registry row in the same PR. Closes v5 D5 HIGH "Missing External Error Code Registry" — replaces auditor's "inline minimal Go pkg" recommendation with the correct cross-module-reference fix.
- **Why:** The two findings were genuine (verified-before-open per Lesson #30): AC-SC-11 and AC-SC-17 implied but did not bind the CHANGELOG↔lock ordering; the Go code samples used `apperror`/`AppError`/`AB-9301`/`ErrSeedLoadFailed` symbols that an AI implementer cannot resolve without explicit binding to spec/03.
- **AC count:** 20 → 22.
- **Lockstep:** §97 4.0.0 → **4.1.0** (new ACs); §00 4.2.0 → **4.3.0**; §98 4.2.0 → **4.3.0** (this row); §99 4.2.0 → **4.3.0**.
- **Closes** Phase 153 v5 audit findings [MEDIUM/D3] + [HIGH/D5] in `06-seedable-config-architecture.json`.

### 4.2.0 — 2026-04-29 — Phase 153 Task A11e (spec/06 D3 Type-enum reconciliation)

- **Changed** `00-overview.md` JSON Schema `Type` enum from legacy storage-type set `{string, int, float, bool, json}` to AC-SC-14's UI-aware enum `{boolean, number, string, select, multiselect}`. The reference instance and Forbidden-shapes table were updated in lockstep (`int`→`number`, `bool`→`boolean`, `Storage.Backend` becomes `Type: select` since it already declares `Validation.Enum`). Inline `description` clause names the migration mapping and explicitly FORBIDS the legacy values.
- **Why:** Phase 153 v5 audit (D3 HIGH "Inconsistent Type Enums between Schema and AC") flagged that the §00 schema and AC-SC-14 advertised two different closed enums for the same `Type` field — an AI implementer would not know which to enforce. Per Lesson #36 (cross-module references must link, not restate) and AC-31-31 (§97 wins on contract conflict), §00 is realigned to AC-SC-14's canonical enum. The UI-aware enum is preferred because (a) it preserves `select`/`multiselect` UI semantics that storage types cannot express, (b) `number` cleanly subsumes `int`/`float` for typed-language consumers, (c) `json`-typed values are better expressed as `string` + `Validation.Pattern`.
- **No new AC** — AC-SC-14 was already canonical; this commit closes the §00↔§97 contract drift in §00's favour.
- **Lockstep:** §00 v4.1.1 → v4.2.0 (contract value-set narrowed); §98 v4.1.1 → v4.2.0 (this row); §99 v4.1.1 → v4.2.0.
- **Closes** Phase 153 v5 audit finding `06-seedable-config-architecture.json` issue [HIGH/D3] "Inconsistent Type Enums between Schema and AC".

### 4.1.1 — 2026-04-29 — Phase 153 Task A2 (canonical PascalCase pin)

- **Added** "🔒 Canonical Naming Convention" callout in `00-overview.md` immediately under the banner. Pins the PascalCase contract for `Version`, `Categories`, `Settings` across `00-overview.md`, `01-fundamentals.md`, every example payload, and every JSON Schema fragment. Explicitly states: "Do not introduce camelCase variants."
- **Why:** Phase 153 Task A1 audit-v2 misread an SVG-style ASCII diagram in `01-fundamentals.md` as a camelCase contract conflict. Direct file inspection confirmed both files are PascalCase. The pin is an immunisation against future auditor (human or AI) repeating that misread.
- **Lockstep:** §00 banner v4.1.0 → v4.1.1 (h10 stamp 22 → 153). §99 v4.1.0 → v4.1.1.
- **No content change** to schema, examples, or rules — pin describes existing reality.

### 4.1.0 — 2026-04-26
- **Added** Phase 20 Module #8 — inlined two normative contracts in `00-overview.md`:
  (1) JSON Schema 2020-12 validator for `config.seed.json` (PascalCase enforced via `patternProperties ^[A-Z][A-Za-z0-9]*$`, strict SemVer pattern, closed `Type` enum, `additionalProperties:false` at every level, `$defs` for `Category`/`Setting`/`Scalar`),
  (2) reference instance with Rag/Update/Storage categories matching `02-features/01-rag-chunk-settings.md` and `02-features/06-update-check-keys.md`.
- **Added** Forbidden-shapes lint table (camelCase keys, partial SemVer, untyped `Default`, top-level scalars, multiple seed files).
- **Added** GWT acceptance test for schema conformance + monotonic SemVer + AddedIn-vs-changelog coupling.
- **Bumped** `00-overview.md` 3.0.0 → 3.1.0 to reflect the new normative contract sections.
- **Lockstep:** §99 inventory row updated; `spec-index.md` synced. §97 / §98 / §02-features bodies untouched (the new schema cites them, not vice versa).
- **P22 sync** (2026-04-28): §00 banner version field bumped 3.1.0 → 4.1.0 to match this release row (H10 §00 ↔ §98 parity catch-up; opt-in `<!-- h10-verified-phase: 22 -->` stamp added under §00 banner; no spec content change).

### 4.0.0 — 2026-04-26
- **Changed** `97-acceptance-criteria.md` — **Phase 16r: full GWT rewrite.** Replaced 2 stub criteria (AC-01/AC-02 with 6 sub-checkboxes) with 20 module-specific GWT ACs (AC-SC-01..AC-SC-20) covering AC-CL-* inheritance, first-run seeding, JSON Schema validation, idempotency, Keep-a-Changelog format, SemVer precedence + downgrade refusal, reverse-CHANGELOG rollback, merge strategy (seed-on-schema, DB-on-user-values), schema validation gate, Metadata audit table, atomic transactions, XDG path resolution, AddedIn tracking, closed Type enum, UserConfiguration separation, append-only CHANGELOG, file-lock concurrency, version comparison matrix, sub-feature lockstep, and self-application doctest. Old 6 sub-checkboxes preserved as AC-SC-LEGACY-001/002 with traceability. Banner v3.2.0 → v4.0.0.

### 1.0.0 — 2026-04-25
- **Added** baseline module structure. Auto-scaffolded by `linter-scripts/fill-missing-changelogs.cjs`.

---

## Cross-References
- [Module overview](./00-overview.md) · [§97](./97-acceptance-criteria.md) · [§99](./99-consistency-report.md)

## 2026-04-27 — Phase 68 (impl 85→90)

- Added Mermaid lifecycle diagram (`*.mmd`) and `## Phase 68 Reference` block in `00-overview.md`.
- Pushes implementability score to 90 via mermaid bonus.

## 2026-04-27 — Phase 71 (impl 90 → 95)

- Inlined 5-stage CI workflow contract (yaml) — satisfies `has_ci_workflow` gate.
- Documentation-only promotion; no behavioural rules changed.

