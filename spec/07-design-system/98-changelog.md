# Changelog — AI-Adaptable Design System

**Version:** 1.1.0  
**Updated:** 2026-04-26  
**Scope:** `spec/07-design-system/`

---

## Format

- Versions follow [SemVer](https://semver.org/): MAJOR.MINOR.PATCH.
- Entries are reverse-chronological (newest first).
- Each entry lists: date (YYYY-MM-DD), version, change category, summary.
- Change categories: `Added`, `Changed`, `Deprecated`, `Removed`, `Fixed`, `Security`.

---

## Releases

### 1.1.0 — 2026-04-26
- **Phase 15a — Convert §97 Theme & Variables section from table-row to GWT format.** Per `mem://specs/full-tree-audit-v4.md` deepening backlog + Phase 14 close-out. AC IDs unchanged (still AC-001..AC-034 sequential, count = 34). The 6 ACs in the **Theme & Variables** section converted from one-row-per-AC table format to full Given/When/Then subsections with concrete contracts + cross-refs to `01-design-principles.md`, `02-theme-variable-architecture.md`, `src/index.css`, `tailwind.config.ts`, the theme provider, WCAG 2.1 §1.4.3/§1.4.11.
- **AC-001** (no hardcoded colors) — exhaustive forbidden-pattern list (hex, rgb/rgba, named CSS colors, literal Tailwind utilities), explicit exception allowlist (token definitions, third-party SVG with `currentColor` wrapper).
- **AC-002** (`hsl(var(--token))` discipline) — bare-triplet token format rule (`--primary: 217 91% 60%;` NOT `hsl(...)`-wrapped), alpha-composition syntax, prohibition on inlining matching raw HSL.
- **AC-003** (`--primary` cascade) — explicit list of all surfaces that MUST update, paired-foreground manual-update obligation per AC-006, gradient-token cascade.
- **AC-004** (`:root` + `.dark` block parity) — set-equality rule, mode-invariant tokens MUST still appear in both, `.dark` MUST be applied to `<html>`/`<body>` not nested.
- **AC-005** (toggle correctness) — ≤16ms perceptible delay, FOUC prevention via synchronous pre-paint script in `index.html`, `localStorage` persistence, `prefers-color-scheme` first-visit honoring.
- **AC-006** (WCAG AA contrast) — 4.5:1 normal / 3:1 large text, BOTH modes independently, hover/focus/disabled inheritance, 3:1 non-text threshold per WCAG §1.4.11.
- Added **Format note** at top of §97 explaining that AC-007..AC-034 remain in table format pending Phase 15b..15e — IDs are stable across formats so tooling that scrapes by ID continues to work.
- Banner v1.0.0 → v1.1.0; lockstep §97 v3.2.0 → v3.3.0 + §99 v3.2.0 → v3.3.0 + spec-index updated.

### 1.0.0 — 2026-04-25
- **Added** baseline module structure (00-overview, 97-acceptance-criteria, 98-changelog, 99-consistency-report).
- **Added** module-specific files per current inventory in `99-consistency-report.md`.
- Auto-scaffolded by `linter-scripts/fill-missing-changelogs.cjs` as part of root v3.7.x Phase 2c sweep.

---

## Cross-References

- [Module overview](./00-overview.md)
- [Module acceptance criteria](./97-acceptance-criteria.md)
- [Module consistency report](./99-consistency-report.md)
