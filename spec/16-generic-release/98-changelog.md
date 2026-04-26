# Changelog — Generic Release Pipeline Specification

**Version:** 2.0.0  
**Updated:** 2026-04-26  
**Scope:** `spec/16-generic-release/`

---

## Format

- Versions follow [SemVer](https://semver.org/): MAJOR.MINOR.PATCH.
- Entries are reverse-chronological (newest first).
- Each entry lists: date (YYYY-MM-DD), version, change category, summary.
- Change categories: `Added`, `Changed`, `Deprecated`, `Removed`, `Fixed`, `Security`.

---

## Releases

### 2.0.0 — 2026-04-26
- **Phase 16d-ii — §97 Depth Pass.** Per `mem://specs/full-tree-audit-v4.md` open backlog item ("deepen scaffolded AC content for high-traffic modules"), expanded `97-acceptance-criteria.md` from 5 generic scaffold ACs to **20 module-specific GWT ACs** (AC-01..AC-05 retained as universal scaffold floor; AC-06..AC-20 added). Each new AC averages 1500-2400 chars with explicit `**Given** / **When** / **Then**` triplet and a `**Verifies:**` cross-ref line. Harmonized with §15 AC-12/13/16/18 — `Verifies:` lines explicitly cite where overlap exists so the contracts stay in lockstep (this is the upstream generic blueprint; §15 is the concrete consumer).
- **Doc-only — no schema bump, no DDL change, no pipeline/installer code changes.** This is a contract clarification, not a behavior change.
- **§97 acceptance-criteria changes (AC-06..AC-20):** (a) **AC-06 Cross-compilation matrix** — 6 minimum target triples (`linux/amd64`, `linux/arm64`, `darwin/amd64`, `darwin/arm64`, `windows/amd64`, `windows/arm64`), `CGO_ENABLED=0` static linking enforced (`ldd`/`otool` checks), build-once + tool-version-pin discipline, `.exe` suffix for Windows only, missing target fails workflow before publish; (b) **AC-07 Tag-driven workflow** — triggers ONLY on `v*` tag pushes (no `main` push, no PR, no schedule), pinned to tag's commit SHA not branch HEAD, branch-agnostic, re-tag MUST NOT republish; (c) **AC-08 Atomic publication** — draft → checksum-verify-roundtrip → promote in single API call, verification failure deletes draft, published releases immutable, no auto-publish to package registries (npm/PyPI/Homebrew/Scoop/Chocolatey); (d) **AC-09 Asset naming** — `<binary>-<version>-<os>-<arch>.<ext>` (`.tar.gz` POSIX / `.zip` Windows), flat archive structure, `<version>` includes leading `v`, Go runtime tokens (`linux`/`darwin`/`windows` + `amd64`/`arm64`), `0755` mode, no symlinks; (e) **AC-10 release-metadata.json schema** — required keys `version`/`commit`/`built_at`/`targets`/`assets`, version derived from `${{ github.ref_name }}` via `-ldflags -X main.Version`, `built_at` is workflow start time, `assets` superset of `checksums.txt`, unknown keys allowed for forward-compat; (f) **AC-11 Version-pinned installers** — version embedded as literal constant (NOT `/releases/latest` probe), spec-first ordering (spec download+verify+extract → then binaries), `latest` alias forbidden even as fallback, deterministic re-generation; (g) **AC-12 SHA-256 protocol** — `sha256sum`-compat format (two-space separator, lowercase 64-char hex), every asset checksummed except `checksums.txt` itself, installers MUST verify before extract (supply-chain protection), generic spec doesn't mandate signing tool but allows detached `.sig` asset; (h) **AC-13 Post-install PATH activation** — detect `$SHELL`/`$PROFILE`, idempotent fenced-marker block in rc file OR clear manual-append message, no system-wide changes without `--system`, manual-append exits `0` not `1`, `doctor` sub-command for self-heal; (i) **AC-14 Terminal output discipline** — progress to stderr (stdout for machine-readable only), `NO_COLOR` honored, color suppressed when stderr non-TTY, verbose gated, error context actionable; (j) **AC-15 Known-issues ledger** — `REL-NNN` IDs, RCA + prevention rule REQUIRED in same fix PR, prevention rules promote to sibling specs when generalizable, duplicates link to canonical, reverse-chronological; (k) **AC-16 Mermaid diagrams** — both `.mmd` files parseable by `mermaid-cli`, unified-architecture covers all six referenced specs, re-validate on flow-affecting edit, `.mmd` source under `images/` (NOT embedded fences), `.svg`/`.png` artifacts regen-only-from-`.mmd`; (l) **AC-17 Generic vs concrete separation** — placeholder convention (`<binary>`/`<repo>`/`<version>`/`<module>`) preserved, consumer ACs MUST cite generic ACs, deviations require justification, generic spec stays out of language-specific concerns; (m) **AC-18 Bash+PowerShell installer parity** — functionally equivalent installs, OS-conventional defaults (`$HOME/.local/bin` POSIX vs `$env:LOCALAPPDATA\Programs\<binary>` Windows), shared 6-flag surface (`--version`, `--dest`, `--verify-only`, `--no-activate`, `--verbose`, `--help`), partial-parity forbidden, OS-mismatch detection (no Linux binaries on Windows via Git Bash), third installer flavor forbidden; (n) **AC-19 Cross-refs intact + bi-directional** — links to §12/§13 resolve, content stays consistent, back-references encouraged, lockstep maintenance on convention changes; (o) **AC-20 Sibling files versioned + content-aligned** — each has H1+banner, `**Verifies:**` MUST cite both §00 and the relevant sibling, §00 wins on conflict, §08 marked authoritative wins over §03 on installer questions.
- **Banner v1.0.0 → v2.0.0** (major bump per the "depth materially better" rule — 4× AC count + each new AC adds explicit verification cross-refs that change reviewer posture; harmonization with §15 is structural).
- **Lockstep:** §99 banner v1.0.0 → v2.0.0; spec-index entries for §97 + §98 + §99 bumped to 2.0.0; phased-roadmap Phase 16d-ii marked done.
- **Scope discipline (Phase 16d-ii ONLY):** §00 / §01 / §02 / §03 / §04 / §05 / §06 / §07 / §08 untouched (the ACs cite existing normative content from those sections, NOT new contracts). Sibling contract files remain at v1.0.0 — they were never the target. §15 AC-12/13/16/18 referenced but NOT modified — harmonization is one-directional citation, not edit propagation.

### 1.0.0 — 2026-04-25
- **Added** baseline module structure (00-overview, 97-acceptance-criteria, 98-changelog, 99-consistency-report).
- **Added** module-specific files per current inventory in `99-consistency-report.md`.
- Auto-scaffolded by `linter-scripts/fill-missing-changelogs.cjs` as part of root v3.7.x Phase 2c sweep.

---

## Cross-References

- [Module overview](./00-overview.md)
- [Module acceptance criteria](./97-acceptance-criteria.md)
- [Module consistency report](./99-consistency-report.md)
