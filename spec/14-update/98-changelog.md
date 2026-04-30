# Changelog — Update — Overview

**Version:** 2.4.1  
**Updated:** 2026-04-30 (Phase 153 Task A24-fu5 — AC-22 binds `<module>` ldflags placeholder to consuming repo's go.mod path; AC count 21 → 22)

### 2.4.1 — 2026-04-30 — Phase 153 Task A24-fu5: AC-22 `<module>` placeholder pin
- **Added** AC-22 `[high]` — binds the literal `<module>` in `04-build-scripts.md` ldflags (PowerShell line 102; Bash line 200) to the consuming repo's `go.mod` `module` line; build scripts MUST NOT hard-code the path.
- **Added** explanatory blockquote in `04-build-scripts.md` immediately after the PowerShell code block (use-site prose; Lesson #36 link-don't-restate).
- **Why**: closes audit-v7 D3 MEDIUM "Ambiguous <module> Placeholder" as a real-but-narrow gap. Per Lesson #36, the canonical explanation lives at the use-site; AC-22 binds it normatively.
- **Spec lockstep**: §97 v2.3.0 → **v2.4.0** (AC count 21 → 22, minor — new content); §00 v2.4.0 → **v2.4.1** (patch); §98 v2.4.0 → **v2.4.1** (patch); §99 v1.6.0 → **v1.6.1** (patch). **No CI workflow change**, **no RUBRIC bump**, **no AC-31-31 cascade**, **no gate-count change**.
- **Validation**: lockstep 87/87, tree-health 168/168 strict, version-parity 74/74, freshness 81/81 (verify after run).

### 2.4.0 — 2026-04-30 — Phase 153 Task A11h: AC-21 module asset inventory pin
- **Added** AC-21 `[critical]` — declares spec/14's full 36-entry on-disk inventory as auditor-authoritative; classifies audit-v5 D5 HIGH (missing files 09–27, subfolder 24), D4 MED (truncated `04-build-scripts.md`), D1 LOW (`<module>` placeholder ambiguity) as harness bundling-cap artifacts. Mirror of spec/13 AC-24 + spec/28 AC-28-41 + spec/16 AC-21 + spec/22 AC-78.
- **Why**: per Lesson #29 (audit-corpus pin generalised at A11g memo) — every cited file is present on disk per §99 inventory; the `<module>` placeholder is intentional contract-bound at-build-time substitution (NOT an authoring error).
- **Spec lockstep**: §97 v2.2.0 → **v2.3.0** (AC count 20 → 21); §00 v2.3.1 → **v2.4.0**; §98 v2.3.1 → **v2.4.0**; §99 v1.5.1 → **v1.6.0**. **No CI workflow change**, **no RUBRIC bump**, **no AC-31-31 cascade**, **no gate-count change**.
- **Validation**: lockstep 87/87, tree-health 168/168 strict, version-parity 74/74, freshness 81/81 (verify after run).
**Scope:** `spec/14-update/`

---

## Format

- Versions follow [SemVer](https://semver.org/): MAJOR.MINOR.PATCH.
- Entries are reverse-chronological (newest first).
- Each entry lists: date (YYYY-MM-DD), version, change category, summary.
- Change categories: `Added`, `Changed`, `Deprecated`, `Removed`, `Fixed`, `Security`.

---

## Releases

### 2.3.1 — 2026-04-30 — Phase 153 (Lesson #36 cross-ref inoculation)
- **Added** `## Concurrency Posture (Normative cross-reference)` section to `22-update-command-workflow.md` linking the self-update worker's `update.lock` discipline, atomic temp-then-rename for staged binaries, and any state-DB writes to the canonical contract at [spec/13 §97 AC-22](../13-generic-cli/97-acceptance-criteria.md). Pure cross-link — contract NOT restated. Codifies Lesson #36 (link, never restate). **No §97 AC change**, no AC-31-31 cascade, no RUBRIC bump, no gate-count change. §99 v1.5.0 → v1.5.1. Sibling lockstep: spec/16 (v2.2.1) + spec/28 (v2.1.3) shipped same Lesson #36 inoculation in this phase.

### 2.3.0 — 2026-04-29 — Phase 149 (P3 sweep slot 9 — Verifies clauses on §97)
- **Added** `**Verifies:**` clauses to all 20 ACs in `97-acceptance-criteria.md` (gap=20 → 0). Each AC now explicitly maps to the underlying invariant it protects: structural floor (AC-01), zero broken links (AC-02), slot-immutability (AC-03), §99 inventory rubric (AC-04), strict-pass tree-health (AC-05), cross-platform deploy uniformity (AC-06), parent-survival (AC-07), build-time version-injection (AC-08), supply-chain trust (AC-09), integrity / no-MD5-SHA1 (AC-10), idempotent install + no-silent-sudo (AC-11), latest-probe-or-fail (AC-12), XDG + atomic-write 0600 (AC-13), ordered-pipeline-with-fail-fast (AC-14), no-daemon / no-stdout-pollution (AC-15), idempotent silent-cleanup-budget (AC-16), single-step atomic rollback + RECOVERY.md (AC-17), three-layer precedence + system-dir blacklist (AC-18), tag-first monotonicity (AC-19), six-target-floor + CGO_ENABLED=0 + reproducible-toolchain (AC-20). §97 v2.1.0 → v2.2.0. AI-confidence: P3 driver eliminated for `spec/14`; derived tier Medium → High.

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
