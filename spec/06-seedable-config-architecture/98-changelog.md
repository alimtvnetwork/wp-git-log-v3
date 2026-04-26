# Changelog — Seedable Config Architecture (CW Config)

**Version:** 4.0.0
**Updated:** 2026-04-26
**Scope:** `spec/06-seedable-config-architecture/`

---

## Releases

### 4.0.0 — 2026-04-26
- **Changed** `97-acceptance-criteria.md` — **Phase 16r: full GWT rewrite.** Replaced 2 stub criteria (AC-01/AC-02 with 6 sub-checkboxes) with 20 module-specific GWT ACs (AC-SC-01..AC-SC-20) covering AC-CL-* inheritance, first-run seeding, JSON Schema validation, idempotency, Keep-a-Changelog format, SemVer precedence + downgrade refusal, reverse-CHANGELOG rollback, merge strategy (seed-on-schema, DB-on-user-values), schema validation gate, Metadata audit table, atomic transactions, XDG path resolution, AddedIn tracking, closed Type enum, UserConfiguration separation, append-only CHANGELOG, file-lock concurrency, version comparison matrix, sub-feature lockstep, and self-application doctest. Old 6 sub-checkboxes preserved as AC-SC-LEGACY-001/002 with traceability. Banner v3.2.0 → v4.0.0.

### 1.0.0 — 2026-04-25
- **Added** baseline module structure. Auto-scaffolded by `linter-scripts/fill-missing-changelogs.cjs`.

---

## Cross-References
- [Module overview](./00-overview.md) · [§97](./97-acceptance-criteria.md) · [§99](./99-consistency-report.md)
