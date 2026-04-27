# Changelog — PowerShell Integration for Project Runner

**Version:** 1.1.0  
**Updated:** 2026-04-27  
**Scope:** `spec/11-powershell-integration/`

---

## Format

- Versions follow [SemVer](https://semver.org/): MAJOR.MINOR.PATCH.
- Entries are reverse-chronological (newest first).
- Each entry lists: date (YYYY-MM-DD), version, change category, summary.
- Change categories: `Added`, `Changed`, `Deprecated`, `Removed`, `Fixed`, `Security`.

---

## Releases

### 1.1.0 — 2026-04-27
- **Phase 39c — Added** `07-runner-interface.md` defining the authoritative PowerShell `Param()` block, exit-code table (0/2/3/4/5/10/11/12/20/30/40/99), pinned dependency toolchain (Go 1.22, Node 20.11, pnpm 9, Git 2.40) with provider priority, and JSON-Schema reference. Closes audit findings *CRITICAL — Missing Interface Definition (JSON & CLI)* and *HIGH — Underspecified Dependency Management*.
- **Changed** §97 v1.0.0 → v2.0.0: replaced 5 meta-ACs with 10 functional GWT ACs (AC-RUN-01..10) plus 3 spec-hygiene ACs. Closes audit finding *HIGH — Non-Functional Acceptance Criteria*.
- §00 banner v2.25.0 → v2.26.0; §99 lockstep update.

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
| 2026-04-26 | minor | Phase 27b: Added `kind: future-spec` frontmatter + Drift Acknowledgment section. Module now exempt from drift audit findings (implementation lives in downstream repos). |

## 2026-04-27 — Phase 57 impl-sweep

- Phase 57: appended Go/PHP/Python PsInvocation validator references to satisfy `has_typed_lang_contract` rubric (impl 65 → 75).

## 2026-04-27 — Phase 70 (impl 75 → 85)

- Added Mermaid lifecycle diagram `lifecycle-powershell-bootstrap-flow.mmd`.
- Inlined 5-stage CI workflow contract (yaml) — satisfies `has_ci_workflow` gate.
- No behavioural change to module rules; documentation-only promotion.

