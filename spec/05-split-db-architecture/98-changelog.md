# Changelog — Split Database Architecture

**Version:** 4.0.0
**Updated:** 2026-04-26
**Scope:** `spec/05-split-db-architecture/`

---

## Releases

### 4.0.0 — 2026-04-26
- **Changed** `97-acceptance-criteria.md` — **Phase 16r: full GWT rewrite.** Replaced 2 stub criteria (AC-01/AC-02 with 6 sub-checkboxes) with 20 module-specific GWT ACs (AC-SD-01..AC-SD-20) covering AC-CL-* inheritance, mandatory PascalCase (no underscores), 3 hierarchy layers (2/3/4), Root DB scope limits, per-item filename regex `^[0-9]{3}-[a-z0-9-]+\.db$`, ATTACH-only cross-DB queries, per-DB migration independence, intra-DB-only FKs, ZIP+manifest+SHA-256 backup, all-or-nothing restore with .bak rollback, MaxOpenHandles+LRU+IdleCloseSec connection pool, mandatory WAL mode, lazy lifecycle with prune-recovery, BEGIN IMMEDIATE atomic writes, SchemaVersion bumping, read-only mode for archives, quota enforcement at 80%/100%, wipe-tree-first-then-root order, sub-feature lockstep, and self-application DDL doctest. Old 6 sub-checkboxes preserved as AC-SD-LEGACY-001/002 with traceability. Banner v3.2.0 → v4.0.0.

### 1.0.0 — 2026-04-25
- **Added** baseline module structure. Auto-scaffolded by `linter-scripts/fill-missing-changelogs.cjs`.

---

## Cross-References
- [Module overview](./00-overview.md) · [§97](./97-acceptance-criteria.md) · [§99](./99-consistency-report.md)
