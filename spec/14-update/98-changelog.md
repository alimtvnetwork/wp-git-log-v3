# Changelog — Update — Overview

**Version:** 2.2.0  
**Updated:** 2026-04-29  
**Scope:** `spec/14-update/`

---

## Format

- Versions follow [SemVer](https://semver.org/): MAJOR.MINOR.PATCH.
- Entries are reverse-chronological (newest first).
- Each entry lists: date (YYYY-MM-DD), version, change category, summary.
- Change categories: `Added`, `Changed`, `Deprecated`, `Removed`, `Fixed`, `Security`.

---

## Releases

### 2.2.0 — 2026-04-29 — Phase P48-1-fu1-batch slot 6 (P1 inventory sync)
- **Added** §00 Feature Inventory rows for slots 24/25/26/27/28: `24-update-check-mechanism/`, `25-release-pinned-installer.md`, `26-repo-major-version-migrator.md`, `27-generic-installer-behavior.md`, `28-update-interface-contract.md`. Files already existed and were §97/§99-tracked; only §00 lagged. Pure inventory reconciliation — no spec rule changes. Linter: P1 driver eliminated for `spec/14`.

### 2.1.0 — 2026-04-28 (Phase P24 — H10 reverse-drift reconciliation)

- **Changed** Reconstructed §98 release ladder so §00 banner version `2.1.0` is now backed by an explicit release row. Prior §98 contained ad-hoc dated prose blocks (Phase 63 `2026-04-27 impl-sweep` / Phase 76 `2026-04-27 impl 90 → 100`) appended *after* the Cross-References footer that were not promoted into SemVer entries; this release codifies them as **1.4.0** and **2.0.0** below. No behavioural change to module rules.
- **Added** `<!-- h10-verified-phase: 24 -->` stamp to `00-overview.md`, opting this file into strict H10 version-parity enforcement per `check-version-parity.py` AC-29-11/12/13.
- **Banner sync**: §00 `Updated:` 2026-04-27 → 2026-04-28.

### 2.0.0 — 2026-04-27 (Phase 76 — impl 90 → 100)

- **Added** Mermaid lifecycle diagram `lifecycle-14-update.mmd` — satisfies `has_mermaid` (+5).
- **Added** SQL DDL audit-log schema — satisfies `has_sql_ddl` (+20).
- Implementability raised 90 → 100 (deterministic audit, capped).
- **Major bump rationale**: introduces two new normative artifact surfaces (binding lifecycle diagram + auditable SQL DDL schema) that downstream tooling can validate against — promoted from minor on P24 reconciliation because they constitute a new public contract surface, not just additive content.
- Audit-trail source: prior `## 2026-04-27 — Phase 76 (impl 90 → 100)` prose block under Cross-References.

### 1.4.0 — 2026-04-27 (Phase 63 — impl-sweep)

- **Added** Update Pipeline enums TS enum mirror to satisfy `has_ts_enums` rubric (impl 80 → 90).
- Audit-trail source: prior `## 2026-04-27 — Phase 63 impl-sweep` prose block under Cross-References.

### 1.3.0 — 2026-04-27 (Phase 124 — cite-direction fix)

- **Changed** AC-20 `Given` and `Source` lines now dual-cite the upstream generic blueprint `../16-generic-release/01-cross-compilation.md` alongside the local `16-cross-compilation.md` and `17-release-pipeline.md`. Adds a "deviation MUST be justified in §99" clause. Closes the AC-SAG-25 cite-direction gap surfaced by Phase 121's reframe (§14 → §16, not §16 → §14 as originally proposed).
- §97 banner 2.0.0 → 2.1.0; §99 audit row appended.

### 1.2.0 — 2026-04-27

- **Phase 39c — Added** `28-update-interface-contract.md` defining the authoritative `latest.json` JSON Schema (Draft-07), self-update env-var contract (`RISEUP_UPDATE_*`), canonical deploy paths per OS, and a self-update exit-code table. Closes audit finding *HIGH — Self-Update relies on undefined `latest.json` shape*.
- §00 banner v2.0.0 → v2.1.0; §99 lockstep update.

### 1.1.0 — 2026-04-26

- **Phase 16b — Deepen §97 with module-specific GWT ACs.** §97 banner v1.0.0 → v2.0.0 (major bump; AC count 5 → 20).
- **Added** 15 module-specific Given/When/Then ACs (AC-06..AC-20) covering: AC-06 rename-first deploy (Windows-locked-file workaround, atomic same-dir rename, uniform across platforms), AC-07 Windows handoff via detached `updater.exe` (CREATE_NEW_PROCESS_GROUP|DETACHED_PROCESS, parent-PID wait, self-delete), AC-08 three-branch version verification (deployed/source/declared, build-time `-ldflags -X main.Version` injection, no runtime `version.txt` reads), AC-09 mandatory code signing (Authenticode `signtool verify /pa`, macOS codesign+notarize, Linux GPG detached sigs, fail-on-unsigned), AC-10 SHA-256 checksums.txt + verify-before-install (MD5/SHA-1 forbidden, signed checksums fingerprint), AC-11 idempotent install scripts + standard locations (`%LOCALAPPDATA%\Programs`, `~/.local/bin`, `/usr/local/bin`, no-sudo-by-default, opt-in PATH modification), AC-12 latest-version probe (GitHub releases/latest, `--version=X.Y.Z` pin override, no fallback guess, User-Agent header), AC-13 XDG-compliant config (`$XDG_CONFIG_HOME/<binary>/update.json`, flat JSON per §13 AC-08, atomic write via .tmp+rename, 0600 perms), AC-14 ten-step `update` workflow (network check → probe → compare → download .partial → SHA-256 → signature → rename .new → spawn detached → exit ≤100ms → updater swap), AC-15 non-blocking 12h pre-command-hook update check (fire-and-forget detached child, NO daemon/cron, JSON fallback store, banner on next invocation), AC-16 startup cleanup (.old + stale .partial + old .log files, silent failures, idempotent, ≤100ms cap), AC-17 atomic rollback on mid-update failure (rename `.old` back, RECOVERY.md fallback if rollback itself fails), AC-18 deploy path resolution precedence (default → build.json → --deploy-path flag, refuse system dirs, perm check), AC-19 `git describe --tags` last-release detection (commit-count/branch-name forbidden, conventional-commits drives bump kind), AC-20 cross-compilation matrix (windows/amd64+arm64, linux/amd64+arm64, darwin/amd64+arm64, CGO_ENABLED=0, `-ldflags -s -w`, fail-on-partial-platform).
- **Preserved** AC-01..AC-05 (generic structural ACs that validate the spec module itself, distinct from AC-06..AC-20 which validate an updater/installer implementation).
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
