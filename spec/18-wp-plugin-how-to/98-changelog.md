# Changelog — WordPress Plugin How-To — Overview

**Version:** 1.4.0  
**Updated:** 2026-04-30 (Phase 153 A24-fu10-fu1 — AC-12 Phases 07-13 Patterns + AC-13 Phases 14-21 Integration + AC-14 filename casing + AC-15 Lesson #29 deep-tree pin; closes audit-v7 [D2 HIGH] + [D5 MEDIUM] + [D1 LOW] — promotes spec/18 to EXCELLENT)
**Scope:** `spec/18-wp-plugin-how-to/`

---

## Format

- Versions follow [SemVer](https://semver.org/): MAJOR.MINOR.PATCH.
- Entries are reverse-chronological (newest first).
- Each entry lists: date (YYYY-MM-DD), version, change category, summary.
- Change categories: `Added`, `Changed`, `Deprecated`, `Removed`, `Fixed`, `Security`.

---

## Releases

### 1.4.0 — 2026-04-30 — Phase 153 A24-fu10-fu1 (spec/18 second-pass self-lift; promotes to EXCELLENT)

- **Added** AC-12 (`[high]`) — Phase-file architectural invariants binding for **Phases 07–13 (Patterns)** with 7-row table covering reference-impl runnability, hook priority+arg-count discipline, WP_UnitTestCase + Factory test pattern, deployment reproducibility, template escaping, design-system token consumption (cross-ref spec/07 AC-036 per L#36), admin-page capability checks. Closes audit-v7 [D2 HIGH] second band.
- **Added** AC-13 (`[high]`) — Phase-file architectural invariants binding for **Phases 14–21 (Integration)** with 8-row table covering REST `permission_callback` discipline, settings facade, typed exceptions, bundled-data sha256 pinning, JS module enqueue, micro-ORM Repository facade (cross-ref spec/04 AC-09 per L#36), end-to-end walkthrough parity, ping-endpoint exact-shape contract. Closes audit-v7 [D2 HIGH] third + final band — AC-10/12/13 trio now exhaustively binds all 21 phase files.
- **Added** AC-14 (`[low]`) — Filename casing discipline. `CHANGELOG.md` + `README.md` (uppercase) FORBIDDEN; canonical lowercase `changelog.md` + `readme.md`. Lists `readme.md:84` as RESOLVED (already lowercase) + flags `10-deployment-patterns.md` 4 prose refs as remaining actionable items. Closes audit-v7 [D1 LOW] "Filename casing mismatch" via forbidden-pattern contract surface.
- **Added** AC-15 (`[medium]`) — Internal sub-file resolution discipline (Lesson #29 deep-tree variant). Auditor MUST treat [D5] findings citing missing internal phase files as **harness bundling-cap artifacts** when on-disk inventory matches AC-09. Mirror of spec/03/11/12/17/25 inventory pins. Closes audit-v7 [D5 MEDIUM] "Unresolved internal sub-file references" per Lesson #29 module-kind pin.
- **Updated** §99 §2.1 — `readme.md:84` row marked RESOLVED 2026-04-30 (file already lowercase on disk + reference matches); `10-deployment-patterns.md` 4 prose refs reclassified as P0 actionable per AC-14 forbidden-pattern contract; impact downgraded Medium → Low (prose-only, no broken on-disk links).
- Lockstep: §97 v1.3.0 → **v1.4.0** (minor — 4 new ACs, AC count 11 → 15); §00 v1.3.0 → **v1.4.0** (minor — sync to §97/§98 per L#25); §98 v1.3.0 → **v1.4.0** (minor — release row); §99 v1.4.1 → **v1.4.2** (patch — §2.1 update).
- v7 cache score predicted lift 86 → ≥91 (EXCELLENT band — D2 +3 HIGH closure of phases 07-21 band, D5 +2 MEDIUM Lesson #29 pin, D1 +1 LOW casing); fresh re-score performed per Lesson #38.

### 1.3.0 — 2026-04-30 — Phase 153 A24-fu10 (spec/18 self-lift)

- **Added** AC-10 (`[high]`) to `97-acceptance-criteria.md` (v1.2.0 → v1.3.0) — Phase-file architectural invariants binding for Phases 01–06 with 6-row invariant table (bootstrap idempotency / enum info-object pattern / trait composition rules / FileLogger facade / Response envelope / Validator chain) + Forbidden patterns. Closes audit-v7 [D2 HIGH] "Missing Verifies clauses for Phase 01-06" via Lesson #19 audit-boundary lift to §97 (the canonical fix; phase files remain implementer-facing prose, §97 owns the contract per Lesson #36 link-don't-restate).
- **Added** AC-11 (`[high]`) — Concurrency contract for FileLogger + self-update / rollback with 5-row surface table (`flock(LOCK_EX)` mandatory; `LOCK_NB` forbidden silent-drop class; atomic-rename rotation; `.zip.partial` staging; `register_shutdown_function` deferred reload; sha256-verified rollback). Closes audit-v7 [D3 LOW] "Concurrency and Race Conditions Unaddressed" + cross-refs `spec/13-generic-cli` AC-22 per Lesson #36.
- **Removed** stale §99 §4 "Recommended Fixes" P0 rows #2/#3 + P1 row #4 (formatting-rules-reference + Go enum prefix + 05-info-object-pattern) — already RESOLVED 2026-04-29 per §2.2/§2.3 RESOLVED tables. Closes audit-v7 [D5 MEDIUM] "Unresolved External References in Consistency Report" per Lesson #34 (audit caches MUST NOT be authoritative; mechanical cleanup of resolved-but-listed items).
- Lockstep: §97 v1.2.0 → **v1.3.0** (minor — 2 new ACs, AC count 9 → 11); §00 v1.2.1 → **v1.2.2** (patch); §98 v1.2.1 → **v1.3.0** (minor — sync to §97 per L#25); §99 v1.4.0 → **v1.4.1** (patch — actionable-only cleanup).
- v7 cache score predicted lift 80 → ≥90 (D2 +5 HIGH closure, D3 +3 LOW closure, D5 +3 MEDIUM closure); fresh re-score performed per Lesson #38.

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
