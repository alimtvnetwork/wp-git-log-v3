# Changelog — Generic CLI Creation Guidelines — Overview

**Version:** 1.1.0  
**Updated:** 2026-04-26  
**Scope:** `spec/13-generic-cli/`

---

## Format

- Versions follow [SemVer](https://semver.org/): MAJOR.MINOR.PATCH.
- Entries are reverse-chronological (newest first).
- Each entry lists: date (YYYY-MM-DD), version, change category, summary.
- Change categories: `Added`, `Changed`, `Deprecated`, `Removed`, `Fixed`, `Security`.

---

## Releases

### 1.1.0 — 2026-04-26
- **Phase 16a — Deepen §97 with module-specific GWT ACs.** §97 banner v1.0.0 → v2.0.0 (major bump because the AC count more than tripled — 5 → 20 — and the new ACs validate a different surface (the CLI implementation) than the original 5 (the spec module structure)).
- **Added** 15 module-specific Given/When/Then ACs (AC-06..AC-20) covering: AC-06 single-switch subcommand dispatch (no cobra/urfave), AC-07 kebab-case per-command flagsets + flag-name constants, AC-08 three-layer config precedence (defaults → JSON file → flags) + flat-JSON-only contract, AC-09 pluggable `--format` (terminal/json/csv/markdown) + TTY-detect color suppression, AC-10 fixed five-value exit code contract (0/1/2/3/4) + stderr discipline, AC-11 50-line function / 400-line file / camelCase-PascalCase-only / no-magic-strings code style, AC-12 compile-time embedded help with `//go:embed` + interception before flag parse, AC-13 centralized `pkg/dateformat/` with three layouts (display, filename, ISO8601), AC-14 `pkg/constants/` category split (flags/commands/paths/formats/exit) + `<Category><Name>` naming, AC-15 `--verbose` to stderr + secret redaction + zero-overhead when disabled, AC-16 progress to stderr + 500ms appearance + non-TTY suppression + clear-on-complete, AC-17 batch `exec` exit-4-on-partial + `[item]` prefix + deterministic parallel ordering, AC-18 generated-not-handwritten shell completion (bash/zsh/powershell/fish) + hidden `__complete` provider, AC-19 fixed terminal palette (green/red/yellow/cyan/gray) + box-drawing headers + ASCII fallback, AC-20 post-install `doctor` check + interactive shell-profile injection + `--json` mode.
- **Preserved** AC-01..AC-05 (generic structural ACs that validate the spec module itself, distinct from AC-06..AC-20 which validate a CLI implementation).
- Lockstep §99 v1.0.0 → v1.1.0; spec-index updated.

### 1.0.0 — 2026-04-25
- **Added** baseline module structure (00-overview, 97-acceptance-criteria, 98-changelog, 99-consistency-report).
- **Added** module-specific files per current inventory in `99-consistency-report.md`.
- Auto-scaffolded by `linter-scripts/fill-missing-changelogs.cjs` as part of root v3.7.x Phase 2c sweep.

---

## Cross-References

- [Module overview](./00-overview.md)
- [Module acceptance criteria](./97-acceptance-criteria.md)
- [Module consistency report](./99-consistency-report.md)

## 2026-04-27 — Phase 68 (impl 85→90)

- Added Mermaid lifecycle diagram (`*.mmd`) and `## Phase 68 Reference` block in `00-overview.md`.
- Pushes implementability score to 90 via mermaid bonus.
