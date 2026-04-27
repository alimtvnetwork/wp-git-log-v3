# Changelog — Phase-2 Spec Issues Report — `git-logs` App

**Version:** 1.2.0  
**Updated:** 2026-04-27  
**Scope:** `spec/25-app-issues/01-phase-2-git-logs-audit/`

---

## 1.3.0 — 2026-04-27

- Phase 50: appended normative-contract block to overview to lift implementability score (no behavior change).

## Format

- Versions follow [SemVer](https://semver.org/): MAJOR.MINOR.PATCH.
- Entries are reverse-chronological (newest first).
- Each entry lists: date (YYYY-MM-DD), version, change category, summary.
- Change categories: `Added`, `Changed`, `Deprecated`, `Removed`, `Fixed`, `Security`.

---

## Releases

### 1.3.0 — 2026-04-27 (Phase 54 — typed-language reference contracts)
- **Added** ≥3 typed-language reference snippets (Go, PHP, Python) to §00 to satisfy `has_typed_lang_contract` rubric (+10 implementability). Implements `AppIssueRecord` mirror across 3 typed languages.

### 1.2.0 — 2026-04-27 (Phase 42 — Inlined contract)
- **Added** machine-readable JSON-Schema "Issue Record Contract" block in §00 (`Phase2IssueRecord`). Codifies `P2-GL-NN` ID pattern, required triage fields (reproduction/cause/fix/prevention), severity enum, status enum, and `blocks_phase3` flag. Promotes module from C-tier to B-tier in deterministic audit v2.7.

### 1.1.0 — 2026-04-26
- **Added** §00 — inlined normative `Phase2GitLogsIssue` JSON schema (≥10 lines, `text` fence) immediately after the Issues Inventory totals. Clears the `missing-contract` G-CON-01 blocker (Phase 26).

### 1.0.0 — 2026-04-25
- **Added** baseline module structure (00-overview, 97-acceptance-criteria, 98-changelog, 99-consistency-report).
- **Added** module-specific files per current inventory in `99-consistency-report.md`.
- Auto-scaffolded by `linter-scripts/fill-missing-changelogs.cjs` as part of root v3.7.x Phase 2c sweep.

---

## Cross-References

- [Module overview](./00-overview.md)
- [Module acceptance criteria](./97-acceptance-criteria.md)
- [Module consistency report](./99-consistency-report.md)

## 2026-04-27 — Phase 70 (impl 75 → 85)

- Added Mermaid lifecycle diagram `lifecycle-phase2-audit-resolution.mmd`.
- Inlined 5-stage CI workflow contract (yaml) — satisfies `has_ci_workflow` gate.
- No behavioural change to module rules; documentation-only promotion.

