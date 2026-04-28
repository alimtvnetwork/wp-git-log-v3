# Changelog — Seedable Config Architecture (CW Config)

**Version:** 4.1.0
**Updated:** 2026-04-26
**Scope:** `spec/06-seedable-config-architecture/`

---

## Releases

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

