# Changelog — Update Check Mechanism

**Version:** 2.0.0
**Updated:** 2026-04-26
**Scope:** `spec/14-update/24-update-check-mechanism/`

---

## Format

- Versions follow [SemVer](https://semver.org/): MAJOR.MINOR.PATCH.
- Entries are reverse-chronological (newest first).
- Each entry lists: date (YYYY-MM-DD), version, change category, summary.
- Change categories: `Added`, `Changed`, `Deprecated`, `Removed`, `Fixed`, `Security`.

---

## Releases

### 2.0.0 — 2026-04-26

**Changed:**
- `97-acceptance-criteria.md` — **Phase 16q: full GWT rewrite.** Replaced 34 table-row criteria (A-G sections) with 20 module-specific Given/When/Then ACs (AC-UCM-01..AC-UCM-20) covering: parent §14 inheritance (AC-UCM-01), parallel discovery with 6 probes + V+5 hard stop (AC-UCM-02), response classification — 404=no-retry, malformed=logged+not-found, highest-wins (AC-UCM-03), per-probe 5s timeout + total 10s deadline (AC-UCM-04), UpdateChecker table schema with RawJson + parsed columns (AC-UCM-05), UpdateStatus enum 5 values + TINYINT PK (AC-UCM-06), failed re-check preserves prior state + atomic single-UPDATE (AC-UCM-07), JSON fallback with XDG paths + atomic tmp+rename (AC-UCM-08), sync update-check print+persist vs --async < 200ms (AC-UCM-09), --force bypass + do-update unattended + exit codes 0/1/2/3/4 (AC-UCM-10), pre-hook < 50ms + never blocks + no recursion + stderr warning (AC-UCM-11/18), error logging with file+line + 1 MiB cap + rotation (AC-UCM-12), PascalCase everywhere (AC-UCM-13), Schema Rule 11/12 compliance (AC-UCM-14), flat guard-clause no nested if (AC-UCM-15), JSON-to-SQLite migration + NewRepoUrl banner (AC-UCM-16), --async detached child < 200ms (AC-UCM-17), pre-hook interval gate + spawn + warning (AC-UCM-18), status script fetching + PascalCase JSON + combined JSON (AC-UCM-19), self-application traceability (AC-UCM-20). Old 34 table-row criteria preserved as AC-UCM-LEGACY-001..034 at end with traceability notes. Banner v1.0.0 → v2.0.0.

### 1.0.0 — 2026-04-25

- **Added** baseline module structure (00-overview, 01-fundamentals, 02-status-script-json, 03-combined-json, 04-database-schema, 05-update-checker-service, 06-cli-commands, 07-pre-command-hook, 08-error-handling, 09-json-fallback-store, 97-acceptance-criteria, 98-changelog, 99-consistency-report).
- **Added** 34 table-row acceptance criteria (A-G sections: Discovery, Persistence, CLI Behavior, Pre-Command Hook, Logging & Errors, Naming & Standards, Migration & Backwards-Compat).
- Auto-scaffolded by `linter-scripts/fill-missing-changelogs.cjs` as part of root v3.7.x Phase 2c sweep.

---

## Cross-References

- [Module overview](./00-overview.md)
- [Parent §14-update §97](../97-acceptance-criteria.md)
- [Module consistency report](./99-consistency-report.md)
