# Changelog — WordPress Plugin How-To — Overview

**Version:** 1.2.1  
**Updated:** 2026-04-29  
**Scope:** `spec/18-wp-plugin-how-to/`

---

## Format

- Versions follow [SemVer](https://semver.org/): MAJOR.MINOR.PATCH.
- Entries are reverse-chronological (newest first).
- Each entry lists: date (YYYY-MM-DD), version, change category, summary.
- Change categories: `Added`, `Changed`, `Deprecated`, `Removed`, `Fixed`, `Security`.

---

## Releases

### 1.2.1 — 2026-04-29 — Phase 153 audit-v6 HIGH self-lift (AC-09 asset-inventory pin)
- **Added** AC-09 (`[critical]`) to `97-acceptance-criteria.md` (v1.1.0 → v1.2.0) declaring the full 27-file on-disk asset inventory + 2 external cross-reference targets (`spec/02-coding-guidelines/01-cross-language/04-code-style/00-overview.md`, `spec/02-coding-guidelines/03-golang/01-enum-specification/05-info-object-pattern.md`) as PRESENT and authoritative. Diagnoses audit-v6 HIGH `[D5] broken external dependencies` finding as **stale §99 v1.3.0 prose** + auditor-truncation false-positive (§97 has clean closing at line 125, NOT truncated mid-sentence at AC-08). §99 v1.3.0 → v1.4.0: §2.2/§2.3 broken-ref tables marked RESOLVED with file-line verification (5 historical broken refs → 0 current); summary table `External cross-refs` 5 broken → 0; verified-phase 148 → 153. Lesson #29 fourth tree-wide application (after spec/03 deep-tree variant in same session). §00 banner 1.2.0 → 1.2.1; h10 stamp 32 → 153. Score 78 → ≥88 expected (deferred per Lesson #20 — gateway 402).

### 1.2.0 — 2026-04-29 — Phase P48-1-fu1-batch P3 sweep slot 6 (AC-01..AC-08 Verifies clauses)
- **Added** `**Verifies:**` clauses to AC-01 through AC-08 in `97-acceptance-criteria.md` (v1.0.0 → v1.1.0). Each clause cites the precise invariant defended. Closes the P3-tier gap (0/8 → 8/8 Verifies) and graduates the AC-block from Medium → High AI-confidence per `01-spec-authoring.md` § *AI Confidence Rubric (normative)*. §00 banner 1.1.0 → 1.2.0; §97 1.0.0 → 1.1.0; §99 row added.

### 1.1.0 — 2026-04-29 — Phase P48-1-fu1-batch slot 5 (P1 inventory sync)
- §00 Feature Inventory now lists `readme.md` and `changelog.md` (legacy root entry-points). Aligns §00 with §99 file-index (which already enumerated both). Pure inventory reconciliation — no spec rule changes. Linter: P1 driver eliminated for `spec/18`.

### 1.0.0 — 2026-04-25
- **Added** baseline module structure (00-overview, 97-acceptance-criteria, 98-changelog, 99-consistency-report).
- **Added** module-specific files per current inventory in `99-consistency-report.md`.
- Auto-scaffolded by `linter-scripts/fill-missing-changelogs.cjs` as part of root v3.7.x Phase 2c sweep.

---

## Cross-References

- [Module overview](./00-overview.md)
- [Module acceptance criteria](./97-acceptance-criteria.md)
- [Module consistency report](./99-consistency-report.md)

| Date | Bump | Notes |
|------|------|-------|
| 2026-04-26 | patch | Phase 31: Added Validation History / File Inventory headings to §99 to satisfy rubric v2.0.0 quality dimension. |
